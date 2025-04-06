import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { EventsComponent } from './events/events.component';
import { EventSummaryComponent } from './event-summary/event-summary.component';
import { TransactionsComponent } from './transactions/transactions.component';
import { ZaiksGeneratorComponent } from './zaiks-generator/zaiks-generator.component';
import { SongsComponent } from './songs/songs.component';
import { ContractsComponent } from './contracts/contracts.component';
import { MembersComponent } from './members/members.component';

export const routes: Routes = [
  {
    path: 'dashboard',
    children: [
      { path: 'events', component: EventsComponent, title: 'Wydarzenia' },
      { path: 'events/:id', component: EventSummaryComponent },
      { path: 'finances', component: TransactionsComponent, title: 'Finanse' },
      { path: 'zaiks', component: ZaiksGeneratorComponent, title: 'Generator ZAiKS' },
      { path: 'songs', component: SongsComponent, title: 'Wszystkie utwory' },
      { path: 'contracts', component: ContractsComponent, title: 'Umowy' },
      { path: 'members', component: MembersComponent, title: 'Członkowie' }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class DashboardRoutingModule { }