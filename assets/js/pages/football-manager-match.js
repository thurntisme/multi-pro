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
const GK = { x: (width * 4) / 100, y: height / 2 };
const LB = { x: (width * 20) / 100, y: height / 6 };
const RB = { x: (width * 20) / 100, y: (height * 5) / 6 };
const LCB = { x: (width * 12) / 100, y: height / 3 };
const RCB = { x: (width * 12) / 100, y: (height * 2) / 3 };
const LCM = { x: (width * 28) / 100, y: height / 3 };
const RCM = { x: (width * 28) / 100, y: (height * 2) / 3 };
const LM = { x: (width * 36) / 100, y: height / 6 };
const RM = { x: (width * 36) / 100, y: (height * 5) / 6 };
const LF = { x: (width * 45) / 100, y: (height * 3) / 8 };
const RF = { x: (width * 45) / 100, y: (height * 5) / 8 };
const formations = {
  442: [GK, LB, RB, LCB, RCB, LCM, RCM, LM, RM, LF, RF],
};

let team1Positions = formations["442"].map((pos, idx) => {
  const accuracy = Math.floor(Math.random() * (100 - 50 + 1)) + 50;
  const speed = Math.floor(Math.random() * (100 - 50 + 1)) + 50;
  const defense = Math.floor(Math.random() * (100 - 50 + 1)) + 50;
  return {
    ...pos,
    score: playerScore,
    accuracy,
    speed,
    defense,
    name: team1.players[idx]["name"],
  };
});
let team2Positions = formations["442"].map((pos, idx) => {
  const accuracy = Math.floor(Math.random() * (100 - 50 + 1)) + 50;
  const speed = Math.floor(Math.random() * (100 - 50 + 1)) + 50;
  const defense = Math.floor(Math.random() * (100 - 50 + 1)) + 50;
  return {
    x: width - pos.x,
    y: pos.y,
    score: playerScore,
    accuracy,
    speed,
    defense,
    name: team2.players[idx]["name"],
  };
});

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
  function drawCircle(x, y, radius, color, isFilled = true) {
    ctx.beginPath();
    ctx.arc(x, y, radius, 0, Math.PI * 2);
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
    team1Positions.forEach((pos, index) => {
      drawCircle(pos.x, pos.y, 10, team1Color);
      drawPlayerNumber(pos.x, pos.y, index + 1);
      drawPlayerName(pos.x, pos.y, pos.name);
      drawPlayerScore(pos);
    });
    team2Positions.forEach((pos, index) => {
      drawCircle(pos.x, pos.y, 10, team2Color);
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

  document.getElementById("team-1-name").innerText = team1.name;
  document.getElementById("team-2-name").innerText = team2.name;

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
        simulateAction(team, player, team1, team2, currentTimeInSeconds);
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

function simulateAction(team, player, team1, team2, currentTime) {
  const actions = [
    "pass",
    "shoot",
    "foul",
    "card",
    "injury",
    "corner",
    "save",
    "assist",
    "tackle",
    "offside",
    "penalty",
    "ownGoal",
  ];
  const action = actions[Math.floor(Math.random() * actions.length)];

  const team1score = document.getElementById("team-1-score");
  const team2score = document.getElementById("team-2-score");

  switch (action) {
    case "pass":
      logEvent(currentTime, "pass", `${player.name} passes the ball.`);
      break;

    case "shoot":
      const scored = attemptOutcome(
        player,
        team,
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
      break;

    case "assist":
      logEvent(currentTime, "assist", `${player.name} assists in a goal.`);
      break;

    case "tackle":
      logEvent(
        currentTime,
        "tackle",
        `${player.name} successfully tackles an opponent.`
      );
      break;

    case "offside":
      logEvent(currentTime, "offside", `${player.name} is caught offside.`);
      break;

    case "penalty":
      const penaltyScored = attemptOutcome(
        player,
        team,
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
      break;

    default:
      logEvent(currentTime, "", `Unknown action.`);
      break;
  }

  team1score.innerText = team1.score;
  team2score.innerText = team2.score;
}

function attemptOutcome(player, attackingTeam, defendingTeam) {
  const attackerSkill = player.accuracy + Math.random() * player.speed;
  const defenderSkill = defendingTeam.players.find((p) =>
    p.name.includes("Goalkeeper")
  ).defense;
  return attackerSkill > defenderSkill;
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
simulateMatch(team1, team2);
