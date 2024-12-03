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

    // Initialize default settings if the table is empty
    public function initialize()
    {
        // Check if the settings table is empty
        $sql = "SELECT COUNT(*) FROM settings";
        $stmt = $this->pdo->query($sql);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            try {
                // Insert each default setting into the settings table
                $this->setSetting(INIT_SETTINGS);

                echo "Initialized settings successfully\n";
            } catch (Throwable $th) {
                echo "Failed to initialize settings: " . $th->getMessage() . "\n";
            }
        }
    }

    // Create or update a setting
    public function setSetting($postData)
    {
        $sql = "
        INSERT INTO settings (key, value, updated_at, user_id)
        VALUES (:key, :value, CURRENT_TIMESTAMP, :user_id)
        ON CONFLICT(key) DO UPDATE SET value = :value, updated_at = CURRENT_TIMESTAMP";

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
        $stmt = $this->pdo->query($sql);
        $stmt->execute([':user_id' => $this->user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
