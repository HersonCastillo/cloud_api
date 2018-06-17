<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/application', function(){
    return [ 'v' => '0.4.7', '_token' => csrf_token() ];
});
Route::group(['prefix' => 'api'], function(){
    Route::post('/validate/token', 'LoginController@validateToken');
    Route::post('/login', 'LoginController@login');
    Route::post('/new/account', 'LoginController@createAccount');
    Route::post('/new/folder', 'CarpetasController@newFolder');
    Route::post('/new/file', 'ArchivosController@upload');
    Route::post('/new/group', 'GruposController@newGroup');
    Route::post('/view/files', 'ArchivosController@viewFiles');
    Route::post('/download', 'ArchivosController@download');
    Route::post('/delete/object', 'ArchivosController@deleteOne');
    Route::post('/shared', 'GruposController@infoFilesShared');
    Route::post('delete/share', 'GruposController@quitShare');
});
