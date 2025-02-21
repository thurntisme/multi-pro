<?php

require 'services/WebsiteService.php';

class WebsiteController
{
    private $user_id;
    private $pdo;
    private $websiteService;
    private $systemController;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        global $systemController;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->websiteService = new WebsiteService($pdo);
        $this->systemController = $systemController;
    }

    // Handle creating a new website
    public function createWebsite()
    {
        // Check CSRF Token
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create website. CSRF token mismatch";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $url = $_POST['url'] ?? '';
        $tags = $_POST['tags'] ?? '';

        if ($title) {
            $this->websiteService->createWebsite($title, $content, $url, $tags);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Website created successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create website";
        }

        header("Location: " . home_url("app/website"));
        exit;
    }

    // Handle updating a website
    public function updateWebsite()
    {
        // Check CSRF Token
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to update website. CSRF token mismatch";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        $id = $_POST['website_id'] ?? '';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $url = $_POST['url'] ?? '';
        $tags = $_POST['tags'] ?? '';

        if ($id && $title) {
            $rowsAffected = $this->websiteService->updateWebsite($id, $title, $content, $url, $tags);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Website updated successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update website.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Website ID and service name are required.";
        }

        header("Location: " . home_url("website/edit") . '?id=' . $id);
        exit;
    }

    // Handle deleting a website
    public function deleteWebsite()
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->websiteService->deleteWebsite($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Website deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete website.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to delete website.";
        }

        header("Location: " . home_url("app/website"));
        exit;
    }

    // Get all websites

    public function listWebsites()
    {
        // return $this->websiteService->getAllWebsites();
        return [
            'list' => $this->getWebsitesSQL("result"),
            'count' => $this->getWebsitesSQL("count"),
        ];
    }

    // Handle listing all websites

    public function getWebsitesSQL($queryType = "result")
    {
        // Pagination parameters
        $itemsPerPage = 10; // Number of results per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

        // Search keyword
        $keyword = isset($_GET['s']) ? $_GET['s'] : '';

        $selectSql = $queryType === "result" ? "SELECT * FROM websites" : "SELECT COUNT(*) FROM websites";
        $sql = $selectSql . " WHERE user_id = $this->user_id ";

        if ($keyword !== '') {
            $keyword = '%' . $keyword . '%'; // Prepare for LIKE search
            $sql .= " AND (title LIKE :keyword OR tags LIKE :keyword OR content LIKE :keyword)";
        }

        // Sorting parameters (optional)
        $sortColumn = $_GET['sort'] ?? 'updated_at';
        $sortOrder = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) ? strtoupper($_GET['order']) : 'DESC'; // Default to DESC

        if ($queryType === "result") {
            // Add pagination (LIMIT and OFFSET)
            $sql .= " LIMIT $itemsPerPage OFFSET $offset";
        }

        // Prepare the query
        $stmt = $this->pdo->prepare($sql);

        // Bind parameters
        if ($keyword != '') {
            $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        }

        // Execute the query
        $stmt->execute();
        return $queryType === "result" ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchColumn();
    }

    // Handle viewing a single website

    public function viewWebsite($id)
    {
        if ($id) {
            return $this->websiteService->getWebsiteById($id);
        }

        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Website ID is required.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
