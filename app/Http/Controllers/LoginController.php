<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Usuario;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
       
    }

   
    public function login(Request $request){

        $usuario = Usuario::where('usuario', $request->user)->first();

        if(!$usuario){

            return response()->json([
                "status" => 100,
                "message" => "Usuario o contraseña incorrectas"
            ]);

        }

        $decrypt_pass = Crypt::decrypt($usuario->password);

        if ($request->pass != $decrypt_pass) {
            
            return response()->json([
                "status" => 100,
                "message" => "Usuario o contraseña incorrectas"
            ]);

        }

        $user_data = [
            "usuario_id" => $usuario->id,
            "usuario" => $usuario->usuario,
            "dark" => $usuario->dark,
            "persona" => $usuario->persona,
            "rol" => $usuario->rol
        ];

        return response()->json($user_data);

    }

    public function obtener_menu(Request $request){

        $usuario = Usuario::find($request->usuario_id);

        return response()->json($usuario->menu);

    }

}
