<?php

namespace App\Http\Controllers;

use App\Models\FinanceCategory;
use App\Models\Transaction;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class FinancesController extends Controller
{
    private  $dashboardCtrl;

    public function __construct() {
        $this->dashboardCtrl = new DashboardController();
    }

    public function index(Request $request) {

        $validator = Validator::make($request->all(), [
            'start-date' => 'date',
            'end-date' => 'date',
        ]);

        $startDate = $request->has('start-date') ? $request->input('start-date') : date('Y-m-d', strtotime('-12 months'));
        $endDate = $request->has('end-date') ? $request->input('end-date') : date('Y-m-d');

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
                ->searchPane('ev_id', function() {
                    $events = Event::orderBy('date', 'desc')->get();
                    $result = $events->map(function ($event) {
                        return [
                            'value' => $event->id,
                            'label' => $event->name . ' - ' . date('Y', strtotime($event->date)),
                        ];
                    });
                
                    return $result;

                })
                ->make(true);
        }

        return $this->dashboardCtrl->default('dashboard-sections.finances', ['startDate' => $startDate, 'endDate' => $endDate]);
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
        $validatedData = $request->validate([
            'date' => 'required|date',
            'category' => 'required|exists:App\Models\FinanceCategory,id',
            'amount' => 'required|numeric',
            'description' => 'required',
            'event' => 'exists:App\Models\Event,id',
            'cash-transaction' => 'required|boolean'
        ]);

        $transaction = new Transaction();
        $transaction->date = $validatedData['date'];
        $transaction->category()->associate(FinanceCategory::find($validatedData['category']));
        $transaction->amount = $validatedData['amount'];
        $transaction->description = $validatedData['description'];
        $transaction->event()->associate($validatedData['event']);
        $transaction->cash_transaction = $validatedData['cash-transaction'];

        $transaction->save();

        return redirect()->withSuccess('Transakcja dodana pomyślnie')->route('finances');
    }

    public function displayTransaction($id)
    {
        $transaction = Transaction::find($id);
        return $this->dashboardCtrl->default('dashboard-sections.edit-transaction', ['transaction' => $transaction]);
    }

    public function editTransaction(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'category' => 'required|exists:App\Models\FinanceCategory,id',
            'amount' => 'required|numeric',
            'description' => 'required',
            'event' => 'exists:App\Models\Event,id',
            'cash-transaction' => 'required|boolean'
        ]);

        $transaction = Transaction::find($id);
        $transaction->date = $validatedData['date'];
        $transaction->category()->associate(FinanceCategory::find($validatedData['category']));
        $transaction->amount = $validatedData['amount'];
        $transaction->description = $validatedData['description'];
        $transaction->event()->associate($validatedData['event']);
        $transaction->cash_transaction = $validatedData['cash-transaction'];

        $transaction->save();

        return redirect()->withSuccess('Transakcja zaktualizowana pomyślnie')->route('finances');
    }

    public function deleteTransaction(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:App\Models\Transaction,id',
        ]);
        $transaction = Transaction::find($validatedData['id']);
        $transaction->delete();

        return redirect()->with('success', 'Transakcja usunięta pomyślnie')->route('finances');
    }

}
