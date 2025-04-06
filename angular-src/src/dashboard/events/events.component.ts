import { Component, inject, OnInit, ViewChild } from '@angular/core';
import { DashboardBackendService } from '../services/dashboardbackend.service';
import { Event } from '../model/event';
import { CommonModule } from '@angular/common';
import {MatTableDataSource, MatTableModule} from '@angular/material/table';
import { MatPaginator, MatPaginatorModule } from '@angular/material/paginator';
import { MatSort, MatSortModule } from '@angular/material/sort';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatSelectModule } from '@angular/material/select';
import { MatOptionModule } from '@angular/material/core';
import { Router } from '@angular/router';
import { EventDetailsEditorComponent } from '../event-details-editor/event-details-editor.component';
import { MatDialog } from '@angular/material/dialog';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { Normalisator } from '../shared/normalisator';


@Component({
  selector: 'dashboard-events',
  imports: [
    CommonModule, 
    MatTableModule, 
    MatPaginatorModule, 
    MatSortModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    MatSelectModule,
    MatOptionModule,
    MatProgressSpinnerModule
  ],
  templateUrl: './events.component.html',
  styleUrl: './events.component.scss'
})
export class EventsComponent implements OnInit {

  eventService: DashboardBackendService;
  dialog: MatDialog;
  router: Router;
  loading: boolean = false;

  @ViewChild(MatSort) sort!: MatSort;
  @ViewChild(MatPaginator) paginator!: MatPaginator;

  displayedColumns = ['id', 'name', 'date', 'saldo', 'contracts_amount', 'type'];
  dataSource: MatTableDataSource<Event> = new MatTableDataSource<Event>();
  eventTypes: string[] = [];
  selectedTypes: string[] = [];
  
  constructor() {
    this.eventService = inject(DashboardBackendService);
    this.router = inject(Router);
    this.dialog = inject(MatDialog);
  }

  fetchEvents() {
    this.loading = true;
    this.eventService.getAllEvents().subscribe(data => {
      this.dataSource = new MatTableDataSource<Event>(data);
      console.log('Loaded events:', data); // Debug log

      this.dataSource.paginator = this.paginator;
  
      this.dataSource.sortingDataAccessor = (item, property) => {
        switch(property) {
          case 'type': return item.type.value.toString();
          case 'name': return Normalisator.normalizePolishChars(item.name);
          case 'date': return new Date(item.date).getTime();
          case 'saldo': return parseFloat(item.saldo.toString().replace(',', '.'));
          case 'contracts_amount': return item.contracts_amount as number;
          default: return item[property as keyof Event] as string | number;
        }
        
      };

      this.dataSource.sort = this.sort;

      this.dataSource.filterPredicate = (data: Event, filter: string) => {
        const transformedFilter = filter.trim().toLowerCase();
        let matchesName = true;
        if (filter.trim().toLowerCase() !== 'type') {
          matchesName = data.name.toLowerCase().includes(transformedFilter);
        }
        const matchesType = this.selectedTypes.length === 0 || this.selectedTypes.includes(data.type.value);
        return matchesName && matchesType;
      }
      this.eventTypes = [...new Set(data.map(event => event.type.value))];

      this.loading = false;
    });
  }

  goToEventSummary(eventId: Number) {
    this.router.navigate(['/events', eventId]);
  }


  applyNameFilter(keyboardEvent: KeyboardEvent) {
    const filterValue = (keyboardEvent.target as HTMLInputElement).value;
    this.dataSource.filter = filterValue;
  }

  applyTypeFilter(event: any) {;
    this.selectedTypes = event.value;
    this.dataSource.filter = 'type';
  }

  ngOnInit() {
    this.loading = true;
    this.fetchEvents();
  }

  newEvent() {
        const dialogRef = this.dialog.open(EventDetailsEditorComponent, {
          data: { event: { name: '', date: new Date(), type: '', saldo: 0, contracts_amount: 0 } },
          width: '50vw',
        });
  }
}
