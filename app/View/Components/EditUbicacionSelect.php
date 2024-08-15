<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class EditUbicacionSelect extends Component
{
    public $departamentos;
    public $entidad;
    public string $departamentoName;
    public string $provinciaName;
    public string $distritoName;

    public function __construct($entidad ,string $departamentoName, string $provinciaName, string $distritoName)
    {
        $this->entidad = $entidad;
        $this->departamentos = DB::table('departamentos')->orderBy('nombre')->get();
        $this->departamentoName = $departamentoName;
        $this->provinciaName = $provinciaName;
        $this->distritoName = $distritoName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.edit-ubicacion-select');
    }
}
