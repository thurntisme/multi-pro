<?php

require 'services/BookService.php';

class BookController
{
    private $user_id;
    private $pdo;
    private $bookService;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->bookService = new BookService($pdo);
    }

    // Handle creating a new book
    public function createBook()
    {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $status = $_POST['status'] ?? '';
        $url = $_POST['url'] ?? '';

        if ($title) {
            $this->bookService->createBook($title, $content, $tags, $status, $url);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Book created successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create book";
        }

        header("Location: " . home_url("book"));
        exit;
    }

    // Handle updating a book
    public function updateBook()
    {
        $id = $_POST['book_id'] ?? '';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $status = $_POST['status'] ?? '';
        $url = $_POST['url'] ?? '';

        if ($id && $title) {
            $rowsAffected = $this->bookService->updateBook($id, $title, $content, $tags, $status, $url);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Book updated successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update book.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Book ID and service name are required.";
        }

        header("Location: " . home_url("book/edit") . '?id=' . $id);
        exit;
    }

    // Handle deleting a book
    public function deleteBook()
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->bookService->deleteBook($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Book deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete book.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to delete book.";
        }

        header("Location: " . home_url("book"));
        exit;
    }

    // Get all books

    public function listBooks()
    {
        // return $this->bookService->getAllBooks();
        return [
            'list' => $this->getBooksSQL("result"),
            'count' => $this->getBooksSQL("count"),
        ];
    }

    // Handle listing all books

    public function getBooksSQL($queryType = "result")
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
        $status = isset($_GET['status']) ? $_GET['status'] : '';

        $selectSql = $queryType === "result" ? "SELECT * FROM books" : "SELECT COUNT(*) FROM books";
        $sql = $selectSql . " WHERE user_id = $this->user_id ";

        if ($keyword !== '') {
            $sql .= " AND (title LIKE :keyword OR tags LIKE :keyword OR content LIKE :keyword)";
        }

        if ($type !== '') {
            $sql .= " AND type = :type";
        }
        if ($status !== '') {
            $sql .= " AND status = :status";
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
        if ($status !== '') {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }
        if ($startDate && $endDate) {
            $stmt->bindParam(':start_date', $startDate);
            $stmt->bindParam(':end_date', $endDate);
        }

        // Execute the query
        $stmt->execute();
        return $queryType === "result" ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchColumn();
    }

    // Handle viewing a single book

    public function viewBook($id)
    {
        if ($id) {
            return $this->bookService->getBookById($id);
        }

        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Book ID is required.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}