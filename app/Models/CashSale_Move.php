<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashSale_Move extends Model
{
    use SoftDeletes;
    protected $table = 'cash_sale_moves';
}
