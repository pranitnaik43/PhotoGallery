<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photos extends Model
{
    protected $table = 'photos';  
    public $primaryKey = 'id';  
    public $timestamps = false;
    public $incrementing =true;
}
