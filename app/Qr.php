<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    protected $table = 'qr';
    protected $fillable = [
        'alias', 
        'estado',
    ];
}
