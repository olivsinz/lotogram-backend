<?php

namespace App\Service;

use Illuminate\Support\Str;
use App\Models\ExceptionLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Exceptions\ApolloCoreException;

class LoggerService
{
    private static $maskableRequestData = [
        'password',
    ];

    private static $maskableHeadertData = [
        'authorization',
    ];

    protected static function getUserDataAsJson()
    {
        if (!Auth::user())
            return null;

        $userData = collect(Auth::user())
            ->only(['uuid', 'name', 'username', 'email', 'first_name', 'last_name'])
            ->toArray();

        if (isset($userData['uuid'])) {
            $userData['id'] = $userData['uuid'];
            unset($userData['uuid']);
        }

        return json_encode($userData);
    }

    protected static function defaultLoggerData(): array
    {
        return [
            'host' => config('app.host'),
            'container' => config('app.container'),
            'x-request-id' => request()->header('x-request-id'),
            'ip_address' => request()->ip(),
            'user_id' => Auth::id() ?? null,
            'user' => self::getUserDataAsJson(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public static function traffic($request, $response, $requestStartTime): void
    {
        $bodyData = self::maskRequestData($request);
        $headerData = self::maskHeaderData($request);
        $data = [
            ...self::defaultLoggerData(),
            'method' => $request->method(),
            'status_code' => $response->getStatusCode(),
            'path' => $request->path(),
            'query' => collect($request->query())->toJson(),
            'headers' => collect($headerData)->toJson(),
            'body' => collect($bodyData)->toJson(),
            'response_body' => $response->getContent(),
            'response_headers' => collect($response->headers->all())->toJson(),
            'request_time' => number_format(microtime(true) - $requestStartTime, 3),
        ];

        try
        {
            Redis::publish('traffic-log-channel', json_encode([
                'data' => $data
            ]));
        }
        catch (\Exception $e)
        {
            if (Cache::get('hold_service.mongo') === null) {
                Cache::put('hold_service.mongo', 'LoggerService::Traffic', 300);
                throw new ApolloCoreException("LoggerService::Traffic, MongoDB'ye kayıt eklerken bir hata oluştu. Hata : " . $e->getMessage());
            }
        }
    }

    public static function history($action, $model): void
    {
        try
        {
            $data = [
                ...self::defaultLoggerData(),
                ...self::regenerateHistoryData($action, $model)
            ];

            Redis::publish('model-changes-channel', json_encode([
                'action' => $action,
                'data' => $data
            ]));
        }
        catch (\Exception $e)
        {
            if (Cache::get('hold_service.mongo') === null) {
                Cache::put('hold_service.mongo', 'LoggerService::History', 300);
                throw new ApolloCoreException("LoggerService::History, MongoDB'ye kayıt eklerken bir hata oluştu. Hata : " . $e->getMessage());
            }
        }
    }

    public static function exception($exception)
    {
        try {
            ExceptionLogger::create([
                ...self::defaultLoggerData(),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'code' => $exception->getCode(),
                'trace' => $exception->getTraceAsString(),
                'ip_address' => request()->ip(),
                'country' => null,
                'os' => request()->header('User-Agent')
            ]);
        } catch (\Exception $e) {
            if (Cache::get('hold_service.mongo') === null) {
                Cache::put('hold_service.mongo', 'LoggerService::History', 300);
                throw new ApolloCoreException("LoggerService::Exception, MongoDB'ye kayıt eklerken bir hata oluştu. Hata : " . $e->getMessage());
            }
        }
    }

    public static function methodTraffic($transactionId, $methodId, $url, $method, $requestHeaders, $requestPayload, $response, $requestStartTime): void
    {
        $totalTime = (float) number_format((microtime(true) - $requestStartTime), 4, '.', '');
        
        $networkInfo = [
            'interface_ip' => null,
            'target_ip' => null
        ];

        if ($response instanceof \Illuminate\Http\Client\Response && $response->transferStats) {
            $stats = $response->transferStats->getHandlerStats();
            $networkInfo = [
                'interface_ip' => $stats['local_ip'] ?? null,
                'target_ip' => $stats['primary_ip'] ?? null
            ];
            
            $timings = [
                'total_time' => (float)number_format($stats['total_time'] ?? 0, 4, '.', ''),
                'namelookup_time' => (float)number_format($stats['namelookup_time'] ?? 0, 4, '.', ''),
                'connect_time' => (float)number_format($stats['connect_time'] ?? 0, 4, '.', ''),
                'pretransfer_time' => (float)number_format($stats['pretransfer_time'] ?? 0, 4, '.', ''),
                'starttransfer_time' => (float)number_format($stats['starttransfer_time'] ?? 0, 4, '.', ''),
                'redirect_time' => (float)number_format($stats['redirect_time'] ?? 0, 4, '.', ''),
                'ssl_time' => (float)number_format($stats['appconnect_time'] ?? 0, 4, '.', ''),
                'remote_server_response_time' => (float)number_format(
                    ($stats['starttransfer_time'] ?? 0) - ($stats['pretransfer_time'] ?? 0), 
                    4, 
                    '.', 
                    ''
                )
            ];
        }
        
        $responseContent = $response instanceof \Illuminate\Http\Client\Response 
            ? $response->body()
            : $response->getContent();
        
        $cleanedResponse = self::cleanHtmlResponse($responseContent);
        
        $responseHeaders = $response instanceof \Illuminate\Http\Client\Response 
            ? $response->headers() 
            : $response->headers->all();
        
        $data = [
            'transaction_id' => $transactionId,
            'method_id' => $methodId,
            'method' => $method,
            'url' => $url,
            'status_code' => $response->status(),
            'total_time' => $totalTime,
            'interface_ip' => $networkInfo['interface_ip'],
            'target_ip' => $networkInfo['target_ip'],
            'host' => config('app.host'),
            'request_headers' => collect($requestHeaders)->toJson(),
            'request_payload' => collect($requestPayload)->toJson(),
            'response_headers' => collect($responseHeaders)->toJson(),
            'response_body' => $cleanedResponse,
            'timing' => $timings,
            'container' => config('app.container'),
            'created_at' => now()->format('Y-m-d H:i:s')
        ];

        try {
            Redis::publish('method-traffic-channel', json_encode([
                'data' => $data
            ]));
        } catch (\Exception $e) {
            if (Cache::get('hold_service.mongo') === null) {
                Cache::put('hold_service.mongo', 'LoggerService::MethodTraffic', 300);
                throw new ApolloCoreException("LoggerService::MethodTraffic, MongoDB'ye kayıt eklerken bir hata oluştu. Hata : " . $e->getMessage());
            }
        }
    }

    protected static function maskRequestData($request): array
    {
        $allRequestData = $request->all();

        foreach (self::$maskableRequestData as $field) {
            if (isset($allRequestData[$field])) {
                $allRequestData[$field] = '********';
            }
        }

        return $allRequestData;
    }

    protected static function maskHeaderData($request): array
    {
        $allRequestData = $request->header();

        foreach (self::$maskableHeadertData as $field) {
            if (isset($allRequestData[$field])) {
                $allRequestData[$field] = '********';
            }
        }

        return $allRequestData;
    }

    protected static function regenerateHistoryData($action, $model): array
    {
        /*
        if ($model instanceof \Illuminate\Database\Eloquent\Relations\Pivot)
        {
            return [
                'ownerable_id' => $model->{Str::lower($model->parentTable) . '_id'},
                'ownerable_type' => 'App\Models\\' . $model->parentTable,
                'action' => $action,
                'changes' => $action == 'created' ? $model : $model->getChanges(),
                'original' => array_intersect_key($model->getOriginal(), $model->getChanges()),
            ];
        }
        */

        return [
            'ownerable_id' => $model->id,
            'ownerable_type' => get_class($model),
            'action' => $action,
            'changes' => $action == 'created' ? $model : $model->getChanges(),
            'original' => array_intersect_key($model->getOriginal(), $model->getChanges()),
        ];
    }

    protected static function cleanHtmlResponse($content): string 
    {
        // Content-Type header'ı HTML içeriyorsa veya içerik HTML tag'leri içeriyorsa
        if (
            stripos($content, '<!DOCTYPE html>') !== false || 
            stripos($content, '<html') !== false ||
            preg_match('/<[^>]*>/', $content)
        ) {
            // HTML'i yükle
            $dom = new \DOMDocument();
            
            // HTML hata bildirimleri kapalı
            libxml_use_internal_errors(true);
            
            // UTF-8 karakterleri düzgün işle
            $content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
            $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            
            // Script ve style tag'lerini kaldır
            $scripts = $dom->getElementsByTagName('script');
            $styles = $dom->getElementsByTagName('style');
            
            while ($scripts->length > 0) {
                $scripts->item(0)->parentNode->removeChild($scripts->item(0));
            }
            
            while ($styles->length > 0) {
                $styles->item(0)->parentNode->removeChild($styles->item(0));
            }
            
            // Sadece metin içeriğini al
            $text = $dom->textContent;
            
            // Fazla boşlukları temizle
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);
            
            // İlk 1000 karakteri al
            return mb_substr($text, 0, 1000) . (mb_strlen($text) > 1000 ? '...' : '');
        }
        
        return $content;
    }
}
