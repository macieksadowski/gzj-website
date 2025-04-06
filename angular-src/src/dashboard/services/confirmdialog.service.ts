import { Injectable } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { ConfirmationDialogComponent } from '../confirmation-dialog/confirmation-dialog.component';
import { firstValueFrom } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class ConfirmDialogService {
  constructor(private dialog: MatDialog) {}

  openConfirmDialog(title: string, message: string): Promise<boolean> {
    const dialogRef = this.dialog.open(ConfirmationDialogComponent, {
      data: { title, message },
    });

    return firstValueFrom(dialogRef.afterClosed());
  }
}
