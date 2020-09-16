<?php

namespace App\Http\Controllers;

use App\Models\Client_Enterprise;
use App\Models\Client_Singular;
use App\Models\Company;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Service;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use stdClass;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $expire;
    private $expire_msg;
    private $company_logo;
    private $expire_days_left;
    private $enable_sales;
    private $user;
    private $company;
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function company_validate(Object $user){
        $this->enable_sales = true;
        $user = Auth::user();
        $company = Company::where('id', 'like', $user->id_company)->first();
        $company_logo = url('storage/' . $company->logo);
        //$expire = date('Y/m/d', strtotime('+30 days', strtotime($company->created_at))) . PHP_EOL . '23:59:59';
        $this->expire = date('Y/m/d', strtotime('+1 month', strtotime($company->payment_date))) . PHP_EOL;
        $this->expire_days_left = intval((strtotime($this->expire) - strtotime(now()))/86400)+1;
        $expire_msg = 'Validade: ';
        if($company->id_package === 2){
            $this->expire_msg = 'Possui apenas ' . $this->expire_days_left . ' dias de uso gratuito.';
        }
        if($company->id_package !== 2){
            $this->expire_msg = 'Validade: ' . $this->expire;
        }
        $this->company_validate($user);
        if($company->logo === ''){
            $company_logo = '';
            $this->enable_sales = false;
        }
        if($this->expire_days_left <= 0){
            DB::table('companies')
                    ->where('id', $company->id)
                    ->update(array(
                        'payment_date' => now(),
                        'id_package' => 0
                    ));
            $this->enable_sales = false;
        }
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $company = Company::where('id', 'like', $user->id_company)->first();
        if($company->email === $user->email){
            return redirect()->route('view_user');
        }
        return redirect()->route('view_sale');
    }

    public function view_home()
    {
        $user = Auth::user();
        if($user->privilege == "TOTAL"){
            $users = User::paginate(30);
            return view ('home.pages.home', $user, ['users' => $users]);
        }
        $this->view_product();
    }


    public function view_sale()
    {
        $user = Auth::user();
        $company = Company::where('id', 'like', $user->id_company)->first();
        
        $isAdmin = true;
        if($company->email !== $user->email){
            $isAdmin = false;
        }
        $services = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('services', 'users.id', '=', 'services.id_user')
        ->select('services.*')
        ->where('companies.id', 'like', $company->id)->get();
        $products = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('products', 'users.id', '=', 'products.id_user')
        ->select('products.*')
        ->where('companies.id', 'like', $company->id)->get();
        $clients_enterprise = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('clients_enterprise', 'users.id', '=', 'clients_enterprise.id_user')
        ->select('clients_enterprise.*')
        ->where('companies.id', 'like', $company->id)->get();
        $clients_singular = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('clients_singular', 'users.id', '=', 'clients_singular.id_user')
        ->select('clients_singular.*')
        ->where('companies.id', 'like', $company->id)->get();
        $sales_query = DB::table('sales')->select('id', 'type', 'id_product_service', 'type_client', 'id_client', 'quantity', 'iva', 'discount')
        ->where('id_user', 'like', $user->id)->get();
        $sales = [];
        $i = 0;
        $hasSales = false;
        $actual_client = '';
        foreach ($sales_query as $sale_query){
            $sale = new stdClass;
            if($sale_query->type === 'PRODUCT'){
                $product = DB::table('products')->select('products.name', 'products.description', 'products.price')
                ->find($sale_query->id_product_service);
                $sale->id = $sale_query->id;
                $sale->sale_type = 'PRODUCT';
                $sale->name = $product->name;
                $sale->description = $product->description;
                $sale->price_unit = $product->price;
                if($sale_query->type_client === 'ENTERPRISE'){
                    $sale->client_type = 'ENTERPRISE';
                    $sale->id_client = $sale_query->id_client;
                    $client = DB::table('clients_enterprise')->select('name', 'email')->find($sale_query->id_client);
                    $actual_client = $client->name . ' === ' . $client->email;
                }
                if($sale_query->type_client === 'SINGULAR'){
                    $sale->client_type = 'SINGULAR';
                    $sale->id_client = $sale_query->id_client;
                    $client = DB::table('clients_singular')->select('name', 'surname', 'email')->find($sale_query->id_client);
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
                $service = DB::table('services')->select('services.name', 'services.description', 'services.price')
                ->find($sale_query->id_product_service);
                $sale->id = $sale_query->id;
                $sale->sale_type = 'PRODUCT';
                $sale->name = $service->name;
                $sale->description = $service->description;
                $sale->price_unit = $service->price;
                if($sale_query->type_client === 'ENTERPRISE'){
                    $sale->client_type = 'ENTERPRISE';
                    $sale->id_client = $sale_query->id_client;
                    $client = DB::table('clients_enterprise')->select('name', 'email')->find($sale_query->id_client);
                    $actual_client = $client->name . ' === ' . $client->email;
                }
                if($sale_query->type_client === 'SINGULAR'){
                    $sale->client_type = 'SINGULAR';
                    $sale->id_client = $sale_query->id_client;
                    $client = DB::table('clients_singular')->select('name', 'surname', 'email')->find($sale_query->id_client);
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
        return view ('home.pages.sale.sale', $user,
        [
            'company_type' => $company->type,
            'logo' => $company_logo,
            'deadline_payment' =>  $this->expire_msg,
            'sales' => $sales,
            'services' => $services,
            'products' => $products,
            'clients_enterprise' => $clients_enterprise,
            'clients_singular' => $clients_singular,
            'hasSales' => $hasSales,
            'actual_client' => $actual_client,
            'isAdmin' => $isAdmin,
            'enable_sales' => $this->enable_sales
        ]);
    }

    public function view_product()
    {
        $user = Auth::user();
        $company = Company::where('id', 'like', $user->id_company)->first();
        $products = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('products', 'users.id', '=', 'products.id_user')
        ->select('products.*')
        ->where('companies.id', 'like', $company->id)->orderBy('name')->paginate(30);
        return view ('home.pages.product.product', $user, ['products' => $products,
        'logo' => url('storage/' . $company->logo)]);
    }

    public function view_credit()
    {
        $user = Auth::user();
        $company = Company::where('id', 'like', $user->id_company)->first();
        $invoices_query = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('invoices', 'users.id', '=', 'invoices.id_user')
        ->select('invoices.*')
        ->where('companies.id', 'like', $company->id)
        ->where('invoices.status', 'like', 'PAID')
        ->orderByDesc('invoices.created_at')->get();
        $invoices = [];
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
        return view ('home.pages.credit.credit', $user, ['invoices' => $invoices,
        'logo' => url('storage/' . $company->logo)]);
    }

    public function view_debit()
    {
        $user = Auth::user();
        $company = Company::where('id', 'like', $user->id_company)->first();
        $invoices_query = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('invoices', 'users.id', '=', 'invoices.id_user')
        ->select('invoices.*')
        ->where('companies.id', 'like', $company->id)
        ->where('invoices.status', 'like', 'NOT PAID')
        ->orderByDesc('invoices.created_at')->get();
        $invoices = [];
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
        return view ('home.pages.debit.debit', $user, ['invoices' => $invoices,
        'logo' => url('storage/' . $company->logo)]);
    }

    public function view_service()
    {
        $user = Auth::user();
        $company = Company::where('id', 'like', $user->id_company)->first();
        $services = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('services', 'users.id', '=', 'services.id_user')
        ->select('services.*')
        ->where('companies.id', 'like', $company->id)->orderBy('name')->paginate(30);
        return view ('home.pages.service.service', $user, ['services' => $services,
        'logo' => url('storage/' . $company->logo)]);
    }

    public function view_client_singular()
    {
        $user = Auth::user();
        $company = Company::where('id', 'like', $user->id_company)->first();
        $clients_singular = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('clients_singular', 'users.id', '=', 'clients_singular.id_user')
        ->select('clients_singular.*')
        ->where('companies.id', 'like', $company->id)->paginate(30);
        return view ('home.pages.client_singular.client_singular', $user, ['clients_singular' => $clients_singular,
        'logo' => url('storage/' . $company->logo)]);
    }

    public function view_client_enterprise()
    {
        $user = Auth::user();
        $company = Company::where('id', 'like', $user->id_company)->first();
        $clients_enterprise = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('clients_enterprise', 'users.id', '=', 'clients_enterprise.id_user')
        ->select('clients_enterprise.*')
        ->where('companies.id', 'like', $company->id)->paginate(30);
        return view ('home.pages.client_enterprise.client_enterprise', $user, ['clients_enterprise' => $clients_enterprise,
        'logo' => url('storage/' . $company->logo)]);
    }

    public function view_user()
    {
        $user = Auth::user();
        $company = DB::table('companies')->select('*')->where('id', 'like', $user->id_company)->first();
        if($user['privilege'] == "TOTAL"){
            $users = User::where('id_company', 'like', $user->id_company)->paginate(30);
            return view ('home.pages.user.user', $user, ['users' => $users, 'logo' => url('storage/' . $company->logo)]);
        }
        return redirect()->route('view_sale')->with('sale_notification', 'A sua conta nao possui permissao para realizar esta accao');
    }

    public function view_company()
    {
        $user = Auth::user();
        $company = DB::table('companies')->select('*')->where('id', 'like', $user->id_company)->first();
        if($user['privilege'] == "TOTAL"){
            return view ('home.pages.company.company', $user, ['company' => $company,
            'logo' => url('storage/' . $company->logo)]);
        }
        //return $this->view_sale();
        return redirect()->route('view_sale')->with('sale_notification', 'A sua conta nao possui permissao para realizar esta accao');
    }

    public function view_about()
    {
        $user = Auth::user();
        $company = Company::where('id', 'like', $user->id_company)->first();
        return view ('home.pages.about.about', $user, ['logo' => url('storage/' . $company->logo)]);
    }

    /**
     * Update the user's profile.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        $request->user();
        return '';
        //returns an instance of the authenticated user...
    }
}
