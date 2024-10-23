<?php

require 'services/UserService.php';

class UserController
{
  private $userService;

  public function __construct()
  {
    global $pdo;
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
}
