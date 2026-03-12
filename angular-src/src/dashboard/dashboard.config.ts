import { ApplicationConfig, provideZoneChangeDetection } from '@angular/core';
import { provideRouter } from '@angular/router';
import { TitleStrategy } from '@angular/router';

import { routes } from './dashboard.routes';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { provideHttpClient } from '@angular/common/http';

import { MatPaginatorIntl } from '@angular/material/paginator';
import { getPolishPaginatorIntl } from './shared/custom-paginator-intl.service';

import { provideNgxMask } from 'ngx-mask';
import { DashboardTitleStrategy } from './shared/dashboard-title.strategy';

export const dashboardConfig: ApplicationConfig = {
  providers: [
    provideZoneChangeDetection({ eventCoalescing: true }), 
    provideRouter(routes), 
    provideAnimationsAsync(), 
    provideHttpClient(),
    provideNgxMask({ validation: true }),
    {provide: MatPaginatorIntl, useValue: getPolishPaginatorIntl()},
    {provide: TitleStrategy, useClass: DashboardTitleStrategy}],
};
