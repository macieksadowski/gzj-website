import { AfterViewInit, Component, ElementRef, inject, OnDestroy, OnInit, ViewChild } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { CommonModule } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { DashboardBackendService } from '../services/dashboardbackend.service';
import { EventContractsEditorComponent } from "../event-contracts-editor/event-contracts-editor.component";
import { EventDetailsEditorComponent } from '../event-details-editor/event-details-editor.component';
import { EventSetlistEditorComponent } from '../event-setlist-editor/event-setlist-editor.component';
import { ConfirmDialogService } from '../services/confirmdialog.service';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import {MatSnackBar} from '@angular/material/snack-bar';
import { Title } from '@angular/platform-browser';
import { MatTableModule, MatTableDataSource } from '@angular/material/table';
import { MatCardModule } from '@angular/material/card';
import { MatSortModule, MatSort } from '@angular/material/sort';
import { MatPaginatorModule, MatPaginator } from '@angular/material/paginator';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { parseCurrencyAmount } from '../shared/currencyFormat';
import { FabNavigationComponent } from '../shared/fab-navigation.component';
import { EMPTY, Subscription, catchError, distinctUntilChanged, switchMap, tap } from 'rxjs';

@Component({
  selector: 'dashboard-event-summary',
  imports: [CommonModule, MatButtonModule, MatIconModule, MatTableModule, MatCardModule, MatSortModule, MatPaginatorModule, MatProgressSpinnerModule],
  templateUrl: './event-summary.component.html',
  styleUrl: './event-summary.component.scss'
})
export class EventSummaryComponent implements OnInit, AfterViewInit, OnDestroy {
  event!: any;
  eventService: DashboardBackendService;
  dialog: MatDialog;
  private routeSub?: Subscription;
  private eventsIdsSub?: Subscription;
  private titleResizeObserver?: ResizeObserver;
  private eventTitleElement?: HTMLElement;
  private isFittingTitle = false;
  private readonly titleMaxFontSizePx = 48;
  private readonly titleMinFontSizePx = 14;
  private allEventIds: number[] = [];
  currentEventId: number | null = null;
  previousEventId: number | null = null;
  nextEventId: number | null = null;
  loadingEvent = true;
  generatingZaiksReport = false;

  transactionsDataSource = new MatTableDataSource<any>();

  contractsDataSource = new MatTableDataSource<any>();

  contractsColumns: string[] = ['member', 'amount', 'type'];
  transactionsColumns: string[] = ['amount', 'description', 'category'];
  setlistColumns: string[] = ['order', 'song'];
  readonly fabNavigationComponent = FabNavigationComponent;

  getSetlistDisplayOrder(entry: any, index: number): number {
    const rawOrder = Number(entry?.order);
    if (Number.isFinite(rawOrder) && rawOrder >= 0) {
      return rawOrder + 1;
    }

    return index + 1;
  }

  private getContractsSaveErrorMessage(err: any): string {
    const apiDetails = err?.error?.details;
    const apiError = err?.error?.error;
    const apiMessage = err?.error?.message;

    if (typeof apiDetails === 'string' && apiDetails.length > 0) {
      return `Błąd zapisu umów: ${apiDetails}`;
    }

    if (typeof apiError === 'string' && apiError.length > 0) {
      return `Błąd zapisu umów: ${apiError}`;
    }

    if (typeof apiMessage === 'string' && apiMessage.length > 0) {
      return `Błąd zapisu umów: ${apiMessage}`;
    }

    const validationErrors = err?.error?.errors;
    if (validationErrors && typeof validationErrors === 'object') {
      const firstEntry = Object.values(validationErrors)[0] as unknown;
      if (Array.isArray(firstEntry) && firstEntry.length > 0) {
        return `Błąd zapisu umów: ${firstEntry[0]}`;
      }
      if (typeof firstEntry === 'string' && firstEntry.length > 0) {
        return `Błąd zapisu umów: ${firstEntry}`;
      }
    }

    return 'Błąd zapisu umów. Sprawdź poprawność danych (osoba, kwota, typ umowy).';
  }

  @ViewChild('transactionsSort')
  set transactionsSort(sort: MatSort | undefined) {
    if (sort) {
      this.transactionsDataSource.sort = sort;
    }
  }

  @ViewChild('transactionsPaginator')
  set transactionsPaginator(paginator: MatPaginator | undefined) {
    if (paginator) {
      this.transactionsDataSource.paginator = paginator;
    }
  }

  @ViewChild('eventTitle')
  set eventTitle(elementRef: ElementRef<HTMLElement> | undefined) {
    this.eventTitleElement = elementRef?.nativeElement;
    if (this.eventTitleElement) {
      this.initTitleResizeObserver();
      this.scheduleTitleFit();
    }
  }

  constructor(
    private route: ActivatedRoute,
    private titleService: Title,
    private router: Router,
    private confirmDialog: ConfirmDialogService,
    private snackbar: MatSnackBar
  ) {
    this.eventService = inject(DashboardBackendService);
    this.dialog = inject(MatDialog);
   }

  ngOnInit(): void {
    this.eventsIdsSub = this.eventService.getAllEventIds().subscribe((eventIds) => {
        this.allEventIds = eventIds
          .map((entry: any) => Number(entry))
        .filter((id: number) => Number.isFinite(id))
        .sort((a: number, b: number) => a - b);
      this.updateNeighbourEventIds();
    });

    this.routeSub = this.route.paramMap.pipe(
      tap(() => {
        this.loadingEvent = true;
      }),
      switchMap((params) => {
        const eventId = params.get('id');
        this.currentEventId = eventId ? Number(eventId) : null;
        this.updateNeighbourEventIds();

        if (!eventId) {
          this.loadingEvent = false;
          return EMPTY;
        }

        return this.eventService.getEventById(eventId).pipe(
          catchError(() => {
            this.loadingEvent = false;
            return EMPTY;
          })
        );
      }),
      distinctUntilChanged((prev, curr) => prev?.id === curr?.id)
    ).subscribe((event) => {
      this.event = event;
      this.transactionsDataSource.data = event.transactions || [];
      this.contractsDataSource.data = event.contracts || [];

      const eventYear = new Date(event.date).getFullYear();
      this.titleService.setTitle(`${ event.name } - ${ eventYear } - Podgląd wydarzenia`);
      this.scheduleTitleFit();
      this.loadingEvent = false;
    });
  }

  ngAfterViewInit(): void {
    this.scheduleTitleFit();
  }

  ngOnDestroy(): void {
    this.routeSub?.unsubscribe();
    this.eventsIdsSub?.unsubscribe();
    this.titleResizeObserver?.disconnect();
  }

  get previousEventRoute(): any[] | null {
    return this.previousEventId ? ['/dashboard/events', this.previousEventId] : null;
  }

  get nextEventRoute(): any[] | null {
    return this.nextEventId ? ['/dashboard/events', this.nextEventId] : null;
  }

  private updateNeighbourEventIds(): void {
    if (!this.currentEventId) {
      this.previousEventId = null;
      this.nextEventId = null;
      return;
    }

    this.previousEventId = null;
    this.nextEventId = null;

    for (const id of this.allEventIds) {
      if (id < this.currentEventId) {
        this.previousEventId = id;
      }

      if (id > this.currentEventId) {
        this.nextEventId = id;
        break;
      }
    }
  }

  private scheduleTitleFit(): void {
    requestAnimationFrame(() => {
      requestAnimationFrame(() => this.fitTitleToSingleLine());
    });
  }

  private initTitleResizeObserver(): void {
    if (typeof ResizeObserver === 'undefined' || !this.eventTitleElement) {
      return;
    }

    this.titleResizeObserver?.disconnect();
    const resizeTarget = this.eventTitleElement.parentElement || this.eventTitleElement;

    this.titleResizeObserver = new ResizeObserver(() => {
      if (!this.isFittingTitle) {
        this.fitTitleToSingleLine();
      }
    });

    this.titleResizeObserver.observe(resizeTarget);
  }

  private fitTitleToSingleLine(): void {
    const titleElement = this.eventTitleElement;
    if (!titleElement) {
      return;
    }

    this.isFittingTitle = true;
    try {
      const computedSize = parseFloat(getComputedStyle(titleElement).fontSize);
      let currentSize = Number.isFinite(computedSize) ? computedSize : this.titleMaxFontSizePx;
      currentSize = Math.min(this.titleMaxFontSizePx, Math.max(this.titleMinFontSizePx, currentSize));

      titleElement.style.fontSize = `${currentSize}px`;

      while (titleElement.scrollWidth > titleElement.clientWidth && currentSize > this.titleMinFontSizePx) {
        currentSize -= 1;
        titleElement.style.fontSize = `${currentSize}px`;
      }

      while (titleElement.scrollWidth <= titleElement.clientWidth && currentSize < this.titleMaxFontSizePx) {
        currentSize += 1;
        titleElement.style.fontSize = `${currentSize}px`;
        if (titleElement.scrollWidth > titleElement.clientWidth) {
          currentSize -= 1;
          titleElement.style.fontSize = `${currentSize}px`;
          break;
        }
      }
    } finally {
      this.isFittingTitle = false;
    }
  }

  openContractsEditor() {
    const originalContracts = JSON.parse(JSON.stringify(this.event.contracts || []));

    this.eventService.getContractTypes().subscribe((contractTypes) => {
      const dialogRef = this.dialog.open(EventContractsEditorComponent, {
        data: { contracts: originalContracts, contractTypes },
      });

      dialogRef.afterClosed().subscribe((result: any) => {
        if (result) {
          try {
            const resultContracts = Array.isArray(result) ? result : (result.contracts || []);
            const explicitlyAddedContracts = Array.isArray(result?.addedContracts) ? result.addedContracts : [];

            const origIds = (originalContracts || [])
              .map((c: any) => Number(c.id))
              .filter((id: any) => Number.isFinite(id));
            const resultIds = (resultContracts || [])
              .map((c: any) => Number(c.id))
              .filter((id: any) => Number.isFinite(id));
            const deletedContracts = origIds.filter((id: any) => !resultIds.includes(id));

            const combinedCandidates = [...(resultContracts || []), ...(explicitlyAddedContracts || [])];
            const uniqueNewCandidates = combinedCandidates.filter((candidate: any, index: number, array: any[]) => {
              if (!candidate) {
                return false;
              }

              const candidateId = Number(candidate.id);
              return array.findIndex((entry: any) => Number(entry?.id) === candidateId) === index;
            });

            const newContracts: any[] = uniqueNewCandidates
              .filter((c: any) => c && (c.__isNew === true || !origIds.includes(Number(c.id))))
              .map((c: any) => ({
              'contract-person': c.member ? Number(c.member.id || c.member) : null,
              'contract-amount': parseCurrencyAmount(c.contract_amount).toFixed(2),
              'contract-type': c.type ? Number(c.type.id || c.type) : null
              }));

            const payload: any = {
              event: this.event.id,
            } as any;

            if (newContracts.length) {
              payload['new-contract'] = newContracts;
            }
            if (deletedContracts.length) {
              payload['deletedContracts'] = deletedContracts;
            }

            this.eventService.updateEventContracts(this.event.id, payload).subscribe(() => {
              this.eventService.getEventById(this.event.id).subscribe((event) => {
                this.event = event;
                this.contractsDataSource.data = this.event.contracts || [];
                this.snackbar.open('Zaktualizowano umowy', 'Zamknij', { duration: 3000 });
              });
            }, (err) => {
              console.error('Failed to update contracts', err);
              this.snackbar.open(this.getContractsSaveErrorMessage(err), 'Zamknij', { duration: 8000 });
            });
          } catch (err) {
            console.error('Error preparing contracts payload', err);
            this.snackbar.open('Błąd przygotowania danych umów: sprawdź kwoty i wybrane pola.', 'Zamknij', { duration: 6000 });
          }
        }
      });
    }, (err) => {
      console.error('Failed to fetch contract types', err);
      this.snackbar.open('Nie udało się pobrać typów umów.', 'Zamknij', { duration: 6000 });
    });
  }

  openDetailsEditor() {
    this.eventService.getEventTypes().subscribe((eventTypes) => {
      const dialogRef = this.dialog.open(EventDetailsEditorComponent, {
        data: { event: this.event, eventTypes: eventTypes },
        width: '50vw',
      });

      dialogRef.afterClosed().subscribe((result) => {
        if (result) {
          this.eventService.getEventById(this.event.id).subscribe((event) => {
            this.event = event;
            this.contractsDataSource.data = this.event.contracts || [];
            this.snackbar.open('Zaktualizowano wydarzenie', 'Zamknij', {
              duration: 3000,
            });
            this.scheduleTitleFit();
          });
        }
      });
    });
  }

  openSetlistEditor() {
    const selectedSongIds = (this.event?.setlist || [])
      .map((entry: any) => Number(entry?.song?.id))
      .filter((id: number) => Number.isFinite(id));

    const dialogRef = this.dialog.open(EventSetlistEditorComponent, {
      data: { selectedSongIds },
      width: '900px',
      maxWidth: '95vw',
    });

    dialogRef.afterClosed().subscribe((songIds: number[] | undefined) => {
      if (!Array.isArray(songIds)) {
        return;
      }

      this.eventService.updateEventSetlist(this.event.id, songIds).subscribe(() => {
        this.eventService.getEventById(String(this.event.id)).subscribe((event) => {
          this.event = event;
          this.snackbar.open('Zaktualizowano setlistę', 'Zamknij', {
            duration: 3000,
          });
          this.scheduleTitleFit();
        });
      }, () => {
        this.snackbar.open('Błąd podczas zapisu setlisty', 'Zamknij', {
          duration: 5000,
        });
      });
    });
  }

  generateZaiksReportForEvent() {
    const setlistLength = Array.isArray(this.event?.setlist) ? this.event.setlist.length : 0;
    if (!this.event?.id || setlistLength === 0) {
      this.snackbar.open('Brak utworów na setliście do raportu ZAiKS.', 'Zamknij', {
        duration: 4000,
      });
      return;
    }

    this.generatingZaiksReport = true;
    this.eventService.generateZaiksReportForEvent(Number(this.event.id)).subscribe({
      next: (response) => {
        const contentDisposition = response.headers.get('Content-Disposition');
        const fileName = this.eventService.getFileNameFromContentDisposition(contentDisposition);
        this.eventService.downloadFile(response.body, fileName);
        this.snackbar.open('Wygenerowano raport ZAiKS', 'Zamknij', {
          duration: 3000,
        });
        this.generatingZaiksReport = false;
      },
      error: (error) => {
        this.snackbar.open('Błąd podczas generowania raportu ZAiKS.', 'Zamknij', {
          duration: 5000,
        });
        this.generatingZaiksReport = false;
      },
    });
  }

  deleteThisEvent() {
    this.confirmDialog.openConfirmDialog('Usuwanie wydarzenia', 'Czy na pewno chcesz usunąć to wydarzenie?').then((confirmed) => {
      if (confirmed) {
        this.eventService.deleteEvent(this.event.id).subscribe((message) => {
          this.snackbar.open('Usunięto wydarzenie', 'Zamknij', {
            duration: 3000,
          });
          this.router.navigate(['/events']);
        });

      }
    });
  }
    
}
