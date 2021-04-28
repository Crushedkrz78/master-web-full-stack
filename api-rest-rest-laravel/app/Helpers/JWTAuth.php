<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{

    public $key;

    public function __construct(){
        $this->key = 'esto_es_una_clave_super_secreta'

    }

    public function signup($email, $password, $token = null){
        // Buscar si existe el usuario con las credenciales
        $user = User::where([
            'email' => $email,
            'password' => $password
        ])->first();
        // Comprobar si las credenciales son correctas (Objeto)
        $signup = false;
        if(is_object($user)){
            $signup = true;
        }
        // Generar Token con los datos del usuario identificado
        if($signup){
            $token = array(
                'sub' => $user->id,
                'email' => $user->email,
                'name'=> $user->name,
                'surname'=> $user->surname,
                'iat' => time(),
                'exp' => time() + (7 * 24 * 60 * 60)
            );

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);

            // Devolver los datos decodificados o el Token, en función del un parámetro
            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data = $decoded;
            }

        }else{
            $data = array(
                'status'=> 'error',
                'message'=> 'Login incorrecto'
            );
        }



        return $data;
    }

}
