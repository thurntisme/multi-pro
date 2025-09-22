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
            $_SESSION['message'] = "Event " . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . " has been added successfully to your calendar.";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create event";
        }

        header("Location: " . home_url("app/calendar"));
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
                $_SESSION['message'] = "Event " . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . " has been updated successfully.";
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

        header("Location: " . home_url("app/calendar"));
        exit;
    }

    // Get all events

    public function listEvents($queryType)
    {
        return $this->getEventsSQL($queryType);
    }

    // Handle listing all events

    public function getEventsSQL($queryType = '')
    {
        $sql = "SELECT id, title, description, type, start_date, end_date, start_time, end_time, location 
             FROM events WHERE user_id = $this->user_id";

        // Get the current date for filtering
        $currentDate = date('Y-m-d');

        switch ($queryType) {
            case 'month':
                // Get the first and last day of the current month
                $startOfMonth = date('Y-m-01'); // First day of the month
                $endOfMonth = date('Y-m-t');   // Last day of the month
                $sql .= " AND start_date BETWEEN '$startOfMonth' AND '$endOfMonth'";
                break;

            case 'week':
                // Get the start and end dates of the current week
                $startOfWeek = date('Y-m-d', strtotime('monday this week')); // Start of this week (Monday)
                $endOfWeek = date('Y-m-d', strtotime('sunday this week'));   // End of this week (Sunday)
                $sql .= " AND start_date BETWEEN '$startOfWeek' AND '$endOfWeek'";
                break;

            case 'day':
                // Filter for events that are happening today
                $sql .= " AND start_date = '$currentDate'";
                break;

            default:
                // If $queryType is empty, select the next 10 upcoming events
                $sql .= " AND start_date >= '$currentDate' ORDER BY start_date ASC LIMIT 10";
                break;
        }

        // Prepare the query
        $stmt = $this->pdo->prepare($sql);

        // Execute the query
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
