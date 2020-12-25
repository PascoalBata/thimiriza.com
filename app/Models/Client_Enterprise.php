<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client_Enterprise extends Model
{
    //
    use SoftDeletes;
    protected $table = 'clients_enterprise';
}
