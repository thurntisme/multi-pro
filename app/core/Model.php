<?php

declare(strict_types=1);

namespace App\Core;

use App\Helpers\Pagination;
use PDO;

abstract class Model
{
    protected $table;
    protected PDO $db;

    protected array $errors = [];
    protected $pagination;

    public function __construct(protected Database $database)
    {
        $this->db = $this->database->getConnection();
        $this->pagination = new Pagination($this->db);
    }

    abstract protected function getTable(): string;

    public function findAll(string|int $user_id): array
    {
        return $this->pagination->get($this->getTable(), "user_id = :user_id", ["user_id" => $user_id]);
    }

    public function find(string $id, string|int $user_id): array|bool
    {
        $sql = "SELECT *
                FROM {$this->getTable()}
                WHERE id = :id AND user_id = :user_id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isOwner(string $id, string|int $user_id): bool
    {
        $sql = "SELECT *
                FROM {$this->getTable()}
                WHERE id = :id AND user_id = :user_id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function insert(array $data): bool
    {
        $this->validate($data);

        if (!empty($this->errors)) {
            return false;
        }

        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));

        $sql = "INSERT INTO {$this->getTable()} ($columns)
                VALUES ($placeholders)";

        $stmt = $this->db->prepare($sql);

        $i = 1;

        foreach ($data as $value) {

            $type = match (gettype($value)) {
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };

            $stmt->bindValue($i++, $value, $type);

        }

        return $stmt->execute();
    }

    public function update(string $id, string|int $user_id, array $data): bool
    {
        $this->isOwner($id, $user_id);
        $this->validate($data);

        if (!empty($this->errors)) {
            return false;
        }

        $sql = "UPDATE {$this->getTable()} ";

        unset($data["id"]);

        $assignments = array_keys($data);

        array_walk($assignments, function (&$value) {
            $value = "$value = ?";
        });

        $sql .= " SET " . implode(", ", $assignments);

        $sql .= " WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        $i = 1;

        foreach ($data as $value) {

            $type = match (gettype($value)) {
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };

            $stmt->bindValue($i++, $value, $type);

        }

        $stmt->bindValue($i, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete(string $id, string|int $user_id): bool
    {
        $this->isOwner($id, $user_id);

        $sql = "DELETE FROM {$this->getTable()}
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    protected function validate(array $data): void
    {
        // TODO: Check owner of post
    }

    public function getLastInsertId(): string
    {
        return $this->db->lastInsertId();
    }

    protected function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}