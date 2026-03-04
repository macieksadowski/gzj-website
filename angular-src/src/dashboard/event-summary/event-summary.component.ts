import { Component, inject, OnInit, ViewChild } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { CommonModule } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { DashboardBackendService } from '../services/dashboardbackend.service';
import { EventContractsEditorComponent } from "../event-contracts-editor/event-contracts-editor.component";
import { EventDetailsEditorComponent } from '../event-details-editor/event-details-editor.component';
import { ConfirmDialogService } from '../services/confirmdialog.service';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import {MatSnackBar} from '@angular/material/snack-bar';
import { Title } from '@angular/platform-browser';
import { MatTableModule, MatTableDataSource } from '@angular/material/table';
import { MatCardModule } from '@angular/material/card';
import { MatSortModule, MatSort } from '@angular/material/sort';
import { MatPaginatorModule, MatPaginator } from '@angular/material/paginator';
import { MatToolbarModule } from '@angular/material/toolbar';
import { parseCurrencyAmount } from '../shared/currencyFormat';

@Component({
  selector: 'dashboard-event-summary',
  imports: [CommonModule, MatButtonModule, MatIconModule, MatTableModule, MatCardModule, MatSortModule, MatPaginatorModule, MatToolbarModule],
  templateUrl: './event-summary.component.html',
  styleUrl: './event-summary.component.scss'
})
export class EventSummaryComponent implements OnInit {
  event!: any;
  eventService: DashboardBackendService;
  dialog: MatDialog;

  transactionsDataSource = new MatTableDataSource<any>();

  contractsDataSource = new MatTableDataSource<any>();

  contractsColumns: string[] = ['member', 'amount', 'type'];
  transactionsColumns: string[] = ['amount', 'description', 'category'];
  setlistColumns: string[] = ['order', 'song'];

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
    const eventId = this.route.snapshot.paramMap.get('id');
    this.eventService.getEventById(eventId).subscribe(event => {
      this.event = event;
      this.transactionsDataSource.data = event.transactions || [];
      this.contractsDataSource.data = event.contracts || [];

      const eventYear = new Date(event.date).getFullYear();
      this.titleService.setTitle(`${ event.name } - ${ eventYear } - Podgląd wydarzenia`);
    });
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
    console.log('Opening details editor');
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
          });
        }
      });
    });
  }

  deleteThisEvent() {
    this.confirmDialog.openConfirmDialog('Usuwanie wydarzenia', 'Czy na pewno chcesz usunąć to wydarzenie?').then((confirmed) => {
      if (confirmed) {
        this.eventService.deleteEvent(this.event.id).subscribe((message) => {
          console.log(message);
          this.snackbar.open('Usunięto wydarzenie', 'Zamknij', {
            duration: 3000,
          });
          this.router.navigate(['/events']);
        });

      }
    });
  }
    
}
