<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Usuarios as Users;

class UsuariosController extends Controller
{
    public static function lastUser(){
        $Last = Users::orderBy('id', 'desc')->first();
        return $Last->id;
    }
    public static function getIdUser($token){
        $User = Users::orderBy('id')
          ->where('api_token', $token)
          ->select('id', 'nombre', 'apellido')
          ->first();
        return $User->id;
    }
}
