<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Contracts\InvitesTeamMembers;
use Laravel\Jetstream\Events\InvitingTeamMember;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Mail\TeamInvitation;
use Laravel\Jetstream\Rules\Role;

class InviteTeamMember implements InvitesTeamMembers
{
    /**
     * Invite a new team member to the given team.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  string  $email
     * @param  string|null  $role
     * @return void
     */
    public function invite($user, $team, string $email, string $role = null, int $bo = null, int $terminal = null)
    {
        Gate::forUser($user)->authorize('addTeamMember', $team);

        $this->validate($team, $email, $role, $bo, $terminal);

        InvitingTeamMember::dispatch($team, $email, $role, $bo, $terminal);

        $invitation = $team->teamInvitations()->create([
            'email' => $email,
            'role' => $role,
            'bo' => $bo,
            'terminal' => $terminal,
        ]);
        Mail::to($email)->send(new TeamInvitation($invitation));
    }

    /**
     * Validate the invite member operation.
     *
     * @param  mixed  $team
     * @param  string  $email
     * @param  string|null  $role
     * @return void
     */
    protected function validate($team, string $email, ?string $role, int $bo = null, int $terminal = null)
    {
        Validator::make([
            'email' => $email,
            'role' => $role,
            'bo' => (Auth::user()->currentTeam->id_plan != 1) ? $bo : '',
            'terminal' => (Auth::user()->currentTeam->id_plan != 1) ? $terminal : '',
        ], $this->rules($team, $role), [
            'email.unique' => __('This user has already been invited to the team.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($team, $email)
        )->validateWithBag('addTeamMember');
    }

    /**
     * Get the validation rules for inviting a team member.
     *
     * @param  mixed  $team
     * @return array
     */
    protected function rules($team, $role)
    {
        return array_filter([
            'email' => ['required', 'email', Rule::unique('team_invitations')->where(function ($query) use ($team, $role) {
                $query->where('team_id', $team->id);
            })],
            'role' => Jetstream::hasRoles()
                ? ['required', 'string', new Role]
                : null,
            'bo' => ((Auth::user()->currentTeam->plan_id != 1 && Auth::user()->currentTeam->plan_id != 2) && $role != 'admin') ? 'required' : null,
            'terminal' => ((Auth::user()->currentTeam->plan_id != 1 && Auth::user()->currentTeam->plan_id != 2) && $role != 'admin') ? 'required' : null
        ]);
    }

    /**
     * Ensure that the user is not already on the team.
     *
     * @param  mixed  $team
     * @param  string  $email
     * @return \Closure
     */
    protected function ensureUserIsNotAlreadyOnTeam($team, string $email)
    {
        return function ($validator) use ($team, $email) {
            $validator->errors()->addIf(
                $team->hasUserWithEmail($email),
                'email',
                __('This user already belongs to the team.')
            );
        };
    }
}
