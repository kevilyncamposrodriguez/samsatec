<?php

namespace App\Http\Middleware;

use App\Models\BranchOffice;
use App\Models\CompaniesEconomicActivities;
use App\Models\PaymentSystem;
use App\Models\Team;
use App\Models\Terminal;
use Illuminate\Support\Facades\Session;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPay
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
        if ($pay = PaymentSystem::where("id_company", Auth::user()->currentTeam->id)->whereNull("id_pay")
            ->orderBy('created_at', 'asc')->first()
        ) {
            if ($pay->payment_due < now() ) {
                $message = "El pago de su factura por nuestro servicio se encuentra atraso, favor realizar el proceso de pago para poder continuar usando nuestro sistema. ¡Gracias!";
                session(['mensaje' => $message, 'npay' => 1]);
                return $next($request);
            } else {
                Session::forget('mensaje');
                Session::forget('npay');
                return $next($request);
            }
        } else {
            Session::forget('mensaje');
            Session::forget('npay');
            return $next($request);
        }
    }
}
