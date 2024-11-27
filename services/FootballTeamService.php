<?php

class FootballTeamService
{
    private $pdo;
    private $user_id;

    public function __construct($pdo)
    {
        global $user_id;
        $this->pdo = $pdo;
        $this->user_id = $user_id;
    }

    public function initializeTeams(array $teams, $systemUserId)
    {
        $sqlCheck = "SELECT COUNT(*) FROM football_team";
        $stmtCheck = $this->pdo->query($sqlCheck);
        $count = $stmtCheck->fetchColumn();

        if ($count > 0) {
            // Teams already initialized
            return false;
        }

        $this->pdo->beginTransaction();
        try {
            $sqlInsert = "INSERT INTO football_team (team_name, league_position, manager_id) 
                          VALUES (:team_name, :league_position, :manager_id)";
            $stmtInsert = $this->pdo->prepare($sqlInsert);

            foreach ($teams as $index => $team) {
                $stmtInsert->execute([
                    ':team_name' => $team['name'],
                    ':league_position' => $index + 1,
                    ':manager_id' => $systemUserId,
                ]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Create a new code
    public function createTeam($team_name, $system_user_id)
    {
        $sql = "INSERT INTO football_team (team_name, manager_id) VALUES (:team_name, :manager_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':team_name' => $team_name, ':manager_id' => $system_user_id]);

        return $this->pdo->lastInsertId();
    }

    // Update a code
    public function updateCode($id, $title, $content, $tags, $url)
    {
        $sql = "UPDATE codes SET title = :title, content = :content, tags = :tags, url = :url, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':url' => $url, ':id' => $id, ':user_id' => $this->user_id]);

        return $stmt->rowCount();
    }

    // Delete a code
    public function deleteCode($id)
    {
        $sql = "DELETE FROM codes WHERE id = :id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id, ':user_id' => $this->user_id]);

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

    public function getTeamPlayers($team_id)
    {
        $sql = "SELECT * FROM football_team_player WHERE team_id = :team_id ORDER BY starting_order ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':team_id' => $team_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
