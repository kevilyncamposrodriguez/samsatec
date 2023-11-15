<?php

namespace App\Http\Livewire\Lot;

use Livewire\Component;
use App\Models\Lot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LotsComponent extends Component
{

    public $allLots, $lot_id, $code, $date_purchase, $updateMode = false, $allBO;
    protected $listeners = ['editLot' => 'edit', 'deleteLot' => 'delete'];
    public function render()
    {
        return view('livewire.lot.lots-component');
    }

    private function resetInputFields()
    {
        $this->code = '';
        $this->date_purchase = '';
    }

    public function store()
    {
        $this->validate([
            'code' => 'required',
            'date_purchase' => 'required'
        ]);
        DB::beginTransaction();
        try {
            Lot::create([
                'code' => $this->code,
                'id_company' => Auth::user()->currentTeam->id,
                'date_purchase' => $this->date_purchase
            ]);

            $this->resetInputFields();
            $this->dispatchBrowserEvent('lot_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Lote creado con exito']);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshLotTable');
    }

    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $result = \App\Models\Lot::where('id', $id)->first();
            $this->lot_id = $id;
            $this->code = $result->code;
            $this->date_purchase = str_replace(" ", "T", $result->date_purchase);
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback and then redirect
            // back to form with errors
            $this->resetInputFields();
            $this->dispatchBrowserEvent('discountU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('discountU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados']);
        }
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function update()
    {
        $this->validate([
            'code' => 'required',
            'date_purchase' => 'required'
        ]);
        DB::beginTransaction();
        try {
            if ($this->lot_id) {
                $result = Lot::find($this->lot_id);
                $result->update([
                    'code' => $this->code,
                    'id_company' => Auth::user()->currentTeam->id,
                    'date_purchase' => $this->date_purchase
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('lotU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Lote actualizado con exito']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshLotTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                Lot::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Lote eliminado con exito']);
                $this->emit('refreshLotTable');
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
