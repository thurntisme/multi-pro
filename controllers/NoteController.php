<?php

require 'services/NoteService.php';

class NoteController
{
  private $user_id;
  private $pdo;
  private $noteService;

  public function __construct()
  {
    global $user_id;
    global $pdo;
    $this->user_id = $user_id;
    $this->pdo = $pdo;
    $this->noteService = new NoteService($pdo);
  }

  // Handle creating a new note
  public function createNote()
  {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $tags = $_POST['tags'] ?? '';

    if ($title) {
      $this->noteService->createNote($title, $content, $tags);
      $_SESSION['message_type'] = 'success';
      $_SESSION['message'] = "Note created successfully";
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Failed to create note";
    }

    header("Location: " . home_url("note"));
    exit;
  }

  // Handle updating a note
  public function updateNote()
  {
    $id = $_POST['note_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $tags = $_POST['tags'] ?? '';

    if ($id && $title) {
      $rowsAffected = $this->noteService->updateNote($id, $title, $content, $tags);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Note updated successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to update note.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Note ID and service name are required.";
    }

    header("Location: " . home_url("note/edit") . '?id=' . $id);
    exit;
  }

  // Handle deleting a note
  public function deleteNote()
  {
    $id = $_POST['post_id'] ?? null;
    if ($id) {
      $rowsAffected = $this->noteService->deleteNote($id);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Note deleted successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to delete note.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Failed to delete note.";
    }

    header("Location: " . home_url("note"));
    exit;
  }

  // Get all notes
  public function getNotesSQL($queryType = "result")
  {
    // Pagination parameters
    $itemsPerPage = 12; // Number of results per page
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Current page number
    $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

    // Search keyword
    $keyword = isset($_GET['s']) ? $_GET['s'] : '';
    $keyword = '%' . $keyword . '%'; // Prepare for LIKE search

    // Filter last updated
    $lastUpdated = isset($_GET['last_updated']) ? $_GET['last_updated'] : '';

    // Filter by role (optional)
    $type = isset($_GET['type']) ? $_GET['type'] : '';

    $selectSql = $queryType === "result" ? "SELECT * FROM notes" : "SELECT COUNT(*) FROM notes";
    $sql = $selectSql . " WHERE user_id = $this->user_id ";

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
      $stmt->bindParam(':start_date', $startDate,);
      $stmt->bindParam(':end_date', $endDate);
    }

    // Execute the query
    $stmt->execute();
    return $queryType === "result" ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchColumn();
  }

  // Handle listing all notes
  public function listNotes()
  {
    // return $this->noteService->getAllNotes();
    return [
      'list' => $this->getNotesSQL("result"),
      'count' => $this->getNotesSQL("count"),
    ];
  }

  // Handle viewing a single note
  public function viewNote($id)
  {
    if ($id) {
      return $this->noteService->getNoteById($id);
    }

    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "Note ID is required.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }
}