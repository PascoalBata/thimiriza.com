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
        $sales = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('products', 'users.id', '=', 'products.id_user')
        ->join('services', 'users.id', '=', 'services.id_user')
        ->join('sales', 'users.id', '=', 'sales.id_user')
        ->join('clients_enterprise', 'users.id', '=', 'clients_enterprise.id_user')
        ->join('clients_singular', 'users.id', '=', 'clients_singular.id_user')
        ->select('sales.*, products.name, products.description,
        products.price, services.name, services.description,
        clients_singular.name, clients_singular_surname, clients_enterprise_name')
        ->where('sales.id_user', 'like', $user->id)->paginate(30);
        return view ('home.pages.sale.sale', $user, 
        [
            'sales' => $sales,
            'services' => $services,
            'products' => $products,
            'clients_enterprise' => $clients_enterprise,
            'clients_singular' => $clients_singular
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
