<?php

require 'services/NoteService.php';

class NoteController
{
  private $noteService;

  public function __construct()
  {
    global $pdo;
    $this->noteService = new NoteService($pdo);
  }

  // Handle creating a new note
  public function createNote()
  {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if ($title) {
      $this->noteService->createNote($title, $content);
      $_SESSION['message_type'] = 'success';
      $_SESSION['message'] = "Note created successfully";
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Note title is required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Handle updating a note
  public function updateNote()
  {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if ($id && $title) {
      $rowsAffected = $this->noteService->updateNote($id, $title, $content);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Note updated successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to update note.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Note ID and title are required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Handle deleting a note
  public function deleteNote($id)
  {
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
      $_SESSION['message'] = "Note ID is required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Retrieve all notes for dashboard
  public function getLatestTasks()
  {
    return $this->noteService->getAllNotes(4);
  }

  // Handle listing all notes
  public function listNotes()
  {
    return $this->noteService->getAllNotes();
  }

  // Handle viewing a single note
  public function viewNote()
  {
    $id = $_GET['id'] ?? null;
    if ($id) {
      return $this->noteService->getNoteById($id);
    }

    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "Note ID is required.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }
}
