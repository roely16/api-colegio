<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model{

    protected $table = 'cargo';
    
    public function personal(){

        return $this->hasMany('App\Persona');

    }

}
