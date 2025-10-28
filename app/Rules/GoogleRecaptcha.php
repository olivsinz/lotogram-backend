<?php

namespace App\Rules;

use Closure;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Illuminate\Contracts\Validation\ValidationRule;

class GoogleRecaptcha implements ValidationRule
{
    protected $endpoint = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(env('APP_ENV') == 'local' || env('APP_ENV') == 'test')
             return;

        $payload = [
            'form_params' =>
            [
                'secret' => config('app.google_recaptcha_secret'),
                'response' => $value,
                'remoteip' => Request::ip()
            ],
            'connect_timeout' => 10,
            'timeout' => 10,
        ];
        $client = new Client;
        $response = $client->request('POST', $this->endpoint, $payload);
        $body = json_decode((string)$response->getBody());

        if ($body->success && ($body->score * 10) > config('app.google_recaptcha_score')) {
            return;
        }

        $fail('validation.google_recaptcha')->translate();
    }
}
