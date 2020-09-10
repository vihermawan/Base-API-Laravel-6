<?php

namespace App\Http\Middleware;

use Closure;

class ActiveUser
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
        if ($request->user()->isBan == 1)
        {
            return response()->json(['message' => 'Akun anda telah diblokir. Silahkan kirim email ke service.eventin@gmail.com jika menurut anda tidak selayaknya diblokir'], 401);
        }
        return $next($request);
    }
}
