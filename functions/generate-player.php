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
    global $positionGroups;
    // Find the group for the specific position
    $playablePositions = [];
    foreach ($positionGroups as $group => $groupPositions) {
        if (in_array($specificPosition, $groupPositions, true)) {
            // Shuffle the positions
            shuffle($groupPositions);

            // Get a random number of positions from the group
            $randomCount = rand(1, count($groupPositions));
            $playablePositions = array_slice($groupPositions, 0, $randomCount);

            // Ensure $specificPosition is included
            if (!in_array($specificPosition, $playablePositions, true)) {
                array_unshift($playablePositions, $specificPosition);
                $playablePositions = array_unique($playablePositions); // Remove duplicates if necessary
            }

            // Limit to the maximum of the group size
            $playablePositions = array_slice($playablePositions, 0, count($groupPositions));
            break;
        }
    }

    return array_slice($playablePositions, 0, 4);
}

function calculatePlayerWage(
    string $nation,
    string $position,
    array  $playablePositions,
    string $season,
    int    $overallAbility,
    string $type
): float
{
    // Define base nation multipliers
    global $popularNations, $semiPopularNations;
    $nationMultipliers = [
        'Tier 1' => 1.5,
        'Tier 2' => 1.2,
        'Tier 3' => 1.0,
    ];

    // Assign tier for the nation
    $nationTier = match (true) {
        in_array($nation, $popularNations) => 'Tier 1',
        in_array($nation, $semiPopularNations) => 'Tier 2',
        default => 'Tier 3',
    };
    $baseNation = $nationMultipliers[$nationTier];

    // Define position modifiers
    $positionModifiers = [
        'GK' => 1.0,
        'CB' => 1.0,
        'LB' => 1.0,
        'RB' => 1.0,
        'CM' => 1.2,
        'CDM' => 1.1,
        'CAM' => 1.2,
        'ST' => 1.3,
        'CF' => 1.3,
        'RW' => 1.2,
        'LW' => 1.2,
        'LM' => 1.1,
        'RM' => 1.1,
    ];

    $positionModifier = $positionModifiers[$position] ?? 1.0;

    // Add a bonus for flexible players
    $flexibilityBonus = 0.1 * count($playablePositions);

    // Define season multipliers
    $seasonMultipliers = [
        'Legend' => 2.0,
        'The Best' => 1.7,
        'Superstar' => 1.5,
        'Rising Star' => 1.1,
        'Normal' => 1.0,
    ];

    $seasonMultiplier = $seasonMultipliers[$season] ?? 1.0;

    if ($type == 'ballon-d-or') {
        $baseNation = 1.8;
        $seasonMultiplier = 2.2;
    }

    // Calculate wage
    $wage = $baseNation * ($positionModifier + $flexibilityBonus) * $seasonMultiplier * $overallAbility * 100;

    return round($wage + rand(100, 400), 2); // Round for readability
}


function calculateMarketValue(
    string $nation,
    string $position,
    array  $playablePositions,
    string $season,
    int    $overallAbility,
    string $type
): float
{
    // Step 1: Define the base salary depending on the nation
    global $popularNations, $semiPopularNations;
    $baseSalaries = [
        'Tier 1' => 1500000,
        'Tier 2' => 1000000,
        'Tier 3' => 500000,
    ];

    // Assign tier for the nation (using the same tiering system as before)
    $nationTier = match (true) {
        in_array($nation, $popularNations) => 'Tier 1',
        in_array($nation, $semiPopularNations) => 'Tier 2',
        default => 'Tier 3',
    };

    // Get base salary from the tier
    $baseSalary = $baseSalaries[$nationTier];

    // Step 2: Position modifier
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

    // Step 3: Ability modifier (scale from 0 to 1, with 100 being the max ability)
    $abilityModifier = $overallAbility / 100;

    // Step 4: Season multiplier (affects salary depending on career stage)
    $seasonMultipliers = [
        'Legend' => 2.0,
        'Superstar' => 1.8,
        'Rising Star' => 1.2,
        'Current Player' => 1.0,
    ];

    $seasonMultiplier = $seasonMultipliers[$season] ?? 1.0;

    // Step 5: Versatility bonus (if the player can play multiple positions)
    $versatilityBonus = count($playablePositions) > 1 ? 0.1 : 0;

    if ($type == 'ballon-d-or') {
        $baseSalary = 2000000;
        $seasonMultiplier = 2.2;
        $positionModifier = 1.6;
    }

    // Step 6: Calculate the salary
    $salary = $baseSalary * $positionModifier * $abilityModifier * $seasonMultiplier * (1 + $versatilityBonus);

    // Return the calculated salary (annual salary)
    return round($salary, 2);
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

function generateRandomPlayers($type = '', $playerData = []): array
{
    global $positions;
    $players = [];
    $minAttr = 44;
    $maxAttr = 77;

    if (!empty($type)) {
        if ($type == 'legend') {
            $minAttr = 87;
            $maxAttr = 97;
        }
        if ($type == 'ballon-d-or') {
            $minAttr = 97;
            $maxAttr = 110;
        }
        if ($type == 'demand') {
            $maxAttr = 77;
        }
    }

    // Randomly select or generate player data
    $uuid = uniqid();
    $age = rand(18, 35);
    $nationality = getRandomNation(DEFAULT_NATIONALITY);
    $name = getRandomFullName($nationality, DEFAULT_NAME_BY_NATIONALITY);
    $bestPosition = $positions[array_rand($positions)];
    $playablePositions = getPlayablePosition($bestPosition);

    // Generate random attributes
    $attributes = [
        'technical' => [
            'passing' => rand($minAttr, $maxAttr),
            'dribbling' => rand($minAttr, $maxAttr),
            'first_touch' => rand($minAttr, $maxAttr),
            'crossing' => rand($minAttr, $maxAttr),
            'finishing' => rand($minAttr, $maxAttr),
            'long_shots' => rand($minAttr, $maxAttr),
            'free_kick_accuracy' => rand($minAttr, $maxAttr),
            'heading' => rand($minAttr, $maxAttr),
            'tackling' => rand($minAttr, $maxAttr),
            'handling' => rand($minAttr, $maxAttr), // New: For goalkeepers
            'marking' => rand($minAttr, $maxAttr), // New: For defenders
        ],
        'mental' => [
            'decision' => rand($minAttr, $maxAttr),
            'vision' => rand($minAttr, $maxAttr),
            'leadership' => rand($minAttr, $maxAttr),
            'work_rate' => rand($minAttr, $maxAttr),
            'positioning' => rand($minAttr, $maxAttr),
            'composure' => rand($minAttr, $maxAttr),
            'aggression' => rand($minAttr, $maxAttr),
            'anticipation' => rand($minAttr, $maxAttr),
            'concentration' => rand($minAttr, $maxAttr), // New: For maintaining focus
            'off_the_ball' => rand($minAttr, $maxAttr), // New: For attackers and midfielders
            'flair' => rand($minAttr, $maxAttr), // New: For creative and attacking players
        ],
        'physical' => [
            'pace' => rand($minAttr, $maxAttr),
            'strength' => rand($minAttr, $maxAttr),
            'stamina' => rand($minAttr, $maxAttr),
            'agility' => rand($minAttr, $maxAttr),
            'balance' => rand($minAttr, $maxAttr),
            'jumping_reach' => rand($minAttr, $maxAttr), // Updated: Aerial ability
            'natural_fitness' => rand($minAttr, $maxAttr),
        ],
    ];

    // Weights for attributes by position
    $weights = [
        'GK' => [
            'technical' => [
                'handling' => 0.4,
                'passing' => 0.2,
            ],
            'mental' => [
                'decision' => 0.3,
                'concentration' => 0.3,
            ],
            'physical' => [
                'jumping_reach' => 0.4,
                'strength' => 0.2,
            ],
        ],
        'CM' => [ // Central Midfielder
            'technical' => [
                'passing' => 0.4,
                'first_touch' => 0.3,
                'long_shots' => 0.2,
            ],
            'mental' => [
                'vision' => 0.4,
                'work_rate' => 0.3,
                'decision' => 0.2,
            ],
            'physical' => [
                'stamina' => 0.3,
                'pace' => 0.2,
            ],
        ],
        'CF' => [ // Center Forward
            'technical' => [
                'finishing' => 0.4,
                'dribbling' => 0.3,
                'first_touch' => 0.2,
            ],
            'mental' => [
                'off_the_ball' => 0.4,
                'composure' => 0.3,
            ],
            'physical' => [
                'pace' => 0.4,
                'strength' => 0.3,
            ],
        ],
        'ST' => [ // Striker
            'technical' => [
                'finishing' => 0.5,
                'heading' => 0.3,
            ],
            'mental' => [
                'decision' => 0.3,
                'off_the_ball' => 0.4,
            ],
            'physical' => [
                'pace' => 0.4,
                'strength' => 0.4,
            ],
        ],
        'RW' => [ // Right Winger
            'technical' => [
                'crossing' => 0.5,
                'dribbling' => 0.4,
            ],
            'mental' => [
                'flair' => 0.3,
            ],
            'physical' => [
                'pace' => 0.5,
            ],
        ],
        'LW' => [ // Left Winger
            'technical' => [
                'crossing' => 0.5,
                'dribbling' => 0.4,
            ],
            'mental' => [
                'flair' => 0.3,
            ],
            'physical' => [
                'pace' => 0.5,
            ],
        ],
        'CB' => [ // Center Back
            'technical' => [
                'tackling' => 0.4,
                'heading' => 0.3,
            ],
            'mental' => [
                'decision' => 0.3,
                'marking' => 0.4,
            ],
            'physical' => [
                'strength' => 0.5,
                'jumping_reach' => 0.4,
            ],
        ],
        'RB' => [ // Right Back
            'technical' => [
                'crossing' => 0.3,
                'tackling' => 0.3,
            ],
            'mental' => [
                'work_rate' => 0.4,
            ],
            'physical' => [
                'pace' => 0.4,
                'stamina' => 0.4,
            ],
        ],
        'LB' => [ // Left Back
            'technical' => [
                'crossing' => 0.3,
                'tackling' => 0.3,
            ],
            'mental' => [
                'work_rate' => 0.4,
            ],
            'physical' => [
                'pace' => 0.4,
                'stamina' => 0.4,
            ],
        ],
        'CDM' => [ // Defensive Midfielder
            'technical' => [
                'passing' => 0.3,
                'tackling' => 0.3,
            ],
            'mental' => [
                'positioning' => 0.4,
                'decision' => 0.3,
            ],
            'physical' => [
                'strength' => 0.4,
                'stamina' => 0.3,
            ],
        ],
        'CAM' => [ // Attacking Midfielder
            'technical' => [
                'dribbling' => 0.5,
                'first_touch' => 0.4,
            ],
            'mental' => [
                'vision' => 0.5,
                'flair' => 0.3,
            ],
            'physical' => [
                'pace' => 0.3,
            ],
        ],
        'LM' => [ // Left Midfielder
            'technical' => [
                'crossing' => 0.4,
                'dribbling' => 0.4,
            ],
            'mental' => [
                'vision' => 0.3,
            ],
            'physical' => [
                'pace' => 0.4,
                'stamina' => 0.4,
            ],
        ],
        'RM' => [ // Right Midfielder
            'technical' => [
                'crossing' => 0.4,
                'dribbling' => 0.4,
            ],
            'mental' => [
                'vision' => 0.3,
            ],
            'physical' => [
                'pace' => 0.4,
                'stamina' => 0.4,
            ],
        ],
    ];

    $positionAbility = calculateAbility($attributes, $weights[$bestPosition]);
    $generalAbility = calculateGeneralAbility($attributes);
    $overallAbility = (int)round(($positionAbility * 0.7) + ($generalAbility * 0.3));

    // Generate abilities with seasons
    $season = getSeason($overallAbility);
    $ability = (int)round($overallAbility);
    $height = rand(165, 195); // in cm
    $weight = rand($height - 105, $height - 85); // Proportional weight

    if (!empty($type) && count($playerData) > 0) {
        $nationality = $playerData['nationality'];
        $name = $playerData['name'];
        $bestPosition = $playerData['best_position'];
        $playablePositions = $playerData['playable_positions'];
        $age = $playerData['age'];
        $weight = $playerData['weight'];
        $height = $playerData['height'];
        if ($type === 'young') {
            $age = rand(18, 19);
        }
    }

    // Build player array
    $players[] = [
        'uuid' => $uuid,
        'name' => $name,
        'age' => $age,
        'nationality' => $nationality,
        'best_position' => $bestPosition,
        'playable_positions' => $playablePositions,
        'attributes' => $attributes,
        'season' => $season,
        'ability' => $ability,
        'contract_wage' => calculatePlayerWage($nationality, $bestPosition, $playablePositions, $season, $overallAbility, $type),
        'contract_end' => rand(10, 20),
        'injury' => rand(1, 5),
        'recovery_time' => rand(3, 10),
        'market_value' => calculateMarketValue($nationality, $bestPosition, $playablePositions, $season, $overallAbility, $type),
        'reputation' => rand(1, 10),
        'form' => rand(1, 10),
        'morale' => rand(1, 10),
        'potential' => rand(60, 99),
        'height' => $height, // Height in cm
        'weight' => $weight, // Weight in kg
    ];

    return $players;
}

function getFootballJsonData($type)
{
    return match ($type) {
        'ballon-d-or' => getPlayersJson('assets/json/football-ballon-d-or-player.json'),
        'legend' => getPlayersJson('assets/json/football-legend-player.json'),
        default => [],
    };
}

function getPlayersJson($fileName = '')
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

function exportPlayersToJson($players)
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

function getPositionColor($position)
{
    global $positionColors, $positionGroupsExtra;

    foreach ($positionGroupsExtra as $group => $positions) {
        if (in_array($position, $positions)) {
            return $positionColors[$group];
        }
    }

    return "gray"; // Default color if position is not found
}

function filterPlayers($item_slug, $playerData)
{
    $players = getPlayersJson();
    $seasonArr = [
        "ballon-dor" => "Ballon d'Or",
        "legend" => "Legend",
        "the-best" => "The Best",
        "superstar" => "Superstar",
        "rising-star" => "Rising Star",
        "normal" => "Normal"
    ];
    $by_season = false;
    if (array_key_exists($item_slug, $seasonArr)) {
        $by_season = true;
    }
    $levelArr = [
        "level-1" => 60,
        "level-2" => 70,
        "level-3" => 80,
        "level-4" => 90,
        "level-5" => 100,
    ];
    $by_level = false;
    if (array_key_exists($item_slug, $levelArr)) {
        $by_level = true;
    }

    $filteredPlayers = array_filter($players, function ($item) use ($levelArr, $by_level, $seasonArr, $by_season, $item_slug, $playerData) {
        return
            (!$by_season || $item['season'] === $seasonArr[$item_slug]) &&
            (!$by_level || ($item['ability'] >= $levelArr[$item_slug]) && ($item['ability'] < $levelArr[$item_slug] + 10)) &&
            ($item_slug === "mystery-pack" && $item['ability'] < 100);
    });
    // Pick a random item
    $randomKey = array_rand($filteredPlayers); // Get a random key
    return $filteredPlayers[$randomKey]; // Get the player
}