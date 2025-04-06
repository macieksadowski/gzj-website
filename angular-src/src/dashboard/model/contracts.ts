export interface Contract {
    id: number;
    contract_amount: number;
    type: ContractTypes;
    member: any;
    event: any;
}

export interface ContractTypes {
    id: number;
    value: string;
}