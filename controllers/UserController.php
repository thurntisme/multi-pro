<?php

require_once DIR . '/services/UserService.php';

class UserController
{
    private $user_id;
    private $userService;
    private $pdo;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->pdo = $pdo;
        $this->user_id = $user_id;
        $this->userService = new UserService($pdo);
    }

    public function getUserById($id)
    {
        return $this->userService->getUser('id', $id);
    }

    public function getUserByEmail($email)
    {
        return $this->userService->getUser('email', $email);
    }

    public function updateUser($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $username = $_POST['username'] ?? '';

            if (empty($username)) {
                $_SESSION['message'] = 'Username are required.';
                $_SESSION['message_type'] = 'danger';

                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            }

            try {
                $this->userService->updateUser($id, $username, $first_name, $last_name);
                $_SESSION['message'] = "User have already been updated.";
                $_SESSION['message_type'] = 'success';
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            $_SESSION['message'] = 'Invalid request method.';
            $_SESSION['message_type'] = 'danger';

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    }

    public function getUserFullName($id)
    {
        $currentUser = $this->userService->getUser('id', $id);
        return $currentUser['first_name'] . ' ' . $currentUser['last_name'];
    }

    public function getSystemUser()
    {
        return $this->userService->getUser('username', 'system');
    }

    // Get all users
    public function listUsers()
    {
        // return $this->todoService->getAllTodos();
        return [
            'list' => $this->getUsersSQL("result"),
            'count' => $this->getUsersSQL("count"),
        ];
    }

    // Handle listing all users
    public function getUsersSQL($queryType = "result")
    {
        // Pagination parameters
        $itemsPerPage = 10; // Number of results per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

        // Search keyword
        $keyword = isset($_GET['s']) ? $_GET['s'] : '';
        $joined_date = isset($_GET['joined_date']) ? $_GET['joined_date'] : '';
        $user_role = isset($_GET['user_role']) ? $_GET['user_role'] : '';

        $selectSql = $queryType === "result" ? "SELECT * FROM users" : "SELECT COUNT(*) FROM users";
        $sql = $selectSql . " WHERE id != $this->user_id ";

        if ($keyword !== '') {
            $keyword = '%' . $keyword . '%'; // Prepare for LIKE search
            $sql .= " AND (title LIKE :keyword OR tags LIKE :keyword OR content LIKE :keyword)";
        }

        if ($joined_date !== '') {
            $date_array = explode('to', $joined_date);
            $date_array = array_map('trim', $date_array);
            list($startDate, $endDate) = $date_array;
            $endDate = $endDate ?? $startDate;
            $sql .= " AND created_at BETWEEN :start_date AND :end_date";
        }
        if ($user_role !== '') {
            $sql .= " AND role = :role";
        }

        // Sorting parameters (optional)
        $sortColumn = $_GET['sort'] ?? 'created_at';
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
        if ($startDate && $endDate) {
            $startDateTime = date('Y-m-d 00:00:00', strtotime($startDate));
            $endDateTime = date('Y-m-d 23:59:59', strtotime($endDate));
            $stmt->bindParam(':start_date', $startDateTime, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $endDateTime, PDO::PARAM_STR);
        }
        if ($user_role != '') {
            $stmt->bindParam(':role', $user_role, PDO::PARAM_STR);
        }

        // Execute the query
        $stmt->execute();
        return $queryType === "result" ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchColumn();
    }
}
