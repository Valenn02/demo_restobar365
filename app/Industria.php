<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Industria extends Model
{
    //
    protected $table = 'industrias';
    protected $fillable = ['nombre','estado'];
    public $timestamps = false;
}
