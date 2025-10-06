<?php
namespace App\Helpers;

class Football
{
    public static function generatePlayerUUID($length = 32)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $uuid = '';

        $maxIndex = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $uuid .= $chars[random_int(0, $maxIndex)];
        }

        return $uuid;
    }
}