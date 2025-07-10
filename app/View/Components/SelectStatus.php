<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;

class SelectStatus extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Collection $options, public int $reservationId, public string $selected) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-status');
    }
}
