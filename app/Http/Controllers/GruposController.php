<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Grupos as Groups;

class GruposController extends Controller
{
    public static function lastGroup(){
        $group = Groups::orderBy('id', 'desc')->first();
        return $group->id;
    }
}
