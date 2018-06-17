<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Grupos as Groups;
use App\Archivos as Files;

class GruposController extends Controller
{
    public function newGroup(Request $request){
        try{
            $Token = $request['token'];
            $NameFile = $request['filename'];
            $FileId = Files::where('nombre', $NameFile)
              ->first();
            $FileId = $FileId->id;
            $isExist = Groups::where('id_file', $FileId)->first();
            if(!is_null($isExist)) return response()->json([
                'success' => 'El archivo ya fue compartido',
                'url' => 'http://localhost:4200/download/'.base64_encode($FileId),
                'code' => 'error'
            ], 200);
            $Group = new Groups();
            $Group->id_file = $FileId;
            $Group->save();
            return response()->json([
                'success' => 'El archivo se ha compartido',
                'url' => 'http://localhost:4200/download/'.base64_encode($FileId),
                'code' => 'ok'
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'error' => 'Ocurrió un error al generar el grupo.',
                'exception' => 'normal'
            ], 500);
        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'error' => 'Ocurrió un error al generar el grupo.',
                'exception' => 'query'
            ], 500);
        }
    }
    public function quitShare(Request $request){
        try{
            $Token = $request['token'];
            $FileName = $request['filename'];
            $FileId = Files::where('nombre', $FileName)
              ->first();
            $FileId = $FileId->id;
            $isExist = Groups::where('id_file', $FileId)->first();
            if(is_null($isExist)) return response()->json([
                'success' => 'El archivo no ha sido compartido.'
            ], 200);
            $isExist->delete();
            return response()->json([
                'success' => 'El archivo se dejó de compartir.'
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'error' => 'Ocurrió un error al eliminar el grupo.'
            ], 500);
        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'error' => 'Ocurrió un error al eliminar el grupo.',
                'exception' => 'query'
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
              ->select('archivos.nombre', 'archivos.id')
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
