<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Rol;

class RolController extends Controller{

    public function obtener_roles(){

        $roles = Rol::all();

        return response()->json($roles);

    }

}

?>