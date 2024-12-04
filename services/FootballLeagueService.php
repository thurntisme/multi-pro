<?php
require_once DIR . '/controllers/FootballPlayerController.php';
require_once DIR . '/controllers/FootballTeamController.php';

class FootballLeagueService
{
    private $pdo;
    private $user_id;
    private $footballPlayerController;
    private $footballTeamController;

    public function __construct($pdo)
    {
        global $user_id;
        $this->pdo = $pdo;
        $this->user_id = $user_id;
        $this->footballPlayerController = new FootballPlayerController();
        $this->footballTeamController = new FootballTeamController();
    }

    public function createLeague($name, $season, $start_date, $end_date, $win_amount)
    {
        $sql = "INSERT INTO football_league (name, season, start_date, end_date, win_amount) VALUES (:name, :season, :start_date, :end_date, :win_amount)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':name' => $name, ':season' => $season, ':start_date' => $start_date, ':end_date' => $end_date, ':win_amount' => $win_amount]);

        return $this->pdo->lastInsertId();
    }

    public function getNewestLeague()
    {
        $sql = "SELECT * 
                FROM football_league 
                WHERE start_date <= :current_date 
                  AND end_date >= :current_date 
                ORDER BY end_date DESC 
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        // Bind current date for filtering
        $currentDate = date('Y-m-d');
        $stmt->bindParam(':current_date', $currentDate);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMyLeagueStanding($currLeagueId, $myTeamId)
    {
        $sql = "SELECT * 
                FROM football_league_standing 
                WHERE team_id = :team_id
                AND league_id = :league_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':team_id', $myTeamId);
        $stmt->bindParam(':league_id', $currLeagueId);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createLeagueMatches($schedule, $currLeagueId, $myTeamId)
    {
        try {
            $match_uuid = '';
            // Begin a transaction
            $this->pdo->beginTransaction();

            // Prepare the SQL insert statement
            $stmt = $this->pdo->prepare("
                INSERT INTO football_match (
                    team_home_id, team_away_id, home_score, away_score, league_id, match_uuid, home_score, away_score, 
                    match_date, status, created_at, updated_at
                ) VALUES (
                    :team_home_id, :team_away_id, :home_score, :away_score, :league_id, :match_uuid, 0, 0, 
                    :match_date, :status, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
                )
            ");

            // Loop through the schedule and execute the insert
            foreach ($schedule as $match) {
                $uuid = uniqid();
                $home_score = (int)rand(0, 9);
                $away_score = (int)rand(0, 9);
                if ($match['home']['id'] === $myTeamId || $match['away']['id'] === $myTeamId) {
                    $match_uuid = $uuid;
                    if ($match['home']['id'] === $myTeamId) {
                        $home_score = 0;
                        $away_score = 3;
                    }
                    if ($match['away']['id'] === $myTeamId) {
                        $home_score = 3;
                        $away_score = 0;
                    }
                }
                $stmt->execute([
                    ':team_home_id' => $match['home']['id'],
                    ':team_away_id' => $match['away']['id'],
                    ':home_score' => $home_score,
                    ':away_score' => $away_score,
                    ':league_id' => $currLeagueId,
                    ':match_uuid' => $uuid,
                    ':status' => 'scheduled',
                    ':match_date' => date('Y-m-d'),
                ]);
            }

            // Commit the transaction
            $this->pdo->commit();

            return $match_uuid;
        } catch (PDOException $e) {
            // Rollback the transaction in case of an error
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
        }
    }

    public function getMatch($uuid, $currLeagueId, $myTeamId)
    {
        $sql = "SELECT * FROM football_match WHERE match_uuid = :uuid AND league_id = :league_id AND (team_home_id = :team_id OR team_away_id = :team_id)";
        $stmt = $this->pdo->query($sql);
        $stmt->execute([':uuid' => $uuid, 'league_id' => $currLeagueId, ':team_id' => $myTeamId]);

        $match = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($match)) {
            $team_home_id = $match['team_home_id'];
            $team_away_id = $match['team_away_id'];
            return [
                'home' => $this->footballTeamController->getMyTeamInMatch($team_home_id),
                'away' => $this->footballTeamController->getMyTeamInMatch($team_away_id),
            ];
        }
        return null;
    }
}
