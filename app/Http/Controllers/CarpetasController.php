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
            if(!empty($request['nombre']) && !empty($request['raiz'])){
                $Count = strlen($request['nombre']);
                if($Count <= 35){
                    $Nombre = $request['nombre'];
                    $Raiz = $request['raiz'];
                    Storage::disk('public')->makeDirectory($Raiz.'/'.$Nombre);
                    return response()->json([
                        'success' => 'Folder creado con éxito.'
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
