<?php

require 'services/SettingService.php';

class SystemController
{
    private $settingService;
    private $user_id;

    public function __construct($user_id)
    {
        global $pdo;
        $this->user_id = $user_id;
        $this->settingService = new SettingService($pdo);
    }

    public function convertDateTime($utcTime)
    {
        if (empty($utcTime)) return '';
        $dateTime = $this->getDateTime($utcTime);
        // Format and display the converted time
        return $dateTime->format('Y-m-d H:i:s');
    }

    public function getDateTime($utcTime): DateTime
    {
        $timezone = $this->getTimezone();

        // Create a DateTime object in UTC
        $dateTime = new DateTime($utcTime, new DateTimeZone('UTC'));

        // Convert the time to the Asia/Ho_Chi_Minh time zone
        $dateTime->setTimezone(new DateTimeZone($timezone));

        return $dateTime;
    }

    public function getTimezone()
    {
        $timezone = $this->settingService->getSetting('timezone');
        return $timezone['option_value'] ?? 'UTC';
    }

    public function getCurrentDateTimeStr($format = 'Y-m-d H:i:s')
    {
        $timezone = $this->getTimezone();
        $dateTime = new DateTime('now', new DateTimeZone($timezone));
        return $dateTime->format($format);
    }

    function getTimezoneOffset()
    {
        $localTimezone = $this->getTimezone();

        // Create DateTime objects for UTC and local timezones
        $utc = new DateTime('now', new DateTimeZone('UTC'));
        $local = new DateTime('now', new DateTimeZone($localTimezone));

        // Calculate the difference between the two timezones
        $offset = $local->getOffset() - $utc->getOffset();

        // Convert offset to hours and format it
        $hours = $offset / 3600;
        $formattedOffset = ($hours >= 0 ? '+' : '-') . str_pad(abs($hours), 2, '0', STR_PAD_LEFT) . ' hours';

        return $formattedOffset;
    }

    public function getLanguage()
    {
        $timezone = $this->settingService->getSetting('language');
        return $timezone['option_value'] ?? 'en';
    }

    public function getFont()
    {
        $timezone = $this->settingService->getSetting('font-family');
        return $timezone['option_value'] ?? 'Helvetica';
    }

    public function convertDate($utcTime): string
    {
        if ($utcTime !== '0000-00-00') {
            $dateTime = $this->getDateTime($utcTime);
            // Format and display the converted time
            return $dateTime->format('Y-m-d');
        }
        return '';
    }

    public function convertTime($utcTime)
    {
        if (empty($utcTime)) return '';
        $dateTime = $this->getDateTime($utcTime);
        // Format and display the converted time
        return $dateTime->format('H:i:s');
    }

    public function getSiteName()
    {
        $siteName = $this->settingService->getSetting('site_name');
        return $siteName['value'] ?? DEFAULT_SITE_NAME;
    }

    public function checkUserOnline($last_time_login)
    {
        if (empty($last_time_login)) return false;
        $loginTime = $this->getDateTime($last_time_login);
        $currentTime = $this->getDateTime('');

        $interval = $loginTime->diff($currentTime);

        // Check if the difference is less than 1 day
        return $interval->days < 1 && $interval->invert == 0;
    }
}
