<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UsuariosController as User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Archivos;
use File;
use Storage;

class ArchivosController extends Controller
{
    public function upload(Request $request){
        try{
            $File = $request->file('file');
            $Raiz = $request['token'];
            $Path = $request['path'];

            $Kernel = $Raiz.$Path;
            $Name = $File->getClientOriginalName();
            $User = User::getIdUser($Raiz);

            $IfExist = Archivos::where('nombre', $Path.$Name)->where('id_user', $User)->first();
            if(!is_null($IfExist)) return response()->json([
                'success' => 'no-process',
                'message' => 'Ya existe un archivo con ese nombre, cambialo o elimina el archivo guardado.'
            ], 200);

            $File->move("../storage/app/public/$Kernel", $Name);

            $Archivos = new Archivos();
            $Archivos->nombre = $Path.$Name;
            $Archivos->fecha_subida = date('Y-m-d');
            $Archivos->id_user = $User;
            $Archivos->save();

            return response()->json([
                'success' => 'ok',
                'message' => 'El archivo se subió exitosamente.'
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'error' => 'Ocurrió un error al subir el archivo',
                'message' => $ex->getMessage(),
                'exception' => 'normal'
            ], 500);
        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'error' => 'Ocurrió un error al subir el archivo',
                'exception' => 'query'
            ], 500);
        }
    }
    public function viewFiles(Request $request){
        try{
            $Path = $request['path'];
            $Token = $request['token'];
            $Route = "../storage/app/public/";
            $files = scandir($Route.$Token.$Path);
            $files = $this->clear($files);
            return response()->json([
                'success' => 'ok',
                'data' => $files
            ], 200);
        } catch(\Exception $ex){
            return response()->json([
                'error' => 'Directorio no encontrado',
                'message' => 'La raiz o el token no son válido.',
                'exception' => 'notfound'
            ], 500);
        }
    }
    private function clear($arrayFiles){
        $nArr = [];
        foreach($arrayFiles as $file)
            if($file != "." && $file != "..")
              array_push($nArr, $file);
        return $nArr;
    }
    public function download(Request $request){
        try{
            $Token = $request['token'];
            $Path = $request['path'];
            $FileName = $request['filename'];
            $Url = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($Token.$Path.$FileName);
            $Url = str_replace("C:\\wamp64\\www\\", "http://localhost/", $Url);
            return response()->json([
                'url' => $Url
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'error' => 'No se puede descargar el archivo.',
                'message' => $ex->getMessage()
            ], 500);
        }
    }
    public function deleteOne(Request $request){
        try{
            $Token = $request['token'];
            $Path = $request['path'];
            $isFolder = $request['is'];
            $Route = "../storage/app/public/";
            $Route = $Route.$Token.$Path;
            if($isFolder == "is") rmdir($Route);
            else{
                $archivoId = Archivos::where('nombre', $Path)->first();
                if(!is_null($archivoId)){
                    $archivoId = $archivoId->delete();
                    unlink($Route);
                } else return response()->json([
                    'error' => 'Archivo dañado no almacenado.'
                ], 200);
            }
            return response()->json([
                'success' => 'Elemento eliminado con éxito.'
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'error' => 'Ocurrió un error al tratar de eliminar un objeto.',
                'exception' => 'normal',
                'message' => $ex->getMessage()
            ], 500);
        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'error' => 'Ocurrió un error al tratar de eliminar un objeto.',
                'exception' => 'query'
            ], 500);
        }
    }
}
