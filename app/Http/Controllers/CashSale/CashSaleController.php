<?php

namespace App\Http\Controllers\CashSale;

use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PDF\PDFController;
//use App\Http\Controllers\SystemMail\SystemMailController;
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

class CashSaleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
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

    public function store_vd(){
        if(Auth::check()){
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
                    return $pdf_controller->cash_sale_generator([
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
                        'type' => 'CASH_SALE'
                        ]);
                }
            }
            return redirect()->route('view_sale');
        }
        return redirect()->route('root');
    }

    public function see_cash_sale($id){
        $cash_sale = DB::table('cash_sales')->find($id);
        if($cash_sale === null){
            return back()->with('opertation_status', 'Esse VD nao existe');
        }
        $user = DB::table('users')->find($cash_sale->created_by);
        $company = DB::table('companies')->find($user->id_company);
        $user_name = $user->name . ' ' . $user->surname;
        $client_name = '';
        $client_email = '';
        $client_nuit = '';
        $client_address = '';
        if($cash_sale->client_type === 'ENTERPRISE'){
            $client = DB::table('clients_enterprise')->find($cash_sale->id_client);
            $client_name = $client->name;
            $client_nuit = $client->nuit;
            $client_email = $client->email;
            $client_nuit = $client->nuit;
            $client_address = $client->address;
        }
        if($cash_sale->client_type === 'SINGULAR'){
            $client = DB::table('clients_singular')->find($cash_sale->id_client);
            $client_name = $client->name . ' ' . $client->surname;
            $client_nuit = $client->nuit;
            $client_email = $client->email;
            $client_nuit = $client->nuit;
            $client_address = $client->address;
        }
        $pdf_controller = new PDFController;
        return $pdf_controller->check_cash_sale([
            'user_id' => $user->id,
            'client_type' => $cash_sale->client_type,
            'client_id' => $cash_sale->id_client,
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
            'cash_sale_id' => $id,
            'type' => 'CASH_SALE']);
    }
}
