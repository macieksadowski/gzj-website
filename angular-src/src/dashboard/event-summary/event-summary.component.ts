import { Component, inject, OnInit, ViewChild, AfterViewInit} from '@angular/core';
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

@Component({
  selector: 'dashboard-event-summary',
  imports: [CommonModule, MatButtonModule, MatIconModule, MatTableModule, MatCardModule, MatSortModule, MatPaginatorModule, MatToolbarModule],
  templateUrl: './event-summary.component.html',
  styleUrl: './event-summary.component.scss'
})
export class EventSummaryComponent implements OnInit, AfterViewInit {
  event!: any;
  eventService: DashboardBackendService;
  dialog: MatDialog;

  transactionsDataSource = new MatTableDataSource<any>();

  contractsDataSource = new MatTableDataSource<any>();

  // Table columns
  contractsColumns: string[] = ['member', 'amount', 'type'];
  transactionsColumns: string[] = ['amount', 'description', 'category'];
  setlistColumns: string[] = ['order', 'song'];

  @ViewChild(MatSort) sort!: MatSort;
  @ViewChild(MatPaginator) paginator!: MatPaginator;

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
    
      // Ensure paginator is assigned after data is set
      setTimeout(() => {
        if (this.paginator) {
          this.transactionsDataSource.paginator = this.paginator;
        }
        if (this.sort) {
          this.transactionsDataSource.sort = this.sort;
        }
      });
      const eventYear = new Date(event.date).getFullYear();
      this.titleService.setTitle(`${ event.name } - ${ eventYear } - Podgląd wydarzenia`);
    });
  }

  ngAfterViewInit() {
  }

  openContractsEditor() {
    console.log('Opening contracts editor');

    // Keep a snapshot of original contracts to compute deletions
    const originalContracts = JSON.parse(JSON.stringify(this.event.contracts || []));

    const dialogRef = this.dialog.open(EventContractsEditorComponent, {
      data: { contracts: originalContracts },
    });

    dialogRef.afterClosed().subscribe((result: any) => {
      if (result) {
        // result is the modified contracts array
        try {
          // compute deleted contract IDs (present in original, missing in result)
          const origIds = (originalContracts || []).map((c: any) => c.id).filter((id: any) => id !== undefined && id !== null);
          const resultIds = (result || []).map((c: any) => c.id).filter((id: any) => id !== undefined && id !== null);
          const deletedContracts = origIds.filter((id: any) => !resultIds.includes(id));

          // compute new-contract array expected by backend
          const newContracts: any[] = (result || []).filter((c: any) => !origIds.includes(c.id)).map((c: any) => ({
            'contract-person': c.member && (c.member.id || c.member),
            'contract-amount': Number(c.contract_amount).toFixed(2),
            'contract-type': c.type && c.type.value
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
            // Refresh event data after saving
            this.eventService.getEventById(this.event.id).subscribe((event) => {
              this.event = event;
              this.contractsDataSource.data = this.event.contracts || [];
              this.snackbar.open('Zaktualizowano umowy', 'Zamknij', { duration: 3000 });
            });
          }, (err) => {
            console.error('Failed to update contracts', err);
            this.snackbar.open('Błąd podczas zapisywania umów', 'Zamknij', { duration: 5000 });
          });
        } catch (err) {
          console.error('Error preparing contracts payload', err);
          this.snackbar.open('Błąd przygotowania danych umów', 'Zamknij', { duration: 5000 });
        }
      }
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
