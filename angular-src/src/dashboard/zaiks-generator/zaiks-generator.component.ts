import { Component, ElementRef, ViewChild } from '@angular/core';
import { Song } from '../model/song';
import { DashboardBackendService } from '../services/dashboardbackend.service';
import { CommonModule } from '@angular/common';
import { MatButtonModule } from '@angular/material/button';
import { FormControl, ReactiveFormsModule } from '@angular/forms';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { MatCardModule } from '@angular/material/card';
import { CdkDragDrop, DragDropModule, moveItemInArray } from '@angular/cdk/drag-drop';
import { FormsModule } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatSnackBar, MatSnackBarModule } from '@angular/material/snack-bar';

@Component({
  selector: 'dashboard-zaiks-generator',
  templateUrl: './zaiks-generator.component.html',
  styleUrl: './zaiks-generator.component.scss',
  imports: [
    CommonModule, 
    MatButtonModule,
    ReactiveFormsModule,
    MatProgressSpinnerModule,
    MatCardModule,
    DragDropModule,
    FormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatSnackBarModule
  ]
})
export class ZaiksGeneratorComponent {
  songs: Song[] = [];
  availableSongs: Song[] = [];
  selectedSongs: Song[] = [];
  eventNameControl = new FormControl('');
  loading = false;
  generating = false;
  searchQuery = '';
  selectedAvailableSongId: number | null = null;
  selectedSetlistSongId: number | null = null;
  private originalIndexById = new Map<number, number>();

  @ViewChild('availableSearchInput') availableSearchInput?: ElementRef<HTMLInputElement>;
  @ViewChild('availableListElement') availableListElement?: ElementRef<HTMLUListElement>;

  constructor(
    private backend: DashboardBackendService,
    private snackbar: MatSnackBar,
  ) { }

  ngOnInit() {
    this.loading = true;
    this.backend.getSongs().subscribe({
      next: (songs) => {
        this.initializeSongs(songs || []);
        this.loading = false;
        this.focusSearchInput();
      },
      error: () => {
        this.loading = false;
      }
    });
  }

  get filteredAvailableSongs(): Song[] {
    const query = this.searchQuery.trim().toLowerCase();
    if (!query) {
      return this.availableSongs;
    }

    return this.availableSongs.filter((song) => String(song?.title || '').toLowerCase().includes(query));
  }

  private initializeSongs(songs: Song[]): void {
    this.songs = songs;
    this.originalIndexById = new Map<number, number>();
    this.songs.forEach((song, index) => this.originalIndexById.set(Number(song.id), index));
    this.availableSongs = [...this.songs];
    this.selectedSongs = [];
  }

  selectAvailable(song: Song): void {
    this.selectedAvailableSongId = Number(song.id);
  }

  selectSetlist(song: Song): void {
    this.selectedSetlistSongId = Number(song.id);
  }

  addSelectedSong(): void {
    if (this.selectedAvailableSongId === null) {
      return;
    }

    const index = this.availableSongs.findIndex((song) => Number(song.id) === this.selectedAvailableSongId);
    if (index < 0) {
      return;
    }

    const [song] = this.availableSongs.splice(index, 1);
    this.selectedSongs.push(song);
    this.selectedSetlistSongId = Number(song.id);
    this.selectedAvailableSongId = null;
    this.searchQuery = '';
    this.focusSearchInput();
  }

  removeSelectedSong(): void {
    if (this.selectedSetlistSongId === null) {
      return;
    }

    const index = this.selectedSongs.findIndex((song) => Number(song.id) === this.selectedSetlistSongId);
    if (index < 0) {
      return;
    }

    const nextIndexAfterRemoval = Math.min(index, this.selectedSongs.length - 2);
    const [song] = this.selectedSongs.splice(index, 1);
    this.availableSongs.push(song);
    this.availableSongs.sort((left, right) => {
      const leftIndex = this.originalIndexById.get(Number(left.id)) ?? Number.MAX_SAFE_INTEGER;
      const rightIndex = this.originalIndexById.get(Number(right.id)) ?? Number.MAX_SAFE_INTEGER;
      return leftIndex - rightIndex;
    });

    this.selectedAvailableSongId = Number(song.id);
    this.selectedSetlistSongId = this.selectedSongs[nextIndexAfterRemoval]
      ? Number(this.selectedSongs[nextIndexAfterRemoval].id)
      : null;
  }

  onSelectedSongsDrop(event: CdkDragDrop<Song[]>): void {
    moveItemInArray(this.selectedSongs, event.previousIndex, event.currentIndex);
  }

  moveSelectedUp(): void {
    const index = this.getSelectedSongIndex();
    if (index <= 0) {
      return;
    }

    moveItemInArray(this.selectedSongs, index, index - 1);
  }

  moveSelectedDown(): void {
    const index = this.getSelectedSongIndex();
    if (index < 0 || index >= this.selectedSongs.length - 1) {
      return;
    }

    moveItemInArray(this.selectedSongs, index, index + 1);
  }

  get canMoveSelectedUp(): boolean {
    return this.getSelectedSongIndex() > 0;
  }

  get canMoveSelectedDown(): boolean {
    const index = this.getSelectedSongIndex();
    return index >= 0 && index < this.selectedSongs.length - 1;
  }

  onSearchKeydown(event: KeyboardEvent): void {
    if (event.key !== 'Enter') {
      return;
    }

    event.preventDefault();
    if (!this.filteredAvailableSongs.length) {
      return;
    }

    if (!this.filteredAvailableSongs.some((song) => Number(song.id) === this.selectedAvailableSongId)) {
      this.selectedAvailableSongId = Number(this.filteredAvailableSongs[0].id);
    }

    this.focusAvailableList();
  }

  onAvailableListKeydown(event: KeyboardEvent): void {
    if (!this.filteredAvailableSongs.length) {
      return;
    }

    if (event.key === 'ArrowDown') {
      event.preventDefault();
      this.moveAvailableSelection(1);
      return;
    }

    if (event.key === 'ArrowUp') {
      event.preventDefault();
      this.moveAvailableSelection(-1);
      return;
    }

    if (event.key === 'Enter') {
      event.preventDefault();
      this.addSelectedSong();
    }
  }

  onSelectedListKeydown(event: KeyboardEvent): void {
    if (!this.selectedSongs.length) {
      return;
    }

    if (event.key === 'ArrowDown') {
      event.preventDefault();
      this.moveSelectedSelection(1);
      return;
    }

    if (event.key === 'ArrowUp') {
      event.preventDefault();
      this.moveSelectedSelection(-1);
      return;
    }

    if (event.key === 'Delete') {
      event.preventDefault();
      this.removeSelectedSong();
    }
  }

  private moveAvailableSelection(step: number): void {
    const list = this.filteredAvailableSongs;
    if (!list.length) {
      this.selectedAvailableSongId = null;
      return;
    }

    const currentIndex = list.findIndex((song) => Number(song.id) === this.selectedAvailableSongId);
    const startIndex = currentIndex < 0 ? (step > 0 ? 0 : list.length - 1) : currentIndex;
    const nextIndex = Math.max(0, Math.min(list.length - 1, startIndex + step));
    this.selectedAvailableSongId = Number(list[nextIndex].id);
  }

  private moveSelectedSelection(step: number): void {
    const list = this.selectedSongs;
    if (!list.length) {
      this.selectedSetlistSongId = null;
      return;
    }

    const currentIndex = list.findIndex((song) => Number(song.id) === this.selectedSetlistSongId);
    const startIndex = currentIndex < 0 ? (step > 0 ? 0 : list.length - 1) : currentIndex;
    const nextIndex = Math.max(0, Math.min(list.length - 1, startIndex + step));
    this.selectedSetlistSongId = Number(list[nextIndex].id);
  }

  private getSelectedSongIndex(): number {
    if (this.selectedSetlistSongId === null) {
      return -1;
    }

    return this.selectedSongs.findIndex((song) => Number(song.id) === this.selectedSetlistSongId);
  }

  private focusAvailableList(): void {
    setTimeout(() => {
      this.availableListElement?.nativeElement?.focus();
    }, 0);
  }

  private focusSearchInput(): void {
    setTimeout(() => {
      const input = this.availableSearchInput?.nativeElement;
      if (!input) {
        return;
      }

      input.focus();
      input.select();
    }, 0);
  }

  submitForm() {
    const selectedSongs = this.selectedSongs
      .map((song) => String(song.id))
      .filter((id) => id.length > 0);

    if (!selectedSongs.length) {
      this.snackbar.open('Wybierz co najmniej jeden utwór.', 'Zamknij', { duration: 3500 });
      return;
    }

    const eventName = this.eventNameControl.value || '';
    this.generating = true;

    this.backend.generateZaiksReport(eventName, selectedSongs).subscribe({
      next: (response) => {
        const contentDisposition = response.headers.get('Content-Disposition');
        const fileName = this.backend.getFileNameFromContentDisposition(contentDisposition);
      
        this.backend.downloadFile(response.body, fileName);
        this.snackbar.open('Wygenerowano dokument ZAiKS.', 'Zamknij', { duration: 3000 });
        this.generating = false;
      },
      error: () => {
        this.snackbar.open('Błąd podczas generowania dokumentu ZAiKS.', 'Zamknij', { duration: 5000 });
        this.generating = false;
      }
    });
  }
}
