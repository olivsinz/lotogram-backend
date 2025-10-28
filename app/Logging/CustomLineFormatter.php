<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;

class CustomLineFormatter extends LineFormatter
{
    const DETAILED_FORMAT = "[%datetime%] [%level_name%] %channel%: %message% %context% %extra%\n";

    public function __construct(
        ?string $format = null,
        ?string $dateFormat = null,
        bool $allowInlineLineBreaks = false,
        bool $ignoreEmptyContextAndExtra = false
    ) {
        parent::__construct($format ?? static::DETAILED_FORMAT, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra);
    }

    /**
     * Log kaydını formatlar ve bağlamsal bilgileri mesajın başına ekler.
     *
     * @param Monolog\LogRecord $record Monolog log kaydı.
     * @return string Formatlanmış log mesajı.
     */
    public function format(LogRecord $record): string
    {
        // DEBUG_BACKTRACE_IGNORE_ARGS ile performansı artırırken,
        // Yüksek bir limit belirleyerek doğru çağrıya ulaşmaya çalışıyoruz.
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 30); // Derinliği 30'a çıkardık

        $file = 'N/A';
        $line = 'N/A';
        $class = 'N/A';
        $function = 'N/A';

        // İlgili Stack Frame'i Bulun:
        // Amacımız, Log Facade'ını (yani Log::channel()->info()) çağıran asıl yeri bulmak.
        // Bunun için, Monolog'un, Laravel'in Logger'ının ve kendi formatlayıcımızın çağrılarını atlamalıyız.
        $foundLogCall = false;
        foreach ($trace as $i => $frame) {
            // Monolog'un iç çağrıları, Laravel'in loglama sınıfları ve bizim formatlayıcımız.
            $isInternalCall = isset($frame['class']) && (
                    str_starts_with($frame['class'], 'Monolog\\') ||
                    str_starts_with($frame['class'], 'Illuminate\\Log\\') ||
                    str_starts_with($frame['class'], 'App\\Logging\\CustomLineFormatter')
                );

            // Eğer şu anki frame bir "Log::..." çağrısıysa, bir sonraki döngüde dıştaki çağrıyı arayacağız.
            if ($isInternalCall && isset($frame['function']) && in_array($frame['function'], ['info', 'error', 'warning', 'debug', 'log', '__call', '__callStatic'])) {
                $foundLogCall = true;
                continue; // Bu frame'i atla, bir sonraki muhtemelen çağrı kaynağıdır.
            }

            // Eğer bir önceki frame log çağrısıysa ve şu anki frame dahili değilse, bu bizim aradığımız kaynaktır.
            if ($foundLogCall && !$isInternalCall && isset($frame['file']) && isset($frame['line'])) {
                $file = $this->stripBasePath($frame['file']);
                $line = $frame['line'];
                $class = $frame['class'] ?? 'Closure';
                $function = $frame['function'] ?? 'N/A';
                break; // İlk gerçek çağrıyı bulduk.
            }
        }

        // Eğer döngü bittiğinde hala bulunamadıysa (çok derin veya özel bir çağrı),
        // fallback olarak en üstteki uygulama katmanını bulmaya çalışalım.
        if ($file === 'N/A' || $line === 'N/A') {
            foreach ($trace as $frame) {
                if (isset($frame['file']) && str_starts_with($frame['file'], base_path('app/'))) {
                    $file = $this->stripBasePath($frame['file']);
                    $line = $frame['line'] ?? 'N/A';
                    $class = $frame['class'] ?? 'Closure';
                    $function = $frame['function'] ?? 'N/A';
                    break;
                }
            }
        }


        // Mesajın Başına Bağlamsal Bilgileri Ekleyin:
        $contextualPrefix = "[{$class}::{$function}@{$line}] " . PHP_EOL;

        // Orijinal mesajı yeni önekle güncelleyip yeni bir LogRecord oluşturun.
        $recordWithPrefix = new LogRecord(
            $record->datetime,
            $record->channel,
            $record->level,
            $contextualPrefix . $record->message, // Mesajı güncelliyoruz
            $record->context,
            $record->extra,
            $record->formatted
        );

        return parent::format($recordWithPrefix);
    }

    /**
     * Dosya yolundan uygulama temel yolunu kaldırır.
     *
     * @param string $file
     * @return string
     */
    protected function stripBasePath(string $file): string
    {
        $basePath = base_path();
        if (str_starts_with($file, $basePath)) {
            return substr($file, strlen($basePath) + 1);
        }
        return $file;
    }
}
