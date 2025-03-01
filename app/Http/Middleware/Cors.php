<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->secure()) {
            return redirect()->secure($request->getRequestUri());
        }


        $response = $next($request);

        // Tambahkan Header CORS
        $response->header("Access-Control-Allow-Origin", "*");
        $response->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS");
        $response->header("Access-Control-Allow-Headers", "Content-Type, Authorization");

        // Menangani preflight request OPTIONS agar tidak diblokir
        if ($request->isMethod("OPTIONS")) {
            return response("", 200);
        }

        return $response;
    }
}
