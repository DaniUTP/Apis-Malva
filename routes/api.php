<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuariosController;
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
                Route::post('/create',[UsuariosController::class,'create']);
                Route::post('/login',[UsuariosController::class,'login']);
                Route::get('/me',[UsuariosController::class,'me'])->middleware('validateToken');
                Route::post('/update',[UsuariosController::class,'update'])->middleware('validateToken');
                Route::post('/status',[UsuariosController::class,'changeStatus'])->middleware('validateToken');
        });
        Route::group(
            [
                'prefix'=>'rol'
            ],function(){
            Route::post('/',[RolController::class,'createRol'])->middleware('validateToken'); 
            Route::get('/',[RolController::class,'listRol']); 
            Route::post('/status',[RolController::class,'changeStatus'])->middleware('validateToken');
        });

        Route::group(
            [
                'prefix'=>'personal',
                'middleware'=>['validateToken']
            ],function(){
            Route::get('/',[PersonalController::class,'listPersonal']);
            Route::post('/',[PersonalController::class,'create']);
            Route::post('/update',[PersonalController::class,'update']);
            Route::post('/status',[PersonalController::class,'changeStatus']);
        });

        Route::group(
            [
                'prefix'=>'reserva',
                'middleware'=>['validateToken']
            ],function(){
            Route::get('/',[ReservasController::class,'listReservas']);
            Route::post('/',[ReservasController::class,'create'])->middleware('validatePropietario');
            Route::post('/available',[ReservasController::class,'listHorariosDisponibles']);
        });

        Route::group([
            'prefix'=>'area',
            'middleware'=>['validateToken']
        ],function(){
            Route::get('/',[AreaController::class,'listArea']);
            Route::post('/',[AreaController::class,'create']);
        });
    
});