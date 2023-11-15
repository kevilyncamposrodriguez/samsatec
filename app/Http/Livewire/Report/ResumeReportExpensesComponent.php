<?php

namespace App\Http\Livewire\Report;

use App\Exports\ExpensesResumeExport;
use App\Models\CompaniesEconomicActivities;
use App\Models\Expense;
use App\Models\ExpenseDetail;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ResumeReportExpensesComponent extends Component
{
    public $allExpenses = [], $allEAs = [];
    public $id_expense, $start_date, $finish_date, $id_ea = 0;
    public function mount()
    {
        $this->start_date = date('Y-m-d');
        $this->finish_date = date('Y-m-d');
    }
    public function render()
    {
        $this->allEAs = CompaniesEconomicActivities::where("companies_economic_activities.id_company", "=", Auth::user()->currentTeam->id)
            ->join('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
            ->select('economic_activities.*', 'companies_economic_activities.*')->get();
        return view('livewire.report.resume-report-expenses-component');
    }

    public function getForDate($export)
    {
        $this->allExpenses= [];
        $Expenses = [];
        //gastos
        if (isset($this->id_ea) && $this->id_ea != 0) {
            $Expenses = Expense::where('id_company', Auth::user()->currentTeam->id)
                ->where('e_a', $this->id_ea)
                ->whereBetween('date_issue', [$this->start_date, $this->finish_date])
                ->join('providers', 'providers.id', '=', 'expenses.id_provider')
                ->join('mh_categories', 'mh_categories.id', '=', 'expenses.id_mh_categories')
                ->leftJoin('counts', 'counts.id', '=', 'expenses.id_count')
                ->leftJoin('count_categories', 'count_categories.id', '=', 'counts.id_count_category')
                ->select(
                    'expenses.*',
                    'counts.name as nameCount',
                    'count_categories.name as nameCountCategory',
                    'providers.id_card as idcardProvider',
                    'providers.name_provider as nameProvider',
                    'mh_categories.name as nameMHCategoty'
                )
                ->orderBy('created_at', 'DESC')->get();
        } else {
            $Expenses = Expense::whereBetween('expenses.date_issue', [$this->start_date, $this->finish_date])
                ->where('expenses.id_company', Auth::user()->currentTeam->id)
                ->join('providers', 'providers.id', '=', 'expenses.id_provider')
                ->join('mh_categories', 'mh_categories.id', '=', 'expenses.id_mh_categories')
                ->leftJoin('counts', 'counts.id', '=', 'expenses.id_count')
                ->leftJoin('count_categories', 'count_categories.id', '=', 'counts.id_count_category')
                ->select(
                    'expenses.*',
                    'counts.name as nameCount',
                    'count_categories.name as nameCountCategory',
                    'providers.id_card as idcardProvider',
                    'providers.name_provider as nameProvider',
                    'mh_categories.name as nameMHCategoty'
                )
                ->orderBy('expenses.created_at', 'DESC')->get();
        }
        $datos['allExpenses'] = [];
        foreach ($Expenses as $key => $exp) {

            $iva0 = 0;
            $total0 = 0;
            $sub0 = 0;
            $mount0 = 0;
            $discount0=0;
            $exempt0=0;
            $iva1 = 0;
            $total1 = 0;
            $sub1 = 0;
            $mount1 = 0;
            $discount1=0;
            $exempt1=0;
            $iva2 = 0;
            $total2 = 0;
            $sub2 = 0;
            $mount2 = 0;
            $discount2=0;
            $exempt2=0;
            $iva4 = 0;
            $total4 = 0;
            $sub4 = 0;
            $mount4 = 0;
            $discount4=0;
            $exempt4=0;
            $iva8 = 0;
            $total8 = 0;
            $sub8 = 0;
            $mount8 = 0;
            $discount8=0;
            $exempt8=0;
            $iva13 = 0;
            $total13 = 0;
            $sub13 = 0;
            $mount13 = 0;
            $discount13=0;
            $exempt13=0;
           
            $details = ExpenseDetail::where('id_expense', $exp->id)->get();
            foreach ($details as $detail) {
                if (isset(json_decode($detail->taxes, true)['rate'])) {
                    if (intval(json_decode($detail->taxes, true)['rate']) == 13) {
                        $iva13 += intval(json_decode($detail->taxes, true)['mount']);
                        $total13 += $detail->total_amount_line;
                        $sub13 += $detail->subtotal;
                        $mount13 += $detail->total_amount;
                        $discount13 += (isset($detail->discounts)) ? $detail->discounts : 0;
                        $exempt13 += (isset(json_decode($detail->taxes, true)['rate'])) ? 0 : $detail->total_amount;
                    } else if (intval(json_decode($detail->taxes, true)['rate']) == 8) {
                        $iva8 += intval(json_decode($detail->taxes, true)['mount']);
                        $total8 += $detail->total_amount_line;
                        $sub8 += $detail->subtotal;
                        $mount8 += $detail->total_amount;
                        $discount8 += (isset($detail->discounts)) ? $detail->discounts : 0;
                        $exempt8 += (isset(json_decode($detail->taxes, true)['rate'])) ? 0 : $detail->total_amount;
                    } else if (intval(json_decode($detail->taxes, true)['rate']) == 4) {
                        $iva4 += intval(json_decode($detail->taxes, true)['mount']);
                        $total4 += $detail->total_amount_line;
                        $sub4 += $detail->subtotal;
                        $mount4 += $detail->total_amount;
                        $discount4 += (isset($detail->discounts)) ? $detail->discounts : 0;
                        $exempt4 += (isset(json_decode($detail->taxes, true)['rate'])) ? 0 : $detail->total_amount;
                    } else if (intval(json_decode($detail->taxes, true)['rate']) == 2) {
                        $iva2 += intval(json_decode($detail->taxes, true)['mount']);
                        $total2 += $detail->total_amount_line;
                        $sub2 += $detail->subtotal;
                        $mount2 += $detail->total_amount;
                        $discount2 += (isset($detail->discounts)) ? $detail->discounts : 0;
                        $exempt2 += (isset(json_decode($detail->taxes, true)['rate'])) ? 0 : $detail->total_amount;
                    } else if (intval(json_decode($detail->taxes, true)['rate']) == 1) {
                        $iva1 += intval(json_decode($detail->taxes, true)['mount']);
                        $total1 += $detail->total_amount_line;
                        $sub1 += $detail->subtotal;
                        $mount1 += $detail->total_amount;
                        $discount1 += (isset($detail->discounts)) ? $detail->discounts : 0;
                        $exempt1 += (isset(json_decode($detail->taxes, true)['rate'])) ? 0 : $detail->total_amount;
                    } else{}
                } else {
                    $iva0 += 0;
                    $total0 += $detail->total_amount_line;
                    $sub0 += 0;
                    $mount0 += $detail->total_amount;
                    $discount0 += (isset($detail->discounts)) ? $detail->discounts : 0;
                    $exempt0 += (isset(json_decode($detail->taxes, true)['rate'])) ? 0 : $detail->total_amount;
                }
            }
            if ($total0 > 0) {
                $data = array(
                    'type' => (substr($exp->key, 29, 2) == '01') ? 'Factura Electrónica' : ((substr($exp->key, 29, 2) == '03') ? 'Nota credito' : 'Factura Compra'),
                    'e_a' => $exp->e_a,
                    'date' => substr($exp->date_issue,0,10),
                    'key' => $exp->key,
                    'idcardProvider' => $exp->idcardProvider,
                    'detail' => $detail->detail,
                    'nameProvider' => $exp->nameProvider,
                    'nameMHCategoty' => $exp->nameMHCategoty,
                    'total_amount' => $mount0,
                    'discount' => $discount0,
                    'exempt' => $exempt0,
                    'sub' => $sub0,
                    'iva' => 0,
                    'tax' => $iva0,
                    'total' => $total0
                );
                array_push($this->allExpenses, $data);
            }
            if ($total1 > 0) {
                $data = array(
                    'type' => (substr($exp->key, 29, 2) == '01') ? 'Factura Electrónica' : ((substr($exp->key, 29, 2) == '03') ? 'Nota credito' : 'Factura Compra'),
                    'e_a' => $exp->e_a,
                    'date' => substr($exp->date_issue,0,10),
                    'key' => $exp->key,
                    'idcardProvider' => $exp->idcardProvider,
                    'nameProvider' => $exp->nameProvider,
                    'nameMHCategoty' => $exp->nameMHCategoty,
                    'total_amount' => $mount1,
                    'discount' => $discount1,
                    'exempt' => $exempt1,
                    'sub' => $sub1,
                    'iva' => 1,
                    'tax' => $iva1,
                    'total' => $total1
                );
                array_push($this->allExpenses, $data);
            }
            if ($total2 > 0) {
                $data = array(
                    'type' => (substr($exp->key, 29, 2) == '01') ? 'Factura Electrónica' : ((substr($exp->key, 29, 2) == '03') ? 'Nota credito' : 'Factura Compra'),
                    'e_a' => $exp->e_a,
                    'date' => substr($exp->date_issue,0,10),
                    'key' => $exp->key,
                    'idcardProvider' => $exp->idcardProvider,
                    'detail' => $detail->detail,
                    'nameProvider' => $exp->nameProvider,
                    'nameMHCategoty' => $exp->nameMHCategoty,
                    'total_amount' => $mount2,
                    'discount' => $discount2,
                    'exempt' => $exempt2,
                    'sub' => $sub2,
                    'iva' => 2,
                    'tax' => $iva2,
                    'total' => $total2
                );
                array_push($this->allExpenses, $data);
            }
            if ($total4 > 0) {
                $data = array(
                    'type' => (substr($exp->key, 29, 2) == '01') ? 'Factura Electrónica' : ((substr($exp->key, 29, 2) == '03') ? 'Nota credito' : 'Factura Compra'),
                    'e_a' => $exp->e_a,
                    'date' => substr($exp->date_issue,0,10),
                    'key' => $exp->key,
                    'idcardProvider' => $exp->idcardProvider,
                    'detail' => $detail->detail,
                    'nameProvider' => $exp->nameProvider,
                    'nameMHCategoty' => $exp->nameMHCategoty,
                    'total_amount' => $mount4,
                    'discount' => $discount4,
                    'exempt' => $exempt4,
                    'sub' => $sub4,
                    'iva' => 4,
                    'tax' => $iva4,
                    'total' => $total4
                );
                array_push($this->allExpenses, $data);
            }
            if ($total8 > 0) {
                $data = array(
                    'type' => (substr($exp->key, 29, 2) == '01') ? 'Factura Electrónica' : ((substr($exp->key, 29, 2) == '03') ? 'Nota credito' : 'Factura Compra'),
                    'e_a' => $exp->e_a,
                    'date' => substr($exp->date_issue,0,10),
                    'key' => $exp->key,
                    'idcardProvider' => $exp->idcardProvider,
                    'detail' => $detail->detail,
                    'nameProvider' => $exp->nameProvider,
                    'nameMHCategoty' => $exp->nameMHCategoty,
                    'total_amount' => $mount8,
                    'discount' => $discount8,
                    'exempt' => $exempt8,
                    'sub' => $sub8,
                    'iva' => 8,
                    'tax' => $iva8,
                    'total' => $total8
                );
                array_push($this->allExpenses, $data);
            }
            if ($total13 > 0) {
                $data = array(
                    'type' => (substr($exp->key, 29, 2) == '01') ? 'Factura Electrónica' : ((substr($exp->key, 29, 2) == '03') ? 'Nota credito' : 'Factura Compra'),
                    'e_a' => $exp->e_a,
                    'date' => substr($exp->date_issue,0,10),
                    'key' => $exp->key,
                    'idcardProvider' => $exp->idcardProvider,
                    'detail' => $detail->detail,
                    'nameProvider' => $exp->nameProvider,
                    'nameMHCategoty' => $exp->nameMHCategoty,
                    'total_amount' => $mount13,
                    'discount' => $discount13,
                    'exempt' => $exempt13,
                    'sub' => $sub13,
                    'iva' => 13,
                    'tax' => $iva13,
                    'total' => $total13
                );
                array_push($this->allExpenses, $data);
            }
        }

        if ($export) {
            $datos['allExpenses'] = $this->allExpenses;
            return Excel::download(new ExpensesResumeExport($datos), 'Compras y Gastos Resumen.xlsx');
        } else {
            return view('livewire.report.resume-report-expenses-component', ['allExpenses', $this->allExpenses]);
        }
    }
}
