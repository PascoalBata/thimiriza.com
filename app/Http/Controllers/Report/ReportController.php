<?php

namespace App\Http\Controllers\Report;

use App\Helpers\Collection\CollectionHelper;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PDF\PDFController;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;
use TCPDF;

class ReportController extends Controller
{
    public function print_tax($invoices){
        if(Auth::check()){
            $inicial_date = date("Y-m-d H:i:s", substr($invoices, 0, strlen($invoices)/2));
            $limit_date = date("Y-m-d H:i:s", substr($invoices, strlen($invoices)/2, strlen($invoices)/2));
            $user = Auth::user();
            $company = DB::table('companies')->find($user->id_company);
            $pdf_controller = new PDFController;
            return $pdf_controller->print_tax($inicial_date, $limit_date, $company ,"TAX");
        }
        return route('root');
    }

    public function print_invoices_report($invoices){
        if(Auth::check()){
            $inicial_date = date("Y-m-d H:i:s", substr($invoices, 0, strlen($invoices)/2));
            $limit_date = date("Y-m-d H:i:s", substr($invoices, strlen($invoices)/2, strlen($invoices)/2));
            $user = Auth::user();
            $company = DB::table('companies')->find($user->id_company);
            $pdf_controller = new PDFController;
            return $pdf_controller->print_invoices_report($inicial_date, $limit_date, $company ,"INVOICES_REPORT");
        }
        return route('root');
    }

    public function print_cash_sales_report($cash_sales){
        if(Auth::check()){
            $inicial_date = date("Y-m-d H:i:s", substr($cash_sales, 0, strlen($cash_sales)/2));
            $limit_date = date("Y-m-d H:i:s", substr($cash_sales, strlen($cash_sales)/2, strlen($cash_sales)/2));
            $user = Auth::user();
            $company = DB::table('companies')->find($user->id_company);
            $pdf_controller = new PDFController;
            return $pdf_controller->print_cash_sales_report($inicial_date, $limit_date, $company ,"CASH_SALES_REPORT");
        }
        return route('root');
    }

    public function print_credit($invoices){
        if(Auth::check()){
            $inicial_date = date("Y-m-d H:i:s", substr($invoices, 0, strlen($invoices)/2));
            $limit_date = date("Y-m-d H:i:s", substr($invoices, strlen($invoices)/2, strlen($invoices)/2));
            $user = Auth::user();
            $company = Company::find($user->id_company);
            $pdf_controller = new PDFController;
            return $pdf_controller->print_credit($inicial_date, $limit_date, $company ,"CREDIT");
        }
        return route('root');
    }

    public function print_debit($invoices){
        if(Auth::check()){
            $inicial_date = date("Y-m-d H:i:s", substr($invoices, 0, strlen($invoices)/2));
            $limit_date = date("Y-m-d H:i:s", substr($invoices, strlen($invoices)/2, strlen($invoices)/2));
            $user = Auth::user();
            $company = DB::table('companies')->find($user->id_company);
            $pdf_controller = new PDFController;
            return $pdf_controller->print_debit($inicial_date, $limit_date, $company ,"DEBIT");
        }
        return route('root');
    }

    /**
     * Display a listing of ISPC companies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_tax(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $company_controller = new CompanyController;
            $company_validate = $company_controller->validate_company($user->id_company);
            $company = Company::find($user->id_company);
            if($company === null){
                return back()->with([
                    'status' => 'Essa empresa nao existe!',
                ]);
            }
            $invoices = new Collection();
            if(!$request->filled('inicial_date') && !$request->filled('final_date')){
                $invoices_query = DB::table('companies')
                ->join('invoices', 'companies.id', '=', 'invoices.id_company')
                ->select('invoices.*')
                ->where('companies.id', 'like', $user->id_company)
                ->orderByDesc('invoices.created_at')->get();
                $i=0;
                foreach($invoices_query as $invoice_query){
                    $invoice = new stdClass;
                    $invoice->id = $invoice_query->id;
                    $invoice->number = $invoice_query->number;
                    $invoice->status = $invoice_query->status;
                    $invoice->created_at = $invoice_query->created_at;
                    $invoice->iva = $invoice_query->iva;
                    $invoice->incident = $invoice_query->price - $invoice_query->iva + $invoice_query->discount;
                    $invoice->price = $invoice_query->price;
                    if($invoice_query->client_type === 'SINGULAR'){
                        $client = DB::table('clients_singular')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name . ' ' . $client->surname;
                    }
                    if($invoice_query->client_type === 'ENTERPRISE'){
                        $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name;
                    }
                    $invoices[$i] = $invoice;
                    $i++;
                }
                $paginator = CollectionHelper::paginate($invoices, 30);
                return view ('pt.home.pages.report.tax', $user, ['invoices' => $paginator,
                    'company_type' => $company->type,
                    'logo' => $company_validate['company_logo']]);
            }
            if($request->filled('inicial_date')){
                $invoices_query = DB::table('companies')
                ->join('invoices', 'companies.id', '=', 'invoices.id_company')
                ->select('invoices.*')
                ->where('companies.id', 'like', $user->id_company)
                ->whereBetween('invoices.created_at', [$request['inicial_date'], now()])
                ->orderByDesc('invoices.created_at')->get();
                $i=0;
                foreach($invoices_query as $invoice_query){
                    $invoice = new stdClass;
                    $invoice->id = $invoice_query->id;
                    $invoice->number = $invoice_query->number;
                    $invoice->status = $invoice_query->status;
                    $invoice->created_at = $invoice_query->created_at;
                    $invoice->iva = $invoice_query->iva;
                    $invoice->incident = $invoice_query->price - $invoice_query->iva + $invoice_query->discount;
                    $invoice->price = $invoice_query->price;
                    if($invoice_query->client_type === 'SINGULAR'){
                        $client = DB::table('clients_singular')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name . ' ' . $client->surname;
                    }
                    if($invoice_query->client_type === 'ENTERPRISE'){
                        $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name;
                    }
                    $invoices[$i] = $invoice;
                    $i++;
                }
                $paginator = CollectionHelper::paginate($invoices, 30);
                return view ('pt.home.pages.report.tax', $user, ['invoices' => $paginator,
                    'company_type' => $company->type,
                    'logo' => $company_validate['company_logo']]);
            }
            if($request->filled('final_date')){
                $invoices_query = DB::table('companies')
                ->join('invoices', 'companies.id', '=', 'invoices.id_company')
                ->select('invoices.*')
                ->where('companies.id', 'like', $user->id_company)
                ->where('invoices.created_at', '<=', $request['final_date'])
                ->orderByDesc('invoices.created_at')->get();
                $i=0;
                foreach($invoices_query as $invoice_query){
                    $invoice = new stdClass;
                    $invoice->id = $invoice_query->id;
                    $invoice->number = $invoice_query->number;
                    $invoice->status = $invoice_query->status;
                    $invoice->created_at = $invoice_query->created_at;
                    $invoice->iva = $invoice_query->iva;
                    $invoice->incident = $invoice_query->price - $invoice_query->iva + $invoice_query->discount;
                    $invoice->price = $invoice_query->price;
                    if($invoice_query->client_type === 'SINGULAR'){
                        $client = DB::table('clients_singular')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name . ' ' . $client->surname;
                    }
                    if($invoice_query->client_type === 'ENTERPRISE'){
                        $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name;
                    }
                    $invoices[$i] = $invoice;
                    $i++;
                }
                $paginator = CollectionHelper::paginate($invoices, 30);
                return view ('pt.home.pages.report.tax', $user, ['invoices' => $paginator,
                    'company_type' => $company->type,
                    'logo' => $company_validate['company_logo']]);
            }
        }
        return redirect()->route('root');
    }


    /**
     * Show the report of sales.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_invoices_report(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $company_controller = new CompanyController;
            $company_validate = $company_controller->validate_company($user->id_company);
            $invoices = new Collection();
            if(!$request->filled('inicial_date') && !$request->filled('final_date')){
                $invoices_query = DB::table('companies')
                ->join('invoices', 'companies.id', '=', 'invoices.id_company')
                ->select('invoices.*')
                ->where('companies.id', 'like', $user->id_company)
                ->orderByDesc('invoices.created_at')->get();
                $i=0;
                foreach($invoices_query as $invoice_query){
                    $invoice = new stdClass;
                    $invoice->id = $invoice_query->id;
                    $invoice->number = $invoice_query->number;
                    $invoice->status = $invoice_query->status;
                    $invoice->created_at = $invoice_query->created_at;
                    $invoice->price = $invoice_query->price;
                    if($invoice_query->client_type === 'SINGULAR'){
                        $client = DB::table('clients_singular')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name . ' ' . $client->surname;
                    }
                    if($invoice_query->client_type === 'ENTERPRISE'){
                        $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name;
                    }
                    $invoices[$i] = $invoice;
                    $i++;
                }
                $paginator = CollectionHelper::paginate($invoices, 30);
                return view ('pt.home.pages.report.invoice_report', $user, ['invoices' => $paginator,
                    'logo' => $company_validate['company_logo']]);
            }
            if($request->filled('inicial_date')){
                $invoices_query = DB::table('companies')
                ->join('invoices', 'companies.id', '=', 'invoices.id_company')
                ->select('invoices.*')
                ->where('companies.id', 'like', $user->id_company)
                ->whereBetween('invoices.created_at', [$request['inicial_date'], now()])
                ->orderByDesc('invoices.created_at')->get();
                $i=0;
                foreach($invoices_query as $invoice_query){
                    $invoice = new stdClass;
                    $invoice->id = $invoice_query->id;
                    $invoice->number = $invoice_query->number;
                    $invoice->status = $invoice_query->status;
                    $invoice->created_at = $invoice_query->created_at;
                    $invoice->price = $invoice_query->price;
                    if($invoice_query->client_type === 'SINGULAR'){
                        $client = DB::table('clients_singular')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name . ' ' . $client->surname;
                    }
                    if($invoice_query->client_type === 'ENTERPRISE'){
                        $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name;
                    }
                    $invoices[$i] = $invoice;
                    $i++;
                }
                $paginator = CollectionHelper::paginate($invoices, 30);
                return view ('pt.home.pages.report.invoice_report', $user, ['invoices' => $paginator,
                    'logo' => $company_validate['company_logo']]);
            }
            if($request->filled('final_date')){
                $invoices_query = DB::table('companies')
                ->join('invoices', 'companies.id', '=', 'invoices.id_company')
                ->select('invoices.*')
                ->where('companies.id', 'like', $user->id_company)
                ->where('invoices.created_at', '<=', $request['final_date'])
                ->orderByDesc('invoices.created_at')->get();
                $i=0;
                foreach($invoices_query as $invoice_query){
                    $invoice = new stdClass;
                    $invoice->id = $invoice_query->id;
                    $invoice->number = $invoice_query->number;
                    $invoice->status = $invoice_query->status;
                    $invoice->created_at = $invoice_query->created_at;
                    $invoice->price = $invoice_query->price;
                    if($invoice_query->client_type === 'SINGULAR'){
                        $client = DB::table('clients_singular')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name . ' ' . $client->surname;
                    }
                    if($invoice_query->client_type === 'ENTERPRISE'){
                        $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name;
                    }
                    $invoices[$i] = $invoice;
                    $i++;
                }
                $paginator = CollectionHelper::paginate($invoices, 30);
                return view ('pt.home.pages.report.invoice_report', $user, ['invoices' => $paginator,
                    'logo' => $company_validate['company_logo']]);
            }
        }
        return redirect()->route('root');
    }

    public function index_cash_sales_report(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $company_controller = new CompanyController;
            $company_validate = $company_controller->validate_company($user->id_company);
            $cash_sales = new Collection();
            if(!$request->filled('inicial_date') && !$request->filled('final_date')){
                $cash_sales_query = DB::table('companies')
                ->join('cash_sales', 'companies.id', '=', 'cash_sales.id_company')
                ->select('cash_sales.*')
                ->where('companies.id', 'like', $user->id_company)
                ->orderByDesc('cash_sales.created_at')->get();
                $i=0;
                foreach($cash_sales_query as $cash_sale_query){
                    $cash_sale = new stdClass;
                    $cash_sale->id = $cash_sale_query->id;
                    $cash_sale->number = $cash_sale_query->number;
                    $cash_sale->created_at = $cash_sale_query->created_at;
                    $cash_sale->price = $cash_sale_query->price;
                    if($cash_sale_query->client_type === 'SINGULAR'){
                        $client = DB::table('clients_singular')->find($cash_sale_query->id_client);
                        $cash_sale->client_name = $client->name . ' ' . $client->surname;
                    }
                    if($cash_sale_query->client_type === 'ENTERPRISE'){
                        $client = DB::table('clients_enterprise')->find($cash_sale_query->id_client);
                        $cash_sale->client_name = $client->name;
                    }
                    $cash_sales[$i] = $cash_sale;
                    $i++;
                }
                $paginator = CollectionHelper::paginate($cash_sales, 30);
                return view ('pt.home.pages.report.cash_sale_report', $user, ['cash_sales' => $paginator,
                    'logo' => $company_validate['company_logo']]);
            }
            if($request->filled('inicial_date')){
                $cash_sales_query = DB::table('companies')
                ->join('cash_sales', 'companies.id', '=', 'cash_sales.id_company')
                ->select('cash_sales.*')
                ->where('companies.id', 'like', $user->id_company)
                ->whereBetween('cash_sales.created_at', [$request['inicial_date'], now()])
                ->orderByDesc('cash_sales.created_at')->get();
                $i=0;
                foreach($cash_sales_query as $cash_sale_query){
                    $cash_sale = new stdClass;
                    $cash_sale->id = $cash_sale_query->id;
                    $cash_sale->number = $cash_sale_query->number;
                    $cash_sale->created_at = $cash_sale_query->created_at;
                    $cash_sale->price = $cash_sale_query->price;
                    if($cash_sale_query->client_type === 'SINGULAR'){
                        $client = DB::table('clients_singular')->find($cash_sale_query->id_client);
                        $cash_sale->client_name = $client->name . ' ' . $client->surname;
                    }
                    if($cash_sale_query->client_type === 'ENTERPRISE'){
                        $client = DB::table('clients_enterprise')->find($cash_sale_query->id_client);
                        $cash_sale->client_name = $client->name;
                    }
                    $cash_sales[$i] = $cash_sale;
                    $i++;
                }
                $paginator = CollectionHelper::paginate($cash_sales, 30);
                return view ('pt.home.pages.report.cash_sale_report', $user, ['cash_sales' => $paginator,
                    'logo' => $company_validate['company_logo']]);
            }
            if($request->filled('final_date')){
                $cash_sales_query = DB::table('companies')
                ->join('cash_sales', 'companies.id', '=', 'cash_sales.id_company')
                ->select('cash_sales.*')
                ->where('companies.id', 'like', $user->id_company)
                ->where('cash_sales.created_at', '<=', $request['final_date'])
                ->orderByDesc('cash_sales.created_at')->get();
                $i=0;
                foreach($cash_sales_query as $cash_sale_query){
                    $cash_sale = new stdClass;
                    $cash_sale->id = $cash_sale_query->id;
                    $cash_sale->number = $cash_sale_query->number;
                    $cash_sale->created_at = $cash_sale_query->created_at;
                    $cash_sale->price = $cash_sale_query->price;
                    if($cash_sale_query->client_type === 'SINGULAR'){
                        $client = DB::table('clients_singular')->find($cash_sale_query->id_client);
                        $cash_sale->client_name = $client->name . ' ' . $client->surname;
                    }
                    if($cash_sale_query->client_type === 'ENTERPRISE'){
                        $client = DB::table('clients_enterprise')->find($cash_sale_query->id_client);
                        $cash_sale->client_name = $client->name;
                    }
                    $cash_sales[$i] = $cash_sale;
                    $i++;
                }
                $paginator = CollectionHelper::paginate($cash_sales, 30);
                return view ('pt.home.pages.report.cash_sale_report', $user, ['cash_sales' => $paginator,
                    'logo' => $company_validate['company_logo']]);
            }
        }
        return redirect()->route('root');
    }

    /**
     * Show the form for filter paid sales.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_credit(Request $request)
    {
        $user = Auth::user();
        $company_controller = new CompanyController;
        $company_validate = $company_controller->validate_company($user->id_company);
        $invoices = new Collection();
        if(!$request->filled('inicial_date') && !$request->filled('final_date')){
            $invoices_query = DB::table('companies')
            ->join('invoices', 'companies.id', '=', 'invoices.id_company')
            ->select('invoices.*')
            ->where('companies.id', 'like', $user->id_company)
            ->where('invoices.status', 'like', 'PAID')
            ->orderByDesc('invoices.created_at')->get();
            $i=0;
            foreach($invoices_query as $invoice_query){
                $invoice = new stdClass;
                $invoice->id = $invoice_query->id;
                $invoice->number = $invoice_query->number;
                $invoice->created_at = $invoice_query->created_at;
                $invoice->price = $invoice_query->price;
                if($invoice_query->client_type === 'SINGULAR'){
                    $client = DB::table('clients_singular')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name . ' ' . $client->surname;
                }
                if($invoice_query->client_type === 'ENTERPRISE'){
                    $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name;
                }
                $invoices[$i] = $invoice;
                $i++;
            }
            $paginator = CollectionHelper::paginate($invoices, 30);
            return view ('pt.home.pages.credit.credit', $user, ['invoices' => $paginator,
                'logo' => $company_validate['company_logo']]);
        }
        if($request->filled('inicial_date')){
            $invoices_query = DB::table('companies')
            ->join('invoices', 'companies.id', '=', 'invoices.id_company')
            ->select('invoices.*')
            ->where('companies.id', 'like', $user->id_company)
            ->whereBetween('invoices.created_at', [$request['inicial_date'], now()])
            ->where('invoices.status', 'like', 'PAID')
            ->orderByDesc('invoices.created_at')->get();
            $i=0;
            foreach($invoices_query as $invoice_query){
                $invoice = new stdClass;
                $invoice->id = $invoice_query->id;
                $invoice->number = $invoice_query->number;
                $invoice->created_at = $invoice_query->created_at;
                $invoice->price = $invoice_query->price;
                if($invoice_query->client_type === 'SINGULAR'){
                    $client = DB::table('clients_singular')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name . ' ' . $client->surname;
                }
                if($invoice_query->client_type === 'ENTERPRISE'){
                    $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name;
                }
                $invoices[$i] = $invoice;
                $i++;
            }
            $paginator = CollectionHelper::paginate($invoices, 30);
            return view ('home.pages.credit.credit', $this->user, ['invoices' => $paginator,
                'logo' => $this->company_logo]);
        }
        if($request->filled('final_date')){
            $invoices_query = DB::table('companies')
            ->join('invoices', 'companies.id', '=', 'invoices.id_company')
            ->select('invoices.*')
            ->where('companies.id', 'like', $user->id_company)
            ->where('invoices.created_at', '<=', $request['final_date'])
            ->where('invoices.status', 'like', 'PAID')
            ->orderByDesc('invoices.created_at')->get();
            $i=0;
            foreach($invoices_query as $invoice_query){
                $invoice = new stdClass;
                $invoice->id = $invoice_query->id;
                $invoice->number = $invoice_query->number;
                $invoice->created_at = $invoice_query->created_at;
                $invoice->price = $invoice_query->price;
                if($invoice_query->client_type === 'SINGULAR'){
                    $client = DB::table('clients_singular')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name . ' ' . $client->surname;
                }
                if($invoice_query->client_type === 'ENTERPRISE'){
                    $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                    $invoice->client_name = $client->name;
                }
                $invoices[$i] = $invoice;
                $i++;
            }
            $paginator = CollectionHelper::paginate($invoices, 30);
            return view ('pt.home.pages.credit.credit', $user, ['invoices' => $paginator,
                'logo' => $company_validate['company_logo']]);
        }
    }

    /**
     * Show the form for filter non paid sales.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_debit(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $company_controller = new CompanyController;
            $company_validate = $company_controller->validate_company($user->id_company);
            $invoices = new Collection();
            if(!$request->filled('inicial_date') && !$request->filled('final_date')){
                $invoices_query = DB::table('companies')
                ->join('invoices', 'companies.id', '=', 'invoices.id_company')
                ->select('invoices.*')
                ->where('companies.id', 'like', $user->id_company)
                ->where('invoices.status', 'like', 'NOT PAID')
                ->orderByDesc('invoices.created_at')->get();
                $i=0;
                foreach($invoices_query as $invoice_query){
                    $invoice = new stdClass;
                    $invoice->id = $invoice_query->id;
                    $invoice->number = $invoice_query->number;
                    $invoice->created_at = $invoice_query->created_at;
                    $invoice->price = $invoice_query->price;
                    if($invoice_query->client_type === 'SINGULAR'){
                        $client = DB::table('clients_singular')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name . ' ' . $client->surname;
                    }
                    if($invoice_query->client_type === 'ENTERPRISE'){
                        $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name;
                    }
                    $invoices[$i] = $invoice;
                    $i++;
                }
                $paginator = CollectionHelper::paginate($invoices, 30);
                return view ('pt.home.pages.debit.debit', $user, ['invoices' => $paginator,
                    'logo' => $company_validate['company_logo']]);
            }
            if($request->filled('inicial_date')){
                $invoices_query = DB::table('companies')
                ->join('invoices', 'companies.id', '=', 'invoices.id_company')
                ->select('invoices.*')
                ->where('companies.id', 'like', $user->id_company)
                ->whereBetween('invoices.created_at', [$request['inicial_date'], now()])
                ->where('invoices.status', 'like', 'NOT PAID')
                ->orderByDesc('invoices.created_at')->get();
                $i=0;
                foreach($invoices_query as $invoice_query){
                    $invoice = new stdClass;
                    $invoice->id = $invoice_query->id;
                    $invoice->number = $invoice_query->number;
                    $invoice->created_at = $invoice_query->created_at;
                    $invoice->price = $invoice_query->price;
                    if($invoice_query->client_type === 'SINGULAR'){
                        $client = DB::table('clients_singular')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name . ' ' . $client->surname;
                    }
                    if($invoice_query->client_type === 'ENTERPRISE'){
                        $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name;
                    }
                    $invoices[$i] = $invoice;
                    $i++;
                }
                $paginator = CollectionHelper::paginate($invoices, 30);
                return view ('home.pages.debit.debit', $user, ['invoices' => $paginator,
                    'logo' => $company_validate['company_logo']]);
            }
            if($request->filled('final_date')){
                $invoices_query = DB::table('companies')
                ->join('invoices', 'companies.id', '=', 'invoices.id_company')
                ->select('invoices.*')
                ->where('companies.id', 'like', $user->id_company)
                ->where('invoices.created_at', '<=', $request['final_date'])
                ->where('invoices.status', 'like', 'NOT PAID')
                ->orderByDesc('invoices.created_at')->get();
                $i=0;
                foreach($invoices_query as $invoice_query){
                    $invoice = new stdClass;
                    $invoice->id = $invoice_query->id;
                    $invoice->number = $invoice_query->number;
                    $invoice->created_at = $invoice_query->created_at;
                    $invoice->price = $invoice_query->price;
                    if($invoice_query->client_type === 'SINGULAR'){
                        $client = DB::table('clients_singular')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name . ' ' . $client->surname;
                    }
                    if($invoice_query->client_type === 'ENTERPRISE'){
                        $client = DB::table('clients_enterprise')->find($invoice_query->id_client);
                        $invoice->client_name = $client->name;
                    }
                    $invoices[$i] = $invoice;
                    $i++;
                }
                $paginator = CollectionHelper::paginate($invoices, 30);
                return view ('pt.home.pages.debit.debit', $user, ['invoices' => $paginator,
                    'logo' => $company_validate['company_logo']]);
            }
        }
        return redirect()->route('root');
    }
}
