<?php

namespace App\Http\Controllers\InvoiceNote;

use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::check()){
            $user = Auth::user();
            $company_controller = new CompanyController;
            $company_validate = $company_controller->validate_company($user->id_company);
            $isAdmin = true;
            if($user->privilege !== 'ADMIN'){
                $isAdmin = false;
            }
            return view ('pt.home.pages.invoice_note.invoice_note', $user,
            [
                'company_type' => $company_validate['company_type'],
                'logo' => $company_validate['company_logo'],
                'deadline_payment' =>  $company_validate['expire_msg'],
                'isAdmin' => $isAdmin,
                'enable_sales' => $company_validate['make_sales'],
                'is_edit' => false,
                'is_destroy' => false]);
        }
        return redirect()->route('root');
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
            $invoice = Invoice::find($request['invoice_id']);
            if($invoice !== null){
                $invoice_note = new InvoiceNote;
                $invoice_note->id_invoice = $request['invoice_id'];
                $invoice_note->description = $request['description'];
                $invoice_note->value = $request['value'];
                $invoice_note->type = $request['type'];
                if($invoice_note->save()){
                    if($request['type'] === 'DEBIT'){
                        return redirect()->route('view_invoice_note')->with('operation_status', 'Nota de crédito criada com sucesso.');
                    }
                    if($request['type'] === 'CREDIT'){
                        return redirect()->route('view_invoice_note')->with('operation_status', 'Nota de dédito criada com sucesso.');
                    }
                }
                return redirect()->route('view_invoice_note')->with('operation_status', 'Nao foi possivel criar a nota. Ocorreu um erro.');
            }
            return redirect()->route('view_invoice_note')->with('operation_status', 'A factura inserida nao existe');
        }
        return redirect()->route('root');
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
        //
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
}
