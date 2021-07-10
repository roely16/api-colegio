<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Gestion;
    use App\Persona;
    use App\Alumno;

    class GestionController extends Controller{

        public function detalle_gestion(Request $request){

            $gestion = Gestion::find($request->gestion);

            $detalle_gestion = app('db')->select("  SELECT t2.*, t1.created_at
                                                    FROM detalle_gestion t1
                                                    INNER JOIN estado_gestion t2
                                                    ON t1.estado_id = t2.id
                                                    WHERE t1.gestion_id = $gestion->id");

            $persona = Persona::find($gestion->alumno_id);

            $persona->nombre = $persona->primer_nombre . ' ' . $persona->segundo_nombre . ' ' . $persona->primer_apellido . ' ' . $persona->segundo_apellido;

            $response = [
                "gestion" => $gestion,
                "detalle_gestion" => $detalle_gestion,
                "persona" => $persona
            ];

            return response()->json($response);

        }

    }

?>