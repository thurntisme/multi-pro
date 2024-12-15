<?php
require_once DIR . '/controllers/FootballPlayerController.php';

class FootballTeamService
{
    private $pdo;
    private $user_id;
    private $footballPlayerController;

    public function __construct($pdo)
    {
        global $user_id;
        $this->pdo = $pdo;
        $this->user_id = $user_id;
        $this->footballPlayerController = new FootballPlayerController();
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

    public function initializeTeamPlayers($teamId, $players)
    {
        if (empty($players)) {
            throw new Exception("Player list cannot be empty.");
        }

        $this->pdo->beginTransaction();
        try {
            foreach ($players as $player) {
                $this->footballPlayerController->createPlayer($teamId, $player['uuid']);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Failed to initialize team players: " . $e->getMessage());
        }
    }

    // Create a new code
    public function createTeam($team_name, $system_user_id, $league_position = 1, $formation = '442')
    {
        $sql = "INSERT INTO football_team (team_name, manager_id, formation, league_position) VALUES (:team_name, :manager_id, :formation, :league_position)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':team_name' => $team_name, ':manager_id' => $system_user_id, ':formation' => $formation, ':league_position' => $league_position]);

        return $this->pdo->lastInsertId();
    }

    public function updateBudget($amount)
    {
        $team = $this->getTeamData();
        $remainingBudget = $team['budget'] - $amount;
        $sql = "UPDATE football_team SET budget = :budget, updated_at = CURRENT_TIMESTAMP WHERE manager_id = :manager_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':budget' => $remainingBudget, ':manager_id' => $this->user_id]);

        return $stmt->rowCount();
    }

    // Delete a code

    public function getTeamData($teamId)
    {
        $sql = "SELECT * FROM football_team WHERE id = :team_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':team_id' => $teamId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllTeams()
    {
        $sql = "SELECT * FROM football_team ORDER BY league_position ASC ";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeamByUserId()
    {
        $team = $this->getMyTeamData();
        if (!empty($team)) {
            $players = array_map(function ($player) {
                return $this->footballPlayerController->viewPlayer($player['id']);
            }, $this->getTeamPlayers($team['id']));
            $team['players'] = $players;
        }
        return $team;
    }

    public function getMyTeamData()
    {
        $sql = "SELECT * FROM football_team WHERE manager_id = :manager_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':manager_id' => $this->user_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTeamPlayers($team_id)
    {
        $sql = "SELECT * FROM football_player WHERE team_id = :team_id ORDER BY starting_order ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':team_id' => $team_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeamById($teamId)
    {
        $team = $this->getTeamData($teamId);

        if (!empty($team)) {
            $players = array_map(function ($player) {
                return $this->footballPlayerController->viewPlayer($player['id']);
            }, $this->getTeamPlayers($teamId));
            $team['players'] = $players;
        }
        return $team;
    }

    public function updateMyClubFormation($formation)
    {
        $sql = "UPDATE football_team SET formation = :formation, updated_at = CURRENT_TIMESTAMP WHERE manager_id = :manager_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':formation' => $formation, ':manager_id' => $this->user_id]);

        return $stmt->rowCount();
    }

    function updateMyClubPlayers($index, $players)
    {
        $myTeam = $this->getMyTeamData();
        try {
            // Start transaction
            $this->pdo->beginTransaction();

            // Prepare the SQL statement
            $stmt = $this->pdo->prepare("UPDATE football_player SET starting_order = :starting_order, updated_at = CURRENT_TIMESTAMP WHERE team_id = :team_id AND player_uuid = :player_uuid");

            // Execute the update for each player
            foreach ($players as $index => $player) {
                $stmt->execute([
                    ':starting_order' => $index,
                    ':team_id' => $myTeam['id'],
                    ':player_uuid' => $player['uuid'],
                ]);
            }

            // Commit the transaction
            $this->pdo->commit();
        } catch (Exception $e) {
            // Roll back if something fails
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
        }
    }

    function updateMyClubPlayer($player)
    {
        $sql = "UPDATE football_player SET starting_order = :starting_order, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':starting_order' => $player['new_starting_order'],
            ':id' => $player['id']
        ]);

        return $stmt->rowCount();
    }
}
