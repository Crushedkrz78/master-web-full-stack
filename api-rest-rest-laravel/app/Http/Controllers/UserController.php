<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
                'email' => 'required|email|unique:users',
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
                //$pwd = password_hash($params->password, PASSWORD_BCRYPT, ['cost' => 4]);
                $pwd = hash('sha256', $params->password);

                // Comprobar existencia de usuario ingresado (Duplicado)


                // Crear usuario
                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                $user->role = 'ROLE_USER';

                // Guardar el usuario en DB
                $user->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El usuario se ha creado correctamente',
                    'user' => $user
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

        $jwtAuth = new \JwtAuth();
        //echo $jwtAuth->signup();

        // Recibir POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        // Validar datos recibidos
        $validate = \Validator::make($params_array,[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validate->fails()){
            // La validación ha fallado
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha podido identificar',
                'errors' => $validate->errors()
            );
        }else{
            // Cifrar la password
            $pwd = hash('sha256', $params->password);

            // Devolver token o datos
            $signup = $jwtAuth->signup($params->email, $pwd);
            if(!empty($params->getToken)){
                $signup = $jwtAuth->signup($params->email, $pwd, true);
            }
        }

        $email = 'test@test.com';
        $password = '1234';
        //$pwd = password_hash($password, PASSWORD_BCRYPT, ['cost' => 4]);
        $pwd = hash('sha256', $password);


        return response()->json($signup, 200);
    }
}
