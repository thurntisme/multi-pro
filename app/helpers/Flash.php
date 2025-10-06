<?php

namespace App\Helpers;

use App\Core\Session;

class Flash
{
    private const FLASH_KEY = 'flash';

    private static function getFlashKey(string $type): string
    {
        return self::FLASH_KEY . '.' . $type;
    }

    /**
     * Add a flash message
     *
     * @param string $type success|error|warning|info
     * @param string $message
     */
    public static function add(string $type, string $message): void
    {
        Session::set(self::getFlashKey($type), $message);
    }

    /**
     * Get flash messages and clear them
     *
     * @param string|null $type
     * @return array
     */
    public static function get(?string $type = null): array
    {
        if ($type !== null) {
            $messages = Session::get(self::getFlashKey($type), '');
            Session::forget(self::getFlashKey($type));
            return [$messages];
        }

        $all = [];
        foreach (self::getAvailableFlashKeys() as $key) {
            $all[$key] = self::get($key);
        }
        return $all;
    }

    private static function getAvailableFlashKeys(): array
    {
        $keys = ['success', 'error', 'warning', 'info'];
        $availableKeys = [];
        foreach ($keys as $key) {
            if (self::has($key)) {
                $availableKeys[] = $key;
            }
        }
        return $availableKeys;
    }

    /**
     * Check if there is flash message
     *
     * @param string $type
     * @return bool
     */
    public static function has(string $type): bool
    {
        return Session::has(self::getFlashKey($type));
    }
}
