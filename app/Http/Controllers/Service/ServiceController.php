<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::paginate();
       
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
            $code = $this->service_code($user->code);
            if(!$this->service_exists($request['name'], $request['description'], $code)){
                $service = new Service; 
                $service->code = $code;
                $service->name = $request['name'];
                $service->description = $request['description'];
                $service->price = $request['price'];
                $service->id_user = Auth::id();
                if($service->save()){
                    return redirect()->route('view_service')->with('service_register_status', 'Serviço registado com sucesso.');
                }
                return redirect()->route('view_service')->with('service_register_status', 'Falhou! Ocorreu um erro durante o registo.');
            }
            return redirect()->route('view_service')->with('service_register_status', 'Falhou! Esse serviço já existe.');
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
    public function edit()
    {
        if(Auth::check()){
            $user = Auth::user();
            if(!$this->service_exists($request['name'], $request['description'], $code)){
                $service = new Service; 
                $service->code = $code;
                $service->name = $request['name'];
                $service->description = $request['description'];
                $service->price = $request['price'];
                $service->id_user = Auth::id();
                if($service->save()){
                    return redirect()->route('view_service')->with('service_register_status', 'Serviço registado com sucesso.');
                }
                return redirect()->route('view_service')->with('service_register_status', 'Falhou! Ocorreu um erro durante o registo.');
            }
            return redirect()->route('view_service')->with('service_register_status', 'Falhou! Esse serviço já existe.');
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

    public function update_name(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $user_code = $user->code;
            $id = $request['id'];
            $name = $request['name'];
            $company_code = substr($user_code, 0, 10);
            $services = DB::table('services')
            ->select('name', 'description')
            ->where('id', 'like', $id)->first();
            if(DB::table('companies')
            ->join('users', 'companies.id', '=', 'users.id_company')
            ->join('services', 'users.id', '=', 'services.id_user')
            ->select('services.name', 'services.description')
            ->where('companies.code', 'like', $company_code)
            ->where('services.name', 'like', $name)
            ->where('services.description', 'like', $services->description)->count() >= 1){
                return redirect()->route('view_service')->with('service_register_status', 'Falhou! Existe um Serviço com esse nome e descricao.');
            }else{
                if(DB::table('services')
                ->where('id', $id)
                ->update(array(
                    'name' => $name,
                    'id_user' => $user_id
                ))){
                    return redirect()->route('view_service')->with('service_register_status', 'Serviço (Nome) actualizado com sucesso.');
                }
            }
            return redirect()->route('view_service')->with('service_register_status', 'Falhou! Ocorreu um erro durante a actualizacao.');
        }
        return route('root');
    }

    public function update_description(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $user_code = $user->code;
            $id = $request['id'];
            $description = $request['description'];
            $company_code = substr($user_code, 0, 10);
            $services = DB::table('services')
            ->select('name', 'description')
            ->where('id', 'like', $id)->first();
            if(DB::table('companies')
            ->join('users', 'companies.id', '=', 'users.id_company')
            ->join('services', 'users.id', '=', 'services.id_user')
            ->select('services.name', 'services.description')
            ->where('companies.code', 'like', $company_code)
            ->where('services.name', 'like', $services->name)
            ->where('services.description', 'like', $description)->count() >= 1){
                return redirect()->route('view_service')->with('service_register_status', 'Falhou! Existe um Serviço com esse nome e descricao.');
            }else{
                if(DB::table('services')
                ->where('id', $id)
                ->update(array(
                    'description' => $description,
                    'id_user' => $user_id
                ))){
                    return redirect()->route('view_service')->with('service_register_status', 'Serviço (Descricao) actualizado com sucesso.');
                }
            }
            return redirect()->route('view_service')->with('service_register_status', 'Falhou! Ocorreu um erro durante a actualizacao.');
        }
        return route('root');
    }

    public function update_price(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $id = $request['id'];
            $price = $request['price'];
            if(DB::table('services')
                ->where('id', $id)
                ->update(array(
                'price' => $price,
                'id_user' => $user_id
            ))){
                    return redirect()->route('view_service')->with('service_register_status', 'Serviço (Preco) actualizado com sucesso.');
                }
            return redirect()->route('view_service')->with('service_register_status', 'Falhou! Ocorreu um erro durante a actualizacao.');
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
        //
    }

    private function service_exists($name, $description, $user_code){
        $company_code = substr($user_code, 0, 10);
        if (DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('services', 'users.id', '=', 'services.id_user')
        ->select('services.code')
        ->where('companies.code', 'like', $company_code)
        ->where('services.name', 'like', $name)
        ->where('services.description', 'like', $description)
        ->count() > 0) {
            return true;
        }
        return false;
    }

    //Generate product_code
    private function service_code($user_code)
    {
        $company_code = substr($user_code, 0, 10);
        if (DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('services', 'users.id', '=', 'services.id_user')
        ->select('services.code')
        ->where('companies.code', 'like', $company_code)->count() == 0) {
            return $company_code . date('y') . date('m') . $this->next_code('');
        }
        $services_code = DB::table('companies')
        ->join('users', 'companies.id', '=', 'users.id_company')
        ->join('services', 'users.id', '=', 'services.id_user')
        ->select('services.code')
        ->where('companies.code', 'like', $company_code)->orderByRaw('services.created_at DESC')->first();
        $service_code = $services_code->code;
        return $company_code . date('y') . date('m') . $this->next_code($service_code);
    }

    private function next_code($last)
    {
        $new_id = "SAA0001";
        if ($last == "") {
            return $new_id;
        }
        $last = substr($last, 15, 6);
        $last++;
        $new_id = 'S'.$last;
        
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