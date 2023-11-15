<?php

namespace App\Http\Livewire\Plan;

use Livewire\Component;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PlansComponent extends Component
{

    public $allPlans, $plan_id, $name, $description, $descriptionJSON, $active = true, $updateMode = false;
    protected $listeners = ['editPlan' => 'edit', 'deletePlan' => 'delete'];
    public function render()
    {
        return view('livewire.plan.plans-component');
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->descriptionJSON = '';
        $this->active = true;
    }

    public function newReg() {
        $this->resetInputFields();
        $this->dispatchBrowserEvent('plan_modal_show', []);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'description' => 'required',
            'descriptionJSON' => 'required',
        ]);
        DB::beginTransaction();
        try {
            Plan::create([
                'name' => $this->name,
                'description' => $this->description,
                'descriptionJSON' => $this->descriptionJSON,
                'active' => $this->active,
            ]);

            $this->resetInputFields();
            $this->dispatchBrowserEvent('plan_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Plan creado con exito', 'refresh' => 1]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshPlanTable');
    }

    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $result = Plan::where('id', $id)->first();
            $this->plan_id = $id;
            $this->name = $result->name;
            $this->description = $result->description;
            $this->descriptionJSON = $result->descriptionJSON;
            $this->active = $result->active;
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback and then redirect
            // back to form with errors
            $this->resetInputFields();
            $this->dispatchBrowserEvent('planU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('planU_modal_hide', []);
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
            'name' => 'required',
            'description' => 'required',
            'descriptionJSON' => 'required',
            'active' => 'required',
        ]);
        DB::beginTransaction();
        try {
            if ($this->plan_id) {
                $result = Plan::find($this->plan_id);
                $result->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'descriptionJSON' => $this->descriptionJSON,
                    'active' => $this->active,
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('planU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Plan actualizado con exito', 'refresh' => 1]);
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
        $this->emit('refreshPlanTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                $result = Plan::find($this->plan_id);
                $result->update([
                    'active' => false,
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Plan eliminado con exito', 'refresh' => 1]);
                $this->emit('refreshPlanTable');
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
