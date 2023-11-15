<?php

namespace App\Http\Livewire\BranchOffice;

use App\Models\BranchOffice;
use Livewire\Component;
use App\Models\Cantons;
use App\Models\Districts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Terminal;
use App\Models\Consecutive;
use App\Models\Count;

class BranchOfficeComponent extends Component
{

    //datos para manejo de sucursales
    public $allBO, $name, $provinces = [], $cantons = [], $districts = [], $id_province, $id_canton, $id_district, $id_country_code = '52', $number = 1, $country_codes, $other_signs, $emails, $phone;
    //datos para manejo de terminales
    public $allTerminals, $numberT = 1, $id_branch_office = 0, $updateMode = false, $bo_id, $ante, $id_count, $allAcounts;

    //datos generales
    public $isOpen = 0, $message;
    public function mount()
    {
        $this->allAcounts = Count::where("id_count_primary", 4)->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->id_count = $this->allAcounts[0]->id;
    }
    public function render()
    {
        if ($this->updateMode) {
            $this->cantons = Cantons::where("id_province", $this->id_province)->get();
            $this->districts = Districts::where("id_canton", $this->id_canton)->get();
        } else {
            if (!empty($this->id_province)) {
                if ($this->ante != $this->id_province) {
                    $this->ante = $this->id_province;
                    $this->cantons = Cantons::where("id_province", $this->id_province)->get();
                    $this->districts = array();
                } else {
                    if (!empty($this->id_canton)) {
                        $this->districts = Districts::where("id_canton", $this->id_canton)->get();
                    } else {
                        $this->districts = array();
                    }
                }
            } else {
                $this->cantons = array();
                $this->districts = array();
            }
        }

        $this->allTerminals = Terminal::where("terminals.id_company", "=", Auth::user()->currentTeam->id)
            ->leftJoin('branch_offices', 'branch_offices.id', '=', 'terminals.id_branch_office')
            ->select('terminals.*', 'branch_offices.name_branch_office')->get();

        $this->provinces = DB::table('provinces')->get();
        $this->country_codes = DB::table('country_codes')->get();
        $this->allBO = BranchOffice::where("branch_offices.id_company", "=", Auth::user()->currentTeam->id)
            ->leftJoin('provinces', 'provinces.id', '=', 'branch_offices.id_province')
            ->leftJoin('cantons', 'cantons.id', '=', 'branch_offices.id_canton')
            ->leftJoin('districts', 'districts.id', '=', 'branch_offices.id_district')
            ->leftJoin('country_codes', 'country_codes.id', '=', 'branch_offices.id_country_code')
            ->select(
                'branch_offices.*',
                'provinces.code as province_code',
                'provinces.province as nameProvince',
                'cantons.canton as nameCanton',
                'districts.district as nameDistrict',
                'cantons.code as canton_code',
                'districts.code as district_code',
                'country_codes.phone_code as phone_code'
            )->get();

        return view('livewire.branch-office.branch-office-component');
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->id_province = '';
        $this->id_canton = '';
        $this->id_district = '';
        $this->other_signs = '';
        $this->emails = '';
        $this->id_country_code = '52';
        $this->phone = '';
        $this->number = 1;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'number' => 'required',
            'id_province' => 'required|exists:provinces,id',
            'id_canton' => 'required|exists:cantons,id',
            'id_count' => 'required|exists:counts,id',
            'id_district' => 'required|exists:districts,id',
            'other_signs' => 'required',
            'emails' => 'required|email',
            'id_country_code' => 'required|exists:country_codes,id',
            'phone' => 'required|numeric|digits_between:8,8'
        ]);
        $b = BranchOffice::where("branch_offices.id_company", "=", Auth::user()->currentTeam->id)
            ->where("branch_offices.number", "=", $this->number)->first();
        if ($b == null) {
            DB::beginTransaction();
            try {
                $result = BranchOffice::create([
                    'number' => $this->number,
                    'id_company' => Auth::user()->currentTeam->id,
                    'name_branch_office' => $this->name,
                    'id_province' => $this->id_province,
                    'id_canton' => $this->id_canton,
                    'id_district' => $this->id_district,
                    'other_signs' => $this->other_signs,
                    'emails' => $this->emails,
                    'id_country_code' => $this->id_country_code,
                    'id_count' => $this->id_count,
                    'phone' => $this->phone
                ]);
                $this->resetInputFields();
                $this->dispatchBrowserEvent('bo_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Sucursal creada con exito']);
            } catch (\Illuminate\Database\QueryException $e) {
                // Rollback and then redirect
                // back to form with errors
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
                DB::rollback();
            } catch (\Exception $e) {

                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion. ' . $e->getMessage()]);
                DB::rollback();
            }
            DB::commit();
            $this->emit('refreshTerminal');
        } else {
            $this->resetInputFields();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'El numero de sucursal ya existe para esta compañia']);
        }
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $bo = BranchOffice::where('id', $id)->first();
        $this->bo_id = $id;
        $this->number = $bo->number;
        $this->name = $bo->name_branch_office;
        $this->id_province = $bo->id_province;
        $this->id_canton = $bo->id_canton;
        $this->id_district = $bo->id_district;
        $this->other_signs = $bo->other_signs;
        $this->emails = $bo->emails;
        $this->id_country_code = $bo->id_country_code;
        $this->phone = $bo->phone;
        $this->id_count = ($bo->id_count) ? $bo->id_count : $this->allAcounts[0]->id;
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'number' => 'required',
            'id_province' => 'required|exists:provinces,id',
            'id_canton' => 'required|exists:cantons,id',
            'id_district' => 'required|exists:districts,id',
            'other_signs' => 'required',
            'id_count' => 'required|exists:counts,id',
            'emails' => 'required|email',
            'id_country_code' => 'required|exists:country_codes,id',
            'phone' => 'required|numeric|digits_between:8,8'
        ]);
        if ($this->bo_id) {
            $bo = \App\Models\BranchOffice::find($this->bo_id);
            $bo->update([
                'number' => $this->number,
                'id_company' => Auth::user()->currentTeam->id,
                'name_branch_office' => $this->name,
                'id_province' => $this->id_province,
                'id_canton' => $this->id_canton,
                'id_district' => $this->id_district,
                'other_signs' => $this->other_signs,
                'emails' => $this->emails,
                'id_country_code' => $this->id_country_code,
                'id_count' => $this->id_count,
                'phone' => $this->phone
            ]);
            $this->updateMode = false;
            $this->resetInputFields();
            $this->dispatchBrowserEvent('boU_modal_hide', []);
            $this->emit('refreshTerminal');
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Sucursal actualizada con exito']);
        }
    }

    public function delete($id)
    {
        try {
            if ($id) {
                if (BranchOffice::where('id', $id)->delete()) {
                    $this->emit('refreshTerminal');
                    $this->dispatchBrowserEvent('messageData', ['messageData' => 'Sucursal eliminada con exito']);
                } else {
                    $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }

    //funciones para las terminales
    private function resetInputFieldsT()
    {
        $this->numberT = '1';
        $this->id_branch_office = '0';
    }
}
