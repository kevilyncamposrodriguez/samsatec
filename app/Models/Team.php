<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;
use Illuminate\Support\Str;

class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'personal_team',
        'id_card',
        'type_id_card',
        'user_mh',
        'pass_mh',
        'cryptographic_key',
        'pin',
        'ridivi_username',
        'ridivi_pass',
        'fe',
        'phone_company',
        'email_company',
        'accounts',
        'logo_url',
        'bo_inventory',
        'plan_id',
        'referred_by',
        'referral_code',
        'cash_register'
    ];
  
    public static function getUniqueReferralCode()
    {
        do {
            $code = Str::random(10);
        } while (Team::where('referral_code', $code)->exists());

        return $code;
    }
    public static function getUserTeam()
    {
        return TeamUser::where('user_id', Auth::user()->id)->where('team_id', Auth::user()->currentTeam->id)->first();
    }
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];
}
