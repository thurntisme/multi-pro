<?php

class AuthenticationService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createUser($firstName, $lastName, $username, $email, $password)
    {
        $sql = "INSERT INTO users (first_name, last_name, username, email, password) VALUES (:first_name, :last_name, :username, :email, :password)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':first_name' => $firstName, ':last_name' => $lastName, ':username' => $username, ':email' => $email, ':password' => password_hash($password, PASSWORD_DEFAULT)]);

        return $this->pdo->lastInsertId();
    }

    public function userExists($username)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetchColumn() > 0;
    }

    public function loginUser($email, $password)
    {
        global $commonController;

        // Retrieve user from database
        $user = $this->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Manage tokens for the user
            $this->manageUserTokens($user['id']);

            // Generate and store a new token
            $token = $this->createUserToken($user['id'], $commonController);

            // Update user's last login time
            $this->updateUserLastLogin($user['id']);

            return $token;
        }

        return null;
    }

    private function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    private function manageUserTokens($userId)
    {
        $stmt = $this->pdo->prepare('SELECT id FROM tokens WHERE user_id = ? ORDER BY last_time_login ASC');
        $stmt->execute([$userId]);
        $tokens = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Remove the oldest token if more than 3 exist
        if (count($tokens) >= 3) {
            $oldestTokenId = $tokens[0];
            $stmt = $this->pdo->prepare('DELETE FROM tokens WHERE id = ?');
            $stmt->execute([$oldestTokenId]);
        }
    }

    private function createUserToken($userId, $commonController)
    {
        $token = bin2hex(random_bytes(16));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 day'));
        $deviceDetails = $commonController->getDeviceDetails();
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $lastTimeLogin = date('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare(
            'INSERT INTO tokens (user_id, token, expires_at, device_name, device_type, ip_address, last_time_login) 
        VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $userId,
            $token,
            $expiresAt,
            $deviceDetails['device_name'],
            $deviceDetails['device_type'],
            $ipAddress,
            $lastTimeLogin
        ]);

        return $token;
    }

    private function updateUserLastLogin($userId)
    {
        $lastTimeLogin = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare('UPDATE users SET last_login = :last_login, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
        $stmt->execute(['id' => $userId, 'last_login' => $lastTimeLogin]);
    }

    public function logoutUser($token)
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $stmt = $this->pdo->prepare('DELETE FROM tokens WHERE token = ? AND ip_address = ?');
        $stmt->execute([$token, $ipAddress]);
    }

    public function getTokenData($token)
    {
        $timestamp = (new DateTime())->format('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare("SELECT * FROM tokens WHERE token = ? AND expires_at > ?");
        $stmt->execute([$token, $timestamp]);
        return $stmt->fetch();
    }

    // Get all devices

    public function getUserIdByToken($token)
    {
        $user = $this->getAllDevices($token);
        if (count($user) > 0) {
            return $user;
        }
        return null;
    }

    public function getAllDevices($user_id)
    {
        $sql = "SELECT * FROM tokens WHERE user_id = :user_id ORDER BY updated_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete a device

    public function deleteDevice($id)
    {
        $sql = "DELETE FROM tokens WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount();
    }
}
