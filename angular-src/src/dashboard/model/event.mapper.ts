import { Event, EventDTO } from './event';
import { formatDate } from '@angular/common';

export function mapEventToDTO(ev: Event): EventDTO {
  return {
    id: ev.id,
    name: ev.name,
    date: formatDate(ev.date, 'yyyy-MM-dd', 'pl-PL'),
    type: ev.type.id,
    contracts_amount: ev.contracts_amount,
    saldo: ev.saldo,
    contracts: ev.contracts
    
  };
}
