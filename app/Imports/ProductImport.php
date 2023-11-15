<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use App\Models\ClassProduct;
use App\Models\Count;
use App\Models\Family;
use App\Models\Skuse;
use App\Models\Tax;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Symfony\Component\Console\Helper\ProgressBar;

class ProductImport implements ToModel, WithHeadingRow, WithValidation, WithProgressBar
{
    use Importable;
    public $id_count_inventory;
    public function __construct($id_count_inventory)
    {
        $this->id_count_inventory = $id_count_inventory;
    }
    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }
    public function model(array $row)
    {

        if (empty($row["codigo_interno"]) || $row["codigo_interno"] == '') {
            return;
        }
        $tax = Tax::where('id_company', Auth::user()->currentTeam->id)->where('rate', $row["impuesto"])->first();
        $sku = Skuse::where('symbol', $row["unidad_medida"])->first()->id;
        $sku = ($sku) ? $sku : Skuse::where('symbol', "Unid")->first()->id;
        $class = ClassProduct::where('name', $row["clase"])->first();
        if ($class == null) {
            $class = ClassProduct::create([
                'id_company' => Auth::user()->currentTeam->id,
                'name' => $row["clase"],
                'symbol' => substr($row["clase"], 0, 3)
            ]);
        }
        $family = Family::where('name', $row["familia"])->first();
        if ($family == null) {
            $family = Family::create([
                'id_company' => Auth::user()->currentTeam->id,
                'name' => $row["familia"]
            ]);
        }
        $category = Category::where('name', $row["categoria"])->first();
        if ($category == null) {
            $category = Category::create([
                'id_company' => Auth::user()->currentTeam->id,
                'name' => $row["categoria"]
            ]);
        }
        if ($row["iva_incluido"] == 'SI' || $row["iva_incluido"] == 'Si' || $row["iva_incluido"] == 'si') {
            $total_price = $row["precio"];
            $price_unid = round($row["precio"]/(1+$row["impuesto"]/100),5);
        } else {
            $price_unid = $row["precio"];
            $total_price = round($row["precio"] + ($row["precio"] * $row["impuesto"] / 100),5);
        }
        return new Product([
            'id_company' => Auth::user()->currentTeam->id,
            'internal_code' => $row["codigo_interno"],
            'cabys' => $row["cabys"],
            'description' => $row["descripcion"],
            'ids_taxes' => ($tax) ? '["' . $tax->id . '"]' : null,
            'stock_start' => $row["stock"],
            'stock' => $row["stock"],
            'stock_base' => $row["stock_base"],
            'alert_min' => $row["alerta_stock"],
            'price_unid' => $price_unid,
            'total_price' => $total_price,
            'cost_unid' => $row["costo"],
            'id_sku' => $sku,
            'type' => ($row["unidad_medida"] == "Spe" || $row["unidad_medida"] == "Sp") ? 0 : 1,
            'id_class' => ($row["unidad_medida"] == "Spe" || $row["unidad_medida"] == "Sp") ? null : $class->id,
            'id_category' => ($row["unidad_medida"] == "Spe" || $row["unidad_medida"] == "Sp") ? null : $category->id,
            'id_family' => ($row["unidad_medida"] == "Spe" || $row["unidad_medida"] == "Sp") ? null : $family->id,
            'id_count_income' => Count::where("id_company", Auth::user()->currentTeam->id)->where('name', 'VENTAS')->first()->id,
            'id_count_expense' => Count::where("id_company", Auth::user()->currentTeam->id)->where('name', 'COSTO DE MERCADERÍA VENDIDA')->first()->id,
            'id_count_inventory' => ($row["unidad_medida"] == "Spe" || $row["unidad_medida"] == "Sp") ? null : Count::find($this->id_count_inventory)->id,

        ]);
    }

    public function rules(): array
    {
        if (empty($row["codigo_interno"]) || $row["codigo_interno"] == '') {
            return [];
        }
        return [
            'codigo_interno' => 'required',
            'cabys' => 'required|min:13|max:13|exists:cabys,codigo',
            'unidad_medida' => 'required|string',
            'impuesto' => 'required',
            'descripcion' => 'required|string',
            'exoneracion' => 'required',
            'stock' => 'required',
            'costo' => 'required',
            'precio' => 'required',
            'stock_base' => 'required',
            'alerta_stock' => 'required'
        ];
    }
    public function uniqueBy()
    {
        return 'codigo_interno';
    }
}
