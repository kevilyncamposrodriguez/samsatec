<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TeamUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'team_id',
        'role',
        'bo',
        'terminal'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'team_user';

    public static function getUserTeam()
    {
        return TeamUser::where('user_id', Auth::user()->id)->where('team_id', Auth::user()->currentTeam->id)->first();
    }
    public static function getUserTeamTerminal($user, $team)
    {
        $terminal = 'Terminal no asignada';
        if ($user && $team) {
            $tu = TeamUser::where('user_id', $user)->where('team_id', $team)->first();
            $terminal = ($tu != null) ? Terminal::find($tu->terminal) : 'Terminal no asignada';
            $terminal = ($terminal) ? $terminal->number : 'Terminal no asignada';
        }
        return $terminal;
    }
}
