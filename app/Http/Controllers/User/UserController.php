<?php

namespace App\Http\Controllers\User;

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
        //return dd($request->all());
        if(Auth::check()){
            $user = Auth::user();
            $code = $this->user_code();
            $id_company = $user->id_company;
             User::create([
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
            ]);
            //return route('view_user', $request->name . $request->surname);
            return route('view_user');
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
        //
    }

    public function update_birthdate(Request $request)
    {
        //
    }

    public function update_privilege(Request $request)
    {
        //
    }

    public function update_address(Request $request)
    {
        //
    }

    public function update_phone(Request $request)
    {
        //
    }

    public function update_email(Request $request)
    {
        //
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

    //Generate User ID
    private function user_code()
    {
        $users_code = DB::table('users')->orderByRaw('created_at DESC')->first();
        $company_code = substr($users_code->code, 0, 10);
        if (DB::table('users')->count() == 1) {
            return $company_code . '00001';
        }
        $user_code = $users_code->code;
        return $company_code . '' . $this->next_code($user_code);
    }

    private function next_code($last)
    {
        if (substr($last, 10, 5) == "Admin") {
            return substr($last, 0, 10) . "00001";
        }
        return $last++;
    }

}
