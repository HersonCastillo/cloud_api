<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Storage;
use App\Etiquetas as Tags;

class EtiquetasController extends Controller
{
    public static function newEtiqueta(){
        return str_random(32);
    }
    public static function saveEtiqueta($tag){
        try{
            $Tag = new Tags();
            $Tag->nombre = $tag;
            $Tag->save();
            $Last = Tags::orderBy('id', 'desc')->first();
            return $Last->id;
        }catch(\Exception $ex){
            return -1;
        }catch(\Illuminate\Database\QueryException $ex){
            return -1;
        }
    }
}
