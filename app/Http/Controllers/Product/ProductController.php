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
                if($product->save()){
                    return redirect()->route('view_product')->with('product_notification', 'Produto registado com sucesso.');
                }
                return redirect()->route('view_product')->with('product_notification', 'Falhou! Ocorreu um erro durante o registo.');
            }
            return redirect()->route('view_product')->with('product_notification', 'Falhou! Esse produto já existe.');
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
    public function update_name(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $user_code = $user->code;
            $id = $request['id'];
            $name = $request['name'];
            $company_code = substr($user_code, 0, 10);
            $products = DB::table('products')
            ->select('name', 'description')
            ->where('id', 'like', $id)->first();
            if(DB::table('companies')
            ->join('users', 'companies.id', '=', 'users.id_company')
            ->join('products', 'users.id', '=', 'products.id_user')
            ->select('products.name', 'products.description')
            ->where('companies.code', 'like', $company_code)
            ->where('products.name', 'like', $name)
            ->where('products.description', 'like', $products->description)->count() >= 1){
                return redirect()->route('view_product')->with('product_notification', 'Falhou! Existe um Produto com esse nome e descricao.');
            }else{
                if(DB::table('products')
                ->where('id', $id)
                ->update(array(
                    'name' => $name,
                    'id_user' => $user_id
                ))){
                    return redirect()->route('view_product')->with('product_notification', 'Produto (Nome) actualizado com sucesso.');
                }
            }
            return redirect()->route('view_product')->with('product_notification', 'Falhou! Ocorreu um erro durante a actualizacao.');
        }
        return route('root');
    }

    public function update_description(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $user_code = $user->code;
            $id = $request['id'];
            $description = $request['description'];
            $company_code = substr($user_code, 0, 10);
            $products = DB::table('products')
            ->select('name', 'description')
            ->where('id', 'like', $id)->first();
            if(DB::table('companies')
            ->join('users', 'companies.id', '=', 'users.id_company')
            ->join('products', 'users.id', '=', 'products.id_user')
            ->select('products.name', 'products.description')
            ->where('companies.code', 'like', $company_code)
            ->where('products.name', 'like', $products->name)
            ->where('products.description', 'like', $description)->count() >= 1){
                return redirect()->route('view_product')->with('product_notification', 'Falhou! Existe um Produto com esse nome e descricao.');
            }else{
                if(DB::table('products')
                ->where('id', $id)
                ->update(array(
                    'description' => $description,
                    'id_user' => $user_id
                ))){
                    return redirect()->route('view_product')->with('product_notification', 'Produto (Descricao) actualizado com sucesso.');
                }
            }
            return redirect()->route('view_product')->with('product_notification', 'Falhou! Ocorreu um erro durante a actualizacao.');
        }
        return route('root');
    }

    public function update_quantity(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $id = $request['id'];
            $quantity = $request['quantity'];
            if(DB::table('products')
                ->where('id', $id)
                ->update(array(
                'quantity' => $quantity,
                'id_user' => $user_id
            ))){
                    return redirect()->route('view_product')->with('product_notification', 'Produto (Quantidade) actualizado com sucesso.');
                }
            return redirect()->route('view_product')->with('product_notification', 'Falhou! Ocorreu um erro durante a actualizacao.');
        }
        return route('root');
    }

    public function update_price(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $id = $request['id'];
            $price = $request['price'];
            if(DB::table('products')
                ->where('id', $id)
                ->update(array(
                'price' => $price,
                'id_user' => $user_id
            ))){
                    return redirect()->route('view_product')->with('product_notification', 'Produto (Preco) actualizado com sucesso.');
                }
            return redirect()->route('view_product')->with('product_notification', 'Falhou! Ocorreu um erro durante a actualizacao.');
        }
        return route('root');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(Auth::check()){
            $id = $request['id'];
            if(DB::table('products')->where('id', 'like', $id)->delete()){
                return redirect()->route('view_product')->with('product_notification', 'Produto removido sucesso.');
            }
            return redirect()->route('view_product')->with('product_notification', 'Falhou! Ocorreu um erro durante a remocao do produto.');
        }
        return route('root');
    }

    private function product_exists($name, $description, $user_code){
        $company_code = substr($user_code, 0, 10);
        if (DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('products', 'users.id', '=', 'products.id_user')
        ->select('products.code')
        ->where('companies.code', 'like', $company_code)
        ->where('products.name', 'like', $name)
        ->where('products.description', 'like', $description)
        ->count() > 0) {
            return true;
        }
        return false;
    }

    //Generate product_code
    private function product_code($user_code)
    {
        $company_code = substr($user_code, 0, 10);
        if (DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('products', 'users.id', '=', 'products.id_user')
        ->select('products.code')
        ->where('companies.code', 'like', $company_code)->count() == 0) {
            return $company_code . date('y') . date('m') . $this->next_code('');
        }
        $products_code = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('products', 'users.id', '=', 'products.id_user')
        ->select('products.code')
        ->where('companies.code', 'like', $company_code)->orderByRaw('products.created_at DESC')->first();
        $product_code = $products_code->code;
        return $company_code . date('y') . date('m') . $this->next_code($product_code);
    }

    private function next_code($last)
    {
        $new_id = "PAA0001";
        if ($last == "") {
            return $new_id;
        }
        $last = substr($last, 15, 6);
        $last++;
        $new_id = 'P'.$last;
        
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
