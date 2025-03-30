<?php

namespace App\Http\Controllers;

use App\Models\FinanceCategory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use function Laravel\Prompts\alert;

class FinancesController extends Controller
{
    private  $dashboardCtrl;

    public function __construct() {
        $this->dashboardCtrl = new DashboardController();
    }

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
            'event' => 'nullable|exists:App\Models\Event,id'
        ]);
        if($request->has('cash')){
            $validatedData['cash'] = true;
        }
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
            'event' => 'nullable|exists:App\Models\Event,id'
        ]);
        if($request->has('cash')){
            $validatedData['cash'] = true;
        }
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

    public function index(Request $request) {

        $totalSaldo = $this->getTotalSaldo();

        $validator = Validator::make($request->all(), [
            'start-date' => 'date',
            'end-date' => 'date',
        ]);

        $startDate = $request->has('start-date') ? $request->input('start-date') : date('Y-m-d', strtotime('-12 months'));
        $endDate = $request->has('end-date') ? $request->input('end-date') : date('Y-m-d');

        $newTransactionContainer = new Transaction();
        $newTransactionContainer->date = date('Y-m-d');
        $newTransactionContainer->category()->associate(FinanceCategory::where('name', 'Inne')->first());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($request->ajax()) {

            $searchPanes = $request->query('searchPanes');

            $query = Transaction::query();

            if(isset($searchPanes['cat_id'])) {
                $query->where('cat_id', $searchPanes['cat_id']);
            }

            if(isset($searchPanes['ev_id'])) {
                $query->where('ev_id', $searchPanes['ev_id']);
            }

            $query->where('date', '>=', $startDate)->where('date', '<=', $endDate);
            $query->orderBy('date', 'desc');
            $transactions = $query->get();

            return Datatables::of($transactions)
                ->editColumn('ev_id', function ($transaction) {
                    if(isset($transaction->event)) {
                        return ['name' => $transaction->event->name . ' - ' . date('Y', strtotime($transaction->event->date)), 'link' => route('events.show', ['id' => $transaction->event->id])];
                    }
                    return null;
                })
                ->editColumn('cat_id', function ($transaction) {
                    return $transaction->category->name;
                })
                ->editColumn('cash_transaction', function ($transaction) {
                    return $transaction->cash_transaction ? 'Tak' : 'Nie';
                })
                ->addColumn('action', function ($transaction) {
                    return [
                        'edit' => route('editTransaction', ['id' => $transaction->tr_id]),
                        'delete' => route('deleteTransaction', ['id' => $transaction->tr_id])
                    ];
                })
                ->searchPane('cat_id', function() {
                    return FinanceCategory::query()->get(['id as value', 'name as label']);
                })
                ->searchPane('ev_id', function() use ($transactions) {
                    // Convert to collection and sort by event date
                    $sortedTransactions = collect($transactions)
                    ->filter(fn($t) => isset($t->event))
                    ->sortByDesc(fn($t) => $t->event->date);

                    Log::info($sortedTransactions);
                    
                    $events = $sortedTransactions
                    ->map(function($transaction) {
                        return [
                            'value' => $transaction->event->id,
                            'label' => $transaction->event->name . ' - ' . date('Y', strtotime($transaction->event->date))
                        ];
                    })
                    ->unique()
                    ->values()
                    ->all();

                    Log::info($events);

                    return $events;
                })
                ->make(true);
        }

        return $this->dashboardCtrl->default('dashboard-sections.finances', ['startDate' => $startDate, 'endDate' => $endDate, 'newTransactionContainer' => $newTransactionContainer, 'totalSaldo' => $totalSaldo]);
    }

    public function categories()
    {
        $categories = FinanceCategory::all();
        return $this->dashboardCtrl->default('dashboard-sections.finances-categories', ['categories' => $categories]);
    }

    public function getIncomeCategories()
    {
        $incomeCategories = FinanceCategory::whereRelation('type', 'value', 'WPŁYWY')->get();
        return $incomeCategories;
    }

    public function getExpenseCategories()
    {
        $expenseCategories = FinanceCategory::whereRelation('type', 'value', 'WYDATKI')->get();
        return $expenseCategories;
    }

    public function newCategory(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $category = new FinanceCategory();
        $category->name = $request->name;
        $category->save();

        return redirect()->route('categories');
    }

    public function editCategory(Request $request, $id)
    {
        $category = FinanceCategory::find($id);
        $category->name = $request->name;
        $category->save();

        return redirect()->route('categories');
    }
    public function newTransaction(Request $request)
    {
        $validatedData = $this->validateTransactionRequest($request);

        $transaction = new Transaction();
        $this->fillTransaction($transaction, $validatedData);

        $transaction->save();

        return redirect()->route('finances')->withSuccess('Transakcja dodana pomyślnie');
    }

    public function displayTransaction($id)
    {
        $transaction = Transaction::find($id);
        return $this->dashboardCtrl->default('dashboard-sections.edit-transaction', ['transaction' => $transaction]);
    }

    public function editTransaction(Request $request, $id)
    {
        $validatedData = $this->validateTransactionRequest($request);

        $transaction = Transaction::find($id);
        $this->fillTransaction($transaction, $validatedData);

        $transaction->save();

        return redirect()->route('finances')->withSuccess('Transakcja zaktualizowana pomyślnie');
    }

    private function validateTransactionRequest($request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|exists:App\Models\FinanceCategory,id',
            'amount' => 'required|numeric',
            'description' => 'required',
            //event can be null but if it's not null it has to exist
            'event' => 'nullable|exists:App\Models\Event,id'
        ]);
        if($request->has('cash')){
            $validated['cash'] = true;
        }
        return $validated;
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
        if(isset($validatedData['cash'])) {
            $transaction->cash_transaction = true;
        }
    }

    public function deleteTransaction(Request $request)
    {
        $transactionId = $request->id;
        if (isset($transactionId) && Transaction::where('tr_id', $transactionId)->exists()) {
            $transaction = Transaction::find($transactionId);
            $transaction->delete();
            return redirect()->back()->with('success', 'Transakcja usunięta pomyślnie');
        } else {
            return redirect()->back()->with('error', 'Transakcja nie istnieje');
        }
    }

    private function getTotalSaldo()
    {
        $totalSaldo = Transaction::sum('amount');
        return $totalSaldo;
    }

}
