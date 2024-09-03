<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DeleteModal extends Component
{

    public $entityName;
    public $entityId;

    /**
     * Create a new component instance.
     */
    public function __construct($name, $id)
    {
        $this->entityName = $name;
        $this->entityId = $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.delete-modal');
    }
}
