import { CommonModule } from '@angular/common';
import { Component, Input } from '@angular/core';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatTooltipModule } from '@angular/material/tooltip';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'dashboard-fab-navigation',
  standalone: true,
  imports: [CommonModule, MatButtonModule, MatIconModule, MatTooltipModule, RouterLink],
  template: `
    <div class="fab-navigation" aria-label="Nawigacja rekordów">
      <button
        mat-fab
        type="button"
        color="primary"
        [disabled]="isBusy || !previousRoute"
        [routerLink]="previousRoute || []"
        [matTooltip]="previousTooltip"
        [attr.aria-label]="previousTooltip"
      >
        <mat-icon>arrow_back</mat-icon>
      </button>

      <button
        mat-fab
        type="button"
        color="primary"
        [disabled]="isBusy || !nextRoute"
        [routerLink]="nextRoute || []"
        [matTooltip]="nextTooltip"
        [attr.aria-label]="nextTooltip"
      >
        <mat-icon>arrow_forward</mat-icon>
      </button>
    </div>
  `,
  styles: [
    `
      .fab-navigation {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 50;
        display: flex;
        flex-direction: column;
        gap: 12px;
      }

      @media (max-width: 768px) {
        .fab-navigation {
          right: 12px;
          bottom: 12px;
          gap: 10px;
        }
      }
    `,
  ],
})
export class FabNavigationComponent {
  @Input() previousRoute: string | any[] | null = null;
  @Input() nextRoute: string | any[] | null = null;
  @Input() isBusy: boolean = false;

  @Input() previousTooltip: string = 'Poprzedni';
  @Input() nextTooltip: string = 'Następny';
}
