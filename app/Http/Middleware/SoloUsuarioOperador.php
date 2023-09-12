<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Symfony\Component\HttpFoundation\Response;

class SoloUsuarioOperador
{
    use HasRoles;
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
                    
        $usuario = Auth::user();

        if (! $usuario->is_active) {

            abort(401, 'Usuario Desactivado');

        }

        if (! $usuario->hasRole('OPERADOR') ) {

            abort(403, 'Solo para Operadores');

        }

        return $next($request);
    }
}
