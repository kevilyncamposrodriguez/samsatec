<?php

namespace App\Http\Livewire\DebtsPays;

use App\Models\Provider;
use App\Models\Count;
use App\Models\CXP;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Paybill;
use App\Models\Payment;
use App\Models\PaymentInvoice;
use App\Models\ProviderAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\CashFlow\Constant\Periodic\Payments;

class DebtsPaysComponent extends Component
{
    public $total = 0, $rango0 = 0, $rango0a15 = 0, $rango15a30 = 0, $rango30a60 = 0, $rango60a90 = 0, $rangoMas90 = 0, $notes,
        $provider, $id_provider = '', $selects = [], $facts = [], $pay, $allAcounts = [], $totalPay = 0, $id_count = '', $id_countP = '',
        $reference = '', $date = '', $allProviderAccounts = [], $time = 0;

    protected $listeners = ['changeSelectedP', 'setProvider', 'quitProvider'];
    public function mount($id_provider = null)
    {
        $this->date = date("Y-m-d");
        $this->id_provider = $id_provider;
        $this->provider = ($id_provider) ? Provider::find($id_provider)->name_provider : null;
        $this->allAcounts = Count::where("counts.id_company", Auth::user()->currentTeam->id)
            ->whereIn('counts.id_count_primary', ['34', '35'])->get();
        $this->id_count = isset($this->allAcounts[0]->id) ? $this->allAcounts[0]->id : '';
        $this->allProviderAccounts = ProviderAccount::where("id_provider", $this->id_provider)->where("active", '1')->get();
    }
    public function render()
    {
        $this->facts = [];
        $this->totalPay = 0;
        foreach ($this->selects as $index => $value) {
            $this->totalPay += ((isset($this->pay[$index]) && is_numeric($this->pay[$index])) ? $this->pay[$index] : 0);
            array_push($this->facts, Expense::find($value));
        }
        if ($this->id_provider) {
            $this->rango0 = collect(CXP::all())
                ->where('dias_de_atraso', 'Al Dia')
                ->where('company', Auth::user()->currentTeam->id)
                ->where('provider', $this->provider)->sum('saldo_pendiente');
            $this->rango0a15 = collect(CXP::all())
                ->where('dias_de_atraso', '0 a 15 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->where('provider', $this->provider)->sum('saldo_pendiente');
            $this->rango15a30 = collect(CXP::all())
                ->where('dias_de_atraso', '15 a 30 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->where('provider', $this->provider)->sum('saldo_pendiente');
            $this->rango30a60 = collect(CXP::all())
                ->where('dias_de_atraso', '30 a 60 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->where('provider', $this->provider)->sum('saldo_pendiente');
            $this->rango60a90 = collect(CXP::all())
                ->where('dias_de_atraso', '60 a 90 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->where('provider', $this->provider)->sum('saldo_pendiente');
            $this->rangoMas90 = collect(CXP::all())->where('dias_de_atraso', 'Mas de 90 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->where('provider', $this->provider)->sum('saldo_pendiente');
            $this->total = $this->rango0 + $this->rango0a15 + $this->rango15a30 + $this->rango30a60 + $this->rango60a90 + $this->rangoMas90;
        } else {
            $this->rango0 = collect(CXP::all())
                ->where('dias_de_atraso', 'Al Dia')
                ->where('company', Auth::user()->currentTeam->id)
                ->sum('saldo_pendiente');
            $this->rango0a15 = collect(CXP::all())
                ->where('dias_de_atraso', '0 a 15 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->sum('saldo_pendiente');
            $this->rango15a30 = collect(CXP::all())
                ->where('dias_de_atraso', '15 a 30 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->sum('saldo_pendiente');
            $this->rango30a60 = collect(CXP::all())
                ->where('dias_de_atraso', '30 a 60 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->sum('saldo_pendiente');
            $this->rango60a90 = collect(CXP::all())
                ->where('dias_de_atraso', '60 a 90 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->sum('saldo_pendiente');
            $this->rangoMas90 = collect(CXP::all())
                ->where('dias_de_atraso', 'Mas de 90 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->sum('saldo_pendiente');
            $this->total = $this->rango0 + $this->rango0a15 + $this->rango15a30 + $this->rango30a60 + $this->rango60a90 + $this->rangoMas90;
        }
        return view(
            'livewire.debts-pays.debts-pays-component',
            ["providerfind" => ($this->id_provider != '') ? Provider::where('providers.id', $this->id_provider)
                ->join('provinces', 'provinces.id', '=', 'providers.id_province')
                ->join('cantons', 'cantons.id', '=', 'providers.id_canton')
                ->join('districts', 'districts.id', '=', 'providers.id_district')
                ->join('country_codes', 'country_codes.id', '=', 'providers.id_country_code')
                ->join('sale_conditions', 'sale_conditions.id', '=', 'providers.id_sale_condition')
                ->join('type_id_cards', 'type_id_cards.id', '=', 'providers.type_id_card')->first() : null]
        );
    }
    public function saveNotes()
    {
        Provider::find($this->id_provider)->update([
            "notes" => $this->notes
        ]);
    }
    public function chargeNotes()
    {
        $this->notes = "";
        $this->notes =  Provider::find($this->id_provider)->notes;
    }
    public function changeSelectedP($value)
    {
        $this->selects = $value;
    }
    public function proccess()
    {
        $this->pay = [];
        foreach ($this->selects as $index => $value) {
            $d = Expense::find($value);
            $this->pay[$index] = $d->pending_amount;
        }
    }
    public function storePay()
    {
        $this->validate([
            'date' => 'required',
            'id_count' => 'required',
            'reference' => 'required'
        ]);
        DB::beginTransaction();
        foreach ($this->selects as $index => $value) {
            try {
                $doc = Expense::find($value);
                Payment::create([
                    'date' => $this->date,
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_expense' => $doc->id,
                    'id_count' => $this->id_count,
                    'reference' => $this->reference,
                    'mount' => $this->pay[$index],
                    'observations' => "Ninguna"
                ]);

                $doc->update([
                    'pending_amount' => $doc->pending_amount - $this->pay[$index]
                ]);
                //$this->resetInputFields();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Pagos realizados con exito']);
                $this->dispatchBrowserEvent('pays_modal_hide', []);
            } catch (\Illuminate\Database\QueryException $e) {
                // back to form with errors
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
                DB::rollback();
            } catch (\Exception $e) {
                DB::rollback();
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion. ' . $e->getMessage()]);
            }
        }
        DB::commit();
        $this->facts = [];
        $this->emit('clearSelectsCXPProviderTable');
        $this->emit('refreshCXPProviderTable');
    }
    public function getAccounts($id)
    {
        $counts = Count::where("counts.id_count", $id)->get();
        if (count($counts) > 0) {
            foreach ($counts as $key => $value) {
                array_push($this->allAcounts, $this->getAccounts($value->id));
            }
        } else {
            return Count::find($id);
        }
    }
}
