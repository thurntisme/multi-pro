<?php

require_once 'services/SettingService.php';

class SettingController
{
    private $settingService;

    public function __construct()
    {
        global $pdo;
        $this->settingService = new SettingService($pdo);
    }

    // Handle creating or updating a setting
    public function setSetting($postData)
    {
        if (isset($postData)) {
            $this->settingService->setSetting($postData);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Setting saved successfully.";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Both key and value are required.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // Handle listing all settings
    public function listSettings()
    {
        return $this->settingService->getAllSettings();
    }

    // Handle viewing a single setting
    public function viewSetting($key)
    {
        if ($key) {
            return $this->settingService->getSetting($key);
        }

        return null;
    }
}
