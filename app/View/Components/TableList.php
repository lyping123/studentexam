<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class TableList extends Component
{
    public array $headers;
    public Collection $data;
    public array $actions;

    public function __construct(array $headers,Collection $data,array $actions=[])
    {
        $this->headers=$headers;
        $this->data=$data;
        $this->actions=$actions;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // dd($this->headers, $this->data, $this->actions);
        return view('components.table-list');
    }
}
