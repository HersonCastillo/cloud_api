<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Grupos as Groups;
use App\Archivos as Files;
use Storage;

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
    public function isShared($id){
        return !is_null(Groups::where('id_file', $id)->first());
    }
    public function downloadFile(Request $request){
        try{
            $Id = $request['id'];
            if($this->isShared($Id)){
                $Data = Groups::orderBy('grupos.id', 'desc')
                  ->join('archivos', 'archivos.id', '=', 'grupos.id_file')
                  ->join('usuarios', 'usuarios.id', '=', 'archivos.id_user')
                  ->select('archivos.nombre', 'usuarios.api_token')
                  ->first();
                $Path = $Data->api_token.$Data->nombre;
                $Url = Storage::disk('public')
                        ->getDriver()
                        ->getAdapter()
                        ->applyPathPrefix($Path);
                $Url = str_replace("C:\\wamp64\\www\\", "http://localhost/", $Url);
                $Size = Storage::disk('public')->size($Path);
                return response()->json([
                    'success' => 'ok',
                    'url' => $Url,
                    'size' => $Size,
                    'file' => $Data->nombre
                ], 200);
            }
            return response()->json([
                'error' => 'El archivo no se está compartiendo.',
                'exception' => 'normal'
            ], 500);
        }catch(\Exception $ex){
            return response()->json([
                'error' => 'Ocurrió un error al obtener el archivo.',
                'exception' => 'normal'
            ], 500);
        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'error' => 'Ocurrió un error al obtener el archivo.',
                'exception' => 'query'
            ], 500);
        }
    }
}
