<?php

namespace App\Exceptions;

use App\Service\LoggerService;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
        'secret',
        'public_key',
        'private_key',
        'token',
    ];

    // protected $withoutDuplicates = true; her istisna sadece 1 kez rapor edilsin.

    protected $dontReport = [
        //AuthorizationException::class, // asla rapor edilmesin.
    ];

    protected function throttle(Throwable $e): mixed
    {
        return match (true) {
            $e instanceof \App\Exceptions\AuthorizationException => Limit::perDay(1),
            $e instanceof \RedisException => Limit::perDay(1),
            default => Limit::none(),
        };
    }

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }

            if ($this->shouldReport($e)) {
                LoggerService::Exception($e);
            }
        });

        $this->renderable(function (Throwable $e, Request $request)
        {
            if ($request->is('api/*') && config('app.debug') === false)
            {
                return $this->handleApiExceptions($e);
            }
        });
    }

    protected function handleApiExceptions ($e)
    {
        return match (true) {

            $e instanceof \Illuminate\Auth\AuthenticationException => Response::error([
                'key' => 'login_failed',
                'message' => __('exception.handler.invalid_session'),
            ], 401),

            $e instanceof \Illuminate\Database\QueryException => Response::error([
                'key' => 'db_error',
                'message' => __('exception.handler.database_connection_failed'),
            ], 500),

            $e instanceof \Illuminate\Auth\Access\AuthorizationException => Response::error([
                'key' => 'unauthorized_access',
                'message' => __('exception.handler.unauthorized_access_request'),
            ], 403),

            $e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException => Response::error([
                'key' => 'unauthorized_access',
                'message' => __('exception.handler.access_denied_request'),
            ], 403),

            $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException => Response::error([
                'key' => 'not_found',
                'message' => __('exception.handler.not_found'),
            ], 404),

            $e instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException => Response::error([ // TODO: reportable
                'key' => 'too_many_request',
                'message' => __('exception.handler.too_many_requests'),
            ], 429),

            $e instanceof \Illuminate\Validation\ValidationException => Response::errorsForValidation(collect($e->validator->errors())->toArray()),

            $e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException => Response::error([
                'key' => 'method_not_allowed',
                'message' => __('exception.handler.method_not_allowed_http_exception'),
            ], 404),

            $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException => Response::error([
                'key' => 'record_not_found',
                'message' => __('exception.handler.no_records_found'),
            ], 404),

            $e instanceof \Symfony\Component\HttpKernel\Exception\ConflictHttpException => Response::error([
                'key' => 'conflict',
                'message' => !empty($e->getMessage()) ? $e->getMessage() :  __('exception.handler.conflict_http_exception'),
            ], 409),

            $e instanceof \Symfony\Component\HttpKernel\Exception\HttpException => Response::error([
                'key' => 'http_error',
                'message' => $e->getMessage()
            ], $e->getStatusCode()),

            default => Response::error([
                'key' => 'unknown_error',
                'message' => __('exception.handler.unknown_error')
            ], 500),

        };
    }
}
