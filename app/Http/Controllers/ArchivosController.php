<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Storage;

class ArchivosController extends Controller
{
    public function upload(Request $request){
        $File = $request->file('file');
        $Raiz = $request['token'];
        $Path = $request['path'];

        $Kernel = $Raiz.$Path;
        $Name = $File->getClientOriginalName();
        $File->move($Kernel, $Name);

        return response()->json([
            'success' => $Kernel.$Name,
            'to' => $Kernel
        ], 200);
    }
}
