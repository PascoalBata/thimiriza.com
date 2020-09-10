<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\StoreCompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $company_code = $this->company_code(); 
        $company_name = $request->input('name');
        $company_email = $request->input('email');
        $company_phone = $request->input('phone');
        $company_address = $request->input('address');
        $company_type = $request->input('type');
        $company_password = $request->input('password');
        $company_nuit = $request->input('nuit');

        $company = new Company;
        $company->code = $company_code;
        $company->name  = $company_name;
        $company->type  = $company_type;
        $company->nuit  = $company_nuit;
        $company->phone  = $company_phone;
        $company->address  = $company_address;
        $company->email  = $company_email;
        $company->status  = $company_status;
        $company->id_package = $package_id;
        if(!$company->save()){
            //return redirect()->route('new_company')->with('status', 'O registo falhou! Por favor, tente novamente.');
        }else{
            $company_query = DB::table('companies')->select('id')->where('email', 'like', $company_email)->first();
            $id = $company_query->id;
            $userController = new RegisterController;
            $userController->create_admin([
            'id_company'=>$id,
            'code' => $company_code, 
            'name'=> $company_name, 
            'surname' => 'N/A',
            'gender' => 'N/A',
            'privilege' => 'TOTAL',
            'birthdate' => now(),
            'email'=> $company_email, 
            'phone'=> $company_phone,
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

    public function update_company(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            if($user->privilege == "TOTAL"){
                    $user_id = $user->id;
                //if($request->filled('name')){
                    //update name
                //}
                $id = $request['id'];
                $name = $request['name'];
                $type = $request['type'];
                $email = $request['email'];
                $phone = $request['phone'];
                $nuit = $request['nuit'];
                $address = $request['address'];
                $bank_account_owner = $request['bank_account_owner'];
                $bank_account_number = $request['bank_account_number'];
                $bank_account_nib = $request['bank_account_nib'];
                $bank_account_name = $request['bank_account_name'];
                $company_query = DB::table('companies')->find($id);
                if(DB::table('companies')->select('*')->where('nuit', 'like', $nuit)->where('id', 'not like', $id)->exists()){
                    return redirect()->route('view_company')->with('company_notification', 'Falhou! Este NUIT ja encontra-se associado a uma outra entidade.');
                }
                if(DB::table('companies')->select('*')->where('email', 'like', $email)->where('id', 'not like', $id)->exists()){
                    return redirect()->route('view_company')->with('company_notification', 'Falhou! Este Email ja encontra-se associado a uma outra entidade.');
                }

                if($request->hasFile('logo')){
                    Storage::deleteDirectory('public/companies/'.$id.'/logo');
                    $logo = $request->file('logo')->store(
                        'companies/'. $id . '/logo',
                        'public'
                    );
                    if(DB::table('companies')
                    ->where('id', $id)
                    ->update(array(
                        'logo' => $logo,
                        'updated_at' => now()
                    ))){
                        //Storage::setVisibility($logo, 'public');
                    }
                }
                if($request->filled('name')){
                    if(DB::table('companies')
                    ->where('id', $id)
                    ->update(array(
                        'name' => $name,
                        'type' => $type,
                        'email' => $email,
                        'phone' => $phone,
                        'address' => $address,
                        'bank_account_owner' => $bank_account_owner,
                        'bank_account_number' => $bank_account_number,
                        'bank_account_name' => $bank_account_name,
                        'bank_account_nib' => $bank_account_nib,
                        'updated_at' => now()
                    ))){
                        $user_query = DB::table('users')->select('id')->where('email', 'like', $email)->first();
                        if(DB::table('users')
                        ->where('id', $user_query->id)
                        ->update(array(
                            'name' => $name,
                            'email' => $email,
                            'phone' => $phone,
                            'address' => $address,
                            'updated_at' => now()
                        ))){
                            //user updated successfuly
                        }
                        return redirect()->route('view_company')->with('company_notification', 'Actualizacao da empresa realizada com sucesso. ');
                    }
                }
                return redirect()->route('view_company')->with('company_notification', 'Falhou! Ocorreu um erro durante a actualizacao.');
            }
            return redirect()->route('view_company')->with('company_notification', 'A sua conta nao possui previlegios para realizar esta accao.');
        }
        return route('root');
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

    private function company_code()
    {
        $companies = DB::table('companies')->orderByRaw('created_at DESC')->first();
        if (DB::table('companies')->count() == 0) {
            return $this->next_code('');
        }
        $company_code = $companies->code;
        return $this->next_code($company_code);
    }

    private function next_code($last)
    {
        $new_id = date('y') . '/' . date('m') ."A0001";
        if ($last == "") {
            return $new_id;
        }
        $last++;
        $new_id = $last;
        /*
        if (substr($last, 6, 4) == "0000") {
            $letters = substr($last, 5, 1);
            $numbers = "0001";
            $new_id = $letters . $numbers;
        }
        */
        return $new_id;
    }
}