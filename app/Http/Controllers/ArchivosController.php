<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UsuariosController as User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Archivos;
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

            Storage::disk('public')->put($Kernel.$Name, $Name);

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
}
