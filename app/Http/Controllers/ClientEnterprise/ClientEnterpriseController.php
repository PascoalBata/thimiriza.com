<?php

namespace App\Http\Controllers\ClientEnterprise;

use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Controller;
use App\Models\Client_Enterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientEnterpriseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $company_controller = new CompanyController;
        $company_validate = $company_controller->validate_company($user->id_company);
        $clients_enterprise = DB::table('companies')
        ->join('clients_enterprise', 'companies.id', '=', 'clients_enterprise.id_company')
        ->select('clients_enterprise.*')
        ->where('companies.id', 'like', $user->id_company)->paginate(30);
        return view ('pt.home.pages.client_enterprise.client_enterprise', $user, ['clients_enterprise' => $clients_enterprise,
        'logo' => $company_validate['company_logo']]);
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
        if(Auth::check()){
            $user = Auth::user();
            $code = $this->client_code($user->code);
            if(!$this->client_exists($request['name'], $request['email'], $request['nuit'], $code)){
                $client_enterprise = new Client_Enterprise;
                $client_enterprise->code = $code;
                $client_enterprise->name = $request['name'];
                $client_enterprise->email = $request['email'];
                $client_enterprise->address = $request['address'];
                $client_enterprise->nuit = $request['nuit'];
                $client_enterprise->phone = $request['phone'];
                $client_enterprise->id_user = Auth::id();
                if($client_enterprise->save()){
                    return redirect()->route('view_client_enterprise')->with('view_client_enterprise_register_status', 'cliente registado com sucesso.');
                }
                return redirect()->route('view_client_enterprise')->with('view_client_enterprise_register_status', 'Falhou! Ocorreu um erro durante o registo.');
            }
            return redirect()->route('view_client_enterprise')->with('view_client_enterprise_register_status', 'Falhou! Esse Cliente já existe.');
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
            if(DB::table('companies')
            ->join('users', 'companies.id', '=', 'users.id_company')
            ->join('clients_enterprise', 'users.id', '=', 'clients_enterprise.id_user')
            ->select('clients_enterprise.name')
            ->where('companies.code', 'like', $company_code)
            ->where('clients_enterprise.name', 'like', $name)->count() >= 1){
                return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Falhou! Existe um Cliente Empresarial com esse nome.');
            }else{
                if(DB::table('clients_enterprise')
                ->where('id', $id)
                ->update(array(
                    'name' => $name,
                    'id_user' => $user_id
                ))){
                    return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Cliente Empresarial (Nome) actualizado com sucesso.');
                }
            }
            return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }



    public function update_email(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $user_code = $user->code;
            $id = $request['id'];
            $email = $request['email'];
            $company_code = substr($user_code, 0, 10);
            if(DB::table('companies')
            ->join('users', 'companies.id', '=', 'users.id_company')
            ->join('clients_enterprise', 'users.id', '=', 'clients_enterprise.id_user')
            ->select('clients_enterprise.name')
            ->where('companies.code', 'like', $company_code)
            ->where('clients_enterprise.email', 'like', $email)->count() >= 1){
                return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Falhou! Existe um Cliente Empresarial com esse email.');
            }else{
                if(DB::table('clients_enterprise')
                ->where('id', $id)
                ->update(array(
                    'email' => $email,
                    'id_user' => $user_id
                ))){
                    return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Cliente Empresarial (Email) actualizado com sucesso.');
                }
            }
            return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }

    public function update_nuit(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $user_code = $user->code;
            $id = $request['id'];
            $nuit = $request['nuit'];
            $company_code = substr($user_code, 0, 10);
            if(DB::table('companies')
            ->join('users', 'companies.id', '=', 'users.id_company')
            ->join('clients_enterprise', 'users.id', '=', 'clients_enterprise.id_user')
            ->select('clients_enterprise.name')
            ->where('companies.code', 'like', $company_code)
            ->where('clients_enterprise.nuit', 'like', $nuit)->count() >= 1 ){
                return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Falhou! Este NUIT já pertence um Cliente Empresarial.');
            }else{
                if(DB::table('companies')
                ->join('users', 'companies.id', '=', 'users.id_company')
                ->join('clients_singular', 'users.id', '=', 'clients_singular.id_user')
                ->select('clients_singular.name')
                ->where('companies.code', 'like', $company_code)
                ->where('clients_singular.nuit', 'like', $nuit)->count() >= 1 )
                {
                    return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Falhou! Este NUIT já pertence um Cliente Singular.');
                }else{
                    if(DB::table('clients_enterprise')
                    ->where('id', $id)
                    ->update(array(
                        'nuit' => $nuit,
                        'id_user' => $user_id
                    ))){
                        return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Cliente Empresarial (NUIT) actualizado com sucesso.');
                    }
                }
            }
            return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }

    public function update_address(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $user_code = $user->code;
            $id = $request['id'];
            $address = $request['address'];
            $company_code = substr($user_code, 0, 10);
            if(DB::table('companies')
            ->join('users', 'companies.id', '=', 'users.id_company')
            ->join('clients_enterprise', 'users.id', '=', 'clients_enterprise.id_user')
            ->select('clients_enterprise.name')
            ->where('companies.code', 'like', $company_code)
            ->where('clients_enterprise.address', 'like', $address)->count() >= 1){
                return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Falhou! Este NUIT já pertence um Cliente Empresarial.');
            }else{
                if(DB::table('clients_enterprise')
                ->where('id', $id)
                ->update(array(
                    'address' => $address,
                    'id_user' => $user_id
                ))){
                    return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Cliente Empresarial (Endereço) actualizado com sucesso.');
                }
            }
            return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }

    public function update_phone(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $user_code = $user->code;
            $id = $request['id'];
            $phone = $request['phone'];
            $company_code = substr($user_code, 0, 10);
            if(DB::table('companies')
            ->join('users', 'companies.id', '=', 'users.id_company')
            ->join('clients_enterprise', 'users.id', '=', 'clients_enterprise.id_user')
            ->select('clients_enterprise.name')
            ->where('companies.code', 'like', $company_code)
            ->where('clients_enterprise.phone', 'like', $phone)->count() >= 1){
                return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Falhou! Existe um Cliente Empresarial com esse email.');
            }else{
                if(DB::table('clients_enterprise')
                ->where('id', $id)
                ->update(array(
                    'phone' => $phone,
                    'id_user' => $user_id
                ))){
                    return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Cliente Empresarial (Telefone) actualizado com sucesso.');
                }
            }
            return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Falhou! Ocorreu um erro durante a actualização.');
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
                if(DB::table('clients_enterprise')->where('id', 'like', $id)->delete()){
                    return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Cliente Empresarial removido com sucesso.');
                }
            return redirect()->route('view_client_enterprise')->with('client_enterprise_notification', 'Falhou! Ocorreu um erro durante o processo da remoção.');
        }
        return route('root');
    }

    private function client_exists($name, $nuit, $user_code, $email){
        $company_code = substr($user_code, 0, 10);
        if (DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('clients_enterprise', 'users.id', '=', 'clients_enterprise.id_user')
        ->select('clients_enterprise.code')
        ->where('companies.code', 'like', $company_code)
        ->where('clients_enterprise.name', 'like', $name)
        ->where('clients_enterprise.nuit', 'like', $nuit)
        ->where('clients_enterprise.email', 'like', $email)
        ->count() > 0) {
            return true;
        }
        return false;
    }

    //Generate product_code
    private function client_code($user_code)
    {
        $company_code = substr($user_code, 0, 10);
        if (DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('clients_enterprise', 'users.id', '=', 'clients_enterprise.id_user')
        ->select('clients_enterprise.code')
        ->where('companies.code', 'like', $company_code)->count() == 0) {
            return $company_code . date('y') . date('m') . $this->next_code('');
        }
        $clients_code = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('clients_enterprise', 'users.id', '=', 'clients_enterprise.id_user')
        ->select('clients_enterprise.code')
        ->where('companies.code', 'like', $company_code)->orderByRaw('clients_enterprise.created_at DESC')->first();
        $client_code = $clients_code->code;
        return $company_code . date('y') . date('m') . $this->next_code($client_code);
    }

    private function next_code($last)
    {
        $new_id = "CEAA0001";
        if ($last == "") {
            return $new_id;
        }
        $last = substr($last, 16, 6);
        $last++;
        $new_id = 'CE'.$last;

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
