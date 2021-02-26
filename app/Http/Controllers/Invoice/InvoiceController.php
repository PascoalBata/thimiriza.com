<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PDF\PDFController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SystemMail\SystemMailController;
use App\Models\Client_Enterprise;
use App\Models\Client_Singular;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Sale;
use TCPDF;

class InvoiceController extends Controller
{
    public function pay_invoice(Request $request, $id){
        if(Auth::check()){
            $user = Auth::user();
            $invoice = Invoice::find($id);
            if($invoice !== null){
                $invoice->status = 'PAID';
                $invoice->updated_by = $user->id;
                if($invoice->save()){
                    return redirect()->route('view_debit')->with('operation_status', 'Pagamento da factura efectuado com sucesso.');
                }
                return redirect()->route('view_debit')->with('operation_status', 'Falhou! Ocorreu um erro durante o pagamento da facuta.');
            }
            return redirect()->route('view_debit')->with('operation_status', 'Nao foi possivel efectuar o pagamento. Essa factura nao existe!');
        }
        return redirect()->route('root');
    }

    public function see_invoice($id){
        $invoice = DB::table('invoices')->find($id);
        $user = DB::table('users')->find($invoice->created_by);
        $company = DB::table('companies')->find($user->id_company);
        $status = $invoice->status;
        $user_name = $user->name . ' ' . $user->surname;
        $client_name = '';
        $client_email = '';
        $client_nuit = '';
        $client_address = '';
        if($invoice->client_type === 'ENTERPRISE'){
            $client = DB::table('clients_enterprise')->find($invoice->id_client);
            $client_name = $client->name;
            $client_nuit = $client->nuit;
            $client_email = $client->email;
            $client_nuit = $client->nuit;
            $client_address = $client->address;
        }
        if($invoice->client_type === 'SINGULAR'){
            $client = DB::table('clients_singular')->find($invoice->id_client);
            $client_name = $client->name . ' ' . $client->surname;
            $client_nuit = $client->nuit;
            $client_email = $client->email;
            $client_nuit = $client->nuit;
            $client_address = $client->address;
        }
        $pdf_controller = new PDFController;
        return $pdf_controller->check_invoice([
            'user_id' => $user->id,
            'client_type' => $invoice->client_type,
            'client_id' => $invoice->id_client,
            'client_name' => $client_name,
            'client_email' => $client_email,
            'client_address' => $client_address,
            'client_nuit' => $client_nuit,
            'company_name' => $company->name,
            'company_email' => $company->email,
            'company_address' => $company->address,
            'company_nuit' => $company->nuit,
            'company_type' => $company->type,
            'company_bank_account_owner' => $company->bank_account_owner,
            'company_bank_account_name' => $company->bank_account_name,
            'company_bank_account_nib' => $company->bank_account_nib,
            'company_bank_account_number' => $company->bank_account_number,
            'company_logo' => url('storage/' .$company->logo),
            'user_name' => $user_name,
            'status' => $status,
            'invoice_id' => $id,
            'type' => 'INVOICE']);
    }

    public function store_invoice(){
        if(Auth::check()){
            $pdf_controller = new PDFController;
            $user = Auth::user();
            $sale = Sale::where('created_by', 'like', $user->id)->get();
            $company = Company::find($user->id_company);
            $requisites = true;
            if($sale->count() > 0){
                $sale = $sale->first();
                $client_id = $sale->id_client;
                $client_name = '';
                $client_email = '';
                $client_address = '';
                $client_nuit ='';
                $status = 'NOT PAID';
                $user_name = '';
                if($user->email !== $company->email){
                    $user_name = $user->name . ' ' . $user->surname;
                }else{
                    $user_name = 'Admin';
                }

                if($sale->type_client === 'SINGULAR'){
                    $client = Client_Singular::find($client_id);
                    $client_name = $client->name . ' ' . $client->surname;
                    $client_nuit = $client->nuit;
                    $client_address = $client->address;
                    $client_email = $client->email;
                }else{
                    if($sale->type_client === 'ENTERPRISE'){
                        $client = Client_Enterprise::find($client_id);
                        $client_name = $client->name;
                        $client_nuit = $client->nuit;
                        $client_address = $client->address;
                        $client_email = $client->email;
                    }else{
                        $requisites = false;
                    }
                }
                if($requisites){
                    return $pdf_controller->invoice_generator([
                        'client_type' => $sale->type_client,
                        'client_id' => $client_id,
                        'client_name' => $client_name,
                        'client_email' => $client_email,
                        'client_address' => $client_address,
                        'client_nuit' => $client_nuit,
                        'company_name' => $company->name,
                        'company_email' => $company->email,
                        'company_address' => $company->address,
                        'company_nuit' => $company->nuit,
                        'company_type' => $company->type,
                        'company_bank_account_owner' => $company->bank_account_owner,
                        'company_bank_account_name' => $company->bank_account_name,
                        'company_bank_account_nib' => $company->bank_account_nib,
                        'company_bank_account_number' => $company->bank_account_number,
                        'company_logo' => url('storage/' .$company->logo),
                        'user_name' => $user_name,
                        'status' => $status,
                        'type' => 'INVOICE'
                        ]);
                }
            }
            return redirect()->route('view_sale');
        }
        return redirect()->route('root');
    }
}
