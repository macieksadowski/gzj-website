import { ChangeDetectorRef, Component, inject, Inject } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogActions, MatDialogContent, MatDialogRef, MatDialogTitle } from '@angular/material/dialog';
import { Event, EventTypes } from '../model/event';
import { CommonModule } from '@angular/common';
import { MatSelectModule } from '@angular/material/select';
import { MatButtonModule } from '@angular/material/button';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import {MatDatepickerModule} from '@angular/material/datepicker';
import { FormControl, FormGroup, FormsModule, ReactiveFormsModule } from '@angular/forms';
import { DashboardBackendService } from '../services/dashboardbackend.service';
import {provideNativeDateAdapter} from '@angular/material/core';
import { Router } from '@angular/router';
import { CurrencyFormatDirective } from '../shared/currencyFormat';
import { MatAutocompleteModule } from '@angular/material/autocomplete';
import { debounceTime, distinctUntilChanged, switchMap } from 'rxjs';
import { CategoryType, CategoryTypeEnum, Transaction, TransactionCategory } from '../model/transaction';

@Component({
  selector: 'dashboard-transaction-editor',
  templateUrl: './transaction-editor.component.html',
  styleUrl: './transaction-editor.component.scss',
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
    CurrencyFormatDirective,
    MatAutocompleteModule
  ]
})
export class TransactionEditorComponent {
  transactionForm: FormGroup;
  eventControl: FormControl;
  amountControl: FormControl;
  categoryControl: FormControl;
  transaction: Transaction;
  transactionCategories: TransactionCategory[] = [];
  events: Event[] = [];
  suggestions: { id: number, name: string }[] = [];

  constructor(
    public dialogRef: MatDialogRef<TransactionEditorComponent>,
    @Inject(MAT_DIALOG_DATA) public data: { transaction: Transaction, transactionCategories: TransactionCategory[] },
    private router: Router,
    private transactionService: DashboardBackendService,
    private cdr: ChangeDetectorRef
  ) {
    this.eventControl = new FormControl();
    this.amountControl = new FormControl();
    this.categoryControl = new FormControl();
    this.transactionForm = new FormGroup({
      event: this.eventControl,
      category: this.categoryControl,
      amount: this.amountControl,
      date: new FormControl(),
      description: new FormControl()
    });
    this.transaction = Object.assign({}, this.data.transaction);
    this.transactionCategories = this.data.transactionCategories;

    this.transactionForm.setValue({
      event: this.transaction.event || null,
      category: this.transaction.category?.id || null,
      amount: this.transaction.amount,
      date: this.transaction.date,
      description: this.transaction.description
    });
    
    
  }

  ngOnInit(): void {
    console.log('Transaction on init:', this.transaction);
    console.log('Transaction Categories on init:', this.transactionCategories);
  

    this.eventControl.valueChanges
      .pipe(
        debounceTime(300),
        distinctUntilChanged(),
        switchMap((query) => this.transactionService.getEventSuggestions(query))
      )
      .subscribe((data: { id: number, name: string }[]) => {
        console.log('Suggestions:', data);
        this.suggestions = data;
      });

  }

  filterCategories() {
    const amount = this.transactionForm.value.amount;
    this.transactionCategories = [...this.data.transactionCategories.filter((category) => {
      if (amount < 0) {
        return category.type.value === CategoryTypeEnum.OUTCOME;
      } else {
        return category.type.value === CategoryTypeEnum.INCOME;
      }
    })];

    if (!this.transactionCategories.some(cat => cat.id === this.categoryControl.value)) {
      this.categoryControl.setValue(null);
    }

    this.cdr.detectChanges();
  }

  onOptionSelected(event: any): void {
    const selectedSuggestion = event.option.value;
    console.log('Selected event id:', selectedSuggestion.id);
    this.transaction.event = selectedSuggestion.id;
  }

  cancel() {
    this.dialogRef.close();
  }
  
  save() {
    const formValues = this.transactionForm.value;
    this.transaction.event = formValues.event;
    this.transaction.category = this.data.transactionCategories.find((category) => category.id === formValues.category);
    this.transaction.amount = formValues.amount;
    this.transaction.date = formValues.date;
    this.transaction.description = formValues.description;

    if (this.transaction.tr_id) {
      console.log('Updating transaction');
      this.transactionService.updateTransaction(this.transaction).subscribe((updatedTransaction) => {
        console.log('Updated transaction:', updatedTransaction);
        this.dialogRef.close(updatedTransaction);
      });
    } else {
      this.transactionService.createTransaction(this.transaction).subscribe((createdTransaction) => {
        console.log('Created transaction:', createdTransaction);
        this.transaction.tr_id = createdTransaction.id;
        this.dialogRef.close(createdTransaction);
      });
    }
    this.dialogRef.close(this.data.transaction);
  }

  displayEvent(event?: any): string {
    return event ? event.name : '';
  }
}