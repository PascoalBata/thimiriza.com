<?php

namespace App\Http\Controllers\InvoiceNote;

use App\Helpers\Collection\CollectionHelper;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PDF\PDFController;
use App\Models\Client_Enterprise;
use App\Models\Client_Singular;
use App\Models\Invoice;
use App\Models\InvoiceNote;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class InvoiceNoteController extends Controller
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
            $isAdmin = true;
            if($user->privilege !== 'ADMIN'){
                $isAdmin = false;
            }
            $notes_data = new Collection();
            $i = 0;
            $notes = DB::table('invoice_notes')->join('invoices', 'invoice_notes.id_invoice', '=', 'invoices.id')
            ->select('invoices.id as invoice_id', 'invoice_notes.id as note_id', 'invoices.price', 'invoices.client_type',
            'invoices.id_client', 'invoices.created_at', 'invoices.number',
            'invoice_notes.description', 'invoice_notes.type', 'invoice_notes.value')
            ->where('id_company', $user->id_company)
            ->whereNull('invoice_notes.deleted_at')->get();
            foreach($notes as $note){
                $data = new stdClass;
                $data->invoice_number = date('Y', strtotime($note->created_at)) . '/' . $note->number;
                $data->invoice_id = $note->invoice_id; //invoice id
                $data->note_id = $note->note_id; //invoice_note id
                $data->price = $note->price; //invoice price
                $data->value = $note->value; //invoice_note value
                $data->type = $note->type;
                $data->client_name = '';
                    $data->client_type = '';
                if($note->client_type === 'SINGULAR'){
                    $client = Client_Singular::find($note->id_client);
                    $data->client_name = '-';
                    $data->client_type = 'SINGULAR';
                    if($client !== null){
                        $data->client_name = $client->name . ' ' . $client->surname;
                    }
                }
                if($note->client_type === 'ENTERPRISE'){
                    $client = Client_Enterprise::find($note->id_client);
                    $data->client_name = '-';
                    $data->client_type = 'ENTERPRISE';
                    if($client !== null){
                        $data->client_name = $client->name;
                    }
                }
                $data->description = $note->description;
                $notes_data[$i] = $data;
                $i = $i + 1;
            }
            $paginator = CollectionHelper::paginate($notes_data, 1);
            return view ('pt.home.pages.invoice_note.invoice_note', $user,
            [
                'company_type' => $company_validate['company_type'],
                'logo' => $company_validate['company_logo'],
                'deadline_payment' =>  $company_validate['expire_msg'],
                'isAdmin' => $isAdmin,
                'is_edit' => false,
                'is_index' => true,
                'notes_data' => $paginator,
                'is_destroy' => false]);
        }
        return redirect()->route('root');
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
                'is_edit' => false,
                'is_index' => false,
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
            $serie_number = explode('/', $request['invoice_number']);
            $serie = intval($serie_number[0]);
            $number = intval($serie_number[1]);
            if($number === 0) {
                return back()->with([
                    'operation_status' => 'As facturas Ano/0 sao inaceitaveis',
                ]);
            }
            $invoice = Invoice::select('*')
            ->where('id_company', $user->id_company)
            ->where('number', $number)
            ->whereYear('created_at', $serie)->first();
            if($invoice !== null){
                $invoice_note = new InvoiceNote;
                $invoice_note->id_invoice = $invoice->id;
                $invoice_note->description = $request['description'];
                $invoice_note->value = $request['value'];
                $invoice_note->type = $request['type'];
                $invoice_note->created_by = $user->id;
                if($invoice_note->save()){
                    if($request['type'] === 'DEBIT'){
                        return redirect()->route('view_invoice_note')->with('operation_status', 'Nota de crédito criada com sucesso.');
                    }
                    if($request['type'] === 'CREDIT'){
                        return redirect()->route('view_invoice_note')->with('operation_status', 'Nota de dédito criada com sucesso.');
                    }
                }
                return back()->with([
                    'operation_status' => 'operation_status', 'Nao foi possivel criar a nota. Ocorreu um erro.',
                ]);
            }
            return back()->with([
                'operation_status' => 'operation_status', 'A factura inserida nao existe',
            ]);
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
    public function print($id)
    {
        if(Auth::check()){
            $note = InvoiceNote::find($id);
            if($note === null){
                return back()->with('operation_status', 'Essa nota não existe.');
            }
            $invoice = Invoice::find($note->id_invoice);
            if($invoice === null){
                return back()->with('operation_status', 'A factura referenciada não existe.');
            }
            $note->invoice_number = date('Y', strtotime($invoice->created_at)) . '/' . $invoice->number;
            $client = new stdClass;
            if($invoice->client_type === 'ENTERPRISE'){
                $client = Client_Enterprise::find($invoice->id_client);
            }
            if($invoice->client_type === 'SINGULAR'){
                $client = Client_Singular::find($invoice->id_client);
            }
            if($client === null){
                return back()->with('operation_status', 'Cliente não identificado.');
            }
            $user = User::find($note->created_by);
            $company = DB::table('companies')->find($user->id_company);
            $pdf_controller = new PDFController;
            return $pdf_controller->print_note($company, $user, $note, $client);
        }

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
            $selected_note = InvoiceNote::find($id);
            if($selected_note === null){
                return back()->route('view_invoice_note')->with('operation_status', 'Essa nota não existe.');
            }
            $selected_note_invoice = Invoice::find($selected_note->id_invoice);
            if($selected_note_invoice === null){
                return back()->with('operation_status', 'A factura referenciada não existe.');
            }
            $selected_note->invoice_number = date('Y', strtotime($selected_note_invoice->created_at)) . '/' . $selected_note_invoice->number;
            $user = Auth::user();
            $company_controller = new CompanyController;
            $company_validate = $company_controller->validate_company($user->id_company);
            $isAdmin = true;
            if($user->privilege !== 'ADMIN'){
                $isAdmin = false;
            }
            $notes_data = new Collection();
            $i = 0;
            $notes = DB::table('invoice_notes')->join('invoices', 'invoice_notes.id_invoice', '=', 'invoices.id')
            ->select('invoices.id as invoice_id', 'invoice_notes.id as note_id', 'invoices.price', 'invoices.client_type',
            'invoices.id_client', 'invoices.created_at', 'invoices.number',
            'invoice_notes.description', 'invoice_notes.type', 'invoice_notes.value')
            ->where('id_company', $user->id_company)
            ->whereNull('invoice_notes.deleted_at')->get();
            foreach($notes as $note){
                $data = new stdClass;
                $data->invoice_number = date('Y', strtotime($note->created_at)) . '/' . $note->number;
                $data->invoice_id = $note->invoice_id; //invoice id
                $data->note_id = $note->note_id; //invoice_note id
                $data->price = $note->price; //invoice price
                $data->value = $note->value; //invoice_note value
                $data->type = $note->type;
                $data->client_name = '';
                    $data->client_type = '';
                if($note->client_type === 'SINGULAR'){
                    $client = Client_Singular::find($note->id_client);
                    $data->client_name = '-';
                    $data->client_type = 'SINGULAR';
                    if($client !== null){
                        $data->client_name = $client->name . ' ' . $client->surname;
                    }
                }
                if($note->client_type === 'ENTERPRISE'){
                    $client = Client_Enterprise::find($note->id_client);
                    $data->client_name = '-';
                    $data->client_type = 'ENTERPRISE';
                    if($client !== null){
                        $data->client_name = $client->name;
                    }
                }
                $data->description = $note->description;
                $notes_data[$i] = $data;
                $i = $i + 1;
            }
            $paginator = CollectionHelper::paginate($notes_data, 1);
            return view ('pt.home.pages.invoice_note.invoice_note', $user,
            [
                'company_type' => $company_validate['company_type'],
                'logo' => $company_validate['company_logo'],
                'deadline_payment' =>  $company_validate['expire_msg'],
                'isAdmin' => $isAdmin,
                'selected_note' => $selected_note,
                'is_edit' => true,
                'is_index' => true,
                'notes_data' => $paginator,
                'is_destroy' => false]);
        }
        return redirect()->route('root');
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
            $invoice_note = InvoiceNote::find($id);
            if($invoice_note === null){
                return redirect()->route('view_invoice_note')->with('operation_status', 'Essa nota não existe');
            }
            $invoice_note->id_invoice = $request['edit_invoice_id'];
            $invoice_note->description = $request['edit_description'];
            $invoice_note->value = $request['edit_value'];
            $invoice_note->type = $request['edit_type'];
            $invoice_note->updated_by = $user->id;
            if($invoice_note->save()){
                return redirect()->route('edit_invoice_note', $id)->with('operation_status', 'Nota actualizada com sucesso.');
            }
            return redirect()->route('edit_invoice_note', $id)->with('operation_status', 'Falhou! Ocorreu um erro durante a actualizaçåo da nota.');
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
            $invoice_note = InvoiceNote::find($id);
            if($invoice_note->delete()){
                $invoice_note->updated_by = $user->id;
                $invoice_note->save();
                return redirect()->route('view_invoice_note', $id)->with('operation_status', 'Nota removida com sucesso.');
            }
            return redirect()->route('view_invoice_note', $id)->with('operation_status', 'Falhou! Ocorreu um erro durante o processo da remoção.');
        }
    return route('root');
    }
}
