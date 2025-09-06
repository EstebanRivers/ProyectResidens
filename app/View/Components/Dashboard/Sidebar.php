<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;
use Illuminate\View\View;

class Sidebar extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.dashboard.sidebar');
    }
}