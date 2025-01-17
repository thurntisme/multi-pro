<?php

require_once DIR . '/services/FootballLeagueService.php';
require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/functions/generate-player.php';

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

    function generateMatch()
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

        return $this->pdo->lastInsertId();
    }

    public function createMatch()
    {
        $rowAffect = $this->generateMatch();
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

    public
    function saveMatchResult($match_uuid, $result): bool
    {
        // Fetch the latest match details
        $match = $this->getLatestMatch($match_uuid);
        if (empty($match) || $match['status'] !== 'recorded') {
            return false;
        }

        $resultData = json_decode($result);
        if (!$resultData || empty($resultData->players)) {
            return false;
        }

        $players = array_values($resultData->players);

        try {
            // Begin a transaction for all updates
            $this->pdo->beginTransaction();

            // Prepare statements
            $updatePlayerStmt = $this->pdo->prepare("
                UPDATE football_player
                SET goals_scored = :goals_scored,
                    yellow_cards = :yellow_cards,
                    red_cards = :red_cards,
                    player_stamina = :player_stamina,
                    assists = :assists,
                    level = :level,
                    match_played = :match_played,
                    avg_score = :avg_score,
                    injury_end_date = :injury_end_date,
                    updated_at = CURRENT_TIMESTAMP
                WHERE team_id = :team_id
                    AND player_uuid = :player_uuid
            ");

            $fetchPlayerStmt = $this->pdo->prepare("
                SELECT * FROM football_player WHERE player_uuid = :player_uuid
            ");

            // Update players
            foreach ($players as $player) {
                $fetchPlayerStmt->execute([':player_uuid' => $player->uuid]);
                $playerData = $fetchPlayerStmt->fetch(PDO::FETCH_ASSOC);

                if (!$playerData) {
                    throw new Exception("Player not found: {$player->uuid}");
                }

                // Calculate updated stats
                $goalsScored = (int)$playerData['goals_scored'] + (int)$player->goals;
                $yellowCards = (int)$playerData['yellow_cards'] + (int)$player->yellow_cards;
                $redCards = (int)$playerData['red_cards'] + (int)$player->red_cards;
                $matchPlayed = (int)$playerData['match_played'] + 1;
                $avgScore = $playerData['match_played'] > 0
                ? ((float)$playerData['avg_score'] * (float)$playerData['match_played'] + (float)$player->score) / $matchPlayed
                    : $player->score;

                $isInjury = $player->is_injury || ($player->remaining_stamina <= 0);
                $injuryEndDate = $isInjury && is_null($playerData['injury_end_date'])
                ? date('Y-m-d H:i:s', strtotime('+' . $player->recovery_time . ' days'))
                : $playerData['injury_end_date'];

                // Execute player update
                $updatePlayerStmt->execute([
                    ':goals_scored' => $goalsScored,
                    ':yellow_cards' => $yellowCards,
                    ':red_cards' => $redCards,
                    ':player_stamina' => max($player->remaining_stamina, 0),
                    ':assists' => $player->assists,
                    ':level' => $this->updatePlayerLevel($playerData['level'], $player->score),
                    ':team_id' => $match['team_id'],
                    ':match_played' => $matchPlayed,
                    ':avg_score' => round($avgScore, 1),
                    ':injury_end_date' => $injuryEndDate,
                    ':player_uuid' => $player->uuid,
                ]);
            }

            // Update match score
            $updateMatchStmt = $this->pdo->prepare("
                UPDATE football_match
                SET draft_home_score = :draft_home_score,
                    draft_away_score = :draft_away_score
                WHERE match_uuid = :match_uuid
            ");
            $updateMatchStmt->execute([
                ':draft_home_score' => $resultData->draft_home_score,
                ':draft_away_score' => $resultData->draft_away_score,
                ':match_uuid' => $match_uuid,
            ]);

            // Commit the transaction
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            // Roll back the transaction on failure
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Failed to save match result: " . $e->getMessage());
            return false;
        }
    }

    public function acceptMatchResult($match_uuid)
    {
        $sql = "SELECT * FROM football_match WHERE match_uuid = :match_uuid";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':match_uuid' => $match_uuid
        ]);

        $match = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$match) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to save the match UUID: " . $match_uuid;
            header("Location: " . home_url("app/football-manager"));
            exit;
        }

        $matchScoreSql = "UPDATE football_match SET home_score = :home_score, away_score = :away_score, status = :status WHERE match_uuid = :match_uuid";
        $matchScoreStmt = $this->pdo->prepare($matchScoreSql);
        $matchScoreStmt->execute([':home_score' => $match['draft_home_score'], ':away_score' => $match['draft_away_score'], ':status' => 'finished', ':match_uuid' => $match_uuid]);
        if ($matchScoreStmt->rowCount()) {
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Your match saved successfully";
            header("Location: " . home_url("app/football-manager/match-result?uuid=".$match_uuid));
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to save the match";
            header("Location: " . home_url("app/football-manager"));
        }
        exit;
    }

    function calculateNewStamina($old_stamina, $player_attributes_physical_stamina) {
        $scaling_factor = 0.5; 
        $new_stamina = $old_stamina + ($player_attributes_physical_stamina * $scaling_factor);
        $new_stamina = min($new_stamina, 100);
        return $new_stamina;
    }

    function updatePlayerStamina($team_id, $players_in_match) {
        $team_players = $this->footballTeamController->getTeamPlayersInClub($team_id);
        try {
            // Begin a transaction for all updates
            $this->pdo->beginTransaction();
    
            // Update players
            foreach ($team_players as $player) {
                // Check if the player is in the players_in_match array
                $isInMatch = false;
                foreach ($players_in_match as $player_in_match) {
                    if ($player_in_match['id'] === $player['id']) {
                        $isInMatch = true;
                        break;
                    }
                }
    
                // If player is not in match, update their stamina
                if (!$isInMatch) {
                    // Prepare statements
                    $updatePlayerStmt = $this->pdo->prepare("
                        UPDATE football_player
                        SET player_stamina = :player_stamina,
                            updated_at = CURRENT_TIMESTAMP
                        WHERE id = :id
                            AND player_uuid = :player_uuid
                    ");
    
                    // Execute player update
                    $updatePlayerStmt->execute([
                        ':player_stamina' => $this->calculateNewStamina($player['player_stamina'], $player['attributes']['physical']['stamina']),
                        ':id' => $player['id'],
                        ':player_uuid' => $player['uuid'],
                    ]);
                }
            }
    
            // Commit the transaction
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            // Roll back the transaction on failure
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Failed to save match result: " . $e->getMessage());
            return false;
        }
    }    

    public function recordMatch($match_uuid, $players)
    {
        $sql = "SELECT * FROM football_match WHERE match_uuid = :match_uuid AND status = 'scheduled' ORDER BY created_at DESC LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':match_uuid' => $match_uuid
        ]);

        $match = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($match)) {
            $recordSql = "UPDATE football_match SET status = :status WHERE match_uuid = :match_uuid";
            $recordStmt = $this->pdo->prepare($recordSql);
            $recordStmt->execute([':status' => 'recorded', ':match_uuid' => $match_uuid]);

            return ($recordStmt->rowCount() > 0) && $this->generateMatch() && $this->updatePlayerStamina($match['team_id'], $players);
        }
        return false;
    }  

    function randMatchGift($item_idx)
    {
        $players = [];
        for($i = 0; $i < 3; $i++){
            $players[] = generateRandomPlayers()[0];
        }
        if (isset($players[$item_idx])) {
            $maxAbility = max(array_column($players, 'ability'));
            do {
                $randomAbility = rand(0, $maxAbility);
            } while ($randomAbility == $maxAbility);
            $players[$item_idx]['ability'] = $randomAbility;
        }
        return $players;
    }

    public function getMatchGift($match_uuid, $item_idx)
    {
        $sql = "SELECT * FROM football_match WHERE match_uuid = :match_uuid AND status = 'finished' ORDER BY created_at DESC LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':match_uuid' => $match_uuid
        ]);

        $match = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($match)) {
            // $recordSql = "UPDATE football_match SET status = :status WHERE match_uuid = :match_uuid";
            // $recordStmt = $this->pdo->prepare($recordSql);
            // $recordStmt->execute([':status' => 'archived', ':match_uuid' => $match_uuid]);

            return [
                'list' => $this->randMatchGift($item_idx),
                'item_idx' => $item_idx,
            ];
        }
        return [];
    }

    function getLatestMatch($match_uuid)
    {
        $sql = "SELECT * FROM football_match WHERE match_uuid = :match_uuid ORDER BY created_at DESC LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':match_uuid' => $match_uuid
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTeamInMatch($match_uuid)
    {
        $match = $this->getLatestMatch($match_uuid);

        if (!empty($match) && ($match['status'] == 'scheduled')) {
            $myTeamInMatch = $this->footballTeamController->getMyTeamInMatch();
            $randTeam = $this->footballTeamController->getRandTeamInMatch();

            if (count($myTeamInMatch) > 0 && count($randTeam) > 0) {
                $players = [
                    'home_team_data' => $match['is_home'] ? $myTeamInMatch : $randTeam,
                    'away_team_data' => $match['is_home'] ? $randTeam : $myTeamInMatch,
                ];

                return array_merge($match, $players);
            }
            return null;
        }

        // Return null if no match is found
        return null;
    }

    function updatePlayerLevel($currentLevel, $score)
    {
        $score = max(1.0, min($score, 10.0));
        $scalingFactor = 1 / (1 + $currentLevel / 100);
        $levelIncrease = $score * $scalingFactor * rand(10, 20);
        return round($currentLevel + $levelIncrease);
    }

    public function getMatchResult($match_uuid)
    {
        $match = $this->getLatestMatch($match_uuid);
        if (!$match) {
            return null;
        }
        if ($match['status'] !== 'finished') {
            return null;
        }
        return $match;
    }
}
