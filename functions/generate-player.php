<?php


// Possible values for player attributes
$positions = ['GK', 'CM', 'CF', 'ST', 'RW', 'LW', 'CB', 'RB', 'LB', 'DM', 'AMC', 'LM', 'RM'];
$seasons = [
  'Legend' => 90,
  'The Best' => 80,
  'Superstar' => 70,
  'Rising Star' => 60,
  'Normal' => 0,
];
$seasonsRate = [
  'Legend' => 1.5,
  'The Best' => 1.3,
  'Superstar' => 1.2,
  'Rising Star' => 1.1,
  'Normal' => 1.0,
];

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
  // Define position groups
  $positionGroups = [
    'Goalkeepers' => ['GK'],
    'Defenders' => ['CB', 'RB', 'LB'],
    'Midfielders' => ['CM', 'DM', 'AMC', 'LM', 'RM'],
    'Attackers' => ['CF', 'ST', 'RW', 'LW'],
  ];

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
  int    $overallAbility
): float {
  // Define base nation multipliers
  $nationMultipliers = [
    'Tier 1' => 1.5,
    'Tier 2' => 1.2,
    'Tier 3' => 1.0,
  ];

  // Assign tier for the nation
  $nationTier = match ($nation) {
    'Brazil', 'Germany', 'Argentina' => 'Tier 1',
    'Zimbabwe', 'Ghana', 'Egypt' => 'Tier 2',
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
    'DM' => 1.1,
    'AMC' => 1.2,
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

  // Calculate wage
  $wage = $baseNation * ($positionModifier + $flexibilityBonus) * $seasonMultiplier * $overallAbility * 100;

  return round($wage, 2); // Round for readability
}


function calculatePlayerPrice(
  string $nation,
  string $position,
  array  $playablePositions,
  string $season,
  int    $overallAbility
): float {
  // Step 1: Define the base salary depending on the nation
  $baseSalaries = [
    'Tier 1' => 2000000, // High-paying countries like Brazil, Germany, Argentina
    'Tier 2' => 1000000, // Mid-tier countries like Zimbabwe, Egypt, Ghana
    'Tier 3' => 500000,  // Lower-paying countries
  ];

  // Assign tier for the nation (using the same tiering system as before)
  $nationTier = match ($nation) {
    'Brazil', 'Germany', 'Argentina' => 'Tier 1',
    'Zimbabwe', 'Ghana', 'Egypt' => 'Tier 2',
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
    'DM' => 1.1,
    'AMC' => 1.3,
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

  // Step 6: Calculate the salary
  $salary = $baseSalary * $positionModifier * $abilityModifier * $seasonMultiplier * (1 + $versatilityBonus);

  // Return the calculated salary (annual salary)
  return round($salary, 2);
}

function formatCurrency(float $value): string
{
  return "$" . number_format($value);
}

function generateRandomPlayers(int $count = 10): array
{
  global $positions, $seasons, $seasonsRate;
  $players = [];

  for ($i = 0; $i < $count; $i++) {
    // Randomly select or generate player data
    $uuid = uniqid();
    $age = rand(18, 35);
    $nationality = DEFAULT_NATIONALITY[array_rand(DEFAULT_NATIONALITY)];
    $name = getRandomFullName($nationality, DEFAULT_NAME_BY_NATIONALITY);
    $bestPosition = $positions[array_rand($positions)];
    $playablePositions = getPlayablePosition($bestPosition);

    // Generate random attributes
    $attributes = [
      'technical' => [
        'passing' => rand(44, 80),
        'dribbling' => rand(44, 80),
        'first_touch' => rand(44, 80),
        'crossing' => rand(44, 80),
      ],
      'mental' => [
        'decision' => rand(44, 80),
        'vision' => rand(44, 80),
        'leadership' => rand(44, 80),
        'work_rate' => rand(44, 80),
      ],
      'physical' => [
        'pace' => rand(44, 80),
        'strength' => rand(44, 80),
        'stamina' => rand(44, 80),
      ],
    ];

    // Weights for attributes by position
    $weights = [
      'GK' => [
        'technical' => [
          'crossing' => 0.1,
          'passing' => 0.1,
        ],
        'mental' => [
          'decision' => 0.3,
          'leadership' => 0.3,
        ],
        'physical' => [
          'strength' => 0.3,
        ],
      ],
      'CM' => [ // Central Midfielder
        'technical' => [
          'passing' => 0.3,
          'first_touch' => 0.3,
        ],
        'mental' => [
          'vision' => 0.4,
          'work_rate' => 0.2,
        ],
        'physical' => [
          'stamina' => 0.2,
        ],
      ],
      'CF' => [ // Center Forward
        'technical' => [
          'dribbling' => 0.4,
          'first_touch' => 0.3,
        ],
        'mental' => [
          'decision' => 0.2,
        ],
        'physical' => [
          'pace' => 0.3,
          'strength' => 0.2,
        ],
      ],
      'ST' => [ // Striker
        'technical' => [
          'dribbling' => 0.3,
          'first_touch' => 0.3,
        ],
        'mental' => [
          'vision' => 0.2,
        ],
        'physical' => [
          'pace' => 0.4,
          'strength' => 0.2,
        ],
      ],
      'RW' => [ // Right Winger
        'technical' => [
          'crossing' => 0.4,
          'dribbling' => 0.4,
        ],
        'mental' => [
          'vision' => 0.2,
        ],
        'physical' => [
          'pace' => 0.4,
        ],
      ],
      'LW' => [ // Left Winger
        'technical' => [
          'crossing' => 0.4,
          'dribbling' => 0.4,
        ],
        'mental' => [
          'vision' => 0.2,
        ],
        'physical' => [
          'pace' => 0.4,
        ],
      ],
      'CB' => [ // Center Back
        'technical' => [
          'passing' => 0.2,
        ],
        'mental' => [
          'decision' => 0.3,
          'leadership' => 0.3,
        ],
        'physical' => [
          'strength' => 0.4,
          'stamina' => 0.2,
        ],
      ],
      'RB' => [ // Right Back
        'technical' => [
          'crossing' => 0.3,
          'passing' => 0.2,
        ],
        'mental' => [
          'decision' => 0.2,
        ],
        'physical' => [
          'pace' => 0.4,
          'stamina' => 0.3,
        ],
      ],
      'LB' => [ // Left Back
        'technical' => [
          'crossing' => 0.3,
          'passing' => 0.2,
        ],
        'mental' => [
          'decision' => 0.2,
        ],
        'physical' => [
          'pace' => 0.4,
          'stamina' => 0.3,
        ],
      ],
      'DM' => [ // Defensive Midfielder
        'technical' => [
          'passing' => 0.3,
          'first_touch' => 0.3,
        ],
        'mental' => [
          'work_rate' => 0.3,
          'decision' => 0.2,
        ],
        'physical' => [
          'strength' => 0.3,
        ],
      ],
      'AMC' => [ // Attacking Midfielder
        'technical' => [
          'dribbling' => 0.4,
          'first_touch' => 0.4,
        ],
        'mental' => [
          'vision' => 0.4,
        ],
        'physical' => [
          'pace' => 0.2,
        ],
      ],
      'LM' => [ // Left Midfielder
        'technical' => [
          'crossing' => 0.3,
          'dribbling' => 0.3,
        ],
        'mental' => [
          'vision' => 0.3,
        ],
        'physical' => [
          'pace' => 0.3,
          'stamina' => 0.3,
        ],
      ],
      'RM' => [ // Right Midfielder
        'technical' => [
          'crossing' => 0.3,
          'dribbling' => 0.3,
        ],
        'mental' => [
          'vision' => 0.3,
        ],
        'physical' => [
          'pace' => 0.3,
          'stamina' => 0.3,
        ],
      ],
    ];

    $positionAbility = calculateAbility($attributes, $weights[$bestPosition]);
    $generalAbility = calculateGeneralAbility($attributes);
    $overallAbility = (int)round(($positionAbility * 0.7) + ($generalAbility * 0.3));

    // Generate abilities with seasons
    $season = getSeason($overallAbility);
    $abilities = [
      'season' => $season,
      'current_ability' => (int)round($overallAbility * $seasonsRate[$season]),
    ];

    // Build player array
    $players[] = [
      'uuid' => $uuid,
      'name' => $name,
      'age' => $age,
      'nationality' => $nationality,
      'best_position' => $bestPosition,
      'playable_positions' => $playablePositions,
      'attributes' => $attributes,
      'abilities' => $abilities,
      'contract' => [
        'wage' => calculatePlayerWage($nationality, $bestPosition, $playablePositions, $season, $overallAbility),
        'contract_end' => date('Y-m-d', strtotime('+' . rand(1, 5) . ' years')), // Contract end date
      ],
      'injury' => [
        'status' => (bool)rand(0, 1),
        'recovery_time' => rand(0, 1) ? date('Y-m-d', strtotime('+' . rand(1, 6) . ' months')) : null,
      ],
      'price' => calculatePlayerPrice($nationality, $bestPosition, $playablePositions, $season, $overallAbility)
    ];
  }

  return $players;
}
