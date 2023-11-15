<?php

namespace App\Http\Livewire\Tax;

use Livewire\Component;
use App\Models\TaxesCode;
use App\Models\Tax;
use App\Models\RateCode;
use App\Models\Exoneration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TaxComponent extends Component
{

    public $tax_id, $updateMode = false, $id_taxes_code = 1, $id_rate_code = 0, $id_exoneration = 0, $description, $rate = 0, $exoneration_amount = 0, $tax_net = 0;
    public $allTaxesCodes = [], $allRatesCodes = [], $allExonerations = [], $allTaxes = [], $disable;

    public function render()
    {
        if ($this->id_exoneration != 0 && $this->rate != '') {
            $exo = Exoneration::where('id', $this->id_exoneration)->first();
            $this->exoneration_amount = $exo->exemption_percentage;
        } else {
            $this->exoneration_amount = 0;
        }

        $this->tax_net = ($this->rate != '') ? $this->rate - $this->exoneration_amount : 0;
        $this->allTaxesCodes = TaxesCode::all();
        $this->allRatesCodes = RateCode::all();
        $this->allExonerations = Exoneration::where("id_company",Auth::user()->currentTeam->id)
        ->where("active",'1')->get();
        $this->allTaxes = Tax::where("taxes.id_company", "=", Auth::user()->currentTeam->id)
            ->join('taxes_codes', 'taxes_codes.id', '=', 'taxes.id_taxes_code')
            ->leftJoin('rate_codes', 'rate_codes.id', '=', 'taxes.id_rate_code')
            ->leftJoin('exonerations', 'exonerations.id', '=', 'taxes.id_exoneration')
            ->select('taxes.*', 'taxes_codes.description as taxCode', 'rate_codes.description as rateCode', 'exonerations.description as exoneration')->get();
        return view('livewire.tax.tax-component');
    }

    public function changeTaxCode()
    {
        if (($this->id_taxes_code == 1 || $this->id_taxes_code == 7) && $this->id_rate_code != 0) {
            $result = RateCode::where('id', $this->id_rate_code)->first();
            $this->rate = $result->value;
        } else {
            $this->rate = 0;
            $this->id_rate_code = 0;
        }
    }

    public function changeRateCode()
    {
        if ($this->id_rate_code != 0) {
            $result = RateCode::where('id', $this->id_rate_code)->first();
            $this->rate = $result->value;
        } else {
            $this->rate = 0;
        }
    }

    private function resetInputFields()
    {
        $this->id_taxes_code = 1;
        $this->id_rate_code = 0;
        $this->id_exoneration = 0;
        $this->description = '';
        $this->rate = 0;
        $this->exoneration_amount = 0;
        $this->tax_net = 0;
    }

    public function store()
    {
        $this->validate([
            'id_taxes_code' => 'required|exists:taxes_codes,id',
            'id_rate_code' => $rc = ($this->id_taxes_code == 1 || $this->id_taxes_code == 7) ? 'required|exists:rate_codes,id' : '',
            'id_exoneration' => $e = ($this->id_exoneration != 0) ? 'required|exists:exonerations,id' : '',
            'description' => 'required',
            'rate' => 'required|numeric',
            'exoneration_amount' => 'required|numeric',
            'tax_net' => 'required|numeric'
        ]);
        DB::beginTransaction();
        try {
            Tax::create([
                'id_company' => Auth::user()->currentTeam->id,
                'id_taxes_code' => $this->id_taxes_code,
                'id_rate_code' => $rc = ($this->id_rate_code != 0) ? $this->id_rate_code : null,
                'id_exoneration' => $rc = ($this->id_exoneration != 0) ? $this->id_exoneration : null,
                'description' => $this->description,
                'rate' => $this->rate,
                'exoneration_amount' => $this->exoneration_amount,
                'tax_net' => $this->tax_net
            ]);

            $this->resetInputFields();
            $this->dispatchBrowserEvent('tax_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Impuesto creado con exito']);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $result = Tax::where('id', $id)->first();
        $this->tax_id = $id;
        $this->id_taxes_code = $result->id_taxes_code;
        $this->id_rate_code = $result->id_rate_code;
        $this->id_exoneration = $result->id_exoneration;
        $this->description = $result->description;
        $this->rate = $result->rate;
        $this->exoneration_amount = $result->exoneration_amount;
        $this->tax_net = $result->tax_net;
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function update()
    {
        $this->validate([
            'id_taxes_code' => 'required|exists:taxes_codes,id',
            'id_rate_code' => $rc = ($this->id_taxes_code == 1 || $this->id_taxes_code == 7) ? 'required|exists:rate_codes,id' : '',
            'id_exoneration' => $e = ($this->id_exoneration != 0) ? 'required|exists:exonerations,id' : '',
            'description' => 'required',
            'rate' => 'required|numeric',
            'exoneration_amount' => 'required|numeric',
            'tax_net' => 'required|numeric'
        ]);

        if ($this->tax_id) {
            DB::beginTransaction();
            try {
                $result = Tax::find($this->tax_id);
                $result->update([
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_taxes_code' => $this->id_taxes_code,
                    'id_rate_code' => $rc = ($this->id_rate_code != 0) ? $this->id_rate_code : null,
                    'id_exoneration' => $rc = ($this->id_exoneration != 0) ? $this->id_exoneration : null,
                    'description' => $this->description,
                    'rate' => $this->rate,
                    'exoneration_amount' => $this->exoneration_amount,
                    'tax_net' => $this->tax_net
                ]);
                $this->updateMode = false;

                $this->resetInputFields();
                $this->dispatchBrowserEvent('taxU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Impuesto actualizado con exito']);
            } catch (\Illuminate\Database\QueryException $e) {
                // back to form with errors
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
                DB::rollback();
            } catch (\Exception $e) {
                DB::rollback();
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
            }
            DB::commit();
        }
    }

    public function delete($id)
    {
        try {
            if ($id) {
                Tax::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Impuesto eliminado con exito']);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
