<?php

class FootballTransferService
{
    private $pdo;
    private $user_id;

    public function __construct($pdo)
    {
        global $user_id;
        $this->pdo = $pdo;
        $this->user_id = $user_id;
    }

    public function initializeTeams(array $teams)
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
                    ':manager_id' => $this->user_id,
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
    public function createTransfer($type, $player_id, $amount)
    {
        $is_success = rand(0, 1);  // Random boolean (0 or 1)
        $current_time = time();  // Current timestamp (now)
        $min_time = 4 * 60 * 60;  // 4 hours in seconds
        $max_time = 2 * 24 * 60 * 60;  // 2 days in seconds

        // Random time between 4 hours and 7 days from now
        $random_time_in_seconds = rand($min_time, $max_time);
        $response_at = date('Y-m-d H:i:s', $current_time + $random_time_in_seconds);  // Format as MySQL datetime

        $sql = "INSERT INTO football_transfer (type, player_id, amount, manager_id, is_success, response_at) VALUES (:type, :player_id, :amount, :manager_id, :is_success, :response_at)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':type' => $type, ':player_id' => $player_id, ':amount' => $amount, ':is_success' => $is_success, ':response_at' => $response_at, ':manager_id' => $this->user_id]);

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
    public function deleteTransfer($transferId)
    {
        $sql = "DELETE FROM football_transfer WHERE id = :id AND manager_id = :manager_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $transferId, ':manager_id' => $this->user_id]);

        return $stmt->rowCount();
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
}
