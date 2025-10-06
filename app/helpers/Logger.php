<?php

namespace App\Helpers;

class Logger
{
    private static string $appLogFile = LOG_DIR . '/app.log';
    private static string $requestLogFile = LOG_DIR . '/request.log';

    public static function log(string $level, string $message, array $context = []): void
    {
        $context = self::maskSensitive($context);

        $logEntry = [
            'timestamp' => date('c'), // ISO 8601 format
            'level' => strtoupper($level),
            'message' => $message,
            'context' => $context,
            'pid' => getmypid(),
            'timezone' => date_default_timezone_get(),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'CLI',
        ];

        // Convert to JSON
        $json = json_encode($logEntry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $logFile = self::$appLogFile;
        if ($level === 'REQUEST') {
            $logFile = self::$requestLogFile;
        }

        // Append to log file
        file_put_contents($logFile, $json . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    public static function request(string $message, array $context = []): void
    {
        self::log('REQUEST', $message, $context);
    }

    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        self::log('DEBUG', $message, $context);
    }

    public static function warn(string $message, array $context = []): void
    {
        self::log('WARN', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }

    private static function maskSensitive(array $data): array
    {
        $sensitiveKeys = ['password', 'token', 'authorization', 'api_key'];
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $value = self::maskSensitive($value);
            } elseif (in_array(strtolower($key), $sensitiveKeys)) {
                $value = '***MASKED***';
            }
        }
        return $data;
    }
}