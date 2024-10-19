<?php

namespace App\Http\Middleware;

use App\CustomResponse\CustomResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidatePropietario
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $language=$request->query('lang');
        $user=auth('sanctum')->user();
        if($user->id_rol !=2) {
            return CustomResponse::responseMessage('noPropietario', 403, $language);
        }
        return $next($request);
    }
}
