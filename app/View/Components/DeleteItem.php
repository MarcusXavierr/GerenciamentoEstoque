<?php

namespace App\View\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class DeleteItem extends Component
{

    public Model $item;
    public String $route;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Model $item, String $route)
    {
        $this->item = $item;
        $this->route = $route;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.delete-item');
    }
}
