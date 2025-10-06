<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;

class ErrorHandler
{
    private static $logFile = LOGS_DIR . '/app-error.log';

    public function register(): void
    {
        set_error_handler([$this, 'handleError']);

        register_shutdown_function([$this, 'handleShutdown']);

        set_exception_handler([$this, 'handleException']);
    }

    public function handleError(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline
    ): bool {
        $message = "[Error {$errno}] {$errstr} in {$errfile} on line {$errline}";
        self::log($message);

        if ($_ENV['SHOW_ERRORS']) {
            echo "<b>Custom Error:</b> {$message}<br>";
        }

        return true;
    }

    public function handleException(Throwable $exception): void
    {
        $exceptionMsg = $exception->getMessage();
        $message = "[Uncaught Exception] " . $exceptionMsg .
            " in " . $exception->getFile() .
            " on line " . $exception->getLine();
        self::log($message);

        if ($_ENV['SHOW_ERRORS']) {
            echo "<b>Exception:</b> {$message}<br>";
        } else {
            if ($exception instanceof \PDOException) {
                $view = VIEWS_DIR . 'errors/db-error.php';
                if (file_exists($view)) {
                    include $view;
                    return;
                }
            }
        }

    }

    public function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null) {
            $message = "[Fatal Error] {$error['message']} in {$error['file']} on line {$error['line']}";
            $this->log($message);

            if ($_ENV['SHOW_ERRORS']) {
                echo "<b>Fatal Error:</b> {$message}<br>";
            }
        }
    }

    private function log(string $err_msg): void
    {
        error_log(date("[Y-m-d H:i:s] ") . $err_msg . PHP_EOL, 3, self::$logFile);
    }
}