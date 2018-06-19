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

    Route::group(['prefix' => 'new'], function(){
        Route::post('/account', 'LoginController@createAccount');
        Route::post('/folder', 'CarpetasController@newFolder');
        Route::post('/file', 'ArchivosController@upload');
        Route::post('/group', 'GruposController@newGroup');
    });
    Route::group(['prefix' => 'delete'], function(){
        Route::post('/object', 'ArchivosController@deleteOne');
        Route::post('/share', 'GruposController@quitShare');
    });

    Route::group(['prefix' => 'download'], function(){
        Route::post('/', 'ArchivosController@download');
        Route::post('/one', 'GruposController@downloadFile');
    });

    Route::post('/view/files', 'ArchivosController@viewFiles');

    Route::post('/shared', 'GruposController@infoFilesShared');

});
