<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class UserModel extends Model
{
    protected function getTable(): string
    {
        return 'users';
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->getTable()} WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->getTable()} WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }
}
