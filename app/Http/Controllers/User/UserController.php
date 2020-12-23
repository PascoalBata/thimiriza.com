<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Controller;
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
        $user = Auth::user();
        $company_controller = new CompanyController;
        $company_validate = $company_controller->validate_company($user->id_company);
        if($user['privilege'] === "TOTAL" || $user['privilege'] == "ADMIN"){
            $users = User::where('id_company', 'like', $user->id_company)->paginate(30);
            return view ('pt.home.pages.user.user', $user, ['users' => $users, 'logo' => $company_validate['company_logo']]);
        }
        return redirect()->route('index_sale')->with('sale_notification', 'A sua conta nao possui permissão para realizar esta acção');
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
        //return dd($request->all());
        if(Auth::check()){
            $user = Auth::user();
            $code = $this->user_code();
            $id_company = $user->id_company;
             if(User::create([
                'code' => $code,
                'name' => $request['name'],
                'surname' => $request['surname'],
                'gender' => $request['gender'],
                'birthdate' => $request['birthdate'],
                'privilege' => 'PARCIAL',
                'email' => $request['email'],
                'phone' => $request['phone'],
                'nuit' => $request['nuit'],
                'address' => $request['address'],
                'id_company' => $id_company,
                'password' => Hash::make($request['password'])
            ])){
                return redirect()->route('view_user')->with('user_notification', 'Utilizador registadp com sucesso.');
            }
            return redirect()->route('view_user')->with('user_notification', 'Falhou! Ocorreu um erro durante o registo.');
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
    public function update_name(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $id = $request['id'];
            $name = $request['name'];
            $surname = $request['surname'];
            $user_query = DB::table('users')->select('surname')->where('id', 'like', $id)->first();
            if($user_query->surname == "N/A"){
                return redirect()->route('view_user')->with('user_notification', 'Não pode actualizar dados do Utilizador administrador.');
            }
            if(DB::table('users')
            ->where('id', $id)
            ->update(array(
                'name' => $name,
                'surname' => $surname,
                'updated_at' => now()
            ))){
                return redirect()->route('view_user')->with('user_notification', 'Utilizador (Nome) actualizado com sucesso.');
            }
            return redirect()->route('view_user')->with('user_notification', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }

    public function update_gender(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $id = $request['id'];
            $gender = $request['gender'];
            $user_query = DB::table('users')->select('surname')->where('id', 'like', $id)->first();
            if($user_query->surname == "N/A"){
                return redirect()->route('view_user')->with('user_notification', 'Não pode actualizar dados do Utilizador administrador.');
            }
            if(DB::table('users')
            ->where('id', $id)
            ->update(array(
                'gender' => $gender,
                'updated_at' => now()
            ))){
                return redirect()->route('view_user')->with('user_notification', 'Utilizador (Género) actualizado com sucesso.');
            }
            return redirect()->route('view_user')->with('user_notification', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }

    public function update_birthdate(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $id = $request['id'];
            $birthdate = $request['birthdate'];
            $user_query = DB::table('users')->select('surname')->where('id', 'like', $id)->first();
            if($user_query->surname == "N/A"){
                return redirect()->route('view_user')->with('user_notification', 'Não pode actualizar dados do Utilizador administrador.');
            }
            if(DB::table('users')
            ->where('id', $id)
            ->update(array(
                'birthdate' => $birthdate,
                'updated_at' => now()
            ))){
                return redirect()->route('view_user')->with('user_notification', 'Utilizador (Data de nascimento) actualizado com sucesso.');
            }
            return redirect()->route('view_user')->with('user_notification', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }

    public function update_privilege(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $id = $request['id'];
            $privilege = $request['privilege'];
            $user_query = DB::table('users')->select('surname')->where('id', 'like', $id)->first();
            if($user_query->surname == "N/A"){
                return redirect()->route('view_user')->with('user_notification', 'Não pode actualizar dados do Utilizador administrador.');
            }
            if(DB::table('users')
            ->where('id', $id)
            ->update(array(
                'privilege' => $privilege,
                'updated_at' => now()
            ))){
                return redirect()->route('view_user')->with('user_notification', 'Utilizador (Previlégio) actualizado com sucesso.');
            }
            return redirect()->route('view_user')->with('user_notification', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }

    public function update_address(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $id = $request['id'];
            $address = $request['address'];
            $user_query = DB::table('users')->select('surname')->where('id', 'like', $id)->first();
            if($user_query->surname == "N/A"){
                return redirect()->route('view_user')->with('user_notification', 'Não pode actualizar dados do Utilizador administrador.');
            }
            if(DB::table('users')
            ->where('id', $id)
            ->update(array(
                'address' => $address,
                'updated_at' => now()
            ))){
                return redirect()->route('view_user')->with('user_notification', 'Utilizador (Endereço) actualizado com sucesso.');
            }
            return redirect()->route('view_user')->with('user_notification', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }

    public function update_phone(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $id = $request['id'];
            $phone = $request['phone'];
            $user_query = DB::table('users')->select('surname')->where('id', 'like', $id)->first();
            if($user_query->surname == "N/A"){
                return redirect()->route('view_user')->with('user_notification', 'Não pode actualizar dados do Utilizador administrador.');
            }
            if(DB::table('users')
            ->where('id', $id)
            ->update(array(
                'phone' => $phone,
                'updated_at' => now()
            ))){
                return redirect()->route('view_user')->with('user_notification', 'Utilizador (Telefone) actualizado com sucesso.');
            }
            return redirect()->route('view_user')->with('user_notification', 'Falhou! Ocorreu um erro durante a actualização.');
        }
        return route('root');
    }

    public function update_email(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $id = $request['id'];
            $email = $request['email'];
            $user_query = DB::table('users')->select('surname')->where('id', 'like', $id)->first();
            if($user_query->surname == "N/A"){
                return redirect()->route('view_user')->with('user_notification', 'Não pode actualizar dados do Utilizador administrador.');
            }
            if(DB::table('users')
            ->where('id', $id)
            ->update(array(
                'email' => $email,
                'updated_at' => now()
            ))){
                return redirect()->route('view_user')->with('user_notification', 'Utilizador (Email) actualizado com sucesso.');
            }
            return redirect()->route('view_user')->with('user_notification', 'Falhou! Ocorreu um erro durante a actualização.');
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
        //
    }

}
