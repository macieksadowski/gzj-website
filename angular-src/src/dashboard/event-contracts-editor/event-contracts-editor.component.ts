import { Component, inject, Inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogTitle, MatDialogActions, MatDialogContent, MatDialogRef } from '@angular/material/dialog';
import { CommonModule } from '@angular/common';
import { MatDialogModule } from '@angular/material/dialog';
import { MatSelectModule } from '@angular/material/select';
import { MatIconModule } from '@angular/material/icon';
import { MatButtonModule } from '@angular/material/button';
import { MatTooltipModule } from '@angular/material/tooltip';
import { MatFormFieldModule, MAT_FORM_FIELD_DEFAULT_OPTIONS } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { CurrencyFormatDirective, parseCurrencyAmount } from '../shared/currencyFormat';
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
    MatTooltipModule,
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
  private addedContracts: any[] = [];
  private originalContracts: any[] = [];
  private nextTemporaryId = -1;
  members: any[] = [];
  memberService: DashboardBackendService;
  confirmDeleteIndex: number | null = null;

  contractTypes: ContractTypes[] = [];

  newContract: Contract = {
    id: 0,
    contract_amount: 0,
    type: null as any,
    member: null,
    event: null
  };

  constructor(
    public dialogRef: MatDialogRef<EventContractsEditorComponent>,
    @Inject(MAT_DIALOG_DATA) public data: { contracts: any[], contractTypes?: ContractTypes[] },
  ) {
    this.memberService = inject(DashboardBackendService);
    dialogRef.disableClose = true;
    this.contractTypes = this.data.contractTypes || [];

    this.contracts = this.data.contracts ? JSON.parse(JSON.stringify(this.data.contracts)) : [];
    this.contracts = this.contracts.map((contract: any) => ({ ...contract, __isNew: false }));
    this.originalContracts = JSON.parse(JSON.stringify(this.contracts));

    if (!this.newContract.type && this.contractTypes.length) {
      this.newContract.type = this.contractTypes[0];
    }

    this.memberService.getAllMembersNames().subscribe(data => {
      this.members = data;
    });
  }


  addContract() {
    if (this.canAdd()) {
      const normalizedAmount = parseCurrencyAmount(this.newContract.contract_amount);
      const addedContract = { ...this.newContract, id: this.nextTemporaryId, contract_amount: normalizedAmount, __isNew: true };
      this.contracts.push(addedContract);
      this.addedContracts.push(addedContract);
      this.nextTemporaryId -= 1;

      this.newContract = {
        id: 0,
        contract_amount: 0,
        type: this.contractTypes[0] || null as any,
        member: null,
        event: null
      };
    }
  }

  canAdd(): boolean {
    return !!(this.newContract.member && parseCurrencyAmount(this.newContract.contract_amount) > 0 && this.newContract.type);
  }

  removeContract(index: number) {
    // Two-step delete: first click arms confirmation, second click removes.
    if (this.confirmDeleteIndex === index) {
      const removedContract = this.contracts[index];
      if (removedContract?.__isNew === true) {
        this.addedContracts = this.addedContracts.filter((contract: any) => contract.id !== removedContract.id);
      }
      this.contracts = this.contracts.filter((_, i) => i !== index);
      this.confirmDeleteIndex = null;
    } else {
      this.confirmDeleteIndex = index;
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
    this.contracts = JSON.parse(JSON.stringify(this.originalContracts));
    this.contracts = this.contracts.map((contract: any) => ({ ...contract, __isNew: false }));
    this.addedContracts = [];
    this.confirmDeleteIndex = null;
  }

  save() {
    this.dialogRef.close({ contracts: this.contracts, addedContracts: this.addedContracts });
  }

  hasChanges(): boolean {
    try {
      return JSON.stringify(this.contracts) !== JSON.stringify(this.originalContracts);
    } catch (e) {
      return true;
    }
  }

}
