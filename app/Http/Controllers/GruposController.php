<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Grupos as Groups;

class GruposController extends Controller
{
    public function newGroup(Request $request){
        try{
            //$FileId = $request['id'];

        }catch(\Exception $ex){
            return response()->json([
                'error' => 'Ocurrió un error al generar el grupo.',
                'exception' => 'normal'
            ], 500);
        }
    }
    public function infoFilesShared(Request $request){
        try{
            $Token = $request['token'];
            return Groups::orderBy('grupos.id')
              ->join('archivos', 'archivos.id', '=', 'grupos.id_file')
              ->join('usuarios', 'usuarios.id', '=', 'archivos.id_user')
              ->where('usuarios.api_token', $Token)
              ->select('archivos.nombre')
              ->get();
        }catch(\Exception $ex){
            return response()->json([
                'error' => 'Ocurrió un error al ordenar los archivos compartidos.',
                'exception' => 'normal'
            ], 500);
        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'error' => 'Ocurrió un error al ordenar los archivos compartidos.',
                'exception' => 'query'
            ], 500);
        }
    }
}
