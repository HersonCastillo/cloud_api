<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Carpetas as Folder;
use Storage;

class CarpetasController extends Controller
{
    public function newFolder(Request $request){
        try{
            if(!empty($request['ruta']) && !empty($request['raiz'])){
                $Count = strlen($request['ruta']);
                if($Count <= 35){
                    $Nombre = $request['ruta'];
                    $Raiz = $request['raiz'];
                    Storage::disk('public')->makeDirectory($Raiz.'/'.$Nombre);
                    return response()->json([
                        'success' => 'Carpeta creado con éxito.'
                    ], 200);
                }
                return response()->json([
                    'error' => 'El nombre excede el límite admitido.',
                    'exception' => 'no-exp'
                ], 500);
            }
            return response()->json([
                'error' => 'Nombre no especificado.',
                'exception' => 'no-exp'
            ], 500);
        }catch(\Exception $ex){
            return response()->json([
                'error' => 'Ocurrió un error al crear el folder.',
                'exception' => 'normal'
            ], 500);
        }
    }
}
