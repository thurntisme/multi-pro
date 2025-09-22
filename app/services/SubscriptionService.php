<?php

class SubscriptionService
{
  private $pdo;
  private $user_id;

  public function __construct($pdo)
  {
    global $user_id;
    $this->pdo = $pdo;
    $this->user_id = $user_id;
  }

  // Create a new subscription
  public function createSubscription($service_name, $amount, $currency, $payment_date, $payment_month, $payment_type, $description)
  {
    $sql = "INSERT INTO subscriptions (service_name, amount, currency, payment_date, payment_type, payment_month, description, user_id) VALUES (:service_name, :amount, :currency, :payment_date, :payment_month, :payment_type,:description, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':service_name' => $service_name, ':amount' => $amount, ':currency' => $currency, ':payment_date' => $payment_date, ':payment_month' => $payment_month, ':payment_type' => $payment_type, ':description' => $description, ':user_id' => $this->user_id]);

    return $this->pdo->lastInsertId();
  }

  // Update a subscription
  public function updateSubscription($id, $service_name, $amount, $currency, $payment_date, $payment_month, $payment_type, $description)
  {
    $sql = "UPDATE subscriptions SET service_name = :service_name, amount = :amount, currency = :currency, payment_date = :payment_date, payment_month = :payment_month, payment_type = :payment_type, description = :description, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':service_name' => $service_name, ':amount' => $amount, ':currency' => $currency, ':payment_date' => $payment_date, ':payment_month' => $payment_month, ':payment_type' => $payment_type, ':description' => $description, ':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Delete a subscription
  public function deleteSubscription($id)
  {
    $sql = "DELETE FROM subscriptions WHERE id = :id AND user_id = :user_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Get all subscriptions
  public function getAllSubscriptions($limit = -1)
  {
    $sql = "SELECT * FROM subscriptions WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
    $stmt = $this->pdo->query($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Read a subscription by ID
  public function getSubscriptionById($id)
  {
    $sql = "SELECT * FROM subscriptions WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_id' => $this->user_id, ':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
