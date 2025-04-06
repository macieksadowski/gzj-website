import { Component, inject, ViewChild } from '@angular/core';
import { DashboardBackendService } from '../services/dashboardbackend.service';
import { CommonModule } from '@angular/common';
import { MatFormFieldModule } from '@angular/material/form-field';
import { FormsModule } from '@angular/forms';
import { MatInputModule } from '@angular/material/input';
import { MatPaginator, MatPaginatorModule } from '@angular/material/paginator';
import { MatSort, MatSortModule } from '@angular/material/sort';
import { MatTableDataSource, MatTableModule } from '@angular/material/table';
import { Router } from '@angular/router';
import { MatButtonModule } from '@angular/material/button';
import { CurrencyFormatDirective } from '../shared/currencyFormat';
import { MatIconModule } from '@angular/material/icon';
import { ConfirmDialogService } from '../services/confirmdialog.service';
import { MatSnackBar } from '@angular/material/snack-bar';
import { TransactionEditorComponent } from '../transaction-editor/transaction-editor.component';
import { MatDialog } from '@angular/material/dialog';
import { firstValueFrom } from 'rxjs';
import {MatProgressSpinnerModule} from '@angular/material/progress-spinner';
import { Transaction, TransactionCategory } from '../model/transaction';
import { MatOptionModule } from '@angular/material/core';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatNativeDateModule } from '@angular/material/core';
import { MatSelectModule } from '@angular/material/select';

@Component({
  selector: 'dashboard-transactions',
  imports: [
    CommonModule,
    MatTableModule,
    MatPaginatorModule,
    MatSortModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    MatInputModule,
    FormsModule,
    MatIconModule,
    MatProgressSpinnerModule,
    MatOptionModule,
    MatDatepickerModule,
    MatNativeDateModule,
    MatSelectModule
],
  templateUrl: './transactions.component.html',
  styleUrl: './transactions.component.scss'
})
export class TransactionsComponent {
  transactions: Transaction[] = [];
  saldo: number = 0;
  displayedColumns = ['date', 'amount', 'description', 'category', 'event', 'cash', 'id', 'actions'];
  dataSource: MatTableDataSource<any> = new MatTableDataSource<any>();
  numberOfTransactionsLoaded: number = 0;
  loading: boolean = false;
  categories: { id: number; name: string }[] = [];
  selectedCategories: number[] = [];
  dateFrom: Date | null = null;
  dateTo: Date | null = null;

  @ViewChild(MatSort) sort!: MatSort;
  @ViewChild(MatPaginator) paginator!: MatPaginator;

  constructor(
    private router: Router,
    private financesService: DashboardBackendService,
    private confirmDialog: ConfirmDialogService,
    private snackbar: MatSnackBar,
    private dialog: MatDialog
  ) { }

  ngOnInit(): void {
    this.loading = true;
    this.loadMainTable();
    
  }

  loadMainTable() {
    this.financesService.getTransactions().subscribe((transactions) => {
      this.dataSource = new MatTableDataSource<any>(transactions);
      this.numberOfTransactionsLoaded = this.dataSource.data.length;
      console.log('Loaded transactions:', transactions); // Debug log

      this.dataSource.paginator = this.paginator;

      this.sort.active = '';
      this.sort.direction = '';
      this.sort.sort({ id: 'date', start: 'desc', disableClear: true });

      this.dataSource.sortingDataAccessor = (item, property) => {
        switch (property) {
          case 'id': return item.id;
          case 'date': return new Date(item.date).getTime();
          case 'amount': return parseFloat(item.amount.toString().replace(',', '.'));
          case 'description': return item.description;
          default: return '';
        }
      }

      this.dataSource.sort = this.sort;

      this.dataSource.filterPredicate = (data: Transaction, filter: string) => {
        const transformedFilter = filter.trim().toLowerCase();
        let matchesCategory = true;
        let matchesDateFrom = true;
        let matchesDateTo = true;
        if (transformedFilter === 'category' && data.category) {
          matchesCategory = this.selectedCategories.length === 0 || this.selectedCategories.includes(data.category.id);
        }
        if (transformedFilter === 'date') {
          matchesDateFrom = !this.dateFrom || new Date(data.date) >= this.dateFrom;
          matchesDateTo = !this.dateTo || new Date(data.date) <= this.dateTo;
        }
        let result = matchesCategory && matchesDateFrom && matchesDateTo;
        return result;
      }
      //this.categories = [...new Set(transactions.map(transaction => transaction.category.name))];
      this.categories = [...new Map(
        transactions.map(transaction => [transaction.category.id, transaction.category])
      ).values()];

      this.loading = false;

    });
    this.financesService.getSaldo().subscribe((saldo) => {
      this.saldo = saldo;
      console.log('Loaded saldo:', saldo); // Debug log
    });
    
  }

  applyCategoryFilter(event: any) {
    this.selectedCategories = event.value;
    this.dataSource.filter = 'category';
  }

  applyDateFilter(event : any) {
    this.dataSource.filter = 'date';
  }

  goToEventSummary(eventId: Number) {
    this.router.navigate(['/events', eventId]);
  }

  async editTransaction(transaction: any) {
    this.loading = true;
    const transactionCategories = await this.loadTransactionCategories();

    this.loading = false;
    const dialogRef = this.dialog.open(TransactionEditorComponent, {
      width: '600px',
      data: { transaction: transaction, transactionCategories: transactionCategories }
    })

    
    dialogRef.afterClosed().subscribe((result) => {
      if (result) {
        this.loadMainTable();
        this.snackbar.open(`Zedytowano transakcję o id: ${result.transaction.id}`, 'Zamknij', {
          duration: 3000,
        });
      } else {
        this.snackbar.open('Błąd dodawania transakcji', 'Zamknij', {
          duration: 3000
        });
      }
    });
    
  }

  deleteTransaction(transaction: Transaction) {
    const transactionId = transaction.tr_id;
    this.confirmDialog.openConfirmDialog('Usuwanie transakcji', `Czy na pewno chcesz usunąć transakcję o id: ${transactionId}`).then((confirmed) => {
      if (confirmed) {
        this.financesService.deleteTransaction(transactionId).subscribe((response) => {
          console.log(response);
          this.snackbar.open(response.message, 'Zamknij', {
            duration: 3000,
          });
          this.loadMainTable();
        });

      }
    });
  }

  async newTransaction() {
    this.loading = true;
    const transactionCategories = await this.loadTransactionCategories();

    this.loading = false;

    let newTransaction: Transaction = {
      tr_id: 0,
      date: new Date(),
      amount: 0,
      description: '',
      category: undefined,
      event: undefined,
      cash_transaction: false
    }

    const dialogRef = this.dialog.open(TransactionEditorComponent, {
      width: '600px',
      data: { transaction: newTransaction, transactionCategories: transactionCategories }
    })

    dialogRef.afterClosed().subscribe((result) => {
      if (result) {
        this.loadMainTable();
        this.snackbar.open('Dodano nową transakcję', 'Zamknij', {
          duration: 3000,
        });
      } else {
        this.snackbar.open('Błąd dodawania transakcji', 'Zamknij', {
          duration: 3000
        });
      }
    });
  }

  async loadTransactionCategories() {
    try {
      return await firstValueFrom(this.financesService.getTransactionCategories());
    } catch (error) {
      console.error('Error loading transaction categories:', error);
      throw error;
    }
  }

}
