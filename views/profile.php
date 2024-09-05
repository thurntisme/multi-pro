<?php
$pageTitle = "Profile";

require_once 'controllers/UserController.php';

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userController->updateUser($user_id);
};

$userData = $userController->getUserById($user_id);
$first_name = $userData['first_name'] ?? '';
$last_name = $userData['last_name'] ?? '';
$username = $userData['username'] ?? '';
$email = $userData['email'] ?? '';

ob_start();


echo '<div class="jumbotron bg-light">
  <div class="row">
    <div class="col-6 mx-auto">';

if (isset($_SESSION['message'])) {
  $messageType = $_SESSION['message_type'] ?? 'info';
  echo '<div class="alert alert-' . htmlspecialchars($messageType) . ' alert-dismissible fade show mb-4" role="alert">'
    . htmlspecialchars($_SESSION['message']) .
    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>';

  unset($_SESSION['message']);
  unset($_SESSION['message_type']);
}

echo '<form method="POST" action="' . home_url("profile") . '">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" class="form-control" id="email" value="' . $email . '" readonly>
        </div>
        <div class="form-row">
          <div class="col-md-6 mb-3">
            <label for="firstName">First name</label>
            <input type="text" class="form-control" id="firstName" name="first_name" value="' . $first_name . '" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="lastName">Last name</label>
            <input type="text" class="form-control" id="lastName" name="last_name" value="' . $last_name . '" required>
          </div>
        </div>
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" class="form-control" id="username" name="username" value="' . $username . '">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
      </form>';

echo '</div>
  </div>
</div>';

$pageContent = ob_get_clean();

include 'layout.php';
