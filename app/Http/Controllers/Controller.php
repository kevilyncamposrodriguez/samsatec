<?php

namespace App\Http\Controllers;

use App\Models\PaymentSystem;
use App\Models\Plan;
use App\Models\Team;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Jetstream;

class Controller extends BaseController
{
    public function __construct()
    {
        ini_set('max_execution_time', 300);
    }
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Show the team management screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\View\View
     */
    public function showUser(Request $request, $teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        if (!$request->user()->belongsToTeam($team)) {
            abort(403);
        }

        return view('teams.showUser', [
            'user' => $request->user(),
            'team' => $team,
        ]);
    }
    public function chargesGenerate()
    {
        $lista = [];
        $pagos = [];
        $errors = [];
        $companies = Team::where('npay', 0)->get();
        foreach ($companies as $key => $company) {
            $invoices = PaymentSystem::select(
                'id_company',
                'pay_amount',
                'pay_detail',
                'fe',
                'plan_id',
                DB::raw('MAX(next_pay) as next_pay')
            )
                ->where("id_company", $company->id)
                ->groupBy('id_company')->first();
            array_push($lista, $invoices);
            $plan = Plan::find($company->plan_id);
            DB::beginTransaction();
            try {
                if ($invoices) {
                    if (($invoices->next_pay < date(now()))) {
                        $pay = PaymentSystem::create([
                            'id_company' => $company->id,
                            'pay_amount' => $plan->price_for_month,
                            'pay_detail' => $plan->name . ' 1 mes(es)',
                            'user' => "soporte@samsatec.com",
                            'months' => 1,
                            'plan_id' => $company->plan_id,
                            'start_pay' => $invoices->next_pay
                        ]);
                        array_push($pagos, ['id' => $pay->id, 'plan_id' => $company->plan_id, 'company' => $company->name, 'monto' => $plan->price_for_month]);
                    }
                } else {
                    if (count(PaymentSystem::where("id_company", $company->id)->get()) < 1) {
                        $pay = PaymentSystem::create([
                            'id_company' => $company->id,
                            'pay_amount' => $plan->price_for_month,
                            'pay_detail' => $plan->name . ' 1 mes(es)',
                            'user' => "soporte@samsatec.com",
                            'months' => 1,
                            'plan_id' => $company->plan_id,
                            'start_pay' => date("d/m/Y", strtotime(now()))
                        ]);
                        array_push($pagos, ['id2' => $pay->id, 'plan_id' => $company->plan_id, 'company' => $company->name, 'monto' => $plan->price_for_month]);
                    }
                }
            } catch (QueryException $e) {
                // back to form with errors
                array_push($errors, ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
                DB::rollback();
            } catch (\Exception $e) {
                DB::rollback();
                array_push($errors, ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            }
            DB::commit();
        }
        dd("Lista: ",$lista,"Nuevos Pagos:" ,$pagos, "Errores: ",$errors);
    }
}
