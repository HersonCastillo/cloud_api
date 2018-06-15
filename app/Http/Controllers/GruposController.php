<?php

namespace App\Http\Controllers;
use App\Http\Controllers\EtiquetasController as Tag;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Grupos as Groups;

class GruposController extends Controller
{
    public static function lastGroup(){
        $group = Groups::orderBy('id', 'desc')->first();
        return $group->id;
    }
    public static function newGroup($idUser){
        try{
            $tag = Tag::newEtiqueta();
            $idTag = Tag::saveEtiqueta($tag);
            if($idTag != -1){
                $Groups = new Groups();
                $Groups->id_etiqueta = $idTag;
                $Groups->id_user = $idUser;
                $Groups->save();
                return GruposController::lastGroup();
            }
            return $idTag;
        }catch(\Exception $ex){
            return -1;
        }catch(\Illuminate\Database\QueryException $ex){
            return -1;
        }
    }
}
