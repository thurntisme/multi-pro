<?php

const DEFAULT_FOOTBALL_TEAM = [
    'Phoenix Titans',
    'Atlas Warriors',
    'Crimson Falcons',
    'Iron Wolves',
    'Olympus Storm',
    'Eternal Dragons',
    'Shadow Panthers',
    'Thundering Rhinos',
    'Golden Stallions',
    'Silver Hawks',
    'Frost Giants',
    'Midnight Torches',
    'Emerald Guardians',
    'Scarlet Blades',
    'Azure Knights',
    'Ivory Sharks',
    'Blazing Comets',
    'Volcanic Eagles',
    'Steel Spartans',
    'Radiant Monarchs',
    'Celestial Tigers',
    'Midnight Hawks',
    'Radiant Dragons',
    'Thunder Spire',
    'Gale Warriors',
    'Storm Guardians',
    'Lunar Knights',
    'Solar Ascendants',
    'Oceanic Phantoms',
    'Crystal Vipers',
    'Diamond Fury',
    'Wildfire Predators',
    'Storm Wolves',
    'Frost Wardens',
    'Blizzard Sentinels',
];

$formations = [
    [
        "slug" => "442",
        "name" => "4-4-2",
        "player_positions" => [
            "defenders" => ["LB", "CB", "CB", "RB"],
            "midfielders" => ["LM", "CDM", "CDM", "RM"],
            "forwards" => ["ST", "ST"]
        ]
    ],
    [
        "slug" => "433",
        "name" => "4-3-3",
        "player_positions" => [
            "defenders" => ["LB", "CB", "CB", "RB"],
            "midfielders" => ["CM", "CDM", "CM"],
            "forwards" => ["LW", "ST", "RW"]
        ]
    ],
    [
        "slug" => "352",
        "name" => "3-5-2",
        "player_positions" => [
            "defenders" => ["CB", "CB", "CB"],
            "midfielders" => ["CDM", "CDM", "LM", "RM", "CAM"],
            "forwards" => ["ST", "ST"]
        ]
    ],
    [
        "slug" => "4231",
        "name" => "4-2-3-1",
        "player_positions" => [
            "defenders" => ["LB", "CB", "CB", "RB"],
            "midfielders" => ["CDM", "CDM", "CAM", "LM", "RM"],
            "forwards" => ["ST"]
        ]
    ],
    [
        "slug" => "4141",
        "name" => "4-1-4-1",
        "player_positions" => [
            "defenders" => ["LB", "CB", "CB", "RB"],
            "midfielders" => ["CDM", "LM", "CM", "CM", "RM"],
            "forwards" => ["ST"]
        ]
    ],
    [
        "slug" => "541",
        "name" => "5-4-1",
        "player_positions" => [
            "defenders" => ["LB", "CB", "CB", "CB", "RB"],
            "midfielders" => ["LM", "CDM", "CDM", "RM"],
            "forwards" => ["ST"]
        ]
    ],
    [
        "slug" => "4312",
        "name" => "4-3-1-2",
        "player_positions" => [
            "defenders" => ["LB", "CB", "CB", "RB"],
            "midfielders" => ["CM", "CDM", "CM"],
            "forwards" => ["CF", "ST", "ST"]
        ]
    ],
    [
        "slug" => "343",
        "name" => "3-4-3",
        "player_positions" => [
            "defenders" => ["CB", "CB", "CB"],
            "midfielders" => ["LM", "CDM", "CDM", "RM"],
            "forwards" => ["LW", "ST", "RW"]
        ]
    ],
    [
        "slug" => "4222",
        "name" => "4-2-2-2",
        "player_positions" => [
            "defenders" => ["LB", "CB", "CB", "RB"],
            "midfielders" => ["LW", "CDM", "CDM", "RW"],
            "forwards" => ["ST", "ST"]
        ]
    ]
];
define("DEFAULT_FOOTBALL_FORMATION", $formations);

$seasons = [
    'Legend' => 90,
    'The Best' => 80,
    'Superstar' => 70,
    'Rising Star' => 60,
    'Normal' => 0,
];

$seasonArr = [
    "legend" => "Legend",
    "the-best" => "The Best",
    "superstar" => "Superstar",
    "rising-star" => "Rising Star",
    "normal" => "Normal"
];

// Possible values for player attributes
$positions = ['GK', 'CM', 'CF', 'ST', 'RW', 'LW', 'CB', 'RB', 'LB', 'CDM', 'CAM', 'LM', 'RM'];
// Define position groups
$positionGroups = [
    'Goalkeepers' => ['GK'],
    'Defenders' => ['CB', 'RB', 'LB'],
    'Midfielders' => ['CM', 'CDM', 'CAM', 'LM', 'RM'],
    'Attackers' => ['CF', 'ST', 'RW', 'LW'],
];
$positionGroupsExtra = [
    'Goalkeepers' => ['GK'],
    'Defenders' => ['CB', 'LCB', 'RCB', 'RB', 'LB'],
    'Midfielders' => ['CM', 'RCM', 'LCM', 'CDM', 'CAM', 'LM', 'RM'],
    'Attackers' => ['CF', 'ST', 'LF', 'RF', 'RW', 'LW'],
];
// Weights for attributes by position
$positionWeights = [
    'GK' => [ // Goalkeeper
        "goalkeeping" => [
            "handling" => 0.12,
            "reflexes" => 0.12,
            "shot_stopping" => 0.10,
            "aerial_reach" => 0.08,
            "one_on_ones" => 0.08,
            "command_of_area" => 0.05,
            "communication" => 0.03,
            "kicking" => 0.01,
            "throwing" => 0.01
        ],
        "physical" => [
            "reaction_time" => 0.08,
            "jumping_reach" => 0.06,
            "agility" => 0.05,
            "strength" => 0.03,
            "balance" => 0.02,
            "natural_fitness" => 0.01
        ],
        "mental" => [
            "positioning" => 0.04,
            "concentration" => 0.03,
            "composure" => 0.02,
            "decision_making" => 0.01
        ],
        "technical" => [
            "passing" => 0.02,
            "long_passing" => 0.01,
            "ball_control" => 0.01,
            "first_touch" => 0.01
        ],
    ],
    'LB' => [ // Left Back
        "technical" => [
            "tackling" => 0.15,
            "crossing" => 0.10,
            "short_passing" => 0.08,
            "long_passing" => 0.06,
            "dribbling" => 0.06,
            "first_touch" => 0.05,
            "ball_control" => 0.05,
            "finishing" => 0.02,
            "curve" => 0.02
        ],
        "mental" => [
            "positioning" => 0.12,
            "work_rate" => 0.08,
            "anticipation" => 0.06,
            "decision_making" => 0.05,
            "teamwork" => 0.05,
            "composure" => 0.03,
            "concentration" => 0.03,
            "vision" => 0.02
        ],
        "physical" => [
            "pace" => 0.12,
            "stamina" => 0.10,
            "strength" => 0.05,
            "agility" => 0.05,
            "balance" => 0.05,
            "jumping_reach" => 0.03,
            "natural_fitness" => 0.02
        ],
    ],
    'CB' => [ // Center Back
        "technical" => [
            "tackling" => 0.20,
            "heading_accuracy" => 0.15,
            "short_passing" => 0.10,
            "long_passing" => 0.08,
            "ball_control" => 0.05,
            "first_touch" => 0.05
        ],
        "mental" => [
            "positioning" => 0.15,
            "anticipation" => 0.10,
            "decision_making" => 0.08,
            "concentration" => 0.08,
            "teamwork" => 0.05,
            "composure" => 0.04
        ],
        "physical" => [
            "strength" => 0.15,
            "jumping_reach" => 0.12,
            "stamina" => 0.08,
            "pace" => 0.05,
            "balance" => 0.05
        ]
    ],
    'RB' => [ // Right Back
        "technical" => [
            "tackling" => 0.15,
            "crossing" => 0.12,
            "short_passing" => 0.10,
            "dribbling" => 0.08,
            "long_passing" => 0.06,
            "first_touch" => 0.05,
            "ball_control" => 0.05,
            "curve" => 0.02
        ],
        "mental" => [
            "positioning" => 0.12,
            "work_rate" => 0.10,
            "anticipation" => 0.06,
            "decision_making" => 0.05,
            "teamwork" => 0.05,
            "concentration" => 0.03,
            "composure" => 0.03,
            "vision" => 0.02
        ],
        "physical" => [
            "pace" => 0.12,
            "stamina" => 0.10,
            "agility" => 0.06,
            "strength" => 0.05,
            "balance" => 0.04,
            "jumping_reach" => 0.02
        ]
    ],
    'LM' => [ // Left Midfielder
        "technical" => [
            "crossing" => 0.15,
            "dribbling" => 0.12,
            "ball_control" => 0.10,
            "short_passing" => 0.08,
            "long_passing" => 0.06,
            "first_touch" => 0.06,
            "curve" => 0.05,
            "finishing" => 0.05,
            "technique" => 0.03,
            "long_shots" => 0.02
        ],
        "mental" => [
            "work_rate" => 0.12,
            "vision" => 0.08,
            "positioning" => 0.08,
            "decision_making" => 0.05,
            "off_the_ball" => 0.05,
            "teamwork" => 0.05,
            "flair" => 0.04,
            "composure" => 0.02
        ],
        "physical" => [
            "pace" => 0.15,
            "stamina" => 0.10,
            "agility" => 0.08,
            "balance" => 0.05,
            "sprint_speed" => 0.05,
            "strength" => 0.02
        ]
    ],
    'CDM' => [ // Defensive Midfielder
        "technical" => [
            "tackling" => 0.18,
            "passing" => 0.15,
            "short_passing" => 0.12,
            "long_passing" => 0.10,
            "ball_control" => 0.08,
            "first_touch" => 0.07,
            "dribbling" => 0.05,
            "finishing" => 0.02,
            "technique" => 0.02,
            "long_shots" => 0.02
        ],
        "mental" => [
            "positioning" => 0.15,
            "work_rate" => 0.12,
            "vision" => 0.10,
            "decision_making" => 0.08,
            "anticipation" => 0.07,
            "teamwork" => 0.06,
            "composure" => 0.05,
            "concentration" => 0.04,
            "flair" => 0.02
        ],
        "physical" => [
            "strength" => 0.10,
            "stamina" => 0.08,
            "pace" => 0.07,
            "agility" => 0.05,
            "balance" => 0.03,
            "sprint_speed" => 0.02
        ]
    ],
    'CM' => [ // Central Midfielder
        "technical" => [
            "passing" => 0.18,
            "short_passing" => 0.15,
            "long_passing" => 0.12,
            "ball_control" => 0.10,
            "dribbling" => 0.08,
            "first_touch" => 0.07,
            "finishing" => 0.05,
            "technique" => 0.05,
            "long_shots" => 0.04,
            "curve" => 0.04
        ],
        "mental" => [
            "vision" => 0.15,
            "work_rate" => 0.12,
            "decision_making" => 0.10,
            "positioning" => 0.08,
            "anticipation" => 0.06,
            "teamwork" => 0.06,
            "composure" => 0.05,
            "off_the_ball" => 0.05,
            "flair" => 0.03
        ],
        "physical" => [
            "pace" => 0.10,
            "stamina" => 0.10,
            "agility" => 0.06,
            "balance" => 0.04,
            "strength" => 0.03,
            "sprint_speed" => 0.02
        ]
    ],
    'RM' => [ // Right Midfielder
        "technical" => [
            "crossing" => 0.18,
            "dribbling" => 0.15,
            "ball_control" => 0.12,
            "short_passing" => 0.10,
            "long_passing" => 0.08,
            "first_touch" => 0.06,
            "curve" => 0.05,
            "finishing" => 0.04,
            "long_shots" => 0.03,
            "technique" => 0.02
        ],
        "mental" => [
            "work_rate" => 0.14,
            "vision" => 0.12,
            "positioning" => 0.10,
            "decision_making" => 0.08,
            "off_the_ball" => 0.07,
            "anticipation" => 0.06,
            "teamwork" => 0.05,
            "composure" => 0.04,
            "flair" => 0.03
        ],
        "physical" => [
            "pace" => 0.14,
            "stamina" => 0.12,
            "agility" => 0.08,
            "balance" => 0.06,
            "sprint_speed" => 0.05,
            "strength" => 0.03
        ]
    ],
    'CAM' => [ // Central Attacking Midfielder
        "technical" => [
            "passing" => 0.18,
            "short_passing" => 0.15,
            "long_passing" => 0.12,
            "ball_control" => 0.10,
            "dribbling" => 0.08,
            "finishing" => 0.08,
            "technique" => 0.07,
            "first_touch" => 0.05,
            "long_shots" => 0.05,
            "curve" => 0.03
        ],
        "mental" => [
            "vision" => 0.15,
            "decision_making" => 0.12,
            "work_rate" => 0.10,
            "positioning" => 0.08,
            "anticipation" => 0.07,
            "composure" => 0.05,
            "teamwork" => 0.04,
            "off_the_ball" => 0.04,
            "flair" => 0.03
        ],
        "physical" => [
            "pace" => 0.10,
            "stamina" => 0.08,
            "agility" => 0.06,
            "balance" => 0.05,
            "strength" => 0.03,
            "sprint_speed" => 0.03
        ]
    ],
    'CF' => [ // Center Forward
        "technical" => [
            "finishing" => 0.20,
            "ball_control" => 0.15,
            "dribbling" => 0.12,
            "short_passing" => 0.10,
            "first_touch" => 0.08,
            "heading_accuracy" => 0.08,
            "technique" => 0.07,
            "long_shots" => 0.05,
            "curve" => 0.05
        ],
        "mental" => [
            "positioning" => 0.15,
            "decision_making" => 0.12,
            "anticipation" => 0.10,
            "composure" => 0.08,
            "work_rate" => 0.07,
            "off_the_ball" => 0.05,
            "vision" => 0.05,
            "flair" => 0.03
        ],
        "physical" => [
            "pace" => 0.12,
            "strength" => 0.10,
            "agility" => 0.08,
            "balance" => 0.05,
            "stamina" => 0.05,
            "sprint_speed" => 0.03
        ]
    ],
    'LW' => [ // Left Winger
        "technical" => [
            "dribbling" => 0.18,
            "crossing" => 0.15,
            "ball_control" => 0.12,
            "finishing" => 0.10,
            "short_passing" => 0.08,
            "long_passing" => 0.07,
            "first_touch" => 0.06,
            "curve" => 0.06,
            "long_shots" => 0.05,
            "technique" => 0.04
        ],
        "mental" => [
            "work_rate" => 0.12,
            "vision" => 0.10,
            "positioning" => 0.08,
            "decision_making" => 0.07,
            "anticipation" => 0.06,
            "composure" => 0.06,
            "teamwork" => 0.05,
            "off_the_ball" => 0.05,
            "flair" => 0.04
        ],
        "physical" => [
            "pace" => 0.18,
            "agility" => 0.14,
            "stamina" => 0.10,
            "balance" => 0.08,
            "sprint_speed" => 0.06,
            "strength" => 0.04
        ]
    ],
    'ST' => [ // Striker
        "technical" => [
            "finishing" => 0.20,
            "ball_control" => 0.15,
            "dribbling" => 0.12,
            "heading_accuracy" => 0.12,
            "short_passing" => 0.10,
            "first_touch" => 0.08,
            "long_shots" => 0.08,
            "technique" => 0.07,
            "curve" => 0.05
        ],
        "mental" => [
            "positioning" => 0.18,
            "decision_making" => 0.14,
            "anticipation" => 0.12,
            "composure" => 0.10,
            "work_rate" => 0.08,
            "off_the_ball" => 0.07,
            "flair" => 0.05,
            "vision" => 0.05
        ],
        "physical" => [
            "strength" => 0.15,
            "pace" => 0.12,
            "sprint_speed" => 0.10,
            "agility" => 0.08,
            "balance" => 0.05,
            "stamina" => 0.05
        ]
    ],
    'RW' => [ // Right Winger
        "technical" => [
            "dribbling" => 0.18,
            "crossing" => 0.15,
            "ball_control" => 0.12,
            "finishing" => 0.10,
            "short_passing" => 0.08,
            "long_passing" => 0.07,
            "first_touch" => 0.06,
            "curve" => 0.06,
            "long_shots" => 0.05,
            "technique" => 0.04
        ],
        "mental" => [
            "work_rate" => 0.12,
            "vision" => 0.10,
            "positioning" => 0.08,
            "decision_making" => 0.07,
            "anticipation" => 0.06,
            "composure" => 0.06,
            "teamwork" => 0.05,
            "off_the_ball" => 0.05,
            "flair" => 0.04
        ],
        "physical" => [
            "pace" => 0.18,
            "agility" => 0.14,
            "stamina" => 0.10,
            "balance" => 0.08,
            "sprint_speed" => 0.06,
            "strength" => 0.04
        ]
    ],
];
// Define height and weight ranges by position
$positionAttributes = [
    'GK' => ['height' => [180, 199], 'weight' => [75, 99]],
    'CB' => ['height' => [180, 199], 'weight' => [70, 90]],
    'RB' => ['height' => [170, 190], 'weight' => [65, 90]],
    'LB' => ['height' => [170, 190], 'weight' => [65, 90]],
    'CDM' => ['height' => [175, 190], 'weight' => [70, 90]],
    'CM' => ['height' => [170, 190], 'weight' => [65, 90]],
    'CAM' => ['height' => [170, 190], 'weight' => [65, 90]],
    'LM' => ['height' => [165, 190], 'weight' => [60, 90]],
    'RM' => ['height' => [165, 190], 'weight' => [60, 90]],
    'RW' => ['height' => [170, 190], 'weight' => [65, 90]],
    'LW' => ['height' => [170, 190], 'weight' => [65, 90]],
    'CF' => ['height' => [175, 195], 'weight' => [70, 90]],
    'ST' => ['height' => [175, 195], 'weight' => [70, 90]],
];
$positionColors = [
    'Goalkeepers' => "#ff8811",
    'Defenders' => "#3ec300",
    'Midfielders' => "#337ca0",
    'Attackers' => "#ff1d15",
];
$seasonsRate = [
    'Legend' => 1.5,
    'The Best' => 1.3,
    'Superstar' => 1.2,
    'Rising Star' => 1.1,
    'Normal' => 1.0,
];
// Updated Popular Nations: Top 10 FIFA rankings for 2024
$popularNations = [
    "Argentina",
    "France",
    "Brazil",
    "England",
    "Belgium",
    "Portugal",
    "Netherlands",
    "Spain",
    "Italy",
    "Croatia"
];
// Updated Semi-Popular Nations: Ranked 11-30 FIFA rankings for 2024
$semiPopularNations = [
    "Germany",
    "Morocco",
    "Mexico",
    "Uruguay",
    "Switzerland",
    "Colombia",
    "Senegal",
    "USA",
    "Japan",
    "Denmark",
    "Australia",
    "Serbia",
    "Poland",
    "Iran",
    "Korea Republic",
    "Peru",
    "Sweden",
    "Ukraine",
    "Chile",
    "Egypt"
];

const DEFAULT_STORE_ITEMS = [
    [
        'name' => 'Player on Demand',
        'slug' => 'on-demand-item',
        'price' => 5000000,
        'description' => 'Obtain a completely random player, with a chance to get anything from a Normal player to a Legendary one!',
        'type' => 'item',
        'image' => 'images/explosive-winger-player.jpg',
    ],
    [
        'name' => 'Mystery Pack',
        'slug' => 'mystery-pack',
        'price' => 3600000,
        'description' => 'Get a completely random player, ranging from Normal to Legend.',
        'type' => 'new-player',
        'image' => 'images/explosive-winger-player.jpg',
    ],
    [
        'name' => 'Young Star Player',
        'slug' => 'young-star',
        'price' => 960000,
        'description' => 'Unlock a rising star with the potential to become a future legend. Perfect for building a strong foundation for your team.',
        'type' => 'new-player',
        'image' => 'images/young-star-player.jpg',
    ],
    [
        'name' => 'Reliable Goalkeeper',
        'slug' => 'gk-pack',
        'price' => 2000000,
        'description' => 'Defend your net with a top-tier goalkeeper featuring sharp reflexes, commanding presence, and precise distribution.',
        'type' => 'new-player',
        'image' => 'images/reliable-goalkeeper-pro-player.jpg',
    ],
    [
        'name' => 'Elite Defender',
        'slug' => 'cb-pack',
        'price' => 2400000,
        'description' => 'Get the essentials for wing dominance with dependable full-backs offering solid defense and balanced pace.',
        'type' => 'new-player',
        'image' => 'images/elite-full-back-basic-player.jpg',
    ],
    [
        'name' => 'Dynamic Full-Back',
        'slug' => 'lb-rb-pack',
        'price' => 2400000,
        'description' => 'Boost your defense and attack with full-backs who offer excellent pace, solid defending, and key offensive runs.',
        'type' => 'new-player',
        'image' => 'images/dynamic-full-back-player.jpg',
    ],
    [
        'name' => 'Solid CDM',
        'slug' => 'cdm-pack',
        'price' => 2400000,
        'description' => 'Strengthen your midfield with a CDM who excels at breaking up play, intercepting passes, and providing defensive cover.',
        'type' => 'new-player',
        'image' => 'images/solid-cdm-player.jpg',
    ],
    [
        'name' => 'Versatile CM',
        'slug' => 'cm-pack',
        'price' => 2800000,
        'description' => 'Enhance your midfield with a well-rounded CM who contributes both defensively and offensively, with excellent passing and vision.',
        'type' => 'new-player',
        'image' => 'images/versatile-cm-player.jpg',
    ],
    [
        'name' => 'Winger Specialist',
        'slug' => 'lm-rm-pack',
        'price' => 2400000,
        'description' => 'Upgrade your flanks with a dynamic winger who excels at delivering precise crosses, beating defenders, and contributing to both attack and defense.',
        'type' => 'new-player',
        'image' => 'images/winger-specialist-player.jpg',
    ],
    [
        'name' => 'Creative Playmaker',
        'slug' => 'cam-pack',
        'price' => 2560000,
        'description' => 'Add a central midfielder with unparalleled vision, passing, and creativity to orchestrate your team’s attacks.',
        'type' => 'new-player',
        'image' => 'images/creative-playmaker-player.jpg',
    ],
    [
        'name' => 'Clinical CF',
        'slug' => 'cf-pack',
        'price' => 3200000,
        'description' => 'Add a lethal striker to your squad who excels in finishing, positioning, and creating goal-scoring opportunities in tight situations.',
        'type' => 'new-player',
        'image' => 'images/clinical-cf-player.jpg',
    ],
    [
        'name' => 'Powerful Striker',
        'slug' => 'st-pack',
        'price' => 3600000,
        'description' => 'Strengthen your attack with a dominant striker who excels in physicality, aerial duels, and clinical finishing inside the box.',
        'type' => 'new-player',
        'image' => 'images/powerful-striker-player.jpg',
    ],
    [
        'name' => 'Explosive Winger',
        'slug' => 'lw-rw-pack',
        'price' => 2800000,
        'description' => 'Enhance your attack with an explosive winger who possesses blistering pace, excellent dribbling, and the ability to cut inside and score.',
        'type' => 'new-player',
        'image' => 'images/explosive-winger-player.jpg',
    ],
    [
        'name' => 'Player Level 1',
        'slug' => 'level-1',
        'price' => 800000,
        'description' => 'Start your journey with a player rated 60, offering basic skills and the potential to grow.',
        'type' => 'new-player',
        'image' => 'images/player-level-1.jpg',
    ],
    [
        'name' => 'Player Level 2',
        'slug' => 'level-2',
        'price' => 1800000,
        'description' => 'Enhance your team with a player rated 70, bringing improved performance and reliability.',
        'type' => 'new-player',
        'image' => 'images/player-level-2.jpg',
    ],
    [
        'name' => 'Player Level 3',
        'slug' => 'level-3',
        'price' => 2600000,
        'description' => 'Boost your squad with a player rated 80, delivering solid skills and consistency.',
        'type' => 'new-player',
        'image' => 'images/player-level-3.jpg',
    ],
    [
        'name' => 'Player Level 4',
        'slug' => 'level-4',
        'price' => 3200000,
        'description' => 'Dominate the field with a player rated 90, offering elite skills and game-changing impact.',
        'type' => 'new-player',
        'image' => 'images/player-level-4.jpg',
    ],
    [
        'name' => 'Player Level 5',
        'slug' => 'level-5',
        'price' => 4400000,
        'description' => 'Achieve greatness with a player rated 100, delivering world-class skills, unmatched performance, and legendary status.',
        'type' => 'new-player',
        'image' => 'images/player-level-5.jpg',
    ],
    [
        'id' => 1,
        'name' => 'Normal Player',
        'slug' => 'normal',
        'price' => 1000000,
        'description' => 'Get a new Normal Player with balanced stats.',
        'type' => 'filter-player',
        'image' => 'images/normal-player.jpg',
    ],
    [
        'id' => 2,
        'name' => 'Rising Star Player',
        'slug' => 'rising-star',
        'price' => 3000000,
        'description' => 'Get a new Rising Star Player with high potential.',
        'type' => 'filter-player',
        'image' => 'images/rising-star-player.jpg',
    ],
    [
        'id' => 3,
        'name' => 'Superstar Player',
        'slug' => 'superstar',
        'price' => 5000000,
        'description' => 'Get a Superstar Player with excellent stats.',
        'type' => 'filter-player',
        'image' => 'images/superstar-player.jpg',
    ],
    [
        'id' => 4,
        'name' => 'The Best Player',
        'slug' => 'the-best',
        'price' => 8000000,
        'description' => 'Get a top-tier player, one of the best in the world.',
        'type' => 'filter-player',
        'image' => 'images/the-best-player.jpg',
    ],
    [
        'id' => 5,
        'name' => 'Legend Player',
        'slug' => 'legend',
        'price' => 12000000,
        'description' => 'Get a Legendary player with unbeatable stats.',
        'type' => 'filter-player',
        'image' => 'images/legend-player.jpg',
    ],
    [
        'name' => 'Player Upgrade Token',
        'slug' => 'player-upgrade-token',
        'price' => 1500000,
        'description' => 'Upgrade a player’s skill level and stats to a higher tier.',
    ],
    [
        'name' => 'Veteran Player Pack',
        'slug' => 'veteran-player-pack',
        'price' => 4000000,
        'description' => 'Get an experienced player to bring stability and leadership to your team.',
    ],
    [
        'name' => 'Defensive Specialist Pack',
        'slug' => 'defensive-specialist-pack',
        'price' => 3500000,
        'description' => 'Unlock a top-tier defender or goalkeeper to strengthen your defense.',
    ],
    [
        'name' => 'Offensive Specialist Pack',
        'slug' => 'offensive-specialist-pack',
        'price' => 3500000,
        'description' => 'Unlock a forward or midfielder known for scoring or creating goals.',
    ],
    [
        'name' => 'Training Booster',
        'slug' => 'training-booster',
        'price' => 1000000,
        'description' => 'Boost the growth of a player’s stats by 10% during training sessions.',
    ],
    [
        'name' => 'Skill Upgrade Card',
        'slug' => 'skill-upgrade-card',
        'price' => 2000000,
        'description' => 'Upgrade a specific skill (e.g., passing, shooting) of a player by one level.',
    ],
    [
        'name' => 'Stamina Booster',
        'slug' => 'stamina-booster',
        'price' => 800000,
        'description' => 'Fully restore the stamina of a player for the next match.',
    ],
    [
        'name' => 'XP Multiplier (x2)',
        'slug' => 'xp-multiplier',
        'price' => 3000000,
        'description' => 'Double the experience points gained by your players for 5 matches.',
    ],
    [
        'name' => 'Event Exclusive Player',
        'slug' => 'event-exclusive-player',
        'price' => 12000000,
        'description' => 'Obtain a rare player available only for a limited time.',
    ],
    [
        'name' => 'Custom Player Pack',
        'slug' => 'custom-player-pack',
        'price' => 20000000,
        'description' => 'Create a player with your desired stats and position.',
    ],
    [
        'name' => 'Tactical Blueprint',
        'slug' => 'tactical-blueprint',
        'price' => 1500000,
        'description' => 'Unlock a unique formation or tactic to improve team performance.',
    ],
    [
        'name' => 'Team Morale Booster',
        'slug' => 'team-morale-booster',
        'price' => 2000000,
        'description' => 'Increase your entire team’s morale for the next 3 matches.',
    ],
    [
        'name' => 'Team Performance Booster',
        'slug' => 'team-performance-booster',
        'price' => 2500000,
        'description' => 'Temporarily improve your team\'s overall performance by 5% for the next match.',
    ],
    [
        'name' => 'Custom Kit',
        'slug' => 'custom-kit',
        'price' => 1000000,
        'description' => 'Design a unique team kit to stand out on the pitch.',
    ],
    [
        'name' => 'Custom Stadium Pack',
        'slug' => 'custom-stadium-pack',
        'price' => 5000000,
        'description' => 'Unlock a custom stadium with enhanced visuals and crowd effects.',
    ],
    [
        'name' => 'Fan Banner',
        'slug' => 'fan-banner',
        'price' => 500000,
        'description' => 'Add banners and chants to your stadium to increase crowd support.',
    ],
    [
        'name' => 'Event Participation Ticket',
        'slug' => 'event-participation-ticket',
        'price' => 1000000,
        'description' => 'Join a special event to compete for exclusive rewards.',
    ],
    [
        'name' => 'Special Transfer Token',
        'slug' => 'special-transfer-token',
        'price' => 3000000,
        'description' => 'Get a rare player transfer from a special event.',
    ],

    // Added Items
    [
        'name' => 'Future Star Pack',
        'slug' => 'future-star-pack',
        'price' => 3000000,
        'description' => 'Unlock a young player with exceptional potential to dominate future seasons.',
    ],
    [
        'name' => 'Elite Midfielder Pack',
        'slug' => 'elite-midfielder-pack',
        'price' => 4500000,
        'description' => 'Add a world-class midfielder known for creativity and control.',
    ],
    [
        'name' => 'Golden Goalkeeper Pack',
        'slug' => 'golden-goalkeeper-pack',
        'price' => 3000000,
        'description' => 'Strengthen your team with a top-tier goalkeeper who guarantees clean sheets.',
    ],
    [
        'name' => 'Wildcard Pack',
        'slug' => 'wildcard-pack',
        'price' => 6000000,
        'description' => 'Get a player with a random skill boost, making them unpredictable on the pitch.',
    ],
    [
        'name' => 'Injury Recovery Pack',
        'slug' => 'injury-recovery-pack',
        'price' => 2000000,
        'description' => 'Heal all injured players in your squad instantly.',
    ],
    [
        'name' => 'Speed Booster',
        'slug' => 'speed-booster',
        'price' => 1200000,
        'description' => 'Enhance a player’s speed stats by 10% for 3 matches.',
    ],
    [
        'name' => 'Captain’s Boost',
        'slug' => 'captains-boost',
        'price' => 2500000,
        'description' => 'Temporarily improve your team captain’s stats to inspire the team.',
    ],
    [
        'name' => 'Matchday Booster Pack',
        'slug' => 'matchday-booster-pack',
        'price' => 1800000,
        'description' => 'Improve your entire team\'s stamina and performance for one crucial match.',
    ],
    [
        'name' => 'Rival Player Transfer',
        'slug' => 'rival-player-transfer',
        'price' => 18000000,
        'description' => 'Poach a star player from your biggest rival to weaken them and strengthen your squad.',
    ],
    [
        'name' => 'Golden Age Player Pack',
        'slug' => 'golden-age-player-pack',
        'price' => 13000000,
        'description' => 'Get a player inspired by the greatest footballers of the past.',
    ],
    [
        'name' => 'Set-Piece Master Tactic',
        'slug' => 'set-piece-master-tactic',
        'price' => 2000000,
        'description' => 'Unlock a specialized tactic to dominate free kicks and corners.',
    ],
    [
        'name' => 'Home Advantage Boost',
        'slug' => 'home-advantage-boost',
        'price' => 2500000,
        'description' => 'Increase your team\'s morale and fan support for home games.',
    ],
    [
        'name' => 'Advanced Scouting Report',
        'slug' => 'advanced-scouting-report',
        'price' => 1500000,
        'description' => 'Gain insights into your next opponent’s tactics and weaknesses.',
    ],
    [
        'name' => 'Retro Kit Pack',
        'slug' => 'retro-kit-pack',
        'price' => 1500000,
        'description' => 'Unlock classic kits inspired by your team’s history.',
    ],
    [
        'name' => 'Mascot Unlock',
        'slug' => 'mascot-unlock',
        'price' => 2000000,
        'description' => 'Add a team mascot to boost crowd morale and branding.',
    ],
    [
        'name' => 'Stadium Light Show',
        'slug' => 'stadium-light-show',
        'price' => 1000000,
        'description' => 'Enhance your stadium atmosphere with a custom light show for night matches.',
    ],
    [
        'name' => 'Tournament Entry Token',
        'slug' => 'tournament-entry-token',
        'price' => 2000000,
        'description' => 'Join a global tournament with massive rewards for top players.',
    ],
    [
        'name' => 'Exclusive Player Draft',
        'slug' => 'exclusive-player-draft',
        'price' => 5000000,
        'description' => 'Participate in a draft to choose from a pool of rare players.',
    ],
    [
        'name' => 'Legends Coaching Staff',
        'slug' => 'legends-coaching-staff',
        'price' => 10000000,
        'description' => 'Hire a legendary player as your coach to boost training and tactics.',
    ],
    [
        'name' => 'Youth Academy Expansion',
        'slug' => 'youth-academy-expansion',
        'price' => 7500000,
        'description' => 'Invest in your youth academy to discover top young talent.',
    ],
    [
        'name' => 'Global Scouting Network',
        'slug' => 'global-scouting-network',
        'price' => 5000000,
        'description' => 'Unlock scouts from around the world to find hidden gems.',
    ],
    [
        'name' => 'Extra Challenge Ticket',
        'slug' => 'extra-challenge-ticket',
        'price' => 500000,
        'description' => 'Play an additional challenge match beyond the daily limit.',
    ],
    [
        'name' => 'Challenge Stamina Restore',
        'slug' => 'challenge-stamina-restore',
        'price' => 200000,
        'description' => 'Restore stamina to retry a failed challenge immediately.',
    ],
    [
        'name' => 'Challenge Boost Pack',
        'slug' => 'challenge-boost-pack',
        'price' => 1000000,
        'description' => 'Temporarily increase your team\'s stats by 10% for the next challenge.',
    ],
    [
        'name' => 'Challenge Bonus Reward',
        'slug' => 'challenge-bonus-reward',
        'price' => 800000,
        'description' => 'Earn 50% more rewards (money, XP, etc.) from your next challenge match.',
    ],

    // League-Related Items
    [
        'name' => 'League Participation Token',
        'slug' => 'league-participation-token',
        'price' => 2000000,
        'description' => 'Unlock access to join the next season of the league.',
    ],
    [
        'name' => 'League Revival Pass',
        'slug' => 'league-revival-pass',
        'price' => 1500000,
        'description' => 'Revive your team in a league after being eliminated.',
    ],
    [
        'name' => 'League Prestige Pack',
        'slug' => 'league-prestige-pack',
        'price' => 5000000,
        'description' => 'Get exclusive league-themed rewards, such as special players or kits.',
    ],
    [
        'name' => 'League Match Boost',
        'slug' => 'league-match-boost',
        'price' => 3000000,
        'description' => 'Temporarily increase your team\'s stats by 15% for one league match.',
    ],

    // My Club-Related Items
    [
        'name' => 'Club Expansion Pack',
        'slug' => 'club-expansion-pack',
        'price' => 3000000,
        'description' => 'Increase your squad size to add more players to your roster.',
    ],
    [
        'name' => 'Club Training Facility Upgrade',
        'slug' => 'training-facility-upgrade',
        'price' => 5000000,
        'description' => 'Unlock better training facilities to speed up player growth by 10%.',
    ],
    [
        'name' => 'Club Reputation Booster',
        'slug' => 'club-reputation-booster',
        'price' => 4000000,
        'description' => 'Improve your club’s reputation to attract higher-quality players.',
    ],
    [
        'name' => 'Custom Club Branding Pack',
        'slug' => 'custom-club-branding-pack',
        'price' => 2000000,
        'description' => 'Customize your club’s logo, kit, and stadium to make it unique.',
    ],
    [
        'name' => 'Special Coach Contract',
        'slug' => 'special-coach-contract',
        'price' => 1500000,
        'description' => 'Hire a specialized coach to improve specific skills, such as defense or attack.',
    ],

    // My Money-Related Items
    [
        'name' => 'Money Multiplier (x2)',
        'slug' => 'money-multiplier',
        'price' => 1000000,
        'description' => 'Double the money earned from matches for the next 5 games.',
    ],
    [
        'name' => 'Sponsor Deal Pack',
        'slug' => 'sponsor-deal-pack',
        'price' => 3000000,
        'description' => 'Sign a sponsorship deal to earn extra money after every match.',
    ],
    [
        'name' => 'Daily Income Booster',
        'slug' => 'daily-income-booster',
        'price' => 2000000,
        'description' => 'Increase your daily income by 25% for the next week.',
    ],
    [
        'name' => 'Financial Recovery Pack',
        'slug' => 'financial-recovery-pack',
        'price' => 2500000,
        'description' => 'Instantly recover a portion of your lost money after a failed match.',
    ],
    [
        'name' => 'Investor Pack',
        'slug' => 'investor-pack',
        'price' => 10000000,
        'description' => 'Attract wealthy investors to increase your overall club funds by 10%.',
    ],

    // General Items
    [
        'name' => 'Legendary Challenge Player',
        'slug' => 'legendary-challenge-player',
        'price' => 15000000,
        'description' => 'Unlock a special player with elite stats for use in Challenge Matches.',
    ],
    [
        'name' => 'League Master Pack',
        'slug' => 'league-master-pack',
        'price' => 8000000,
        'description' => 'Gain an exclusive player, bonus money, and league boosts.',
    ],
    [
        'name' => 'Challenge Victory Trophy',
        'slug' => 'challenge-victory-trophy',
        'price' => 1200000,
        'description' => 'Display a trophy in your club\'s collection to commemorate winning challenges.',
    ],
    [
        'name' => 'Custom Match Ball',
        'slug' => 'custom-match-ball',
        'price' => 500000,
        'description' => 'Change the design of your match ball for a personal touch.',
    ],
];

// Special skills definition based on position and attributes
$specialSkills = [
    'GK' => [
        'Shot Stopper' => ['reflexes', 'shot_stopping'], // Focuses on reflexes and shot-stopping abilities to save shots (Goalkeeping)
        'Sweeper Keeper' => ['rushing_out', 'command_of_area'], // Emphasizes a goalkeeper's ability to rush out and command the area (Goalkeeping)
        'Penalty Saver' => ['penalty_saving'], // Specializes in saving penalties (Goalkeeping)
        'Aerial Dominator' => ['aerial_reach', 'handling'], // Dominates aerial situations with strong reach and handling (Goalkeeping)
    ],
    'LB' => [
        'Offensive Fullback' => ['crossing', 'dribbling'], // Focuses on creating chances with crosses and dribbling forward (Technical)
        'Defensive Fullback' => ['tackling', 'work_rate'], // A solid defensive fullback with good tackling and work rate (Technical, Mental)
        'Speedy Fullback' => ['pace', 'stamina'], // Quick and energetic, perfect for covering the whole flank (Physical)
    ],
    'CB' => [
        'Rock Solid Defender' => ['tackling', 'strength'], // Strong and reliable in defensive situations, with excellent tackling and strength (Technical, Physical)
        'Aerial Threat' => ['jumping_reach', 'heading_accuracy'], // Dominates in the air, both in defensive and attacking set pieces (Physical, Technical)
        'No-Nonsense Defender' => ['marking', 'positioning'], // Focuses on solid positioning and marking to prevent goal-scoring opportunities (Mental, Technical)
    ],
    'RB' => [
        'Offensive Fullback' => ['crossing', 'dribbling'], // Similar to LB, focused on attacking with dribbling and crossing abilities (Technical)
        'Defensive Fullback' => ['tackling', 'work_rate'], // A defensive-oriented fullback with a strong work ethic and tackling ability (Technical, Mental)
        'Speedy Fullback' => ['pace', 'stamina'], // Fast and tireless, ideal for covering ground on the right flank (Physical)
    ],
    'LM' => [
        'Wide Playmaker' => ['crossing', 'dribbling'], // A creative winger who provides quality crosses and dribbles past defenders (Technical)
        'Fast Winger' => ['pace', 'acceleration'], // A quick winger who uses speed to get past defenders and create opportunities (Physical)
        'Creative Winger' => ['flair', 'vision'], // A winger with exceptional flair and vision to create chances for teammates (Mental, Technical)
    ],
    'CDM' => [
        'Anchor' => ['positioning', 'tackling'], // A disciplined defensive midfielder with great positioning and tackling skills (Technical, Mental)
        'Defensive Wall' => ['strength', 'stamina'], // A physical and robust defensive midfielder, able to withstand pressure and cover ground (Physical)
        'Deep Lying Playmaker' => ['passing', 'vision'], // A midfield general who dictates play with excellent passing and vision (Technical, Mental)
    ],
    'CM' => [
        'Playmaker' => ['passing', 'vision', 'first_touch'], // A creative central midfielder who controls the game with passing, vision, and first touch (Technical, Mental)
        'Long Range Shooter' => ['long_shots', 'shot_power'], // A midfielder with the ability to score from distance (Technical)
        'Tackling Machine' => ['tackling', 'work_rate'], // A hard-working central midfielder who wins tackles and covers the field (Technical, Mental)
    ],
    'RM' => [
        'Wide Playmaker' => ['crossing', 'dribbling'], // A winger who creates opportunities with crosses and dribbling skills (Technical)
        'Fast Winger' => ['pace', 'acceleration'], // Quick and agile, ideal for explosive runs down the right wing (Physical)
        'Creative Winger' => ['flair', 'vision'], // A winger with creativity and vision to deliver innovative passes and assists (Mental, Technical)
    ],
    'CAM' => [
        'Attacking Playmaker' => ['dribbling', 'vision'], // A creative force in attack with dribbling and the ability to spot key passes (Technical, Mental)
        'Creative Genius' => ['flair', 'passing'], // An inventive player who uses flair and vision to create magical plays (Technical, Mental)
        'Finisher' => ['finishing', 'first_touch'], // A clinical attacking midfielder who excels in finishing chances (Technical)
    ],
    'LW' => [
        'Winger' => ['crossing', 'dribbling'], // A traditional winger focused on creating chances with crosses and dribbling (Technical)
        'Speedster' => ['pace', 'acceleration'], // A winger who uses their speed to break past defenders and make runs behind the defense (Physical)
        'Creative Winger' => ['flair', 'vision'], // A creative winger with flair and vision to generate attacking opportunities (Mental, Technical)
    ],
    'CF' => [
        'Clinical Finisher' => ['finishing', 'composure'], // A striker with deadly finishing and the composure to stay calm in front of goal (Technical, Mental)
        'Dribbler' => ['dribbling', 'first_touch'], // A forward with excellent dribbling and control to maneuver around defenders (Technical)
        'Target Man' => ['heading_accuracy', 'strength'], // A physically strong forward who excels in aerial duels and holds up the ball (Technical, Physical)
    ],
    'RW' => [
        'Crossing King' => ['crossing', 'dribbling'], // A winger known for accurate and dangerous crosses, along with dribbling ability (Technical)
        'Speedster' => ['pace', 'acceleration'], // A quick winger who uses their speed to beat defenders (Physical)
        'Trickster' => ['flair', 'dribbling'], // A winger with flair and dribbling skill to take on defenders and create space (Technical)
    ],
    'ST' => [
        'Poacher' => ['finishing', 'off_the_ball'], // A striker who is always in the right place to score goals and has great off-the-ball movement (Technical, Mental)
        'Strong Forward' => ['strength', 'heading_accuracy'], // A powerful forward with excellent physical attributes and heading ability (Physical, Technical)
        'Fast Striker' => ['pace', 'acceleration'], // A fast forward who uses their speed to outrun defenders and create goal-scoring opportunities (Physical)
    ],
];

$teamRoles = [
    "captain" => "Captain 👑",
    "penaltyTaker" => "Penalty Taker ⚽️",
    "directFreeKickTaker" => "Direct Free Kick Taker 🔥",
    "indirectFreeKickTaker" => "Indirect Free Kick Taker 💡",
    "leftCornerKickTaker" => "Left Corner Kick Taker ↖️",
    "rightCornerKickTaker" => "Right Corner Kick Taker ↗️",
    "throwInSpecialist" => "Throw-In Specialist 📥",
    "longRangeShooter" => "Long-Range Shooter 🏹",
    "penaltyBackup" => "Penalty Backup 🛑",
];

