<?php

namespace App\Http\Controllers\SystemMail;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Mail\SystemMail;
use Illuminate\Http\Request;
use Mail;

class SystemMailController extends Controller
{
    public function basic_email() {
        $data = array('name'=>"Virat Gandhi");

        Mail::send(['text'=>'mail'], $data, function($message) {
           $message->to('abc@gmail.com', 'Tutorials Point')->subject
              ('Laravel Basic Testing Mail');
           $message->from('xyz@gmail.com','Virat Gandhi');
        });
        echo "Basic Email Sent. Check your inbox.";
     }
     public function html_email() {
        $data = array('name'=>"Virat Gandhi");
        Mail::send('mail', $data, function($message) {
           $message->to('abc@gmail.com', 'Tutorials Point')->subject
              ('Laravel HTML Testing Mail');
           $message->from('xyz@gmail.com','Virat Gandhi');
        });
        echo "HTML Email Sent. Check your inbox.";
     }
     public function attachment_email() {
        $data = array('name'=>"Virat Gandhi");
        Mail::send('mail', $data, function($message) {
           $message->to('abc@gmail.com', 'Tutorials Point')->subject
              ('Laravel Testing Mail with Attachment');
           $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
           $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
           $message->from('xyz@gmail.com','Virat Gandhi');
        });
        echo "Email Sent with attachment. Check your inbox.";
     }

     /*
     public function quote_email($email, $name, $file) {
         $mail = new SystemMail;
         $mail->build($name, $email, $file);
         Mail::to([$email, $name])->send($mail);
     }
    */


     public function quote_email($email, $name, $file, $invoice_id, $value, $company_name) {
        $data = array(
            'name' => $name,
            'email' => $email,
            'value' => $value,
            'company_name' => $company_name
        );
        if(Mail::send('home.pages.mail.quote', $data, function($message) use($email, $name, $file, $value, $company_name) {
           $message->to($email, $name)->subject('Cotação');
           $message->attachData($file, 'Cotação.pdf', [
                                'mime' => 'application/pdf',
                            ]);
           $message->from('noreply@thimiriza.com','Thimiriza');
        })){
            //Email sent
        }
        //return HomeController::view_sale();
     }

     public function invoice_email($email, $name, $file, $invoice_id, $value, $company_name) {
        $data = array(
            'name' => $name,
            'email' => $email,
            'invoice_id' => $invoice_id,
            'value' => $value,
            'company_name' => $company_name
        );
        if(Mail::send('home.pages.mail.invoice', $data, function($message) use($email, $name, $file, $invoice_id, $value, $company_name) {
           $message->to($email, $name)->subject('Factura');
           $message->attachData($file, 'Factura.pdf', [
                                'mime' => 'application/pdf',
                            ]);
           $message->from('noreply@thimiriza.com','Thimiriza');
        })){
            //Email sent
        }
        //return HomeController::view_sale();
     }

}
