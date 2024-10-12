<?php

use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuariosController;
use App\Models\Usuarios;
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
                Route::post('login',[UsuariosController::class,'login']);
                Route::get('me',[UsuariosController::class,'me'])->middleware('validateToken');
        });
        Route::group(
            [
                'prefix'=>'rol'
            ],function(){
            Route::post('',[RolController::class,'createRol'])->middleware('validateToken'); 
            Route::get('',[RolController::class,'listRol']); 
        });
    
});