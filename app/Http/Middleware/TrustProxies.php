<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrustProxies
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Percaya pada proxy dan atur skema URL serta IP client yang benar
        $request->setTrustedProxies([config('app.url')], Request::HEADER_X_FORWARDED_ALL);

        return $next($request);
    }
}