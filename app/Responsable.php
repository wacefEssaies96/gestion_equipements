<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Responsable extends Model
{  
    protected $guarded = [];
    public function user(){
        return $this->belongsTo('App\User');
    }
}
