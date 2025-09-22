<?php

require 'services/SubscriptionService.php';

class SubscriptionController
{
  private $user_id;
  private $pdo;
  private $subscriptionService;

  public function __construct()
  {
    global $user_id;
    global $pdo;
    $this->user_id = $user_id;
    $this->pdo = $pdo;
    $this->subscriptionService = new SubscriptionService($pdo);
  }

  // Handle creating a new subscription
  public function createSubscription()
  {
    // Check CSRF Token
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Failed to create subscription. CSRF token mismatch";
      header("Location: " . $_SERVER['REQUEST_URI']);
      exit;
    }
    $service_name = $_POST['service_name'] ?? '';
    $amount = $_POST['amount'] ?? '0';
    $currency = $_POST['currency'] ?? '';
    $payment_date = $_POST['payment_date'] ?? '';
    $payment_month = $_POST['payment_month'] ?? '';
    $payment_type = $_POST['payment_type'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($service_name && $amount && $currency && $payment_date && $payment_type) {
      $this->subscriptionService->createSubscription($service_name, $amount, $currency, $payment_date, $payment_month, $payment_type, $description);
      $_SESSION['message_type'] = 'success';
      $_SESSION['message'] = "Subscription created successfully";
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Failed to create subscription";
    }

    header("Location: " . home_url("app/subscription"));
    exit;
  }

  // Handle updating a subscription
  public function updateSubscription()
  {
    // Check CSRF Token
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Failed to update subscription. CSRF token mismatch";
      header("Location: " . $_SERVER['REQUEST_URI']);
      exit;
    }
    $id = $_POST['subscription_id'] ?? null;
    $service_name = $_POST['service_name'] ?? '';
    $amount = $_POST['amount'] ?? '0';
    $currency = $_POST['currency'] ?? '';
    $payment_date = $_POST['payment_date'] ?? '';
    $payment_month = $_POST['payment_month'] ?? '';
    $payment_type = $_POST['payment_type'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($id && $service_name) {
      $rowsAffected = $this->subscriptionService->updateSubscription($id, $service_name, $amount, $currency, $payment_date, $payment_month, $payment_type, $description);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Subscription updated successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to update subscription.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Subscription ID and service name are required.";
    }

    header("Location: " . home_url("subscription/edit") . '?id=' . $id);
    exit;
  }

  // Handle deleting a subscription
  public function deleteSubscription()
  {
    $id = $_POST['post_id'] ?? null;
    if ($id) {
      $rowsAffected = $this->subscriptionService->deleteSubscription($id);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Subscription deleted successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to delete subscription.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Failed to delete subscription.";
    }

    header("Location: " . home_url("app/subscription"));
    exit;
  }

  // Get all subscriptions
  public function getSubscriptionsSQL($queryType = "result")
  {
    // Pagination parameters
    $itemsPerPage = 12; // Number of results per page
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Current page number
    $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

    // Search keyword
    $keyword = isset($_GET['s']) ? $_GET['s'] : '';
    $keyword = '%' . $keyword . '%'; // Prepare for LIKE search

    // Filter by role (optional)
    $payment_type = isset($_GET['payment_type']) ? $_GET['payment_type'] : '';

    $selectSql = $queryType === "result" ? "SELECT * FROM subscriptions" : "SELECT COUNT(*) FROM subscriptions";
    $sql = $selectSql . " WHERE user_id = $this->user_id ";

    if ($keyword !== '') {
      $sql .= " AND service_name LIKE :keyword";
    }

    if ($payment_type !== '') {
      $sql .= " AND payment_type = :payment_type";
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
    if ($payment_type !== '') {
      $stmt->bindParam(':payment_type', $payment_type, PDO::PARAM_STR);
    }

    // Execute the query
    $stmt->execute();
    return $queryType === "result" ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchColumn();
  }

  // Handle listing all subscriptions
  public function listSubscriptions()
  {
    // return $this->subscriptionService->getAllSubscriptions();
    return [
      'list' => $this->getSubscriptionsSQL("result"),
      'count' => $this->getSubscriptionsSQL("count"),
    ];
  }

  // Handle viewing a single subscription
  public function viewSubscription($id)
  {
    if ($id) {
      return $this->subscriptionService->getSubscriptionById($id);
    }

    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "Subscription ID is required.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }
}
