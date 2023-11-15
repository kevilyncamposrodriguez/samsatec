<?php

namespace App\Imports;

use App\Models\Cantons;
use App\Models\Client;
use App\Models\Districts;
use App\Models\PaymentMethods;
use App\Models\Province;
use App\Models\SaleConditions;
use App\Models\TypeIdCards;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class ClientImport implements ToCollection, WithHeadingRow, WithProgressBar, WithValidation, WithUpserts, WithCalculatedFormulas, WithMultipleSheets
{
    use Importable;
    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }
    public function collection(Collection $rows)
    {
        foreach ($rows as $index =>  $row) {

            if ($row['codigo'] != null && $row['codigo'] != '') {
                $type_idcard = TypeIdCards::where('type', $row["tipo_identificacion"])->first()->id;
                $provincia = Province::where('province', $row["provincia"])->first()->id;
                $canton =  Cantons::where("canton", $row["canton"])->first()->id;
                $distrito = Districts::where("district", $row["distrito"])->first()->id;
                $condicion_venta = SaleConditions::where("sale_condition", $row["condicion_venta"])->first()->id;
                $metodo_pago = PaymentMethods::where("payment_method", $row["metodo_pago"])->first()->id;
                $client = Client::create([
                    'code' => $row["codigo"],
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_card' => $row["identificacion"],
                    'type_id_card' =>  $type_idcard,
                    'name_client' => $row["nombre"],
                    'id_province' => $provincia,
                    'id_canton' => $canton,
                    'id_district' => $distrito,
                    'other_signs' => $row["otras_senas"],
                    'id_country_code' => 52,
                    'phone' => $row["telefono"],
                    'emails' => $row["correo"],
                    'id_sale_condition' => $condicion_venta,
                    'time' => $row["dias_credito"],
                    'id_currency' => 55,
                    'id_payment_method' => $metodo_pago,
                    'total_credit' => 0
                ]);
            }
        }
    }
    public function rules(): array
    {
        return [
            'codigo' => 'required|min:9|max:9',
            'nombre' => 'required|string',
            'tipo_identificacion' => 'required',
            'identificacion' => 'required|min:9|max:12',
            'provincia' => 'required',
            'canton' => 'required',
            'distrito' => 'required',
            'otras_senas' => 'required',
            'correo' => 'required',
            'telefono' => 'required',
            'metodo_pago' => 'required',
            'condicion_venta' => 'required',
            'dias_credito' => 'required',
        ];
    }
    public function uniqueBy()
    {
        return 'identificacion';
    }
}
