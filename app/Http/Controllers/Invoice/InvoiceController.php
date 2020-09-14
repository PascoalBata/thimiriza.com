<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function invoice_payment(Request $request){
        if(Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $id = $request['id'];
            if(DB::table('invoices')
                ->where('id', $id)
                ->update(array(
                'status' => 'PAID',
                'id_user' => $user_id,
                'updated_at' => now()
            ))){
                    return redirect()->route('view_debit')->with('debit_notification', 'Pagamento da factura efectuado com sucesso.');
                }
            return redirect()->route('view_debit')->with('debit_notification', 'Falhou! Ocorreu um erro durante o pagamento da facuta.');
        }
        return route('root');
    }
}
