<?php

require 'services/ExpenseService.php';

class ExpenseController
{
    private $expenseService;
    private $pdo;
    private $user_id;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->expenseService = new ExpenseService($pdo);
    }

    // Handle creating a new expense
    public function createExpense()
    {
        $title = $_POST['title'] ?? '';
        $category = $_POST['category'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $description = $_POST['description'] ?? '';
        $date_expense = $_POST['date_expense'] ?? '';
        $tags = $_POST['tags'] ?? '';

        if ($title && $amount) {
            $this->expenseService->createExpense($title, $category, $amount, $description, $date_expense, $tags);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Expense created successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Expense title and amount are required.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // Handle updating a expense
    public function updateExpense()
    {
        $id = $_POST['id'] ?? null;
        $title = $_POST['title'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $description = $_POST['description'] ?? '';
        $category_id = $_POST['category_id'] ?? '';

        if ($id && $title && $amount) {
            $rowsAffected = $this->expenseService->updateExpense($id, $title, $amount, $description, $category_id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Expense updated successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update expense.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Expense ID, title and amount are required.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // Handle deleting a expense
    public function deleteExpense()
    {
        $id = $_POST['expense_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->expenseService->deleteExpense($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Expense deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete expense.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Expense ID is required.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // Retrieve all expenses for dashboard
    public function getLatestTasks()
    {
        return $this->expenseService->getAllExpenses(4);
    }

    // Handle listing expenses in last 7 days
    public function listExpensesLast7Days()
    {
        return $this->expenseService->getAllExpensesLast7Days();
    }

    // Handle viewing a single expense
    public function viewExpense()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            return $this->expenseService->getExpenseById($id);
        }

        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Expense ID is required.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }


    // Get all todos

    public function listExpenses()
    {
        // return $this->todoService->getAllTodos();
        return [
            'list' => $this->getExpensesSQL("result"),
            'count' => $this->getExpensesSQL("count"),
        ];
    }

    // Handle listing all todos

    public function getExpensesSQL($queryType = "result")
    {
        // Pagination parameters
        $itemsPerPage = 6; // Number of results per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

        // Search keyword
        $keyword = isset($_GET['s']) ? $_GET['s'] : '';
        $keyword = '%' . $keyword . '%'; // Prepare for LIKE search

        // Filter last updated
        $date_expense = $_GET['date_expense'] ?? '';
        $category = $_GET['category'] ?? '';

        $selectSql = $queryType === "result" ? "SELECT * FROM expenses" : "SELECT COUNT(*) FROM expenses";
        $sql = $selectSql . " WHERE user_id = $this->user_id ";

        if ($keyword !== '') {
            $sql .= " AND (title LIKE :keyword OR tags LIKE :keyword)";
        }

        if ($date_expense !== '') {
            $date_array = explode('to', $date_expense);
            $date_array = array_map('trim', $date_array);
            list($startDate, $endDate) = $date_array;
            $endDate = $endDate ?? $startDate;
            $sql .= " AND date_expense BETWEEN :start_date AND :end_date";
        }

        if ($category !== '') {
            $sql .= " AND category = :category";
        }

        // Sorting parameters (optional)
        $sortColumn = $_GET['sort'] ?? 'date_expense';
        $sortOrder = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) ? strtoupper($_GET['order']) : 'DESC'; // Default to DESC

        // Add the ORDER BY clause dynamically
        $sql .= " ORDER BY $sortColumn $sortOrder, updated_at DESC";

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
        if ($category != '') {
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        }
        if ($date_expense !== '') {
            if ($startDate && $endDate) {
                $stmt->bindParam(':start_date', $startDate);
                $stmt->bindParam(':end_date', $endDate);
            }
        }

        // Execute the query
        $stmt->execute();
        return $queryType === "result" ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchColumn();
    }

    function monthlyExpenses()
    {
        // Query for this month's expenses
        $sqlThisMonth = "SELECT SUM(amount) AS total_expense
                     FROM expenses
                     WHERE YEAR(date_expense) = YEAR(CURRENT_DATE)
                       AND MONTH(date_expense) = MONTH(CURRENT_DATE)
                       AND user_id = :user_id";
        $stmtThisMonth = $this->pdo->prepare($sqlThisMonth);
        $stmtThisMonth->execute([':user_id' => $this->user_id]);
        $thisMonthExpense = $stmtThisMonth->fetch(PDO::FETCH_ASSOC)['total_expense'] ?? 0;

        // Query for last month's expenses
        $sqlLastMonth = "SELECT SUM(amount) AS total_expense
                     FROM expenses
                     WHERE YEAR(date_expense) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
                       AND MONTH(date_expense) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                       AND user_id = :user_id";
        $stmtLastMonth = $this->pdo->prepare($sqlLastMonth);
        $stmtLastMonth->execute([':user_id' => $this->user_id]);
        $lastMonthExpense = $stmtLastMonth->fetch(PDO::FETCH_ASSOC)['total_expense'] ?? 0;

        // Calculate percentage change
        if ($lastMonthExpense == 0) {
            // Avoid division by zero
            if ($thisMonthExpense > 0) {
                return [
                    'percentageChange' => 100,
                    'expense' => $thisMonthExpense,
                    'lastExpense' => 0,
                    'direction' => 'up'
                ];
            } else {
                return [
                    'percentageChange' => 0,
                    'expense' => $thisMonthExpense,
                    'lastExpense' => 0,
                    'direction' => 'no change'
                ];
            }
        }

        $percentageChange = (($thisMonthExpense - $lastMonthExpense) / $lastMonthExpense) * 100;

        // Determine direction
        $direction = $percentageChange > 0 ? 'up' : ($percentageChange < 0 ? 'down' : 'no change');

        return [
            'expense' => $thisMonthExpense,
            'lastExpense' => $lastMonthExpense,
            'percentageChange' => round($percentageChange, 2),
            'direction' => $direction
        ];
    }

    function dailyExpenses()
    {
        // Query for today's expenses
        $sqlToday = "SELECT SUM(amount) AS total_expense
                 FROM expenses
                 WHERE DATE(date_expense) = CURRENT_DATE
                   AND user_id = :user_id";
        $stmtToday = $this->pdo->prepare($sqlToday);
        $stmtToday->execute([':user_id' => $this->user_id]);
        $todayExpense = $stmtToday->fetch(PDO::FETCH_ASSOC)['total_expense'] ?? 0;

        // Query for yesterday's expenses
        $sqlYesterday = "SELECT SUM(amount) AS total_expense
                     FROM expenses
                     WHERE DATE(date_expense) = CURRENT_DATE - INTERVAL 1 DAY
                       AND user_id = :user_id";
        $stmtYesterday = $this->pdo->prepare($sqlYesterday);
        $stmtYesterday->execute([':user_id' => $this->user_id]);
        $yesterdayExpense = $stmtYesterday->fetch(PDO::FETCH_ASSOC)['total_expense'] ?? 0;

        // Calculate percentage change
        if ($yesterdayExpense == 0) {
            // Avoid division by zero
            if ($todayExpense > 0) {
                return [
                    'expense' => $todayExpense,
                    'lastExpense' => 0,
                    'percentageChange' => 100,
                    'direction' => 'up'
                ];
            } else {
                return [
                    'expense' => $todayExpense,
                    'lastExpense' => 0,
                    'percentageChange' => 0,
                    'direction' => 'no change'
                ];
            }
        }

        $percentageChange = (($todayExpense - $yesterdayExpense) / $yesterdayExpense) * 100;

        // Determine direction
        $direction = $percentageChange > 0 ? 'up' : ($percentageChange < 0 ? 'down' : 'no change');

        return [
            'expense' => $todayExpense,
            'lastExpense' => $yesterdayExpense,
            'percentageChange' => round($percentageChange, 2),
            'direction' => $direction
        ];
    }
}
