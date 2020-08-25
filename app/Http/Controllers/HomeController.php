<?php

namespace App\Http\Controllers;

use App\Models\Client_Enterprise;
use App\Models\Client_Singular;
use App\Models\Product;
use App\Models\Service;
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
        return redirect()->route('view_product');
    }

    public function view_home($name)
    {
        $user = Auth::user();
        $name = $user['name'];
        $surname = $user['surname'];
        $email = $user['email'];
        return view ('home.pages.home', $user);
    }


    public function view_sell($name)
    {
        $user = Auth::user();
        $name = $user['name'];
        $surname = $user['surname'];
        $email = $user['email'];
        return view ('home.pages.sell.sell');
    }

    public function view_product()
    {
        $user = Auth::user();
        $products = Product::paginate(30);
        return view ('home.pages.product.product', $user, ['products' => $products]);
    }

    public function view_service()
    {
        $user = Auth::user();
        $services = Service::paginate(30);
        return view ('home.pages.service.service', $user, ['services' => $services]);
    }

    public function view_client_singular()
    {
        $user = Auth::user();
        $clients_singular = Client_Singular::paginate(30);
        return view ('home.pages.client_singular.client_singular', $user, ['clients_singular' => $clients_singular]);
    }

    public function view_client_enterprise()
    {
        $user = Auth::user();
        $clients_enterprise = Client_Enterprise::paginate(30);
        return view ('home.pages.client_enterprise.client_enterprise', $user, ['clients_singular' => $clients_enterprise]);
    }

    public function view_user($name)
    {
        $user = Auth::user();
        $name = $user['name'];
        $surname = '';
        if($user['surname'] != 'N/A'){
            $surname = $user['surname'];
        }
        $fullname = $name . $surname;
        $email = $user['email'];
        $data = [
            'email' => $email,
            'name' => $name,
            'surname' => $surname,
            'fullname' => $fullname,
        ];
        return view ('home.pages.user.user', $data);
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
