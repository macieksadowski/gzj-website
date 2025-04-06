import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'dashboard-menu',
  imports: [CommonModule],
  templateUrl: './menu.component.html',
  styleUrl: './menu.component.scss'
})
export class MenuComponent {
  @Input() menuItems: MenuItem[] = [];
  isNavbarMobile: boolean = false;

  ngOnInit(): void {
    console.log('Menu items:', this.menuItems);
  }

  isDropdown(path: string | MenuItem[]): boolean {
    return Array.isArray(path);
  }

  isActive(item: MenuItem): boolean {
    return window.location.pathname === item.path;
  }

  isIterablePath(path: string | MenuItem[]): path is MenuItem[] {
    return Array.isArray(path);
  }

  toggleMobileNav(): void {
    this.isNavbarMobile = !this.isNavbarMobile;
    const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
    if (mobileNavToggle) {
      mobileNavToggle.classList.toggle('bi-list');
      mobileNavToggle.classList.toggle('bi-x');
    }
  }


  toggleDropdown(event: Event): void {
    if (this.isNavbarMobile) {
      event.preventDefault();
      const target = event.target as HTMLElement;
      const nextElement = target.nextElementSibling as HTMLElement;
      if (nextElement) {
        nextElement.classList.toggle('dropdown-active');
      }
    }
  }
}

interface MenuItem {
  name: string;
  path: string | MenuItem[];
}