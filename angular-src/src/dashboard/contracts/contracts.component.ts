import { Component, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { DashboardBackendService } from '../services/dashboardbackend.service';
import { Contract } from '../model/contracts';
import { MatPaginator, MatPaginatorModule } from '@angular/material/paginator';
import { MatSort, MatSortModule } from '@angular/material/sort';
import { MatTableDataSource, MatTableModule } from '@angular/material/table';
import { Normalisator } from '../shared/normalisator';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { CommonModule } from '@angular/common';
import { MatButtonModule } from '@angular/material/button';
import { MatSelectChange, MatSelectModule } from '@angular/material/select';
import { MatDividerModule } from '@angular/material/divider';

@Component({
  selector: 'dashboard-contracts',
  imports: [
    MatFormFieldModule,
    MatInputModule,
    MatTableModule,
    MatSortModule,
    MatPaginatorModule,
    MatProgressSpinnerModule,
    CommonModule,
    MatButtonModule,
    MatSelectModule,
    MatDividerModule
  ],
  templateUrl: './contracts.component.html',
  styleUrl: './contracts.component.scss'
})
export class ContractsComponent {

  loading: boolean = false;
  displayedColumns: string[] = ['id', 'date', 'event', 'member', 'amount'];
  dataSource: MatTableDataSource<Contract> = new MatTableDataSource<Contract>();
  dataSourceSummaryTable: MatTableDataSource<any> = new MatTableDataSource<any>();
  displayedColumnsSummaryTable: string[] = ['member', 'contracts_amount', 'total_amount'];
  summariesPerYear: any[] = [];
  selectedYear: number = new Date().getFullYear();
  years: number[] = [];

  @ViewChild(MatSort) sort!: MatSort;
  @ViewChild(MatPaginator) paginator!: MatPaginator;


  constructor(
    private contractsService: DashboardBackendService,
    private router: Router
  ) { }

  fetchContracts() {
    this.loading = true;
    this.contractsService.getAllContracts().subscribe(data => {
      this.dataSource = new MatTableDataSource<Contract>(data);

      this.dataSource.paginator = this.paginator;

      this.dataSource.sort = this.sort;

      this.dataSource.filterPredicate = (data: Contract, filter: string) => {
        return data.event.name.toLowerCase().includes(filter) ||
          data.member.name.toLowerCase().includes(filter);
      };

      this.loading = false;

    });
  }

  fetchContractsSummaryPerYear() {
    this.loading = true;
    this.contractsService.getContractsSummaryPerYear().subscribe(data => {
      this.summariesPerYear = data;
      this.years = Object.keys(data).map(year => parseInt(year));
      this.years.sort((a, b) => b - a);
      this.selectedYear = this.years[0];
      this.dataSourceSummaryTable = new MatTableDataSource<any>(data[this.selectedYear]);
      

      this.loading = false;
    });
  }

  ngOnInit(): void {
    this.fetchContracts();
    this.fetchContractsSummaryPerYear();
  }

  applyFilter(event: KeyboardEvent) {
    const filterValue = (event.target as HTMLInputElement).value;
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  applyYearFilter(event: MatSelectChange) {
    const selectedYear = event.value;
    this.selectedYear = parseInt(selectedYear);
    const summaryPerSelectedYear = this.summariesPerYear[this.selectedYear];
    this.dataSourceSummaryTable = new MatTableDataSource<any>(summaryPerSelectedYear);
  }

  goToEventSummary(eventId: Number) {
    this.router.navigate(['/events', eventId]);
  }
}
