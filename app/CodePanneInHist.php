<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CodePanneInHist extends Model
{
    public function historique()
    {
        return $this->belongTo('App\Historique');
    }
}
