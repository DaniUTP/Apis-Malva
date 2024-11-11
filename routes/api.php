<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\RecibosController;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\UploadImageController;
use App\Http\Controllers\UsuariosController;
use GuzzleHttp\Middleware;
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
                Route::put('/update',[UsuariosController::class,'update'])->middleware('validateToken');
                Route::post('/recover',[UsuariosController::class,'recoverPassword']);
                Route::put('/status',[UsuariosController::class,'changeStatus']);
        });
        Route::group(
            [
                'prefix'=>'rol'
            ],function(){
            Route::post('/',[RolController::class,'createRol'])->middleware('validateToken'); 
            Route::get('/',[RolController::class,'listRol']); 
            Route::get('/{id}',[RolController::class,'findRol']);
            Route::put('/status',[RolController::class,'changeStatus'])->middleware('validateToken');
        });

        Route::group(
            [
                'prefix'=>'personal'
            ],function(){
            Route::get('/',[PersonalController::class,'listPersonal'])->middleware('validateToken');
            Route::put('/update',[PersonalController::class,'update'])->middleware('validateToken');
            Route::get('/{dni}',[PersonalController::class,'foundPersonal'])->middleware('validateToken');
            Route::put('/status/{dni}',[PersonalController::class,'changeStatus'])->middleware('validateToken');
        });
       
        Route::group(
            [
                'prefix'=>'reserva',
                'middleware'=>['validateToken']
            ],function(){
            Route::get('/',[ReservasController::class,'listReservas']);
            Route::post('/',[ReservasController::class,'create'])->middleware('validatePropietario');
            Route::get('/available',[ReservasController::class,'listHorariosDisponibles']);
        });

        Route::group([
            'prefix'=>'area',
            'middleware'=>['validateToken']
        ],function(){
            Route::get('/',[AreaController::class,'listArea']);
            Route::post('/',[AreaController::class,'create']);
        });
        Route::group(['prefix'=>'servicios'],function(){
            Route::get('/',[ServicesController::class,'listServicios']);
            Route::post('/',[ServicesController::class,'create']);
        });
        Route::group(['prefix'=>'recibos'],function(){
            Route::get('/',[RecibosController::class,'listRecibos']);
            Route::post('/',[RecibosController::class,'create']);
            Route::post('/{idre}',[RecibosController::class,'find']);
        });
        
            Route::prefix('social')->middleware('web')->group(function() {
                Route::get('auth/google', [SocialController::class, 'redirectToGoogle']);
                Route::get('/auth/callback-google', [SocialController::class, 'handleGoogleCallback']);
                Route::get('auth/facebook', [SocialController::class, 'redirectToFacebook']);
                Route::get('auth/facebook/callback', [SocialController::class, 'handleFacebookCallback']);
                Route::post('',[SocialController::class,'social']);
            });
            Route::group(['prefix'=>'upload'],function(){
                Route::post('/',[UploadImageController::class,'uploadImage'])->middleware('validateToken');
            });
        
});