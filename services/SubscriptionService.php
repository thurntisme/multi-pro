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
  public function createSubscription($service_name, $amount, $currency, $payment_date, $payment_month, $payment_year, $payment_type)
  {
    $sql = "INSERT INTO subscriptions (service_name, amount, currency, payment_date, payment_type, payment_month, payment_year, user_id) VALUES (:service_name, :amount, :currency, :payment_date, :payment_type, :payment_month, :payment_year, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':service_name' => $service_name, ':amount' => $amount, ':currency' => $currency, ':payment_date' => $payment_date, ':payment_month' => $payment_month, ':payment_year' => $payment_year, ':payment_type' => $payment_type, ':user_id' => $this->user_id]);

    return $this->pdo->lastInsertId();
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
}
