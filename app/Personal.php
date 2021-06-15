<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model{

    protected $table = 'personal';

    public function persona(){

        return $this->belongsTo('App\Persona');

    }

    public function cargo(){

        return $this->belongsTo('App\Cargo');

    }
    
}
