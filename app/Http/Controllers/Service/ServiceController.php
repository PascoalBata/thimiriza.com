<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Company\CompanyController;
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
        if(Auth::check()){
            $user = Auth::user();
            $company_controller = new CompanyController;
            $company_validate = $company_controller->validate_company($user->id_company);
            $services = Service::where('id_company', $user->id_company)->paginate(30);
            return view ('pt.home.pages.service.service', $user, ['services' => $services,
            'logo' => $company_validate['company_logo'],
            'company_type' => $company_validate['company_type'],
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
            if(!$this->service_exists($request['name'], $request['description'])){
                $service = new Service;
                $service->name = $request['name'];
                $service->description = $request['description'];
                $service->price = $request['price'];
                $service->iva = $request['service_iva'];
                if($request['service_iva'] === null){
                    $service->iva = 'off';
                }
                $service->created_by = $user->id;
                $service->id_company = $user->id_company;
                if($service->save()){
                    return redirect()->route('view_service')->with('operation_status', 'Serviço registado com sucesso.');
                }
                return redirect()->route('view_service')->with('operation_status', 'Falhou! Ocorreu um erro durante o registo.');
            }
            return redirect()->route('view_service')->with('operation_status', 'Falhou! Esse serviço já existe.');
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
            $services = Service::where('id_company', $user->id_company)->paginate(30);
            $service = DB::table('services')->find($id);
            if($service === null){
                return view ('pt.home.pages.service.service', $user, ['services' => $services,
                'logo' => $company_validate['company_logo'],
                'company_type' => $company_validate['company_type'],
                'service' => $service,
                'is_edit' => false,
                'is_destroy' => false]);
            }
            return view ('pt.home.pages.service.service', $user, ['services' => $services,
            'logo' => $company_validate['company_logo'],
            'company_type' => $company_validate['company_type'],
            'selected_service' => $service,
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
        if(Auth::check()){
            $user = Auth::user();
            $service = Service::find($id);
            $service->name = $request['edit_name'];
            $service->description = $request['edit_description'];
            $service->price = $request['edit_price'];
            $service->iva = $request['edit_service_iva'];
            $service->updated_by = $user->id;
            if($request['edit_service_iva'] === null){
            $service->iva = 'off';
            }
            $service->id_company = $user->id_company;
            if($service->save()){
            return redirect()->route('view_service', $id)->with('operation_status', 'Serviço actualizado com sucesso.');
            }
            return redirect()->route('view_service', $id)->with('operation_status', 'Falhou! Ocorreu um erro durante a actualizaçåo do serviço.');
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
            $user = Auth::user();
            $service = Service::find($id);
            if(Service::find($id)->delete()){
                $service->updated_by = $user->id;
                $service->save();
                return redirect()->route('view_service')->with('operation_status', 'Serviço removido sucesso.');
            }
            return redirect()->route('view_service')->with('operation_status', 'Sem sucesso! Esse serviço não existe.');
        }
        return route('root');
    }

    private function service_exists($name, $description){
        if (DB::table('companies')
        ->join('services', 'companies.id', '=', 'services.id_company')
        ->select('*')
        ->where('services.name', 'like', $name)
        ->where('services.description', 'like', $description)
        ->count() > 0) {
            return true;
        }
        return false;
    }
}
