<?php

namespace App\Http\Middleware\Dashboard;

use Closure;
use App\Models\UserGroup;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class VerifyRequest
{
    protected $contentTypeRuleNotRequired = [
        'App\Http\Controllers\CompetitionAPI\MaksiparaController@gateway'
    ];

    protected $expectJsonRuleNotRequired = [
        'App\Http\Controllers\CompetitionAPI\MaksiparaController@gateway'
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('Content-Type') != 'application/json' && $request->method() != 'GET') {
            if (array_search($request->route()->getActionName(), $this->contentTypeRuleNotRequired) === false)
                return response()->json([
                    [
                        'key' => 'content_type',
                        'message' => __('auth.content_type'),
                    ]
                ], 400);
        }

        if ($request->expectsJson() === false) {

            if (array_search($request->route()->getActionName(), $this->expectJsonRuleNotRequired) === false)
                return response()->json([
                    [
                        'key' => 'expects_json',
                        'message' => __('auth.expects_json'),
                    ]
                ], 400);
        }

        // front end burada bearer token göndermiyor, bu yüzden api_token payload üzerinden okunup header'a yazılıyor.
        if($request->route()->getActionName() == 'App\Http\Controllers\Dashboard\MeController@setEmailVerified')
        {
            if($request->api_token)
                $request->headers->set('Authorization', 'Bearer ' . $request->api_token);
        }

        return $next($request);
    }
}
