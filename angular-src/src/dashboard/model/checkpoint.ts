export interface BalanceCheckpoint {
    id: number;
    checkpoint_date: string;
    balance: number;
    notes?: string | null;
    created_at?: string;
    updated_at?: string;
}

export interface CheckpointState {
    last_checkpoint: BalanceCheckpoint | null;
    pending_transactions_sum: number;
    calculated_balance: number;
}

export interface CreateCheckpointDTO {
    checkpoint_date: string;
    balance: number;
    notes?: string | null;
}
