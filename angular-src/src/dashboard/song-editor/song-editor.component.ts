import { Component, Inject } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { Song } from '../model/song';
import { MatDialogRef, MAT_DIALOG_DATA, MatDialogTitle, MatDialogActions, MatDialogContent } from '@angular/material/dialog';
import { MatFormFieldModule } from '@angular/material/form-field';
import { CommonModule } from '@angular/common';
import { MatButtonModule } from '@angular/material/button';
import { MatInputModule } from '@angular/material/input';
import { MatSelectModule } from '@angular/material/select';
import { DashboardBackendService } from '../services/dashboardbackend.service';

@Component({
  selector: 'dashboard-song-editor',
    imports: [
      CommonModule,
      MatSelectModule,
      MatButtonModule,
      MatDialogTitle,
      MatDialogContent,
      MatDialogActions,
      MatFormFieldModule,
      MatInputModule,
      FormsModule,
      ReactiveFormsModule,
    ],
  templateUrl: './song-editor.component.html',
  styleUrl: './song-editor.component.scss'
})
export class SongEditorComponent {
  song: Song;

  constructor(
    public dialogRef: MatDialogRef<SongEditorComponent>,
    @Inject(MAT_DIALOG_DATA) public data: { song: Song },
    private backendService: DashboardBackendService
  ) {
    this.song = Object.assign({}, this.data.song);
  }

  cancel() {
    this.dialogRef.close();
  }

  save() {
    if (this.song.id) {
      this.backendService.updateSong(this.song).subscribe((updatedSong) => {
        console.log('Song updated:', updatedSong);
        this.dialogRef.close(updatedSong);
      });
    } else {
      this.backendService.createSong(this.song).subscribe((newSong) => {
        console.log('Song created:', newSong);
        this.song.id = newSong.id;
        this.dialogRef.close(newSong);
      });
    }
  }

}
