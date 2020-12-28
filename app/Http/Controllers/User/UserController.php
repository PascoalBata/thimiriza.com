<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
            if($user['privilege'] === "TOTAL" || $user['privilege'] == "ADMIN"){
                $users = User::where('id_company', 'like', $user->id_company)->paginate(30);
                return view ('pt.home.pages.user.user', $user, ['users' => $users,
                'logo' => $company_validate['company_logo'],
                'is_edit' => false,
                'is_destroy' => false]);
            }
            return redirect()->route('view_sale')->with('operation_status', 'A sua conta não possui permissão para realizar esta acção');
        }
        return route('root');
    }

    public function root(Request $request)
    {
        //
        //$request->session()->flush();
        return view('auth.login');
    }

    public function end_session(Request $request)
    {
        //
        $request->session()->flush();
        return redirect('/');
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
            $id_company = $user->id_company;
            if(User::where('email', $request['email'])->count() > 0){
                return redirect()->route('view_user')->with('operation_status', 'O Email inserido actualmente pertence a um outro utilizador.');
            }
             if(User::create([
                'name' => $request['name'],
                'surname' => $request['surname'],
                'gender' => $request['gender'],
                'birthdate' => $request['birthdate'],
                'privilege' => $request['privilege'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'nuit' => $request['nuit'],
                'address' => $request['address'],
                'id_company' => $id_company,
                'created_by' => $user->id,
                'password' => Hash::make($request['password'])
            ])){
                return redirect()->route('view_user')->with('operation_status', 'Utilizador registado com sucesso.');
            }
            return redirect()->route('view_user')->with('operation_status', 'Falhou! Ocorreu um erro durante o registo.');
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
            $auth_user = Auth::user();
            if($auth_user['privilege'] === "TOTAL" || $auth_user['privilege'] == "ADMIN"){
                $company_controller = new CompanyController;
                $company_validate = $company_controller->validate_company($auth_user->id_company);
                $users = User::where('id_company', $auth_user->id_company)->paginate(30);
                $user = DB::table('users')->find($id);
                if($user === null){
                    return view ('pt.home.pages.user.user', $auth_user, ['users' => $users,
                    'logo' => $company_validate['company_logo'],
                    'is_edit' => false,
                    'is_destroy' => false]);
                }
                return view ('pt.home.pages.user.user', $auth_user, ['users' => $users,
                    'logo' => $company_validate['company_logo'],
                    'selected_user' => $user,
                    'is_edit' => true,
                    'is_destroy' => false]);
            }
            return redirect()->route('index_sale')->with('operation_status', 'A sua conta não possui permissão para realizar esta acção');
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
    public function update_name(Request $request, $id)
    {
        if(Auth::check()){
            $auth_user = Auth::user();
            $user = User::find($id);
            if($user->privilege == "ADMIN"){
                return redirect()->route('view_user')->with('operation_status', 'Não pode actualizar dados do Utilizador administrador.');
            }
            $user->name = $request['edit_name'];
            $user->surname = $request['edit_surname'];
            $user->updated_by = $auth_user->id;
            if($user->save()){
                return redirect()->route('edit_user', $id )->with('operation_status', 'Nome do utilizador actualizado com sucesso.');
            }
            return redirect()->route('edit_user', $id )->with('operation_status', 'Falhou! Não foi possível actualizar o nome do utilizador.');
        }
        return route('root');
    }

    public function update_password(Request $request, $id)
    {
        if(Auth::check()){
            $auth_user = Auth::user();
            $user = User::find($id);
            if($user->privilege == "ADMIN"){
                return redirect()->route('view_user')->with('operation_status', 'Não pode actualizar dados do Utilizador administrador.');
            }
            if(!password_verify($request['edit_actual_password'], $user->password)){
                return redirect()->route('edit_user', $id )->with('operation_status', 'Falhou a senha actual.');
            }
            if($request['edit_new_password'] !== $request['edit_confirm_password']){
                return redirect()->route('edit_user', $id )->with('operation_status', 'As senhas (nova e de confirmação) são diferentes.');
            }
            $user->password = Hash::make($request['edit_new_passord']);
            $user->updated_by = $auth_user->id;
            if($user->save()){
                return redirect()->route('edit_user', $id )->with('operation_status', 'Senha do utilizador actualizada com sucesso.');
            }
            return redirect()->route('edit_user', $id )->with('operation_status', 'Falhou! Não foi possível actualizar a senha.');
        }
        return route('root');
    }

    public function update_gender(Request $request, $id)
    {
        if(Auth::check()){
            $auth_user = Auth::user();
            $user = User::find($id);
            if($user->privilege == "ADMIN"){
                return redirect()->route('view_user')->with('operation_status', 'Não pode actualizar dados do Utilizador administrador.');
            }
            $user->gender = $request['edit_gender'];
            $user->updated_by = $auth_user->id;
            if($user->save()){
                return redirect()->route('edit_user', $id )->with('operation_status', 'Género do utilizador actualizado com sucesso.');
            }
            return redirect()->route('edit_user', $id )->with('operation_status', 'Falhou! Não foi possível actualizar o género do utilizador.');
        }
        return route('root');
    }

    public function update_birthdate(Request $request, $id)
    {
        if(Auth::check()){
            $auth_user = Auth::user();
            $user = User::find($id);
            if($user->privilege == "ADMIN"){
                return redirect()->route('view_user')->with('operation_status', 'Não pode actualizar dados do Utilizador administrador.');
            }
            if(intval(strtotime(now())) - (strtotime($request['edit_birthdate'])) < 568155232){
                return redirect()->route('edit_user', $id )->with('operation_status', 'Não foi possível actualizar a data de nascimento do utilizador. Idade menor de 18 anos.');
            }
            if(intval(strtotime(now())) - (strtotime($request['edit_birthdate'])) > 2209151275){
                return redirect()->route('edit_user', $id )->with('operation_status', 'Não foi possível actualizar a data de nascimento do utilizador. Idade maior de 70 anos.');
            }
            $user->birthdate = $request['edit_birthdate'];
            $user->updated_by = $auth_user->id;
            if($user->save()){
                return redirect()->route('edit_user', $id )->with('operation_status', 'Data de nascimento do utilizador actualizado com sucesso.');
            }
            return redirect()->route('edit_user', $id )->with('operation_status', 'Falhou! Não foi possível actualizar a data de nascimento do utilizador.');
        }
        return route('root');
    }

    public function update_privilege(Request $request, $id)
    {
        if(Auth::check()){
            $auth_user = Auth::user();
            $user = User::find($id);
            if($user->privilege == "ADMIN"){
                return redirect()->route('view_user')->with('operation_status', 'Não pode actualizar dados do Utilizador administrador.');
            }
            if($auth_user->id === $id){
                $user->privilege = $request['edit_privilege'];
                $user->updated_by = $auth_user->id;
                if($user->save()){
                    return redirect()->route('edit_user')->with('operation_status', 'Previlégios do utilizador actualizados com sucesso.');
                }
            }
            $user->privilege = $request['edit_privilege'];
            $user->updated_by = $auth_user->id;
            if($user->save()){
                return redirect()->route('edit_user', $id )->with('operation_status', 'Previlgios do utilizador actualizados com sucesso.');
            }
            return redirect()->route('edit_user', $id )->with('operation_status', 'Falhou! Não foi possível actualizar os previlégios do utilizador.');
        }
        return route('root');
    }

    public function update_address(Request $request, $id)
    {
        if(Auth::check()){
            $auth_user = Auth::user();
            $user = User::find($id);
            if($user->privilege == "ADMIN"){
                return redirect()->route('view_user')->with('operation_status', 'Não pode actualizar dados do Utilizador administrador.');
            }
            $user->address = $request['edit_address'];
            $user->updated_by = $auth_user->id;
            if($user->save()){
                return redirect()->route('edit_user', $id )->with('operation_status', 'Endereço do utilizador actualizados com sucesso.');
            }
            return redirect()->route('edit_user', $id )->with('operation_status', 'Falhou! Não foi possível actualizar o endereço do utilizador.');
        }
        return route('root');
    }

    public function update_phone(Request $request, $id)
    {
        if(Auth::check()){
            $auth_user = Auth::user();
            $user = User::find($id);
            if($user->privilege == "ADMIN"){
                return redirect()->route('view_user')->with('operation_status', 'Não pode actualizar dados do Utilizador administrador.');
            }
            $user->phone = $request['edit_phone'];
            $user->updated_by = $auth_user->id;
            if($user->save()){
                return redirect()->route('edit_user', $id )->with('operation_status', 'Telefone do utilizador actualizados com sucesso.');
            }
            return redirect()->route('edit_user', $id )->with('operation_status', 'Falhou! Não foi possível actualizar o telefone do utilizador.');
        }
        return route('root');
    }

    public function update_email(Request $request, $id)
    {
        if(Auth::check()){
            $auth_user = Auth::user();
            $user = User::find($id);
            if($user->privilege == "ADMIN"){
                return redirect()->route('view_user')->with('operation_status', 'Não pode actualizar dados do Utilizador administrador.');
            }
            if(User::where('email', $request['edit_email'])){
                $company = Company::find($user->id_company);
                return redirect()->route('edit_user', $id )->with('operation_status', 'Falhou! Este Email já possui uma conta na empresa ' . $company->name);
            }
            $user->email = $request['edit_email'];
            $user->updated_by = $auth_user->id;
            if($user->save()){
                return redirect()->route('edit_user', $id )->with('operation_status', 'Email do utilizador actualizados com sucesso.');
            }
            return redirect()->route('edit_user', $id )->with('operation_status', 'Falhou! Não foi possível actualizar o Email do utilizador.');
        }
        return route('root');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::check()){
            $auth_user = Auth::user();
            $user = User::find($id);
            if($user['privilege'] == "ADMIN"){
                return redirect()->route('view_user')->with('operation_status', 'Sem sucesso! Este utilizador não pode ser removido.');
            }else{
                if(User::find($id)->delete()){
                    $user->updated_by = $auth_user->id;
                    $user->save();
                    return redirect()->route('view_user')->with('operation_status', 'Utilizador removido sucesso.');
                }
                return redirect()->route('view_user')->with('operation_status', 'Sem sucesso! Este utilizador não existe.');
            }
        }
        return route('root');
    }

}
