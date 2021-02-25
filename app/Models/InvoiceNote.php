<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceNote extends Model
{
    use SoftDeletes;
    protected $table = 'invoice_notes';
}
