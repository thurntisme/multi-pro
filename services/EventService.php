<?php

class EventService
{
  private $pdo;
  private $user_id;

  public function __construct($pdo)
  {
    global $user_id;
    $this->pdo = $pdo;
    $this->user_id = $user_id;
  }

  // Create a new event
  public function createEvent($title, $description, $type, $start_date, $end_date, $start_time, $end_time, $location)
  {
    $sql = "INSERT INTO events (title, description, type, start_date, end_date, start_time, end_time, location, user_id) 
                VALUES (:title, :description, :type, :start_date, :end_date, :start_time, :end_time, :location, :user_id)";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
      ':title' => $title,
      ':description' => $description,
      ':type' => $type,
      ':start_date' => $start_date,
      ':end_date' => $end_date,
      ':start_time' => $start_time,
      ':end_time' => $end_time,
      ':location' => $location,
      ':user_id' => $this->user_id
    ]);

    return $this->pdo->lastInsertId();
  }

  // Update a event
  public function updateEvent($id, $description, $type, $start_date, $end_date, $start_time, $end_time, $location)
  {
    $sql = "UPDATE events 
    SET description = :description, type = :type, start_date = :start_date, 
        end_date = :end_date, start_time = :start_time, end_time = :end_time, 
        location = :location, updated_at = CURRENT_TIMESTAMP
    WHERE id = :id AND user_id = :user_id";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
      ':description' => $description,
      ':type' => $type,
      ':start_date' => $start_date,
      ':end_date' => $end_date,
      ':start_time' => $start_time,
      ':end_time' => $end_time,
      ':location' => $location,
      ':id' => $id,
      ':user_id' => $this->user_id
    ]);

    return $stmt->rowCount();
  }

  // Delete a event
  public function deleteEvent($id)
  {
    $sql = "DELETE FROM events WHERE id = :id AND user_id = :user_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Get all events
  public function getAllEvents($limit = -1)
  {
    $sql = "SELECT * FROM events WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
    $stmt = $this->pdo->query($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Read a event by ID
  public function getEventById($id)
  {
    $sql = "SELECT * FROM events WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_id' => $this->user_id, ':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
