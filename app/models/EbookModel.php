<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class EbookModel extends Model
{
    protected function getTable(): string
    {
        return 'ebooks';
    }

    public function getAllEbooks(int $userId): ?array
    {
        $sql = "
            SELECT 
                u.id,
                u.filename,
                u.filepath,
                u.uploaded_at,
                CASE WHEN e.id IS NOT NULL THEN 1 ELSE 0 END AS is_read,
                e.id AS ebook_id,
                e.page_num,
                e.last_read_at
            FROM uploads u
            LEFT JOIN ebooks e 
                ON e.upload_id = u.id 
                AND e.user_id = u.user_id
            WHERE u.user_id = :user_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $row ?: [];
    }

    public function getAvailableEbooks(int $userId): ?array
    {
        $sql = "
            SELECT u.id, u.filename, u.filepath
            FROM uploads u
            WHERE u.user_id = :user_id
            AND NOT EXISTS (
                SELECT 1
                FROM ebooks e
                WHERE e.upload_id = u.id
                    AND e.user_id = u.user_id
            )
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $row ?: [];
    }

    public function getReadEbooks(int $userId): ?array
    {
        $sql = "
            SELECT u.id, u.filename, u.filepath,
                e.id AS ebook_id, e.page_num, e.last_read_at
            FROM uploads u
            INNER JOIN ebooks e ON e.upload_id = u.id
            WHERE u.user_id = :user_id
            AND e.user_id = u.user_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $row ?: [];
    }

    public function findByUserId(int $userId, string|int $id): array|bool
    {
        $sql = "
            SELECT u.id, u.filename, u.filepath,
                e.id AS ebook_id, e.page_num, e.last_read_at
            FROM uploads u
            INNER JOIN ebooks e ON e.upload_id = u.id
            WHERE u.user_id = :user_id
            AND e.user_id = u.user_id
            AND e.id = :id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        $ebook = $stmt->fetch(PDO::FETCH_ASSOC);
        $ebook['note_content'] = $this->getEbookNote($userId, $ebook['ebook_id'], $ebook['page_num']);
        return $ebook ?: false;
    }

    public function getEbookNote(int $userId, string|int $ebook_id, int $page_num)
    {
        $sql = "
            SELECT content
            FROM ebook_notes
            WHERE user_id = :user_id
            AND ebook_id = :ebook_id
            AND page_num = :page_num
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId, 'ebook_id' => $ebook_id, 'page_num' => $page_num]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['content'] ?? '';
    }
}
