<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{

    public function technicien()
    {
        return $this->belongTo('App\Technicien');
    }
    public function hotline()
    {
        return $this->belongTo('App\Hotline');
    }
    public function histAssemblage(){
        return $this->hasMany('App\HistAssemblage');
    }
    public function codePannes(){
        return $this->hasMany('App\CodePanneInHist');
    }
    
}
