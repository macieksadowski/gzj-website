export interface Event {
    id: number;
    name: string;
    date: Date;
    type: EventTypes;
    contracts_amount: number;
    saldo: number;
    contracts: any[];
}

export interface EventDTO {
    id: number;
    name: string;
    date: string;
    type: number;
    contracts_amount: number;
    saldo: number;
    contracts: any[];
}

export interface EventTypes {
    id: number;
    value: string;
}