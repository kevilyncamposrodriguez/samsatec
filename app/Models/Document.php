<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Document extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_company',
        'id_client',
        'id_terminal',
        'key',
        'e_a',
        'consecutive',
        'date_issue',
        'sale_condition',
        'term',
        'payment_method',
        'currency',
        'exchange_rate',
        'delivery_date',
        'id_mh_categories',
        'total_taxed_services',
        'total_exempt_services',
        'total_exonerated_services',
        'total_taxed_merchandise',
        'total_exempt_merchandise',
        'total_exonerated_merchandise',
        'total_taxed',
        'total_exempt',
        'total_exonerated',
        'total_discount',
        'total_net_sale',
        'total_exoneration',
        'total_tax',
        'total_other_charges',
        'iva_returned',
        'total_cost',
        'total_document',
        'balance',
        'path',
        'priority',
        'answer_mh',
        'detail_mh',
        'state_send',
        'type_doc',
        'id_branch_office',
        'note',
        'id_seller',
        'state_proccess'
    ];
    public static function footer($type)
    {
        $header = "";
        switch ($type) {
            case '01':
                $header = '</FacturaElectronica>';
                break;
            case '02':
                $header = '</NotaDebitoElectronica>';
                break;
            case '03':
                $header = '</NotaCreditoElectronica>';
                break;
            case '04':
                $header = '</TiqueteElectronico>';
                break;
            case '08':
                $header = '</FacturaElectronicaCompra>';
                break;
            case '09':
                $header = '</FacturaElectronicaExportacion>';
                break;
            default:
                $header = '</FacturaElectronica>';
        }
        return $header;
    }

    public static function header($type)
    {
        $header = "";
        switch ($type) {
            case '01':
                $header = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '02':
                $header = '<?xml version="1.0" encoding="utf-8"?><NotaDebitoElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/notaDebitoElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '03':
                $header = '<?xml version="1.0" encoding="utf-8"?><NotaCreditoElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/notaCreditoElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '04':
                $header = '<?xml version="1.0" encoding="utf-8"?><TiqueteElectronico xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/tiqueteElectronico" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '08':
                $header = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronicaCompra xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronicaCompra" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '09':
                $header = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronicaExportacion xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronicaExportacion" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            default:
                $header = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        }
        return $header;
    }
    //scopes
    public function scopeStartDate($query, $startDate)
    {
        if ($startDate === '') {
            return;
        }
        return  $query->where('documents.created_at', '<=', date("Y-m-d", strtotime($this->f_end . "+ 1 days")));
    }
    public function scopeEndDate($query, $endDate)
    {
        if ($endDate === '') {
            return;
        }
        return  $query->where('documents.created_at', '>=', $this->f_start);
    }
    public static function allSales($idCompany)
    {
        return DB::select('call allSales("2")');
    }
}
