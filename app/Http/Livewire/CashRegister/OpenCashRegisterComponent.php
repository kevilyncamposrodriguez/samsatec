<?php

namespace App\Http\Livewire\CashRegister;

use App\Models\CashRegister;
use App\Models\TeamUser;
use App\Models\Terminal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OpenCashRegisterComponent extends Component
{
    public $start_balance, $allTerminals, $id_terminal, $prueba, $t_select;
    public function mount()
    {
        $this->t_select = true;
        $this->start_balance = 0;
        $this->allTerminals = Terminal::where('id_company', Auth::user()->currentTeam->id)->get();
        $this->id_terminal = $this->allTerminals[0]->id;
        $tu = TeamUser::where('team_id', Auth::user()->currentTeam->id)
            ->where('user_id', Auth::user()->id)->first();
        if ($tu && $tu->terminal) {
            $this->id_terminal = $tu->terminal;
            $this->t_select = false;
        }
    }
    public function render()
    {
        return view('livewire.cash-register.open-cash-register-component');
    }

    public function store()
    {
        $this->validate([
            'start_balance' => 'required',
        ]);
        if (CashRegister::where('id_terminal', $this->id_terminal)->where('state', '1')->first() === null) {
            DB::beginTransaction();
            try {
                $result = CashRegister::create([
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_user' => Auth::user()->id,
                    'id_terminal' => $this->id_terminal,
                    'start_balance' => $this->start_balance
                ]);
                $this->resetInputFields();
                $this->dispatchBrowserEvent('ocr_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Caja abierta y lista para trabajar', 'refresh' => 0]);
            } catch (\Illuminate\Database\QueryException $e) {
                // back to form with errors
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
                DB::rollback();
            } catch (\Exception $e) {
                DB::rollback();
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
            }
            DB::commit();
            $this->emit('refreshCashRegisterTable');
        } else {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Ya existe una caja abierta para esta terminal, si deseas comenzar un nuevo registro favor cerrar la caja anterior.']);
        }
    }
    public function resetInputFields()
    {
        $this->start_balance = 0;
    }
}
