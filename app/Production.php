<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $guarded = [];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function feedbacks(){
        return $this->hasMany('App\Feedback');
    }
}