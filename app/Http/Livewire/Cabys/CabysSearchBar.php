<?php

namespace App\Http\Livewire\Cabys;

use App\Models\Cabys;
use Livewire\Component;

class CabysSearchBar extends Component
{
    public $allCabys, $query, $cabys;

    public function mount()
    {
        $this->query = '';
        $this->allCabys = array();
    }
    public function render()
    {
        if ($this->query != "") {
            $this->updatedQuery();
        } else {
            $this->allCabys = array();
        }
        return view('livewire.cabys.cabys-search-bar');
    }
    public function updatedQuery()
    {
        $this->allCabys = Cabys::where('codigo', 'like', $this->query . '%')
        ->orwhere('descripcion', 'like', '%'.$this->query . '%')->get();
    }
}
