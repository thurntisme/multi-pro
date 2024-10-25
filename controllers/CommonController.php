<?php

require_once 'services/SettingService.php';

class CommonController
{
  private $settingService;

  public function __construct()
  {
    global $pdo;
    $this->settingService = new SettingService($pdo);
  }

  public function getTimezone()
  {
    $timezone = $this->settingService->getSetting('timezone');
    return $timezone['value'] ?? DEFAULT_TIMEZONE;
  }

  private function getDateTime($utcTime)
  {
    $timezone = $this->getTimezone();

    // Create a DateTime object in UTC
    $dateTime = new DateTime($utcTime, new DateTimeZone('UTC'));

    // Convert the time to the Asia/Ho_Chi_Minh time zone
    $dateTime->setTimezone(new DateTimeZone($timezone));

    return $dateTime;
  }

  public function convertDateTime($utcTime)
  {
    $dateTime = $this->getDateTime($utcTime);
    // Format and display the converted time
    return $dateTime->format('Y-m-d H:i:s');
  }

  public function convertDate($utcTime)
  {
    $dateTime = $this->getDateTime($utcTime);
    // Format and display the converted time
    return $dateTime->format('Y-m-d');
  }

  public function convertTime($utcTime)
  {
    $dateTime = $this->getDateTime($utcTime);
    // Format and display the converted time
    return $dateTime->format('H:i:s');
  }

  public function getSiteName()
  {
    $siteName = $this->settingService->getSetting('site_name');
    return $siteName['value'] ?? DEFAULT_SITE_NAME;
  }

  public function getToken()
  {
    return $_SESSION['token'] ?? "";
  }

  public function removeToken()
  {
    unset($_SESSION['token']);
  }

  public function getDeviceDetails()
  {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $deviceName = 'Unknown Device';
    $deviceType = 'Unknown Type';

    if (stripos($userAgent, 'iPhone') !== false) {
      $deviceName = 'iPhone';
      $deviceType = 'Mobile';
    } elseif (stripos($userAgent, 'iPad') !== false) {
      $deviceName = 'iPad';
      $deviceType = 'Tablet';
    } elseif (stripos($userAgent, 'Android') !== false) {
      if (preg_match('/Android.*; (.+) Build/', $userAgent, $matches)) {
        $deviceName = $matches[1];
      } else {
        $deviceName = 'Android Device';
      }
      $deviceType = 'Mobile';
    } elseif (stripos($userAgent, 'Windows NT') !== false) {
      $deviceName = 'Windows PC';
      $deviceType = 'Desktop';
    } elseif (stripos($userAgent, 'Macintosh') !== false) {
      $deviceName = 'Macintosh';
      $deviceType = 'Desktop';
    } elseif (stripos($userAgent, 'Linux') !== false) {
      $deviceName = 'Linux PC';
      $deviceType = 'Desktop';
    }

    return [
      'device_name' => $deviceName,
      'device_type' => $deviceType
    ];
  }

  function getCurrencySymbol($currencyCode)
  {
    $symbols = [
      'USD' => '$',
      'EUR' => '€',
      'GBP' => '£',
      'JPY' => '¥',
      'CAD' => '$',
      'AUD' => '$',
      'CHF' => 'CHF',
      'CNY' => '¥',
      'INR' => '₹',
      'KRW' => '₩',
      'BRL' => 'R$',
      'RUB' => '₽'
    ];

    return $symbols[$currencyCode] ?? $currencyCode;
  }
}