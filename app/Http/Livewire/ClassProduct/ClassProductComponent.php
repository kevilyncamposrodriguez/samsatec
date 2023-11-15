<?php

namespace App\Http\Livewire\ClassProduct;

use App\Models\ClassProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ClassProductComponent extends Component
{
    public $allClases, $class_id, $symbol, $name, $updateMode = false;
    protected $listeners = ['editClass' => 'edit', 'deleteClass' => 'delete'];
    public function render()
    {
        return view('livewire.class-product.class-product-component');
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->symbol = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'symbol' => 'required|min:4|max:4'
        ]);
        DB::beginTransaction();
        try {
            ClassProduct::create([
                'id_company' => Auth::user()->currentTeam->id,
                'name' => $this->name,
                'symbol' => $this->symbol
            ]);

            $this->resetInputFields();
            $this->dispatchBrowserEvent('class_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Clase de producto creada con exito', 'refresh' => 1]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
        $this->emit('refreshClassTable');
    }

    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $result = ClassProduct::where('id', $id)->first();
            $this->class_id = $id;
            $this->name = $result->name;
            $this->symbol = $result->symbol;
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback and then redirect
            // back to form with errors
            $this->resetInputFields();
            $this->dispatchBrowserEvent('classU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('classU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados. ' . $e->getMessage()]);
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
            'name' => 'required',
            'symbol' => 'required|min:4|max:4'
        ]);
        DB::beginTransaction();
        try {
            if ($this->class_id) {
                $result = ClassProduct::find($this->class_id);
                $result->update([
                    'id_company' => Auth::user()->currentTeam->id,
                    'name' => $this->name,
                    'symbol' => $this->symbol
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('classU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Clase de producto actualizada con exito', 'refresh' => 1]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
        }
        DB::commit();
        $this->emit('refreshClassTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                ClassProduct::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Clase de producto eliminada con exito', 'refresh' => 1]);
                $this->emit('refreshClassTable');
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
