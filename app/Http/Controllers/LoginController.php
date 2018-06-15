<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Usuarios as Users;
use Storage;

class LoginController extends Controller
{
    public function validateToken(Request $request){
        try{
            $Token = $request['token'];
            $Usuarios = new Users;
            $Find = $Usuarios::where('api_token', $Token)
              ->select('nombre', 'apellido', 'email', 'id')
              ->first();
            if(!is_null($Find)){
                return response()->json([
                    'success' => 'ok',
                    'user' => $Find
                ], 200);
            } else return response()->json([
                'error' => 'Token de acceso no válido.',
                'message' => 'El código de acceso pudo haber expirado.'
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'error' => 'Token de acceso no válido',
                'exception' => 'normal'
            ], 500);
        }catch(PDOException $ex){
            return response()->json([
                'error' => 'Token de acceso no válido',
                'exception' => 'pdo'
            ], 500);
        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'error' => 'Token de acceso no válido',
                'exception' => 'query'
            ], 500);
        }
    }
    public function login(Request $request){
        try{
            $Email = $request['email'];
            $Pass = $request['pass'];
            $FIS = $request['inicia'];
            $FFS = $request['final'];
            $API = sha1($Email.$Pass);
            $Usuario = new Users;
            $Find = $Usuario::where('api_token', $API)->first();
            if(!is_null($Find)){
                $Token = $API.'.'.base64_encode($FIS).'.'.base64_encode($FFS);
                return response()->json([
                    'success' => 'ok',
                    '_accesstoken_' => $Token
                ], 200);
            } else return response()->json([
                'error' => 'Usuario no encontrado.',
                'message' => 'Correo o contraseña no válidos.'
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'error' => 'Ocurrió un error al iniciar sesión',
                'exception' => 'normal'
            ], 500);
        }catch(PDOException $ex){
            return response()->json([
                'error' => 'Ocurrió un error al iniciar sesión.',
                'exception' => 'pdo'
            ], 500);
        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'error' => 'Ocurrió un error al iniciar sesión.',
                'exception' => 'query'
            ], 500);
        }
    }
    public function createAccount(Request $request){
        try{
            $Nombres = $request['nombre'];
            $Apellidos = $request['apellido'];
            $Email = $request['email'];
            $Pass = $request['pass'];
            $FechaInicioSesion = $request['inicia'];
            $FechaFinalSesion = $request['final'];
            $API = sha1($Email.$Pass);
            $Token = $API.'.'.base64_encode($FechaInicioSesion).'.'.base64_encode($FechaFinalSesion);
            $Usuario = new Users();
            $Usuario->email = $Email;
            $Usuario->pass = crypt('rasmuslerdorf', $Pass);
            $Usuario->nombre = $Nombres;
            $Usuario->apellido = $Apellidos;
            $Usuario->api_token = $API;
            $Usuario->save();
            Storage::disk('public')->makeDirectory($API);
            return response()->json([
               'success' => 'Usuario creado con éxito.',
               'message' => '.',
               '_accesstoken_' => $Token
            ], 200);
        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'error' => 'Ocurrió un error al crear la cuenta.',
                'exception' => 'query'
            ], 500);
        }catch(\Exception $ex){
            return response()->json([
                'error' => 'Ocurrió un error al crear la cuenta.',
                'exception' => 'normal'
            ], 500);
        }catch(PDOException $ex){
            return response()->json([
                'error' => 'Ocurrió un error al crear la cuenta.',
                'exception' => 'pdo'
            ], 500);
        }
    }
}
