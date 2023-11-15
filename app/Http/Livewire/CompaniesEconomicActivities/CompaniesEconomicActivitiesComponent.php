<?php

namespace App\Http\Livewire\CompaniesEconomicActivities;

use App\Models\EconomicActivity;
use App\Models\CompaniesEconomicActivities;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use SebastianBergmann\Environment\Console;

class CompaniesEconomicActivitiesComponent extends Component
{
    public $allAECompany = [], $allAEs = [];
    public $name_ea, $id_ea = 1;
    protected $listeners = ['changeEA' => 'changeEA'];
    public function render()
    {
        $this->allAEs = EconomicActivity::all();
        $this->allAECompany = CompaniesEconomicActivities::where("companies_economic_activities.id_company", "=", Auth::user()->currentTeam->id)
            ->join('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
            ->select(
                'companies_economic_activities.*',
                'economic_activities.number as number',
                'economic_activities.name_ea as name_ea',
            )->get();
        return view('livewire.companies-economic-activities.companies-economic-activities-component');
    }
    private function resetInputFields()
    {
        $this->number = '0';
        $this->id_branch_office = '0';
    }
    public function changeEA($number)
    {
        $this->id_ea = $number;
    }
    public function store()
    {
        $cea = CompaniesEconomicActivities::where("companies_economic_activities.id_company", "=", Auth::user()->currentTeam->id)
            ->where("companies_economic_activities.id_economic_activity", "=", $this->id_ea)->first();
        if ($cea == null) {
            DB::beginTransaction();
            try {
                CompaniesEconomicActivities::create([
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_economic_activity' => $this->id_ea
                ]);

                $this->resetInputFields();
                $this->dispatchBrowserEvent('ea_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Actividad economica creada con exito']);
            } catch (\Illuminate\Database\QueryException $e) {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
                DB::rollback();
            } catch (\Exception $e) {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion. ' . $e->getMessage()]);
                DB::rollback();
            }
            DB::commit();
        } else {
            $this->resetInputFields();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Ya existe esta actividad economica para la compañia']);
        }
    }

    public function delete($id)
    {
        try {
            if ($id) {
                CompaniesEconomicActivities::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Actividad economica eliminada con exito']);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
