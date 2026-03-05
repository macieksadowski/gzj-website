<?php

namespace App\Http\Controllers;

use App\Models\FinanceCategory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function deleteTransactionApi($id) {
        $transaction = Transaction::find($id);
        $transaction->delete();
        return response()->json(['message' => "Transakcja o id: $id usunięta pomyślnie"]);
    }

    public function editTransactionApi(Request $request, $id) {
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
        $transaction = Transaction::find($id);
        $this->fillTransaction($transaction, $validatedData);
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


    private function fillTransaction($transaction, $validatedData)
    {
        $transaction->date = $validatedData['date'];
        $transaction->category()->associate(FinanceCategory::find($validatedData['category']));
        $transaction->amount = $validatedData['amount'];
        $transaction->description = $validatedData['description'];
        if(isset($validatedData['event'])) {
            $transaction->event()->associate($validatedData['event']);
        }
        $transaction->cash_transaction = (bool)$validatedData['cash_transaction'];
    }

}
