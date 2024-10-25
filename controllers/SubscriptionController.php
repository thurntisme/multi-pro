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
    $service_name = $_POST['service_name'] ?? '';
    $amount = $_POST['amount'] ?? '#';
    $currency = $_POST['currency'] ?? '';
    $payment_date = $_POST['payment_date'] ?? '';
    $payment_type = $_POST['payment_type'] ?? '';

    if ($service_name && $amount && $currency && $payment_date && $payment_type) {
      $this->subscriptionService->createSubscription($service_name, $amount, $currency, $payment_date, $payment_type);
      $_SESSION['message_type'] = 'success';
      $_SESSION['message'] = "Subscription created successfully";
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Failed to create subscription";
    }

    header("Location: " . home_url("subscriptions"));
    exit;
  }

  // Handle deleting a subscription
  public function deleteSubscription()
  {
    $id = $_POST['subscription_id'] ?? null;
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

    header("Location: " . home_url("subscriptions"));
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

    // Filter last updated
    $lastUpdated = isset($_GET['last_updated']) ? $_GET['last_updated'] : '';

    // Filter by role (optional)
    $type = isset($_GET['type']) ? $_GET['type'] : '';

    $selectSql = $queryType === "result" ? "SELECT * FROM subscriptions" : "SELECT COUNT(*) FROM subscriptions";
    $sql = $selectSql . " WHERE user_id = $this->user_id ";

    if ($keyword !== '') {
      $sql .= " AND service_name LIKE :keyword";
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

  // Handle listing all subscriptions
  public function listSubscriptions()
  {
    // return $this->subscriptionService->getAllSubscriptions();
    return [
      'list' => $this->getSubscriptionsSQL("result"),
      'count' => $this->getSubscriptionsSQL("count"),
    ];
  }
}