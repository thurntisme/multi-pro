<?php

require 'services/BlogService.php';

class BlogController
{
    private $user_id;
    private $pdo;
    private $blogService;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->blogService = new BlogService($pdo);
    }

    // Handle creating a new blog
    public function createBlog($firstSlug)
    {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $category = $_POST['category'] ?? '';
        $ref_url = $_POST['ref_url'] ?? '';

        if ($title) {
            $this->blogService->createBlog($title, $content, $tags, $category, $ref_url);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Blog created successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create blog";
        }

        header("Location: " . home_url($firstSlug));
        exit;
    }

    // Handle updating a blog
    public function updateBlog($firstSlug)
    {
        $id = $_POST['blog_id'] ?? '';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $category = $_POST['category'] ?? '';
        $ref_url = $_POST['ref_url'] ?? '';

        if ($id && $title) {
            $rowsAffected = $this->blogService->updateBlog($id, $title, $content, $tags, $category, $ref_url);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Blog updated successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update blog.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Blog ID and service name are required.";
        }

        header("Location: " . home_url($firstSlug . "/edit") . '?id=' . $id);
        exit;
    }

    // Handle deleting a blog
    public function deleteBlog($firstSlug)
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->blogService->deleteBlog($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Blog deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete blog.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to delete blog.";
        }

        header("Location: " . home_url($firstSlug));
        exit;
    }

    // Get all blogs

    public function listBlogs()
    {
        return [
            'list' => $this->getBlogsSQL("result"),
            'count' => $this->getBlogsSQL("count"),
        ];
    }

    // Handle listing all blogs

    public function getBlogsSQL($queryType = "result", $category = '')
    {
        // Pagination parameters
        $itemsPerPage = 12; // Number of results per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

        // Search keyword
        $keyword = isset($_GET['s']) ? $_GET['s'] : '';
        $keyword = '%' . $keyword . '%'; // Prepare for LIKE search

        // Filter last updated
        $lastUpdated = isset($_GET['last_updated']) ? $_GET['last_updated'] : '';

        // Filter by role (optional)
        $type = isset($_GET['type']) ? $_GET['type'] : '';

        $selectSql = $queryType === "result" ? "SELECT * FROM blogs" : "SELECT COUNT(*) FROM blogs";
        $sql = $selectSql . " WHERE user_id = $this->user_id ";
        if (!empty($category)) {
            $sql .= " AND category = '$category' ";
        }

        if ($keyword !== '') {
            $sql .= " AND (title LIKE :keyword OR tags LIKE :keyword OR content LIKE :keyword)";
        }

        if ($type !== '') {
            $sql .= " AND type = :type";
        }

        list($startDate, $endDate) = getDateRange($lastUpdated);
        if ($lastUpdated !== '') {
            $sql .= " AND datetime(updated_at, '" . getTimezoneOffset() . "') BETWEEN :start_date AND :end_date";
        }

        // Sorting parameters (optional)
        $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'updated_at'; // Default sort by updated_at
        $sortOrder = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) ? strtoupper($_GET['order']) : 'DESC'; // Default to DESC

        // Add the ORDER BY clause dynamically
        $sql .= " ORDER BY $sortColumn $sortOrder";

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
        if ($type !== '') {
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        }
        if ($startDate && $endDate) {
            $stmt->bindParam(':start_date', $startDate);
            $stmt->bindParam(':end_date', $endDate);
        }

        // Execute the query
        $stmt->execute();
        return $queryType === "result" ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchColumn();
    }

    // Handle listing all blogs

    public function listBlogsByCategory($category)
    {
        return [
            'list' => $this->getBlogsSQL("result", $category),
            'count' => $this->getBlogsSQL("count", $category),
        ];
    }

    // Handle viewing a single blog
    public function viewBlog($id)
    {
        if ($id) {
            return $this->blogService->getBlogById($id);
        }

        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Blog ID is required.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
