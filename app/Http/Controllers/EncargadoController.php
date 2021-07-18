<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Encargado;

    class EncargadoController extends Controller{

        public function alumnos_encargado(Request $request){

            $encargado = Encargado::where('persona_id', $request->persona_id)->first();

            $alumnos = app('db')->select("  SELECT t3.*
                                            FROM alumno_encargado t1
                                            INNER JOIN alumno t2 
                                            ON t1.alumno_id = t2.id
                                            INNER JOIN persona t3
                                            ON t2.persona_id = t3.id
                                            WHERE t1.encargado_id = $encargado->id");

            return response()->json($alumnos);

        }

    }

?>