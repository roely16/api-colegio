<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model{

    protected $table = 'menu';
    
    public function roles(){

        return $this->belongsToMany('App\Rol');

    }

    public function usuarios(){

        return $this->belongsToMany('App\Usuario');

    }

}
