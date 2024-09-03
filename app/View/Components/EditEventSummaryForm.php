<?php

namespace App\View\Components;

use App\Models\EnumType;
use Closure;
use EnumTypeDiscriminator;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditEventSummaryForm extends Component
{
    public $event;
    public  $eventTypes;

    /**
     * Create a new component instance.
     */
    public function __construct($event)
    {
        $this->event = $event;
        $this->eventTypes = EnumType::where('discriminator', EnumTypeDiscriminator::EVENT_TYPE)->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.edit-event-summary-form');
    }
}
