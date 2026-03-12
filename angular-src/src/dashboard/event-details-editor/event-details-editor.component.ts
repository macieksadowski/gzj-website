import { Component, inject, Inject } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogActions, MatDialogContent, MatDialogRef, MatDialogTitle } from '@angular/material/dialog';
import { Event, EventDTO, EventTypes } from '../model/event';
import { CommonModule } from '@angular/common';
import { MatSelectModule } from '@angular/material/select';
import { MatButtonModule } from '@angular/material/button';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { DashboardBackendService } from '../services/dashboardbackend.service';
import { provideNativeDateAdapter } from '@angular/material/core';
import { Router } from '@angular/router';
import { mapEventToDTO } from '../model/event.mapper';

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
    @Inject(MAT_DIALOG_DATA) public data: { event: Event, eventTypes: EventTypes[] },
    private router: Router,
    private eventService: DashboardBackendService
  ) {
    this.event = Object.assign({}, this.data.event);
    this.eventTypes = this.data.eventTypes || [];


    if (this.event.type) {
      this.event.type = this.eventTypes.find(eventType => eventType.value === this.event.type?.value) || this.event.type;
    }
  }

  cancel() {
    this.dialogRef.close();
  }

  save() {
    const eventDto : EventDTO = mapEventToDTO(this.event);

    if (this.event.id) {
      this.eventService.updateEvent(eventDto).subscribe((updatedEvent) => {
        console.log('Event updated');

        this.dialogRef.close(this.data.event);
      });
    } else {
      // If the event does not have an ID, it means it is a new event and should call api endpoint /events to create it
      this.eventService.createEvent(eventDto).subscribe((createdEvent) => {
        console.log('Event created');
        this.event.id = createdEvent.id;
        this.dialogRef.close(this.data.event);
        // Navigate to the event details page for the new event
        this.router.navigate(['/dashboard/events', createdEvent.id]);

      });
    }
  }
}
