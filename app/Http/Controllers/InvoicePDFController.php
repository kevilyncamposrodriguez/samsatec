<?php

namespace App\Http\Controllers;

use App\Models\BranchOffice;
use App\Models\Client;
use App\Models\Document;
use App\Models\DocumentDetail;
use App\Models\DocumentReference;
use App\Models\Reference;
use App\Models\Seller;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class InvoicePDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF($id)
    {
        $doc = Document::where('documents.id', '=', $id)
            ->join('sale_conditions', 'sale_conditions.code', '=', 'documents.sale_condition')
            ->join('payment_methods', 'payment_methods.code', '=', 'documents.payment_method')
            ->select(
                'documents.*',
                'sale_conditions.sale_condition as saleConditions',
                'payment_methods.payment_method as paymentMethod',
            )->first();
        $clave = $doc->key;
        $bo = BranchOffice::where('branch_offices.id_company', Auth::user()->currentTeam->id)
            ->where('branch_offices.number', $doc->id_branch_office)
            ->join('country_codes', 'country_codes.id', '=', 'branch_offices.id_country_code')
            ->join('provinces', 'provinces.id', '=', 'branch_offices.id_province')
            ->join('cantons', 'cantons.id', '=', 'branch_offices.id_canton')
            ->join('districts', 'districts.id', '=', 'branch_offices.id_district')
            ->select(
                'branch_offices.*',
                'country_codes.phone_code',
                'provinces.province as name_province',
                'cantons.canton as name_canton',
                'districts.district as name_district'
            )->first();

        $client = Client::where('clients.id', $doc->id_client)
            ->join('country_codes', 'country_codes.id', '=', 'clients.id_country_code')
            ->join('provinces', 'provinces.id', '=', 'clients.id_province')
            ->join('cantons', 'cantons.id', '=', 'clients.id_canton')
            ->join('districts', 'districts.id', '=', 'clients.id_district')
            ->select(
                'clients.*',
                'country_codes.phone_code',
                'provinces.province as name_province',
                'cantons.canton as name_canton',
                'districts.district as name_district'
            )->first();
        $reference = DocumentReference::where('document_references.id_document', $doc->id)->first();
        $details = DocumentDetail::where('document_details.id_document', $doc->id)->get();
        $seller = ($doc->id_seller) ? Seller::where('id', $doc->id_seller)->first() : '';
        $data = [
            'title' => $this->typeDoc($doc->type_doc),
            'document' => $doc,
            'company' => Auth::user()->currentTeam,
            'bo' => $bo,
            'client' => $client,
            'reference' => $reference,
            'details' => $details,
            'seller' => $seller
        ];

        $pdf = PDF::loadView('livewire.document.invoicePDF', $data);

        return $pdf->stream($clave . '.pdf');
    }
    public function typeDoc($type)
    {

        switch ($type) {
            case '01':
                return 'Factura Electronica';
                break;
            case '02':
                return 'Nota Electronica de Debito';
                break;
            case '03':
                return 'Nota Electronica de Credito';
                break;
            case '04':
                return 'Tiquete Electronico';
                break;
            case '09':
                return 'Factura Electronica de Exportacion';
                break;
            case '00':
                return 'Orden de Venta';
                break;
            case '11':
                return 'Factura';
                break;
            case '99':
                return 'Cotización';
                break;
            default:
                return 'Orden de Venta';
        }
    }
    public function ticketPDF($id)
    {
        $result = array();
        $doc = Document::where("id", $id)->first();
        if ($doc) {
            $result["key"] = $doc->key;
            $result["consecutive"] = (string) $doc->consecutive;
            $result["ced_emisor"] = Auth::user()->currentTeam->id_card;
            $result["name_compania"] = Auth::user()->currentTeam->name;
            $result["emisor"] = BranchOffice::find($doc->id_branch_office);
            $result["terminal"] = Terminal::find($doc->id_terminal)->number;
            $result["receptor"] = Client::where('id', $doc->id_client)->first();
            $result["detail"] = DocumentDetail::where("id_document", $doc->id)->get();
            $result["type_doc"] = $doc->type_doc;
            $result["typeDoc"] = $this->typeDoc($doc->type_doc);
            $result["subTotal"]  = $doc->total_net_sale;
            $result["tax"]  = $doc->total_tax;
            $result["desc"]  = $doc->total_discount;
            $result["total"]  = $doc->total_document;
            $result["date"]  = (string) $doc->date_issue;
            $result["otrosCargos"] = $doc->total_other_charges;
        } else {
            echo "Documento XML no encontrado";
            return 0;
        }
        $pdf = PDF::loadView('livewire.document.generar_ticket', $result);
        $pdf->setPaper('letther', 'portrait');
        return $pdf->stream('cine.pdf');
    }
    public function ticketCeller($id)
    {
        $result = array();
        $doc = Document::where("id", $id)->first();
        if ($doc) {
            $result["key"] = $doc->key;
            $result["consecutive"] = (string) $doc->consecutive;
            $result["ced_emisor"] = Auth::user()->currentTeam->id_card;
            $result["name_compania"] = Auth::user()->currentTeam->name;
            $result["emisor"] = BranchOffice::find($doc->id_branch_office);
            $result["terminal"] = Terminal::find($doc->id_terminal)->number;
            $result["receptor"] = Client::where('id', $doc->id_client)->first();
            $result["detail"] = DocumentDetail::where("id_document", $doc->id)->get();
            $result["type_doc"] = $doc->type_doc;
            $result["typeDoc"] = $this->typeDoc($doc->type_doc);
            $result["subTotal"]  = $doc->total_net_sale;
            $result["tax"]  = $doc->total_tax;
            $result["desc"]  = $doc->total_discount;
            $result["total"]  = $doc->total_document;
            $result["date"]  = (string) $doc->date_issue;
            $result["otrosCargos"] = $doc->total_other_charges;
        } else {
            echo "Documento XML no encontrado";
            return 0;
        }
        $pdf = PDF::loadView('livewire.cellar.generar_ticket', $result);
        $pdf->setPaper('letther', 'portrait');
        return $pdf->stream('cine.pdf');
    }
    public function ticketCellerProcessed($id)
    {
        $result = array();
        $doc = Document::where("id", $id)->first();
        if ($doc) {
            $result["key"] = $doc->key;
            $result["consecutive"] = (string) $doc->consecutive;
            $result["ced_emisor"] = Auth::user()->currentTeam->id_card;
            $result["name_compania"] = Auth::user()->currentTeam->name;
            $result["emisor"] = BranchOffice::find($doc->id_branch_office);
            $result["terminal"] = Terminal::find($doc->id_terminal)->number;
            $result["receptor"] = Client::where('id', $doc->id_client)->first();
            $result["detail"] = DocumentDetail::where("id_document", $doc->id)->get();
            $result["type_doc"] = $doc->type_doc;
            $result["typeDoc"] = $this->typeDoc($doc->type_doc);
            $result["subTotal"]  = $doc->total_net_sale;
            $result["tax"]  = $doc->total_tax;
            $result["desc"]  = $doc->total_discount;
            $result["total"]  = $doc->total_document;
            $result["date"]  = (string) $doc->date_issue;
            $result["otrosCargos"] = $doc->total_other_charges;
        } else {
            echo "Documento XML no encontrado";
            return 0;
        }
        $pdf = PDF::loadView('livewire.cellar.generar_ticket_processed', $result);
        $pdf->setPaper('letther', 'portrait');
        return $pdf->stream('tiquete_procesado.pdf');
    }
}
