<?php

namespace App\Http\Livewire\Report;

use App\Exports\ExpensesDetailExport;
use App\Models\CompaniesEconomicActivities;
use App\Models\Expense;
use App\Models\ExpenseDetail;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class DetailReportExpensesComponent extends Component
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
        return view('livewire.report.detail-report-expenses-component');
    }
    public function getForDate($export)
    {
        //gastos
        if (isset($this->id_ea) && $this->id_ea != 0) {
            $this->allExpenses = ExpenseDetail::where('id_company', Auth::user()->currentTeam->id)
                ->where('e_a', $this->id_ea)
                ->whereBetween('date_issue', [$this->start_date, $this->finish_date])
                ->join('providers', 'providers.id', '=', 'expenses.id_provider')
                ->join('mh_categories', 'mh_categories.id', '=', 'expenses.id_mh_categories')
                ->leftJoin('counts', 'counts.id', '=', 'expenses.id_count')
                ->leftJoin('count_categories', 'count_categories.id', '=', 'counts.id_count_category')
                ->select(
                    'expense_details.*',
                    'expenses.*',
                    'counts.name as nameCount',
                    'count_categories.name as nameCountCategory',
                    'providers.id_card as idcardProvider',
                    'providers.name_provider as nameProvider',
                    'mh_categories.name as nameMHCategoty'
                )
                ->orderBy('created_at', 'DESC')->get();
        } else {
            $this->allExpenses = ExpenseDetail::join('expenses', 'expenses.id', '=', 'expense_details.id_expense')
                ->whereBetween('expenses.date_issue', [$this->start_date, $this->finish_date])
                ->where('expenses.id_company', Auth::user()->currentTeam->id)
                ->join('providers', 'providers.id', '=', 'expenses.id_provider')
                ->join('mh_categories', 'mh_categories.id', '=', 'expenses.id_mh_categories')
                ->leftJoin('counts', 'counts.id', '=', 'expenses.id_count')
                ->leftJoin('count_categories', 'count_categories.id', '=', 'counts.id_count_category')
                ->select(
                    'expense_details.*',
                    'expenses.*',
                    'counts.name as nameCount',
                    'count_categories.name as nameCountCategory',
                    'providers.id_card as idcardProvider',
                    'providers.name_provider as nameProvider',
                    'mh_categories.name as nameMHCategoty'
                )
                ->orderBy('expenses.created_at', 'DESC')->get();
        }

        if ($export) {
            $datos['allExpenses'] = $this->allExpenses;
            return Excel::download(new ExpensesDetailExport($datos), 'Compras y Gasto.xlsx');
        } else {
            return view('livewire.report.detail-report-expenses-component', $this->allExpenses);
        }
    }
}
