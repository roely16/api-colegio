<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Persona;
    use App\Alumno;
    use App\Gestion;
    use App\DetalleGestion;

    use App\Mail\GestionMail;
    use Illuminate\Support\Facades\Mail;

    class AlumnoController extends Controller{

        public function registrar_alumno(Request $request){

            $alumno = (object) $request->alumno;
            $encargado = (object) $request->encargado;

            // Crear a la persona

            $persona = new Persona();
            $persona->primer_nombre = $alumno->primer_nombre;
            $persona->segundo_nombre = $alumno->segundo_nombre;
            $persona->primer_apellido = $alumno->primer_apellido;
            $persona->segundo_apellido = $alumno->segundo_apellido;
            $persona->direccion = $alumno->direccion;
            $persona->email = $alumno->email;
            $persona->fecha_nacimiento = $alumno->fecha_nacimiento;
            $persona->telefono = $alumno->telefono;

            $persona->save();

            $persona_alumno = $persona->id;
            
            // Registrar al alumno
            $alumno_id = app('db')->table('alumno')->insertGetId([
                "persona_id" => $persona->id
            ]);

            // Crear al encargado

            $persona = new Persona();
            $persona->primer_nombre = $encargado->primer_nombre;
            $persona->primer_apellido = $encargado->primer_apellido;
            $persona->direccion = $encargado->direccion;
            $persona->email = $encargado->email;
            $persona->telefono = $encargado->telefono;
            $persona->save();

            // Registrar al encargado
            $encargado_id = app('db')->table('encargado')->insertGetId([
                "persona_id" => $persona->id,
            ]);    

            // Registrar al encargado junto al alumno

            $result = app('db')->table('alumno_encargado')->insert([
                "alumno_id" => $alumno_id,
                "encargado_id" => $encargado_id
            ]);

            // Crear la gestión de incripción del alumno
            $gestion = new Gestion();
            $gestion->tipo_gestion_id = 1;
            $gestion->alumno_id = $persona_alumno;
            $gestion->save();

            // Crear registro en el historial de la gestión
            $detalle_gestion = new DetalleGestion();
            $detalle_gestion->gestion_id = $gestion->id;
            $detalle_gestion->estado_id = 1;
            $detalle_gestion->save();

            // Enviar correo indicando que la gestión fue creada.
            $email_data = (object) [
                "alumno" => $alumno,
                "encargado" => $encargado,
                "gestion_id" => $gestion->id
            ];

            Mail::to('gerson.roely@gmail.com')->send(new GestionMail($email_data));

            // Retornar a la persona
            $persona = Persona::find($persona_alumno);

            $persona->nombre = $persona->primer_nombre . ' ' . $persona->segundo_nombre . ' ' . $persona->primer_apellido . ' ' . $persona->segundo_apellido;

            $response = [
                "message" => [
                    "title" => "Excelente!",
                    "text" => "El alumno a sido registrado.  Se ha generado la gestión No. " . $gestion->id,
                    "icon" => "success"
                ],
                "alumno" => $persona
            ];

            return response()->json($response);

        }

        public function obtener_alumnos(Request $request){
            
            $matricula = $request->matricula;
        
            if (!$matricula) {
                
                $alumnos = app('db')->select("  SELECT 
                                                t2.id,
                                                t2.primer_nombre,
                                                t2.segundo_nombre,
                                                t2.primer_apellido,
                                                t2.segundo_apellido,
                                                t2.email,
                                                t2.telefono
                                            FROM alumno t1
                                            INNER JOIN persona t2
                                            ON t1.persona_id = t2.id 
                                            WHERE t1.matricula IS NULL
                                        ");

            }else{

                $alumnos = app('db')->select("  SELECT 
                                                t2.id,
                                                t2.primer_nombre,
                                                t2.segundo_nombre,
                                                t2.primer_apellido,
                                                t2.segundo_apellido,
                                                t2.email,
                                                t2.telefono
                                            FROM alumno t1
                                            INNER JOIN persona t2
                                            ON t1.persona_id = t2.id 
                                            WHERE t1.matricula IS NOT NULL
                                        ");

            }

            foreach ($alumnos as &$alumno) {
                
                $alumno->nombre = $alumno->primer_nombre . ' ' . $alumno->segundo_nombre . ' ' . $alumno->primer_apellido . ' ' . $alumno->segundo_apellido;

            }

            $headers = [
                [
                    "text" => "ID",
                    "value" => "id",
                    "width" => "5%"
                ],
                [
                    "text" => "Nombre",
                    "value" => "nombre",
                    "width" => "30%"
                ],
                [
                    "text" => "Grado",
                    "value" => "grado",
                    "width" => "30%"
                ],
                [
                    "text" => "Email",
                    "value" => "email",
                    "width" => "15%"
                ],
                [
                    "text" => "Teléfono",
                    "value" => "telefono",
                    "width" => "15%"
                ],
                [
                    "text" => "Acciones",
                    "value" => "acciones",
                    "width" => "5%",
                    "align" => "end",
                    "sortable" => false
                ]
            ];

            $response = [
                "items" => $alumnos,
                "headers" => $headers
            ];

            return response()->json($response);

        }

    }

?>