const canvas = document.getElementById("footballPitch");
const ctx = canvas.getContext("2d");
// Pitch Dimensions
const width = canvas.width;
const height = canvas.height;

// Colors
const pitchColor = "#4CAF50";
const lineColor = "#FFFFFF";
const team1Color = "#FF0000"; // Red
const team2Color = "#0000FF"; // Blue
const playerScore = 5;

// Formations
const pitchX = 100;

// Player Positions for the 4-4-2 Formation
const GK = { posName: "GK", x: (width * 4) / pitchX, y: height / 2 };
const LB = { posName: "LB", x: (width * 18) / pitchX, y: height / 6 };
const RB = { posName: "RB", x: (width * 18) / pitchX, y: (height * 5) / 6 };
const LCB = { posName: "CB", x: (width * 12) / pitchX, y: height / 3 };
const RCB = { posName: "CB", x: (width * 12) / pitchX, y: (height * 2) / 3 };
const LCM = { posName: "CM", x: (width * 30) / pitchX, y: height / 3 };
const RCM = { posName: "CM", x: (width * 30) / pitchX, y: (height * 2) / 3 };
const LM = { posName: "LM", x: (width * 30) / pitchX, y: height / 6 };
const RM = { posName: "RM", x: (width * 30) / pitchX, y: (height * 5) / 6 };
const LF = { posName: "LF", x: (width * 45) / pitchX, y: (height * 3) / 8 };
const RF = { posName: "RF", x: (width * 45) / pitchX, y: (height * 5) / 8 };

// Player Positions for the 4-3-3 Formation
const LW = { posName: "LW", x: (width * 40) / pitchX, y: height / 6 };
const RW = { posName: "RW", x: (width * 40) / pitchX, y: (height * 5) / 6 };
const CF = { posName: "CF", x: (width * 40) / pitchX, y: height / 2 };
const ST = { posName: "ST", x: (width * 45) / pitchX, y: height / 2 };

// Player Positions for the 3-5-2 Formation
const LCB_3_5_2 = {
  posName: "CB",
  x: (width * 12) / pitchX,
  y: (height * 1) / 3 - 12,
};
const CB_3_5_2 = { posName: "CB", x: (width * 12) / pitchX, y: height / 2 };
const RCB_3_5_2 = {
  posName: "CB",
  x: (width * 12) / pitchX,
  y: height - (height * 1) / 3 + 12,
};
const LM_3_5_2 = { posName: "LM", x: (width * 36) / pitchX, y: height / 6 };
const RM_3_5_2 = {
  posName: "RM",
  x: (width * 36) / pitchX,
  y: (height * 5) / 6,
};
const AMC_3_5_2 = { posName: "AMC", x: (width * 36) / pitchX, y: height / 2 };

// Player Positions for the 4-2-3-1 Formation
const CDM1_4_2_3_1 = {
  posName: "DM",
  x: (width * 25) / pitchX,
  y: height / 3,
};
const CDM2_4_2_3_1 = {
  posName: "DM",
  x: (width * 25) / pitchX,
  y: (height * 2) / 3,
};
const AMC = { posName: "AMC", x: (width * 35) / pitchX, y: height / 2 };
const LW_4_2_3_1 = { posName: "LW", x: (width * 40) / pitchX, y: height / 4 };
const RW_4_2_3_1 = {
  posName: "RW",
  x: (width * 40) / pitchX,
  y: (height * 3) / 4,
};

// Player Positions for the 4-1-4-1 Formation
const DM = { posName: "DM", x: (width * 24) / pitchX, y: height / 2 };
const LCM_4_1_4_1 = { posName: "CM", x: (width * 28) / pitchX, y: height / 3 };
const RCM_4_1_4_1 = {
  posName: "CM",
  x: (width * 28) / pitchX,
  y: (height * 2) / 3,
};
const ST_4_1_4_1 = { posName: "ST", x: (width * 50) / pitchX, y: height / 2 };

// Player Positions for the 5-4-1 Formation
const LWB = { posName: "LB", x: (width * 20) / pitchX, y: height / 6 };
const RWB = {
  posName: "RB",
  x: (width * 20) / pitchX,
  y: (height * 5) / 6,
};
const LCB_5_4_1 = { posName: "CB", x: (width * 10) / pitchX, y: height / 3 };
const CB_5_4_1 = { posName: "CB", x: (width * 20) / pitchX, y: height / 2 };
const RCB_5_4_1 = { posName: "CB", x: (width * 30) / pitchX, y: height / 3 };
const CM_5_4_1 = { posName: "CM", x: (width * 35) / pitchX, y: height / 2 };
const ST_5_4_1 = { posName: "ST", x: (width * 50) / pitchX, y: height / 2 };

// Function to generate player positions for various formations
function generateFormation(formation) {
  switch (formation) {
    case "433":
      return [GK, LB, LCB, RCB, RB, LCM, RCM, LW, CF, RW];
    case "442":
      return [GK, LB, LCB, RCB, RB, LM, LCM, RCM, RM, LF, RF];
    case "352":
      return [
        GK,
        LCB_3_5_2,
        CB_3_5_2,
        RCB_3_5_2,
        { ...CDM1_4_2_3_1, y: CDM1_4_2_3_1.y + 12 },
        { ...CDM2_4_2_3_1, y: CDM2_4_2_3_1.y - 12 },
        LM_3_5_2,
        RM_3_5_2,
        AMC_3_5_2,
        LF,
        RF,
      ];
    case "4231":
      return [
        GK,
        LB,
        LCB,
        RCB,
        RB,
        CDM1_4_2_3_1,
        CDM2_4_2_3_1,
        AMC,
        LM,
        RM,
        CF,
      ];
    case "4141":
      return [
        GK,
        LB,
        LCB,
        RCB,
        RB,
        { ...DM, x: DM.x - 12 },
        { ...LCM, x: LCM.x + 16 },
        { ...RCM, x: RCM.x + 16 },
        LM,
        RM,
        ST,
      ];
    case "541":
      return [
        GK,
        LWB,
        LCB_3_5_2,
        CB_3_5_2,
        RCB_3_5_2,
        RWB,
        LCM_4_1_4_1,
        RCM_4_1_4_1,
        LM_3_5_2,
        RM_3_5_2,
        ST,
      ];
    case "4312":
      return [GK, LB, LCB, RCB, RB, DM, LCM, RCM, CF, LF, RF];
    case "343":
      return [
        GK,
        LCB_3_5_2,
        CB_3_5_2,
        RCB_3_5_2,
        LM,
        LCM_4_1_4_1,
        RCM_4_1_4_1,
        RM,
        LW_4_2_3_1,
        ST,
        RW_4_2_3_1,
      ];
    case "4222":
      return [GK, LB, LCB, RCB, RB, LCM, RCM, LF, RF, LM_3_5_2, RM_3_5_2];
    default:
      console.error("Invalid formation");
      return [];
  }
}

let team1InMatch = {
  name: team1.name,
  score: 0,
  players: generateFormation(team1.formation).map((pos, idx) => {
    return {
      ...pos,
      ...team1.players[idx],
      score: playerScore,
    };
  }),
  bench: team1.bench,
};
let team2InMatch = {
  name: team2.name,
  score: 0,
  players: generateFormation("442").map((pos, idx) => {
    return {
      ...pos,
      x: width - pos.x,
      ...team2.players[idx],
      score: playerScore,
    };
  }),
  bench: team2.bench,
};

function getPositionColor(position) {
  const positionColors = {
    Goalkeepers: "#ff8811",
    Defenders: "#3ec300",
    Midfielders: "#337ca0",
    Attackers: "#ff1d15",
  };

  for (const [group, positions] of Object.entries(positionGroups)) {
    if (positions.includes(position)) {
      return positionColors[group];
    }
  }

  return "gray"; // Default color if position is not found
}

// Football Pitch
const drawFootballPitch = () => {
  // Helper function to draw lines
  function drawLine(x1, y1, x2, y2) {
    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);
    ctx.strokeStyle = lineColor;
    ctx.lineWidth = 2;
    ctx.stroke();
  }

  // Helper function to draw circles
  function drawCircle(x, y, color, isFilled = true) {
    ctx.beginPath();
    ctx.arc(x, y, 10, 0, Math.PI * 2);
    if (isFilled) {
      ctx.fillStyle = color;
      ctx.fill();
    } else {
      ctx.strokeStyle = color;
      ctx.lineWidth = 2;
      ctx.stroke();
    }
  }

  // Draw penalty and goal areas (simplified for brevity)
  function drawRect(x, y, w, h) {
    ctx.strokeStyle = lineColor;
    ctx.lineWidth = 2;
    ctx.strokeRect(x, y, w, h);
  }

  // Function to draw player numbers
  function drawPlayerNumber(x, y, number) {
    ctx.font = "10px Arial";
    ctx.fillStyle = "#FFFFFF";
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.fillText(number, x, y);
  }

  // Function to draw player names
  function drawPlayerName(x, y, name) {
    ctx.font = "10px Arial";
    ctx.fillStyle = "#FFFFFF";
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.fillText(name, x, y + 20);
  }

  // Draw a ball at a specific position
  function drawBall() {
    ctx.beginPath();
    ctx.arc(width / 2, height / 2, 5, 0, Math.PI * 2); // Ball with radius 5
    ctx.fillStyle = "white";
    ctx.fill();
    ctx.strokeStyle = "black";
    ctx.stroke();
  }

  // Draw Team
  const drawPlayerPositions = () => {
    team1InMatch.players.forEach((pos, index) => {
      drawCircle(pos.x, pos.y, getPositionColor(pos.posName));
      drawPlayerNumber(pos.x, pos.y, index + 1);
      drawPlayerName(pos.x, pos.y, pos.name);
      drawPlayerScore(pos);
    });
    team2InMatch.players.forEach((pos, index) => {
      drawCircle(pos.x, pos.y, getPositionColor(pos.posName));
      drawPlayerNumber(pos.x, pos.y, index + 1);
      drawPlayerName(pos.x, pos.y, pos.name);
      drawPlayerScore(pos);
    });
  };

  // Function to redraw the pitch and players
  function redraw() {
    // Redraw pitch
    ctx.fillStyle = pitchColor;
    ctx.fillRect(0, 0, width, height);

    // Redraw lines
    drawLine(10, 10, width - 10, 10);
    drawLine(width - 10, 10, width - 10, height - 10);
    drawLine(width - 10, height - 10, 10, height - 10);
    drawLine(10, height - 10, 10, 10);
    drawLine(width / 2, 10, width / 2, height - 10);

    // Redraw center circle and other elements
    drawCircle(width / 2, height / 2, 40, lineColor, false);
    drawCircle(width / 2, height / 2, 2, lineColor, true);
    drawRect(10, height / 2 - 40, 30, 80);
    drawRect(width - 40, height / 2 - 40, 30, 80);
    drawCircle(20, height / 2, 2, lineColor, true);
    drawCircle(width - 20, height / 2, 2, lineColor, true);

    // Redraw players
    drawPlayerPositions();
    drawBall();
  }

  // Initial draw
  redraw();
};
drawFootballPitch();

// Function to draw a rounded rectangle
function drawRoundedRect(x, y, width, height, radius, fillStyle, strokeStyle) {
  ctx.beginPath();
  ctx.moveTo(x + radius, y);
  ctx.lineTo(x + width - radius, y);
  ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
  ctx.lineTo(x + width, y + height - radius);
  ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
  ctx.lineTo(x + radius, y + height);
  ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
  ctx.lineTo(x, y + radius);
  ctx.quadraticCurveTo(x, y, x + radius, y);
  ctx.closePath();

  if (fillStyle) {
    ctx.fillStyle = fillStyle;
    ctx.fill();
  }
}

function drawPlayerScore(player) {
  const { x, y, score } = player;
  // Draw circle around the score
  const scoreX = x;
  const scoreY = y + 28;
  const maxScore = 10; // Maximum score
  const normalizedScore = score / maxScore; // Normalize score between 0 and 1

  // Use normalizedScore to create a gradient from blue -> green -> yellow -> red
  const red = Math.min(255, Math.floor(normalizedScore * 255));
  const green = Math.min(
    255,
    Math.floor((1 - Math.abs(normalizedScore - 0.5) * 2) * 255)
  ); // Peaks in the middle
  const blue = Math.min(255, Math.floor((1 - normalizedScore) * 255));

  const backgroundColor = `rgb(${red}, ${green}, ${blue})`;

  // Calculate luminance for dynamic text color
  const luminance = (0.299 * red + 0.587 * green + 0.114 * blue) / 255;
  const textColor = luminance > 0.5 ? "#000000" : "#ffffff";

  drawRoundedRect(scoreX - 11, scoreY, 22, 14, 5, backgroundColor);

  // Add player score
  ctx.fillStyle = textColor;
  ctx.font = "10px Arial";
  ctx.textAlign = "center";
  ctx.fillText(score.toFixed(1), scoreX, scoreY + 7);
}

function simulateMatch(team1, team2) {
  const matchTime = 90 * 60; // Total match duration in minutes
  const maxHalfTime = 45 * 60; // Total match duration in minutes
  const maxExtraTime = 10; // Maximum possible extra time in minutes
  let currentTime = 0;
  let currentTimeInSeconds = 0;
  let halfTimePassed = false;
  let extraTimePassed = false;
  let matchOver = false;

  // Random extra time
  const extraHalfTime = Math.floor(Math.random() * maxExtraTime) * 60;
  const extraTime = Math.floor(Math.random() * maxExtraTime) * 60;

  // Random half-time duration (between 45 and 55 minutes)
  const halfTimeDuration = maxHalfTime + extraHalfTime; // Half-time between 45 and 55 minutes

  const matchInterval = setInterval(() => {
    document.getElementById("minute").innerText =
      formatTime(currentTimeInSeconds)["minute"];
    document.getElementById("second").innerText =
      formatTime(currentTimeInSeconds)["second"];

    if (currentTimeInSeconds === maxHalfTime && extraHalfTime > 0) {
      logEvent(
        currentTimeInSeconds,
        "extra-time",
        `Extra time of ${extraHalfTime / 60} minutes begins!`
      );
    }

    if (currentTimeInSeconds >= halfTimeDuration && !halfTimePassed) {
      // Notify half-time
      logEvent(
        currentTimeInSeconds,
        "end",
        `Half-time: ${team1.name} ${team1.score} - ${team2.score} ${team2.name}`
      );
      halfTimePassed = true;
      currentTimeInSeconds = currentTime = 45 * 60; // Set the second half to start at minute 45
      logEvent(currentTimeInSeconds, "start", "Second half begins!");
    }

    if (currentTimeInSeconds >= matchTime && !extraTimePassed) {
      // After second half, random extra time begins
      logEvent(
        currentTimeInSeconds,
        "extra-time",
        `Extra time of ${extraTime / 60} minutes begins!`
      );
      extraTimePassed = true;
    }

    if (currentTimeInSeconds >= matchTime + extraTime && !matchOver) {
      // Notify end of match after extra time
      clearInterval(matchInterval);
      logEvent(
        currentTimeInSeconds,
        "end",
        `Match Over! Final Score: ${team1.name} ${team1.score} - ${team2.score} ${team2.name}`
      );
      return;
    }

    if (currentTimeInSeconds === currentTime) {
      currentTime += Math.floor(Math.random() * 60) + 30;
      if (!currentTimeInSeconds) {
        logEvent(currentTimeInSeconds, "start", "Match start");
      } else {
        // Simulate an action
        const team = Math.random() < 0.5 ? team1 : team2;
        const player =
          team.players[Math.floor(Math.random() * team.players.length)];
        let previousAction = null; // Initially, no previous action
        simulateAction(
          team,
          player,
          team1,
          team2,
          currentTimeInSeconds,
          previousAction
        );
      }
    }

    // Increment the total time
    currentTimeInSeconds++;
  }, 10); // Delay of 1 second per iteration
}

// Convert time to mm:ss format
function formatTime(time) {
  // Calculate minutes and seconds
  const minutes = Math.floor(time / 60);
  const seconds = time % 60;

  // Format the time as MM:SS
  return {
    minute: String(minutes).padStart(2, "0"),
    second: String(seconds).padStart(2, "0"),
  };
}

// Function to get follow-up actions based on the previous action
function getFollowUpActions(previousAction, position, validActionsByPosition) {
  const followUpActions = {
    dribble: ["assist", "shoot", "pass"],
    assist: ["shoot", "cross", "pass"],
    shoot: ["rebound", "assist", "pass"],
    header: ["assist", "shoot", "clearance"],
    pass: ["dribble", "shoot", "assist"],
    longShot: ["pass", "assist"],
    chipShot: ["shoot", "pass"],
  };

  // If no follow-up actions for this action, return all possible actions for the position
  if (followUpActions[previousAction]) {
    return followUpActions[previousAction];
  }

  return validActionsByPosition[position]; // If no specific follow-up, return all actions for the position
}

function simulateAction(
  team,
  player,
  team1,
  team2,
  currentTime,
  previousAction
) {
  // Define valid actions for each position
  const validActionsByPosition = {
    GK: [
      "save",
      "catch",
      "punch",
      "goalKick",
      "distribution",
      "ownGoal",
      "foul",
    ],
    CM: [
      "pass",
      "dribble",
      "keyPass",
      "longShot",
      "assist",
      "foul",
      "tackle",
      "intercept",
      "clearance",
    ],
    CF: [
      "shoot",
      "offside",
      "assist",
      "ownGoal",
      "penalty",
      "header",
      "longShot",
    ],
    LF: [
      "shoot",
      "offside",
      "assist",
      "ownGoal",
      "penalty",
      "header",
      "longShot",
    ],
    RF: [
      "shoot",
      "offside",
      "assist",
      "ownGoal",
      "penalty",
      "header",
      "longShot",
    ],
    ST: [
      "shoot",
      "offside",
      "assist",
      "ownGoal",
      "penalty",
      "header",
      "chipShot",
    ],
    RW: ["cross", "cutInside", "dribble", "assist", "shoot", "ownGoal", "pass"],
    LW: ["cross", "cutInside", "dribble", "assist", "shoot", "ownGoal", "pass"],
    CB: ["tackle", "clearance", "header", "block", "foul", "ownGoal"],
    RB: ["tackle", "clearance", "header", "cross", "assist", "foul", "ownGoal"],
    LB: ["tackle", "clearance", "header", "cross", "assist", "foul", "ownGoal"],
    DM: [
      "intercept",
      "shieldBall",
      "throughBall",
      "switchPlay",
      "assist",
      "tackle",
      "foul",
      "ownGoal",
    ],
    AMC: [
      "keyPass",
      "assist",
      "shoot",
      "foul",
      "offside",
      "longShot",
      "ownGoal",
    ],
    LM: ["shoot", "assist", "dribble", "foul", "ownGoal", "cross"],
    RM: ["shoot", "assist", "dribble", "foul", "ownGoal", "cross"],
  };
  const { posName } = player;
  // Randomly select a valid action for the chosen position
  let actions = validActionsByPosition[posName];
  if (previousAction) {
    actions = getFollowUpActions(
      previousAction,
      posName,
      validActionsByPosition
    );
  }
  const action = actions[Math.floor(Math.random() * actions.length)];

  const team1score = document.getElementById("team-1-score");
  const team2score = document.getElementById("team-2-score");

  switch (action) {
    case "shoot":
      const scored = attemptOutcome(
        action,
        player,
        team === team1 ? team2 : team1
      );
      if (scored) {
        logEvent(
          currentTime,
          "goal",
          `GOAL! ${player.name} scores for ${team.name}.`
        );
        player.score += Math.random() * (3 - 1) + 1;
        player.score = Math.min(player.score, 10);
        drawPlayerScore(player);
      } else {
        logEvent(currentTime, "miss-goal", `${player.name} shoots but misses.`);
      }
      break;

    case "foul":
      logEvent(currentTime, "foul", `${player.name} commits a foul.`);
      player.score -= Math.random() * (2 - 0.5) + 0.5;
      player.score = Math.max(player.score, 1);
      drawPlayerScore(player);
      break;

    case "card":
      handleCard(player, team, currentTime);
      break;

    case "injury":
      handlePlayerExit(currentTime, team, player, "injury");
      break;

    case "corner":
      logEvent(currentTime, "corner", `${player.name} takes a corner kick.`);
      break;

    case "save":
      logEvent(currentTime, "save", `Goalkeeper saves a shot!`);
      player.score += Math.random() * (3 - 1) + 1;
      player.score = Math.min(player.score, 10);
      drawPlayerScore(player);
      break;

    case "assist":
      logEvent(currentTime, "assist", `${player.name} assists in a goal.`);
      player.score += Math.random() * (3 - 1) + 1;
      player.score = Math.min(player.score, 10);
      drawPlayerScore(player);
      break;

    case "tackle":
      logEvent(
        currentTime,
        "tackle",
        `${player.name} successfully tackles an opponent.`
      );
      player.score += Math.random() * (2 - 0.5) + 0.5;
      player.score = Math.min(player.score, 10);
      drawPlayerScore(player);
      break;

    case "offside":
      logEvent(currentTime, "offside", `${player.name} is caught offside.`);
      break;

    case "penalty":
      const penaltyScored = attemptOutcome(
        action,
        player,
        team === team1 ? team2 : team1
      );
      if (penaltyScored) {
        team.score++;
        logEvent(
          currentTime,
          "goal",
          `${player.name} scores a penalty for ${team.name}.`
        );
        player.score += Math.random() * (3 - 1) + 1;
        player.score = Math.min(player.score, 10);
        drawPlayerScore(player);
      } else {
        logEvent(
          currentTime,
          "miss-goal",
          `${player.name} misses the penalty.`
        );
      }
      break;

    case "ownGoal":
      const ownGoal = attemptOutcome(action, player, team);
      if (ownGoal) {
        logEvent(
          currentTime,
          "goal",
          `${player.name} accidentally scores an own goal!`
        );
        const opposingTeam = team === team1 ? team2 : team1;
        opposingTeam.score++;
        player.score -= Math.random() * (2 - 1) + 1;
        player.score = Math.max(player.score, 1);
        drawPlayerScore(player);
      }
      break;

    default:
      // logEvent(currentTime, "", `Unknown action.`);
      break;
  }

  team1score.innerText = team1.score;
  team2score.innerText = team2.score;
}

function attemptOutcome(type, attacker, defendingTeam) {
  const goalkeeper = defendingTeam.players.find(
    (p) => p.best_position === "GK"
  );

  // Forward attributes
  const { composure, finishing, heading, flair, off_the_ball } = attacker;

  // Goalkeeper attributes
  const { decision, concentration } = goalkeeper;

  let baseChance = 0;
  let goalkeeperImpact = 0;

  if (type === "shoot") {
    // Logic for regular shot
    baseChance =
      composure * 0.3 + finishing * 0.4 + heading * 0.2 + flair * 0.1;

    const positioningBonus = off_the_ball * 0.2; // Adds to base chance
    baseChance += positioningBonus;

    goalkeeperImpact = decision * 0.5 + concentration * 0.5;
  } else if (type === "penalty") {
    // Logic for penalty
    baseChance = composure * 0.5 + finishing * 0.5;
    goalkeeperImpact = decision * 0.7; // Decision is more critical for penalties
  }

  // Calculate final chance
  const finalChance = Math.max(0, baseChance - goalkeeperImpact);

  // Randomly determine the outcome
  const randomChance = Math.random() * 100; // Random number between 0 and 100
  return randomChance < finalChance;
}

function handleCard(player, team, currentTime) {
  const cardType = Math.random() < 0.5 ? "yellow" : "red";
  logEvent(
    currentTime,
    `${cardType} card`,
    `${player.name} receives a ${cardType} card.`
  );

  if (cardType === "red") {
    handlePlayerExit(currentTime, team, player, "red card");
  }
}

function handlePlayerExit(currentTime, team, player, reason) {
  logEvent(
    currentTime,
    "player-out",
    `${player.name} exits the match (${reason}).`
  );
  const substitute = team.bench.shift(); // Get the next substitute

  if (substitute) {
    logEvent(
      currentTime,
      "player-in",
      `${substitute.name} enters the field as a substitute.`
    );
    team.players = team.players.filter((p) => p !== player); // Remove the player
    team.players.push(substitute); // Add the substitute
  } else {
    logEvent(
      currentTime,
      "player-empty",
      `${team.name} has no substitutes left!`
    );
    team.players = team.players.filter((p) => p !== player); // Remove the player
  }
}

function getActionIcon(action) {
  switch (action) {
    case "start":
      return "mdi-clock-outline";
    case "end":
      return "mdi-clock-remove-outline";
    case "extra-time":
      return "mdi-clock-plus-outline";
    case "goal":
      return "mdi-soccer";

    default:
      return "";
  }
}

function logEvent(time, action, message) {
  const html = `
    <div class="flex-shrink-0 avatar-xs acitivity-avatar">
        <div class="avatar-title bg-success-subtle text-success rounded-circle">
            <i class="mdi ${getActionIcon(action)}"></i>
        </div>
    </div>
    <div class="flex-grow-1 ms-3 pt-1">
        <p class="text-muted mb-0 fs-12">${formatTime(time)["minute"]}:${
    formatTime(time)["second"]
  }</p>
        <h6 class="mb-1">${message}</h6>
    </div>`;
  const parentElement = document.getElementById("match-timeline");
  const newElement = document.createElement("div");
  newElement.classList.add("acitivity-item", "pb-3", "d-flex");
  newElement.innerHTML = html;

  parentElement.insertBefore(newElement, parentElement.firstChild);
}

// Start the match simulation
simulateMatch(team1InMatch, team2InMatch);
