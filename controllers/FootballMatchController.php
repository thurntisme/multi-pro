<?php

require_once DIR . '/services/FootballLeagueService.php';
require_once DIR . '/controllers/FootballTeamController.php';

class FootballMatchController
{
    private $footballLeagueService;
    private $footballTeamController;
    private $myTeam;
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
        $this->footballLeagueService = new FootballLeagueService($pdo);
        $this->footballTeamController = new FootballTeamController();

        $this->myTeam = $this->footballTeamController->getMyTeam();
    }

    public function createLeague(): void
    {
        // List of awesome league names
        $leagueNames = [
            'Champions Arena',
            'Ultimate Football League',
            'Legends Showdown',
            'Pro League Stars',
            'Victory Cup',
            'Elite Soccer Championship',
            'All-Star Clash',
            'Glory Battle',
            'Power Kick League',
            'Dream Team Challenge'
        ];

        // Generate random values
        $name = $leagueNames[array_rand($leagueNames)];
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime('+30 days'));
        $season = date('Y/m/d', strtotime($start_date)) . ' - ' . date('Y/m/d', strtotime($end_date));
        $win_amount = rand(5555555, 8888888);

        try {
            $this->footballLeagueService->createLeague($name, $season, $start_date, $end_date, $win_amount);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "League created successfully";
        } catch (Throwable $th) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create league";
        }

        header("Location: " . home_url("football-manager"));
        exit;
    }

    public function getMySchedule()
    {
        $schedule = $this->getCurrLeagueSchedule();
        if (!empty($schedule)) {
            $myTeamId = $this->myTeam['id'];
            return array_filter($schedule, function ($sc) use ($myTeamId) {
                return $sc['home']['id'] == $myTeamId || $sc['away']['id'] == $myTeamId;
            })[0];
        }
        return null;
    }

    public function getCurrLeagueSchedule()
    {
        if (!empty($this->myTeam)) {
            $myTeamId = $this->myTeam['id'];
            $myLeagueStand = $this->getMyLeagueStanding($myTeamId);
            $myStand = $myLeagueStand ? count($myLeagueStand) : 0;
            $schedule = $this->getLeagueSchedule();
            return $schedule[$myStand];
        }
        return null;
    }

    public function getMyLeagueStanding($myTeamId)
    {
        if (!empty($this->getNewestLeague())) {
            $currLeagueId = $this->getNewestLeague()['id'];
            $standing = $this->footballLeagueService->getMyLeagueStanding($currLeagueId, $myTeamId);
            return $standing;
        }
        return null;
    }

    public function getNewestLeague()
    {
        return $this->footballLeagueService->getNewestLeague();
    }

    public function getLeagueSchedule()
    {
        $teams = $this->footballTeamController->listTeams();

        $totalTeams = count($teams);
        $rounds = [];
        $halfCount = $totalTeams / 2;

        // Create the round-robin pairs
        for ($round = 0; $round < 2 * ($totalTeams - 1); $round++) {
            $roundMatches = [];
            for ($i = 0; $i < $halfCount; $i++) {
                $home = $teams[$i];
                $away = $teams[$totalTeams - 1 - $i];

                // Ensure a home and away game for both
                $roundMatches[] = ['home' => $home, 'away' => $away];
            }

            // Rotate the teams for the next round (excluding the first one)
            $teams = array_merge([array_pop($teams)], array_slice($teams, 0, $totalTeams - 1));

            $rounds[$round] = $roundMatches;
        }

        return $rounds;
    }

    public function gameOn()
    {
        $match = $this->getMatch();
        $match_uuid = $match['match_uuid'];
        if (!empty($match_uuid)) {
            header("Location: " . home_url("app/football-manager/match?uuid=$match_uuid"));
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create match";
            header("Location: " . home_url("football-manager"));
        }
        exit;
    }

    public function createMatch()
    {
        // Prepare the SQL insert statement
        $stmt = $this->pdo->prepare("
            INSERT INTO football_match (
                team_id, match_uuid, home_team, away_team, home_score, away_score, is_home
            ) VALUES (
                :team_id, :match_uuid, :home_team, :away_team, :home_score, :away_score, :is_home
            )
        ");

        $randTeamName = DEFAULT_FOOTBALL_TEAM[array_rand(DEFAULT_FOOTBALL_TEAM)];

        $uuid = uniqid();
        $myTeamId = $this->myTeam['id'];
        $myTeamName = $this->myTeam['team_name'];
        $is_home = rand(0, 1) > 0;
        $home_team_name = $is_home ? $myTeamName : $randTeamName;
        $away_team_name = !$is_home ? $myTeamName : $randTeamName;
        $home_score = $is_home ? 0 : 3;
        $away_score = !$is_home ? 0 : 3;

        $stmt->execute([
            ':team_id' => $myTeamId,
            ':match_uuid' => $uuid,
            ':home_team' => $home_team_name,
            ':away_team' => $away_team_name,
            ':home_score' => $home_score,
            ':away_score' => $away_score,
            ':is_home' => $is_home,
        ]);
        $rowAffect = $this->pdo->lastInsertId();
        if ($rowAffect) {
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "A new match created successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create match";
        }
        header("Location: " . home_url("football-manager"));
        exit;
    }

    public function getMatch()
    {
        $myTeamId = $this->myTeam['id'];
        // Corrected: use prepare instead of query
        $sql = "SELECT * FROM football_match WHERE team_id = :team_id AND status = 'scheduled' ORDER BY created_at DESC LIMIT 1";

        // Prepare the statement
        $stmt = $this->pdo->prepare($sql);

        // Execute the statement with bound parameters
        $stmt->execute([
            ':team_id' => $myTeamId
        ]);

        // Fetch the result
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTeamInMatch($match_uuid)
    {
        // Corrected: use prepare instead of query
        $sql = "SELECT * FROM football_match WHERE match_uuid = :match_uuid AND status = 'scheduled' ORDER BY created_at DESC LIMIT 1";

        // Prepare the statement
        $stmt = $this->pdo->prepare($sql);

        // Execute the statement with bound parameters
        $stmt->execute([
            ':match_uuid' => $match_uuid
        ]);

        // Fetch the result
        $match = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($match)) {
            $players = [
                'home_team_data' => $match['is_home'] ? $this->footballTeamController->getMyTeamInMatch($match['team_id']) : $this->footballTeamController->getRandTeamInMatch(),
                'away_team_data' => $match['is_home'] ? $this->footballTeamController->getRandTeamInMatch() : $this->footballTeamController->getMyTeamInMatch($match['team_id']),
            ];

            return array_merge($match, $players);
        }

        // Return null if no match is found
        return null;
    }
}
