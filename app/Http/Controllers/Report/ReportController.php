<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCPDF;

class ReportController extends Controller
{

    public function print_report($invoices){
        if(Auth::check()){
            $inicial_date = date("Y-m-d H:i:s", substr($invoices, 0, strlen($invoices)/2));
            $limit_date = date("Y-m-d H:i:s", substr($invoices, strlen($invoices)/2, strlen($invoices)/2));
            $user = Auth::user();
            $company = DB::table('companies')->find($user->id_company);
            $this->report_generator($inicial_date, $limit_date, $company ,"REPORT");
        }
        return route('root');
    }

    public function print_credit($invoices){
        if(Auth::check()){
            $inicial_date = date("Y-m-d H:i:s", substr($invoices, 0, strlen($invoices)/2));
            $limit_date = date("Y-m-d H:i:s", substr($invoices, strlen($invoices)/2, strlen($invoices)/2));
            $user = Auth::user();
            $company = DB::table('companies')->find($user->id_company);
            $this->report_generator($inicial_date, $limit_date, $company ,"CREDIT");
        }
        return route('root');
    }

    public function print_debit($invoices){
        if(Auth::check()){
            $inicial_date = date("Y-m-d H:i:s", substr($invoices, 0, strlen($invoices)/2));
            $limit_date = date("Y-m-d H:i:s", substr($invoices, strlen($invoices)/2, strlen($invoices)/2));
            $user = Auth::user();
            $company = DB::table('companies')->find($user->id_company);
            $this->report_generator($inicial_date, $limit_date, $company ,"DEBIT");
        }
        return route('root');
    }



    private function report_generator($inicial_date, $limit_date, Object $company, $type){
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $company_name = $company->name;
            $company_nuit = $company->nuit;
            $company_address = $company->address;
            $company_phone = $company->phone;
            $company_email = $company->email;
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor($company_name);
            $type_doc = "Relatório";
            $invoice_status = 'PAID';
            if($type === "REPORT"){
                $pdf->setType($type_doc);
            }
            if($type === "CREDIT"){
                $type_doc = "Relatório (Pago)";
                $pdf->setType($type_doc);
                $invoice_status = 'PAID';
            }
            if($type === "DEBIT"){
                $type_doc = "Relatório (Não Pago)";
                $pdf->setType($type_doc);
                $invoice_status = 'NOT PAID';
            }
            $pdf->setType($type_doc);
            $pdf->SetTitle($type_doc);
            $pdf->SetSubject("Relatório");
            $pdf->SetKeywords("Thimiriza, $company_name, Relatório");
            $info_1 = '';
            if($inicial_date === $limit_date){
                $info_1 = '<hr/><html>'
                . '<head></head>'
                . '<body>'
                . '<table>'
                . '<tr><td><table>'
                . '<tr><td style="width: 100px; font-weight:bold;">Emissão:</td><td style="font-weight:bold;">' . date('d-m-Y' , strtotime(now())) . '</td></tr>'
                . '<tr><td style="width: 100px;">Empresa:</td><td colspan="2" style="width: 300px;">' . $company_name . '</td></tr>'
                . '<tr><td style="width: 100px;">NUIT:</td><td colspan="2" style="width: 300px;">' . $company_nuit . '</td></tr>'
                . '<tr><td style="width: 100px;">Telefone:</td><td colspan="2" style="width: 300px;">' . $company_phone . '</td></tr>'
                . '<tr><td style="width: 100px;">Endereço:</td><td colspan="2" style="width: 300px;">' . $company_address . '</td></tr>'
                . '<tr><td style="width: 100px;"></td><td colspan="2" style="width: 230px;"></td></tr>'
                . '</table></td>'
                . '<td></td>'
                . '<td rowspan="2"><table><tr><td></td></tr><tr><td><img src="' . url('storage/' .$company->logo) . '" border="0" height="90" width="135" align="bottom"/></td></tr></table></td></tr>'
                . '<tr><td></td><td></td><td></td></tr>'
                . '<tr><td></td><td></td><td></td></tr>'
                . '<tr><td colspan="3" style="text-align:center; font-size:20px;">Relatório</td></tr>'
                . '<tr><td></td><td></td><td></td></tr>'
                . '<tr><td colspan="3" style="text-align:center; font-size:15px;"><u>' . date('d-m-Y', strtotime($inicial_date)) . '</u></td></tr>'
                . '</table>'
                . '</body>'
                . '</html>';
            }else{
                $info_1 = '<hr/><html>'
                . '<head></head>'
                . '<body>'
                . '<table>'
                . '<tr><td><table>'
                . '<tr><td style="width: 100px; font-weight:bold;">Emissão:</td><td style="font-weight:bold;">' . date('d-m-Y' , strtotime(now())) . '</td></tr>'
                . '<tr><td style="width: 100px;">Empresa:</td><td colspan="2" style="width: 300px;">' . $company_name . '</td></tr>'
                . '<tr><td style="width: 100px;">NUIT:</td><td colspan="2" style="width: 300px;">' . $company_nuit . '</td></tr>'
                . '<tr><td style="width: 100px;">Telefone:</td><td colspan="2" style="width: 300px;">' . $company_phone . '</td></tr>'
                . '<tr><td style="width: 100px;">Endereço:</td><td colspan="2" style="width: 300px;">' . $company_address . '</td></tr>'
                . '</table></td>'
                . '<td></td>'
                . '<td rowspan="2"><table><tr><td></td></tr><tr><td><img src="' . url('storage/' .$company->logo) . '" border="0" height="90" width="135" align="bottom"/></td></tr></table></td></tr>'
                . '<tr><td></td><td></td><td></td></tr>'
                . '<tr><td colspan="3" style="text-align:center; font-size:20px;">Relatório</td></tr>'
                . '<tr><td></td><td></td><td></td></tr>'
                . '<tr><td colspan="3" style="text-align:center; font-size:15px;"><u>' . date('d-m-Y', strtotime($inicial_date)) . '</u>   à   <u>' . date('d-m-Y', strtotime($limit_date)) . '</u></td></tr>'
                . '</table>'
                . '</body>'
                . '</html>';
            }

            $pdf->SetMargins(10, 23, 10);
            $bottom_margin = 60;
            $pdf->SetAutoPageBreak(TRUE, $bottom_margin);
            //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->AddPage();
            $pdf->SetFont('times', '', 10);
            $pdf->writeHTML($info_1, true, false, true, false, '');
            $pdf->SetFont('times', 'B', 10);
            $details = '<html>'
                . '<head></head>'
                . '<body>'
                . '<table cellspacing="1">'
                . '<tr>'
                . '<th style="text-align:left; font-weight:bold; border-bottom: 1px solid black;">DATA</th>'
                . '<th style="text-align:left; font-weight:bold; border-bottom: 1px solid black;">FACTURA</th>'
                . '<th style="text-align:center; font-weight:bold; border-bottom: 1px solid black;">ESTADO</th>'
                . '<th style="text-align:center; font-weight:bold; border-bottom: 1px solid black;">CLIENTE</th>'
                . '<th style="text-align:right; font-weight:bold; border-bottom: 1px solid black;">VALOR</th>'
                . '</tr>';
            $pdf->SetFont('times', '', 10);
            $invoices = '';
            if($type === 'REPORT'){
                if($inicial_date === $limit_date){
                    $invoices = DB::table('companies')
                    ->join('users', 'companies.id', '=', 'users.id_company')
                    ->join('invoices', 'users.id', '=', 'invoices.id_user')
                    ->select('invoices.*')
                    ->where('companies.id', 'like', $company->id)
                    ->where('invoices.created_at', 'like', $inicial_date)
                    ->orderByDesc('invoices.created_at')->get();
                }
                $invoices = DB::table('companies')
                ->join('users', 'companies.id', '=', 'users.id_company')
                ->join('invoices', 'users.id', '=', 'invoices.id_user')
                ->select('invoices.*')
                ->where('companies.id', 'like', $company->id)
                ->whereBetween('invoices.created_at', [$inicial_date, $limit_date])
                ->orderByDesc('invoices.created_at')->get();
            }
            if($type === 'CREDIT'){
                if($inicial_date === $limit_date){
                    $invoices = DB::table('companies')
                    ->join('users', 'companies.id', '=', 'users.id_company')
                    ->join('invoices', 'users.id', '=', 'invoices.id_user')
                    ->select('invoices.*')
                    ->where('companies.id', 'like', $company->id)
                    ->where('invoices.status', 'like', 'PAID')
                    ->where('invoices.created_at', '=', $inicial_date)
                    ->orderByDesc('invoices.created_at')->get();
                }
                $invoices = DB::table('companies')
                ->join('users', 'companies.id', '=', 'users.id_company')
                ->join('invoices', 'users.id', '=', 'invoices.id_user')
                ->select('invoices.*')
                ->where('companies.id', 'like', $company->id)
                ->where('invoices.status', 'like', 'PAID')
                ->whereBetween('invoices.created_at', [$inicial_date, $limit_date])
                ->orderByDesc('invoices.created_at')->get();
            }
            if($type === 'DEBIT'){
                if($inicial_date === $limit_date){
                    $invoices = DB::table('companies')
                    ->join('users', 'companies.id', '=', 'users.id_company')
                    ->join('invoices', 'users.id', '=', 'invoices.id_user')
                    ->select('invoices.*')
                    ->where('companies.id', 'like', $company->id)
                    ->where('invoices.status', 'like', 'NOT PAID')
                    ->where('invoices.created_at', 'like', $inicial_date)
                    ->orderByDesc('invoices.created_at')->get();
                }
                $invoices = DB::table('companies')
                ->join('users', 'companies.id', '=', 'users.id_company')
                ->join('invoices', 'users.id', '=', 'invoices.id_user')
                ->select('invoices.*')
                ->where('companies.id', 'like', $company->id)
                ->where('invoices.status', 'like', 'NOT PAID')
                ->whereBetween('invoices.created_at', [$inicial_date, $limit_date])
                ->orderByDesc('invoices.created_at')->get();
            }
            $price_total = 0;
            $invoice_total = 0;
            $invoice_total_paid = 0;
            $details_1 = '';
            $start_date = date('Y-m-d', strtotime($limit_date));
            $dates = [];
            $i = 0;
            $total_day = 0;
            foreach($invoices as $invoice){
                $price = 0;
                $client_type = $invoice->client_type;
                $invoice_status = 'NÃO PAGO';
                if($invoice->status === 'PAID'){
                    $invoice_status = 'PAGO';
                    $invoice_total_paid = $invoice_total_paid + 1;
                }
                if($client_type === 'SINGULAR'){
                    $client = DB::table('clients_singular')->select('name', 'surname')->find($invoice->id_client);
                    $client_name = $client->name .' ' . $client->surname;
                    $price = $invoice->price;
                    $details_2 = '<tr><td style="text-align:left;">' . $invoice->created_at . '</td><td style="text-align:left;">' . substr($invoice->code, 10, 11) . '</td>'
                    . '<td style="text-align:center;">' . $invoice_status . '</td><td style="text-align:center;">' . $client_name . '</td>'
                    . '<td style="text-align:right;">' . number_format($price, 2, ",", ".") . ' MT</td></tr>';
                }
                if($client_type === 'ENTERPRISE'){
                    $client = DB::table('clients_enterprise')->select('name')->find($invoice->id_client);
                    $client_name = $client->name;
                    $price = $invoice->price;
                    $details_2 = '<tr><td style="text-align:left;">' . $invoice->created_at . '</td><td style="text-align:left;">' . substr($invoice->code, 10, 11) . '</td>'
                    . '<td style="text-align:center;">' . $invoice_status . '</td><td style="text-align:center;">' . $client_name . '</td>'
                    . '<td style="text-align:right;">' . number_format($price, 2, ",", ".") . ' MT</td></tr>';
                }
                if(date('Y-m-d', strtotime($invoice->created_at)) === $start_date){
                    $i = $i + 1;
                    $total_day = $total_day + $price;
                    $dates[$start_date] = $i . '===' . $total_day;
                }else{
                    $i = 1;
                    $total_day = $price;
                    $start_date = date('Y-m-d', strtotime($invoice->created_at));
                    $dates[$start_date] = $i . '===' . $total_day;
                }
                $details_1 = $details_1 . $details_2;
                $price_total = $price_total + $price;
                $invoice_total = $invoice_total + 1;
            }
            $details_3 = $details. $details_1 . '</table></body></html>';
            $pdf->writeHTML($details_3, true, false, true, false, '');

            $pdf->SetFont('times', '', 10);
            $details_4 ='<table cellspacing="1" style="width:50%;">'
                            . '<tr><th style="font-weight:bold; border-bottom: 1px solid black;">DATA</th>'
                            . '<th style="font-weight:bold; border-bottom: 1px solid black;">N° FACTURAS</th>'
                            . '<th style="text-align:right; font-weight:bold; border-bottom: 1px solid black;">VALOR</th></tr>';
            $details_5 = '';
            foreach($dates as $date_key => $date_value){
                $value_data = explode('===', $date_value);
                $number = $value_data[0];
                $value = $value_data[1];
                $details_6 = '<tr><td>' . $date_key . '</td>'
                            . '<td style="text-align:center;">' . $number .'</td>'
                            . '<td style="text-align:right;">' . number_format($value, 2, ",", ".") . ' MT</td></tr>';
                $details_5 = $details_5 . $details_6;
            }
            $details_7 = $details_4 . $details_5. '</table>';
            $pdf->writeHTML($details_7, true, false, true, false, '');
            $pdf->SetFont('times', 'B', 10);
            $pdf->setData($price_total, $invoice_total, $company->bank_account_name, $company->bank_account_owner, $company->bank_account_number, $company->bank_account_nib);
            $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
            $pdf->Output('Factura.pdf', 'I');
    }
}


class MYPDF extends TCPDF {

    private $total_price = 0;
    private $total_invoices = 0;
    private $bank_name = '';
    private $bank_account_owner = '';
    private $bank_account_number = '';
    private $bank_account_nib = '';
    private $type = '';
    protected $last_page_flag = false;


    public function Close() {
        $this->last_page_flag = true;
        parent::Close();
    }

    // Page header
    public function Header() {
        $this->SetY(17);
        $this->SetFont('times', 'B', 12);
        $this->Cell(0, 3, $this->type, 0, false, 'L', 0, '', false, 'M', 'M');

    }

    // Page footer
    public function Footer() {
        if ($this->last_page_flag) {
            // Position at 15 mm from bottom
            $this->SetY(-65);
            // Set font
            $this->SetFont('times', '', 10);
            // Page number
            $html = '<hr/><html>'
                    . '<head></head>'
                    . '<body>'
                    . '<table>'
                    . '<tr><td colspan="2">Banco: ' . $this->bank_name . '</td><td></td><td></td><td font-size: 15px;><strong>FACTURAS:</strong></td><td colspan="2" style="text-align: right; font-weight:bold; font-size: 15px;">' . $this->total_invoices . '</td></tr>'
                    . '<tr><td colspan="2">Nome da conta: ' . $this->bank_account_owner . '</td><td></td><td></td><td><strong></strong></td><td colspan="2" style="text-align: right;"></td></tr>'
                    . '<tr><td colspan="2">Nº. da conta: ' . $this->bank_account_number . '</td><td></td><td></td><td></td><td></td><td></td></tr>'
                    . '<tr><td colspan="2">NIB: ' . $this->bank_account_nib . '</td><td></td><td></td><td style="font-size: 20px;"><strong>Total:</strong></td><td colspan="2"><strong style="text-align: right; font-size: 20px;">' . number_format($this->total_price, 2, ",", ".") . ' MT</strong></td></tr>'
                    . '<tr><td colspan="2"></td><td></td><td></td><td></td><td></td><td></td></tr>'
                    . '<tr><td colspan="4">Documento processado por computador (Thimiriza)</td><td></td><td></td><td></td><td><strong></strong></td><td></td></tr>'
                    . '</table>'
                    . '</body>'
                    . '</html>';
            $this->writeHTML($html, true, false, true, false, '');
            $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }else{

        }
    }

    public function setType($type){
        $this->type = $type;
    }

    public function setData($total_price, $total_invoices, $bank_name, $bank_account_owner, $bank_account_number, $bank_account_nib) {
        $this->total_price = $total_price;
        $this->total_invoices = $total_invoices;
        $this->bank_name = $bank_name;
        $this->bank_account_owner = $bank_account_owner;
        $this->bank_account_number = $bank_account_number;
        $this->bank_account_nib = $bank_account_nib;
    }
}
