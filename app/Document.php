<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function equipement(){
        return $this->belongsTo('App\Equipement');
    }
}
