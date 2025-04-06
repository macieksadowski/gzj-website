import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { MenuComponent } from "./menu/menu.component";

@Component({
  selector: 'dashboard-root',
  imports: [RouterOutlet, MenuComponent],
  templateUrl: './dashboard.component.html',
  styleUrl: './dashboard.component.scss'
})
export class DashboardComponent {
  title = 'dashboard';
  menuItems = [
    { name: 'Start', path: '/dashboard' },
    { name: 'Finanse', path: '/dashboard/finances' },
    { name: 'Wydarzenia', path: '/dashboard/events' },
    { name: 'Umowy',
      path: [
        { name: 'Lista umów', path: '/dashboard/contracts' },
        { name: 'Generator', path: '/dashboard/contracts-generator' }
      ]
    },
    { 
      name: 'Utwory',
      path: [
        { name: 'Lista utworów', path: '/dashboard/songs' },
        { name: 'Generator', path: '/dashboard/zaiks' }
      ]
    },
    { name: 'Wyloguj', path: '/logout' }
  ];
}
