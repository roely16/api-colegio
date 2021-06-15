<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model{

    protected $table = 'rol';
    
    public function usuarios(){

        return $this->hasMany('App\Usuario');

    }

    public function menu(){

        return $this->belongsToMany('App\Menu');

    }

}
