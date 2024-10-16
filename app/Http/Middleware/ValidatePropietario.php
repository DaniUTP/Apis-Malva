<?php

namespace App\Http\Middleware;

use App\CustomResponse\CustomResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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
        if(Gate::denies('propietario-only')){
            return CustomResponse::responseMessage('noPropietario',403,$language);
        }
        return $next($request);
    }
}
