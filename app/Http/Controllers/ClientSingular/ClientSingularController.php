<?php

namespace App\Http\Controllers\ClientSingular;

use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Controller;
use App\Models\Client_Enterprise;
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
        if(Auth::check()){
            $user = Auth::user();
            $company_controller = new CompanyController;
            $company_validate = $company_controller->validate_company($user->id_company);
            $clients_singular = Client_Singular::where('id_company', 'like', $user->id_company)->paginate(30);
            return view ('pt.home.pages.client_singular.client_singular', $user, ['clients_singular' => $clients_singular,
            'logo' => $company_validate['company_logo'],
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
            if(!$this->client_exists($request['email'], $request['nuit'])){
                $client_singular = new Client_Singular;
                $client_singular->name = $request['name'];
                $client_singular->surname = $request['surname'];
                $client_singular->email = $request['email'];
                $client_singular->address = $request['address'];
                $client_singular->nuit = $request['nuit'];
                $client_singular->phone = $request['phone'];
                $client_singular->id_company = $user->id_company;
                $client_singular->created_by = $user->id;
                if($client_singular->save()){
                    return redirect()->route('view_client_singular')->with('operation_status', 'cliente registado com sucesso.');
                }
                return redirect()->route('view_client_singular')->with('operation_status', 'Falhou! Ocorreu um erro durante o registo.');
            }
            return redirect()->route('view_client_singular')->with('operation_status', 'Falhou! Esse Cliente já existe.');
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
            $clients_singular = Client_Singular::where('id_company', $user->id_company)->paginate(30);
            $client_singular = DB::table('clients_singular')->find($id);
            if($user === null){
                return view ('pt.home.pages.client_singular.client_singular', $user, ['clients_singular' => $clients_singular,
                    'logo' => $company_validate['company_logo'],
                    'is_edit' => false,
                    'is_destroy' => false]);
            }
            return view ('pt.home.pages.client_singular.client_singular', $user, ['clients_singular' => $clients_singular,
                'logo' => $company_validate['company_logo'],
                'selected_client_singular' => $client_singular,
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
        //
    }

    public function update_name(Request $request, $id)
    {
        if(Auth::check()){
            $user = Auth::user();
            $client_singular = Client_Singular::find($id);
            $client_singular->name = $request['name'];
            $client_singular->surname = $request['surname'];
            $client_singular->created_by = $user->id;
            $client_singular->created_at = now();
            if($client_singular->save()){
                return redirect()->route('edit_client_singular', $id)->with('operation_status', 'Cliente Empresarial (Nome) actualizado com sucesso.');
            }
            return redirect()->route('edit_client_singular', $id)->with('operation_status', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }

    public function update_email(Request $request, $id)
    {
        if(Auth::check()){
            $user = Auth::user();
            $email = $request['email'];
            $client_singular = Client_Singular::find($id);
            if(Client_Enterprise::where('email', $email)->where('id_company', $user->id_company)->count() > 0){
                return redirect()->route('edit_client_singular', $id)->with('operation_status', 'Falhou! Este Email actualmente pertence a um Cliente Empresarial.');
            }else{
                $client_singular->email = $email;
                $client_singular->created_by = $user->id;
                $client_singular->created_at = now();
                if($client_singular->save()){
                    return redirect()->route('edit_client_singular', $id)->with('operation_status', 'Cliente Singular (Email) actualizado com sucesso.');
                }
            }
            return redirect()->route('edit_client_singular', $id)->with('operation_status', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }

    public function update_phone(Request $request, $id)
    {
        if(Auth::check()){
            $user = Auth::user();
            $client_singular = Client_Singular::find($id);
            $client_singular->phone = $request['phone'];
            $client_singular->created_by = $user->id;
            $client_singular->created_at = now();
            if($client_singular->save()){
                return redirect()->route('edit_client_singular', $id)->with('operation_status', 'Cliente Singular (Telefone) actualizado com sucesso.');
            }
            return redirect()->route('edit_client_singular', $id)->with('operation_status', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }

    public function update_nuit(Request $request, $id)
    {
        if(Auth::check()){
            $user = Auth::user();
            $nuit = $request['nuit'];
            $client_singular = Client_Singular::find($id);
            if(Client_Enterprise::where('nuit', $nuit)->where('id_company', $user->id_company)->count() > 0){
                return redirect()->route('edit_client_singular', $id)->with('operation_status', 'Falhou! Este NUIT pertence um Cliente Empresarial.');
            }else{
                if(Client_Singular::where('nuit', $nuit)->where('id_company', $user->id_company)->count() > 0)
                {
                    return redirect()->route('edit_client_singular', $id)->with('operation_status', 'Falhou! Este NUIT já pertence um Cliente Singular.');
                }else{
                    $client_singular->nuit = $nuit;
                    $client_singular->created_by = $user->id;
                    $client_singular->created_at = now();
                    if($client_singular->save()){
                        return redirect()->route('edit_client_singular', $id)->with('operation_status', 'Cliente Singular (NUIT) actualizado com sucesso.');
                    }
                }
            }
            return redirect()->route('edit_client_singular', $id)->with('operation_status', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }

    public function update_address(Request $request, $id)
    {
        if(Auth::check()){
            $user = Auth::user();
            $client_singular = Client_Singular::find($id);
            $client_singular->address = $request['address'];
            $client_singular->created_by = $user->id;
            $client_singular->created_at = now();
            if($client_singular->save()){
                return redirect()->route('edit_client_singular', $id)->with('operation_status', 'Cliente Singular (Endereço) actualizado com sucesso.');
            }
            return redirect()->route('edit_client_singular', $id)->with('operation_status', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(Auth::check()){
                if(Client_Singular::find($id)->delete()){
                    return redirect()->route('view_client_singular', $id)->with('operation_status', 'Cliente Singular removido com sucesso.');
                }
                return redirect()->route('view_client_singular', $id)->with('operation_status', 'Falhou! Ocorreu um erro durante o processo da remoção.');
        }
        return route('root');
    }

    public function client_exists($nuit, $email){
        if (Client_Singular::where('nuit', $nuit)
        ->where('email', $email)
        ->count() > 0) {
            return true;
        }
        return false;
    }
}
