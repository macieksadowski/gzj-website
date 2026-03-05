import { Injectable } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { TitleStrategy, RouterStateSnapshot } from '@angular/router';

@Injectable()
export class DashboardTitleStrategy extends TitleStrategy {
  private readonly appName = 'Główny Zawór Jazzu';

  constructor(private readonly title: Title) {
    super();
  }

  override updateTitle(snapshot: RouterStateSnapshot): void {
    const routeTitle = this.buildTitle(snapshot);

    if (routeTitle && routeTitle.trim().length > 0) {
      this.title.setTitle(`${routeTitle} | ${this.appName}`);
      return;
    }

    this.title.setTitle(this.appName);
  }
}
