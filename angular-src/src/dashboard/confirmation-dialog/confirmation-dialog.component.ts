import { AfterViewInit, Component, ElementRef, Inject, ViewChild } from '@angular/core';
import { MatButtonModule } from '@angular/material/button';
import { MAT_DIALOG_DATA, MatDialogActions, MatDialogContent, MatDialogRef, MatDialogTitle } from '@angular/material/dialog';

@Component({
  selector: 'dashboard-confirmation-dialog',
  imports: [MatDialogContent, MatDialogTitle, MatDialogActions, MatButtonModule],
  template: `
      <h2 mat-dialog-title>{{ data.title }}</h2>
      <mat-dialog-content>
        <p>{{ data.message }}</p>
      </mat-dialog-content>
      <mat-dialog-actions>
        <button mat-button (click)="onConfirm()">Tak</button>
        <button #cancelButton mat-button (click)="onCancel()">Nie</button>
      </mat-dialog-actions>
  `
})
export class ConfirmationDialogComponent implements AfterViewInit {
  @ViewChild('cancelButton') cancelButton?: ElementRef<HTMLButtonElement>;

  constructor(
    public dialogRef: MatDialogRef<ConfirmationDialogComponent>,
    @Inject(MAT_DIALOG_DATA) public data: { title: string; message: string }
  ) { }

  ngAfterViewInit(): void {
    this.cancelButton?.nativeElement.focus();
  }

  onConfirm(): void {
    this.dialogRef.close(true);
  }

  onCancel(): void {
    this.dialogRef.close(false);
  }
}