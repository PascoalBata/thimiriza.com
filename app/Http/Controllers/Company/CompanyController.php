<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Models\Company;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(session('logged')){
            $companies = DB::table('companies')
            ->join('users', 'companies.id', '=', 'users.id_company')
            ->join('invoices', 'companies.id', '=', 'invoices.id_company')
            ->selectRaw('count(users.id) as users, count(invoices.id) as invoices, companies.id, companies.name, companies.phone,
                companies.email, companies.status, companies.created_at')
                ->groupBy('companies.id')->paginate(30);
            return view('pt.Admin.pages.companies', ['companies' => $companies])->with([
                'user' => session('user'),
            ]);
        }else{
            return redirect()->route('login_admin');
        }
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
            return back()->with('status', 'O registo falhou porque esse utilizador ja existe.');
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
        if(Auth::check()){
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
        return redirect()->route('root');

    }

    /**
     * Display information about the software owners.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_about()
    {
        if(Auth::check()){
            $user = Auth::user();
            $company_validate = $this->validate_company($user->id_company);
            return view ('pt.home.pages.about.about', $user, ['logo' => $company_validate['company_logo']]);
        }
        return redirect()->route('root');
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
            if($user->privilege == "TOTAL" || $user->privilege == "ADMIN"){
                $phone = $request['phone'];
                $company_id = $user->id_company;
                $company = Company::find($company_id);
                if($company === null){
                    return back()->with('operation_status', 'Falhou. Essa empresa nao existe!');
                }
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
                dd($ch);
                if ($server_output == '{"message":"Pagamento feito com Sucesso"}') {
                    $company->payment_date = now();
                    $company->id_package = $package_id;
                    $company->updated_by = $user->id;
                    if($company->save()){
                        return back()->with('operation_status', 'Pagamento efectuado com sucesso. ');
                    }
                    return back()->with('operation_status', 'Pagamento efectuado com sucesso, porem, ocorreu um falha no sistema. Por favor, reporte-nos contactando a linha do apoio ao cliente. ');
                }else{
                    return back()->with('operation_status', 'Pagamento sem sucesso.');
                }
                return back()->with('operation_status', 'Nao possui privilegios para efectuar esta operacao.');
            }
        }
        return redirect()->route('root');
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
                $company = Company::find($id);
                if($company !== null){
                    if(Company::where('nuit', 'like', $nuit)->where('id', 'not like', $id)->count() !== 0){
                        return back()->with('operation_status', 'Falhou! Este NUIT ja encontra-se associado a uma outra entidade.');
                    }
                    if(Company::where('email', 'like', $email)->where('id', 'not like', $id)->count() !== 0){
                        return back()->with('operation_status', 'Falhou! Este Email ja encontra-se associado a uma outra entidade.');
                    }

                    if($request->hasFile('logo')){
                        Storage::deleteDirectory('public/companies/'.$id.'/logo');
                        $logo = $request->file('logo')->store(
                            'companies/'. $id . '/logo',
                            'public'
                        );
                        $company->logo = $logo;
                        if(!$company->save()){
                            return back()->with('operation_status', 'Falhou! Nao foi possivel actualizar o logo.');
                        }
                    }
                }
                if($request->filled('name')){
                    $company->name = $name;
                    if(!$company->save()){
                        return back()->with('operation_status', 'Falhou! Nao foi possivel actualizar o nome.');
                    }
                }
                if($request->filled('email')){
                    $company->email = $email;
                    if(!$company->save()){
                        return back()->with('operation_status', 'Falhou! Nao foi possivel actualizar o email.');
                    }
                }
                if($request->filled('phone')){
                    $company->phone = $phone;
                    if(!$company->save()){
                        return back()->with('operation_status', 'Falhou! Nao foi possivel actualizar o telefone.');
                    }
                }
                if($request->filled('address')){
                    $company->address = $address;
                    if(!$company->save()){
                        return back()->with('operation_status', 'Falhou! Nao foi possivel actualizar o endereco.');
                    }
                }
                if($request->filled('bank_account_owner')){
                    $company->bank_account_owner = $bank_account_owner;
                    if(!$company->save()){
                        return back()->with('operation_status', 'Falhou! Nao foi possivel actualizar o titular da conta.');
                    }
                }
                if($request->filled('bank_account_number')){
                    $company->bank_account_number = $bank_account_number;
                    if(!$company->save()){
                        return back()->with('operation_status', 'Falhou! Nao foi possivel actualizar o numero da conta.');
                    }
                }
                if($request->filled('bank_account_name')){
                    $company->bank_account_name = $bank_account_name;
                    if(!$company->save()){
                        return back()->with('operation_status', 'Falhou! Nao foi possivel actualizar o nome do banco.');
                    }
                }
                if($request->filled('bank_account_nib')){
                    $company->bank_account_nib = $bank_account_nib;
                    if(!$company->save()){
                        return back()->with('operation_status', 'Falhou! Nao foi possivel actualizar o nib da conta.');
                    }
                }
                $company->type = $type;
                if(!$company->save()){
                    return back()->with('operation_status', 'Falhou! Nao foi possivel actualizar o logo.');
                }
                return back()->with('operation_status', 'Actualizacao bem sucedida.');
            }
            return back()->with('operation_status', 'A sua conta nao possui previlegios para realizar esta accao.');
        }
        return redirect()->route('root');
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
        if($id === null){
            return redirect()->route('root')->with('status', 'Autentique-se');
        }
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
