<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\StoreCompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        //$this->middleware('auth')->only(['update', 'destroy', 'edit', 'show', 'index']);
        //$this->middleware('auth')->except(['create', 'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $empresas = Company::paginate(2);
        //$empresas = DB::table('empresas')->orderByRaw('updated_at - created_at DESC')->get('empresa_id');
        return view('pt.Admin.pages.empresas', ['empresas' => $empresas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //returns a create company view
        return view('pt.Company.pages.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCompanyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request)
    {
        //
        //$empresa_dados = $request->all();
        $package_id = '1'; 
        $company_status = "ON";
        $company_id = $this->company_id(); 
        $company_name = $request->input('name');
        $company_email = $request->input('email');
        $company_phone = $request->input('phone');
        $company_address = $request->input('address');
        $company_type = $request->input('type');
        $company_password = $request->input('password');
        $company_nuit = $request->input('nuit');

        $company = new Company;
        $company->id = $company_id;
        $company->name  = $company_name;
        $company->type  = $company_type;
        $company->nuit  = $company_nuit;
        $company->phone  = $company_phone;
        $company->address  = $company_address;
        $company->email  = $company_email;
        $company->status  = $company_status;
        $company->id_package = $package_id;
        if(!$company->save()){
            return redirect()->route('new_company')->with('status', 'O registo falhou! Por favor, tente novamente.');
        }else{
            //$utilizadorController = new UtilizadorController($request);
            //$utilizadorController->storeAdmin($empresa_id, $empresa_nome, $empresa_email, $empresa_telefone, $empresa_endereco, $empresa_senha);
            $userController = new RegisterController;
            $userController->create_admin([
            'id'=>$company_id, 
            'name'=> $company_name, 
            'surname' => 'N/A',
            'gender' => 'N/A',
            'privilege' => 'TOTAL',
            'birthdate' => now(),
            'email'=> $company_email, 
            'phone'=> $company_phone, 
            'nuit'=> $company_nuit, 
            'address'=> $company_address, 
            'password'=> $company_password]);
            return redirect()->route('root')->with('status', 'Registo efectuado com sucesso.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function company_id()
    {
        $companies_id = DB::table('companies')->orderByRaw('created_at DESC')->first();
        if (DB::table('companies')->count() == 0) {
            return $this->next_id('');
        }
        $company_id = $companies_id->id;
        return $this->next_id($company_id);
        //return view('pt.Admin.pages.teste', ['empresas' => $empresa_id]);
    }

    private function next_id($last)
    {
        $new_id = "A0001";
        if ($last == "") {
            return $new_id;
        }
        $last++;
        $new_id = $last;
        if (substr($last, 1, 4) == "0000") {
            $letters = substr($last, 0, 1);
            $numbers = "0001";
            $new_id = $letters . $numbers;
        }
        return $new_id;
    }
}
