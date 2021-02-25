<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Move extends Model
{
    //use HasFactory;
    use SoftDeletes;
    protected $table = 'moves';
}
