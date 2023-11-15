<?php

namespace App\Http\Livewire\Discount;

use Livewire\Component;
use App\Models\Discount;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class DiscountComponent extends Component
{
    use WithPagination;
    public $allDiscounts = [], $discount_id, $nature, $amount, $updateMode = false, $allBO;
    protected $listeners = ['editDiscount' => 'edit', 'deleteDiscount' => 'delete'];
    public function render()
    {
        $this->allDiscounts = Discount::where("discounts.id_company", "=", Auth::user()->currentTeam->id)->get();
        return view('livewire.discount.discount-component');
    }
    private function resetInputFields()
    {
        $this->nature = '';
        $this->amount = '';
    }

    public function store()
    {
        $this->validate([
            'nature' => 'required',
            'amount' => 'required'
        ]);
        DB::beginTransaction();
        try {
            Discount::create([
                'nature' => $this->nature,
                'id_company' => Auth::user()->currentTeam->id,
                'amount' => $this->amount
            ]);

            $this->resetInputFields();
            $this->dispatchBrowserEvent('discount_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Descuento creado con exito']);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshDiscountTable');
    }

    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $result = \App\Models\Discount::where('id', $id)->first();
            $this->discount_id = $id;
            $this->nature = $result->nature;
            $this->amount = str_replace(" ", "T", $result->amount);
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
            'nature' => 'required',
            'amount' => 'required'
        ]);
        DB::beginTransaction();
        try {
            if ($this->discount_id) {
                $result = Discount::find($this->discount_id);
                $result->update([
                    'nature' => $this->nature,
                    'id_company' => Auth::user()->currentTeam->id,
                    'amount' => $this->amount
                ]);
                $this->updateMode = false;
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Descuento actualizado con exito']);
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
        $this->dispatchBrowserEvent('discountU_modal_hide', []);
        $this->emit('refreshDiscountTable');
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            if ($id) {
                Discount::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Descuento eliminado con exito']);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            //return Redirect::to('discounts');
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
            DB::rollback();
        }
        DB::commit();
    }
}
