import { Component, OnInit } from '@angular/core';
import { AbstractControl, FormBuilder, FormGroup, ReactiveFormsModule, ValidationErrors, Validators } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatRadioModule } from '@angular/material/radio';
import { MatCardModule } from '@angular/material/card';
import { MatSelectModule } from '@angular/material/select';
import { DashboardBackendService } from '../services/dashboardbackend.service';
import { MatSnackBar } from '@angular/material/snack-bar';
import { CommonModule } from '@angular/common';
import { NgxMaskService } from 'ngx-mask';
import { BankAccountInputDirective } from '../shared/bank-account-input.directive';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';

@Component({
  selector: 'dashboard-members',
  imports: [
    ReactiveFormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatRadioModule,
    MatButtonModule,
    MatCardModule,
    MatSelectModule,
    CommonModule,
    BankAccountInputDirective,
    MatProgressSpinnerModule,
  ],
  templateUrl: './members.component.html',
  styleUrl: './members.component.scss'
})
export class MembersComponent implements OnInit {
  memberForm: FormGroup;
  contractForm: FormGroup; // Dodano formularz dla generatora umów
  isEditing = true;
  members: any[] = [];
  selectedMember: number | null = null;
  isLoading = false;

  constructor(private fb: FormBuilder, private backendService: DashboardBackendService, private snackbar: MatSnackBar) {
    this.memberForm = this.fb.group({
      first_name: ['', [Validators.required]],
      last_name: ['', [Validators.required]],
      street: [''],
      house_no: [''],
      postal_code: [''],
      town: [''],
      pesel: ['', [Validators.pattern(/^\d{11}$/)]],
      birth_place: [''],
      account_no: ['', [this.bankAccountValidator]],
      tax_office: ['']
    });

    this.contractForm = this.fb.group({
      contract_title: ['', [Validators.required]],
      contract_type: ['zlecenie', [Validators.required]]
    });
  }

  ngOnInit() {
    this.isLoading = true;
    this.fetchMembers();
  }

  fetchMembers() {
    this.backendService.getAllMembers().subscribe({
      next: (data) => {
        this.members = data;
        console.log('Members fetched:', this.members);
        this.onMemberChange({ value: this.members[0]?.id });
        this.isLoading = false;
      },
      error: (error) => {
        console.error('Error fetching members:', error);
      }
    });
  }

  toggleEditMode() {
    if (this.isEditing) {
      this.saveMember();
    }
    //this.isEditing = !this.isEditing;

    

  }

  onMemberChange(event: any) {
    const selectedMember = this.members.find(member => member.id === event.value);
    // Change value of the dropdown to the selected member
    this.selectedMember = selectedMember ? selectedMember.id : null;
    console.log('Selected member:', selectedMember);
    if (selectedMember) {
      this.memberForm.patchValue(selectedMember);
    }
  }

  onPeselInput(event: Event) {
    const input = event.target as HTMLInputElement;
    input.value = input.value.replace(/\D/g, ''); // Remove non-digit characters
    this.memberForm.get('pesel')?.setValue(input.value); // Update the form control value
  }

  onPostalCodeInput(event: Event) {
    const input = event.target as HTMLInputElement;
    let value = input.value.replace(/[^0-9]/g, '');
    if (value.length > 2) {
      value = value.slice(0, 2) + '-' + value.slice(2, 5);
    }
    input.value = value.slice(0, 6);
    this.memberForm.get('postal_code')?.setValue(input.value);
  }

  bankAccountValidator(control: AbstractControl): ValidationErrors | null {
    const value = (control.value || '').replace(/\s/g, '');
    if (!value) {
      return null; // Pass validation if the value is empty
    }
    return /^\d{26}$/.test(value) ? null : { invalidAccount: true };
  }

  saveMember() {
    if (this.memberForm.valid) {
      const memberData = this.memberForm.value;
      console.log('Saving member:', memberData);
      if (this.selectedMember) {
        //Edit member
        memberData.id = this.selectedMember;
        this.backendService.editMember(memberData).subscribe({
          next: (response) => {
            console.log('Member edited:', response);
            this.fetchMembers();
            this.snackbar.open('Pomyślnie zapisano zmiany', 'Zamknij', {
              duration: 3000,
            });
          }
        });
      } else {
        //New member
        this.backendService.createMember(memberData).subscribe({
          next: (response) => {
            console.log('Member created:', response);
            this.fetchMembers();
            this.onMemberChange({ value: response.id });
          }
        });
      }
    } else {
      console.error('Form is invalid:', this.memberForm.errors);
      this.snackbar.open('Błąd walidacji formularza', 'Zamknij', {
        duration: 3000,
      });
    }
  }

  createNewMember() {
    // Reset the form and set selectedMember to null
    this.memberForm.reset();
    this.selectedMember = null;
    this.isEditing = true;

  }

  copyToClipboard() {
    const member = this.members.find(m => m.id === this.selectedMember);
    if (member) {
      const textToCopy = 
        `Imię i nazwisko: ${member.first_name} ${member.last_name}\r\n` +
        `Ulica i nr domu: ${member.street} ${member.house_no}\r\n` +
        `Kod pocztowy: ${member.postal_code}\r\n` +
        `Miasto: ${member.town}\r\n` +
        `PESEL: ${member.pesel}\r\n` +
        `Miejsce urodzenia: ${member.birth_place}\r\n` +
        `Nr konta: ${member.account_no}\r\n` +
        `Urząd skarbowy: ${member.tax_office}`;
  
      navigator.clipboard.writeText(textToCopy).then(() => {
        this.snackbar.open('Skopiowano do schowka', 'Zamknij', {
          duration: 3000,
        });
      }).catch(err => {
        console.error('Failed to copy text: ', err);
      });
    }
  }

  generateContract() {
    if (this.contractForm.valid && this.selectedMember) {
      const contractTitle = this.contractForm.get('contract_title')?.value;
      const contractType = this.contractForm.get('contract_type')?.value;

      console.log(`Generating contract: ${contractTitle}, Type: ${contractType}`);
      this.backendService.generateContract(contractTitle, contractType, this.selectedMember).subscribe({
        next: (response) => {
          const contentDisposition = response.headers.get('Content-Disposition');
          const fileName = this.backendService.getFileNameFromContentDisposition(contentDisposition);
      
          this.backendService.downloadFile(response.body, fileName);

        },
        error: (error) => {
          console.error('Error generating contract:', error);
          this.snackbar.open('Błąd podczas generowania umowy', 'Zamknij', {
            duration: 3000,
          });
        }
      });
    } else {
      console.error('Contract form is invalid or no member selected:', this.contractForm.errors);
      this.snackbar.open('Błąd walidacji formularza lub brak wybranego członka', 'Zamknij', {
        duration: 3000,
      });
    }
  }

}
