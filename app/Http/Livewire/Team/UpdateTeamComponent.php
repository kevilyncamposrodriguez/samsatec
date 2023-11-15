<?php

namespace App\Http\Livewire\Team;

use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Ramsey\Uuid\Type\Integer;

class UpdateTeamComponent extends Component
{

    use WithFileUploads;
    /**
     * The team instance.
     *
     * @var mixed
     */
    public $fe, $team, $name, $id_card, $type_id_card, $email_company, $phone_company, $accounts, $logo, $logo_url;

    public function render()
    {
        $this->logo_url = ($this->team->logo_url) ? asset('Logos/' . $this->team->id_card . '.png') : '/assets/img/no logo.png';
        return view('livewire.team.update-team-component');
    }
    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
    }
    /**
     * Mount the component.
     *
     * @param  mixed  $team
     * @return void
     */
    public function mount($team)
    {
        $this->team = $team;
        $this->name = $team->name;
        $this->id_card = $team->id_card;
        $this->type_id_card = $team->type_id_card;        
        $this->email_company = $team->email_company;
        $this->phone_company = $team->phone_company;
        $this->accounts = $team->accounts;
        $this->logo_url = ($team->logo_url) ? asset('Logos/' . $team->id_card . '.png') : '/assets/img/no logo.png';
    }
    
    public function chargeLogo()
    {
        $this->validate([
            'logo' => 'image', // 1MB Max
        ]);
        DB::beginTransaction();
        try {
            $result = Team::find($this->team->id);
            if ($result->logo_url != '') {
                Storage::disk("public_files")->delete($result->logo_url);
            }
            $this->logo->storeAs('Logos/', $this->id_card . '.png', ["disk" => "public_files"]);
            $result->update(
                [
                    'logo_url' => 'Logos/' . $this->id_card . '.png'
                ]
            );
            $this->logo_url = asset('Logos/' . $this->id_card . '.png');
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
    }

    /**
     * Update the team's name.
     *
     * @param  
     * @return void
     */
    public function update()
    {
        $this->resetErrorBag();
        $this->validate([
            'name' => 'required|min:4',
            'id_card' => 'required|min:9|max:12',
            'type_id_card' => 'required|exists:type_id_cards,id',
            'email_company' => 'required',
            'phone_company' => 'required',
            'accounts' => 'min:0|max:300',
        ]);
        DB::beginTransaction();
        try {
            if ($this->team->id) {
                $this->team->update([
                    'name' => $this->name,
                    'id_card' => $this->id_card,
                    'type_id_card' => $this->type_id_card,                    
                    'email_company' => $this->email_company,
                    'phone_company' =>  $this->phone_company,
                    'accounts' => $this->accounts
                ]);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Compañia actualizada con exito', 'r' => 0]);
                $this->emit('refresh-navigation-dropdown');
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
    }
}
