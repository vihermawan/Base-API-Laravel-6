<?php

namespace App\Http\Middleware;

use Closure;

class Penandatangan
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
        if ($request->user() && $request->user()->id_role != 4)
        {
            return response()->json(['message' => 'Not Found!'], 404);
        }
        return $next($request);

    }
}