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
use App\Models\Move;
use App\Models\Note_Move;
use App\Models\Product;
use App\Models\Service;
use App\Models\Temp_Note;
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
            $series = [];
            $hasTemp_notes = false;
            $hasInvoices = false;
            $actual_invoice = new stdClass; //the selected invoice that's being created an invoice_note
            $actual_invoice->serie_number = '';
            $invoice_id = 0;
            $items = [];
            $invoice_products_services = null; //Products and services of specific invoice
            $total_invoices = 0;
            if($user->privilege !== 'ADMIN'){
                $isAdmin = false;
            }
            $notes_data = new Collection();
            $i = 0;
            $notes = DB::table('invoice_notes')->join('invoices', 'invoice_notes.id_invoice', '=', 'invoices.id')
            ->select('invoices.id as invoice_id', 'invoice_notes.id as note_id', 'invoices.price', 'invoices.client_type',
            'invoices.id_client', 'invoices.created_at', 'invoices.number', 'invoice_notes.type', 'invoice_notes.value')
            ->where('invoices.id_company', $user->id_company)
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
                //$data->description = $note->description;
                $notes_data[$i] = $data;
                $i = $i + 1;
            }
            $paginator = CollectionHelper::paginate($notes_data, 30);
            return view ('pt.home.pages.invoice_note.index', $user,
            [
                'company_type' => $company_validate['company_type'],
                'logo' => $company_validate['company_logo'],
                'deadline_payment' =>  $company_validate['expire_msg'],
                'isAdmin' => $isAdmin,
                'is_edit' => false,
                'is_index' => true,
                'notes_data' => $paginator,
                'actual_serie_number' => $actual_invoice->serie_number,
                'invoices_series' => $series,
                'is_destroy' => false]);
        }
        return redirect()->route('root');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $selected_invoice
     * @return \Illuminate\Http\Response
     */
    public function select_invoice($selected_invoice)
    {
        if(Auth::check()){
            $selected_invoice = urldecode( urldecode( $selected_invoice ));
            $split_invoice = explode('/', $selected_invoice);
            $invoice_year = $split_invoice[0];
            $invoice_number = $split_invoice[1];
            $invoice = Invoice::where('number', $invoice_number)->whereYear('created_at', $invoice_year)->first();
            $invoice_id = $invoice->id;
            if(strlen($invoice_year) < 4){
                return back()->with('operation_status', 'Factura inexistente.');
            }
            $user = Auth::user();
            $company_controller = new CompanyController;
            $company_validate = $company_controller->validate_company($user->id_company);
            $isAdmin = true;
            $series = [];
            $hasTemp_notes = false;
            $hasInvoices = false;
            $actual_invoice = new stdClass; //the selected invoice that's being created an invoice_note
            $actual_invoice->serie_number = '';
            $items = [];
            $invoice_products_services = null; //Products and services of specific invoice
            $total_invoices = 0;
            if($user->privilege !== 'ADMIN'){
                $isAdmin = false;
            }
            $temp_notes = DB::table('companies')->select('temp_notes.*')
            ->join('temp_notes', 'companies.id', '=', 'temp_notes.id_company')
            ->where('created_by',  $user->id)->get();
            if($temp_notes->count() > 0){
                $hasTemp_notes = true;
                $actual_invoice = $temp_notes[0];
                $invoice = Invoice::find($actual_invoice->id_invoice);
                if($invoice !== null){
                    $actual_invoice->serie_number = date('Y', strtotime($invoice->created_at)) . '/' . $invoice->number;
                    $invoice_products_services = Move::select('id_product_service', 'sale_type')
                    ->where('id_invoice', $actual_invoice->id_invoice)->get();
                }
                foreach($temp_notes as $item){
                    $item->name_description = '';
                    if($item->type_product_service === 'PRODUCT'){
                        $product = Product::find($item->id_product_service);
                        if($product !== null){
                            $item->name_description = $product->name . ' === ' . $product->description;
                        }
                    }
                    if($item->type_product_service === 'SERVICE'){
                        $service = Service::find($item->id_product_service);
                        if($service !== null){
                            $item->name_description = $service->name . ' === ' . $service->description;
                        }
                    }
                }
            }else{
                foreach(Invoice::select('number', 'created_at')->get() as $serie){
                    $series[$total_invoices] = date('Y', strtotime($serie->created_at)) . '/' . $serie->number;
                    $total_invoices = $total_invoices + 1;
                }
                if($total_invoices > 0){
                    $hasInvoices = true;
                }
            }
            $products = Move::select('product_service', 'description', 'id_product_service')->where('id_invoice', $invoice_id)->where('sale_type', 'like', 'PRODUCT')->get();
            $services = Move::select('product_service', 'description', 'id_product_service')->where('id_invoice', $invoice_id)->where('sale_type', 'like', 'SERVICE')->get();
            return view ('pt.home.pages.invoice_note.invoice_note', $user,
            [
                'company_type' => $company_validate['company_type'],
                'logo' => $company_validate['company_logo'],
                'deadline_payment' =>  $company_validate['expire_msg'],
                'isAdmin' => $isAdmin,
                'products' => $products,
                'services' => $services,
                'hasTemp_notes' => $hasTemp_notes,
                'temp_notes' => $temp_notes,
                'actual_serie_number' => $selected_invoice,
                'invoices_series' => $series,
                'is_edit' => false,
                'is_index' => false,
                'is_destroy' => false]);
        }
        return redirect()->route('root');
    }
    public function create()
    {
        if(Auth::check()){

            $user = Auth::user();
            $company_controller = new CompanyController;
            $company_validate = $company_controller->validate_company($user->id_company);
            $isAdmin = true;
            $series = [];
            $hasTemp_notes = false;
            $hasInvoices = false;
            $actual_invoice = new stdClass; //the selected invoice that's being created an invoice_note
            $actual_invoice->serie_number = '';
            $invoice_id = 0;
            $items = [];
            $invoice_products_services = null; //Products and services of specific invoice
            $total_invoices = 0;
            if($user->privilege !== 'ADMIN'){
                $isAdmin = false;
            }
            $temp_notes = DB::table('companies')->select('temp_notes.*')
            ->join('temp_notes', 'companies.id', '=', 'temp_notes.id_company')
            ->where('created_by',  $user->id)->get();
            if($temp_notes->count() > 0){
                $selected_invoice = $temp_notes[0];
                $year = date('Y', strtotime($selected_invoice->created_at));
                $number = Invoice::find($selected_invoice->id_invoice)->number;
                return redirect()->route('select_invoice', urlencode(urlencode($year . '/' . $number)));

            }else{
                foreach(Invoice::select('number', 'created_at')->get() as $serie){
                    $series[$total_invoices] = date('Y', strtotime($serie->created_at)) . '/' . $serie->number;
                    $total_invoices = $total_invoices + 1;
                }
                if($total_invoices > 0){
                    $hasInvoices = true;
                }
            }
            $products = Move::select('product_service', 'description', 'id_product_service')->where('id_invoice', $invoice_id)->where('sale_type', 'like', 'PRODUCT')->get();
            $services = Move::select('product_service', 'description', 'id_product_service')->where('id_invoice', $invoice_id)->where('sale_type', 'like', 'SERVICE')->get();
            return view ('pt.home.pages.invoice_note.invoice_note', $user,
            [
                'company_type' => $company_validate['company_type'],
                'logo' => $company_validate['company_logo'],
                'deadline_payment' =>  $company_validate['expire_msg'],
                'isAdmin' => $isAdmin,
                'products' => $products,
                'services' => $services,
                'hasTemp_notes' => $hasTemp_notes,
                'temp_notes' => $temp_notes,
                'actual_serie_number' => $actual_invoice->serie_number,
                'invoices_series' => $series,
                'is_edit' => false,
                'is_index' => false,
                'is_destroy' => false]);
        }
        return redirect()->route('root');
    }

    public function addItem(Request $request)
    {
        if(Auth::check()){
            $selected_invoice = urldecode( urldecode( $request['invoice_number'] ));
            $split_invoice = explode('/', $selected_invoice);
            $invoice_year = $split_invoice[0];
            $invoice_number = $split_invoice[1];
            $invoice = Invoice::where('number', $invoice_number)->whereYear('created_at', $invoice_year)->first();
            $invoice_id = $invoice->id;
            if(strlen($invoice_year) < 4){
                return back()->with('operation_status', 'Factura inexistente.');
            }
            $user = Auth::user();
            $temp_notes = Temp_Note::where('id_invoice', $invoice_id)
            ->where('id_product_service', intval($request['product_service']))
            ->where('type_product_service', 'like', $request['product_service_type'])
            ->where('created_by', $user->id)
            ->get();
            if($temp_notes->count() === 1){
                //update if exists
                $temp_notes = Temp_Note::find($temp_notes[0]->id);
                $temp_notes->type_product_service = $request['product_service_type'];
                $temp_notes->id_product_service = $request['product_service'];
                $temp_notes->description = $request['description'];
                $temp_notes->value = $request['value'];
                $temp_notes->type = $request['type'];
                $temp_notes->id_invoice = $invoice_id;
                $temp_notes->id_company = $user->id_company;
                $temp_notes->created_by = $user->id;
                if($temp_notes->update()){
                    return redirect()->route('select_invoice', urlencode(urlencode($request['invoice_number'])))
                    ->with('operation_status', 'Item actualizado com sucesso');
                }
                return back()->with('operation_status', 'Ocorreu um erro ao actualizar o item');
            }else{
                //create new
                $temp_notes = new Temp_Note();
                $temp_notes->type_product_service = $request['product_service_type'];
                $temp_notes->id_product_service = $request['product_service'];
                $temp_notes->description = $request['description'];
                $temp_notes->value = $request['value'];
                $temp_notes->type = $request['type'];
                $temp_notes->id_invoice = $invoice_id;
                $temp_notes->id_company = $user->id_company;
                $temp_notes->created_by = $user->id;
                if($temp_notes->save()){
                    return redirect()->route('select_invoice', urlencode(urlencode($request['invoice_number'])))
                    ->with('operation_status', 'Item adicionado com sucesso');
                }
                return back()->with('operation_status', 'Ocorreu um erro ao adicionar o item');
            }
        }
        return redirect()->route('root');
    }

    /**
     * Clean a temp_notes resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function clean()
    {
        if(Auth::check()){
            $user = Auth::user();
            Temp_Note::where('created_by', $user->id)->forceDelete();
            return redirect()->route('view_invoice_note');
        }
        return redirect()->route('root');
    }

    /**
     * Store a newly created temp_notes in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if(Auth::check()){
            $user = Auth::user();
            $temp_notes = Temp_Note::where('created_by', $user->id)->get();
            if($temp_notes->count() === 0 ){
                return back();
            }else{
                $invoice = Invoice::find($temp_notes[0]->id_invoice);
                if($invoice === null){
                    return back()->with('operation_status', 'A factura referenciada nao existe.');
                }else{
                    $status = true;
                    $date_time = now();
                    $invoice_note = new InvoiceNote();
                    $invoice_note->value = Temp_Note::where('created_by', $user->id)->sum('value');
                    $invoice_note->type = $temp_notes[0]->type;
                    $invoice_note->id_invoice = $invoice->id;
                    $invoice_note->created_by = $user->id;
                    $invoice_note->created_at = $date_time;
                    if($invoice_note->save()){
                        foreach($temp_notes as $temp_note){
                            $move = Move::where('id_invoice', $invoice->id)
                            ->where('id_product_service', $temp_note->id_product_service)->first();
                            $note_move = new Note_Move();
                            $note_move->id_invoice_note = $invoice_note->id;
                            $note_move->description = $temp_note->description;
                            $note_move->value = $temp_note->value;
                            $note_move->type_product_service = $temp_note->type_product_service;
                            $note_move->product_service = $move->product_service;
                            $note_move->product_service_description = $move->description;
                            $note_move->id_product_service = $temp_note->id_product_service;
                            $note_move->created_at = $date_time;
                            if($note_move->save()){
                                //$status = true;
                            }else{
                                $status = false;
                            }
                        }
                        if($status){
                            $pdf_controller = new PDFController;
                            $company = DB::table('companies')->find($user->id_company);
                            $client = new stdClass;
                            if($invoice->client_type === 'ENTERPRISE'){
                                $client = Client_Enterprise::find($invoice->id_client);
                                $client->type = 'ENTERPRISE';
                            }
                            if($invoice->client_type === 'SINGULAR'){
                                $client = Client_Singular::find($invoice->id_client);
                                $client->type = 'SINGULAR';
                            }
                            if($client === null){
                                return back()->with('operation_status', 'Cliente não identificado.');
                            }
                            $items = Note_Move::where('id_invoice_note', $invoice_note->id)->get();
                            return $pdf_controller->print_note($company, $user, $invoice_note, $client, $items);
                        }else{
                            Note_Move::where('created_by', $user->id)
                            ->where('created_at', $date_time)
                            ->forceDelete();
                            return back()->with('operation_status', 'Ocorreu um erro durante a operacao.');
                        }
                    }

                }
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
                $client->type = 'ENTERPRISE';
            }
            if($invoice->client_type === 'SINGULAR'){
                $client = Client_Singular::find($invoice->id_client);
                $client->type = 'SINGULAR';
            }
            if($client === null){
                return back()->with('operation_status', 'Cliente não identificado.');
            }
            $items = Note_Move::where('id_invoice_note', $id)->get();
            $user = User::find($note->created_by);
            $company = DB::table('companies')->find($user->id_company);
            $pdf_controller = new PDFController;
            return $pdf_controller->print_note($company, $user, $note, $client, $items);
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
    public function remove_item($id)
    {
        if(Auth::check()){
            $user = Auth::user();
            $item_note = Temp_Note::find($id);
            if($item_note->delete()){
                return back()->with('operation_status', 'Item removido com sucesso.');
            }
            return back()->with('operation_status', 'Falhou! Ocorreu um erro durante o processo da remoção.');
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
                return back()->with('operation_status', 'Nota removida com sucesso.');
            }
            return back()->with('operation_status', 'Falhou! Ocorreu um erro durante o processo da remoção.');
        }
    return route('root');
    }
}
