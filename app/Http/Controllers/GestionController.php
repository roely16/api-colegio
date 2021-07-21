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

        public function gestiones_alumno(Request $request){

            $gestiones = app('db')->select("    SELECT t1.*, t2.nombre as gestion
                                                FROM gestion t1
                                                INNER JOIN tipo_gestion t2
                                                ON t1.tipo_gestion_id = t2.id
                                                WHERE alumno_id = '$request->alumno_id'");

            $headers = [
                [
                    "text" => "ID",
                    "value" => "id",
                    "width" => "20%",
                    "sortable" => false
                ],
                [
                    "text" => "Gestión",
                    "value" => "gestion",
                    "width" => "30%",
                    "sortable" => false
                ],
                [
                    "text" => "Fecha",
                    "value" => "created_at",
                    "width" => "30%",
                    "sortable" => false
                ],
                [
                    "text" => "Acciones",
                    "value" => "action",
                    "width" => "20%",
                    "sortable" => false,
                    "align" => "right"
                ],
            ];

            $response = [
                "items" => $gestiones,
                "headers" => $headers 
            ];

            return response()->json($response);

        }

    }

?>