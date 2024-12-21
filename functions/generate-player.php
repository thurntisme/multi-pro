<?php

function getRandomFullName($nation, $namesByNation): string
{
    if (array_key_exists($nation, $namesByNation) && !empty($namesByNation[$nation])) {
        $randomName = $namesByNation[$nation][array_rand($namesByNation[$nation])];
        return $randomName['firstname'] . ' ' . $randomName['lastname'];
    } else {
        return "No names found.";
    }
}

// Function to calculate ability based on position weights
function calculateAbility($attributes, $weights): float|int
{
    $totalWeight = 0;
    $weightedScore = 0;

    foreach ($weights as $category => $attrs) {
        foreach ($attrs as $attribute => $weight) {
            if (isset($attributes[$category][$attribute])) {
                $weightedScore += $attributes[$category][$attribute] * $weight;
                $totalWeight += $weight;
            }
        }
    }

    return $totalWeight > 0 ? $weightedScore / $totalWeight : 0; // Normalize
}

function calculateAttributes($bestPosition, $weights, $minAttr, $maxAttr): array
{
    // Generate random attributes
    $playerAttributes = [
        'technical' => [
            'passing' => $minAttr, // Ability to pass the ball accurately to teammates
            'dribbling' => $minAttr, // Skill in controlling and maneuvering the ball while running
            'first_touch' => $minAttr, // The player's ability to control the ball when receiving it
            'crossing' => $minAttr, // The accuracy and effectiveness of delivering crosses to teammates
            'finishing' => $minAttr, // Ability to score goals when presented with an opportunity
            'long_shots' => $minAttr, // Skill in taking shots from distance
            'shot_power' => $minAttr, // The force behind the player's shots
            'free_kick_accuracy' => $minAttr, // Accuracy in taking free kicks
            'heading_accuracy' => $minAttr, // Ability to head the ball accurately
            'tackling' => $minAttr, // The skill in challenging and taking the ball away from opponents
            'technique' => $minAttr, // General skill and finesse in ball control and execution
            'volleys' => $minAttr, // Skill in striking the ball while it's in the air (volley shots)
            'penalties' => $minAttr, // Accuracy and composure when taking penalty kicks
            'curve' => $minAttr, // Ability to curve the ball when shooting or passing
            'ball_control' => $minAttr, // The player's ability to maintain control of the ball in different situations
            'short_passing' => $minAttr, // Accuracy and precision in short passes
            'long_passing' => $minAttr, // Accuracy and vision for long passes
        ],
        'mental' => [
            'decision_making' => $minAttr, // The player's ability to make the right decision under pressure
            'vision' => $minAttr, // Ability to see opportunities for passes or long shots
            'leadership' => $minAttr, // Ability to inspire and motivate teammates
            'work_rate' => $minAttr, // The player's overall effort on and off the ball
            'positioning' => $minAttr, // The player's awareness of where to be on the field during play
            'composure' => $minAttr, // Ability to stay calm in high-pressure situations
            'aggression' => $minAttr, // The player's willingness to challenge opponents, sometimes aggressively
            'anticipation' => $minAttr, // Ability to predict what will happen next, often to intercept passes
            'concentration' => $minAttr, // Focus and attentiveness during the match
            'off_the_ball' => $minAttr, // Movement and positioning when not in possession of the ball
            'teamwork' => $minAttr, // Ability to work and communicate effectively with teammates
            'determination' => $minAttr, // The player's persistence in overcoming challenges
            'bravery' => $minAttr, // Willingness to take risks and confront tough situations
            'flair' => $minAttr, // Creativity and skill for performing unexpected or stylish actions (e.g., flicks)
        ],
        'physical' => [
            'pace' => $minAttr, // Speed of the player when running at full effort
            'acceleration' => $minAttr, // How quickly the player can reach full speed
            'strength' => $minAttr, // Physical power used to hold off opponents or win challenges
            'stamina' => $minAttr, // Endurance to keep performing at a high level throughout the match
            'agility' => $minAttr, // Ability to change direction quickly and maintain balance
            'balance' => $minAttr, // The player's stability when under pressure or during physical duels
            'jumping_reach' => $minAttr, // Ability to jump and reach for headers, especially in aerial duels
            'natural_fitness' => $minAttr, // Overall physical condition, including flexibility and coordination
            'reaction_time' => $minAttr, // How quickly the player responds to events in the game
            'sprint_speed' => $minAttr, // The player's maximum speed when sprinting
            'endurance' => $minAttr, // The player's ability to maintain effort over a long period
        ],
        'goalkeeping' => [
            'handling' => $minAttr, // Goalkeeper's ability to catch or control the ball in various situations
            'reflexes' => $minAttr, // Quick reactions to shots on goal or unpredictable situations
            'kicking' => $minAttr, // Accuracy and power of goal kicks and other ball clearances
            'throwing' => $minAttr, // Precision in throwing the ball to teammates for quick distribution
            'one_on_ones' => $minAttr, // Ability to stop attackers in one-on-one situations
            'aerial_reach' => $minAttr, // Goalkeeper's ability to jump and reach for crosses or high shots
            'command_of_area' => $minAttr, // The goalkeeper's authority and communication in the penalty area
            'rushing_out' => $minAttr, // Goalkeeper's ability to rush off the line to challenge attackers
            'communication' => $minAttr, // Goalkeeper's ability to organize the defense and direct players
            'penalty_saving' => $minAttr, // The goalkeeper's skill in saving penalty kicks
            'shot_stopping' => $minAttr, // The goalkeeper's overall ability to stop shots on goal
        ],
    ];

    foreach ($playerAttributes as $category => $attrs) {
        foreach ($attrs as $attribute => $value) {
            if ($category === 'goalkeeping') {
                if ($bestPosition === 'GK') {
                    $playerAttributes[$category][$attribute] = rand(min($maxAttr - 10, $minAttr), $maxAttr);
                } else {
                    $playerAttributes[$category][$attribute] = rand(44, 50);
                }
            } else {
                if ($bestPosition === 'GK') {
                    if ($weights[$category][$attribute]) {
                        $playerAttributes[$category][$attribute] = rand(min($maxAttr - 20, $minAttr), $maxAttr);
                    } else {
                        $playerAttributes[$category][$attribute] = rand(44, 50);
                    }
                } else {
                    $playerAttributes[$category][$attribute] = rand($playerAttributes[$category][$attribute], $maxAttr);
                }
            }
        }
    }

    return $playerAttributes; // Normalize
}

// Function to calculate general ability
function calculateGeneralAbility($attributes): float|int
{
    $total = 0;
    $count = 0;

    foreach ($attributes as $category => $attrs) {
        foreach ($attrs as $value) {
            $total += $value;
            $count++;
        }
    }

    return $count > 0 ? $total / $count : 0;
}

function getSeason(int $overallAbility): string
{
    global $seasons;
    foreach ($seasons as $season => $threshold) {
        if ($overallAbility >= $threshold) {
            return $season;
        }
    }
    return 'Unknown'; // Default if something unexpected occurs
}

function getPlayablePosition(string $specificPosition): array
{
    $extraPositions = [
        'GK' => [],
        'CB' => ['CDM', 'RB', 'LB'],
        'RB' => ['CB', 'CDM', 'RM', 'CM'],
        'LB' => ['CB', 'CDM', 'LM', 'CM'],
        'CM' => ['CDM', 'CAM', 'RB', 'LB', 'RM', 'LM'],
        'CDM' => ['CB', 'CM', 'RB', 'LB'],
        'CAM' => ['CM', 'LM', 'RM'],
        'LM' => ['LB', 'CAM', 'CM', 'LW', 'RM', 'RW'],
        'RM' => ['RB', 'CAM', 'CM', 'RW', 'LW', 'LM'],
        'CF' => ['ST', 'CAM', 'RW', 'LW'],
        'ST' => ['CF', 'CAM', 'RW', 'LW'],
        'RW' => ['RM', 'CAM', 'ST', 'LW', 'LM'],
        'LW' => ['LM', 'CAM', 'ST', 'RW', 'RM'],
    ];
    $positions = $extraPositions[$specificPosition];
    shuffle($positions);

    return array_merge([$specificPosition], array_slice($positions, 0, rand(1, 3)));
}

function calculatePlayerWage(
    string $position,
    int    $ability,
): float
{
    $baseWage = rand(900, 1000);

    $positionModifiers = [
        'GK' => 0.9,
        'CB' => 1.0,
        'LB' => 1.1,
        'RB' => 1.1,
        'CM' => 1.2,
        'CDM' => 1.1,
        'CAM' => 1.3,
        'ST' => 1.5,
        'CF' => 1.5,
        'RW' => 1.3,
        'LW' => 1.3,
        'LM' => 1.2,
        'RM' => 1.2,
    ];
    $positionModifier = $positionModifiers[$position] ?? 1.0;

    $rate = $ability / 100 + $positionModifier;
    $wage = $baseWage * $rate;

    return round($wage, 2);
}


function calculateMarketValue(
    string $position,
    array  $playablePositions,
    int    $ability,
    int    $reputation,
): float
{
    $basePrice = 1000000;

    $positionModifiers = [
        'GK' => 0.9,
        'CB' => 1.0,
        'LB' => 1.1,
        'RB' => 1.1,
        'CM' => 1.2,
        'CDM' => 1.1,
        'CAM' => 1.3,
        'ST' => 1.5,
        'CF' => 1.5,
        'RW' => 1.3,
        'LW' => 1.3,
        'LM' => 1.2,
        'RM' => 1.2,
    ];

    $positionModifier = $positionModifiers[$position] ?? 1.0;
    $abilityModifier = $ability / 100;
    $versatilityBonus = (count($playablePositions) > 1 ? count($playablePositions) / 10 : 0.1) + $reputation / 100;
    $rate = ($positionModifier + $abilityModifier + $versatilityBonus);
    $marketValue = $basePrice * $rate + rand(111, 999);

    return round($marketValue, 2);
}

function formatCurrency(float $value, bool $displaySymbol = true): string
{
    if (empty($value)) return '0.00';
    return ($displaySymbol ? "$" : '') . number_format($value);
}

// Function to pick a nation based on rates
function getRandomNation($nations)
{
    // Map nations to their rates
    global $popularNations, $semiPopularNations;
    $nationRates = array_map(function ($nation) use ($popularNations, $semiPopularNations) {
        if (in_array($nation, $popularNations)) {
            return [$nation => 3]; // High rate
        } elseif (in_array($nation, $semiPopularNations)) {
            return [$nation => 2]; // Medium rate
        } else {
            return [$nation => 1]; // Low rate
        }
    }, $nations);

    // Flatten the array
    $nationRates = array_merge(...$nationRates);

    // Create a weighted array
    $weightedNations = [];
    foreach ($nationRates as $nation => $weight) {
        for ($i = 0; $i < $weight; $i++) {
            $weightedNations[] = $nation;
        }
    }

    // Randomly select a nation
    return $weightedNations[array_rand($weightedNations)];
}

function checkSpecialSkills($position, $attributes): array|string
{
    global $specialSkills;

    // Check if the position exists in the special skills array
    if (!isset($specialSkills[$position])) {
        return "Invalid position.";
    }

    $positionSkills = $specialSkills[$position];
    $playerSkills = [];

    // Loop through each special skill for the given position
    foreach ($positionSkills as $skill => $requiredAttributes) {
        $hasSkill = true;

        // Check if the player's attributes meet the required values for the special skill
        foreach ($requiredAttributes as $attribute) {
            // Check if the player has the attribute, and if the value is sufficient (threshold, e.g., >= 10)
            if (!isset($attributes[$attribute]) || $attributes[$attribute] < 80) { // Replace 10 with the threshold you prefer
                $hasSkill = false;
                break;
            }
        }

        // If the player meets the required attributes for the skill, add it to the list of player skills
        if ($hasSkill) {
            $playerSkills[] = $skill;
        }
    }

    // Return the player's skills
    return !empty($playerSkills) ? $playerSkills : [];
}

function flattenAttributes($attributes): array
{
    $flattened = [];

    // Iterate through each category (technical, mental, physical, goalkeeping)
    foreach ($attributes as $values) {
        foreach ($values as $attribute => $value) {
            // Add each attribute directly to the flattened array
            $flattened[$attribute] = $value;
        }
    }

    return $flattened;
}

function calPlayerHeightWeight($position): array
{
    global $positionAttributes;

    // Get height and weight ranges for the assigned position
    $heightRange = $positionAttributes[$position]['height'];
    $weightRange = $positionAttributes[$position]['weight'];

    // Generate random height and weight for the position
    $height = rand($heightRange[0], $heightRange[1]);
    $weight = rand($weightRange[0], $weightRange[1]);

    return [
        'height' => $height,
        'weight' => $weight,
    ];
}

function generateRandomPlayers($type = '', $playerData = []): array
{
    global $positions, $positionWeights;
    $players = [];
    $minAttr = 50;
    $maxAttr = 78;

    if (str_contains($type, "-pack")) {
        $level = ['level-1', 'level-2', 'level-3', 'level-4'];
        $type = $level[array_rand($level)];
    }

    if (str_contains($type, "level-")) {
        if ($type === 'level-1') {
            $maxAttr = 60;
        } elseif ($type === 'level-2') {
            $minAttr = 56;
            $maxAttr = 70;
        } elseif ($type === 'level-3') {
            $minAttr = 64;
            $maxAttr = 80;
        } elseif ($type === 'level-4') {
            $minAttr = 72;
            $maxAttr = 90;
        } elseif ($type === 'level-5') {
            $minAttr = 74;
            $maxAttr = 110;
        }
    }

    // Randomly select or generate player data
    $uuid = uniqid();
    $age = rand(16, 35);
    $player_name_nations = getJsonFileData('assets/json/football-player-name-nations.json');
    $nations = [];
    foreach ($player_name_nations as $item) {
        $nations[$item['nation']] = $item['players'];
    }
    $nationality = getRandomNation(array_keys($nations));
    $name = getRandomFullName($nationality, $nations);
    $bestPosition = $positions[array_rand($positions)];
    if ($type === 'gk-pack') {
        $bestPosition = 'GK';
    }
    if ($type === 'cb-pack') {
        $bestPosition = 'CB';
    }
    if ($type === 'lb-rb-pack') {
        $bestPosition = rand(0, 1) > 0 ? 'LB' : 'RB';
    }
    if ($type === 'cdm-pack') {
        $bestPosition = 'CDM';
    }
    if ($type === 'cm-pack') {
        $bestPosition = 'CM';
    }
    if ($type === 'cam-pack') {
        $bestPosition = 'CAM';
    }
    if ($type === 'lm-rm-pack') {
        $bestPosition = rand(0, 1) > 0 ? 'LM' : 'RM';
    }
    if ($type === 'cf-pack') {
        $bestPosition = 'CF';
    }
    if ($type === 'st-pack') {
        $bestPosition = 'ST';
    }
    if ($type === 'lw-rw-pack') {
        $bestPosition = rand(0, 1) > 0 ? 'LW' : 'RW';
    }
    $playablePositions = getPlayablePosition($bestPosition);

    $attributes = calculateAttributes($bestPosition, $positionWeights[$bestPosition], $minAttr, $maxAttr);

    $positionAbility = calculateAbility($attributes, $positionWeights[$bestPosition]);
    $generalAbility = calculateGeneralAbility($attributes);
    $overallAbility = (int)round(($positionAbility * 0.7) + ($generalAbility * 0.3));

    // Generate abilities with seasons
    $season = getSeason($overallAbility);
    $ability = (int)round($overallAbility);
    $height = calPlayerHeightWeight($bestPosition)['height']; // in cm
    $weight = calPlayerHeightWeight($bestPosition)['weight']; // Proportional weight
    $reputation = rand(1, 5);
    $contract_wage = calculatePlayerWage($bestPosition, $ability);
    $market_value = calculateMarketValue($bestPosition, $playablePositions, $ability, $reputation);
    $special_skills = checkSpecialSkills($bestPosition, flattenAttributes($attributes));

    if (!empty($type) && count($playerData) > 0) {
        $nationality = $playerData['nationality'];
        $name = $playerData['name'];
        $bestPosition = $playerData['best_position'];
        $playablePositions = $playerData['playable_positions'];
        $weight = $playerData['weight'];
        $height = $playerData['height'];
    }
    if ($type === 'young-star') {
        $age = rand(16, 19);
    }

    // Build player array
    $players[] = [
        'uuid' => $uuid, // Unique identifier for the player.
        'name' => $name, // Player's name.
        'age' => $age, // Player's age.
        'nationality' => $nationality, // Player's nationality (e.g., country).
        'best_position' => $bestPosition, // Player's strongest position on the field (e.g., ST, CM, CB).
        'playable_positions' => $playablePositions, // Other positions the player can play, usually as an array or comma-separated string.
        'attributes' => $attributes, // Array of player's skills or abilities (e.g., speed, strength, dribbling).
        'special_skills' => $special_skills, // Specific skills or traits (e.g., free-kick specialist, playmaker).
        'season' => $season, // The current season or the season the player is associated with.
        'ability' => $ability, // Player's overall ability rating.
        'contract_wage' => $contract_wage, // Player's weekly wage based on various factors.
        'contract_end' => rand(7, 14), // Number of seasons before the player's contract expires (random between 7 and 14).
        'injury_prone' => rand(1, 5), // Likelihood of the player getting injured (1 = rarely, 5 = very often).
        'recovery_time' => rand(1, 5), // Time required to recover from an injury (1 = quick recovery, 5 = slow recovery).
        'market_value' => $market_value, // Player's estimated market value based on performance and attributes.
        'reputation' => $reputation, // Player's reputation (1 = low, 5 = world-class).
        'form' => rand(1, 5), // Player's current form (1 = poor, 5 = excellent).
        'morale' => rand(1, 5), // Player's morale or happiness (1 = very low, 5 = very high).
        'potential' => rand(1, 5), // Player's growth potential (1 = low, 5 = world-class potential).
        'height' => $height, // Player's height in centimeters.
        'weight' => $weight, // Player's weight in kilograms.
    ];

    return $players;
}

function getPlayersJson($fileName = ''): false|array
{
    if (empty($fileName)) {
        // Set the file name
        $fileName = "assets/json/players.json";
    }

    // Check if the JSON file exists
    $oldData = [];
    if (file_exists($fileName)) {
        $existingData = file_get_contents($fileName);

        if ($existingData === false) {
            error_log("Failed to read data from {$fileName}");
            return false;
        }

        $oldData = json_decode($existingData, true);
        if ($oldData === null) {
            error_log("Failed to decode JSON from {$fileName}: " . json_last_error_msg());
            $oldData = [];
        }
    } else {
        error_log("File {$fileName} not found. Initializing new data.");
    }
    return array_reverse($oldData);
}

function getTransferPlayerJson(): false|array
{
    $players = getPlayersJson();
    $player_season = $_GET['player_season'] ?? '';
    $player_nationality = $_GET['player_nationality'] ?? '';
    $player_age = $_GET['player_age'] ?? '';
    $player_weight = $_GET['player_weight'] ?? '';
    $player_height = $_GET['player_height'] ?? '';
    $player_position = $_GET['player_position'] ?? '';

    return array_filter($players, function ($player) use (
        $player_season,
        $player_nationality,
        $player_age,
        $player_weight,
        $player_height,
        $player_position
    ) {
        // Filter by player_season if specified
        if ($player_season && $player['season'] !== $player_season) {
            return false;
        }

        // Filter by player_nationality if specified
        if ($player_nationality && $player['nationality'] !== $player_nationality) {
            return false;
        }

        // Filter by player_age if specified
        if ($player_age && $player['age'] != $player_age) {
            return false;
        }

        // Filter by player_weight if specified
        if ($player_weight && $player['weight'] != $player_weight) {
            return false;
        }

        // Filter by player_height if specified
        if ($player_height && $player['height'] != $player_height) {
            return false;
        }

        // Filter by player_position if specified
        if ($player_position && !($player['best_position'] === $player_position)) {
            return false;
        }

        return true; // Include the player if all checks pass
    });
}

function getPlayerJsonByUuid($targetUuid)
{
    // Set the file name
    $fileName = "assets/json/players.json";

    // Check if the JSON file exists
    $player = null;
    if (file_exists($fileName)) {
        $existingData = file_get_contents($fileName);

        if ($existingData === false) {
            error_log("Failed to read data from {$fileName}");
            return false;
        }

        $oldData = json_decode($existingData, true);
        if ($oldData === null) {
            error_log("Failed to decode JSON from {$fileName}: " . json_last_error_msg());
        } else {
            $filteredArray = array_filter($oldData, function ($item) use ($targetUuid) {
                return $item['uuid'] === $targetUuid;
            });

            // Convert the filtered result into a re-indexed array (optional)
            $player = array_values($filteredArray)[0];
        }
    } else {
        error_log("File {$fileName} not found.");
    }
    return $player;
}

function exportPlayersToJson($players): bool
{
    // Set the file name
    $fileName = "assets/json/players.json";

    $oldData = getPlayersJson();

    // Merge existing data with new players
    $mergedData = array_merge($oldData, $players);

    // Convert merged data to JSON
    $jsonData = json_encode($mergedData, JSON_PRETTY_PRINT);
    if ($jsonData === false) {
        error_log("Error encoding JSON: " . json_last_error_msg());
        return false;
    }

    // Write the updated data back to the file
    if (file_put_contents($fileName, $jsonData)) {
        error_log("Data has been successfully updated in {$fileName}");
        return true;
    } else {
        error_log("Failed to write updated data to file {$fileName}");
        return false;
    }
}

function getTeamPlayerData($teamPlayers): array
{
    global $positionGroups;
    $total = count($teamPlayers);
    $firstEleven = array_slice($teamPlayers, 0, 11);
    // Initialize the analytics array
    $analytics = [
        'Goalkeepers' => ['totalRating' => 0, 'count' => 0],
        'Defenders' => ['totalRating' => 0, 'count' => 0],
        'Midfielders' => ['totalRating' => 0, 'count' => 0],
        'Attackers' => ['totalRating' => 0, 'count' => 0]
    ];

    // Loop through the first 11 players
    foreach ($firstEleven as $player) {
        // Check player's best position and determine their group
        $bestPosition = $player['best_position']; // Assuming 'best_position' exists in player data
        foreach ($positionGroups as $group => $positions) {
            if (in_array($bestPosition, $positions)) {
                // Add the player's rating to the group's total rating
                $analytics[$group]['totalRating'] += $player['ability']; // Assuming 'ability' is the rating
                $analytics[$group]['count']++;
                break;
            }
        }
    }

    // Calculate the average rating for each group
    foreach ($analytics as $group => &$data) {
        $data['averageRating'] = $data['count'] > 0
            ? round($data['totalRating'] / $data['count'])
            : 0;
    }

    return array_merge(['total' => $total], $analytics);
}

function getBackgroundColor($ability): string
{
    if ($ability < 50) {
        throw new InvalidArgumentException("Ability must be greater than 50.");
    }

    // Assign colors based on ability ranges
    if ($ability <= 71) {
        return "rgba(245, 245, 245, 0.5)"; // Transparent
    } elseif ($ability <= 96) {
        return "rgba(255, 255, 150, 0.5)"; // Soft Yellow
    } elseif ($ability <= 110) {
        return "rgba(255, 100, 100, 0.5)"; // Soft Red
    } else {
        return "rgba(200, 100, 255, 0.5)"; // Soft Violet
    }
}

function getPositionColor($position): string
{
    global $positionColors, $positionGroupsExtra;

    foreach ($positionGroupsExtra as $group => $positions) {
        if (in_array($position, $positions)) {
            return $positionColors[$group];
        }
    }

    return "gray"; // Default color if position is not found
}
