<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequestCleaner
{
    protected $requestExcept = [
        'current_password',
        'password',
        'password_confirmation',
        'form_domain', // TODO: Bu güvenli mi?
    ];

    protected $headersExcept = [

    ];

    protected $securityPatterns = '/(\/\/+|[\'"<>]+|;+)/';

    public function handle(Request $request, Closure $next)
    {
        // Request verilerini temizle ve geri koy

        $cleanedRequest = $this->clean($request->request->all(), $this->requestExcept);
        $request->request->replace($cleanedRequest);


        // Headerları temizle ve geri koy
        $cleanedHeaders = $this->clean($request->headers->all(), $this->headersExcept, true);
        foreach ($cleanedHeaders as $key => $value) {
            $request->headers->set($key, $value);
        }

        return $next($request);
    }

    protected function clean(array $data, array $except, $isHeader = false)
    {
        foreach ($data as $key => &$value) {
            if (in_array($isHeader ? strtolower($key) : $key, $except)) {
                continue;
            }

            if (is_array($value)) {
                $value = $this->clean($value, $except, $isHeader);
            } elseif (is_string($value)) {
                $value = $this->securityClean($value);
            }
        }

        return $data;
    }

    protected function securityClean($value)
    {
        $value = trim($value);
        $value = preg_replace('/[^\\x20-\\x7EçÇğĞıİöÖşŞüÜ]/u', '', $value);
        // $value = preg_replace($this->securityPatterns, '', $value); TODO: Web request'lerinde sorun yaratıyor. incelenmeli

        return $value;
    }
}
