<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ChartComponent extends Component
{
    public $chartData;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->chartData = $chartData;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.chart-component');
    }
}
