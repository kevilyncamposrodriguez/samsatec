<?php

namespace App\Http\Livewire\Report;

use App\Exports\DocumentsDetailExport;
use App\Models\CompaniesEconomicActivities;
use App\Models\Expense;
use App\Models\DocumentDetail;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class DetailReportDocumentsComponent extends Component
{
    public $allDocuments = [], $allEAs = [];
    public $id_document, $start_date, $finish_date, $id_ea = 0;
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
        return view('livewire.report.detail-report-documents-component');
    }
    public function getForDate($export)
    {
        //gastos
        if (isset($this->id_ea) && $this->id_ea != 0) {
            $this->allDocuments = DocumentDetail::join('documents', 'documents.id', '=', 'document_details.id_document')
                ->where('id_company', Auth::user()->currentTeam->id)
                ->whereBetween('date_issue', [$this->start_date, $this->finish_date])
                ->where('e_a', $this->id_ea)
                ->Where('documents.type_doc', '01')
                ->orWhere('documents.type_doc', '11')
                ->join('clients', 'clients.id', '=', 'documents.id_client')
                ->join('mh_categories', 'mh_categories.id', '=', 'documents.id_mh_categories')
                ->leftJoin('counts', 'counts.id', '=', 'document_details.id_count')
                ->leftJoin('count_categories', 'count_categories.id', '=', 'counts.id_count_category')
                ->select(
                    'document_details.*',
                    'documents.*',
                    'counts.name as nameCount',
                    'count_categories.name as nameCountCategory',
                    'clients.id_card as idcardClient',
                    'clients.name_client as nameClient',
                    'mh_categories.name as nameMHCategoty'
                )
                ->orderBy('document_details.created_at', 'DESC')->get();
        } else {
            $this->allDocuments = DocumentDetail::join('documents', 'documents.id', '=', 'document_details.id_document')
                ->whereBetween('documents.created_at', [$this->start_date, $this->finish_date])
                ->where('documents.id_company', Auth::user()->currentTeam->id)
                ->Where('documents.type_doc', '01')
                ->orWhere('documents.type_doc', '11')
                ->join('clients', 'clients.id', '=', 'documents.id_client')
                ->join('mh_categories', 'mh_categories.id', '=', 'documents.id_mh_categories')
                ->leftJoin('counts', 'counts.id', '=', 'document_details.id_count')
                ->leftJoin('count_categories', 'count_categories.id', '=', 'counts.id_count_category')
                ->select(
                    'document_details.*',
                    'documents.*',
                    'counts.name as nameCount',
                    'count_categories.name as nameCountCategory',
                    'clients.id_card as idcardClient',
                    'clients.name_client as nameClient',
                    'mh_categories.name as nameMHCategoty'
                )
                ->orderBy('document_details.created_at', 'DESC')->get();
        }

        if ($export) {
            $datos['allDocuments'] = $this->allDocuments;
            return Excel::download(new DocumentsDetailExport($datos), 'Ventas.xlsx');
        } else {
            return view('livewire.report.detail-report-documents-component', $this->allDocuments);
        }
    }
}
