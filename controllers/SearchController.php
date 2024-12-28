<?php

require 'services/SearchService.php';

class SearchController
{
    private $user_id;
    private $pdo;
    private $searchService;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->searchService = new SearchService($pdo);
    }

    // Handle creating a new system
    public function createSystem()
    {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $status = $_POST['status'] ?? '';
        $priority = $_POST['priority'] ?? '';
        //        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : date('Y-m-d');
        $due_date = $_POST['due_date'] ?? '';

        if ($title) {
            $this->searchService->createSystem($title, $content, $tags, $status, $priority, $due_date);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "System created successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create system";
        }

        header("Location: " . home_url("system"));
        exit;
    }

    // Handle updating a system
    public function updateSystem()
    {
        $id = $_POST['system_id'] ?? '';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $status = $_POST['status'] ?? '';
        $priority = $_POST['priority'] ?? '';
        //        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : date('Y-m-d');
        $due_date = $_POST['due_date'] ?? '';

        if ($id && $title) {
            $rowsAffected = $this->searchService->updateSystem($id, $title, $content, $tags, $status, $priority, $due_date);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "System updated successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update system.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "System ID and service name are required.";
        }

        header("Location: " . home_url("system/edit") . '?id=' . $id);
        exit;
    }

    // Handle deleting a system
    public function deleteSystem()
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->searchService->deleteSystem($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "System deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete system.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to delete system.";
        }

        header("Location: " . home_url("system"));
        exit;
    }

    // Get all systems

    public function listSearchResults($keyword): array
    {
        return [
            'list' => $this->getSearchSQL("result", $keyword),
            'count' => $this->getSearchSQL("count", $keyword),
        ];
    }

    // Handle listing all systems

    public function getSearchSQL($queryType = "result", $keyword = "")
    {
        // Pagination parameters
        $itemsPerPage = 10; // Number of results per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

        // Search keyword
        $keyword = $keyword ?? (isset($_GET['s']) ? ('%' . $_GET['s'] . '%') : '');

        $selectSql = $queryType === "result" ? "" : "SELECT COUNT(*) AS total_matches FROM ( ";
        $sql = $selectSql . "
        SELECT
            id,
            title,
            content,
            tags,
            updated_at,
            'todos' AS table_name,
            'todo' AS slug
        FROM todos
        WHERE title LIKE :keyword OR content LIKE :keyword OR tags LIKE :keyword
        
        UNION ALL
        
        SELECT
            id,
            title,
            content,
            tags,
            updated_at,
            'blogs' AS table_name,
            category AS slug
        FROM blogs
        WHERE title LIKE :keyword OR content LIKE :keyword OR tags LIKE :keyword
        
        UNION ALL
        
        SELECT
            id,
            title,
            content,
            tags,
            updated_at,
            'books' AS table_name,
            'book' AS slug
        FROM books
        WHERE title LIKE :keyword OR content LIKE :keyword OR tags LIKE :keyword
        
        UNION ALL
        
        SELECT
            id,
            title,
            content,
            tags,
            updated_at,
            'courses' AS table_name,
            'course' AS slug
        FROM courses
        WHERE title LIKE :keyword OR content LIKE :keyword OR tags LIKE :keyword
        
        UNION ALL
        
        SELECT
            id,
            title,
            content,
            tags,
            updated_at,
            'gits' AS table_name,
            'git' AS slug
        FROM gits
        WHERE title LIKE :keyword OR content LIKE :keyword OR tags LIKE :keyword
        
        UNION ALL
        
        SELECT
            id,
            title,
            content,
            tags,
            updated_at,
            'notes' AS table_name,
            'note' AS slug
        FROM notes
        WHERE title LIKE :keyword OR content LIKE :keyword OR tags LIKE :keyword
        ";

        if ($queryType === "result") {
            // Sorting parameters (optional)
            $sortColumn = $_GET['sort'] ?? 'updated_at';
            $sortOrder = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) ? strtoupper($_GET['order']) : 'DESC'; // Default to DESC

            // Add pagination (LIMIT and OFFSET)
            $sql .= " ORDER BY $sortColumn $sortOrder LIMIT $itemsPerPage OFFSET $offset;";
        } else {
            $sql .= " ) AS total_matches";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        $stmt->execute();
        return $queryType === "result" ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchColumn();
    }

    // Handle viewing a single system

    public function viewSystem($id)
    {
        if ($id) {
            return $this->searchService->getSystemById($id);
        }

        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "System ID is required.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
