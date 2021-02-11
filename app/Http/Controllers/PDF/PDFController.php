<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\Client_Singular;
use App\Models\Invoice;
use App\Models\Move;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use stdClass;

class PDFController extends Controller
{
    //PDF Invoice for sale
    public function invoice_generator(Array $data){
        if(Auth::check()){
            $user = Auth::user();
            $sales = Sale::where('created_by', $user->id)->get();
            $price_total = 0;
            $iva_total = 0;
            $price_inc_total = 0;
            $discount_total = 0;
            $sale_items = [];
            $i = 0;
            if($data['type'] === 'INVOICE'){
                $invoice = new Invoice;
                $invoice->client_type = $data['client_type'];
                $invoice->id_client = $data['client_id'];
                $invoice->status = 'NOT PAID';
                $invoice->number = $this->invoice_number_generator($user->id_company);
                $invoice->id_company = $user->id_company;
                $invoice->created_by = $user->id;
                $invoice->updated_by = $user->id;
                $invoice->created_at = now();
                $invoice->price = 0;
                $invoice->discount = 0;
                $invoice->price = 0;
                $invoice->iva = 0;
                if($invoice->save()){
                    foreach($sales as $sale){
                        $move = new Move;
                        $price_sale = 0;
                        $iva = 0;
                        $price_incident = 0;
                        if($sale->type === 'PRODUCT'){
                            $product = Product::find($sale->id_product_service);
                            if($product->quantity > 0){
                                $name = $product->name;
                                $description = $product->description;
                                $quantity = $sale->quantity;
                                $price = $product->price;
                                $price_incident = $product->price * $sale->quantity;
                                $iva = $price_incident * $sale->iva;
                                $discount = $price_incident * $sale->discount;
                                $price_sale = $price_incident - $discount + $iva;
                                if($product->quantity > $quantity){
                                    $move->sale_type = 'PRODUCT';
                                    $move->id_product_service = $product->id;
                                    $move->product_service = $name;
                                    $move->description = $description;
                                    $move->price = $price;
                                    $move->quantity = $quantity;
                                    $move->discount = $discount;
                                    $move->iva = $iva;
                                    $move->id_invoice = $invoice->id;
                                    $move->save();
                                    $product->quantity = $product->quantity - $quantity;
                                    $product->save();
                                    $sale_items [$i] = [
                                        'name' => $name,
                                        'description' => $description,
                                        'quantity' => $quantity,
                                        'price' => $price,
                                        'iva' => $sale->iva * 100,
                                        'discount' => $sale->discount * 100,
                                        'price_incident' => $price_incident,
                                        'price_sale' => $price_sale
                                    ];
                                }
                            }else{
                                $name = $product->name;
                                $description = $product->description;
                                Move::where('id_invoice', $invoice->id)->forceDelete();
                                Invoice::find($invoice->id)->forceDelete();
                                return back()->with('', 'O produto: ' . $name . ' ' . $description . ' esgotou no stock.');
                            }
                        }
                        if($sale->type === 'SERVICE'){
                            $service = Service::find($sale->id_product_service);
                            $name = $service->name;
                            $description = $service->description;
                            $quantity = $sale->quantity;
                            $price = $service->price;
                            $price_incident = $service->price * $sale->quantity;
                            $iva = $price_incident * $sale->iva;
                            $discount = $price_incident * $sale->discount;
                            $price_sale = $price_incident - $discount + $iva;
                            $move->sale_type = 'SERVICE';
                            $move->id_product_service = $service->id;
                            $move->product_service = $name;
                            $move->description = $description;
                            $move->price = $price;
                            $move->quantity = $quantity;
                            $move->discount = $discount;
                            $move->iva = $iva;
                            $move->id_invoice = $invoice->id;
                            $move->save();
                            $sale_items [$i] = [
                                'name' => $name,
                                'description' => $description,
                                'quantity' => $quantity,
                                'price' => $price,
                                'iva' => $sale->iva * 100,
                                'discount' => $sale->discount * 100,
                                'price_incident' => $price_incident,
                                'price_sale' => $price_sale
                            ];
                        }
                        $i++;
                        $price_total = $price_total + $price_sale;
                        $price_inc_total = $price_inc_total + $price_incident;
                        $iva_total = $iva_total + $iva;
                        $discount_total = $discount_total + $discount;
                    }
                    $invoice->price = $price_total;
                    $invoice->discount = $discount_total;
                    $invoice->iva = $iva_total;
                    $invoice->save();
                    $pdf_invoice = PDF::loadView('pt.pdf.quote', [
                        'data' => $data,
                        'serie' => date('Y', strtotime($invoice->created_at)),
                        'sale_items' => $sale_items,
                        'price_total' => $price_total,
                        'price_incident_total' => $price_inc_total,
                        'iva_total' => $iva_total,
                        'invoice_number' => $invoice->number,
                        ]);
                        $pdf_invoice->setPaper('A4');
                    $pdf_invoice->setWarnings(false);
                    return $pdf_invoice->stream('Factura.pdf');
                }
                return redirect()->route('view_sale')->with('sale_notification', 'Falhou! Ocorreu um erro durante a operacao.');
            }
            if($data['type'] === 'QUOTE'){
                foreach($sales as $sale){
                    $price_sale = 0;
                    $iva = 0;
                    $price_incident = 0;
                    if($sale->type === 'PRODUCT'){
                        $product = Product::find($sale->id_product_service);
                        $name = $product->name;
                        $description = $product->description;
                        $quantity = $sale->quantity;
                        $price = $product->price;
                        $price_incident = $product->price * $sale->quantity;
                        $iva = $price_incident * $sale->iva;
                        $discount = $price_incident * $sale->discount;
                        $price_sale = $price_incident - $discount + $iva;
                        if($product->quantity > 0){
                            if($product->quantity > $quantity){
                                $sale_items [$i] = [
                                    'name' => $name,
                                    'description' => $description,
                                    'quantity' => $quantity,
                                    'price' => $price,
                                    'iva' => $sale->iva * 100,
                                    'discount' => $sale->discount * 100,
                                    'price_incident' => $price_incident,
                                    'price_sale' => $price_sale
                                ];
                            }
                        }
                    }
                    if($sale->type === 'SERVICE'){
                        $service = Service::find($sale->id_product_service);
                        $name = $service->name;
                        $description = $service->description;
                        $quantity = $sale->quantity;
                        $price = $service->price;
                        $price_incident = $service->price * $sale->quantity;
                        $iva = $price_incident * $sale->iva;
                        $discount = $price_incident * $sale->discount;
                        $price_sale = $price_incident - $discount + $iva;
                        $sale_items [$i] = [
                            'name' => $name,
                            'description' => $description,
                            'quantity' => $quantity,
                            'price' => $price,
                            'iva' => $sale->iva * 100,
                            'discount' => $sale->discount * 100,
                            'price_incident' => $price_incident,
                            'price_sale' => $price_sale
                        ];
                    }
                    $i++;
                    $price_total = $price_total + $price_sale;
                    $price_inc_total = $price_inc_total + $price_incident;
                    $iva_total = $iva_total + $iva;
                    $discount_total = $discount_total + $discount;
                }
                $pdf_quote = PDF::loadView('pt.pdf.quote', [
                    'data' => $data,
                    'serie' => date('Y'),
                    'sale_items' => $sale_items,
                    'price_total' => $price_total,
                    'price_incident_total' => $price_inc_total,
                    'iva_total' => $iva_total,
                    ]);
                    $pdf_quote->setPaper('A4');

                $pdf_quote->setWarnings(false);
                return $pdf_quote->stream('Cotacao.pdf');
            }
            return redirect()->route('view_sale')->with('sale_notification', 'Falhou! Ocorreu um erro durante a operacao.');
        }
        return redirect()->route('root');
    }

    //PDF Invoice for checking process
    public function check_invoice(Array $data){
        if(Auth::check()){
            $user = Auth::user();
            $moves = Move::where('id_invoice', $data['invoice_id'])->get();
            $price_total = 0;
            $iva_total = 0;
            $price_inc_total = 0;
            $discount_total = 0;
            $sale_items = [];
            $i = 0;
            if($data['type'] === 'INVOICE'){
                $invoice = Invoice::find($data['invoice_id']);
                if($invoice !== null){
                    foreach($moves as $move){
                        $price_sale = 0;
                        $iva = 0;
                        $discount = 0;
                        $price_incident = 0;
                        if($move->sale_type === 'PRODUCT'){
                                $name = $move->product_service;
                                $description = $move->description;
                                $quantity = $move->quantity;
                                $price = $move->price;
                                $price_incident = $move->price * $move->quantity;
                                $iva = $move->iva;
                                $discount = $move->discount;
                                $price_sale = $price_incident - $discount + $iva;
                                $sale_items [$i] = [
                                'name' => $name,
                                'description' => $description,
                                'quantity' => $quantity,
                                'price' => $price,
                                'iva' => $move->iva *100 / $price_incident,
                                'discount' => $move->discount * 100 / $price_incident,
                                'price_incident' => $price_incident,
                                'price_sale' => $price_sale
                            ];
                        }
                        if($move->sale_type === 'SERVICE'){
                            $name = $move->product_service;
                            $description = $move->description;
                            $quantity = $move->quantity;
                            $price = $move->price;
                            $price_incident = $move->price * $move->quantity;
                            $iva = $move->iva;
                            $discount = $move->discount;
                            $price_sale = $price_incident - $discount + $iva;
                            $sale_items [$i] = [
                                'name' => $name,
                                'description' => $description,
                                'quantity' => $quantity,
                                'price' => $price,
                                'iva' => $move->iva,
                                'iva' => $move->iva *100 / $price_incident,
                                'discount' => $move->discount * 100 / $price_incident,
                                'price_sale' => $price_sale,
                                'price_incident' => $price_incident
                            ];
                        }
                        $i++;
                        $price_total = $price_total + $price_sale;
                        $price_inc_total = $price_inc_total + $price_incident;
                        $iva_total = $iva_total + $iva;
                        $discount_total = $discount_total + $discount;
                    }
                    $invoice->price = $price_total;
                    $invoice->save();
                    $pdf_invoice = PDF::loadView('pt.pdf.quote', [
                        'data' => $data,
                        'serie' => date('Y', strtotime($invoice->created_at)),
                        'sale_items' => $sale_items,
                        'price_total' => $price_total,
                        'price_incident_total' => $price_inc_total,
                        'iva_total' => $iva_total,
                        'invoice_number' => $invoice->number
                        ]);
                        $pdf_invoice->setPaper('A4');
                    $pdf_invoice->setWarnings(false);
                    return $pdf_invoice->stream('Factura.pdf');
                }
                return redirect()->route('view_debit')->with('sale_notification', 'Falhou! Ocorreu um erro durante a operacao.');
            }
            return redirect()->route('view_debit')->with('sale_notification', 'Falhou! Ocorreu um erro durante a operacao.');
        }
        return redirect()->route('root');
    }

    //print invoice note
    public function print_note($company, $user, $note, $client, $items){
        $pdf_invoice = PDF::loadView('pt.pdf.note', [
            'company' => $company,
            'user' => $user,
            'note' => $note,
            'items' => $items,
            'client' => $client
            ]);
            $pdf_invoice->setPaper('A4');
        $pdf_invoice->setWarnings(false);
        if($note->type === 'CREDIT'){
            return $pdf_invoice->stream('Nota de crédito.pdf');
        }
        if($note->type === 'DEBIT'){
            return $pdf_invoice->stream('Nota de dédito.pdf');
        }
        return back()->with('operation_status', 'Não foi possivel gerar o documento.pdf');
    }

    //PDF Report
    public function print_debit($inicial_date, $limit_date, Object $company, $type){
            if(Auth::check()){
            $user = Auth::user();
            $items = new stdClass;
            $i=0;
            if(date('Y-m-d', strtotime($inicial_date)) === date('Y-m-d', strtotime($limit_date))){
                $invoices = Invoice::whereDate('created_at', date('Y-m-d', strtotime($inicial_date)))->where('status', 'NOT PAID')
                ->orderByDesc('created_at')->get();
                if($invoices !== null){
                    $items = $invoices;
                    foreach($invoices as $invoice){
                        if($invoice->client_type === 'SINGULAR'){
                            $client = DB::table('clients_singular')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name . ' ' . $client->surname;
                        }
                        if($invoice->client_type === 'ENTERPRISE'){
                            $client = DB::table('clients_enterprise')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name;
                        }
                        $i=$i+1;
                    }
                    $pdf_invoice = PDF::loadView('pt.pdf.report',
                    [
                        'inicial_date' => $inicial_date,
                        'limit_date' => $limit_date,
                        'type' => $type,
                        'company' => $company,
                        'user' => $user,
                        'items' => $items
                        ]);
                        $pdf_invoice->setPaper('A4');
                    $pdf_invoice->setWarnings(false);
                    return $pdf_invoice->stream('Relatório.pdf');
                }
                return redirect()->route('view_debit');
            }else{
                $invoices = Invoice::whereBetween('created_at', [$inicial_date, $limit_date])->where('status', 'NOT PAID')
                ->orderByDesc('invoices.created_at')->get();
                if($invoices !== null){
                    $items = $invoices;
                    foreach($invoices as $invoice){
                        if($invoice->client_type === 'SINGULAR'){
                            $client = DB::table('clients_singular')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name . ' ' . $client->surname;
                        }
                        if($invoice->client_type === 'ENTERPRISE'){
                            $client = DB::table('clients_enterprise')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name;
                        }
                        $i=$i+1;
                    }
                    $pdf_invoice = PDF::loadView('pt.pdf.report',
                    [
                        'inicial_date' => $inicial_date,
                        'limit_date' => $limit_date,
                        'type' => $type,
                        'company' => $company,
                        'user' => $user,
                        'items' => $items
                        ]);
                        $pdf_invoice->setPaper('A4');
                    $pdf_invoice->setWarnings(false);
                    return $pdf_invoice->stream('Relatório.pdf');
                }
                return redirect()->route('view_debit');
            }
        }
        return redirect()->route('root');
    }

    public function print_credit($inicial_date, $limit_date, Object $company, $type){
            if(Auth::check()){
            $user = Auth::user();
            $items = new stdClass;
            $i=0;
            if(date('Y-m-d', strtotime($inicial_date)) === date('Y-m-d', strtotime($limit_date))){
                $invoices = Invoice::whereDate('created_at', date('Y-m-d', strtotime($inicial_date)))->where('status', 'PAID')
                ->orderByDesc('created_at')->get();
                if($invoices !== null){
                    $items = $invoices;
                    foreach($invoices as $invoice){
                        if($invoice->client_type === 'SINGULAR'){
                            $client = DB::table('clients_singular')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name . ' ' . $client->surname;
                        }
                        if($invoice->client_type === 'ENTERPRISE'){
                            $client = DB::table('clients_enterprise')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name;
                        }
                        $i=$i+1;
                    }
                    $pdf_invoice = PDF::loadView('pt.pdf.report',
                    [
                        'inicial_date' => $inicial_date,
                        'limit_date' => $limit_date,
                        'type' => $type,
                        'company' => $company,
                        'user' => $user,
                        'items' => $items
                        ]);
                        $pdf_invoice->setPaper('A4');
                    $pdf_invoice->setWarnings(false);
                    return $pdf_invoice->stream('Relatório.pdf');
                }
                return redirect()->route('view_debit');
            }else{
                $invoices = Invoice::whereBetween('created_at', [$inicial_date, $limit_date])->where('status', 'PAID')
                ->orderByDesc('invoices.created_at')->get();
                if($invoices !== null){
                    $items = $invoices;
                    foreach($invoices as $invoice){
                        if($invoice->client_type === 'SINGULAR'){
                            $client = DB::table('clients_singular')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name . ' ' . $client->surname;
                        }
                        if($invoice->client_type === 'ENTERPRISE'){
                            $client = DB::table('clients_enterprise')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name;
                        }
                        $i=$i+1;
                    }
                    $pdf_invoice = PDF::loadView('pt.pdf.report',
                    [
                        'inicial_date' => $inicial_date,
                        'limit_date' => $limit_date,
                        'type' => $type,
                        'company' => $company,
                        'user' => $user,
                        'items' => $items
                        ]);
                        $pdf_invoice->setPaper('A4');
                    $pdf_invoice->setWarnings(false);
                    return $pdf_invoice->stream('Relatório.pdf');
                }
                return redirect()->route('view_debit');
            }
        }
        return redirect()->route('root');
    }
    public function print_report($inicial_date, $limit_date, Object $company, $type){
        if(Auth::check()){
            $user = Auth::user();
            $items = new stdClass;
            $i=0;
            if(date('Y-m-d', strtotime($inicial_date)) === date('Y-m-d', strtotime($limit_date))){
                $invoices = Invoice::where('id_company', $user->id_company)
                ->whereDate('created_at', date('Y-m-d', strtotime($inicial_date)))
                ->orderByDesc('created_at')
                ->get();
                if($invoices !== null){
                    $items = $invoices;
                    foreach($invoices as $invoice){
                        if($invoice->client_type === 'SINGULAR'){
                            $client = DB::table('clients_singular')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name . ' ' . $client->surname;
                        }
                        if($invoice->client_type === 'ENTERPRISE'){
                            $client = DB::table('clients_enterprise')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name;
                        }
                        $i=$i+1;
                    }
                    $pdf_invoice = PDF::loadView('pt.pdf.report',
                    [
                        'inicial_date' => $inicial_date,
                        'limit_date' => $limit_date,
                        'type' => $type,
                        'company' => $company,
                        'user' => $user,
                        'items' => $items
                        ]);
                        $pdf_invoice->setPaper('A4');
                    $pdf_invoice->setWarnings(false);
                    return $pdf_invoice->stream('Relatório.pdf');
                }
                return back();
            }else{
                $invoices = Invoice::where('id_company', $user->id_company)
                ->whereBetween('created_at', [$inicial_date, $limit_date])
                ->orderByDesc('invoices.created_at')
                ->get();
                if($invoices !== null){
                    $items = $invoices;
                    foreach($invoices as $invoice){
                        if($invoice->client_type === 'SINGULAR'){
                            $client = DB::table('clients_singular')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name . ' ' . $client->surname;
                        }
                        if($invoice->client_type === 'ENTERPRISE'){
                            $client = DB::table('clients_enterprise')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name;
                        }
                        $i=$i+1;
                    }
                    $pdf_invoice = PDF::loadView('pt.pdf.report',
                    [
                        'inicial_date' => $inicial_date,
                        'limit_date' => $limit_date,
                        'type' => $type,
                        'company' => $company,
                        'user' => $user,
                        'items' => $items
                        ]);
                        $pdf_invoice->setPaper('A4');
                    $pdf_invoice->setWarnings(false);
                    return $pdf_invoice->stream('Relatório.pdf');
                }
                return back();
            }
        }
        return redirect()->route('root');
    }

    public function print_tax($inicial_date, $limit_date, Object $company, $type){
        if(Auth::check()){
            $user = Auth::user();
            $items = new stdClass;
            $i=0;
            if(date('Y-m-d', strtotime($inicial_date)) === date('Y-m-d', strtotime($limit_date))){
                $invoices = Invoice::where('id_company', $user->id_company)
                ->whereDate('created_at', date('Y-m-d', strtotime($inicial_date)))
                ->orderByDesc('created_at')
                ->get();
                if($invoices !== null){
                    $items = $invoices;
                    foreach($invoices as $invoice){
                        if($invoice->client_type === 'SINGULAR'){
                            $client = DB::table('clients_singular')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name . ' ' . $client->surname;
                        }
                        if($invoice->client_type === 'ENTERPRISE'){
                            $client = DB::table('clients_enterprise')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name;
                        }
                        $i=$i+1;
                    }
                    $pdf_invoice = PDF::loadView('pt.pdf.tax',
                    [
                        'inicial_date' => $inicial_date,
                        'limit_date' => $limit_date,
                        'type' => $type,
                        'company' => $company,
                        'user' => $user,
                        'items' => $items
                        ]);
                        $pdf_invoice->setPaper('A4');
                    $pdf_invoice->setWarnings(false);
                    return $pdf_invoice->stream('Taxas.pdf');
                }
                return back();
            }else{
                $invoices = Invoice::where('id_company', $user->id_company)
                ->whereBetween('created_at', [$inicial_date, $limit_date])
                ->orderByDesc('invoices.created_at')->get();
                if($invoices !== null){
                    $items = $invoices;
                    foreach($invoices as $invoice){
                        if($invoice->client_type === 'SINGULAR'){
                            $client = DB::table('clients_singular')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name . ' ' . $client->surname;
                        }
                        if($invoice->client_type === 'ENTERPRISE'){
                            $client = DB::table('clients_enterprise')->find($invoice->id_client);
                            $items[$i]->client_name =$client->name;
                        }
                        $i=$i+1;
                    }
                    $pdf_invoice = PDF::loadView('pt.pdf.tax',
                    [
                        'inicial_date' => $inicial_date,
                        'limit_date' => $limit_date,
                        'type' => $type,
                        'company' => $company,
                        'user' => $user,
                        'items' => $items
                        ]);
                        $pdf_invoice->setPaper('A4');
                    $pdf_invoice->setWarnings(false);
                    return $pdf_invoice->stream('Relatório.pdf');
                }
                return back();
            }
        }
        return redirect()->route('root');
    }

    private function invoice_number_generator($company_id){
        $number = 1;
        $serie = date("Y");
        $last_invoice = Invoice::select('number', 'created_at')
        ->where('id_company', $company_id)
        ->latest()
        ->first();
        if($last_invoice === null){
            return $number;
        }
        if(date('Y', strtotime($last_invoice->created_at)) === $serie){
            $number = $last_invoice->number + 1;
            return $number;
        }else{
            return $number;
        }
    }
}
