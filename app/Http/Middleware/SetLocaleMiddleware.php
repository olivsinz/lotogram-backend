<?php

namespace App\Http\Middleware;

use App\Enum\UserLanguage;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->language)
        {
            app()->setLocale(UserLanguage::getNameByValue(auth()->user()->language));
        }
        else if (null !== $request->header('X-Language') && in_array($request->header('X-Language'), UserLanguage::getShortKeys()))
        {
            app()->setLocale($request->header('X-Language'));
        }
        else
        {
            app()->setLocale(UserLanguage::defaultKey());
        }

        return $next($request);
    }
}
