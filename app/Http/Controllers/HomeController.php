<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $name = $user['name'];
        $surname = $user['surname'];
        $email = $user['email'];
        if($surname == 'N/A'){
            $surname = '';
            return redirect()->route('admin_sells', ['name' => $name]);
        }
        //return view('home.pages.about.about');
        return redirect()->route('admin_sells', ['name' => $name]);
    }

    public function about($id)
    {
        
        return view('home.pages.about.about');
    }

    public function product($id)
    {
        return view('home.pages.about.about');
    }

    public function service($id)
    {
        return view('home.pages.about.about');
    }

    public function user($id)
    {
        return view('home.pages.about.about');
    }

    public function company($id)
    {
        return view('home.pages.about.about');
    }

    public function sell($name)
    {
        $user = Auth::user();
        //$name = $user['name'];
        $surname = $user['surname'];
        $email = $user['email'];
        return 'Nome ' . $name;
    }





    /**
     * Update the user's profile.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        $request->user(); 
        return '';
        //returns an instance of the authenticated user...
    }
}
