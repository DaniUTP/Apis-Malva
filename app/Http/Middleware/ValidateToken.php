<?php

namespace App\Http\Middleware;

use App\CustomResponse\CustomResponse;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class ValidateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {  
        $language=$request->query('lang');
    
        $token=$request->bearerToken();

        if(!$token){
            return CustomResponse::responseMessage('noToken',401,$language);
        }

        $accessToken=PersonalAccessToken::findToken($token);
        
        if(!$accessToken){
            return CustomResponse::responseMessage('tokenInValid',401,$language);
        }

        if(Carbon::now(env('LOCATION'))->gt($accessToken->expires_at)){
            return  CustomResponse::responseMessage('expiredToken',401,$language);
        }
        
        return $next($request);
    }
}
