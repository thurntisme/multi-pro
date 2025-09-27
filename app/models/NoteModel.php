<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class NoteModel extends Model
{
    protected function getTable(): string
    {
        return 'ebook_notes';
    }

    public function findNote(int $ebook_id, int $page_num, int $user_id): ?string
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE ebook_id = :ebook_id AND page_num = :page_num AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['ebook_id' => $ebook_id, 'page_num' => $page_num, 'user_id' => $user_id]);
        $note = $stmt->fetch(PDO::FETCH_ASSOC);
        return $note ? (string) $note['id'] : null;
    }
}
