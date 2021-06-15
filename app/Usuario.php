<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model{

    protected $table = 'usuario';
    
    public function rol(){

        return $this->belongsTo('App\Rol');

    }

    public function persona(){

        return $this->belongsTo('App\Persona');

    }

    public function menu(){

        return $this->belongsToMany('App\Menu');

    }

}
