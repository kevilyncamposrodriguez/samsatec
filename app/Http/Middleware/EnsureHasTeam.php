<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureHasTeam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::user()->isMemberOfATeam()) {
            return redirect()->route('teams.create');
        }
        $this->ensureUserHasCurrentTeamSet();
        return $next($request);
    }
    protected function ensureUserHasCurrentTeamSet(): void
    {
        if (is_null(auth()->user()->current_team_id)) {
            $user = Auth::user();
            $user->current_team_id = $user->allTeams()->first()->id;
            $user->save();
        }
    }
}
