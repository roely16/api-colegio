<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gestion extends Model{

    protected $table = 'gestion';

    public function detalle_gestion(){

        return $this->hasMany('App\DetalleGestion');

    }

    public function alumno(){

        return $this->belongsTo('App\Alumno');

    }

}
