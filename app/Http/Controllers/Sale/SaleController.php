<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PDF\PDFController;
use App\Http\Controllers\SystemMail\SystemMailController;
use App\Models\Client_Enterprise;
use App\Models\Client_Singular;
use App\Models\Company;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

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
     * Show the form for filter paid sales.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_credit(Request $request)
    {
        $user = Auth::user();
        $company_controller = new CompanyController;
        $company_validate = $company_controller->validate_company($user->id_company);
        $invoices = [];
        if(!$request->filled('inicial_date') && !$request->filled('final_date')){
            $invoices_query = DB::table('companies')
            ->join('invoices', 'companies.id', '=', 'invoices.id_company')
            ->select('invoices.*')
            ->where('companies.id', 'like', $user->id_company)
            ->where('invoices.status', 'like', 'PAID')
            ->orderByDesc('invoices.created_at')->get();
            $i=0;
            foreach($invoices_query as $invoice_query){
                $invoice = new stdClass;
                $invoice->id = $invoice_query->id;
                $invoice->created_at = $invoice_query->created_at;
                $invoice->price = $invoice_query->price;
                if($invoice_query->client_type === 'SINGULAR'){
                    $client = DB::table('clients_singular')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name . ' ' . $client->surname;
                }
                if($invoice_query->client_type === 'ENTERPRISE'){
                    $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name;
                }
                $invoices[$i] = $invoice;
                $i++;
            }
            return view ('pt.home.pages.credit.credit', $user, ['invoices' => $invoices,
                'logo' => $company_validate['company_logo']]);
        }
        if($request->filled('inicial_date')){
            $invoices_query = DB::table('companies')
            ->join('invoices', 'companies.id', '=', 'invoices.id_company')
            ->select('invoices.*')
            ->where('companies.id', 'like', $user->id_company)
            ->whereBetween('invoices.created_at', [$request['inicial_date'], now()])
            ->where('invoices.status', 'like', 'PAID')
            ->orderByDesc('invoices.created_at')->get();
            $i=0;
            foreach($invoices_query as $invoice_query){
                $invoice = new stdClass;
                $invoice->id = $invoice_query->id;
                $invoice->created_at = $invoice_query->created_at;
                $invoice->price = $invoice_query->price;
                if($invoice_query->client_type === 'SINGULAR'){
                    $client = DB::table('clients_singular')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name . ' ' . $client->surname;
                }
                if($invoice_query->client_type === 'ENTERPRISE'){
                    $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name;
                }
                $invoices[$i] = $invoice;
                $i++;
            }
            return view ('home.pages.credit.credit', $this->user, ['invoices' => $invoices,
                'logo' => $this->company_logo]);
        }
        if($request->filled('final_date')){
            $invoices_query = DB::table('companies')
            ->join('invoices', 'companies.id', '=', 'invoices.id_company')
            ->select('invoices.*')
            ->where('companies.id', 'like', $user->id_company)
            ->where('invoices.created_at', '<=', $request['final_date'])
            ->where('invoices.status', 'like', 'PAID')
            ->orderByDesc('invoices.created_at')->get();
            $i=0;
            foreach($invoices_query as $invoice_query){
                $invoice = new stdClass;
                $invoice->id = $invoice_query->id;
                $invoice->created_at = $invoice_query->created_at;
                $invoice->price = $invoice_query->price;
                if($invoice_query->client_type === 'SINGULAR'){
                    $client = DB::table('clients_singular')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name . ' ' . $client->surname;
                }
                if($invoice_query->client_type === 'ENTERPRISE'){
                    $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name;
                }
                $invoices[$i] = $invoice;
                $i++;
            }
            return view ('pt.home.pages.credit.credit', $user, ['invoices' => $invoices,
                'logo' => $company_validate['company_logo']]);
        }
    }

    /**
     * Show the form for filter non paid sales.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_debit(Request $request)
    {
        $user = Auth::user();
        $company_controller = new CompanyController;
        $company_validate = $company_controller->validate_company($user->id_company);
        $invoices = [];
        if(!$request->filled('inicial_date') && !$request->filled('final_date')){
            $invoices_query = DB::table('companies')
            ->join('invoices', 'companies.id', '=', 'invoices.id_company')
            ->select('invoices.*')
            ->where('companies.id', 'like', $user->id_company)
            ->where('invoices.status', 'like', 'NOT PAID')
            ->orderByDesc('invoices.created_at')->get();
            $i=0;
            foreach($invoices_query as $invoice_query){
                $invoice = new stdClass;
                $invoice->id = $invoice_query->id;
                $invoice->created_at = $invoice_query->created_at;
                $invoice->price = $invoice_query->price;
                if($invoice_query->client_type === 'SINGULAR'){
                    $client = DB::table('clients_singular')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name . ' ' . $client->surname;
                }
                if($invoice_query->client_type === 'ENTERPRISE'){
                    $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name;
                }
                $invoices[$i] = $invoice;
                $i++;
            }
            return view ('pt.home.pages.debit.debit', $user, ['invoices' => $invoices,
                'logo' => $company_validate['company_logo']]);
        }
        if($request->filled('inicial_date')){
            $invoices_query = DB::table('companies')
            ->join('invoices', 'companies.id', '=', 'invoices.id_company')
            ->select('invoices.*')
            ->where('companies.id', 'like', $user->id_company)
            ->whereBetween('invoices.created_at', [$request['inicial_date'], now()])
            ->where('invoices.status', 'like', 'NOT PAID')
            ->orderByDesc('invoices.created_at')->get();
            $i=0;
            foreach($invoices_query as $invoice_query){
                $invoice = new stdClass;
                $invoice->id = $invoice_query->id;
                $invoice->created_at = $invoice_query->created_at;
                $invoice->price = $invoice_query->price;
                if($invoice_query->client_type === 'SINGULAR'){
                    $client = DB::table('clients_singular')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name . ' ' . $client->surname;
                }
                if($invoice_query->client_type === 'ENTERPRISE'){
                    $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name;
                }
                $invoices[$i] = $invoice;
                $i++;
            }
            return view ('home.pages.debit.debit', $user, ['invoices' => $invoices,
                'logo' => $company_validate['company_logo']]);
        }
        if($request->filled('final_date')){
            $invoices_query = DB::table('companies')
            ->join('invoices', 'companies.id', '=', 'invoices.id_company')
            ->select('invoices.*')
            ->where('companies.id', 'like', $user->id_company)
            ->where('invoices.created_at', '<=', $request['final_date'])
            ->where('invoices.status', 'like', 'NOT PAID')
            ->orderByDesc('invoices.created_at')->get();
            $i=0;
            foreach($invoices_query as $invoice_query){
                $invoice = new stdClass;
                $invoice->id = $invoice_query->id;
                $invoice->created_at = $invoice_query->created_at;
                $invoice->price = $invoice_query->price;
                if($invoice_query->client_type === 'SINGULAR'){
                    $client = DB::table('clients_singular')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name . ' ' . $client->surname;
                }
                if($invoice_query->client_type === 'ENTERPRISE'){
                    $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name;
                }
                $invoices[$i] = $invoice;
                $i++;
            }
            return view ('pt.home.pages.debit.debit', $user, ['invoices' => $invoices,
                'logo' => $company_validate['company_logo']]);
        }
    }

    /**
     * Show the report of sales.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_report(Request $request)
    {
        $user = Auth::user();
        $company_controller = new CompanyController;
        $company_validate = $company_controller->validate_company($user->id_company);
        $invoices = [];
        if(!$request->filled('inicial_date') && !$request->filled('final_date')){
            $invoices_query = DB::table('companies')
            ->join('invoices', 'companies.id', '=', 'invoices.id_company')
            ->select('invoices.*')
            ->where('companies.id', 'like', $user->id_company)
            ->orderByDesc('invoices.created_at')->get();
            $i=0;
            foreach($invoices_query as $invoice_query){
                $invoice = new stdClass;
                $invoice->id = $invoice_query->id;
                $invoice->created_at = $invoice_query->created_at;
                $invoice->price = $invoice_query->price;
                $invoice->code = substr($invoice_query->code, 10, 11);
                if($invoice_query->client_type === 'SINGULAR'){
                    $client = DB::table('clients_singular')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name . ' ' . $client->surname;
                }
                if($invoice_query->client_type === 'ENTERPRISE'){
                    $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name;
                }
                $invoices[$i] = $invoice;
                $i++;
            }
            return view ('pt.home.pages.report.report', $user, ['invoices' => $invoices,
                'logo' => $company_validate['company_logo']]);
        }
        if($request->filled('inicial_date')){
            $invoices_query = DB::table('companies')
            ->join('invoices', 'companies.id', '=', 'invoices.id_company')
            ->select('invoices.*')
            ->where('companies.id', 'like', $user->id_company)
            ->whereBetween('invoices.created_at', [$request['inicial_date'], now()])
            ->orderByDesc('invoices.created_at')->get();
            $i=0;
            foreach($invoices_query as $invoice_query){
                $invoice = new stdClass;
                $invoice->id = $invoice_query->id;
                $invoice->created_at = $invoice_query->created_at;
                $invoice->price = $invoice_query->price;
                $invoice->code = substr($invoice_query->code, 10, 11);
                if($invoice_query->client_type === 'SINGULAR'){
                    $client = DB::table('clients_singular')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name . ' ' . $client->surname;
                }
                if($invoice_query->client_type === 'ENTERPRISE'){
                    $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name;
                }
                $invoices[$i] = $invoice;
                $i++;
            }
            return view ('home.pages.report.report', $user, ['invoices' => $invoices,
                'logo' => $company_validate['company_logo']]);
        }
        if($request->filled('final_date')){
            $invoices_query = DB::table('companies')
            ->join('invoices', 'companies.id', '=', 'invoices.id_company')
            ->select('invoices.*')
            ->where('companies.id', 'like', $user->id_company)
            ->where('invoices.created_at', '<=', $request['final_date'])
            ->orderByDesc('invoices.created_at')->get();
            $i=0;
            foreach($invoices_query as $invoice_query){
                $invoice = new stdClass;
                $invoice->id = $invoice_query->id;
                $invoice->created_at = $invoice_query->created_at;
                $invoice->price = $invoice_query->price;
                $invoice->code = substr($invoice_query->code, 10, 11);
                if($invoice_query->client_type === 'SINGULAR'){
                    $client = DB::table('clients_singular')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name . ' ' . $client->surname;
                }
                if($invoice_query->client_type === 'ENTERPRISE'){
                    $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name;
                }
                $invoices[$i] = $invoice;
                $i++;
            }
            return view ('pt.home.pages.report.report', $user, ['invoices' => $invoices,
                'logo' => $company_validate['company_logo']]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::check()){
            $user = Auth::user();
            $company_controller = new CompanyController;
            $company_validate = $company_controller->validate_company($user->id_company);
            $isAdmin = true;
            if($user->privilege !== 'ADMIN'){
                $isAdmin = false;
            }
            $services = Service::where('id_company', $user->id_company)->get();
            $products = Product::where('id_company', $user->id_company)->get();
            $clients_enterprise = Client_Enterprise::where('id_company', $user->id_company)->get();
            $clients_singular = Client_Singular::where('id_company', $user->id_company)->get();
            $sales_query = Sale::where('created_by', $user->id)->get();
            $sales = [];
            $i = 0;
            $hasSales = false;
            $actual_client = '';
            foreach ($sales_query as $sale_query){
                $sale = new stdClass;
                if($sale_query->type === 'PRODUCT'){
                    $product = Product::find($sale_query->id_product_service);
                    $sale->id = $sale_query->id;
                    $sale->sale_type = 'PRODUCT';
                    $sale->name = $product->name;
                    $sale->description = $product->description;
                    $sale->price_unit = $product->price;
                    if($sale_query->type_client === 'ENTERPRISE'){
                        $sale->client_type = 'ENTERPRISE';
                        $sale->id_client = $sale_query->id_client;
                        $client = Client_Enterprise::find($sale_query->id_client);
                        $actual_client = $client->name . ' === ' . $client->email;
                    }
                    if($sale_query->type_client === 'SINGULAR'){
                        $sale->client_type = 'SINGULAR';
                        $sale->id_client = $sale_query->id_client;
                        $client = Client_Singular::find($sale_query->id_client);
                        $actual_client = $client->name . ' ' . $client->surname . ' === ' . $client->email;
                    }
                    $sale->discount = $sale_query->discount;
                    $sale->quantity = $sale_query->quantity;
                    $price = $product->price * $sale_query->quantity;
                    $sale->discount_price = $sale_query->discount * $price;
                    $sale->iva = $sale_query->iva * $price;
                    $sale->price = $price + $sale->iva - $sale->discount_price;
                }
                if($sale_query->type === 'SERVICE'){
                    $service = Service::find($sale_query->id_product_service);
                    $sale->id = $sale_query->id;
                    $sale->sale_type = 'PRODUCT';
                    $sale->name = $service->name;
                    $sale->description = $service->description;
                    $sale->price_unit = $service->price;
                    if($sale_query->type_client === 'ENTERPRISE'){
                        $sale->client_type = 'ENTERPRISE';
                        $sale->id_client = $sale_query->id_client;
                        $client = Client_Enterprise::find($sale_query->id_client);
                        $actual_client = $client->name . ' === ' . $client->email;
                    }
                    if($sale_query->type_client === 'SINGULAR'){
                        $sale->client_type = 'SINGULAR';
                        $sale->id_client = $sale_query->id_client;
                        $client = Client_Singular::find($sale_query->id_client);
                        $actual_client = $client->name . ' ' . $client->surname . ' === ' . $client->email;
                    }
                    $sale->discount = $sale_query->discount;
                    $sale->quantity = $sale_query->quantity;
                    $price = $service->price * $sale_query->quantity;
                    $sale->discount_price = $sale_query->discount * $price;
                    $sale->iva = $sale_query->iva * $price;
                    $sale->price = $price + $sale->iva - $sale->discount_price;
                }
                $sales[$i] = $sale;
                $i++;
            }
            if($i > 0){
                $hasSales = true;
            }else{
                $hasSales = false;
            }
            return view ('pt.home.pages.sale.sale', $user,
            [
                'company_type' => $company_validate['company_type'],
                'logo' => $company_validate['company_logo'],
                'deadline_payment' =>  $company_validate['expire_msg'],
                'sales' => $sales,
                'services' => $services,
                'products' => $products,
                'clients_enterprise' => $clients_enterprise,
                'clients_singular' => $clients_singular,
                'hasSales' => $hasSales,
                'actual_client' => $actual_client,
                'isAdmin' => $isAdmin,
                'enable_sales' => $company_validate['make_sales']
            ]);
        }
        return redirect()->route('root');
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
            $company = Company::find($user->id_company);
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
                $iva = 0.17; //17%
            }
            if($company->type === 'ISPC'){
                $iva = 0; //0%
            }

            $client = DB::table('clients_enterprise')
                ->select('*')
                ->where('email', 'like', $client_email)
                ->where('name', 'like', $client_name)->get();
            if(!$client->count() > 0){
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
                $products = Product::where('id_company', 'like', $user->id_company)
                ->where('products.name', 'like', $sale_name)
                ->where('products.description', 'like', $sale_description)->get();
                if($products->count() > 0){
                    //product exists
                    $product = $products->first();
                    if($product->quantity < $quantity){
                        return redirect()->route('view_sale')->with('sale_notification',
                        'A quantidade requisitada excede o stock. Actualmente o stock possui .' . $product->quantity);
                    }else{
                        $update_status = false;
                        if($product->iva === 'off'){
                            $iva = 0;
                        }
                        if(
                            DB::table('sales')
                            ->updateOrInsert(
                                ['id_product_service' => $product->id, 'type' => $sale_type, 'iva' => $iva, 'created_by' => $user->id,
                                'type_client' => $type_client, 'id_client' => $client->id, 'id_company' => $user->id_company],
                                ['discount' => $discount, 'quantity' => $quantity]
                            )
                        ){$update_status = true;}
                        if($update_status){
                            return redirect()->route('view_sale')->with('sale_notification', 'Produto adicionado com sucesso.');
                        }
                    }
                }
                //product does not exist
                return redirect()->route('view_sale')->with('sale_notification', 'Esse produto nao existe.');
            }
            if($sale_type === 'SERVICE'){
                $services = Service::where('id_company', 'like', $user->id_company)
                ->where('services.name', 'like', $sale_name)
                ->where('services.description', 'like', $sale_description)->get();
                if($services->count() > 0){
                    //service exists
                    $services = $services->first();
                    if(
                        DB::table('sales')
                        ->updateOrInsert(
                            ['id_product_service' => $services->id, 'type' => $sale_type, 'iva' => $iva, 'created_by' => $user->id,
                            'type_client' => $type_client, 'id_client' => $client->id, 'id_company' => $user->id_company,],
                            ['quantity' => $quantity, 'discount' => $discount]
                        )
                    ){
                        return redirect()->route('view_sale')->with('sale_notification', 'Sucesso Service.');
                    }
                }
                //service does no exist
                return redirect()->route('view_sale')->with('sale_notification', 'Esse servico nao existe.');
            }
            return redirect()->route('view_sale')->with('sale_notification', 'Ocorreu um erro durante o processo.');
        }
        return redirect()->route('root');
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
            $client = Client_Enterprise::where('email', 'like', $client_email)
                ->where('name', 'like', $client_name)->get();
            if($client->count() > 0){
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
                $products = Product::where('id_company', 'like', $user->id_company)
                ->where('name', 'like', $sale_name)
                ->where('description', 'like', $sale_description)->get();
                if($products->count() > 0){
                    //product exists
                    return redirect()->route('view_sale')->with('sale_notification', 'Prosseguir venda do produto.');
                }
                return redirect()->route('view_sale')->with('sale_notification', 'Esse produto nao existe.');
            }
            if($sale_type === 'SERVICE'){
                $services = Service::where('id_company', 'like', $user->id_company)
                ->where('name', 'like', $sale_name)
                ->where('description', 'like', $sale_description);
                if($services->count() > 0){
                    //service exists
                    return redirect()->route('view_sale')->with('sale_notification', 'Prosseguir venda do servico.');
                }
                return redirect()->route('view_sale')->with('sale_notification', 'Esse servico nao existe.');
            }
            return redirect()->route('view_sale')->with('sale_notification', 'Ocorreu um erro durante o processo.');
        }
        return redirect()->route('root');
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
        return redirect()->route('root');
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
        return redirect()->route('root');
    }

    public function quote(Request $request){
        $pdf_controller = new PDFController;
        $user = Auth::user();
        $sale = Sale::where('created_by', 'like', $user->id)->get();
        $company = Company::find($user->id_company);
        if($sale->count() > 0){
            $sale = $sale->first();
            $client_id = $sale->id_client;
            $client_name = '';
            $client_email = '';
            $client_type = $sale->type_client;
            $client_address = '';
            $client_nuit ='';
            $status = 'NOT PAID';
            $user_name = '';
            if($user->email !== $company->email){
                $user_name = $user->name . ' ' . $user->surname;
            }else{
                $user_name = 'Admin';
            }

            if($sale->type_client === 'SINGULAR'){
                $client = Client_Singular::find($client_id);
                $client_name = $client->name . ' ' . $client->surname;
                $client_nuit = $client->nuit;
                $client_address = $client->address;
                $client_email = $client->email;
            }

            if($sale->type_client === 'ENTERPRISE'){
                $client = Client_Enterprise::find($client_id);
                $client_name = $client->name;
                $client_nuit = $client->nuit;
                $client_address = $client->address;
                $client_email = $client->email;
            }
            $data = [
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
                'type' => 'QUOTE'
            ];

            return $pdf_controller->invoice_generator($data,);
        }
        return redirect()->route('view_sale');
    }

    public function clean_sale(Request $request){
        if(Auth::check()){
            $user = Auth::user();
            if(DB::table('sales')->select('*')->where('created_by', $user->id)->count() === 0){
                return redirect()->route('view_sale');
            }
            if(DB::table('sales')->where('created_by', 'like', $user->id)->delete()){
                return redirect()->route('view_sale');
            }
            return redirect()->route('view_sale')->with('sale_notification', 'Falhou! Ocorreu um erro durante a operacao.');
        }
        return route('root');
    }

    public function sell(Request $request){
        $pdf_controller = new PDFController;
        $user = Auth::user();
        $sale = Sale::where('created_by', 'like', $user->id)->get();
        $company = Company::find($user->id_company);
        $requisites = true;
        if($sale->count() > 0){
            $sale = $sale->first();
            $client_id = $sale->id_client;
            $client_name = '';
            $client_email = '';
            $client_type = $sale->type_client;
            $client_address = '';
            $client_nuit ='';
            $status = 'NOT PAID';
            $user_name = '';
            if($user->email !== $company->email){
                $user_name = $user->name . ' ' . $user->surname;
            }else{
                $user_name = 'Admin';
            }

            if($sale->type_client === 'SINGULAR'){
                $client = Client_Singular::find($client_id);
                $client_name = $client->name . ' ' . $client->surname;
                $client_nuit = $client->nuit;
                $client_address = $client->address;
                $client_email = $client->email;
            }else{
                if($sale->type_client === 'ENTERPRISE'){
                    $client = Client_Enterprise::find($client_id);
                    $client_name = $client->name;
                    $client_nuit = $client->nuit;
                    $client_address = $client->address;
                    $client_email = $client->email;
                }else{
                    $requisites = false;
                }
            }
            if($requisites){
                return $pdf_controller->invoice_generator([
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
                    'status' => $status,
                    'type' => 'INVOICE'
                    ]);
            }
        }
        return redirect()->route('view_sale');
    }

}
