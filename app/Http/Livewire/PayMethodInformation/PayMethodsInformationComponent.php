<?php

namespace App\Http\Livewire\PayMethodInformation;

use Livewire\Component;
use App\Models\PayMethodInformation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PayMethodsInformationComponent extends Component
{

    public $allPlans, $payMethodInformation_id, $count_number, $count_owner, $email_notification, $use_by_default = true, $id_team, $active = true, $updateMode = false;
    protected $listeners = ['editPayMethodInformation' => 'edit', 'deletePayMethodInformation' => 'delete'];

    public function render()
    {
        return view('livewire.pay-method-information.pay-methods-information-component');
    }

    private function resetInputFields()
    {
        $this->id_team = Auth::user()->currentTeam->id;
        $this->count_number = '';
        $this->count_owner = '';
        $this->email_notification = '';
        $this->use_by_default = true;
        $this->id_team = 0;
        $this->active = true;
    }

    public function newReg()
    {
        $this->resetInputFields();
        $this->dispatchBrowserEvent('payMethodInformation_modal_show', []);
    }

    public function store()
    {
        $this->validate([
            'count_number' => 'required',
            'count_owner' => 'required',
            'email_notification' => 'required',
            'use_by_default' => 'required'
        ]);
        DB::beginTransaction();
        try {

            //Actualizo todas las cuentas de la compañía en false si este que actualizo es el metodo por defecto
            if ($this->use_by_default == true) {
                PayMethodInformation::where('id_team', '=', $this->id_team)->update(['use_by_default' => false]);
            } else {
                //Valido que si es el primer registro hacer true el atributo 'use_by_default'
                $resultCount = PayMethodInformation::where('id_team', '=', $this->id_team)->count();
                if ($resultCount == 0) {
                    $this->use_by_default = true;
                }
            }

            PayMethodInformation::create([
                'id_team' => $this->id_team,
                'count_number' => $this->count_number,
                'count_owner' => $this->count_owner,
                'email_notification' => $this->email_notification,
                'use_by_default' => $this->use_by_default,
                'active' => true
            ]);

            $this->resetInputFields();
            $this->dispatchBrowserEvent('payMethodInformation_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Método de pago creado con exito', 'refresh' => 1]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshPayMethodInformationTable');
    }

    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $result = PayMethodInformation::where('id', $id)->first();
            $this->payMethodInformation_id = $id;
            $this->count_number = $result->count_number;
            $this->count_owner = $result->count_owner;
            $this->email_notification = $result->email_notification;
            $this->use_by_default = $result->use_by_default;
            $this->active = $result->active;
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback and then redirect
            // back to form with errors
            $this->resetInputFields();
            $this->dispatchBrowserEvent('payMethodInformationU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('payMethodInformationU_modal_hide', []);
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
            'id_team' => 'required',
            'count_number' => 'required',
            'count_owner' => 'required',
            'email_notification' => 'required',
            'use_by_default' => 'required',
            'active' => 'required',
        ]);
        DB::beginTransaction();
        try {
            if ($this->payMethodInformation_id) {

                //Actualizo todas las cuentas de la compañía en false si este que actualizo es el metodo por defecto
                if ($this->use_by_default == true) {
                    PayMethodInformation::where('id_team', '=', $this->id_team)->update(['use_by_default' => false]);
                }

                //Aplico los cambios a la cuenta
                $result = PayMethodInformation::find($this->payMethodInformation_id)->update([
                    'count_number' => $this->count_number,
                    'count_owner' => $this->count_owner,
                    'email_notification' => $this->email_notification,
                    'use_by_default' => $this->use_by_default,
                    'active' => $this->active,
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('payMethodInformationU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Método de pago actualizado con exito', 'refresh' => 1]);
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
        $this->emit('refreshPayMethodInformationTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                $result = PayMethodInformation::find($id);

                if ($result->use_by_default == true) {
                    $this->dispatchBrowserEvent('errorData', ['errorData' => 'No se puede desactivar el método de pago por defecto.']);
                }else{
                    $result ->update([
                        'active' => false,
                    ]);
                    $this->dispatchBrowserEvent('messageData', ['messageData' => 'Método de pago eliminado con exito.', 'refresh' => 1]);
                    $this->emit('refreshPayMethodInformationTable');
                }
                $this->updateMode = false;
                $this->resetInputFields();
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
