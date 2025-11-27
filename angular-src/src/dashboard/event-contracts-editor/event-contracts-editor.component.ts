import { Component, inject, Inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogTitle, MatDialogActions, MatDialogContent, MatDialogRef } from '@angular/material/dialog';
import { CommonModule } from '@angular/common';
import { MatDialogModule } from '@angular/material/dialog';
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
  standalone: true,
  templateUrl: './event-contracts-editor.component.html',
  styleUrls: ['./event-contracts-editor.component.scss'],
  imports: [
    CommonModule,
    MatSelectModule,
    MatButtonModule,
    MatIconModule,
    MatDialogModule,
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
  private originalContracts: any[] = [];
  members: any[] = [];
  memberService: DashboardBackendService;
  confirmDeleteIndex: number | null = null;

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
    // Use a deep copy so mutations inside this component don't change the original reference
    this.contracts = this.data.contracts ? JSON.parse(JSON.stringify(this.data.contracts)) : [];
    this.originalContracts = JSON.parse(JSON.stringify(this.contracts));

    this.memberService.getAllMembersNames().subscribe(data => {
      this.members = data;
      console.log('Loaded members:', data); // Debug log
    });
  }


  addContract() {
    if (this.canAdd()) {
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

  canAdd(): boolean {
    return !!(this.newContract.member && this.newContract.contract_amount > 0 && this.newContract.type);
  }

  removeContract(index: number) {
    // two-step confirm: first click marks index, second click removes
    if (this.confirmDeleteIndex === index) {
      this.contracts = this.contracts.filter((_, i) => i !== index);
      this.confirmDeleteIndex = null;
    } else {
      this.confirmDeleteIndex = index;
      // reset confirmation after a short timeout
      setTimeout(() => {
        if (this.confirmDeleteIndex === index) {
          this.confirmDeleteIndex = null;
        }
      }, 4000);
    }
  }

  cancel() {
    this.dialogRef.close();
  }

  undo() {
    // Restore the in-component contracts from the original snapshot
    this.contracts = JSON.parse(JSON.stringify(this.originalContracts));
    this.confirmDeleteIndex = null;
  }

  save() {
    // Return the modified contracts to the caller
    this.dialogRef.close(this.contracts);
  }

  hasChanges(): boolean {
    try {
      return JSON.stringify(this.contracts) !== JSON.stringify(this.originalContracts);
    } catch (e) {
      return true;
    }
  }

}
