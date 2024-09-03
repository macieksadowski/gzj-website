<?php

namespace App\View\Components;

use App\Models\Event;
use App\Models\Member;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NewContractForm extends Component
{

    public $members;
    public $events;
    public  $eventSelection;
    public $formAction;

    /**
     * Create a new component instance.
     */
    public function __construct($formAction, $eventSelection)
    {
        $this->members = Member::all();

        $this->formAction = $formAction;
        $this->eventSelection = $eventSelection;

        if($this->eventSelection) {
            $this->events = Event::all();
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.new-contract-form');
    }
}
