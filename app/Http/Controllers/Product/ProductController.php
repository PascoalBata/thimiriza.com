<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
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
    public function create(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $code = $this->product_code($user->code);
            if(!$this->product_exists($request['name'], $request['description'], $code)){
                $product = new Product; 
                $product->code = $code;
                $product->name = $request['name'];
                $product->description = $request['description'];
                $product->price = $request['price'];
                $product->quantity = $request['quantity'];
                $product->id_user = Auth::id();
                $product->save();
            }
            return route('view_product');
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

    private function product_exists($name, $description, $user_code){
        $company_code = substr($user_code, 0, 5);
        if (DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('products', 'users.id', '=', 'products.id_user')
        ->select('products.code')
        ->where(
            ['companies.code', 'like', $company_code],
            ['products.name', 'like', $name],
            ['products.description', 'like', $description]
            )->count() > 0) {
            return true;
        }
        return false;
    }

    //Generate product_code
    private function product_code($user_code)
    {
        $company_code = substr($user_code, 0, 5);
        if (DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('products', 'users.id', '=', 'products.id_user')
        ->select('products.code')
        ->where('company.code', 'like', $company_code)->count() == 0) {
            return $company_code . '/' . $this->next_code('');
        }
        $products_code = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('products', 'users.id', '=', 'products.id_user')
        ->select('products.code')
        ->where('companies.code', 'like', $company_code)->orderByRaw('created_at DESC')->first();
        $product_code = $products_code->code;
        return $company_code . '/' . date('y') . date('m') . $this->next_code($product_code);
    }

    private function next_code($last)
    {
        $new_id = "AA0001";
        if ($last == "") {
            return $new_id;
        }
        $last++;
        $new_id = $last;
        /*
        if (substr($last, 16, 4) == "0000") {
            $letters = substr($last, 14, 2);
            $numbers = "0001";
            $new_id = $letters . $numbers;
        }
        */
        return $new_id;
    }
}
