<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Persona;
    use App\Alumno;
    use App\Gestion;
    use App\DetalleGestion;
    use App\EstadoGestion;
    use App\Usuario;
    use App\Encargado;
    use App\AlumnoEncargado;
    use App\MenuUsuario;
    use App\RolMenu;

    use App\Mail\GestionMail;
    use Illuminate\Support\Facades\Mail;

    use Illuminate\Support\Facades\Crypt;

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
            $gestion->alumno_id = $alumno_id;
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
                                                    t1.id,
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

                foreach ($alumnos as $alumno) {
                    
                    $gestion = Gestion::where('alumno_id', $alumno->id)->where('tipo_gestion_id', 1)->first();

                    $detalle_gestion = DetalleGestion::where('gestion_id', $gestion->id)->orderBy('id', 'desc')->first();
                    
                    $estado = EstadoGestion::find($detalle_gestion->estado_id);

                    $alumno->gestion = $gestion->id;
                    $alumno->estado = $estado->nombre;

                }

                $headers = [
                    [
                        "text" => "ID",
                        "value" => "id",
                        "width" => "5%"
                    ],
                    [
                        "text" => "Gestión",
                        "value" => "gestion",
                        "width" => "10%"
                    ],
                    [
                        "text" => "Nombre",
                        "value" => "nombre",
                        "width" => "30%"
                    ],
                    [
                        "text" => "Estado",
                        "value" => "estado",
                        "width" => "20%"
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

            }

            foreach ($alumnos as &$alumno) {
                
                $alumno->nombre = $alumno->primer_nombre . ' ' . $alumno->segundo_nombre . ' ' . $alumno->primer_apellido . ' ' . $alumno->segundo_apellido;

            }

            

            $response = [
                "items" => $alumnos,
                "headers" => $headers
            ];

            return response()->json($response);

        }

        public function detalle_alumno(Request $request){

            $alumno = Alumno::find($request->alumno_id);

            $persona = Persona::find($alumno->persona_id);

            $persona->nombre_completo = $persona->primer_nombre . ' ' . $persona->segundo_nombre . ' ' . $persona->primer_apellido . ' ' . $persona->segundo_apellido;

            return response()->json($persona);

        }

        public function estados_alumno(Request $request){

            $estados = app('db')->select("  SELECT *
                                            FROM estado_gestion
                                            WHERE id NOT IN (
                                                SELECT 
                                                    estado_id
                                                FROM detalle_gestion
                                                WHERE gestion_id = '$request->gestion_id'
                                            )");

            return response()->json($estados);

        }

        public function actualizar_gestion(Request $request){

            // Validar si el estado requiere ejectuar algun proceso
            $estado = EstadoGestion::find($request->estado_id);

            if ($estado->proceso) {
                
                $result = $this->{$estado->proceso}($request);

                return response()->json($result->original);

            }

            $detalle_gestion = new DetalleGestion();

            $detalle_gestion->gestion_id = $request->gestion_id;
            $detalle_gestion->estado_id = $request->estado_id;
            $detalle_gestion->save();

            $response = [

                "message" => [
                    "title" => "Excelente!",
                    "text" => "El estado de la gestión a sido actualizado exitosamente",
                    "icon" => "success"
                ]

            ];
            
            return response()->json($response);

        }

        public function habilitar_usuario($data){

            // Buscar al alumno
            $alumno = Alumno::find($data->persona_id);

            $persona = Persona::find($alumno->persona_id);

            $primer_nombre = str_split(strtolower($persona->primer_nombre));
            $primer_apellido = strtolower($persona->primer_apellido);
            $segundo_apellido = str_split(strtolower($persona->segundo_apellido));

            $usuario = $primer_nombre[0] . $primer_apellido . $segundo_apellido[0];

            $bk_usuario = $usuario;
            // Validar que dicho usuario no exista

            $valid_user = false;
            $i = 1;

            while (!$valid_user) {
                
                $user_exist = Usuario::where('usuario', $usuario)->first();

                if ($user_exist) {

                    $usuario = $bk_usuario . $i;
                    $i++;

                }else{

                    $valid_user = true;

                }

            }

            $new_user = new Usuario();
            $new_user->persona_id = $persona->id;
            $new_user->rol_id = 7;
            $new_user->usuario = $usuario;
            $new_user->password = Crypt::encrypt($usuario);
            $new_user->save();

            // Habilitar los permisos al usuario
            $result = $this->habilitar_permisos($new_user);

            // Asignar al alumno el número de matricula
            $result = Alumno::where('persona_id', $persona->id)->update(['matricula' => $usuario]);
            
            // Validar si es necesario habilitar el usuario al encargado 
            $alumno = Alumno::where('persona_id', $persona->id)->first();

            $alumno_encargado = AlumnoEncargado::where('alumno_id', $alumno->id)->get();

            foreach ($alumno_encargado as $encargado) {
                
                $encargado_ = Encargado::find($encargado->encargado_id);

                $usuario = Usuario::where('persona_id', $encargado_->persona_id)->first();

                if (!$usuario) {
                    
                    $persona = Persona::find($encargado_->persona_id);

                    $primer_nombre = str_split(strtolower($persona->primer_nombre));
                    $primer_apellido = strtolower($persona->primer_apellido);
                    $segundo_apellido = str_split(strtolower($persona->segundo_apellido));

                    $usuario = $primer_nombre[0] . $primer_apellido . $segundo_apellido[0];

                    $bk_usuario = $usuario;
                    // Validar que dicho usuario no exista

                    $valid_user = false;
                    $i = 1;

                    while (!$valid_user) {
                        
                        $user_exist = Usuario::where('usuario', $usuario)->first();

                        if ($user_exist) {

                            $usuario = $bk_usuario . $i;
                            $i++;

                        }else{

                            $valid_user = true;

                        }

                    }

                    $new_user = new Usuario();
                    $new_user->persona_id = $persona->id;
                    $new_user->rol_id = 6;
                    $new_user->usuario = $usuario;
                    $new_user->password = Crypt::encrypt($usuario);
                    $new_user->save();

                    $result = $this->habilitar_permisos($new_user);

                }

            }

            $response = [

                "message" => [
                    "title" => "Excelente!",
                    "text" => "Se ha habilitado el usuario " . $new_user->usuario,
                    "icon" => "success"
                ]

            ];
            
            return response()->json($response);

        }

        public function habilitar_permisos($data){

            $permisos = RolMenu::where('rol_id', $data->rol_id)->get();

            foreach ($permisos as $permiso) {
                
                $menu_usuario = new MenuUsuario();
                $menu_usuario->menu_id = $permiso->menu_id;
                $menu_usuario->usuario_id = $data->id;
                $menu_usuario->save();

            }
            
            return $permisos;

        }

    }

?>