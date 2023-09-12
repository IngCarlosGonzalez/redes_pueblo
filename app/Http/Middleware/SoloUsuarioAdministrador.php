<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function Laravel\Prompts\alert;

class SoloUsuarioAdministrador
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
                    
        $usuario = auth()->user();

        if (! $usuario->is_active) {

            return redirect('/');

        }

        if (! $usuario->is_admin) {

            abort(403, 'Solo para Administrador');

        }

        return $next($request);
    }
}
