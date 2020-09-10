<?php

namespace App\Http\Middleware;

use Closure;

class Peserta
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
        if ($request->user() && $request->user()->id_role != 3)
        {
            return response()->json(['message' => 'Not Found!'], 404);
        }
        return $next($request);

    }
}