<?php

namespace App\Imports;

use App\Models\Cantons;
use App\Models\Client;
use App\Models\Districts;
use App\Models\Document;
use App\Models\PaymentInvoice;
use App\Models\Province;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Date as DateTimeExcelDate;
use PhpOffice\PhpSpreadsheet\Shared\Date as SharedDate;

class PayImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $doc = Document::where('consecutive', str_replace("'", "", $row["consecutivo"]))->first();
            if (isset($doc->id)) {
                $result = PaymentInvoice::create([
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_count' => $row["id_cuenta"],
                    'id_document' => $doc->id,
                    'reference' => $row["referencia"],
                    'mount' => $row["monto"], //
                    'observations' => $row["observacion"],
                    'date' => $doc->created_at,
                ]);
                $result = Document::find($doc->id);
                $result->update([
                    'balance' =>($result->total_document-$row["monto"]),
                ]);
            }
        }

    }
}
