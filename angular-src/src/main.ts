/// <reference types="@angular/localize" />

import { bootstrapApplication } from '@angular/platform-browser';
import { dashboardConfig } from './dashboard/dashboard.config';
import { DashboardComponent } from './dashboard/dashboard.component';

bootstrapApplication(DashboardComponent, dashboardConfig)
  .catch((err) => console.error(err));
