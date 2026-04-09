<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
      if (!auth()->check()) {
        return redirect('login');
    }

    if (!in_array(auth()->user()->role, $roles)) {
        return redirect()->route('unauthorized');
    }

    $response = $next($request);

   

    return $response;
    }
}
