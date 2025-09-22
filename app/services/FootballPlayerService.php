<?php

class FootballPlayerService
{
    private $pdo;
    private $user_id;

    public function __construct($pdo)
    {
        global $user_id;
        $this->pdo = $pdo;
        $this->user_id = $user_id;
    }

    // Create a new player
    public function createPlayer($teamId, $playerUuid)
    {
      $sql = "INSERT INTO football_player (team_id, player_uuid) VALUES (:team_id, :player_uuid)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':team_id' => $teamId, 'player_uuid' => $playerUuid]);
  
      return $this->pdo->lastInsertId();
    }

    public function createFavoritePlayer($playerUuid)
    {
      $sql = "INSERT INTO football_favorite_player (player_uuid, manager_id) VALUES (:player_uuid, :manager_id)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':player_uuid' => $playerUuid, ':manager_id' => $this->user_id]);
  
      return $this->pdo->lastInsertId();
    }

    public function removeFavoritePlayer($playerUuid)
    {
      $sql = "DELETE FROM football_favorite_player WHERE player_uuid = :player_uuid AND manager_id = :manager_id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':player_uuid' => $playerUuid, ':manager_id' => $this->user_id]);
  
      return $stmt->rowCount();
    }

    // Update a code
    public function updateCode($id, $title, $content, $tags, $url)
    {
        $sql = "UPDATE football_player SET title = :title, content = :content, tags = :tags, url = :url, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':url' => $url, ':id' => $id, ':user_id' => $this->user_id]);

        return $stmt->rowCount();
    }

    // Delete a player
    public function deletePlayer($id)
    {
        $sql = "DELETE FROM football_player WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount();
    }

    // Get all codes
    public function getAllTeams()
    {
        $sql = "SELECT * FROM football_team ORDER BY league_position ASC ";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeamByUserId()
    {
        $team = $this->getTeamData();
        if (!empty($team)) {
            $team['players'] = $this->getTeamPlayers($team['id']);
        }
        return $team;
    }

    public function getTeamData()
    {
        $sql = "SELECT * FROM football_team WHERE manager_id = :manager_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':manager_id' => $this->user_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTeamPlayers()
    {
        $sql = "SELECT * FROM football_player WHERE team_id = :team_id ORDER BY starting_order ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':team_id' => $this->user_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPlayerById($id)
    {
        $sql = "SELECT * FROM football_player WHERE  id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
