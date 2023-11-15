<?php

namespace App\Http\Livewire\Zone;

use Livewire\Component;
use App\Models\Zone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ZonesComponent extends Component
{

    public $allZones, $zone_id, $code, $name, $updateMode = false, $allBO;
    protected $listeners = ['editZone' => 'edit', 'deleteZone' => 'delete'];
    public function render()
    {
        $this->allZones = Zone::where("zones.id_company", "=", Auth::user()->currentTeam->id)->get();
        return view('livewire.zone.zones-component');
    }

    private function resetInputFields()
    {
        $this->code = '';
        $this->name = '';
    }

    public function store()
    {
        $this->validate([
            'code' => 'required',
            'name' => 'required'
        ]);
        DB::beginTransaction();
        try {
            Zone::create([
                'code' => $this->code,
                'id_company' => Auth::user()->currentTeam->id,
                'name' => $this->name
            ]);

            $this->resetInputFields();
            $this->dispatchBrowserEvent('zone_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Zona creada con exito', 'refresh' => 1]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshZoneTable');
    }

    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $result = \App\Models\Zone::where('id', $id)->first();
            $this->zone_id = $id;
            $this->code = str_pad($result->code, 3, "0", STR_PAD_LEFT);
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
            'code' => 'required',
            'name' => 'required'
        ]);
        DB::beginTransaction();
        try {
            if ($this->zone_id) {
                $result = Zone::find($this->zone_id);
                $result->update([
                    'code' => $this->code,
                    'id_company' => Auth::user()->currentTeam->id,
                    'name' => $this->name
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('zoneU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Zona actualizada con exito', 'refresh' => 1]);
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
        $this->emit('refreshZoneTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                Zone::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Zona eliminada con exito', 'refresh' => 1]);
                $this->emit('refreshZoneTable');
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
