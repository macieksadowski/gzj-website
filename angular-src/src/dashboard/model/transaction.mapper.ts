import { Transaction, TransactionDTO } from './transaction';
import { formatDate } from '@angular/common';

export function mapTransactionToDTO(tx: Transaction): TransactionDTO {
  return {
    tr_id: tx.tr_id,
    date: formatDate(tx.date, 'yyyy-MM-dd', 'pl-PL'),
    amount: tx.amount,
    description: tx.description,
    category: tx.category ? tx.category.id : undefined,
    event: tx.event ? tx.event.id : undefined,
    cash_transaction: tx.cash_transaction
  };
}
