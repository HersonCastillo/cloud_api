<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
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
                    if(!Login::validate($Raiz)) return response()->json([
                        'error' => 'La llave de acceso no es válida.'
                    ], 200);
                    Storage::disk('public')->makeDirectory($Raiz.'/'.$Nombre);
                    return response()->json([
                        'success' => 'Carpeta creado con éxito.'
                    ], 200);
                }
                return response()->json([
                    'error' => 'El nombre excede el límite admitido.',
                    'exception' => 'no-exp'
                ], 200);
            }
            return response()->json([
                'error' => 'Nombre no especificado.',
                'exception' => 'no-exp'
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'error' => 'Ocurrió un error al crear el folder.',
                'exception' => 'normal'
            ], 200);
        }
    }
}
