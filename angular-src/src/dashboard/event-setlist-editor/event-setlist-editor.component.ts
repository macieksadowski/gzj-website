import { CommonModule } from '@angular/common';
import { Component, ElementRef, Inject, ViewChild } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogActions, MatDialogContent, MatDialogRef, MatDialogTitle } from '@angular/material/dialog';
import { MatButtonModule } from '@angular/material/button';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { FormsModule } from '@angular/forms';
import { CdkDragDrop, DragDropModule, moveItemInArray } from '@angular/cdk/drag-drop';
import { DashboardBackendService } from '../services/dashboardbackend.service';
import { Song } from '../model/song';

@Component({
  selector: 'dashboard-event-setlist-editor',
  standalone: true,
  imports: [CommonModule, FormsModule, DragDropModule, MatDialogTitle, MatDialogContent, MatDialogActions, MatButtonModule, MatProgressSpinnerModule],
  templateUrl: './event-setlist-editor.component.html',
  styleUrl: './event-setlist-editor.component.scss',
})
export class EventSetlistEditorComponent {
  songs: Song[] = [];
  availableSongs: Song[] = [];
  setlistSongs: Song[] = [];
  loading = false;
  searchQuery = '';
  selectedAvailableSongId: number | null = null;
  selectedSetlistSongId: number | null = null;
  private originalIndexById = new Map<number, number>();

  @ViewChild('availableSearchInput') availableSearchInput?: ElementRef<HTMLInputElement>;
  @ViewChild('availableListElement') availableListElement?: ElementRef<HTMLUListElement>;
  @ViewChild('setlistListElement') setlistListElement?: ElementRef<HTMLUListElement>;

  constructor(
    private backend: DashboardBackendService,
    public dialogRef: MatDialogRef<EventSetlistEditorComponent>,
    @Inject(MAT_DIALOG_DATA) public data: { selectedSongIds?: number[] }
  ) {
    dialogRef.disableClose = true;
  }

  ngOnInit(): void {
    this.loading = true;
    this.backend.getSongs().subscribe({
      next: (songs) => {
        this.initializeSongs(songs || []);
        this.loading = false;
        this.focusSearchInput();
      },
      error: () => {
        this.loading = false;
      },
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

    const selectedIds = (this.data?.selectedSongIds || [])
      .map((id) => Number(id))
      .filter((id) => Number.isFinite(id));

    const songById = new Map<number, Song>(
      this.songs.map((song) => [Number(song.id), song])
    );

    this.setlistSongs = selectedIds
      .map((id) => songById.get(id))
      .filter((song): song is Song => !!song);

    const selectedSet = new Set<number>(selectedIds);
    this.availableSongs = this.songs.filter((song) => !selectedSet.has(Number(song.id)));
  }

  cancel(): void {
    this.dialogRef.close();
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
    this.setlistSongs.push(song);
    this.selectedSetlistSongId = Number(song.id);
    this.selectedAvailableSongId = null;
    this.searchQuery = '';
    this.focusSearchInput();
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

  onSetlistListKeydown(event: KeyboardEvent): void {
    if (!this.setlistSongs.length) {
      return;
    }

    if (event.key === 'ArrowDown') {
      event.preventDefault();
      this.moveSetlistSelection(1);
      return;
    }

    if (event.key === 'ArrowUp') {
      event.preventDefault();
      this.moveSetlistSelection(-1);
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

  private moveSetlistSelection(step: number): void {
    const list = this.setlistSongs;
    if (!list.length) {
      this.selectedSetlistSongId = null;
      return;
    }

    const currentIndex = list.findIndex((song) => Number(song.id) === this.selectedSetlistSongId);
    const startIndex = currentIndex < 0 ? (step > 0 ? 0 : list.length - 1) : currentIndex;
    const nextIndex = Math.max(0, Math.min(list.length - 1, startIndex + step));
    this.selectedSetlistSongId = Number(list[nextIndex].id);
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

  removeSelectedSong(): void {
    if (this.selectedSetlistSongId === null) {
      return;
    }

    const index = this.setlistSongs.findIndex((song) => Number(song.id) === this.selectedSetlistSongId);
    if (index < 0) {
      return;
    }

    const nextIndexAfterRemoval = Math.min(index, this.setlistSongs.length - 2);
    const [song] = this.setlistSongs.splice(index, 1);
    this.availableSongs.push(song);
    this.availableSongs.sort((left, right) => {
      const leftIndex = this.originalIndexById.get(Number(left.id)) ?? Number.MAX_SAFE_INTEGER;
      const rightIndex = this.originalIndexById.get(Number(right.id)) ?? Number.MAX_SAFE_INTEGER;
      return leftIndex - rightIndex;
    });

    this.selectedAvailableSongId = Number(song.id);
    this.selectedSetlistSongId = this.setlistSongs[nextIndexAfterRemoval]
      ? Number(this.setlistSongs[nextIndexAfterRemoval].id)
      : null;
  }

  onSetlistDrop(event: CdkDragDrop<Song[]>): void {
    moveItemInArray(this.setlistSongs, event.previousIndex, event.currentIndex);
  }

  moveSelectedUp(): void {
    const index = this.getSelectedSetlistIndex();
    if (index <= 0) {
      return;
    }

    moveItemInArray(this.setlistSongs, index, index - 1);
  }

  moveSelectedDown(): void {
    const index = this.getSelectedSetlistIndex();
    if (index < 0 || index >= this.setlistSongs.length - 1) {
      return;
    }

    moveItemInArray(this.setlistSongs, index, index + 1);
  }

  get canMoveSelectedUp(): boolean {
    return this.getSelectedSetlistIndex() > 0;
  }

  get canMoveSelectedDown(): boolean {
    const index = this.getSelectedSetlistIndex();
    return index >= 0 && index < this.setlistSongs.length - 1;
  }

  private getSelectedSetlistIndex(): number {
    if (this.selectedSetlistSongId === null) {
      return -1;
    }

    return this.setlistSongs.findIndex((song) => Number(song.id) === this.selectedSetlistSongId);
  }

  save(): void {
    const selectedSongs = this.setlistSongs
      .map((song) => Number(song.id))
      .filter((id) => Number.isFinite(id));

    this.dialogRef.close(selectedSongs);
  }
}
