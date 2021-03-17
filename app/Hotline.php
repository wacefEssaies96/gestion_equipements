<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hotline extends Model
{
    protected $guarded = [];
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function historique(){
        return $this->hasMany('App\Historique');
    }
}
