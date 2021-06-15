<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Usuario;

class UsuarioController extends Controller{

    public function obtener_usuarios(){

        $usuarios = Usuario::all();

        $data_usuarios = [];

        foreach ($usuarios as $usuario) {
            
            $temp_usuario = [];

            $temp_usuario["id"] = $usuario->id;
            $temp_usuario["nombre"] = $usuario->persona->primer_nombre . ' ' . $usuario->persona->primer_apellido;
            $temp_usuario["usuario"] = $usuario->usuario;
            $temp_usuario["rol"] = $usuario->rol->nombre;
            $temp_usuario["icon"] = $usuario->rol->icon;

            $usuario->rol;
            $usuario->persona;

            $data_usuarios [] = $temp_usuario;

        }

        $headers = [
            [
                "text" => "Nombre", 
                "value" => "nombre"
            ],
            [
                "text" => "Usuario", 
                "value" => "usuario"
            ],
            [
                "text" => "Rol", 
                "value" => "rol"
            ],
            [
                "text" => "Acciones", 
                "value" => "action",
                "align" => "right",
                "sortable" => false
            ]
        ];

        $data = [
            "headers" => $headers,
            "items" => $data_usuarios
        ];

        return response()->json($data);

    }

}

?>