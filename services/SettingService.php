<?php

class SettingService
{
    private $pdo;

    public function __construct($pdo)
    {
        global $user_id;
        $this->pdo = $pdo;
    }

    // Create or update a setting
    public function setSetting($postData)
    {
        $sql = "
        INSERT INTO system_settings (option_key, option_value)
        VALUES (:key, :value)
        ON DUPLICATE KEY UPDATE 
            option_value = VALUES(option_value);
        ";

        $stmt = $this->pdo->prepare($sql);

        // Start a transaction
        $this->pdo->beginTransaction();

        // Loop through the data and execute the prepared statement
        foreach ($postData as $key => $value) {
            $stmt->execute([':key' => $key, ':value' => $value]);
        }

        // Commit the transaction
        $this->pdo->commit();

        return $stmt->rowCount();
    }

    // Get a setting by key
    public function getSetting($key)
    {
        $sql = "SELECT * FROM system_settings WHERE option_key = :option_key";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':option_key' => $key]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all settings
    public function getAllSettings()
    {
        $sql = "SELECT * FROM system_settings";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
