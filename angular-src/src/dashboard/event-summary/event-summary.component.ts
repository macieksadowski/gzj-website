import { Component, inject, OnInit} from '@angular/core';
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

@Component({
  selector: 'dashboard-event-summary',
  imports: [CommonModule, MatButtonModule, MatIconModule],
  templateUrl: './event-summary.component.html',
  styleUrl: './event-summary.component.scss'
})
export class EventSummaryComponent implements OnInit {
  event!: any;
  eventService: DashboardBackendService;
  dialog: MatDialog;

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
      const eventYear = new Date(event.date).getFullYear();
      this.titleService.setTitle(`${ event.name } - ${ eventYear } - Podgląd wydarzenia`);
    });
  }

  openContractsEditor() {
    console.log('Opening contracts editor');
    const dialogRef = this.dialog.open(EventContractsEditorComponent, {
      data: { contracts: this.event.contracts },
    });

    
  }

  openDetailsEditor() {
    console.log('Opening details editor');
    const dialogRef = this.dialog.open(EventDetailsEditorComponent, {
      data: { event: this.event },
      width: '50vw',
    });

    dialogRef.afterClosed().subscribe((result) => {
      if (result) {
        this.eventService.getEventById(this.event.id).subscribe((event) => {
          this.event = event;
          this.snackbar.open('Zaktualizowano wydarzenie', 'Zamknij', {
            duration: 3000,
          });
        });
      }
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
