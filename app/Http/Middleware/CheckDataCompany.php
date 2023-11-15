<?php

namespace App\Http\Middleware;

use App\Models\BranchOffice;
use App\Models\CompaniesEconomicActivities;
use App\Models\EconomicActivity;
use App\Models\Team;
use App\Models\Terminal;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckDataCompany
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
        if (isset(Auth::user()->currentTeam->id)) {
            $company = Team::where("teams.id", "=", Auth::user()->currentTeam->id)->first();
            $bo = BranchOffice::where("branch_offices.id_company", "=", Auth::user()->currentTeam->id)->get();
            $terminal = Terminal::where("terminals.id_company", "=", Auth::user()->currentTeam->id)->get();
            $ea = CompaniesEconomicActivities::where("companies_economic_activities.id_company", "=", Auth::user()->currentTeam->id)->get();
            $message = "Antes de utilizar el sistema, debe de completar los siguientes datos de la configuración: <br>";
            if (
                $company->id_card == null || $company->type_id_card == null || count($bo) < 1 || count($terminal) < 1 || count($ea) < 1
            ) {
                $message .= ($company->id_card == null || $company->type_id_card == null) ? '<br> -Numero y tipo de cédula de la compañia' : '';
                $message .= (count($bo) < 1) ? '<br> -Debe de tener almenos una sucursal creada' : '';
                $message .= (count($terminal) < 1) ? '<br> -Debe de tener almenos una terminal creada' : '';
                $message .= (count($ea) < 1) ? '<br> -Debe de tener almenos una Actividad Economica creada' : '';

                if ($company->fe == '1') {
                    $message .= ($company->user_mh == null  || $company->pass_mh == null) ? '<br> -Usuario y contraseña de acceso al ministerio de hacienda ' : '';
                    $message .= ($company->cryptographic_key == null || $company->pin == null) ? '<br> -Llave criptografica y pin para el proceso de comprobantes' : '';
                }
                return redirect('/teams/' . Auth::user()->currentTeam->id)->with('message', $message);
            } else {
                if (($company->fe == '1') && ($company->user_mh == null
                    || $company->pass_mh == null || $company->cryptographic_key == null || $company->pin == null)) {
                    $message .= ($company->user_mh == null  || $company->pass_mh == null) ? '<br> -Usuario y contraseña de acceso al ministerio de hacienda ' : '';
                    $message .= ($company->cryptographic_key == null || $company->pin == null) ? '<br> -Llave criptografica y pin para el proceso de comprobantes' : '';
                    return redirect('/teams/' . Auth::user()->currentTeam->id)->with('message', $message);
                }
                return $next($request);
            }
        } else {
            return $next($request);
        }
    }
}
