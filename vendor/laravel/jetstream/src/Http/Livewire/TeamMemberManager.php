<?php

namespace Laravel\Jetstream\Http\Livewire;

use App\Models\BranchOffice;
use App\Models\Terminal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Actions\UpdateTeamMemberRole;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Laravel\Jetstream\Contracts\InvitesTeamMembers;
use Laravel\Jetstream\Contracts\RemovesTeamMembers;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Role;
use Livewire\Component;

class TeamMemberManager extends Component
{
    /**
     * The team instance.
     *
     * @var mixed
     */
    public $team;

    /**
     * Indicates if a user's role is currently being managed.
     *
     * @var bool
     */
    public $currentlyManagingRole = false;

    /**
     * The user that is having their role managed.
     *
     * @var mixed
     */
    public $managingRoleFor;

    /**
     * The current role for the user that is having their role managed.
     *
     * @var string
     */
    public $currentRole;

    /**
     * The current role for the user that is having their role managed.
     *
     * @var string
     */
    public $currentBo;

    /**
     * The current role for the user that is having their role managed.
     *
     * @var string
     */
    public $currentTerminal;

    /**
     * Indicates if the application is confirming if a user wishes to leave the current team.
     *
     * @var bool
     */
    public $confirmingLeavingTeam = false;

    /**
     * Indicates if the application is confirming if a team member should be removed.
     *
     * @var bool
     */
    public $confirmingTeamMemberRemoval = false;

    /**
     * The ID of the team member being removed.
     *
     * @var int|null
     */
    public $teamMemberIdBeingRemoved = null;

    /**
     * The ID of the team member being removed.
     *
     * @var int|null
     */
    public $allBranchOffices = null;

    /**
     * The ID of the team member being removed.
     *
     * @var int|null
     */
    public $allTerminals = null;

    /**
     * The ID of the team member being removed.
     *
     * @var int|null
     */
    public $allCurrentTerminals = null;


    /**
     * The "add team member" form state.
     *
     * @var array
     */
    public $addTeamMemberForm = [
        'email' => '',
        'role' => null,
        'bo' => null,
        'terminal' => null
    ];

    /**
     * Mount the component.
     *
     * @param  mixed  $team
     * @return void
     */
    public function mount($team)
    {
        $this->team = $team;
        $this->allCurrentTerminals = [];
        $this->allBranchOffices = BranchOffice::where("id_company", Auth::user()->currentTeam->id)->get();
    }

    /**
     * Add a new team member to a team.
     *
     * @return void
     */
    public function addTeamMember()
    {
        $this->resetErrorBag();

        if (Features::sendsTeamInvitations()) {
            app(InvitesTeamMembers::class)->invite(
                $this->user,
                $this->team,
                $this->addTeamMemberForm['email'],
                $this->addTeamMemberForm['role'],
                $this->addTeamMemberForm['bo'],
                $this->addTeamMemberForm['terminal']
            );
        } else {
            app(AddsTeamMembers::class)->add(
                $this->user,
                $this->team,
                $this->addTeamMemberForm['email'],
                $this->addTeamMemberForm['role'],
                $this->addTeamMemberForm['bo'],
                $this->addTeamMemberForm['terminal']
            );
        }

        $this->addTeamMemberForm = [
            'email' => '',
            'role' => null,
            'bo' => null,
            'terminal' => null,
        ];

        $this->team = $this->team->fresh();

        $this->emit('saved');
    }

    /**
     * Cancel a pending team member invitation.
     *
     * @param  int  $invitationId
     * @return void
     */
    public function cancelTeamInvitation($invitationId)
    {
        if (!empty($invitationId)) {
            $model = Jetstream::teamInvitationModel();

            $model::whereKey($invitationId)->delete();
        }

        $this->team = $this->team->fresh();
    }

    /**
     * Allow the given user's role to be managed.
     *
     * @param  int  $userId
     * @return void
     */
    public function manageRole($userId)
    {
        $team_user = DB::table("team_user")->where('team_id', $this->team->id)->where('user_id', $userId)->first();
        $this->currentlyManagingRole = true;
        $this->managingRoleFor = Jetstream::findUserByIdOrFail($userId);
        $this->currentRole = $this->managingRoleFor->teamRole($this->team)->key;
        $this->currentBo = $team_user->bo;
        $this->allCurrentTerminals = Terminal::where("id_branch_office", $this->currentBo)->get();
        $this->currentTerminal =  $team_user->terminal;
    }

    /**
     * Save the role for the user being managed.
     *
     * @param  \Laravel\Jetstream\Actions\UpdateTeamMemberRole  $updater
     * @return void
     */
    public function updateRole(UpdateTeamMemberRole $updater)
    {
        $updater->update(
            $this->user,
            $this->team,
            $this->managingRoleFor->id,
            $this->currentRole,
            $this->currentBo,
            $this->currentTerminal
        );

        $this->team = $this->team->fresh();

        $this->stopManagingRole();
    }

    /**
     * Stop managing the role of a given user.
     *
     * @return void
     */
    public function stopManagingRole()
    {
        $this->currentlyManagingRole = false;
    }

    /**
     * Remove the currently authenticated user from the team.
     *
     * @param  \Laravel\Jetstream\Contracts\RemovesTeamMembers  $remover
     * @return void
     */
    public function leaveTeam(RemovesTeamMembers $remover)
    {
        $remover->remove(
            $this->user,
            $this->team,
            $this->user
        );

        $this->confirmingLeavingTeam = false;

        $this->team = $this->team->fresh();

        return redirect(config('fortify.home'));
    }

    /**
     * Confirm that the given team member should be removed.
     *
     * @param  int  $userId
     * @return void
     */
    public function confirmTeamMemberRemoval($userId)
    {
        $this->confirmingTeamMemberRemoval = true;

        $this->teamMemberIdBeingRemoved = $userId;
    }

    /**
     * Remove a team member from the team.
     *
     * @param  \Laravel\Jetstream\Contracts\RemovesTeamMembers  $remover
     * @return void
     */
    public function removeTeamMember(RemovesTeamMembers $remover)
    {
        $remover->remove(
            $this->user,
            $this->team,
            $user = Jetstream::findUserByIdOrFail($this->teamMemberIdBeingRemoved)
        );

        $this->confirmingTeamMemberRemoval = false;

        $this->teamMemberIdBeingRemoved = null;

        $this->team = $this->team->fresh();
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
     * Get the available team member roles.
     *
     * @return array
     */
    public function getRolesProperty()
    {
        return collect(Jetstream::$roles)->transform(function ($role) {
            return with($role->jsonSerialize(), function ($data) {
                return (new Role(
                    $data['key'],
                    $data['name'],
                    $data['permissions']
                ))->description($data['description']);
            });
        })->values()->all();
    }
    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function changeCurrentBo()
    {
        $currentTerminals = Terminal::where("id_branch_office", $this->currentBo)->get();
        if ($currentTerminals) {
            $this->allCurrentTerminals = $currentTerminals;
            $this->currentTerminal = '';
        } else {
            $this->allCurrentTerminals = [];
            $this->currentTerminal = '';
        }
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $terminals = Terminal::where("id_branch_office", $this->addTeamMemberForm["bo"])->get();
        if ($terminals) {
            $this->allTerminals = $terminals;
            $this->addTeamMemberForm["terminal"] = null;
        } else {
            $this->allTerminals = [];
            $this->addTeamMemberForm["terminal"] = null;
        }
        if ($this->currentRole == 'admin') {
            $this->currentBo = null;
            $this->currentTerminal = null;
        }
        return view('teams.team-member-manager');
    }
}
