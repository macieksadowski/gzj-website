export interface Transaction {
    tr_id: number;
    date: Date;
    amount: number;
    description: string;
    category?: TransactionCategory;
    event?: Event;
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