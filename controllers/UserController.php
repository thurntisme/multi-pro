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

        $userQuery = "SELECT 
            users.id, 
            users.username,  
            users.first_name,  
            users.last_name, 
            users.email, 
            users.role, 
            users.created_at, 
            users.updated_at, 
            users.isEmailVerify, 
            users.isActive, 
            users.last_login,  
            MAX(tokens.last_time_login) AS last_time_login
        FROM 
            users
        LEFT JOIN 
            tokens ON users.id = tokens.user_id
        WHERE users.id != $this->user_id
        ";
        $sql = $queryType === "result" ? $userQuery : "SELECT COUNT(*) FROM users WHERE id != $this->user_id";

        if ($keyword !== '') {
            $keyword = '%' . $keyword . '%';
            $sql .= " AND (username LIKE :keyword OR first_name LIKE :keyword OR last_name LIKE :keyword OR email LIKE :keyword)";
        }

        $startDate = '';
        $endDate = '';
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

        if ($queryType === "result") {
            $sql .= " GROUP BY users.id";
        }

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

    public function viewUser($id)
    {
        $userData = $this->userService->getUser('id', $id);
        $listDevices = $this->getUserDevices($id);

        return array_merge($userData, ['devices' => $listDevices]);
    }

    private function getUserDevices($id)
    {
        $sql = "SELECT * FROM tokens WHERE user_id = :user_id ORDER BY updated_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changeUserStatus($id)
    {
        $userData = $this->userService->getUser('id', $id);
        $newStatus = $userData['isActive'] === 0 ? 1 : 0;
        $rowsAffected = $this->userService->changeUserStatus($id, $newStatus);
        if ($rowsAffected) {
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = sprintf(
                "User (ID: %d) status updated successfully to %s.",
                $id,
                $newStatus === 1 ? 'active' : 'inactive'
            );
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to update user.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
