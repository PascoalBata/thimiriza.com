<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Company\CompanyController;
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
        if(Auth::check()){
            $user = Auth::user();
            $company_controller = new CompanyController;
            $company_validate = $company_controller->validate_company($user->id_company);
            $products = Product::where('id_company', $user->id_company)->paginate(30);
            return view ('pt.home.pages.product.product', $user, ['products' => $products,
            'logo' => $company_validate['company_logo'],
            'company_type' => $company_validate['company_type'],
            'is_edit' => false,
            'is_destroy' => false]);
        }
        return route('root');
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
            if(!$this->product_exists($request['name'], $request['description'])){
                $product = new Product;
                $product->name = $request['name'];
                $product->description = $request['description'];
                $product->price = $request['price'];
                $product->quantity = $request['quantity'];
                $product->iva = $request['product_iva'];
                if($request['product_iva'] === null){
                    $product->iva = 'off';
                }
                $product->created_by = $user->id;
                $product->id_company = $user->id_company;
                if($product->save()){
                    return redirect()->route('view_product')->with('operation_status', 'Produto registado com sucesso.');
                }
                return redirect()->route('view_product')->with('operation_status', 'Falhou! Ocorreu um erro durante o registo.');
            }
            return redirect()->route('view_product')->with('operation_status', 'Falhou! Esse produto já existe.');
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
        if(Auth::check()){
            $user = Auth::user();
            $company_controller = new CompanyController;
            $company_validate = $company_controller->validate_company($user->id_company);
            $products = Product::where('id_company', $user->id_company)->paginate(30);
            $product = DB::table('products')->find($id);
            if($product === null){
                return view ('pt.home.pages.product.product', $user, ['products' => $products,
                'logo' => $company_validate['company_logo'],
                'company_type' => $company_validate['company_type'],
                'product' => $product,
                'is_edit' => false,
                'is_destroy' => false]);
            }
            return view ('pt.home.pages.product.product', $user, ['products' => $products,
            'logo' => $company_validate['company_logo'],
            'company_type' => $company_validate['company_type'],
            'selected_product' => $product,
            'is_edit' => true,
            'is_destroy' => false]);
        }
        return route('root');
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
        if(Auth::check()){
            $user = Auth::user();
            $product = Product::find($id);
            $product->name = $request['edit_name'];
            $product->description = $request['edit_description'];
            $product->price = $request['edit_price'];
            $product->quantity = $request['edit_quantity'];
            $product->iva = $request['edit_product_iva'];
            $product->updated_by = $user->id;
            if($request['edit_product_iva'] === null){
            $product->iva = 'off';
            }
            $product->created_by = $user->id;
            $product->id_company = $user->id_company;
            if($product->save()){
            return redirect()->route('edit_product', $id)->with('operation_status', 'Produto actualizado com sucesso.');
            }
            return redirect()->route('edit_product', $id)->with('operation_status', 'Falhou! Ocorreu um erro durante a actualizaçåo do produto.');
        }
        return route('root');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        if(Auth::check()){
            $user = Auth::user();
            $product = Product::find($id);
            if($product->delete()){
                $product->updated_by = $user->id;
                $product->save();
                return redirect()->route('view_product')->with('operation_status', 'Produto removido sucesso.');
            }
            return redirect()->route('view_product')->with('operation_status', 'Sem sucesso! Esse produto não existe.');
        }
        return route('root');
    }

    private function product_exists($name, $description){
        if (DB::table('companies')
        ->join('products', 'companies.id', '=', 'products.id_company')
        ->select('*')
        ->where('products.name', 'like', $name)
        ->where('products.description', 'like', $description)
        ->count() > 0) {
            return true;
        }
        return false;
    }
}
