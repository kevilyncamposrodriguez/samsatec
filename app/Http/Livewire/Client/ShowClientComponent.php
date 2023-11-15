<?php

namespace App\Http\Livewire\Client;

use App\Models\Client;
use App\Models\Document;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowClientComponent extends Component
{
    public $id_client, $client, $pending, $expired, $allDocuments;
    public function mount($id_client)
    {
        $this->id_client = $id_client;
        $this->client = Client::where('clients.id', $this->id_client)
            ->leftJoin('districts', 'clients.id_district', 'districts.id')
            ->leftJoin('cantons', 'clients.id_canton', 'cantons.id')
            ->leftJoin('provinces', 'clients.id_province', 'provinces.id')->first();
        $this->pending = 0;
        $this->expired = 0;

        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            $this->allDocuments =  Document::where("documents.id_company", Auth::user()->currentTeam->id)
                ->where('id_client', $this->id_client)
                ->leftJoin('clients', 'documents.id_client', 'clients.id')
                ->leftJoin('branch_offices', 'documents.id_branch_office', 'branch_offices.id')
                ->leftJoin('terminals', 'documents.id_terminal', 'terminals.id')
                ->leftJoin('mh_categories', 'documents.id_mh_categories', '=', 'mh_categories.id')->get();
        } else {
            $this->allDocuments =  Document::query()->where("documents.id_company", Auth::user()->currentTeam->id)
                ->where('id_terminal', TeamUser::getUserTeam()->terminal)                
                ->where('id_client', $this->id_client)
                ->leftJoin('clients', 'documents.id_client', 'clients.id')
                ->leftJoin('branch_offices', 'documents.id_branch_office', 'branch_offices.id')
                ->leftJoin('terminals', 'documents.id_terminal', 'terminals.id')
                ->leftJoin('mh_categories', 'documents.id_mh_categories', '=', 'mh_categories.id')->get();
        }
        if ($this->allDocuments) {
            $this->pending = $this->allDocuments->where('balance', '>', '1')->whereIn('type_doc', ['01', '04', '11'])->count();
            foreach ($this->allDocuments->where('balance', '>', '1')->whereIn('type_doc', ['01', '04', '11']) as $key => $document) {
                if ($document->term < date_diff(date_create($document->date_issue), now())->days) {
                    $this->expired++;
                }
            }
        }
    }
    public function render()
    {
        return view('livewire.client.show-client-component');
    }
}
