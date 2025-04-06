import { Component, inject, Inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogTitle, MatDialogActions, MatDialogContent, MatDialogRef } from '@angular/material/dialog';
import { CommonModule } from '@angular/common';
import { MatSelectModule } from '@angular/material/select';
import { MatIconModule } from '@angular/material/icon';
import { MatButtonModule } from '@angular/material/button';
import { MatFormFieldModule, MAT_FORM_FIELD_DEFAULT_OPTIONS } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { CurrencyFormatDirective } from '../shared/currencyFormat';
import { DashboardBackendService } from '../services/dashboardbackend.service';
import { Contract, ContractTypes } from '../model/contracts';

@Component({
  selector: 'dashboard-event-contracts-editor',
  templateUrl: './event-contracts-editor.component.html',
  styleUrl: './event-contracts-editor.component.scss',
  imports: [
    CommonModule,
    MatSelectModule,
    MatButtonModule,
    MatIconModule,
    MatDialogTitle,
    MatDialogContent,
    MatDialogActions,
    MatFormFieldModule,
    MatInputModule,
    CurrencyFormatDirective,
    FormsModule],
  providers: [
    { provide: MAT_FORM_FIELD_DEFAULT_OPTIONS, useValue: { appearance: 'outline' } }
  ]
})
export class EventContractsEditorComponent {
  contracts: any[] = [];
  members: any[] = [];
  memberService: DashboardBackendService;

  contractTypes: ContractTypes[] = [
    { id: 1, value: 'Dzieło' },
    { id: 2, value: 'Zlecenie' },
    { id: 3, value: 'Inna' }
  ];

  newContract: Contract = {
    id: 0,
    contract_amount: 0,
    type: this.contractTypes[0],
    member: null,
    event: null
  };

  constructor(
    public dialogRef: MatDialogRef<EventContractsEditorComponent>,
    @Inject(MAT_DIALOG_DATA) public data: { contracts: any[] },
  ) {
    this.memberService = inject(DashboardBackendService);
    dialogRef.disableClose = true;
    console.log('Received contracts:', this.data.contracts);
    this.contracts = this.data.contracts || [];

    this.memberService.getAllMembersNames().subscribe(data => {
      this.members = data;
      console.log('Loaded members:', data); // Debug log
    });
  }


  addContract() {
    if (this.newContract.member && this.newContract.contract_amount > 0 && this.newContract.type) {
      const newId = this.contracts.length ? Math.max(...this.contracts.map(c => c.id)) + 1 : 1;
      this.contracts.push({ ...this.newContract, id: newId });

      // Resetowanie formularza
      this.newContract = {
        id: 0,
        contract_amount: 0,
        type: this.contractTypes[0],
        member: null,
        event: null
      };
    }
  }

  removeContract(index: number) {
    this.contracts = this.contracts.filter((_, i) => i !== index);
  }

  cancel() {
    this.dialogRef.close();
  }

  undo() {
    this.contracts = this.data.contracts
  }

  save() {
    // Implement saving logic here when backend API is ready
    this.dialogRef.close(this.data.contracts);
  }

}
