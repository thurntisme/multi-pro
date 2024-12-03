<?php

require_once DIR . '/services/FootballLeagueService.php';
require_once DIR . '/controllers/FootballTeamController.php';

class FootballLeagueController
{
    private $footballLeagueService;
    private $footballTeamController;
    private $myTeam;

    public function __construct()
    {
        global $pdo;
        $this->footballLeagueService = new FootballLeagueService($pdo);
        $this->footballTeamController = new FootballTeamController();

        $this->myTeam = $this->footballTeamController->getMyTeam();
    }

    public function createLeague()
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
        } catch (\Throwable $th) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create league";
        }

        header("Location: " . home_url("football-manager"));
        exit;
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

    public function getMyLeagueStanding($myTeamId)
    {
        if (!empty($this->getNewestLeague())) {
            $currLeagueId = $this->getNewestLeague()['id'];
            $standing = $this->footballLeagueService->getMyLeagueStanding($currLeagueId, $myTeamId);
            return $standing;
        }
        return null;
    }

    public function getMySchedule()
    {
        if (!empty($this->myTeam)) {
            $myTeamId = $this->myTeam['id'];
            $myLeagueStand = $this->getMyLeagueStanding($myTeamId);
            $myStand = $myLeagueStand ? count($myLeagueStand) : 0;
            $schedule = $this->getLeagueSchedule();
            return array_filter($schedule[$myStand], function ($sc) use ($myTeamId) {
                return $sc['home']['id'] == $myTeamId || $sc['away']['id'] == $myTeamId;
            })[0];
        }
        return null;
    }

    public function getNewestLeague()
    {
        return $this->footballLeagueService->getNewestLeague();
    }
}
