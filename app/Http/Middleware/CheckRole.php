<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $rol  El rol requerido para acceder (ej. 'admin', 'cliente')
     */
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        // 1. Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión primero.');
        }

        // 2. Verificar si el campo 'rol' del modelo coincide con el requerido
        // Accedemos directamente a la propiedad 'rol' de tu tabla 'usuario'
        if (Auth::user()->rol !== $rol) {
            // Si no tiene el rol, puedes abortar con un 403 o redirigir
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}