<?php

namespace App\Http\Controllers;

use App\Models\BalanceCheckpoint;
use App\Models\FinanceCategory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancesController extends Controller
{
    public function getAllTransactions() {
        $transactions = Transaction::all();
        $transactions->load('event', 'category');
        return response()->json($transactions);
    }

    public function getTransaction($id) {
        $transaction = Transaction::find($id);
        return response()->json($transaction);
    }

    public function getTotalSaldoJson() {
        $totalSaldo = Transaction::sum('amount');
        return response()->json($totalSaldo);
    }

    public function getCheckpointState()
    {
        $lastCheckpoint = BalanceCheckpoint::orderByDesc('id')->first();
        $pendingTransactionsSum = (float) Transaction::whereNull('checkpoint_id')->sum('amount');

        if ($lastCheckpoint) {
            $calculatedBalance = (float) $lastCheckpoint->balance + $pendingTransactionsSum;
        } else {
            $calculatedBalance = (float) Transaction::sum('amount');
        }

        return response()->json([
            'last_checkpoint' => $lastCheckpoint,
            'pending_transactions_sum' => $pendingTransactionsSum,
            'calculated_balance' => $calculatedBalance,
        ]);
    }

    public function createCheckpoint(Request $request)
    {
        $validatedData = $request->validate([
            'checkpoint_date' => 'required|date',
            'balance' => 'required|numeric',
            'notes' => 'nullable|string|max:500',
        ]);

        $checkpoint = DB::transaction(function () use ($validatedData) {
            $checkpoint = BalanceCheckpoint::create([
                'checkpoint_date' => $validatedData['checkpoint_date'],
                'balance' => $validatedData['balance'],
                'notes' => $validatedData['notes'] ?? null,
            ]);

            Transaction::whereNull('checkpoint_id')->update([
                'checkpoint_id' => $checkpoint->id,
            ]);

            return $checkpoint;
        });

        return response()->json($checkpoint, 201);
    }

    public function deleteTransactionApi($id) {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => "Transakcja o id: $id nie istnieje"], 404);
        }

        if (!is_null($transaction->checkpoint_id)) {
            return response()->json([
                'message' => 'Nie można usuwać transakcji przypisanych do checkpointu. Dodaj transakcję korekcyjną.'
            ], 422);
        }

        $transaction->delete();
        return response()->json(['message' => "Transakcja o id: $id usunięta pomyślnie"]);
    }

    public function editTransactionApi(Request $request, $id) {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => "Transakcja o id: $id nie istnieje"], 404);
        }

        $isCheckpointLocked = !is_null($transaction->checkpoint_id);

        $validationRules = [
            'category' => 'required|exists:App\Models\FinanceCategory,id',
            //event can be null but if it's not null it has to exist
            'event' => 'nullable|exists:App\Models\Event,id',
        ];

        if (!$isCheckpointLocked) {
            $validationRules['date'] = 'required|date';
            $validationRules['amount'] = 'required|numeric';
            $validationRules['description'] = 'required';
            $validationRules['cash_transaction'] = 'nullable|boolean';
        } else {
            $validationRules['date'] = 'sometimes|date';
            $validationRules['amount'] = 'sometimes|numeric';
            $validationRules['description'] = 'sometimes|string';
            $validationRules['cash_transaction'] = 'sometimes|boolean';
        }

        $validatedData = $request->validate($validationRules);

        if ($request->has('cash_transaction')) {
            $validatedData['cash_transaction'] = $request->boolean('cash_transaction');
        }

        if ($isCheckpointLocked && $this->hasRestrictedCheckpointChanges($request, $transaction)) {
            return response()->json([
                'message' => 'Dla transakcji przypisanych do checkpointu możesz zmienić tylko kategorię i wydarzenie.'
            ], 422);
        }

        $this->fillTransaction($transaction, $validatedData, !$isCheckpointLocked);
        $transaction->save();
        return response()->json($transaction);
    }

    public function createTransaction(Request $request) {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'category' => 'required|exists:App\Models\FinanceCategory,id',
            'amount' => 'required|numeric',
            'description' => 'required',
            //event can be null but if it's not null it has to exist
            'event' => 'nullable|exists:App\Models\Event,id',
            'cash_transaction' => 'nullable|boolean'
        ]);
        $validatedData['cash_transaction'] = $request->boolean('cash_transaction');
        $transaction = new Transaction();
        $this->fillTransaction($transaction, $validatedData);
        $transaction->save();
        return response()->json($transaction);
    }

    public function getAllCategories() {
        $categories = FinanceCategory::all();
        $categories->load('type');
        $categories->transform(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'type' => [
                    'id' => $category->type->id,
                    'value' => $category->type->value
                ]
            ];
        });
        return response()->json($categories);
    }


    private function fillTransaction($transaction, $validatedData, $allowCoreFields = true)
    {
        $transaction->category()->associate(FinanceCategory::find($validatedData['category']));

        if (array_key_exists('event', $validatedData)) {
            if (is_null($validatedData['event'])) {
                $transaction->event()->dissociate();
            } else {
                $transaction->event()->associate($validatedData['event']);
            }
        }

        if ($allowCoreFields) {
            $transaction->date = $validatedData['date'];
            $transaction->amount = $validatedData['amount'];
            $transaction->description = $validatedData['description'];
            $transaction->cash_transaction = (bool)($validatedData['cash_transaction'] ?? false);
        }
    }

    private function hasRestrictedCheckpointChanges(Request $request, Transaction $transaction)
    {
        $currentDate = date('Y-m-d', strtotime((string)$transaction->date));

        if ($request->has('date') && $request->input('date') !== $currentDate) {
            return true;
        }

        if ($request->has('amount') && (float)$request->input('amount') !== (float)$transaction->amount) {
            return true;
        }

        if ($request->has('description') && $request->input('description') !== $transaction->description) {
            return true;
        }

        if ($request->has('cash_transaction') && $request->boolean('cash_transaction') !== (bool)$transaction->cash_transaction) {
            return true;
        }

        return false;
    }

}
