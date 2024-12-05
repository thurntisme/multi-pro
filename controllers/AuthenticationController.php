<?php

require 'services/AuthenticationService.php';
require 'controllers/UserController.php';

class AuthenticationController
{
    private $authenticationService;
    private $userController;

    public function __construct()
    {
        global $pdo;
        $this->authenticationService = new AuthenticationService($pdo);
        $this->userController = new UserController();
    }

    public function registerUser()
    {
        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';

        $errors = $this->validateUserData($email, $password, $username, $confirmPassword, true);

        if (count($errors) > 0) {
            $_SESSION['message_type'] = 'validate';
            $_SESSION['message'] = $errors;
            $_SESSION['fields'] = array();
            $_SESSION['fields']['email'] = $email;
            $_SESSION['fields']['password'] = $password;
            $_SESSION['fields']['username'] = $username;
        } else {
            $userId = $this->authenticationService->createUser($firstName, $lastName, $username, $email, $password);

            if ($userId) {
                // Check if the "system" user already exists
                if (!$this->authenticationService->userExists('system')) {
                    // Create the "system" user
                    $systemFirstName = 'System';
                    $systemLastName = 'User';
                    $systemUsername = 'system';
                    $systemEmail = 'system@multi-pro.com';
                    $systemPassword = 'securepassword'; // Use a strong password here
                    $this->authenticationService->createUser($systemFirstName, $systemLastName, $systemUsername, $systemEmail, $systemPassword);
                }
            }

            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Register new user successfully";
        }

        header("Location: " . home_url("register"));
        exit;
    }

    private function validateUserData($email, $password, $username = null, $confirmPassword = null, $isRegister = false)
    {
        $errors = [];

        // Validate username
        if (isset($username)) {
            if (empty($username)) {
                $errors['username'] = "Username is required.";
            } elseif (!preg_match('/^[a-zA-Z0-9_]{5,20}$/', $username)) {
                // Username must be alphanumeric, between 5 and 20 characters
                $errors['username'] = "Username must be 5-20 characters long and can only contain letters, numbers, and underscores.";
            }
        }

        // Validate email
        if (empty($email)) {
            $errors['email'] = "Email is required.";
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Check if the email format is valid
                $errors['email'] = "Invalid email format.";
            }
            if ($isRegister === true && $this->userController->getUserByEmail($email)) {
                $errors['email'] = "This email is already in use.";
            }
        }

        // Validate password
        if (empty($password)) {
            $errors['password'] = "Password is required.";
        } elseif (strlen($password) < 8) {
            // Password must be at least 8 characters
            $errors['password'] = "Password must be at least 8 characters long.";
        } elseif (!preg_match('/[A-Z]/', $password)) {
            // Password must contain at least one uppercase letter
            $errors['password'] = "Password must contain at least one uppercase letter.";
        } elseif (!preg_match('/[a-z]/', $password)) {
            // Password must contain at least one lowercase letter
            $errors['password'] = "Password must contain at least one lowercase letter.";
        } elseif (!preg_match('/[0-9]/', $password)) {
            // Password must contain at least one number
            $errors['password'] = "Password must contain at least one number.";
        } elseif (!preg_match('/[\W_]/', $password)) {
            // Password must contain at least one special character
            $errors['password'] = "Password must contain at least one special character.";
        }

        // Confirm password match
        if (isset($confirmPassword)) {
            if ($password !== $confirmPassword) {
                $errors['confirmPassword'] = "Passwords do not match.";
            }
        }

        return $errors;
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $errors = $this->validateUserData($email, $password);

        if (count($errors) > 0) {
            $_SESSION['message_type'] = 'validate';
            $_SESSION['message'] = $errors;
            $_SESSION['fields'] = array();
            $_SESSION['fields']['email'] = $email;
            $_SESSION['fields']['password'] = $password;
        } else {
            $token = $this->authenticationService->loginUser($email, $password);
            if (!empty($token)) {
                $_SESSION['token'] = $token;
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = 'Invalid username or password.';
            }
        }

        header("Location: " . home_url("login"));
        exit;
    }

    public function logout()
    {
        global $commonController;
        $token = $commonController->getToken();
        $this->authenticationService->logoutUser($token);
        $commonController->removeToken();
    }

    public function getCurrentUserId()
    {
        $currUser = $this->getCurrentUser();
        return $currUser['id'] ?? '';
    }

    public function getCurrentUser()
    {
        global $commonController;
        $token = $commonController->getToken();
        $tokenData = $this->getTokenData($token);
        if (isset($tokenData['user_id'])) {
            return $this->userController->getUserById($tokenData['user_id']);
        }
        return null;
    }

    public function getTokenData($token)
    {
        $tokenData = $this->authenticationService->getTokenData($token);
        return $tokenData ?? false;
    }

    public function listDevices()
    {
        $user = $this->getCurrentUser();
        if (isset($user)) {
            return $this->authenticationService->getAllDevices($user['id']);
        } else {
            return [];
        }
    }

    // Handle deleting a device
    public function deleteDevice()
    {
        $id = $_POST['device_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->authenticationService->deleteDevice($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Sign out device successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to sign out device.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to sign out device.";
        }

        header("Location: " . home_url("devices"));
        exit;
    }
}
