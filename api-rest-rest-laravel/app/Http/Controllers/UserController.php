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
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true); //array

        if(!empty($params) && !empty($params_array)){

            // Limpiar los datos
            $params_array = array_map('trim', $params_array);

            // Validar datos
            $validate = \Validator::make($params_array, [
                'name' => 'required|alpha',
                'surname' => 'required|alpha',
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validate->fails()){
                // La validación ha fallado
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'El usuario no se ha creado',
                    'errors' => $validate->errors()
                );
            }else{
                // Validación evaluado correctamente

                // Cifrar contraseña
                // Comprobar existencia de usuario ingresado
                // Crear usuario
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El usuario se ha creado correctamente'
                );
            }
        }else{
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Los datos enviados no son correctos'
            );
        }

        // Cifrar la contraseña

        // Somprobar si el usuario existe ya (Duplicado)

        // Crear el usuario



        return response()->json($data, $data['code']);
    }

    public function login(Request $request){
        return "Acción de login de usuarios";
    }
}
