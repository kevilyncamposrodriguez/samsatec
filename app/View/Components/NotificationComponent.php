<?php

namespace App\View\Components;

use App\Http\Livewire\SystemPay\SystemPayComponent;
use App\Models\PaymentSystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class NotificationComponent extends Component
{
    public $qty, $allNotifications = [];
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->qty = 0;
        $this->allNotifications = [];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */

    public function render()
    {
        $this->getNotifications();
        return view('components.notifications-component');
    }
    public function getNotifications()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            $pays = PaymentSystem::where('id_company', Auth::user()->currentTeam->id)
                ->where('id_pay', null)->get();
            foreach ($pays as  $pay) {
                array_push(
                    $this->allNotifications,
                    array(
                        'msg' => 'Cobro del sistema',
                        'sub' => 'FS-' . str_pad($pay->id, 10, "0", STR_PAD_LEFT),
                        'href' => "listPays"
                    )
                );
            }
            $this->qty += count($pays);
        }
    }
}
