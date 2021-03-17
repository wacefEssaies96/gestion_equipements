<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistAssemblage extends Model
{
    public function historique(){
        return $this->belongsTo('App\Historique');
    }
}
