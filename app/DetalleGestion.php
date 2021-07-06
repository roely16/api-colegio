<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleGestion extends Model{

    protected $table = 'detalle_gestion';

    public function gestion(){

        return $this->belongsTo('App\Gestion');

    }

}
