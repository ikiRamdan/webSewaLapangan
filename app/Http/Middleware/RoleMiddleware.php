<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{

    public function handle(Request $request, Closure $next, $role): Response
    {

        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if ($user->role !== $role) {

            switch ($user->role) {

                case 'admin':
                    return redirect('/admin/dashboard');

                case 'kasir':
                    return redirect('/kasir/dashboard');

                case 'owner':
                    return redirect('/owner/dashboard');

                default:
                    return redirect('/login');
            }
        }

        return $next($request);
    }
}