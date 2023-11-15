<?php

namespace App\Http\Livewire\Family;

use Livewire\Component;
use App\Models\Family;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class FamiliesComponent extends Component
{

    public $allFamilies, $family_id, $name, $updateMode = false;
    protected $listeners = ['editFamily' => 'edit', 'deleteFamily' => 'delete'];
    public function render()
    {
        return view('livewire.family.families-component');
    }

    private function resetInputFields()
    {
        $this->name = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required'
        ]);
        DB::beginTransaction();
        try {
            Family::create([
                'id_company' => Auth::user()->currentTeam->id,
                'name' => $this->name
            ]);

            $this->resetInputFields();
            $this->dispatchBrowserEvent('family_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Familia creada con exito', 'refresh' => 1]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshFamilyTable');
    }

    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $result = Family::where('id', $id)->first();
            $this->family_id = $id;
            $this->name = $result->name;
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
            'name' => 'required'
        ]);
        DB::beginTransaction();
        try {
            if ($this->family_id) {
                $result = Family::find($this->family_id);
                $result->update([
                    'id_company' => Auth::user()->currentTeam->id,
                    'name' => $this->name
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('familyU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Familia actualizada con exito', 'refresh' => 1]);
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
        $this->emit('refreshFamilyTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                Family::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Familia eliminada con exito', 'refresh' => 1]);
                $this->emit('refreshFamilyTable');
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
