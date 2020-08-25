<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    protected $table = 'services';
    protected $fillable = [
        'id', 'code', 'name', 'description', 
        'price', 'id_user'
    ];
}
