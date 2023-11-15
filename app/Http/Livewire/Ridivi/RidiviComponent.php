<?php

namespace App\Http\Livewire\Ridivi;

use App\Models\Ridivi;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RidiviComponent extends Component
{
    public $ridivi_username = '', $ridivi_pass = '', $iban = '', $key, $ridivi_user, $conection = false, $allAccounts = [];
    public function mount()
    {
        $this->conection = false;
        $this->ridivi_username = Auth::user()->currentTeam->ridivi_username;
        $this->ridivi_pass = Auth::user()->currentTeam->ridivi_pass;
        $this->iban = '';
        if ($this->ridivi_username != '' && $this->ridivi_pass != '') {
            $result = Ridivi::getRidiviKey($this->ridivi_username, $this->ridivi_pass);
            if ($result['error']) {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Problemas con la conexion a Ridivi' . $result['message']]);
            } else {
                $result = Ridivi::getRidiviUser($result["key"]);
                if (!$result['error']) {
                    $this->allAccounts = isset($result['user']['accounts']) ? $result['user']['accounts'] : [];
                    $this->conection = true;
                }
            }
        }
    }
    public function render()
    {

        return view('livewire.ridivi.ridivi-component');
    }
    public function save()
    {
        $this->validate([
            'ridivi_username' => 'required',
            'ridivi_pass' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $result = Team::find(Auth::user()->currentTeam->id);
            $result->update([
                'ridivi_username' => $this->ridivi_username,
                'ridivi_pass' => $this->ridivi_pass
            ]);
            $result = Ridivi::getRidiviKey($this->ridivi_username, $this->ridivi_pass);
            if ($result['error']) {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Problemas con la conexion a Ridivi' . $result['message']]);
            } else {
                $result = Ridivi::getRidiviUser( $result["key"]);
                if (!$result['error']) {
                    $this->allAccounts = isset($result['user']['accounts']) ? $result['user']['accounts'] : [];                    
                    $this->conection = true;
                }
            }
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Datos Agregados con exito', 'refresh' => 0]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
    }
    public function conection()
    {
        $this->validate([
            'ridivi_username' => 'required',
            'ridivi_pass' => 'required'
        ]);
        $result = Ridivi::getRidiviKey($this->ridivi_username, $this->ridivi_pass);
        if ($result['error']) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => $result['message']]);
        } else {
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Conexión realizda con éxito', 'refresh' => 0]);
        }
    }
}
