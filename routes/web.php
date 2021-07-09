<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/login', 'LoginController@login');

$router->post('/obtener_menu', 'LoginController@obtener_menu');

$router->get('/obtener_roles', 'RolController@obtener_roles');

$router->get('/obtener_usuarios', 'UsuarioController@obtener_usuarios');

$router->post('/registrar_alumno', 'AlumnoController@registrar_alumno');

$router->post('/obtener_alumnos', 'AlumnoController@obtener_alumnos');

$router->post('/detalle_alumno', 'AlumnoController@detalle_alumno');

$router->post('/estados_alumno', 'AlumnoController@estados_alumno');

$router->post('/actualizar_gestion', 'AlumnoController@actualizar_gestion');