<?php

namespace App\View\Components;

use App\Models\Event;
use App\Models\FinanceCategory;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditTransactionForm extends Component
{

    public $financeCategories;

    public $events;

    public $transaction;
    /**
     * Create a new component instance.
     */
    public function __construct($transaction = null)
    {
        $this->transaction = $transaction;

        $this->financeCategories = FinanceCategory::all();
        
        $this->events = Event::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.edit-transaction-form');
    }
}
