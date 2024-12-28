<?php

require 'services/PlanService.php';

class PlanController
{
    private $user_id;
    private $pdo;
    private $planService;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->planService = new PlanService($pdo);
    }

    // Handle creating a new plan
    public function createPlan()
    {
        // Check CSRF Token
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to update plan. CSRF token mismatch";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $status = $_POST['status'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';

        if ($title) {
            $this->planService->createPlan($title, $content, $status, $start_date, $end_date);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Plan created successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create plan";
        }

        header("Location: " . home_url("plan"));
        exit;
    }

    // Handle updating a plan
    public function updatePlan()
    {
        // Check CSRF Token
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to update plan. CSRF token mismatch";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        $id = $_POST['plan_id'] ?? '';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $status = $_POST['status'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';

        if ($id && $title) {
            $rowsAffected = $this->planService->updatePlan($id, $title, $content, $status, $start_date, $end_date);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Plan updated successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update plan.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Plan ID and service name are required.";
        }

        header("Location: " . home_url("plan/edit") . '?id=' . $id);
        exit;
    }

    // Handle deleting a plan
    public function deletePlan()
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->planService->deletePlan($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Plan deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete plan.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to delete plan.";
        }

        header("Location: " . home_url("plan"));
        exit;
    }

    // Get all plans

    public function listPlans()
    {
        // return $this->planService->getAllPlans();
        return [
            'list' => $this->getPlansSQL("result"),
            'count' => $this->getPlansSQL("count"),
        ];
    }

    // Handle listing all plans

    public function getPlansSQL($queryType = "result")
    {
        // Pagination parameters
        $itemsPerPage = 10; // Number of results per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

        // Search keyword
        $keyword = isset($_GET['s']) ? $_GET['s'] : '';

        // Filter last updated
        $date_range = isset($_GET['date_range']) ? $_GET['date_range'] : '';

        // Filter by role (optional)
        $priority = isset($_GET['priority']) ? $_GET['priority'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';

        $selectSql = $queryType === "result" ? "SELECT * FROM plans" : "SELECT COUNT(*) FROM plans";
        $sql = $selectSql . " WHERE user_id = $this->user_id ";

        if ($keyword !== '') {
            $keyword = '%' . $keyword . '%'; // Prepare for LIKE search
            $sql .= " AND (title LIKE :keyword OR tags LIKE :keyword OR content LIKE :keyword)";
        }

        if ($priority !== '') {
            $sql .= " AND priority = :priority";
        }
        if ($status !== '') {
            $sql .= " AND status = :status";
        }

        if ($date_range !== '') {
            $date_array = explode('to', $date_range);
            $date_array = array_map('trim', $date_array);
            list($startDate, $endDate) = $date_array;
            $endDate = $endDate ?? $startDate;
            $sql .= " AND start_date >= :start_date AND end_date <= :end_date";
        }

        // Sorting parameters (optional)
        $sortColumn = $_GET['sort'] ?? 'updated_at';
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
        if ($priority !== '') {
            $stmt->bindParam(':priority', $priority, PDO::PARAM_STR);
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

    // Handle viewing a single plan

    public function viewPlan($id)
    {
        if ($id) {
            return $this->planService->getPlanById($id);
        }

        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Plan ID is required.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
