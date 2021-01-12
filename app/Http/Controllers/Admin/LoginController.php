<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private $logged = false;
    private $session;
    private $user;

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $admin = Admin::where('user', $request['user'])->first();
        if($admin === null){
            return back()->with([
                'status' => 'Credenciais erradas!',
            ]);
        }
        if ($admin->count() !== 0 && password_verify($request['password'], $admin->password)) {
            $request->session()->regenerate();
            $request->session()->put([
                'user' => $request['user'],
                'logged' => true
            ]);
            return redirect()->route('show_companies');
        }

        return back()->with([
            'status' => 'Credenciais erradas!',
        ]);
    }
}
