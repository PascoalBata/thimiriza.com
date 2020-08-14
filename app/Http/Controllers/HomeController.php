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
            return redirect()->route('view_product', ['name' => $name]);
        }
        return redirect()->route('view_product', ['name' => $name . $surname]);
        
    }


    public function view_sell($name)
    {
        $user = Auth::user();
        $name = $user['name'];
        $surname = $user['surname'];
        $email = $user['email'];
        return view ('home.pages.sell.sell');
    }

    public function view_product($name)
    {
        $user = Auth::user();
        $name = $user['name'];
        $surname = $user['surname'];
        $email = $user['email'];
        return view ('home.pages.product.product', $user);
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
