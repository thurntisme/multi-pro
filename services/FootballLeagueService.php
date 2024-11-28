<?php
require_once DIR . '/controllers/FootballPlayerController.php';

class FootballLeagueService
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
}
