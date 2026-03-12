import { Event } from './event';

export interface Transaction {
    tr_id: number;
    date: Date;
    amount: number;
    description: string;
    category?: TransactionCategory;
    event?: Event;
    cash_transaction: boolean;
    checkpoint_id?: number | null;

}

export interface TransactionDTO {
    tr_id: number;
    date: string;
    amount: number;
    description: string;
    category?: number;
    event?: number | null;
    cash_transaction: boolean;
}

export interface TransactionCategory {
    id: number;
    name: string;
    type: CategoryType;
}

export interface CategoryType {
    id: number;
    value: string;
}

export enum CategoryTypeEnum {
    OUTCOME = 'WYDATKI',
    INCOME = 'WPŁYWY'
}