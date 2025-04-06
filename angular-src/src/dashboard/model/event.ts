export interface Event {
    id: number;
    name: string;
    date: Date;
    type: EventTypes;
    contracts_amount: number;
    saldo: number;
    contracts: any[];
}

export interface EventTypes {
    id: number;
    value: string;
}