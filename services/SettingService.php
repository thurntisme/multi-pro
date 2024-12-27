<?php

class SettingService
{
    private $pdo;
    private $user_id;

    public function __construct($pdo)
    {
        global $user_id;
        $this->pdo = $pdo;
        $this->user_id = $user_id;
    }

    // Create or update a setting
    public function setSetting($postData)
    {
        $sql = "
        INSERT INTO settings (option_key, option_value, user_id)
        VALUES (:key, :value, :user_id)
        ON DUPLICATE KEY UPDATE 
            option_value = VALUES(option_value);
        ";

        $stmt = $this->pdo->prepare($sql);

        // Start a transaction
        $this->pdo->beginTransaction();

        // Loop through the data and execute the prepared statement
        foreach ($postData as $key => $value) {
            $stmt->execute([':key' => $key, ':value' => $value, ':user_id' => $this->user_id]);
        }

        // Commit the transaction
        $this->pdo->commit();

        return $stmt->rowCount();
    }

    // Get a setting by key
    public function getSetting($key)
    {
        $sql = "SELECT * FROM settings WHERE option_key = :option_key AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':option_key' => $key, ':user_id' => $this->user_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all settings
    public function getAllSettings()
    {
        $sql = "SELECT * FROM settings WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $this->user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
