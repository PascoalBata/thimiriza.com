<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\Sale;
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
            $client = explode(' === ', $request['client'], 2);
            $client_name = $client[0];
            $client_email = $client[1];
            $sale_type = $request['sale_type'];
            $sale_name_description = explode(' === ', $request['name'], 2);
            $sale_name = $sale_name_description[0];
            $sale_description = $sale_name_description[1];
            $quantity = $request['quantity'];
            $type_client = 'ENTERPRISE';
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
                        if(
                            DB::table('sales')
                            ->updateOrInsert(
                                ['id_product_service' => $products->id, 'type' => $sale_type, 
                                'type_client' => $type_client, 'id_client' => $client->id, 'id_user' => $user->id], 
                                ['quantity' => $quantity]
                            )
                        ){
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
                            ['id_product_service' => $services->id, 'type' => $sale_type, 
                            'type_client' => $type_client, 'id_client' => $client->id, 'id_user' => $user->id], 
                            ['quantity' => $quantity]
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
    public function destroy($id)
    {
        //
    }
}
