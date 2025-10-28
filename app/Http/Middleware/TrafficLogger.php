<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\LoggerService;


class TrafficLogger
{
    protected $requestStartTime;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->requestStartTime = microtime(true);
        return $next($request);
    }

    public function terminate($request, $response)
	{
        LoggerService::Traffic($request, $response, $this->requestStartTime);
    }
}
