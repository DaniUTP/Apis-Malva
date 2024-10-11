<?php

use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix'=>'v1'
    ],
    function(){
        Route::group(
            [
                'prefix'=>'auth'
            ],
            function(){
                Route::post('create',[UsuariosController::class,'create']);
        });
        Route::group(['prefix'=>'rol'],function(){
            Route::post('',[RolController::class,'createRol']); 
            Route::get('',[RolController::class,'listRol']); 
        });
    
});