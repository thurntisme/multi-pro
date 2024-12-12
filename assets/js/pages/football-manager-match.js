const teamsInMatch = groupTeams.map((team) => {
  const players = generateFormation(team.formation).map((pos, idx) => {
    return {
      position_in_match: pos.posName,
      score: 5,
      ...team.players[idx],
    };
  });
  return { ...team, players };
});

const redraw = () => {
  renderTeamInFitch(teamsInMatch, { circleRadius: 8, isDisplayScore: true });
};
redraw();

function simulateMatch(groupTeams) {
  const team1 = groupTeams[0];
  const team2 = groupTeams[1];
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

function getRandPlayerFromTeam(action = "", team, player = "") {
  const filterPlayers = team.players.filter((p) => {
    let condition = true;
    if (player) {
      condition = condition && p.uuid !== player.uuid;
    }
    if (action === "block") {
      condition = condition && !["GK", "CB"].includes(p.position_in_match);
    }
    if (action === "save") {
      condition = condition && ["GK"].includes(p.position_in_match);
    }
    if (action === "intercept") {
      condition = condition && !["GK", "CB"].includes(p.position_in_match);
    }
    return condition;
  });
  return filterPlayers[Math.floor(Math.random() * filterPlayers.length)];
}

function simulateAction(team, player, team1, team2, currentTime) {
  // Define valid actions for each position
  const validActionsByPosition = {
    GK: [
      "save",
      "catch",
      "punch",
      "goalKick",
      "ownGoal",
      "foul",
      "penaltySave",
    ],
    LB: [
      "tackle",
      "cutInside",
      "clearance",
      "header",
      "cross",
      "foul",
      "ownGoal",
      "shoot",
      "longShot",
      "freeKick",
      "intercept",
      "offside",
    ],
    CB: [
      "tackle",
      "clearance",
      "header",
      "block",
      "foul",
      "intercept",
      "ownGoal",
    ],
    RB: [
      "tackle",
      "cutInside",
      "clearance",
      "header",
      "cross",
      "foul",
      "ownGoal",
      "shoot",
      "longShot",
      "freeKick",
      "intercept",
      "offside",
    ],
    LM: [
      "shoot",
      "cutInside",
      "dribble",
      "foul",
      "ownGoal",
      "cross",
      "longShot",
      "chipShot",
      "freeKick",
      "intercept",
      "offside",
    ],
    CDM: [
      "intercept",
      "shieldBall",
      "tackle",
      "foul",
      "ownGoal",
      "shoot",
      "longShot",
      "freeKick",
      "clearance",
    ],
    CM: [
      "dribble",
      "keyPass",
      "longShot",
      "foul",
      "tackle",
      "intercept",
      "clearance",
      "shoot",
      "freeKick",
    ],
    CAM: [
      "keyPass",
      "shoot",
      "foul",
      "offside",
      "longShot",
      "chipShot",
      "ownGoal",
      "freeKick",
    ],
    RM: [
      "shoot",
      "cutInside",
      "dribble",
      "foul",
      "ownGoal",
      "cross",
      "chipShot",
      "longShot",
      "freeKick",
      "offside",
    ],
    LW: [
      "cross",
      "cutInside",
      "dribble",
      "shoot",
      "ownGoal",
      "chipShot",
      "longShot",
      "freeKick",
      "offside",
    ],
    CF: [
      "shoot",
      "offside",
      "ownGoal",
      "penalty",
      "header",
      "chipShot",
      "longShot",
      "freeKick",
    ],
    ST: [
      "shoot",
      "offside",
      "ownGoal",
      "penalty",
      "header",
      "chipShot",
      "longShot",
      "freeKick",
    ],
    RW: [
      "cross",
      "cutInside",
      "dribble",
      "shoot",
      "ownGoal",
      "chipShot",
      "longShot",
      "freeKick",
      "offside",
    ],
  };
  const { position_in_match } = player;
  // Randomly select a valid action for the chosen position
  let actions = validActionsByPosition[position_in_match];
  const action = actions[Math.floor(Math.random() * actions.length)];
  const opposingTeam = team === team1 ? team2 : team1;
  const randTime = Math.round(Math.random() * (40 - 10) + 10);

  const team1score = document.getElementById("team-1-score");
  const team2score = document.getElementById("team-2-score");

  const lowPlayerScore = Math.random() * 0.5; // Range: 0 to 0.5
  const mediumPlayerScore = Math.random() * (1.0 - 0.5) + 0.5; // Range: 0.5 to 1.0
  const highPlayerScore = Math.random() * (1.5 - 1.0) + 1.0; // Range: 1.0 to 1.5

  switch (action) {
    case "block":
      const shootType = Math.random() < 0.5 ? "shoot" : "long shot";
      logEvent(
        currentTime,
        "block",
        `${player.name} blocked a ${shootType} from ${
          getRandPlayerFromTeam(action, opposingTeam)["name"]
        }.`
      );
      player.score = Math.min(player.score + lowPlayerScore, 10);
      redraw();
      break;

    case "shoot":
    case "longShot":
    case "freeKick":
    case "chipShot":
    case "header":
      const scored = attemptOutcome(
        action,
        player,
        team === team1 ? team2 : team1
      );
      const assistPlayer = getRandPlayerFromTeam(action, team, player);
      const goalkeeper = getRandPlayerFromTeam("save", opposingTeam);
      const actionName = action
        .replace(/([a-z])([A-Z])/g, "$1 $2")
        .toLowerCase();
      if (action !== "freeKick" && Math.random() < 0.3) {
        if (["shoot", "longShot"].includes(action)) {
          logEvent(
            currentTime - randTime,
            "cutInside",
            `${player.name} cut inside from the wing.`
          );
        } else {
          logEvent(
            currentTime - randTime,
            "cross",
            `${assistPlayer.name} delivered a cross into the box.`
          );
        }
      }
      if (scored) {
        logEvent(
          currentTime - randTime,
          "assist",
          `${assistPlayer.name} assists the ball to ${player.name}.`
        );
        assistPlayer.score = Math.min(
          assistPlayer.score + mediumPlayerScore,
          10
        );
        logEvent(
          currentTime,
          "goal",
          `GOAL! ${player.name} scores for ${team.name} from a ${actionName}.`
        );
        player.score = Math.min(player.score + highPlayerScore, 10);
        goalkeeper.score = Math.max(goalkeeper.score - mediumPlayerScore, 1);
        team.score++;
      } else {
        logEvent(
          currentTime,
          "miss-goal",
          `${player.name} do a ${actionName} but misses.`
        );
        player.score = Math.min(player.score + lowPlayerScore, 10);
        if (Math.random() < 0.3) {
          goalkeeper.score = Math.min(goalkeeper.score + mediumPlayerScore, 10);
          const goalkeeperActions = ["save", "catch", "punch"];
          const gkAction =
            goalkeeperActions[
              Math.floor(Math.random() * goalkeeperActions.length)
            ];
          let gkActionName = `${goalkeeper.name} made a save.`;
          if (gkAction == "catch") {
            gkActionName = `${goalkeeper.name} caught the ball.`;
          }
          if (gkAction == "punch") {
            gkActionName = `${goalkeeper.name} punched the ball away from danger.`;
          }

          logEvent(currentTime, "save", gkActionName);
        }
      }
      redraw();
      break;

    case "foul":
      logEvent(currentTime, "foul", `${player.name} commits a foul.`);
      player.score = Math.max(player.score - lowPlayerScore, 1);
      redraw();
      break;

    case "clearance":
      logEvent(
        currentTime,
        "clearance",
        `${player.name} cleared the ball from danger.`
      );
      player.score = Math.min(player.score + mediumPlayerScore, 10);
      redraw();
      break;

    case "dribble":
      logEvent(
        currentTime,
        "dribble",
        `${player.name} dribbled past opponents with the ball and continued to move forward.`
      );
      player.score = Math.min(player.score + lowPlayerScore, 10);
      redraw();
      break;

    case "intercept":
      logEvent(
        currentTime,
        "intercept",
        `${player.name} intercepted the ball from ${
          getRandPlayerFromTeam(action, opposingTeam)["name"]
        }.`
      );
      player.score = Math.min(player.score + mediumPlayerScore, 10);
      redraw();
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
      redraw();
      break;

    case "offside":
      logEvent(currentTime, "offside", `${player.name} was caught offside.`);
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
        player.score = Math.min(player.score + mediumPlayerScore, 10);
        goalkeeper.score = Math.max(goalkeeper.score - mediumPlayerScore, 1);
        redraw();
      } else {
        logEvent(currentTime, "penalty", `${player.name} took a penalty kick.`);
        logEvent(
          currentTime + randTime,
          "penaltySave",
          `${
            getRandPlayerFromTeam("save", team === team1 ? team2 : team1)[
              "name"
            ]
          } saved a penalty kick.`
        );
        goalkeeper.score = Math.min(goalkeeper.score + highPlayerScore, 10);
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
        opposingTeam.score++;
        player.score -= Math.random() * (2 - 1) + 1;
        player.score = Math.max(player.score, 1);
        redraw();
      }
      break;

    default:
      // logEvent(currentTime, "", `Unknown action.`);
      break;
  }

  team1score.innerText = team1.score;
  team2score.innerText = team2.score;
}

function attemptOutcome(action, attacker, defendingTeam) {
  const goalkeeper = defendingTeam.players.find(
    (p) => p.position_in_match === "GK"
  );
  // Attacker attributes
  const { finishing, heading, free_kick_accuracy, marking, long_shots } =
    attacker.attributes.technical;
  const { composure, flair, off_the_ball } = attacker.attributes.mental;
  const { strength, jumping_reach } = attacker.attributes.physical;

  // Goalkeeper attributes
  let decision, concentration, handling;
  if (goalkeeper) {
    decision = goalkeeper.attributes.mental.decision;
    concentration = goalkeeper.attributes.mental.concentration;
    handling = goalkeeper.attributes.mental.technical;
  } else {
    decision = 44;
    concentration = 44;
    handling = 44;
  }

  let baseChance = 0;
  let positioningBonus = 0;
  // Goalkeeper's impact on the shot's success
  let goalkeeperImpact =
    decision * 0.5 + // Goalkeeper's decision-making
    concentration * 0.5; // Focus during the shot

  if (action === "chipShot") {
    // Base chance calculation for the chip shot
    baseChance =
      composure * 0.3 + // Importance of staying calm
      finishing * 0.4 + // Key attribute for scoring
      heading * 0.1 + // Less relevant for a chip shot
      flair * 0.2; // Creativity in execution

    // Positioning bonus based on off-the-ball movement
    positioningBonus = off_the_ball * 0.2;
  }
  if (action === "shoot") {
    // Base chance calculation for a regular shot
    baseChance =
      composure * 0.3 + // Importance of staying calm under pressure
      finishing * 0.5 + // Key attribute for scoring
      flair * 0.1; // Creativity in shot execution

    // Positioning bonus based on off-the-ball movement
    positioningBonus = off_the_ball * 0.2;
  }
  if (action === "penalty") {
    // Base chance calculation for a penalty shot
    baseChance =
      composure * 0.4 + // High importance of staying calm under pressure
      finishing * 0.5 + // Key attribute for scoring from a penalty
      free_kick_accuracy * 0.1 + // Specific skill for penalty kicks
      flair * 0.1; // Creativity in shot execution

    // Positioning bonus based on off-the-ball movement
    positioningBonus = off_the_ball * 0.1;
  }
  if (action === "ownGoal") {
    // Base chance calculation for an own goal
    baseChance =
      decision * -0.3 + // Poor decision-making increases the chance
      composure * -0.4 + // Low composure increases likelihood
      concentration * -0.2 + // Lack of focus contributes to own goals
      marking * 0.2; // Good marking can reduce the likelihood

    // Pressure factor based on situational randomness
    const pressureFactor = Math.random() * 10; // Adds variability
    // Positioning bonus based on defensive positioning
    positioningBonus = pressureFactor * -0.1; // Good positioning reduces own goal likelihood
  }
  if (action === "longShot") {
    // Base chance calculation for a long shot
    baseChance =
      long_shots * 0.5 + // Key skill for executing long-range shots
      finishing * 0.3 + // General scoring ability
      composure * 0.2 + // Staying calm under pressure
      flair * 0.1; // Creativity in shot execution

    // Positioning bonus based on off-the-ball movement for finding space
    positioningBonus = off_the_ball * 0.1;
    baseChance += positioningBonus;

    // Goalkeeper's impact for a long shot
    goalkeeperImpact =
      marking * 0.4 + // Positioning helps block long shots
      concentration * 0.3 + // Staying focused during the attempt
      handling * 0.3; // Ability to save long-range efforts

    // Final chance calculation, ensuring it's non-negative
    baseChance = Math.max(0, baseChance - goalkeeperImpact);
  }
  if (action === "freeKick") {
    // Base chance calculation for a free kick
    baseChance =
      free_kick_accuracy * 0.5 + // Key skill for free kick precision
      finishing * 0.2 + // General scoring ability
      composure * 0.2 + // Staying calm under pressure
      flair * 0.1; // Creativity for bending the shot

    // Positioning bonus based on off-the-ball movement
    positioningBonus = off_the_ball * 0.1;
    baseChance += positioningBonus;

    const wallPlayers = defendingTeam.players
      .filter((p) => p.position_in_match !== "GK")
      .sort(() => Math.random() - 0.5)
      .slice(0, 4)
      .map((player) => {
        const { heading, marking } = player.attributes.technical;
        const { jumping_reach } = player.attributes.physical;
        return { heading, marking, jumping_reach };
      });

    // Calculate wall effectiveness
    const wallEffectiveness =
      wallPlayers.reduce((total, player) => {
        return (
          total +
          player.heading * 0.2 + // Heading ability to block high shots
          player.marking * 0.3 + // Defensive awareness in the wall
          player.jumping_reach * 0.5
        ); // Key attribute for aerial deflection
      }, 0) / wallPlayers.length; // Average wall effectiveness

    // Goalkeeper's impact on the free kick
    const goalkeeperImpact =
      decision * 0.4 + // Positioning to save the shot
      concentration * 0.3 + // Staying focused during the kick
      handling * 0.2; // Catching or controlling the ball

    // Total defensive impact combines wall and goalkeeper
    const totalDefensiveImpact = wallEffectiveness * 0.5 + goalkeeperImpact;

    // Final chance calculation, ensuring it's non-negative
    baseChance = Math.max(0, baseChance - totalDefensiveImpact);
  }
  if (action === "header") {
    // Base chance calculation for a header
    baseChance =
      heading * 0.5 + // Key attribute for header accuracy
      jumping_reach * 0.3 + // Ability to reach aerial balls
      strength * 0.1 + // Helps in physical duels
      marking * 0.1; // Finding the right spot for a header

    // Positioning bonus based on off-the-ball movement
    positioningBonus = off_the_ball * 0.1;
    baseChance += positioningBonus;

    // Defensive players contesting the header
    const defensivePlayers = defendingTeam.players
      .filter((p) => p.position_in_match !== "GK") // Exclude the goalkeeper
      .sort(() => Math.random() - 0.5) // Randomize selection
      .slice(0, 2) // Select 2 players for aerial duel
      .map((player) => {
        const { heading, marking } = player.attributes.technical;
        const { jumping_reach, strength } = player.attributes.physical;
        return { heading, marking, jumping_reach, strength };
      });

    // Calculate defensive effectiveness in aerial duel
    const defensiveEffectiveness =
      defensivePlayers.reduce((total, player) => {
        return (
          total +
          player.heading * 0.4 + // Ability to win aerial duels
          player.marking * 0.3 + // Defensive awareness
          player.jumping_reach * 0.2 + // Aerial ability
          player.strength * 0.1
        ); // Physical dominance
      }, 0) / defensivePlayers.length;

    // Total defensive impact combines defenders and goalkeeper
    const totalDefensiveImpact =
      defensiveEffectiveness * 0.6 + goalkeeperImpact * 0.4;

    // Final chance calculation, ensuring it's non-negative
    baseChance = Math.max(0, baseChance - totalDefensiveImpact);
  }

  baseChance += positioningBonus;
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
simulateMatch(teamsInMatch);
