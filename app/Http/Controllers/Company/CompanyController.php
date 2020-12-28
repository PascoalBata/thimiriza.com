<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\StoreCompanyRequest;
use App\Models\Company;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $empresas = Company::paginate(2);
        //$empresas = DB::table('empresas')->orderByRaw('updated_at - created_at DESC')->get('empresa_id');
        return view('pt.Admin.pages.empresas', ['empresas' => $empresas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //returns a create company view for new accounts
        return view('pt.Company.pages.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCompanyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request)
    {
        $company = new Company;
        $package_id = '2';
        $company_status = "ON";
        $company->name  = $request->input('name');
        $company->type  = $request->input('type');
        $company->nuit  = $request->input('nuit');
        $company->phone  = $request->input('phone');
        $company->address  = $request->input('address');
        $company->email  = $request->input('email');
        $company->status  = $company_status;
        $company->id_package = $package_id;
        $company->payment_date = now();
        $company->created_at = now();
        if(!$company->save()){
            return redirect()->route('create_company')->with('status', 'O registo falhou! Por favor, tente novamente.');
        }else{
            //$company_query = DB::table('companies')->select('id')->where('email', 'like', $request->input('email'))->first();
            if(User::create([
            'id_company'=>$company->id,
            'name'=> $request->input('name'),
            'surname' => 'N/A',
            'gender' => 'N/A',
            'privilege' => 'ADMIN',
            'birthdate' => now(),
            'email'=> $request->input('email'),
            'phone'=> $request->input('phone'),
            'address'=> $request->input('address'),
            'password'=> Hash::make($request->input('password')),
            'created_by'=> '0'])){
                //Send an email
                return redirect()->route('root')->with('status', 'Registo efectuado com sucesso.');
            }
            Company::destroy($company->id);
            return redirect()->route('root')->with('status', 'O registo falhou porque esse utilizador ja existe.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_company()
    {
        $user = Auth::user();
        $company_validate = $this->validate_company($user->id_company);
        $company = Company::where('id', 'like', $user->id_company)->first();
            return view ('pt.home.pages.company.company', $user,
            [
                'company' => $company,
                'privileges' => $user['privilege'],
                'logo' => $company_validate['company_logo']
            ]);
        //return $this->view_sale();
        //return redirect()->route('view_sale')->with('sale_notification', 'A sua conta nao possui permissao para realizar esta accao');
    }

    /**
     * Display information about the software owners.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_about()
    {
        $user = Auth::user();
        $company_validate = $this->validate_company($user->id_company);
        return view ('pt.home.pages.about.about', $user, ['logo' => $company_validate['company_logo']]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function payment(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            if($user->privilege == "TOTAL"){
                $phone = $request['phone'];
                $company_id = $user->id_company;
                $company = DB::table('companies')->find($company_id);
                $company_data = $company->name . ' - ' . $company->nuit;
                $channel = "Thimiriza";
                $amount = 750.0;
                $package_id = '3';
                $url = "https://mpesaphp.herokuapp.com/api/payment-fake";
                $payment = array('amount' => $amount, 'phone' => $phone, 'channel' => $channel, 'user_id' => $company_data);
                $headers = array('Content-Type: application/json', 'Accept: application/json');
                json_encode($payment);
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payment));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $server_output = curl_exec($ch);
                dd($server_output);
                if ($server_output == '{"message":"Pagamento feito com Sucesso"}') {
                    if(DB::table('companies')
                    ->where('id', $company_id)
                    ->update(array(
                        'payment_date' => now(),
                        'id_package' => $package_id
                    ))){
                        return redirect()->route('view_company')->with('company_notification', 'Pagamento efectuado com sucesso. ');
                    }
                    return redirect()->route('view_company')->with('company_notification', 'Pagamento efectuado com sucesso, porem, ocorreu um falha no sistema. Por favor, reporte-nos contactando a linha do apoio ao cliente. ');
                }else{
                    return redirect()->route('view_company')->with('company_notification', 'Pagamento sem sucesso.');
                }
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //
    }

    public function update_company(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            if($user->privilege == "TOTAL" || $user->privilege == "ADMIN"){
                    $user_id = $user->id;
                //if($request->filled('name')){
                    //update name
                //}
                $id = $request['id'];
                $name = $request['name'];
                $type = $request['type'];
                $email = $request['email'];
                $phone = $request['phone'];
                $nuit = $request['nuit'];
                $address = $request['address'];
                $bank_account_owner = $request['bank_account_owner'];
                $bank_account_number = $request['bank_account_number'];
                $bank_account_nib = $request['bank_account_nib'];
                $bank_account_name = $request['bank_account_name'];
                $company_query = DB::table('companies')->find($id);
                if(DB::table('companies')->select('*')->where('nuit', 'like', $nuit)->where('id', 'not like', $id)->exists()){
                    return redirect()->route('view_company')->with('company_notification', 'Falhou! Este NUIT ja encontra-se associado a uma outra entidade.');
                }
                if(DB::table('companies')->select('*')->where('email', 'like', $email)->where('id', 'not like', $id)->exists()){
                    return redirect()->route('view_company')->with('company_notification', 'Falhou! Este Email ja encontra-se associado a uma outra entidade.');
                }

                if($request->hasFile('logo')){
                    Storage::deleteDirectory('public/companies/'.$id.'/logo');
                    $logo = $request->file('logo')->store(
                        'companies/'. $id . '/logo',
                        'public'
                    );
                    if(DB::table('companies')
                    ->where('id', $id)
                    ->update(array(
                        'logo' => $logo,
                        'updated_at' => now()
                    ))){
                        //Storage::setVisibility($logo, 'public');
                    }
                }
                if($request->filled('name')){
                    if(DB::table('companies')
                    ->where('id', $id)
                    ->update(array(
                        'name' => $name,
                        'type' => $type,
                        'email' => $email,
                        'phone' => $phone,
                        'address' => $address,
                        'bank_account_owner' => $bank_account_owner,
                        'bank_account_number' => $bank_account_number,
                        'bank_account_name' => $bank_account_name,
                        'bank_account_nib' => $bank_account_nib,
                        'updated_at' => now()
                    ))){
                        $user_query = DB::table('users')->select('id')->where('email', 'like', $email)->first();
                        if(DB::table('users')
                        ->where('id', $user_query->id)
                        ->update(array(
                            'name' => $name,
                            'email' => $email,
                            'phone' => $phone,
                            'address' => $address,
                            'updated_at' => now()
                        ))){
                            //user updated successfuly
                        }
                        return redirect()->route('view_company')->with('company_notification', 'Actualizacao da empresa realizada com sucesso. ');
                    }
                }
                return redirect()->route('view_company')->with('company_notification', 'Falhou! Ocorreu um erro durante a actualizacao.');
            }
            return redirect()->route('view_company')->with('company_notification', 'A sua conta nao possui previlegios para realizar esta accao.');
        }
        return route('root');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validate_company($id){
        $enable_sales = true;
        $company = Company::where('id', 'like', $id)->first();
        $company_logo = url('storage/' . $company->logo);
        //$this->company_logo = '..' . Storage::url($this->company->logo);
        //dd($this->company_logo);
        //$expire = date('Y/m/d', strtotime('+30 days', strtotime($company->created_at))) . PHP_EOL . '23:59:59';
        $expire = date('Y/m/d', strtotime('+1 month', strtotime($company->payment_date))) . PHP_EOL;
        $expire_days_left = intval((strtotime($expire) - strtotime(now()))/86400)+1;
        $expire_msg = 'Validade: ';
        if($company->id_package === 2){
            $expire_msg = 'Possui apenas ' . $expire_days_left . ' dias de uso gratuito.';
        }
        if($company->id_package !== 0 && $company->id_package !== 2){
            $expire_msg = 'Validade: ' . $expire;
        }
        if($company->bank_account_owner === '' || $company->bank_account_number === ''
        || $company->bank_account_name === '' || $company->bank_account_nib === ''){
            $enable_sales = false;
        }
        if($company->logo === null || trim($company->logo) === ''){
            $company_logo = url('storage/companies/default/logo/default.png');
            $enable_sales = false;
        }
        if($expire_days_left <= 0){
            if($company->id_package !== 1){
                DB::table('companies')
                    ->where('id', $company->id)
                    ->update(array(
                        'id_package' => 1
                    ));
            }
            $expire_msg = 'Conta expirou!';
            $enable_sales = false;
        }
        return [
            'make_sales' => $enable_sales,
            'expire_msg' => $expire_msg,
            'expire_days_left' => $expire_days_left,
            'company_logo' => $company_logo,
            'company_type' => $company->type
        ];
    }
}
