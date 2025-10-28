<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait LoggerTrait
{
    /**
     * Bilgilendirme logu bas
     *
     * @param string|array $message
     * @return void
     */
    protected function writeLog(string|array $message): void
    {
        // Çalışan class ve method bilgisini al
        $caller = $this->getCallerInfo();

        // Mesajı string'e çevir
        $logMessage = is_array($message)
            ? "\n" . json_encode($message, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            : $message;

        // Log mesajını oluştur
        $finalMessage = sprintf(
            '[%s::%s] %s',
            $caller['class'],
            $caller['method'],
            $logMessage
        );

        Log::info($finalMessage);
    }

    /**
     * Çağrılan class ve method bilgisini al
     *
     * @return array
     */
    private function getCallerInfo(): array
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);
        $caller = $trace[2];

        return [
            'class' => class_basename($caller['class']),
            'method' => $caller['function']
        ];
    }
}
