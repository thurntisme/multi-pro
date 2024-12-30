<?php

require 'services/EventService.php';

class EventController
{
    private $user_id;
    private $pdo;
    private $eventService;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->eventService = new EventService($pdo);
    }

    // Handle creating a new event
    public function createEvent()
    {
        // Check CSRF Token
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create event. CSRF token mismatch";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $type = $_POST['type'] ?? '';
        $start_time = $_POST['start_time'] ?? '';
        $end_time = $_POST['end_time'] ?? '';
        $location = $_POST['location'] ?? '';
        $date_range = $_POST['date_range'] ?? '';
        $date_array = explode('to', $date_range);
        $date_array = array_map('trim', $date_array);
        list($start_date, $end_date) = $date_array;
        $end_date = $end_date ?? $start_date;

        if ($title) {
            $this->eventService->createEvent($title, $description, $type, $start_date, $end_date, $start_time, $end_time, $location);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Event created successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create event";
        }

        header("Location: " . home_url("event"));
        exit;
    }

    // Handle updating a event
    public function updateEvent()
    {
        // Check CSRF Token
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to update event. CSRF token mismatch";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        $id = $_POST['event_id'] ?? '';
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $type = $_POST['type'] ?? '';
        $start_time = $_POST['start_time'] ?? '';
        $end_time = $_POST['end_time'] ?? '';
        $location = $_POST['location'] ?? '';
        $date_range = $_POST['date_range'] ?? '';
        $date_array = explode('to', $date_range);
        $date_array = array_map('trim', $date_array);
        list($start_date, $end_date) = $date_array;
        $end_date = $end_date ?? $start_date;

        if ($id && $title) {
            $rowsAffected = $this->eventService->updateEvent($id, $description, $type, $start_date, $end_date, $start_time, $end_time, $location);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Event updated successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update event.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Event ID and service name are required.";
        }

        header("Location: " . home_url("event/edit") . '?id=' . $id);
        exit;
    }

    // Handle deleting a event
    public function deleteEvent()
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->eventService->deleteEvent($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Event deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete event.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to delete event.";
        }

        header("Location: " . home_url("event"));
        exit;
    }

    // Get all events

    public function listEvents()
    {
        // return $this->eventService->getAllEvents();
        return [
            'list' => $this->getEventsSQL("result"),
            'count' => $this->getEventsSQL("count"),
        ];
    }

    // Handle listing all events

    public function getEventsSQL($queryType = "result")
    {
        // Pagination parameters
        $itemsPerPage = 10; // Number of results per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

        // Search keyword
        $keyword = isset($_GET['s']) ? $_GET['s'] : '';

        // Filter last updated
        $due_date = isset($_GET['due_date']) ? $_GET['due_date'] : '';

        // Filter by role (optional)
        $priority = isset($_GET['priority']) ? $_GET['priority'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';

        $selectSql = $queryType === "result" ? "SELECT * FROM events" : "SELECT COUNT(*) FROM events";
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

        $startDate = '';
        $endDate = '';
        if ($due_date !== '') {
            $date_array = explode('to', $due_date);
            $date_array = array_map('trim', $date_array);
            list($startDate, $endDate) = $date_array;
            $endDate = $endDate ?? $startDate;
            $sql .= " AND due_date BETWEEN :start_date AND :end_date";
        }

        // Sorting parameters (optional)
        $sortColumn = $_GET['sort'] ?? 'updated_at';
        $sortOrder = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) ? strtoupper($_GET['order']) : 'DESC'; // Default to DESC

        // Add the ORDER BY clause dynamically
        $sql .= " ORDER BY FIELD(priority, 'critical', 'high', 'medium', 'low'), $sortColumn $sortOrder";

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

    // Handle viewing a single event

    public function viewEvent($id)
    {
        if ($id) {
            return $this->eventService->getEventById($id);
        }

        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Event ID is required.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
