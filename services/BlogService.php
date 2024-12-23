<?php

class BlogService
{
    private $pdo;
    private $user_id;

    public function __construct($pdo)
    {
        global $user_id;
        $this->pdo = $pdo;
        $this->user_id = $user_id;
    }

    // Create a new blog
    public function createBlog($title, $content, $tags, $category, $ref_url)
    {
        $sql = "INSERT INTO blogs (title, content, tags, category, ref_url, user_id) VALUES (:title, :content, :tags, :category, :ref_url, :user_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':category' => $category, ':ref_url' => $ref_url, ':user_id' => $this->user_id]);

        return $this->pdo->lastInsertId();
    }

    // Update a blog
    public function updateBlog($id, $title, $content, $tags, $category, $ref_url)
    {
        $sql = "UPDATE blogs SET title = :title, content = :content, tags = :tags, category = :category, ref_url = :ref_url, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':category' => $category, ':ref_url' => $ref_url, ':id' => $id, ':user_id' => $this->user_id]);

        return $stmt->rowCount();
    }

    // Delete a blog
    public function deleteBlog($id)
    {
        $sql = "DELETE FROM blogs WHERE id = :id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id, ':user_id' => $this->user_id]);

        return $stmt->rowCount();
    }

    // Get all blogs
    public function getAllBlogs($limit = -1)
    {
        $sql = "SELECT * FROM blogs WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
        $stmt = $this->pdo->query($sql);
        $stmt->execute([':user_id' => $this->user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read a blog by ID
    public function getBlogById($id)
    {
        $sql = "SELECT * FROM blogs WHERE user_id = :user_id AND id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $this->user_id, ':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
