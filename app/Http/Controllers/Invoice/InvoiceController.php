<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SystemMail\SystemMailController;
use TCPDF;

class InvoiceController extends Controller
{
    public function invoice_payment(Request $request){
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $id = $request['id'];
            if(DB::table('invoices')
                ->where('id', $id)
                ->update(array(
                'status' => 'PAID',
                'id_user' => $user_id,
                'updated_at' => now()
            ))){
                    return redirect()->route('view_debit')->with('debit_notification', 'Pagamento da factura efectuado com sucesso.');
                }
            return redirect()->route('view_debit')->with('debit_notification', 'Falhou! Ocorreu um erro durante o pagamento da facuta.');
        }
        return route('root');
    }

    public function see_invoice($invoice_id){
        $invoice = DB::table('invoices')->find($invoice_id);
        $user = DB::table('users')->find($invoice->id_user);
        $company = DB::table('companies')->find($user->id_company);
        $status = $invoice->status;
        $user_name = $user->name . ' ' . $user->surname;
        $client_type = '';
        $client_name = '';
        $client_email = '';
        $client_nuit = '';
        $client_address = '';
        if($invoice->client_type === 'ENTERPRISE'){
            $client = DB::table('clients_enterprise')->find($invoice->id_client);
            $client_name = $client->name;
            $client_nuit = $client->nuit;
            $client_email = $client->email;
            $client_nuit = $client->nuit;
        }
        if($invoice->client_type === 'SINGULAR'){
            $client = DB::table('clients_singular')->find($invoice->id_client);
            $client_name = $client->name . ' ' . $client->surname;
            $client_nuit = $client->nuit;
            $client_email = $client->email;
            $client_nuit = $client->nuit;
        }

        $this->invoice_generator([
            'user_id' => $user->id,
            'client_type' => $client_type,
            'client_name' => $client_name,
            'client_email' => $client_email,
            'client_address' => $client_address,
            'client_nuit' => $client_nuit,
            'company_name' => $company->name,
            'company_email' => $company->email,
            'company_address' => $company->address,
            'company_nuit' => $company->nuit,
            'company_type' => $company->type,
            'company_bank_account_owner' => $company->bank_account_owner,
            'company_bank_account_name' => $company->bank_account_name,
            'company_bank_account_nib' => $company->bank_account_nib,
            'company_bank_account_number' => $company->bank_account_number,
            'company_logo' => url('storage/' .$company->logo),
            'user_name' => $user_name,
            'status' => $status,
            'invoice_code' => $invoice->code,
            'invoice_id' => $invoice_id
        ], 'INVOICE');
    }

    private function invoice_generator(Array $data, $type){
        $company_name = $data['company_name'];
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($data['company_name'] . '/' . $data['user_name']);
        //INVOICE

            $invoice_code = $data['invoice_code'];
            $pdf->setType($type);
            $pdf->SetTitle("Factura");
            $pdf->SetSubject("Factura");
            $pdf->SetKeywords("Thimiriza, $company_name, Factura");
            $info_1 = '<hr/><html>'
                . '<head></head>'
                . '<body>'
                . '<table>'
                . '<tr><td><table>'
                . '<tr><td style="width: 100px;">Nº. da Factura:</td><td colspan="2" style="width: 250px;">' . date('y') .'/' . date('m') . substr($invoice_code, 10 ,11) . '</td></tr>'
                . '<tr><td style="width: 100px;">Data:</td><td>' . now() . '</td></tr>'
                . '<tr><td style="width: 100px;">Responsável:</td><td colspan="2" style="width: 230px;">' . $data['user_name'] . '</td></tr>'
                . '</table></td>'
                . '<td></td>'
                . '<td rowspan="2"><table><tr><td></td></tr><tr><td><img src="' . $data['company_logo'] . '" border="0" height="90" width="135" align="bottom"/></td></tr></table></td></tr>'
                . '<tr><td></td><td></td><td></td></tr>'
                . '</table>'
                . '</body>'
                . '</html>';
            $pdf->SetKeywords("Thimiriza, $company_name, Factura");
            $pdf->SetMargins(10, 23, 10);
            $bottom_margin = 60;
            $pdf->SetAutoPageBreak(TRUE, $bottom_margin);
            //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->AddPage();
            $pdf->SetFont('times', 'B', 10);
            $pdf->writeHTML($info_1, true, false, true, false, '');
            $pdf->SetFont('times', '', 10);
            $info_2 = '<html>'
                . '<head></head>'
                . '<body>'
                . '<table>'
                . '<tr><td>DE:</td><td>PARA:</td></tr>'
                . '<tr><td><strong>' . $data['company_name'] . '</strong></td><td><strong>' . $data['client_name'] . '</strong></td></tr>'
                . '<tr><td>NUIT: ' . $data['company_nuit'] . '</td><td>NUIT: ' . $data['client_nuit'] . '</td></tr>'
                . '<tr><td>Endereço:</td><td>Endereço:</td></tr>'
                . '<tr><td>' . $data['company_address'] . '</td><td>' . $data['client_address'] . '</td></tr>'
                . '</table>'
                . '</body>'
                . '</html>';
            $pdf->writeHTML($info_2, true, false, true, false, '');
            $pdf->SetFont('times', 'B', 10);
            $details = '<html>'
                . '<head></head>'
                . '<body>'
                . '<table cellspacing="1">'
                . '<tr><th style="text-align:left;">NOME</th><th style="text-align:left;">DESCRIÇÃO</th><th style="text-align:center;">QUANT.</th><th style="text-align:center;">PREÇO UNIT.</th><th style="text-align:center;">IVA</th><th style="text-align:center;">DESCONTO</th><th style="text-align:right;">PREÇO INC.</th><th style="text-align:right;">PREÇO TOTAL</th></tr>'
                . '</table>'
                . '<hr/>'
                . '</body>'
                . '</html>';
            $pdf->writeHTML($details, true, false, true, false, '');
            $pdf->SetFont('times', '', 10);
            $sales = DB::table('moves')->select('*')
            ->where('id_invoice', 'like', $data['invoice_id'])->get();
            $price_total = 0;
            $iva_total = 0;
            $price_inc_total = 0;
            $discount_total = 0;
            $invoice = '';
            foreach($sales as $sale){
                $price_sale = 0;
                $iva = 0;
                $price_inc = 0;
                $quantity = 0;
                $discount = 0;
                if($sale->sale_type === 'PRODUCT'){
                    $name = $sale->product_service;
                    $description = $sale->description;
                    $quantity = $sale->quantity;
                    $price = $sale->price + $sale->iva - $sale->discount;
                    $price_inc = $sale->price / $sale->quantity;
                    $iva = $price_inc * $sale->iva;
                    $discount = $price_inc * $sale->discount;
                    $price_sale = $price_inc - $discount + $iva;
                    $details_2 = '<table cellspacing="1">'
                    . '<tr><td style="text-align:left;">' . $name . '</td><td style="text-align:left;">' . $description . '</td>'
                    . '<td style="text-align:center;">' . $quantity . '</td>'
                    . '<td style="text-align:right;">' . number_format($price, 2, ",", ".") . ' MT</td>'
                    . '<td style="text-align:center;">' . $sale->iva*100 . '%</td>'
                    . '<td style="text-align:center;">' . $sale->discount*100 . '%</td>'
                    . '<td style="text-align:right;">' . number_format($price_inc, 2, ",", ".") . ' MT</td>'
                    . '<td style="text-align:right;">' . number_format($price_sale, 2, ",", ".") . ' MT</td></tr>'
                    . '</table>';
                    $pdf->writeHTML($details_2, true, false, true, false, '');
                }
                if($sale->sale_type === 'SERVICE'){
                    $service = DB::table('services')->find($sale->id_product_service);
                    $name = $service->name;
                    $description = $service->description;
                    $quantity = $sale->quantity;
                    $price = $service->price;
                    $price_inc = $service->price * $sale->quantity;
                    $iva = $price_inc * $sale->iva;
                    $discount = $price_inc * $sale->discount;
                    $price_sale = $price_inc - $discount + $iva;
                    $details_2 = '<table cellspacing="1">'
                    . '<tr><td style="text-align:left;">' . $name . '</td><td style="text-align:left;">' . $description . '</td>'
                    . '<td style="text-align:center;">' . $quantity . '</td>'
                    . '<td style="text-align:right;">' . number_format($price, 2, ",", ".") . ' MT</td>'
                    . '<td style="text-align:center;">' . $sale->iva*100 . '%</td>'
                    . '<td style="text-align:center;">' . $sale->discount*100 . '%</td>'
                    . '<td style="text-align:right;">' . number_format($price_inc, 2, ",", ".") . ' MT</td>'
                    . '<td style="text-align:right;">' . number_format($price_sale, 2, ",", ".") . ' MT</td></tr>'
                    . '</table>';
                    $pdf->writeHTML($details_2, true, false, true, false, '');
                }
                $price_total = $price_total + $price_sale;
                $price_inc_total = $price_inc_total + $price_inc;
                $iva_total = $iva_total + $iva;
                $discount_total = $discount_total + $discount;
            }
            $details_3 = "<hr/>"
            . "<table>"
            . "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td>Total:</td><td>$price_total MT</td></tr>"
            . "</table>";
            $pdf->SetFont('times', 'B', 10);
            $pdf->setData($price_total, $price_inc_total, $discount_total, $iva_total, $data['company_bank_account_name'], $data['company_bank_account_owner'], $data['company_bank_account_number'], $data['company_bank_account_nib']);
            $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
            $pdf->Output('Factura.pdf', 'I');
    }
}

class MYPDF extends TCPDF {

    private $preco_total = 0;
    private $preco_incidencia = 0;
    private $desconto = 0;
    private $iva = 0;
    private $banco = '';
    private $titular = '';
    private $nr_conta = '';
    private $nib = '';
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
        if($this->type === 'INVOICE'){
            $this->Cell(0, 3, 'FACTURA', 0, false, 'L', 0, '', false, 'M', 'M');
        }
        if($this->type === 'QUOTE'){
            $this->Cell(0, 3, 'COTAÇÃO', 0, false, 'L', 0, '', false, 'M', 'M');
        }
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
                    . '<tr><td colspan="2">Banco: ' . $this->banco . '</td><td></td><td></td><td><strong>Incidência:</strong></td><td colspan="2" style="text-align: right;">' . number_format($this->preco_incidencia, 2, ",", ".") . ' MT</td></tr>'
                    . '<tr><td colspan="2">Nome da conta: ' . $this->titular . '</td><td></td><td></td><td><strong>IVA:</strong></td><td colspan="2" style="text-align: right;">' . number_format($this->iva, 2, ",", ".") . ' MT</td></tr>'
                    . '<tr><td colspan="2">Nº. da conta: ' . $this->nr_conta . '</td><td></td><td></td><td></td><td></td><td></td></tr>'
                    . '<tr><td colspan="2">NIB: ' . $this->nib . '</td><td></td><td></td><td style="font-size: 20px;"><strong>Total:</strong></td><td colspan="2"><strong style="text-align: right; font-size: 20px;">' . number_format($this->preco_total, 2, ",", ".") . ' MT</strong></td></tr>'
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

    public function setData($preco_total, $preco_incidencia, $desconto, $iva, $banco, $titular, $nr_conta, $nib) {
        $this->preco_total = $preco_total;
        $this->preco_incidencia = $preco_incidencia;
        $this->desconto = $desconto;
        $this->iva = $iva;
        $this->subtotal = ($this->preco_incidencia) - ($this->desconto) + ($this->iva);
        $this->banco = $banco;
        $this->titular = $titular;
        $this->nr_conta = $nr_conta;
        $this->nib = $nib;
    }
}
