<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\TrafficLogger;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TrafficLogger::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.app_sql_debug'))
            DB::listen(function ($query) {
                Log::info($query->sql, $query->bindings, $query->time);
            });

        Relation::enforceMorphMap([
            'user' => 'App\Models\User',
            'setting' => 'App\Models\Setting',
            'competition-ticket' => 'App\Models\CompetitionTicket',
        ]);

        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->numbers()
                ->symbols()
                ->mixedCase()
                ->uncompromised();
        });

        Response::macro('ok', function ($data, $statusCode = 200) {
            return response(['data' => $data], $statusCode);
        });

        Response::macro('created', function ($data, $statusCode = 201) {
            return response(['data' => $data], $statusCode);
        });

        Response::macro('error', function ($data, $statusCode) {
            return response(['errors' => [$data]], $statusCode);
        });

        Response::macro('errorsForValidation', function ($datas, $statusCode = 422) {
            foreach ($datas as $key => $error) {
                $results[] = ['key' => $key, 'message' => $error[0]];
            }
            return response(['errors' => $results], $statusCode);
        });

        Response::macro('noContent', function ($data, $statusCode = 204) {
            return response([], $statusCode);
        });

        Response::macro('notFound', function ($data, $statusCode = 404) {
            return response(['data' => $data], $statusCode);
        });
    }
}
