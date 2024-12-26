<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Vérifie si l'utilisateur possède le rôle requis
        if (Auth::user()->role !== $role) {
            return redirect('/')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        return $next($request);
    }
}
