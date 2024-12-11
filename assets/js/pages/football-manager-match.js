renderTeamInFitch(groupTeams, { circleRadius: 8 });

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
      "foul",
      "tackle",
      "intercept",
      "clearance",
    ],
    CF: ["shoot", "offside", "ownGoal", "penalty", "header", "longShot"],
    LF: ["shoot", "offside", "ownGoal", "penalty", "header", "longShot"],
    RF: ["shoot", "offside", "ownGoal", "penalty", "header", "longShot"],
    ST: ["shoot", "offside", "ownGoal", "penalty", "header", "chipShot"],
    RW: ["cross", "cutInside", "dribble", "shoot", "ownGoal", "pass"],
    LW: ["cross", "cutInside", "dribble", "shoot", "ownGoal", "pass"],
    CB: ["tackle", "clearance", "header", "block", "foul", "ownGoal"],
    RB: ["tackle", "clearance", "header", "cross", "foul", "ownGoal"],
    LB: ["tackle", "clearance", "header", "cross", "foul", "ownGoal"],
    CDM: [
      "intercept",
      "shieldBall",
      "throughBall",
      "switchPlay",
      "tackle",
      "foul",
      "ownGoal",
    ],
    CAM: ["keyPass", "shoot", "foul", "offside", "longShot", "ownGoal"],
    LM: ["shoot", "dribble", "foul", "ownGoal", "cross"],
    RM: ["shoot", "dribble", "foul", "ownGoal", "cross"],
  };
  const { best_position } = player;
  // Randomly select a valid action for the chosen position
  let actions = validActionsByPosition[best_position];
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
        const assistPlayer =
          team.players[Math.floor(Math.random() * team.players.length)];
        logEvent(
          currentTime - 28,
          "assist",
          `${assistPlayer.name} assists in a goal.`
        );
        logEvent(
          currentTime,
          "goal",
          `GOAL! ${player.name} scores for ${team.name}.`
        );
        player.score += Math.random() * (3 - 1) + 1;
        player.score = Math.min(player.score, 10);
        drawPlayerScore(player);
        team.score++;
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
  const { finishing, heading } = attacker.attributes.technical;
  const { composure, flair, off_the_ball } = attacker.attributes.mental;

  // Goalkeeper attributes
  let decision, concentration;
  if (goalkeeper) {
    decision = goalkeeper.attributes.mental.decision;
    concentration = goalkeeper.attributes.mental.concentration;
  } else {
    decision = 44;
    concentration = 44;
  }

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
simulateMatch(team1, team2);
