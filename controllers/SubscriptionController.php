<?php

require 'services/SubscriptionService.php';

class SubscriptionController
{
  private $subscriptionService;

  public function __construct()
  {
    global $pdo;
    $this->subscriptionService = new SubscriptionService($pdo);
  }

  // Handle creating a new subscription
  public function createSubscription()
  {
    $service_name = $_POST['service_name'] ?? '';
    $amount = $_POST['amount'] ?? '#';
    $currency = $_POST['currency'] ?? '';
    $payment_date = $_POST['payment_date'] ?? '';
    $payment_month = $_POST['payment_month'] ?? '';
    $payment_year = $_POST['payment_year'] ?? '';
    $payment_type = $_POST['payment_type'] ?? '';

    if ($service_name && $amount && $currency && $payment_date && $payment_type) {
      $this->subscriptionService->createSubscription($service_name, $amount, $currency, $payment_date, $payment_month, $payment_year, $payment_type);
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

  // Handle listing all subscriptions
  public function listSubscriptions()
  {
    return $this->subscriptionService->getAllSubscriptions();
  }
}
