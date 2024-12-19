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

    public function createTransfer($type, $player_id, $amount)
    {
        $is_success = rand(0, 1);  // Random boolean (0 or 1)
        $current_time = time();  // Current timestamp (now)
        $min_time = 4 * 60 * 60;  // 4 hours in seconds
        $max_time = 2 * 24 * 60 * 60;  // 2 days in seconds

        // Random time between 4 hours and 2 days from now
        $random_time_in_seconds = rand($min_time, $max_time);
        $response_at = date('Y-m-d H:i:s', $current_time + $random_time_in_seconds);  // Format as MySQL datetime

        // Faked response
        $is_success = 1;
        $response_at = date('Y-m-d H:i:s');

        // Insert into football_transfer
        $transferSql = "INSERT INTO football_transfer (type, player_id, amount, manager_id, is_success, response_at) 
                        VALUES (:type, :player_id, :amount, :manager_id, :is_success, :response_at)";
        $transferStmt = $this->pdo->prepare($transferSql);
        $transferStmt->execute([
            ':type' => $type,
            ':player_id' => $player_id,
            ':amount' => $amount,
            ':is_success' => $is_success,
            ':response_at' => $response_at,
            ':manager_id' => $this->user_id,
        ]);

        // Update football_player status
        $playerSql = "UPDATE football_player SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $playerStmt = $this->pdo->prepare($playerSql);
        $playerStmt->execute([':status' => $type, ':id' => $player_id]);
        return $playerStmt->rowCount();
    }

    public function cancelSellTransfer($transferId, $playerId)
    {
        $transferSql = "DELETE FROM football_transfer WHERE id = :id AND manager_id = :manager_id";
        $transferStmt = $this->pdo->prepare($transferSql);
        $transferStmt->execute([':id' => $transferId, ':manager_id' => $this->user_id]);

        $playerSql = "UPDATE football_player SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $playerStmt = $this->pdo->prepare($playerSql);
        $playerStmt->execute([':status' => 'players', ':id' => $playerId]);

        return $playerStmt->rowCount();
    }

    public function deleteTransfer($transferId, $playerId)
    {
        $transferSql = "DELETE FROM football_transfer WHERE id = :id AND manager_id = :manager_id";
        $transferStmt = $this->pdo->prepare($transferSql);
        $transferStmt->execute([':id' => $transferId, ':manager_id' => $this->user_id]);

        $playerSql = "DELETE FROM football_player WHERE id = :id";
        $playerStmt = $this->pdo->prepare($playerSql);
        $playerStmt->execute([':id' => $playerId]);

        return $playerStmt->rowCount();
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

    public function getTransferById($id)
    {
        $sql = "SELECT * FROM football_transfer WHERE manager_id = :manager_id AND id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':manager_id' => $this->user_id, ':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
