import { Component, inject, Inject } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogActions, MatDialogContent, MatDialogRef, MatDialogTitle } from '@angular/material/dialog';
import { Event, EventTypes } from '../model/event';
import { CommonModule } from '@angular/common';
import { MatSelectModule } from '@angular/material/select';
import { MatButtonModule } from '@angular/material/button';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import {MatDatepickerModule} from '@angular/material/datepicker';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { DashboardBackendService } from '../services/dashboardbackend.service';
import {provideNativeDateAdapter} from '@angular/material/core';
import { Router } from '@angular/router';

@Component({
  selector: 'dashboard-event-details-editor',
  templateUrl: './event-details-editor.component.html',
  styleUrl: './event-details-editor.component.scss',
  providers: [provideNativeDateAdapter()],
  imports: [
    CommonModule,
    MatSelectModule,
    MatButtonModule,
    MatDialogTitle,
    MatDialogContent,
    MatDialogActions,
    MatFormFieldModule,
    MatInputModule,
    MatDatepickerModule,
    FormsModule,
    ReactiveFormsModule,
  ]
})
export class EventDetailsEditorComponent {
  event: Event;
  eventTypes: EventTypes[] = [];

  constructor(
    public dialogRef: MatDialogRef<EventDetailsEditorComponent>,
    @Inject(MAT_DIALOG_DATA) public data: { event: Event },
    private router: Router,
    private eventService: DashboardBackendService
  ) {
    this.event = Object.assign({}, this.data.event);

    this.eventService.getEventTypes().subscribe((data) => {
      this.eventTypes = data;
      console.log('Loaded event types:', data); // Debug log
    });
  }

  cancel() {
    this.dialogRef.close();
  }
  
  save() {
    // If the event has an ID, it means it already exists in the database and should call api endpoint /events/{id}/edit to update it
    if (this.event.id) {
      this.eventService.updateEvent(this.event).subscribe((updatedEvent) => {
        console.log('Event updated');
        
        this.dialogRef.close(this.data.event);
      });
    } else {
      // If the event does not have an ID, it means it is a new event and should call api endpoint /events to create it
      this.eventService.createEvent(this.event).subscribe((createdEvent) => {
        console.log('Event created');
        this.event.id = createdEvent.id;
        this.dialogRef.close(this.data.event);
        // Navigate to the event details page for the new event
        this.router.navigate(['/events', createdEvent.id]);

      });
    }
    this.dialogRef.close(this.data.event);
  }
}
