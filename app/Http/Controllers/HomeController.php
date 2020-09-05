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
use stdClass;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        return redirect()->route('view_product');
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
        /*
        $sales = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('products', 'users.id', '=', 'products.id_user')
        ->join('services', 'users.id', '=', 'services.id_user')
        ->join('sales', 'users.id', '=', 'sales.id_user')
        ->join('clients_enterprise', 'users.id', '=', 'clients_enterprise.id_user')
        ->join('clients_singular', 'users.id', '=', 'clients_singular.id_user')
        ->select('*')
        ->where('sales.id_user', 'like', $user->id)->paginate(30);
        */
        $sales_query = DB::table('sales')->select('id', 'type', 'id_product_service', 'type_client', 'id_client', 'quantity')
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
                $sale->quantity = $sale_query->quantity;
                $sale->price = $product->price * $sale_query->quantity;
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
                $sale->quantity = $sale_query->quantity;
                $sale->price = $service->price * $sale_query->quantity;
            }
            $sales[$i] = $sale; 
            $i++;  
        }
        if($i > 0){
            $hasSales = true;
        }else{
            $hasSales = false;
        }
        //dd($sales);
        return view ('home.pages.sale.sale', $user, 
        [
            'sales' => $sales,
            'services' => $services,
            'products' => $products,
            'clients_enterprise' => $clients_enterprise,
            'clients_singular' => $clients_singular,
            'hasSales' => $hasSales,
            'actual_client' => $actual_client
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
        ->where('companies.id', 'like', $company->id)->paginate(30);
        return view ('home.pages.product.product', $user, ['products' => $products]);
    }

    public function view_service()
    {
        $user = Auth::user();
        $company = Company::where('id', 'like', $user->id_company)->first();
        $services = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('services', 'users.id', '=', 'services.id_user')
        ->select('services.*')
        ->where('companies.id', 'like', $company->id)->paginate(30);
        return view ('home.pages.service.service', $user, ['services' => $services]);
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
        return view ('home.pages.client_singular.client_singular', $user, ['clients_singular' => $clients_singular]);
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
        return view ('home.pages.client_enterprise.client_enterprise', $user, ['clients_enterprise' => $clients_enterprise]);
    }

    public function view_user()
    {
        $user = Auth::user();
        if($user['privilege'] == "TOTAL"){
            $users = User::where('id_company', 'like', $user->id_company)->paginate(30);
            return view ('home.pages.user.user', $user, ['users' => $users]);
        }
        $this->view_product();
    }

    public function view_company()
    {
        $user = Auth::user();
        $company = DB::table('companies')->select('*')->where('id', 'like', $user->id_company)->first();
        if($user['privilege'] == "TOTAL"){
            return view ('home.pages.company.company', $user, ['company' => $company]);
        }
        $this->view_product();
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
