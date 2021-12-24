<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Information extends Component
{
    /**
     * Encabezado de la sección
     * @var string
     */
    public $encabezado;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($encabezado)
    {
        $this->encabezado=$encabezado;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.information');
    }
}
