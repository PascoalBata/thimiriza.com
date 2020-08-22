<?php

namespace App\Http\Controllers\ClientSingular;

use App\Http\Controllers\Controller;
use App\Models\Client_Singular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientSingularController extends Controller
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
        if(Auth::check()){
            $user = Auth::user();
            $code = $this->client_code($user->code);
            if(!$this->client_exists($request['email'], $request['nuit'], $code)){
                $client_enterprise = new Client_Singular(); 
                $client_enterprise->code = $code;
                $client_enterprise->name = $request['name'];
                $client_enterprise->surname = $request['surname'];
                $client_enterprise->email = $request['email'];
                $client_enterprise->address = $request['address'];
                $client_enterprise->nuit = $request['nuit'];
                $client_enterprise->phone = $request['phone'];
                $client_enterprise->id_user = Auth::id();
                if($client_enterprise->save()){
                    return redirect()->route('view_client_singular')->with('view_client_singular_register_status', 'cliente registado com sucesso.');
                }
                return redirect()->route('view_client_singular')->with('view_client_singular_register_status', 'Falhou! Ocorreu um erro durante o registo.');
            }
            return redirect()->route('view_client_singular')->with('view_client_singular_register_status', 'Falhou! Esse Cliente já existe.');
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

    private function client_exists($nuit, $user_code, $email){
        $company_code = substr($user_code, 0, 10);
        if (DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('clients_singular', 'users.id', '=', 'clients_singular.id_user')
        ->select('clients_singular.code')
        ->where('companies.code', 'like', $company_code)
        ->where('clients_singular.nuit', 'like', $nuit)
        ->where('clients_singular.email', 'like', $email)
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
        ->join('clients_singular', 'users.id', '=', 'clients_singular.id_user')
        ->select('clients_singular.code')
        ->where('companies.code', 'like', $company_code)->count() == 0) {
            return $company_code . date('y') . date('m') . $this->next_code('');
        }
        $clients_code = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('clients_singular', 'users.id', '=', 'clients_singular.id_user')
        ->select('clients_singular.code')
        ->where('companies.code', 'like', $company_code)->orderByRaw('clients_singular.created_at DESC')->first();
        $client_code = $clients_code->code;
        return $company_code . date('y') . date('m') . $this->next_code($client_code);
    }

    private function next_code($last)
    {
        $new_id = "CSAA0001";
        if ($last == "") {
            return $new_id;
        }
        $last = substr($last, 16, 6);
        $last++;
        $new_id = 'CS'.$last;
        
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
