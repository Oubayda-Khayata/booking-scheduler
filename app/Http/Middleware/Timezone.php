<?php

namespace App\Http\Middleware;

use Closure;

class Timezone
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
        if ($request->hasHeader('timezone')) {
            return $next($request);
        }
        $ipApiUri = 'http://ip-api.com/json/' . $request->ip() . '?fields=timezone';
        $ipInfoResponse = file_get_contents($ipApiUri);
        $timezone = json_decode($ipInfoResponse, true)['timezone'];
        $request->headers->add(['timezone' => $timezone]);
        return $next($request);
    }
}
