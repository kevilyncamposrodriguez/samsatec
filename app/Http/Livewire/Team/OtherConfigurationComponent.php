<?php

namespace App\Http\Livewire\Team;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Ramsey\Uuid\Type\Integer;

class OtherConfigurationComponent extends Component
{
    public $bo_inventory = false, $team,$cash_register = false;
    public function mount($team)
    {
        $this->bo_inventory = (int)Auth::user()->currentTeam->bo_inventory;
        $this->cash_register = (int)Auth::user()->currentTeam->cash_register;
        $this->team = $team;
    }
    public function render()
    {
        return view('livewire.team.other-configuration-component');
    }
    public function updateConfiguration()
    {
        DB::beginTransaction();
        try {
            $this->team->update([
                'bo_inventory' =>($this->team->bo_inventory)?'1':(($this->bo_inventory) ? '1' : '0'),
                'cash_register' => ($this->cash_register) ? '1' : '0'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('saved');
    }
}
