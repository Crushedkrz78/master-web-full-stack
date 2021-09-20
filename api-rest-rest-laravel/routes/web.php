<?php

use App\Http\Controllers\PruebasController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Rutas de prueba
Route::get('/', function () {
    return '<h1>Hola mundo con Laravel</h1>';
});

Route::get('/hello', function () {
    return view('welcome');
});

Route::get('/pruebas/{nombre?}', function($nombre = null){
    $texto = '<h2>Texto desde una nueva ruta</h2>';
    $texto .= 'Nombre: ' . $nombre;
    return view('pruebas', array(
        'texto' => $texto
    ));
});

Route::get('/animales', [PruebasController::class, 'index']);
Route::get('/test-orm', [PruebasController::class, 'testOrm']);


/**
 * Rutas de la API
 */
/**
 * Métodos HTTP comunes:
 *  - GET: Conseguir datos o recursos.
 *  - POST: Guardar datos o recursos o hacer lógica desde un formulario.
 *  - PUT: Para actualizar recursos o datos.
 *  - DELETE: Para eliminar datos o recursos.
 */

 //Rutas de prueba
ROUTE::get('/usuario/pruebas', [UserController::class, 'pruebas']);
ROUTE::get('/categoria/pruebas', [CategoryController::class, 'pruebas']);
ROUTE::get('/entrada/pruebas', [PostController::class, 'pruebas']);

//Rutas del controlador de usuarios
ROUTE::post('/api/register', [UserController::class, 'register']);
ROUTE::post('/api/login', [UserController::class, 'login']);
ROUTE::put('/api/user/update', [UserController::class, 'update']);
ROUTE::post('/api/user/upload', [UserController::class, 'upload'])->middleware(ApiAuthMiddleware::class);
ROUTE::get('/api/user/avatar/{filename}', [UserController::class, 'getImage']);
