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
            $code = $this->user_code();
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
                'id_company' => substr($code, 0, 5),
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

    //Generate User ID
    private function user_code()
    {
        $users_code = DB::table('users')->orderByRaw('created_at DESC')->first();
        $company_code = $users_code->code;
        if (DB::table('users')->count() == 1) {
            return $company_code . '/' . $this->next_code('');
        }
        $user_code = $users_code->code;
        return $company_code . '/' . $this->next_code($user_code);
    }

    private function next_code($last)
    {
        $new_id = "0001";
        if ($last == "") {
            return $new_id;
        }
        $length = strlen($last);
        $last++;
        for($i = $length; $i > 0; $i--){
            if(strlen($last) < $length){
                $last = '0' . $last;
            }
        }
        $new_id = $last;
        return $new_id;
    }

}
