<?php

use App\Http\Controllers\PersonalController;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuariosController;
use App\Http\Middleware\ValidateToken;
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
                Route::post('update',[UsuariosController::class,'update']);
        });
        Route::group(
            [
                'prefix'=>'rol'
            ],function(){
            Route::post('',[RolController::class,'createRol'])->middleware('validateToken'); 
            Route::get('',[RolController::class,'listRol']); 
            Route::post('update',[RolController::class,'update']);
            Route::post('change',[RolController::class,'changeStatus']);
        });

        Route::group(['prefix'=>'personal'],function(){
            Route::get('',[PersonalController::class,'listPersonal']);
            Route::post('',[PersonalController::class,'create']);
        });

        Route::group(['prefix'=>'reserva','middleware'=>['validateToken']],function(){
            Route::get('',[ReservasController::class,'listReservas']);
            Route::post('',[ReservasController::class,'create']);
            Route::post('/disponible',[ReservasController::class,'listHorariosDisponibles']);
        });
    
});