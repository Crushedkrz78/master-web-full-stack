<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function pruebas(Request $request){
        return "Acción de pruebas de USER-CONTROLLER";
    }

    public function register(Request $request){

        // Recoger datos de usuario por POST

        // Validar datos

        // Cifrar la contraseña

        // Somprobar si el usuario existe ya (Duplicado)

        // Crear el usuario

        $data = array(
            'status' => 'error',
            'code' => 404,
            'message' => 'El usuario no se ha creado'
        );

        return response()->json($data, $data['code']);
    }

    public function login(Request $request){
        return "Acción de login de usuarios";
    }
}
