<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SoloUsuarioOperador
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
                    
        $usuario = auth()->user();

        if ($usuario->is_admin) {

            return redirect('/');

        } elseif (! $usuario->level_id == 3) {

            abort(403);

        }

        return $next($request);
    }
}
