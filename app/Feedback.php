<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    public function production(){
        return $this->belongsTo('App\Production');
    }
}