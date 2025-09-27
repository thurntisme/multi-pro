<?php
declare(strict_types=1);

namespace App\Core;

class Dotenv
{
    private const ENV_PATH = APP_ROOT . '/.env';

    public function load(): void
    {
        $lines = file(self::ENV_PATH, FILE_IGNORE_NEW_LINES);

        foreach ($lines as $line) {
            if (!empty($line) && $line[0] !== '#') { // Ignore empty lines and comments
                list($name, $value) = explode("=", $line, 2);

                $_ENV[$name] = $value;
            }
        }
    }
}