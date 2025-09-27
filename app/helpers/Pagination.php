<?php

namespace App\Helpers;

use PDO;

class Pagination
{
    private static PDO $db;

    public function __construct(PDO $db)
    {
        self::$db = $db;
    }

    public static function getBaseParams(int $defaultLimit = 10, int $maxLimit = 100): array
    {
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? min($maxLimit, max(1, (int) $_GET['limit'])) : $defaultLimit;
        $offset = ($page - 1) * $limit;

        return [
            "page" => $page,
            "limit" => $limit,
            "offset" => $offset,
        ];
    }

    public static function buildMeta(array $params, int $total): array
    {
        $limit = $params['limit'];
        $page = $params['page'];
        $totalPages = (int) ceil($total / $limit);

        return [
            "page" => $page,
            "limit" => $limit,
            "total" => $total,
            "totalPages" => $totalPages,
            "hasNext" => $page < $totalPages,
            "hasPrev" => $page > 1
        ];
    }

    public static function countAll(string $table, string $query, array $params): int
    {
        $sql = "SELECT COUNT(*) FROM {$table}";
        if ($query) {
            $sql .= " WHERE {$query}";
        }
        $stmt = self::$db->prepare($sql);
        if ($query) {
            foreach ($params as $key => $value) {
                $stmt->bindValue(":{$key}", $value, \PDO::PARAM_STR);
            }
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public static function getAllPaginated(string $table, string $query, array $baseParams, array $params): array
    {
        $limit = $baseParams['limit'];
        $offset = $baseParams['offset'];
        $whereClause = $query ? " WHERE {$query}" : '';
        $stmt = self::$db->prepare("SELECT * FROM {$table} {$whereClause} LIMIT :limit OFFSET :offset");
        if ($query) {
            foreach ($params as $key => $value) {
                $stmt->bindValue(":{$key}", $value, \PDO::PARAM_STR);
            }
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?? [];
    }

    public static function get(string $table, string $query, array $params): array
    {
        $baseParams = self::getBaseParams();
        $total = self::countAll($table, $query, $params);
        return [
            "data" => self::getAllPaginated($table, $query, $baseParams, $params),
            "pagination" => self::buildMeta($baseParams, $total),
        ];
    }
}