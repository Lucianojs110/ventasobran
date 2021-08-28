<?php

namespace SisVentaNew\Http\Middleware;

use Closure;

class SupervisorSuperadminMiddleware
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
        if(auth()->user()->hasRole('Supervisor') || auth()->user()->hasRole('Superadmin')){
            return $next($request);
        }

        return redirect('/');
    }
}
