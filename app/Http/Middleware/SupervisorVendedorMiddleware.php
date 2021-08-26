<?php

namespace SisVentaNew\Http\Middleware;

use Closure;

class SupervisorVendedorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->hasRole('Supervisor') || auth()->user()->hasRole('Vendedor')){
            return $next($request);
        }

        return redirect('/');
    }
}
