<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PDF\PDFController;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCPDF;

class ReportController extends Controller
{

    public function print_report($invoices){
        if(Auth::check()){
            $inicial_date = date("Y-m-d H:i:s", substr($invoices, 0, strlen($invoices)/2));
            $limit_date = date("Y-m-d H:i:s", substr($invoices, strlen($invoices)/2, strlen($invoices)/2));
            $user = Auth::user();
            $company = DB::table('companies')->find($user->id_company);
            $pdf_controller = new PDFController;
            return $pdf_controller->report($inicial_date, $limit_date, $company ,"REPORT");
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
            return $pdf_controller->report($inicial_date, $limit_date, $company ,"CREBIT");
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
            return $pdf_controller->print_report($inicial_date, $limit_date, $company ,"DEBIT");
        }
        return route('root');
    }

}
