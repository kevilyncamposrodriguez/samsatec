<?php

namespace App\Http\Livewire\Team;

use App\Models\Discount;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateTeamKeyComponent extends Component
{
    use WithFileUploads;
    /**
     * The team instance.
     *
     * @var mixed
     */
    public $team;
    public $cryptographic_key;
    public $pin, $fe, $user_mh, $pass_mh;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    /**
     * Mount the component.
     *
     * @param  mixed  $team
     * @return void
     */
    public function mount($team)
    {
        $this->team = $team;
        $this->fe = (int)$team->fe;
        $this->cryptographic_key = $team->cryptographic_key;
        $this->pin = $team->pin;
        $this->user_mh = $team->user_mh;
        $this->pass_mh = $team->pass_mh;
    }
    public function is_fe()
    {
        $this->team->update([
            'fe' => ($this->fe) ? '1' : '0'
        ]);
    }
    /**
     * Update the team's name.
     *
     * @param  \Laravel\Jetstream\Contracts\UpdatesTeamNames  $updater
     * @return void
     */
    public function update()
    {
       // $this->resetErrorBag();
        $this->validate([
            'cryptographic_key' => 'file',
            'pin' => 'required|min:4|max:4',
            'user_mh' => 'required',
            'pass_mh' => 'required',

        ]);
        DB::beginTransaction();
        try {

            if (Auth::user()->currentTeam->id) {
                $result = Team::find(Auth::user()->currentTeam->id);
                $result->update([
                    'cryptographic_key' => 'public/P12s/' . $this->cryptographic_key->getClientOriginalName(),
                    'pin' => $this->pin,
                    'user_mh' => $this->user_mh,
                    'pass_mh' => $this->pass_mh,
                ]);
                $this->cryptographic_key->storeAs('public/P12s', $this->cryptographic_key->getClientOriginalName());
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Datos de llave criptografica actualizados con exito']);
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

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
    }
    public function render()
    {
        return view('livewire.team.update-team-key-component');
    }
}
