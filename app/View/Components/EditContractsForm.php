<?php

namespace App\View\Components;

use App\Models\EnumType;
use App\Models\Member;
use Closure;
use EnumTypeDiscriminator;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditContractsForm extends Component
{

    public $members;
    public $event;
    public  $contractTypes;

    /**
     * Create a new component instance.
     */
    public function __construct($event)
    {
        $this->members = Member::all();

        $this->contractTypes = EnumType::where('discriminator', EnumTypeDiscriminator::CONTRACT_TYPE)->get();

        $this->event = $event;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.edit-contracts-form');
    }
}
