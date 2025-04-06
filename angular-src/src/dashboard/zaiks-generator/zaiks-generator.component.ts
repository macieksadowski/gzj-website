import { Component, ElementRef, ViewChild } from '@angular/core';
import { Song } from '../model/song';
import { DashboardBackendService } from '../services/dashboardbackend.service';
import DualListbox from 'dual-listbox';
import { CommonModule } from '@angular/common';
import { MatButtonModule } from '@angular/material/button';
import { FormControl, ReactiveFormsModule } from '@angular/forms';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';

@Component({
  selector: 'dashboard-zaiks-generator',
  templateUrl: './zaiks-generator.component.html',
  styleUrl: './zaiks-generator.component.scss',
  imports: [
    CommonModule, 
    MatButtonModule,
    ReactiveFormsModule,
    MatProgressSpinnerModule
  ]
})
export class ZaiksGeneratorComponent {
  songs: Song[] = [];
  @ViewChild('songSelect') songSelect!: ElementRef<HTMLSelectElement>;
  listbox!: DualListbox;
  eventNameControl = new FormControl('');
  loading = false;

  constructor(private backend: DashboardBackendService) { }

  ngOnInit() {
    this.loading = true;
    this.backend.getSongs().subscribe(songs => {
      this.songs = songs;
      setTimeout(() => this.initializeDualListbox(), 100);
    });
  }

  initializeDualListbox() {
    this.listbox = new DualListbox(this.songSelect.nativeElement, {
      searchPlaceholder: 'Wyszukaj',
      availableTitle: 'Wszystkie utwory',
      selectedTitle: 'Wybrane',
      addButtonText: '>',
      removeButtonText: '<',
      addAllButtonText: 'Dodaj wszystkie',
      removeAllButtonText: 'Usuń wszystkie',
      showAddButton: false,
      showAddAllButton: true,
      showRemoveButton: false,
      showRemoveAllButton: true,
      showSortButtons: true,
      upButtonText: "ᐱ",
      downButtonText: "ᐯ",
      draggable: true,
    });

    this.listbox.add_all_button.classList.remove('dual-listbox__button');
    this.listbox.remove_all_button.classList.remove('dual-listbox__button');
    this.listbox.add_all_button.classList.add('listbox-button-custom', 'mdc-button', 'mdc-button--flat', 'mat-mdc-button', 'mat-unthemed', 'mat-mdc-button-base');
    this.listbox.remove_all_button.classList.add('listbox-button-custom', 'mdc-button', 'mdc-button--flat', 'mat-mdc-button', 'mat-unthemed', 'mat-mdc-button-base');
  

    (this.listbox.add_all_button.parentNode as HTMLElement)?.classList.add('listboxFirstButtons');
    (this.listbox.selectedList.parentNode as HTMLElement)?.classList.add('listboxSelectedList');
    (this.listbox.availableList.parentNode as HTMLElement)?.classList.add('listboxAvailableList');

    this.loading = false;
  }

  submitForm() {
    const selectedSongs = this.listbox.selected.map(item => item.dataset['id']).filter((id): id is string => id !== undefined);
    const eventName = this.eventNameControl.value || '';
    console.log('Event name:', eventName);
    console.log('Selected songs:', selectedSongs);

    this.backend.generateZaiksReport(eventName, selectedSongs).subscribe({
      next: (response) => {
        const contentDisposition = response.headers.get('Content-Disposition');
        const fileName = this.backend.getFileNameFromContentDisposition(contentDisposition);
      
        this.backend.downloadFile(response.body, fileName);
      },
      error: (err) => {
        console.error('Error generating ZAiKS report:', err);
      }
    });
  }
}
