<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{

    public function signup(){
        // Buscar si existe el usuario con las credenciales
        // Comprobar si las credenciales son correctas (Objeto)
        // Generar Token con los datos del usuario identificado
        // Devolver los datos decodificados o el Token, en función del un parámetro

        return 'Método de clase JWTAUTH';
    }

}
