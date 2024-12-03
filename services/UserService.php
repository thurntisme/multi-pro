<?php

class UserService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createUser($name, $email)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
            $stmt->execute(['name' => $name, 'email' => $email]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error creating user: " . $e->getMessage());
        }
    }

    public function getUser($field, $value)
    {
        try {
            $where = $field . " = :" . $field;
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE " . $where);
            $stmt->execute([$field => $value]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error retrieving user: " . $e->getMessage());
        }
    }

    public function updateUser($id, $username, $first_name, $last_name)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET username = :username, first_name = :first_name, last_name = :last_name WHERE id = :id");
            $stmt->execute(['id' => $id, 'username' => $username, 'first_name' => $first_name, 'last_name' => $last_name]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Error updating user: " . $e->getMessage());
        }
    }

    public function getAllUsers()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM users");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error retrieving users: " . $e->getMessage());
        }
    }
}
