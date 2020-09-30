<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SystemMail\SystemMailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCPDF;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if(Auth::check()){
            $user = Auth::user();
            $company = DB::table('companies')->select('type')->where('id', 'like', $user->id_company)->first();
            $client = explode(' === ', $request['client'], 2);
            $client_name = $client[0];
            $client_email = $client[1];
            $sale_type = $request['sale_type'];
            $sale_name_description = explode(' === ', $request['name'], 2);
            $sale_name = trim($sale_name_description[0]);
            $sale_description = trim($sale_name_description[1]);
            $quantity = $request['quantity'];
            $discount = doubleval($request['discount']/100);
            $iva = 0;
            $type_client = 'ENTERPRISE';
            if($company->type === 'NORMAL'){
                $iva = 0.17;
            }

            $client = DB::table('clients_enterprise')
                ->select('*')
                ->where('email', 'like', $client_email)
                ->where('name', 'like', $client_name);
            if(!$client->exists()){
                $client = DB::table('clients_singular')
                    ->select('*')
                    ->where('email', 'like', $client_email);
                if(!$client->exists()){
                    return redirect()->route('view_sale')->with('sale_notification', 'Esse cliente nao existe.');
                }
                $type_client = 'SINGULAR';
            }
            $client = $client->first();
            if($sale_type === 'PRODUCT'){
                $products = DB::table('companies')
                ->join('users', 'companies.id', '=', 'users.id_company')
                ->join('products', 'users.id', '=', 'products.id_user')
                ->select('products.*')
                ->where('companies.id', 'like', $user->id_company)
                ->where('products.name', 'like', $sale_name)
                ->where('products.description', 'like', $sale_description);
                if($products->exists()){
                    //product exists
                    $products = $products->first();
                    if($products->quantity < $quantity){
                        return redirect()->route('view_sale')->with('sale_notification',
                        'A quantidade requisitada excede o stock. Actualmente o stock possui .' . $products->quantity);
                    }else{
                        $update_status = false;
                        if(
                            DB::table('sales')
                            ->updateOrInsert(
                                ['id_product_service' => $products->id, 'type' => $sale_type, 'iva' => $iva,
                                'type_client' => $type_client, 'id_client' => $client->id, 'id_user' => $user->id],
                                ['discount' => $discount, 'quantity' => $quantity]
                            )
                        ){$update_status = true;}
                        if($update_status){
                            return redirect()->route('view_sale')->with('sale_notification', 'Sucesso Produto.');
                        }
                    }
                }
                return redirect()->route('view_sale')->with('sale_notification', 'Esse produto nao existe.');
            }
            if($sale_type === 'SERVICE'){
                $services = DB::table('companies')
                ->join('users', 'companies.id', '=', 'users.id_company')
                ->join('services', 'users.id', '=', 'services.id_user')
                ->select('services.*')
                ->where('companies.id', 'like', $user->id_company)
                ->where('services.name', 'like', $sale_name)
                ->where('services.description', 'like', $sale_description);
                if($services->exists()){
                    //service exists
                    $services = $services->first();
                    if(
                        DB::table('sales')
                        ->updateOrInsert(
                            ['id_product_service' => $services->id, 'type' => $sale_type, 'iva' => $iva,
                            'type_client' => $type_client, 'id_client' => $client->id, 'id_user' => $user->id,],
                            ['quantity' => $quantity, 'discount' => $discount]
                        )
                    ){
                        return redirect()->route('view_sale')->with('sale_notification', 'Sucesso Service.');
                    }
                }
                return redirect()->route('view_sale')->with('sale_notification', 'Esse servico nao existe.');
            }
            return redirect()->route('view_sale')->with('sale_notification', 'Ocorreu um erro durante o processo.');
        }
        return route('root');
    }

    public function check(Request $request)
    {
        //
        if(Auth::check()){
            $user = Auth::user();
            $client = explode(' === ', $request['client'], 2);
            $client_name = $client[0];
            $client_email = $client[1];
            $sale_type = $request['sale_type'];
            $sale_name_description = explode(' === ', $request['name'], 2);
            $sale_name = $sale_name_description[0];
            $sale_description = $sale_name_description[1];
            $quantity = $request['quantity'];
            $client = DB::table('clients_enterprise')
                ->select('*')
                ->where('email', 'like', $client_email)
                ->where('name', 'like', $client_name);
            if($client->exists()){
                //client_enterprise exists

            }else{
                $client = DB::table('clients_singular')
                    ->select('*')
                    ->where('email', 'like', $client_email);
                if($client->exists()){
                    //client_singular exists

                }else{
                    return redirect()->route('view_sale')->with('sale_notification', 'Esse cliente nao existe.');
                }
            }



            if($sale_type === 'PRODUCT'){
                $products = DB::table('companies')
                ->join('users', 'companies.id', '=', 'users.id_company')
                ->join('products', 'users.id', '=', 'products.id_user')
                ->select('products.*')
                ->where('companies.id', 'like', $user->id_company)
                ->where('products.name', 'like', $sale_name)
                ->where('products.description', 'like', $sale_description);
                if($products->exists()){
                    //product exists
                    return redirect()->route('view_sale')->with('sale_notification', 'Prosseguir venda do produto.');
                }
                return redirect()->route('view_sale')->with('sale_notification', 'Esse produto nao existe.');
            }
            if($sale_type === 'SERVICE'){
                $products = DB::table('companies')
                ->join('users', 'companies.id', '=', 'users.id_company')
                ->join('services', 'users.id', '=', 'services.id_user')
                ->select('products.*')
                ->where('companies.id', 'like', $user->id_company)
                ->where('services.name', 'like', $sale_name)
                ->where('services.description', 'like', $sale_description);
                if($products->exists()){
                    //service exists
                    return redirect()->route('view_sale')->with('sale_notification', 'Prosseguir venda do servico.');
                }
                return redirect()->route('view_sale')->with('sale_notification', 'Esse servico nao existe.');
            }
            return redirect()->route('view_sale')->with('sale_notification', 'Ocorreu um erro durante o processo.');
        }
        return route('root');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }

    public function edit_sale_item(Request $request)
    {
        if(Auth::check()){
            $id = $request['id'];
            $quantity = $request->quantity;
            $discount = $request->discount / 100;
            if(
                DB::table('sales')
                ->where('id', 'like', $id)
                ->update(array(
                    'quantity' => $quantity,
                    'discount' => $discount
                ))
            ){
                return redirect()->route('view_sale')->with('sale_notification', 'Item actualizado com sucesso.');
            }
            return redirect()->route('view_sale')->with('sale_notification', 'Falhou! Ocorreu um erro durante a actualizacao do item.');
        }
        return route('root');
    }

    public function remove_sale_item(Request $request)
    {
        if(Auth::check()){
            $id = $request['id'];
            if(DB::table('sales')->where('id', 'like', $id)->delete()){
                return redirect()->route('view_sale')->with('sale_notification', 'Item removido sucesso.');
            }
            return redirect()->route('view_sale')->with('sale_notification', 'Falhou! Ocorreu um erro durante a remocao do item.');
        }
        return route('root');
    }

    public function quote(Request $request){
        $user = Auth::user();
        $sale = DB::table('sales')->where('id_user', 'like', $user->id);
        $company = DB::table('companies')->find($user->id_company);
        if($sale->exists()){
            $sale = $sale->first();
            $client_id = $sale->id_client;
            //$product_service_id = $sale->id_product_service;
            $client_name = '';
            $client_email = '';
            $client_type = $sale->type_client;
            $client_address = '';
            $client_nuit ='';
            //$product_service_name='';
            //$description='';
            //$price = 0;
            $status = 'NOT PAID';
            $user_id = $user->id;
            $user_name = '';
            if($user->email !== $company->email){
                $user_name = $user->name . ' ' . $user->surname;
            }else{
                $user_name = 'Admin';
            }

            if($sale->type_client === 'SINGULAR'){
                $client = DB::table('clients_singular')->find($client_id);
                $client_name = $client->name . ' ' . $client->surname;
                $client_nuit = $client->nuit;
                $client_address = $client->address;
                $client_email = $client->email;
            }

            if($sale->type_client === 'ENTERPRISE'){
                $client = DB::table('clients_enterprise')->find($client_id);
                $client_name = $client->name;
                $client_nuit = $client->nuit;
                $client_address = $client->address;
                $client_email = $client->email;
            }
            //$company_logo = url('storage/' . $company->logo);
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
                'status' => $status
            ], 'QUOTE');
        }
        return redirect()->route('view_sale');
    }

    public function clean_sale(Request $request){
        if(Auth::check()){
            $user = Auth::user();
            if(DB::table('sales')->where('id_user', 'like', $user->id)->delete()){

            }
            return redirect()->route('view_sale')->with('sale_notification', 'Falhou! Ocorreu um erro durante a operacao.');
        }
        return route('root');
    }

    public function sell(Request $request){
        $user = Auth::user();
        $sale = DB::table('sales')->where('id_user', 'like', $user->id);
        $company = DB::table('companies')->find($user->id_company);
        $requisites = true;
        if($sale->exists()){
            $sale = $sale->first();
            $client_id = $sale->id_client;
            //$product_service_id = $sale->id_product_service;
            $client_name = '';
            $client_email = '';
            $client_type = $sale->type_client;
            $client_address = '';
            $client_nuit ='';
            //$product_service_name='';
            //$description='';
            //$price = 0;
            $status = 'NOT PAID';
            $user_id = $user->id;
            $user_name = '';
            if($user->email !== $company->email){
                $user_name = $user->name . ' ' . $user->surname;
            }else{
                $user_name = 'Admin';
            }

            if($sale->type_client === 'SINGULAR'){
                $client = DB::table('clients_singular')->find($client_id);
                $client_name = $client->name . ' ' . $client->surname;
                $client_nuit = $client->nuit;
                $client_address = $client->address;
                $client_email = $client->email;
            }else{
                if($sale->type_client === 'ENTERPRISE'){
                    $client = DB::table('clients_enterprise')->find($client_id);
                    $client_name = $client->name;
                    $client_nuit = $client->nuit;
                    $client_address = $client->address;
                    $client_email = $client->email;
                }else{
                    $requisites = false;
                }
            }
            //$company_logo = url('storage/' . $company->logo);
            if($requisites){
                $this->invoice_generator([
                    'user_id' => $user->id,
                    'client_type' => $client_type,
                    'client_id' => $client_id,
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
                    'status' => $status
                ], 'INVOICE');
            }
        }
        return redirect()->route('view_sale');
    }

    private function invoice_generator(Array $data, $type){
        $company_name = $data['company_name'];
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($data['company_name'] . '/' . $data['user_name']);
        if($type === 'QUOTE'){
            $pdf->SetTitle("Cotação");
            $pdf->SetSubject("Cotação");
            $pdf->SetKeywords("Thimiriza, $company_name, Cotação");
            $pdf->setType($type);
            $info_1 = '<hr/><html>'
                . '<head></head>'
                . '<body>'
                . '<table>'
                . '<tr><td><table>'
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
            $sales = DB::table('sales')->select('*')
            ->where('id_user', 'like', $data['user_id'])->get();
            $price_total = 0;
            $iva_total = 0;
            $price_inc_total = 0;
            $discount_total = 0;
            foreach($sales as $sale){
                $price_sale = 0;
                $iva = 0;
                $price_inc = 0;
                if($sale->type === 'PRODUCT'){
                    $product = DB::table('products')->find($sale->id_product_service);
                    $name = $product->name;
                    $description = $product->description;
                    $quantity = $sale->quantity;
                    $price = $product->price;
                    $price_inc = $product->price * $sale->quantity;
                    $iva = $price_inc * $sale->iva;
                    $discount = $price_inc * $sale->discount;
                    $price_sale = $price_inc - $discount + $iva;
                    if($product->quantity > 0){
                        if($product->quantity > $quantity){
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
                    }
                }
                if($sale->type === 'SERVICE'){
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
            $pdf->SetFont('times', 'B', 10);
            $pdf->lastPage();
            $pdf->setData($price_total, $price_inc_total, $discount_total, $iva_total, $data['company_bank_account_name'], $data['company_bank_account_owner'], $data['company_bank_account_number'], $data['company_bank_account_nib']);
            $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
            $pdf_output= $pdf;
            $file = $pdf->Output('Cotação.pdf', 'S');
            $mail_controller = new SystemMailController;
            $mail_controller->quote_email(
                $data['client_email'],
                $data['client_name'],
                $file,
                'QUOTE_CODE',
                $price_total,
                $data['company_name']
            );
            ob_end_clean();
            $pdf_output->Output('Cotação.pdf', 'I');
        }
        //INVOICE
        if($type === 'INVOICE'){
            $invoice_code = $this->invoice_code();
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
            $sales = DB::table('sales')->select('*')
            ->where('id_user', 'like', $data['user_id'])->get();
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
                if($sale->type === 'PRODUCT'){
                    $product = DB::table('products')->find($sale->id_product_service);
                    $name = $product->name;
                    $description = $product->description;
                    $quantity = $sale->quantity;
                    $price = $product->price;
                    $price_inc = $product->price * $sale->quantity;
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
                if($sale->type === 'SERVICE'){
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
            $invoice_date = now();
            if(DB::table('invoices')
                ->insert([
                    [
                        'code' => $invoice_code,
                        'client_type' => $data['client_type'],
                        'id_client' => $data['client_id'],
                        'price' => $price_total,
                        'status' => $data['status'],
                        'id_user' => $data['user_id'],
                        'created_at' => $invoice_date
                    ]
                ])
            ){
                $invoice = DB::table('invoices')->where('id_user', 'like', $data['user_id'])
                ->where('created_at', 'like', $invoice_date)->where('code', 'like', $invoice_code)->first();
                foreach($sales as $sale){
                    if($sale->type === 'PRODUCT'){
                        $product = DB::table('products')->find($sale->id_product_service);
                        $name = $product->name;
                        $description = $product->description;
                        $quantity = $sale->quantity;
                        $price = $product->price;
                        $price_inc = $product->price * $sale->quantity;
                        $iva = $price_inc * $sale->iva;
                        $discount = $price_inc * $sale->discount;
                        $price_sale = $price_inc - $discount + $iva;
                        $new_product_quantity = $product->quantity - $quantity;
                        if($product->quantity > 0){
                            if($product->quantity >= $quantity){
                                if(DB::table('moves')
                                ->insert([[
                                        'sale_type' => $sale->type,
                                        'id_product_service' => $sale->id_product_service,
                                        'product_service' => $name,
                                        'description' => $description,
                                        'price' => $price_sale,
                                        'quantity' => $quantity,
                                        'discount' => $discount,
                                        'iva' => $iva,
                                        'id_invoice' => $invoice->id,
                                        'created_at' => now()
                                    ]])){
                                        DB::table('products')
                                        ->where('id', $product->id)
                                        ->update(['quantity'=> $new_product_quantity]);
                                }else{
                                    //remove all recorded products on moves
                                }
                            }
                        }
                    }
                    if($sale->type === 'SERVICE'){
                        $service = DB::table('services')->find($sale->id_product_service);
                        $name = $service->name;
                        $description = $service->description;
                        $quantity = $sale->quantity;
                        $price = $service->price;
                        $price_inc = $service->price * $sale->quantity;
                        $iva = $price_inc * $sale->iva;
                        $discount = $price_inc * $sale->discount;
                        $price_sale = $price_inc - $discount + $iva;
                        if(DB::table('moves')
                        ->insert([[
                                'sale_type' => $sale->type,
                                'id_product_service' => $sale->id_product_service,
                                'product_service' => $name,
                                'description' => $description,
                                'price' => $price_sale,
                                'quantity' => $quantity,
                                'discount' => $discount,
                                'iva' => $iva,
                                'id_invoice' => $invoice->id,
                                'created_at' => now()
                            ]])){

                        }else{
                            //remove all recorded services on moves
                        }
                    }
                }
            }
            $details_3 = "<hr/>"
            . "<table>"
            . "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td>Total:</td><td>$price_total MT</td></tr>"
            . "</table>";
            $pdf->SetFont('times', 'B', 10);
            $pdf->setData($price_total, $price_inc_total, $discount_total, $iva_total, $data['company_bank_account_name'], $data['company_bank_account_owner'], $data['company_bank_account_number'], $data['company_bank_account_nib']);
            $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
            $pdf_output= $pdf;
            $file = $pdf->Output('Factura.pdf', 'S');
            $mail_controller = new SystemMailController;
            $mail_controller->invoice_email(
                $data['client_email'],
                $data['client_name'],
                $file,
                substr($invoice_code, 10 ,11),
                $price_total,
                $data['company_name']
            );
            ob_end_clean();
            $pdf_output->Output('Factura.pdf', 'I');
        }
    }
    //generate invoice_code
    private function invoice_code()
    {
        $user = Auth::user();
        $invoices = DB::table('invoices')
        ->join('users', 'invoices.id_user', '=', 'users.id')
        ->join('companies', 'users.id_company', '=', 'companies.id')
        ->select('invoices.code')
        ->where('companies.id', 'like', $user->id_company)
        ->orderByRaw('invoices.created_at DESC');
        $company = DB::table('users')
        ->join('companies', 'users.id_company', '=', 'companies.id')
        ->select('companies.code')
        ->where('users.id', 'like', $user->id)->first();
        if ($invoices->count() == 0) {
            return $company->code . 'F' . date('y') . date('m') . '000001';
        }
        return $this->next_code($invoices->first()->code);
    }

    private function next_code($last)
    {
        $last++;
        return $last;
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
