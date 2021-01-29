<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note_Move extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'note_moves';
}
