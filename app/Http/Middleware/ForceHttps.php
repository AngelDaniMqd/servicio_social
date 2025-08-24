<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    public function handle(Request $request, Closure $next)
    {
        if ((app()->environment('production') || env('APP_FORCE_HTTPS')) && !$request->isSecure()) {
            $uri = 'https://' . $request->getHttpHost() . $request->getRequestUri();
            return redirect()->to($uri, 301);
        }

        $response = $next($request);

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}