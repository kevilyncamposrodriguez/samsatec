<?php

namespace App\Http\Livewire\Team;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateTeam extends Component
{
    /**
     * The team instance.
     *
     * @var mixed
     */
    public $team;

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
     public function mount($team) {
        $this->team = $team;

        $this->state = [
            'name' => $team->name,
            'id_card' => $team->id_card,
            'type_id_card' => $team->type_id_card,
            'user_mh' => $team->user_mh,
            'pass_mh' => $team->pass_mh,
            'cryptographic_key' => $team->cryptographic_key,
            'pin' => $team->pin
        ];
    }
    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty() {
        return Auth::user();
    }
    public function render()
    {
        return view('livewire.team.showConfiguration');
    }
}
