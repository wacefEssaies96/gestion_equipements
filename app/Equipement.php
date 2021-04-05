<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    protected $guarded = [];

    public function documents(){
        return $this->hasMany('App\Document');
    }
}
