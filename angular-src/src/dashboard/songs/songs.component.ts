import { Component, inject, ViewChild } from '@angular/core';
import { DashboardBackendService } from '../services/dashboardbackend.service';
import { MatDialog } from '@angular/material/dialog';
import { MatSort, MatSortModule } from '@angular/material/sort';
import { MatPaginator, MatPaginatorModule } from '@angular/material/paginator';
import { MatTableDataSource, MatTableModule } from '@angular/material/table';
import { Song } from '../model/song';
import { Normalisator } from '../shared/normalisator';
import { CommonModule } from '@angular/common';
import { MatButtonModule } from '@angular/material/button';
import { MatOptionModule } from '@angular/material/core';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { MatSelectModule } from '@angular/material/select';
import { SongEditorComponent } from '../song-editor/song-editor.component';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatIconModule } from '@angular/material/icon';
import { ConfirmDialogService } from '../services/confirmdialog.service';

@Component({
  selector: 'dashboard-songs',
  imports: [
    CommonModule,
    MatTableModule,
    MatPaginatorModule,
    MatSortModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    MatSelectModule,
    MatOptionModule,
    MatProgressSpinnerModule,
    MatIconModule
  ],
  templateUrl: './songs.component.html',
  styleUrl: './songs.component.scss'
})
export class SongsComponent {
  loading: boolean = false;

  @ViewChild(MatSort) sort!: MatSort;
  @ViewChild(MatPaginator) paginator!: MatPaginator;

  displayedColumns = ['id', 'title', 'performer', 'composer', 'text_author', 'actions'];
  dataSource: MatTableDataSource<Song> = new MatTableDataSource<Song>();

  constructor(
    private songsService: DashboardBackendService,
    private dialog: MatDialog,
    private snackbar: MatSnackBar,
    private confirmDialog: ConfirmDialogService,
  ) { }

  fetchSongs() {
    this.songsService.getSongs().subscribe(data => {
      this.dataSource = new MatTableDataSource<Song>(data);
      console.log('Loaded songs:', data); // Debug log

      this.dataSource.paginator = this.paginator;
      this.dataSource.sortingDataAccessor = (item, property) => {
        switch (property) {
          case 'id':
            return item.id;
          case 'title':
            return Normalisator.normalizePolishChars(item.title);
          case 'performer':
            return Normalisator.normalizePolishChars(item.performer);
          default:
            return item[property as keyof Song];
        }
      }

      this.dataSource.sort = this.sort;

      this.dataSource.filterPredicate = (data: Song, filter: string) => {
        const normalizedFilter = Normalisator.normalizePolishChars(filter);
        return (
          Normalisator.normalizePolishChars(data.title).toLowerCase().includes(normalizedFilter)
        );
      };
      this.loading = false;
    });


  }

  ngOnInit() {
    this.loading = true;
    this.fetchSongs();
  }

  applyTitleFilter(keyboardEvent: KeyboardEvent) {
    const filterValue = (keyboardEvent.target as HTMLInputElement).value;
    this.dataSource.filter = filterValue;
  }

  newSong() {
    const dialogRef = this.dialog.open(SongEditorComponent, {
      data: { song: { id: 0, title: '', title_official: '', performer: '', composer: '', text_author: '' } },
      width: '50vw',
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        this.fetchSongs();
        this.snackbar.open('Dodano nowy utwór "' + result.title + '"', 'Zamknij', {
          duration: 3000,
          panelClass: ['snackbar-success'],
        });
      } else {
        this.snackbar.open('Błąd dodawania utworu', 'Zamknij', {
          duration: 3000,
          panelClass: ['snackbar-error'],
        });
      }
    });
  }

  deleteSong(song: number) {
    this.confirmDialog.openConfirmDialog('Usuwanie utworu', `Czy na pewno chcesz usunąć utwór o id: ${song}`).then((confirmed) => {
      if (confirmed) {
        this.songsService.deleteSong(song).subscribe((response) => {
          console.log(response);
          this.snackbar.open(response.message, 'Zamknij', {
            duration: 3000,
            panelClass: ['snackbar-success'],
          });
          this.fetchSongs();
        });
      }
    });
  }

  editSong(song: number) {
    const songToEdit = this.dataSource.data.find(s => s.id === song);
    if (songToEdit === undefined) {
      return;
    }
    const dialogRef = this.dialog.open(SongEditorComponent, {
      data: { song: songToEdit },
      width: '50vw',
    });
    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        this.fetchSongs();
        this.snackbar.open('Zaktualizowano utwór "' + result.title + '"', 'Zamknij', {
          duration: 3000,
          panelClass: ['snackbar-success'],
        });
      } else {
        this.snackbar.open('Błąd aktualizacji utworu', 'Zamknij', {
          duration: 3000,
          panelClass: ['snackbar-error'],
        });
      }
    });
  }
}
