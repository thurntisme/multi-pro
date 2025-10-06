<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class UploadModel extends Model
{
    protected function getTable(): string
    {
        return 'uploads';
    }

    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->getTable()} WHERE user_id = :user_id LIMIT 1");
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $row ?: [];
    }
}
