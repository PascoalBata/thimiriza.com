<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $client = explode($request['client'], ' === ', 2);
            $client_name = $client[0];
            $client_email = $client[1];
            $sale_type = $request['sale_type'];
            $sale_name_description = explode($request['name'], ' === ', 2);
            $sale_name = $sale_name_description[0];
            $sale_description = $sale_name_description[1];
            $quantity = $request['quantity'];
            if(
                DB::table('clients_enterprise')
                ->where('email', 'like', $client_email)
                ->where('name', 'like', $client_name)
                ->exists()
            ){

            }



            if($sale_type === 'PRODUCT'){
                $products = DB::table('companies')
                ->join('users', 'companies.id', '=', 'users.id_company')
                ->join('products', 'users.id', '=', 'products.id_user')
                ->select('products.*')
                ->where('companies.id', 'like', $user->id_company)
                ->where('product_name', 'like', $sale_name)
                ->where('product_description', 'like', $sale_description)
                ->get();
                if($products->exists()){

                }
                return redirect()->route('view_sale')->with('sale_notification', 'Esse produto nao existe.');
            }
            if($sale_type === 'SERVICE'){
                $products = DB::table('companies')
                ->join('users', 'companies.id', '=', 'users.id_company')
                ->join('services', 'users.id', '=', 'services.id_user')
                ->select('products.*')
                ->where('companies.id', 'like', $user->id_company)
                ->where('service_name', 'like', $sale_name)
                ->where('service_description', 'like', $sale_description)
                ->get();
                if($products->exists()){
                    
                }
                return redirect()->route('view_sale')->with('sale_notification', 'Esse servico nao existe.');
            }    
            return redirect()->route('view_sale')->with('sale_notification', 'Falhou! Esse Cliente já existe.');
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
    public function destroy($id)
    {
        //
    }
}
