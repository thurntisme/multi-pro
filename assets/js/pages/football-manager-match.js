const teamsInMatch = groupTeams.map((team, teamIdx) => {
    const playerColor = teamIdx === 0 ? homeTeamColor : awayTeamColor;
    const players = generateFormation(team.formation).map((pos, idx) => {
        return {
            teamIdx,
            position_in_match: pos.posName,
            score: 5,
            playerColor,
            ...team.players[idx],
        };
    });
    const bench = team.bench.map((player) => {
        return { ...player, position_in_match: player.best_position, playerColor, teamIdx, score: 5 }
    });
    return { ...team, players, bench };
});

const redraw = () => {
    renderTeamInFitch(teamsInMatch, { circleRadius: 8, isDisplayScore: true, isDisplayName: true, isTeamInMatch: true });
};
redraw();

// Define possible follow-up actions for players
const playerActions = {
    // Starting play by distributing or launching a long ball forward.
    goal_kick: ["distribute_ball", "long_ball"], // Distribute to teammates or attempt a long ball.
    // After saving a shot, a goalkeeper can control the ball to distribute or clear danger.
    save: ["catch_cross", "punch", "distribute_ball"], // Prepare for crosses, punch away, or start distribution.
    // Catching a cross typically leads to transitioning play or clearing the ball quickly.
    catch_cross: ["distribute_ball", "long_ball"], // Distribute the ball or send it long.
    // Punching the ball clears immediate danger, often followed by clearance or a long ball.
    punch: ["clearance", "long_ball"], // Clear the ball or send it long after punching.
    // A clearance removes immediate danger; logical follow-ups are passing or a long pass to teammates.
    clearance: ["pass", "long_pass"], // Retain possession or transition quickly via long pass.
    // Distributing the ball leads to advancing play through dribbling or passing.
    distribute_ball: ["dribble", "pass", "long_pass"], // Move the ball forward via teammates or a long pass.
    // Interceptions can quickly transition into attack or controlled possession.
    intercept: ["pass", "dribble"], // Gain possession and look for teammates or space to dribble.
    // Tackling leads to an opportunity to build play by passing or dribbling.
    tackle: ["pass", "dribble"], // Retain possession and progress play.
    // Overlapping players often aim to cross the ball or cut inside for an attack.
    overlap: ["cross", "cut_inside"], // Create attacking opportunities by crossing or moving inside.
    // Crossing aims to set up attacking chances like headers, volleys, or tap-ins.
    cross: ["header", "volley", "tap_in"], // Deliver the ball into dangerous areas for finishes.
    // Cutting inside creates shooting or passing opportunities.
    cut_inside: ["shoot", "pass", "lay_off"], // Create chances through shots or combinations.
    // Long balls look for advanced teammates who can head or control the ball.
    long_ball: ["header", "control", "pass"], // Target advanced players for progression.
    // Blocking a shot is defensive; clearing or sending a long pass follows logically.
    block_shot: ["clearance", "long_pass"], // Defuse danger by clearing or transitioning.
    // Heading the ball often leads to passing or attempting a shot, depending on position.
    header: ["pass", "clearance", "shoot"], // Retain or transition possession, or attempt a goal.
    // Marking involves defensive outcomes like interceptions or blocking shots.
    mark: ["intercept", "block_shot"], // Prevent the opponent from advancing.
    // Dribbling creates opportunities to attack or find teammates.
    dribble: ["cut_inside", "pass", "shoot"], // Beat opponents or create chances.
    // Passing opens up opportunities to continue attacking or distribute the ball long.
    pass: ["overlap", "cross", "shoot", "long_pass"], // Combine with teammates for attacks.
    // Long shots aim to score but can lead to rebounds or pressing defenders.
    long_shot: ["rebound", "shoot", "press_defender"], // Capitalize on deflections or defensive errors.
    // Skill moves help beat opponents and create attacking opportunities.
    skill_move: ["dribble", "pass", "shoot"], // Gain space or break through defenders.
    // Shielding the ball helps retain possession and set up teammates.
    shield_ball: ["pass", "lay_off", "dribble"], // Protect the ball and look for options.
    // Switching play opens space on the opposite side of the pitch.
    switch_play: ["cross", "pass"], // Exploit open areas or set up crosses.
    // Through balls aim to set up scoring or attacking runs.
    through_ball: ["shoot", "dribble"], // Create scoring chances or progress play.
    // Shooting leads to potential rebounds or pressing defenders for loose balls.
    shoot: ["rebound", "press_defender"], // Follow up missed shots or apply pressure.
    // Lay-offs set up teammates for shots or continued play.
    lay_off: ["shoot", "pass", "dribble"], // Support teammates with short, controlled passes.
    // Pressing defenders aims to regain possession through tackles or interceptions.
    press_defender: ["tackle", "intercept"], // Win the ball back quickly.
    // Holding up play helps create space for teammates to join the attack.
    hold_up_play: ["lay_off", "pass"], // Retain possession and set up teammates.
    // Running in behind aims to exploit defensive gaps for scoring or crossing.
    run_in_behind: ["shoot", "cross"], // Take advantage of space behind defenders.
    // Tap-ins follow up on close-range opportunities, with rebounds or pressing defenders as logical next steps.
    tap_in: ["rebound", "press_defender"] // Capitalize on close chances or press after misses.
};

// Define possible opponent reactions
const opponentReactions = {
    // Opponents press to regain possession or tightly mark strikers during a goal kick.
    goal_kick: ["press", "mark_strikers"], // Apply pressure to the defense or mark attackers to disrupt distribution.
    // After a save, opponents pressure the goalkeeper or mark attacking players to limit options.
    save: ["pressure_goalkeeper", "mark_players"], // Prevent quick distribution or cover potential threats.
    // When the goalkeeper catches a cross, opponents can challenge or maintain defensive positioning.
    catch_cross: ["challenge_goalkeeper", "mark"], // Contest the catch or mark nearby players.
    // Following a punch, opponents aim to recover loose balls or block the clearance.
    punch: ["collect_loose_ball", "block_clearance"], // Quickly react to second balls or stop the follow-up clearance.
    // After a clearance, opponents focus on blocking passes or regaining possession.
    clearance: ["block", "recover_ball"], // Prevent progression or regain control.
    // When the ball is distributed, opponents press receivers or intercept the pass.
    distribute_ball: ["press_receiver", "intercept"], // Apply pressure or cut off passing lanes.
    // After an interception, opponents either launch a counter-attack or retain possession.
    intercept: ["counter_attack", "pass"], // Transition quickly or build possession.
    // Tackles are followed by attempts to secure possession or start a counter-attack.
    tackle: ["recover_ball", "counter_attack"], // Regain control and exploit the turnover.
    // Opponents track overlapping runs or attempt to cut out the resulting cross.
    overlap: ["track_runner", "intercept_cross"], // Follow overlapping players or intercept deliveries.
    // Crosses are countered by blocking or clearing the ball to safety.
    cross: ["block_cross", "clearance"], // Prevent the cross or clear the ball from danger.
    // Cutting inside is met with defensive reactions to block a shot or make a tackle.
    cut_inside: ["block_shot", "tackle"], // Prevent progression towards goal or block shooting opportunities.
    // Long balls trigger aerial challenges or interceptions by opponents.
    long_ball: ["challenge_header", "intercept"], // Compete for the ball or cut off its trajectory.
    // After blocking a shot, opponents aim to recover possession or counter-attack.
    block_shot: ["recover_ball", "counter_attack"], // Clear danger and transition quickly.
    // Headers are contested by opponents or followed by attempts to block progression.
    header: ["challenge_header", "block"], // Compete in the air or obstruct follow-up actions.
    // Marking involves tracking runners or intercepting passes intended for marked players.
    mark: ["track_runner", "intercept"], // Follow attacking players or cut off passes.
    // Dribbling is countered by tackles or blocking the dribbler’s progress.
    dribble: ["tackle", "block"], // Stop the dribbler by challenging or obstructing them.
    // Passing is met with interception attempts or pressure on the receiver.
    pass: ["intercept", "press_receiver"], // Anticipate the pass or press its recipient.
    // Long shots prompt opponents to block or prepare for saves.
    long_shot: ["block_shot", "save"], // Prevent the shot from reaching goal or rely on the goalkeeper.
    // Skill moves are countered by tackles or containment strategies.
    skill_move: ["tackle", "contain"], // Challenge the player or deny space.
    // Shielding the ball is met with pressure or tackling to regain possession.
    shield_ball: ["tackle", "press"], // Challenge the ball carrier or force mistakes.
    // Switching play causes opponents to shift their defensive line or intercept the pass.
    switch_play: ["shift_defensive_line", "intercept"], // Realign defense or cut off the switch.
    // Through balls prompt interception attempts or blocking the resulting shot.
    through_ball: ["intercept", "block_shot"], // Stop the pass or defend against the attack.
    // Shooting is countered with blocks or goalkeeper saves.
    shoot: ["block", "save"], // Defend the shot or rely on the goalkeeper.
    // Lay-offs are met with interception attempts or immediate pressure.
    lay_off: ["intercept", "press"], // Cut off the pass or apply pressure.
    // Pressing defenders leads to dribbling away or a quick pass to avoid pressure.
    press_defender: ["dribble_away", "pass"], // Evade pressure or find a teammate.
    // Holding up play is countered by tackles or pressing the ball carrier.
    hold_up_play: ["tackle", "press"], // Regain possession or disrupt the player.
    // Runs in behind trigger defensive tracking or attempts to block the pass.
    run_in_behind: ["track_run", "block_pass"], // Prevent the run or cut off the supply
    // Tap-ins are defended by blocking the shot or making a save.
    tap_in: ["block_shot", "save"] // Stop the close-range attempt or rely on the goalkeeper.
};

// Define valid actions for each position
const validActionsByPosition = {
    GK: ["goal_kick", "save", "catch_cross", "punch", "clearance", "distribute_ball", "pressure_goalkeeper", "mark_players", "block_shot"],
    LB: ["intercept", "tackle", "overlap", "cross", "cut_inside", "long_ball", "track_runner", "intercept_cross", "contain", "block_shot", "press_receiver"],
    CB: ["clearance", "intercept", "tackle", "block_shot", "header", "mark", "challenge_header", "recover_ball", "contain", "press_receiver"],
    RB: ["intercept", "tackle", "overlap", "cross", "cut_inside", "long_ball", "track_runner", "intercept_cross", "contain", "block_shot", "press_receiver"],
    LM: ["cross", "dribble", "cut_inside", "pass", "long_shot", "skill_move", "block_cross", "press_receiver"],
    CDM: ["intercept", "tackle", "pass", "long_ball", "shield_ball", "switch_play", "shift_defensive_line", "contain", "block_shot", "press_receiver"],
    CM: ["pass", "dribble", "long_shot", "through_ball", "tackle", "intercept", "counter_attack", "contain", "block_shot", "press_receiver"],
    CAM: ["dribble", "pass", "through_ball", "shoot", "cut_inside", "skill_move", "block_shot", "press_receiver"],
    RM: ["cross", "dribble", "cut_inside", "pass", "long_shot", "skill_move", "block_cross", "press_receiver"],
    LW: ["cross", "dribble", "cut_inside", "pass", "shoot", "skill_move", "block_shot"],
    CF: ["shoot", "lay_off", "pass", "press_defender", "header", "dribble", "hold_up_play"],
    ST: ["shoot", "header", "hold_up_play", "press_defender", "run_in_behind", "tap_in", "block_shot", "press_receiver"],
    RW: ["cross", "dribble", "cut_inside", "pass", "shoot", "skill_move", "block_shot"],
};

function simulateMatch(teamsInMatch) {
    const team1 = teamsInMatch[0];
    const team2 = teamsInMatch[1];
    const matchTime = 90 * 60; // Total match duration in minutes
    const maxHalfTime = 45 * 60; // Total match duration in minutes
    const maxExtraTime = 10; // Maximum possible extra time in minutes
    let currentTime = 0;
    let currentTimeInSeconds = 0;
    let matchTimeInSeconds = 0;
    let halfTimePassed = false;
    let extraTimePassed = false;
    let matchOver = false;

    // Random extra time
    const extraHalfTime = Math.floor(Math.random() * maxExtraTime) * 60;
    const extraTime = Math.floor(Math.random() * maxExtraTime) * 60;

    // Random half-time duration (between 45 and 55 minutes)
    const halfTimeDuration = maxHalfTime + extraHalfTime; // Half-time between 45 and 55 minutes

    let prevAction = null;
    let prevPlayer = null;

    const matchInterval = setInterval(() => {
        document.getElementById("minute").innerText =
            formatMatchTime(currentTimeInSeconds)["minute"];
        document.getElementById("second").innerText =
            formatMatchTime(currentTimeInSeconds)["second"];

        if (currentTimeInSeconds === maxHalfTime && extraHalfTime > 0) {
            logEvent(
                currentTimeInSeconds,
                "extra-time", '',
                `Extra time of ${extraHalfTime / 60} minutes begins!`
            );
        }

        if (currentTimeInSeconds >= halfTimeDuration && !halfTimePassed) {
            // Notify half-time
            logEvent(
                currentTimeInSeconds,
                "end", '',
                `Half-time: ${team1.name} ${team1.score} - ${team2.score} ${team2.name}`
            );
            halfTimePassed = true;
            currentTimeInSeconds = currentTime = 45 * 60; // Set the second half to start at minute 45
            logEvent(currentTimeInSeconds, "start", '', "Second half begins!");
        }

        if (currentTimeInSeconds >= matchTime && !extraTimePassed) {
            // After second half, random extra time begins
            logEvent(
                currentTimeInSeconds,
                "extra-time", '',
                `Extra time of ${extraTime / 60} minutes begins!`
            );
            extraTimePassed = true;
        }

        if (currentTimeInSeconds >= matchTime + extraTime && !matchOver) {
            // Notify end of match after extra time
            clearInterval(matchInterval);
            logEvent(
                currentTimeInSeconds,
                "end", '',
                `Match Over! Final Score: ${team1.name} ${team1.score} - ${team2.score} ${team2.name}`
            );
            $("#btn-accept-match").removeAttr("disabled");
            $(document).on("click", function () {
                const result = teamsInMatch.map(team => {
                    return {
                        name: team.name,
                        score: team.score,
                        players: [...team.players, ...team.bench].map(player => {
                            return {
                                name: player.name,
                                position: player.position_in_match,
                                score: player.score.toFixed(1)
                            }
                        })
                    }
                });
                console.log(result);
            });
            return;
        }

        if (currentTimeInSeconds === currentTime) {
            currentTime += Math.floor(Math.random() * 60) + 30;
            if (!currentTimeInSeconds) {
                logEvent(currentTimeInSeconds, "start", '', "Match start");
            } else {
                let action = null;
                let player = null;
                if (!prevAction) {
                    // Simulate an action
                    const team = Math.random() < 0.5 ? team1 : team2;
                    player =
                        team.players[Math.floor(Math.random() * team.players.length)];
                    const randAction = getActionFromPlayer(player, currentTimeInSeconds);
                    prevAction = randAction;
                    action = randAction;
                    prevPlayer = player;
                } else {
                    const nextAction = performNextAction(prevAction, prevPlayer);
                    action = nextAction.action;
                    prevAction = action;
                    if (nextAction.player) {
                        player = nextAction.player;
                    }
                }
                if (player) {
                    const outcomeAction = performOutcomeAction(action, player);
                    simulatePlayerAction(outcomeAction, player, currentTimeInSeconds);
                }
            }
        }
        if (matchTimeInSeconds%(15) === 0) {
            currentTimeInSeconds++;
        }
        // Increment the total time
        matchTimeInSeconds++;
    }, 10); // Delay of 1 second per iteration
}

function getActionFromPlayer(player, currentTimeInSeconds) {
    const { position_in_match } = player;

    // Select possible actions based on the player's position
    let actions = validActionsByPosition[position_in_match];
    
    // Randomly select an action from the available actions
    const randAction = Math.random();
    let action = actions[Math.floor(randAction * actions.length)];
    
    // Determine if a substitution is possible (after 45 minutes, for example)
    const isPossibleSub = currentTimeInSeconds > 45 * 60;
    
    // Check if the randomly chosen action should be a special event (injury, substitute, or foul)
    if (randAction < 0.2) { // 20% chance for special actions
        if (randAction < 0.01) {  // 1% chance for injury
            action = "injury";
        } else if (randAction < 0.1 && isPossibleSub) {  // 9% chance for substitute (only after 45 mins)
            action = "substitute";
        } else {  // 10% chance for foul
            action = "foul";
        }
    }

    // Possible extensions based on additional factors:
    // - Adjust based on fatigue, injury recovery, tactical changes, etc.
    
    // Return the chosen action
    return action;
}

// Function to perform an action and simulate opponent reaction
function performNextAction(currentAction, prevPlayer) {
    // Check if opponent can react to this action
    const possibleReactions = opponentReactions[currentAction];
    const possibleFollowUps = playerActions[currentAction];

    if (possibleReactions) {
        // Simulate a random chance for opponent reaction
        const opponentChance = Math.random(); // 0 to 1

        if (opponentChance < 0.5) {
            // Opponent reacts
            const reactionIndex = Math.floor(Math.random() * possibleReactions.length);
            const opponentAction = possibleReactions[reactionIndex];
            const positionsWithAction = Object.keys(validActionsByPosition).filter(position =>
                validActionsByPosition[position].includes(opponentAction)
            );
            const randomPosition = positionsWithAction[Math.floor(Math.random() * positionsWithAction.length)];
            const opponentPlayers = teamsInMatch[prevPlayer.teamIdx === 0 ? 1 : 0].players.filter(p => p.position_in_match === randomPosition);
            const opponentPlayer = opponentPlayers[Math.floor(Math.random() * opponentPlayers.length)];

            return { player: opponentPlayer, action: opponentAction };
        }
    }

    // If no opponent reaction, continue with the player's follow-up action
    if (possibleFollowUps) {
        const followUpIndex = Math.floor(Math.random() * possibleFollowUps.length);
        const nextAction = possibleFollowUps[followUpIndex];
        const positionsWithAction = Object.keys(validActionsByPosition).filter(position =>
            validActionsByPosition[position].includes(nextAction)
        );
        const randomPosition = positionsWithAction[Math.floor(Math.random() * positionsWithAction.length)];
        const teamPlayers = teamsInMatch[prevPlayer.teamIdx].players.filter(p => p.position_in_match === randomPosition);
        const teamPlayer = teamPlayers[Math.floor(Math.random() * teamPlayers.length)];
        return { player: teamPlayer, action: nextAction };
    } else {
        return { player: null, action: null };
    }
}

// Convert time to mm:ss format
function formatMatchTime(time) {
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
    let players = team.players;
    let condition = true;
    if (action === "exit") {
        players = team.bench;
    }
    const filterPlayers = players.filter((p) => {
        if (action === "save") {
            condition = ["GK"].includes(p.position_in_match);
        } else {
            condition = !["GK"].includes(p.position_in_match);
        }
        if (player) {
            return condition && (p.uuid !== player.uuid);
        }
        if (action === "block") {
            return condition && (p.position_in_match !== "GK" || p.position_in_match !== "CB");
        }
        if (action === "intercept") {
            return condition && (p.position_in_match !== "GK" || p.position_in_match !== "CB");
        }
        if (action === "exit") {
            return condition && !p.isExit;
        }
        return condition;
    });
    return filterPlayers[Math.floor(Math.random() * filterPlayers.length)];
}

function performOutcomeAction(action, player) {
    let outcome;

    switch (action) {
        case "shoot":
            outcome = getShootOutcome(player);
            break;
        case "long_shot":
            outcome = getLongShotOutcome(player);
            break;
        case "save":
            outcome = getSaveOutcome(player);
            break;
        case "catch_cross":
            outcome = getCatchCrossOutcome(player);
            break;
        case "pass":
            outcome = getPassOutcome(player);
            break;
        case "long_pass":
            outcome = getLongPassOutcome(player);
            break;
        case "dribble":
            outcome = getDribbleOutcome(player);
            break;
        case "intercept":
            outcome = getInterceptOutcome(player);
            break;
        case "tackle":
            outcome = getTackleOutcome(player);
            break;
        case "cut_inside":
            outcome = getCutInsideOutcome(player);
            break;
        case "volley":
            outcome = getVolleyOutcome(player);
            break;
        case "tap_in":
            outcome = getTapInOutcome(player);
            break;
        case "foul":
            outcome = getFoulOutcome(player);
            break;
        case "recover_ball":
            outcome = getRecoverBallOutcome(player);
            break;
        case "block_cross":
            outcome = getBlockCrossOutcome(player);
            break;
        case "intercept_cross":
            outcome = getInterceptCrossOutcome(player);
            break;
        case "challenge_header":
            outcome = getChallengeHeaderOutcome(player);
            break;
        case "injury":
            outcome = getInjuryOutcome(player);
            break;
        case "substitute":
            outcome = getSubstituteOutcome(player);
            break;
        default:
            return action; 
    }

    return `${action}_${outcome}`;
}

function getShootOutcome(player) {
    let chance = Math.random();  // Random chance for simplicity
    if (chance < 0.2) return "goal";    // 20% chance of scoring
    // else if (chance < 0.4) return "save";    // 20% chance of a save
    else if (chance < 0.7) return "miss";    // 30% chance of missing
    else return "blocked";    // 30% chance of being blocked
}
function getLongShotOutcome(player) {
    let chance = Math.random();  // Random chance for simplicity
    if (chance < 0.1) return "goal";    // 10% chance of scoring
    // else if (chance < 0.3) return "save";    // 20% chance of a save
    else if (chance < 0.6) return "miss";    // 30% chance of missing
    else return "blocked";    // 40% chance of being blocked
}
function getSaveOutcome(player) {
    let chance = Math.random();  // Random chance for simplicity
    if (chance < 0.5) return "success";    // 50% chance of saving the shot
    else return "failure";    // 50% chance of failing to save
}
function getCatchCrossOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.3) return "success"; // 30% chance of successful catch
    else if (chance < 0.6) return "fail"; // 30% chance of failure to catch
    else if (chance < 0.8) return "punch"; // 20% chance of punching the ball away
    else return "intercepted"; // 20% chance of the cross being intercepted by a defender
}
function getPassOutcome(player) {
    let chance = Math.random();  // Random chance for simplicity
    if (chance < 0.7) return "successful"; // 70% chance of a successful pass
    else if (chance < 0.85) return "intercepted"; // 15% chance of interception
    else if (chance < 0.95) return "blocked"; // 10% chance of being blocked
    else return "missed"; // 5% chance of the pass going out of bounds or being off-target
}
function getLongPassOutcome(player) {
    let chance = Math.random();  // Random chance for simplicity
    if (chance < 0.6) return "successful"; // 60% chance of successful long pass
    else if (chance < 0.8) return "intercepted"; // 20% chance of interception
    else if (chance < 0.9) return "blocked"; // 10% chance of being blocked
    else if (chance < 0.95) return "missed"; // 5% chance of going out of bounds
    else return "chipped"; // 5% chance of a successful chip over the defender
}
function getDribbleOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.5) return "successful"; // 50% chance of a successful dribble
    else if (chance < 0.75) return "tackled"; // 25% chance of being tackled
    else if (chance < 0.9) return "fouled"; // 15% chance of being fouled
    else return "lose_control"; // 10% chance of losing control
}
function getInterceptOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.5) return "successful"; // 50% chance of successful interception
    else if (chance < 0.75) return "missed"; // 25% chance of missing the interception
    else if (chance < 0.9) return "deflection"; // 15% chance of deflecting the ball
    else return "foul"; // 10% chance of committing a foul
}
function getTackleOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.5) return "successful"; // 50% chance of successful tackle
    else if (chance < 0.8) return "missed"; // 30% chance of missing the tackle
    else if (chance < 0.95) return "foul"; // 15% chance of committing a foul
    else return "deflected"; // 5% chance of deflecting the ball
}
function getCutInsideOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.5) return "successful"; // 50% chance of successfully cutting inside
    else if (chance < 0.75) return "blocked"; // 25% chance of being blocked
    else if (chance < 0.9) return "fouled"; // 15% chance of being fouled
    else return "lost_possession"; // 10% chance of losing possession
}
function getVolleyOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.3) return "goal"; // 30% chance of scoring a goal
    else if (chance < 0.5) return "saved"; // 20% chance of the volley being saved
    else if (chance < 0.7) return "missed"; // 20% chance of missing the target
    else if (chance < 0.85) return "blocked"; // 15% chance of being blocked by a defender
    else return "rebound"; // 15% chance of a rebound
}
function getTapInOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.7) return "goal"; // 70% chance of a successful tap-in
    else if (chance < 0.85) return "missed"; // 15% chance of missing the tap-in
    else if (chance < 0.95) return "saved"; // 10% chance of the goalkeeper making a save
    else return "blocked"; // 5% chance of being blocked by a defender
}
function getFoulOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.1) return "red_card"; // 10% chance of a red card (serious foul)
    else if (chance < 0.3) return "yellow_card"; // 20% chance of a yellow card (reckless foul)
    else if (chance < 0.5) return "penalty_kick"; // 20% chance of a penalty kick (foul in the box)
    else if (chance < 0.7) return "free_kick"; // 20% chance of a free kick (foul outside the box)
    else return "no_card"; // 30% chance of no card (minor foul)
}
function getRecoverBallOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.5) return "success"; // 50% chance of successfully recovering the ball
    else if (chance < 0.7) return "failed"; // 20% chance of failing to recover the ball
    else if (chance < 0.85) return "tackle"; // 15% chance of a tackle but not a clean recovery
    else if (chance < 0.95) return "clearance"; // 10% chance of a clearance under pressure
    else return "pressured"; // 5% chance of being pressured and losing the ball
}
function getBlockCrossOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.3) return "success"; // 30% chance of a successful block
    else if (chance < 0.5) return "deflected"; // 20% chance of a deflection into a dangerous area
    else if (chance < 0.7) return "cleared"; // 20% chance of a block that still needs a clearance
    else if (chance < 0.85) return "failed"; // 15% chance of failing to block the cross
    else if (chance < 0.95) return "offside"; // 10% chance of the attacking player being offside
    else return "handled"; // 5% chance of handling the ball during the block
}
function getInterceptCrossOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.4) return "success"; // 40% chance of a successful interception
    else if (chance < 0.6) return "deflected"; // 20% chance of a deflection
    else if (chance < 0.75) return "cleared"; // 15% chance of a clearance after interception
    else if (chance < 0.9) return "failed"; // 15% chance of failing to intercept
    else if (chance < 0.95) return "offside"; // 5% chance of the attacker being offside
    else return "handled"; // 5% chance of handball during the interception attempt
}
function getChallengeHeaderOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.4) return "success"; // 40% chance of successfully winning the header
    else if (chance < 0.6) return "failed"; // 20% chance of failing to win the header
    else if (chance < 0.75) return "foul"; // 15% chance of committing a foul during the challenge
    else if (chance < 0.85) return "deflected"; // 10% chance of a deflection off the header
    else if (chance < 0.95) return "cleared"; // 10% chance of winning the header but needing a clearance
    else return "offside"; // 5% chance of the attacker being offside when the ball is headed
}
function getInjuryOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.4) return "minor"; // 40% chance of a minor injury
    else if (chance < 0.6) return "serious"; // 20% chance of a serious injury
    else if (chance < 0.75) return "fake"; // 15% chance of faking an injury
    else if (chance < 0.85) return "temporary"; // 10% chance of a temporary injury
    else if (chance < 0.95) return "rehabilitation"; // 10% chance of requiring rehabilitation but continuing
    else return "stoppage"; // 5% chance of an injury stoppage
}
function getSubstituteOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.25) return "injury"; // 25% chance of substitution due to injury
    else if (chance < 0.5) return "tactical"; // 25% chance of tactical substitution
    else if (chance < 0.65) return "fatigue"; // 15% chance of fatigue substitution
    else if (chance < 0.8) return "strategic"; // 15% chance of strategic substitution
    else if (chance < 0.9) return "time_wasting"; // 10% chance of time-wasting substitution
    else if (chance < 0.95) return "performance"; // 5% chance of performance-based substitution
    else return "multiple"; // 5% chance of part of multiple substitutions
}

function simulatePlayerAction(action, player, currentTime) {
    switch (action) {
        case "goal_kick":
            logEvent(
                currentTime,
                "goal_kick",
                player,
                `${player.name} took a goal kick, sending the ball upfield to initiate an attack.`
            );
            break;
        case "distribute_ball":
            logEvent(
                currentTime,
                "distribute_ball",
                player,
                `${player.name} distributed the ball with precision, finding a teammate to start the attack.`
            );
            break;
        case "long_ball":
            logEvent(
                currentTime,
                "long_ball",
                player,
                `${player.name} launched a long ball upfield, aiming to bypass the opposition's defense.`
            );
            break;
        case "catch_cross":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} caught the cross with confidence, securing possession.`
            );
            break;
        case "catch_cross_success":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} caught the cross cleanly, ending the attack.`
            );
            break;
        case "catch_cross_fail":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} missed the cross! The ball is loose in the box.`
            );
            break;
        case "catch_cross_punch":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} punched the cross away, clearing the danger.`
            );
            break;
        case "catch_cross_intercepted":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name}'s cross was intercepted by a defender before the goalkeeper could react.`
            );
            break;
        case "punch":
            logEvent(
                currentTime,
                "punch",
                player,
                `${player.name} punched the ball away, clearing the danger from the box.`
            );
            break;
        case "clearance":
            logEvent(
                currentTime,
                "clearance",
                player,
                `${player.name} cleared the ball away from danger, ensuring no immediate threats.`
            );
            break;
        case "pass":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} made a well-timed pass, setting up a potential attack.`
            );
            break;
        case "pass_successful":
            logEvent(currentTime, action, player, `${player.name} made a brilliant pass to their teammate.`);
            break;
        case "pass_intercepted":
            logEvent(currentTime, action, player, `${player.name}'s pass was intercepted by the opposition.`);
            break;
        case "pass_blocked":
            logEvent(currentTime, action, player, `${player.name}'s pass was blocked by a defender.`);
            break;
        case "pass_missed":
            logEvent(currentTime, action, player, `${player.name}'s pass went out of bounds.`);
            break;
        case "long_pass":
            logEvent(
                currentTime,
                "long_pass",
                player,
                `${player.name} delivered a long pass, attempting to break the opposition's defensive line.`
            );
            break;
        case "long_pass_successful":
            logEvent(currentTime, action, player, `${player.name} made a successful long pass to their teammate.`);
            break;
        case "long_pass_intercepted":
            logEvent(currentTime, action, player, `${player.name}'s long pass was intercepted by the opposition.`);
            break;
        case "long_pass_blocked":
            logEvent(currentTime, action, player, `${player.name}'s long pass was blocked by a defender.`);
            break;
        case "long_pass_missed":
            logEvent(currentTime, action, player, `${player.name}'s long pass went out of bounds.`);
            break;
        case "long_pass_chipped":
            logEvent(currentTime, action, player, `${player.name} executed a brilliant chip pass over the defender.`);
            break;
        case "dribble":
            logEvent(
                currentTime,
                "dribble",
                player,
                `${player.name} skillfully dribbled past the defender, advancing the ball forward.`
            );
            break;
        case "dribble_successful":
            logEvent(currentTime, action, player, `${player.name} dribbled past the defender successfully.`);
            break;
        case "dribble_tackled":
            logEvent(currentTime, action, player, `${player.name} was tackled and lost the ball.`);
            break;
        case "dribble_fouled":
            logEvent(currentTime, action, player, `${player.name} was fouled while attempting a dribble.`);
            break;
        case "dribble_lose_control":
            logEvent(currentTime, action, player, `${player.name} lost control of the ball during the dribble.`);
            break;
        case "intercept":
            logEvent(
                currentTime,
                "intercept",
                player,
                `${player.name} intercepted the pass, regaining possession for the team.`
            );
            break;
        case "intercept_successful":
            logEvent(currentTime, action, player, `${player.name} successfully intercepted the ball and regained possession.`);
            break;
        case "intercept_missed":
            logEvent(currentTime, action, player, `${player.name} attempted to intercept but missed, allowing the ball to continue.`);
            break;
        case "intercept_deflection":
            logEvent(currentTime, action, player, `${player.name} got a touch on the ball, causing a deflection but no possession.`);
            break;
        case "intercept_foul":
            logEvent(currentTime, action, player, `${player.name} fouled the opponent while trying to intercept.`);
            break;
        case "tackle":
            logEvent(
                currentTime,
                "tackle",
                player,
                `${player.name} made a crucial tackle, winning the ball back for the team.`
            );
            break;
        case "tackle_successful":
            logEvent(currentTime, action, player, `${player.name} successfully tackled the opponent and regained possession.`);
            break;
        case "tackle_missed":
            logEvent(currentTime, action, player, `${player.name} attempted a tackle but missed, and the opponent retained possession.`);
            break;
        case "tackle_foul":
            logEvent(currentTime, action, player, `${player.name} committed a foul while attempting a tackle.`);
            break;
        case "tackle_deflected":
            logEvent(currentTime, action, player, `${player.name}'s tackle deflected the ball into a neutral area.`);
            break;
        case "overlap":
            // Handle overlap action
            logEvent(
                currentTime,
                "overlap",
                player,
                `${player.name} made an overlapping run, providing an option for the ball carrier.`
            );
            break;
        case "cross":
            // Handle cross action
            logEvent(
                currentTime,
                "cross",
                player,
                `${player.name} delivered a dangerous cross into the box, looking for a teammate.`
            );
            break;
        case "cut_inside":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} cut inside, looking for a shot or a pass to break the defense.`
            );
            break;
        case "cut_inside_successful":
            logEvent(currentTime, action, player, `${player.name} successfully cut inside, creating space for a pass or shot.`);
            break;
        case "cut_inside_blocked":
            logEvent(currentTime, action, player, `${player.name}'s attempt to cut inside was blocked by a defender.`);
            break;
        case "cut_inside_fouled":
            logEvent(currentTime, action, player, `${player.name} was fouled while attempting to cut inside.`);
            break;
        case "cut_inside_lost_possession":
            logEvent(currentTime, action, player, `${player.name} lost possession while trying to cut inside.`);
            break;
        case "header":
            logEvent(
                currentTime,
                "header",
                player,
                `${player.name} went for a header, challenging for the ball in the air.`
            );
            break;
        case "volley":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} took a powerful volley, attempting to score in a spectacular fashion.`
            );
            break;
        case "volley_goal":
            logEvent(currentTime, action, player, `${player.name} executed a stunning volley to score a spectacular goal!`);
            break;
        case "volley_saved":
            logEvent(currentTime, action, player, `${player.name} struck a volley on target, but the goalkeeper made an excellent save.`);
            break;
        case "volley_missed":
            logEvent(currentTime, action, player, `${player.name} attempted a volley but missed the target.`);
            break;
        case "volley_blocked":
            logEvent(currentTime, action, player, `${player.name}'s volley was blocked by a defender.`);
            break;
        case "volley_rebound":
            logEvent(currentTime, action, player, `${player.name}'s volley was deflected, and the ball is now in play as a rebound.`);
            break;
        case "tap_in":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} calmly tapped the ball into the net to finish off a great team move.`
            );
            break;
        case "tap_in_goal":
            logEvent(currentTime, action, player, `${player.name} easily taps the ball into the goal to score!`);
            break;
        case "tap_in_missed":
            logEvent(currentTime, action, player, `${player.name} missed the tap-in opportunity, sending the ball wide.`);
            break;
        case "tap_in_saved":
            logEvent(currentTime, action, player, `${player.name} attempted a tap-in, but the goalkeeper made a quick save.`);
            break;
        case "tap_in_blocked":
            logEvent(currentTime, action, player, `${player.name}'s tap-in attempt was blocked by a defender.`);
            break;
        case "tap_in_offside":
            logEvent(currentTime, action, player, `${player.name} was caught offside during the tap-in attempt.`);
            break;
        case "shoot":
            logEvent(
                currentTime,
                "shoot",
                player,
                `${player.name} took a shot at goal, trying to score from distance.`
            );
            break;
        case "shoot_goal":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} took a shot and scored a fantastic goal!`
            );
            break;
        case "shoot_save":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} took a shot, but the goalkeeper made a brilliant save!`
            );
            break;
        case "shoot_miss":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} took a shot, but it went wide of the goal.`
            );
            break;
        case "shoot_blocked":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name}'s shot was blocked by a defender, stopping a potential goal.`
            );
            break;
        case "lay_off":
            // Handle lay off action
            logEvent(
                currentTime,
                "lay_off",
                player,
                `${player.name} laid off the ball to a teammate, setting up a potential shot.`
            );
            break;
        case "control":
            // Handle control action
            logEvent(
                currentTime,
                "control",
                player,
                `${player.name} took control of the ball, settling it to prepare for the next move.`
            );
            break;
        case "block_shot":
            // Handle block shot action
            logEvent(
                currentTime,
                "block_shot",
                player,
                `${player.name} made a crucial block to stop the shot and keep the team in the game.`
            );
            break;
        case "mark":
            // Handle mark action
            logEvent(
                currentTime,
                "mark",
                player,
                `${player.name} marked the opposition closely, preventing them from receiving the ball.`
            );
            break;
        case "long_shot":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} took a powerful long shot, testing the goalkeeper from a distance.`
            );
            break;
        case "long_shot_goal":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} took a long shot and scored a stunning goal from distance!`
            );
            break;
        case "long_shot_save":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} took a long shot, but the goalkeeper made a remarkable save!`
            );
            break;
        case "long_shot_miss":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name}'s long shot missed the target and went over the crossbar.`
            );
            break;
        case "long_shot_blocked":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name}'s long shot was blocked by a defender, stopping a potential threat.`
            );
            break;
        case "rebound":
            // Handle rebound action
            logEvent(
                currentTime,
                "rebound",
                player,
                `${player.name} capitalized on the rebound, pouncing on the loose ball to score.`
            );
            break;
        case "press_defender":
            // Handle press defender action
            logEvent(
                currentTime,
                "press_defender",
                player,
                `${player.name} applied pressure on the defender, forcing a mistake or pass.`
            );
            break;
        case "skill_move":
            // Handle skill move action
            logEvent(
                currentTime,
                "skill_move",
                player,
                `${player.name} performed a skill move, dazzling the defender and advancing with the ball.`
            );
            break;
        case "shield_ball":
            // Handle shield ball action
            logEvent(
                currentTime,
                "shield_ball",
                player,
                `${player.name} shielded the ball from the defender, maintaining possession under pressure.`
            );
            break;
        case "switch_play":
            // Handle switch play action
            logEvent(
                currentTime,
                "switch_play",
                player,
                `${player.name} switched the play to the opposite side, looking for space and an attacking option.`
            );
            break;
        case "through_ball":
            // Handle through ball action
            logEvent(
                currentTime,
                "through_ball",
                player,
                `${player.name} played a perfect through ball, setting up a teammate for a clear shot on goal.`
            );
            break;
        case "hold_up_play":
            // Handle hold up play action
            logEvent(
                currentTime,
                "hold_up_play",
                player,
                `${player.name} held up the ball, waiting for support before making the next move.`
            );
            break;
        case "run_in_behind":
            // Handle run in behind action
            logEvent(
                currentTime,
                "run_in_behind",
                player,
                `${player.name} made a run in behind the defense, looking to receive a through ball or cross.`
            );
            break;
        case "counter_attack":
            // Handle counter attack action
            logEvent(
                currentTime,
                "counter_attack",
                player,
                `${player.name} launched a counter-attack, exploiting the space left by the opposition.`
            );
            break;
        case "foul":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} committed a foul, giving away a free kick to the opposition.`
            );
            break;
        case "foul_free_kick":
            logEvent(currentTime, action, player, `${player.name} committed a foul and the opposing team is awarded a free kick.`);
            break;
        case "foul_penalty_kick":
            logEvent(currentTime, action, player, `${player.name} committed a foul inside the penalty area, awarding a penalty kick.`);
            break;
        case "foul_yellow_card":
            logEvent(currentTime, action, player, `${player.name} committed a foul and received a yellow card.`);
            break;
        case "foul_red_card":
            logEvent(currentTime, action, player, `${player.name} committed a serious foul and received a red card, resulting in a sending off.`);
            break;
        case "foul_no_card":
            logEvent(currentTime, action, player, `${player.name} committed a foul, but the referee decided no card would be issued.`);
            break;
        case "recover_ball":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} won the ball back, recovering possession for the team.`
            );
            break;
        case "recover_ball_success":
            logEvent(currentTime, action, player, `${player.name} successfully recovered the ball and gained possession.`);
            break;
        case "recover_ball_failed":
            logEvent(currentTime, action, player, `${player.name} attempted to recover the ball but was unsuccessful.`);
            break;
        case "recover_ball_tackle":
            logEvent(currentTime, action, player, `${player.name} successfully tackled the opponent, but the ball remains contested.`);
            break;
        case "recover_ball_clearance":
            logEvent(currentTime, action, player, `${player.name} cleared the ball under pressure, but it was not a clean recovery.`);
            break;
        case "recover_ball_pressured":
            logEvent(currentTime, action, player, `${player.name} was pressured by the opponent and lost possession.`);
            break;
        case "save":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} made a crucial save, denying the opposition from scoring.`
            );
            break;
        case "save_success":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} made a great save, denying the striker a goal!`
            );
            break;
        case "save_failure":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} couldn't save the shot and the ball went into the net!`
            );
            break;
        case "contain":
            logEvent(
                currentTime,
                "contain",
                player,
                `${player.name} contained the attacker, preventing them from advancing further.`
            );
            break;
        case "substitute":
            // Handle substitute action
            logEvent(
                currentTime,
                "substitute",
                player,
                `${player.name} was substituted off, making way for a fresh player.`
            );
            break;
        case "press_receiver":
            // Handle press receiver action
            logEvent(
                currentTime,
                "press_receiver",
                player,
                `${player.name} pressed the ball receiver, putting pressure on them to make a mistake.`
            );
            break;
        case "track_runner":
            // Handle track runner action
            logEvent(
                currentTime,
                "track_runner",
                player,
                `${player.name} tracked the opposing runner, staying close to prevent a dangerous move.`
            );
            break;
        case "block_cross":
            // Handle block cross action
            logEvent(
                currentTime,
                "block_cross",
                player,
                `${player.name} blocked the cross, preventing any attacking opportunity from the wing.`
            );
            break;
        case "block_cross_success":
            logEvent(currentTime, action, player, `${player.name} successfully blocked the cross and denied the attacking team.`);
            break;
        case "block_cross_deflected":
            logEvent(currentTime, action, player, `${player.name} got a touch on the cross, but it deflected into a dangerous area.`);
            break;
        case "block_cross_cleared":
            logEvent(currentTime, action, player, `${player.name} blocked the cross, but the ball remains in play and needs to be cleared.`);
            break;
        case "block_cross_failed":
            logEvent(currentTime, action, player, `${player.name} missed the block, and the cross reached its intended target.`);
            break;
        case "block_cross_offside":
            logEvent(currentTime, action, player, `${player.name} blocked the cross, but the attacking player was offside.`);
            break;
        case "block_cross_handled":
            logEvent(currentTime, action, player, `${player.name} handled the ball while attempting to block the cross, resulting in a foul.`);
            break;
        case "shift_defensive_line":
            // Handle shift defensive line action
            logEvent(
                currentTime,
                "shift_defensive_line",
                player,
                `${player.name} shifted the defensive line, ensuring better coverage against the opposition's attack.`
            );
            break;
        case "intercept_cross":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} intercepted the cross, preventing any threat in the box.`
            );
            break;
        case "intercept_cross_success":
            logEvent(currentTime, action, player, `${player.name} successfully intercepted the cross and denied the attack.`);
            break;
        case "intercept_cross_deflected":
            logEvent(currentTime, action, player, `${player.name} got a touch on the cross, but it deflected into a dangerous area.`);
            break;
        case "intercept_cross_cleared":
            logEvent(currentTime, action, player, `${player.name} intercepted the cross, but the ball needs to be cleared.`);
            break;
        case "intercept_cross_failed":
            logEvent(currentTime, action, player, `${player.name} missed the interception, and the cross reached its target.`);
            break;
        case "intercept_cross_offside":
            logEvent(currentTime, action, player, `${player.name} intercepted the cross, but the attacking player was offside.`);
            break;
        case "intercept_cross_handled":
            logEvent(currentTime, action, player, `${player.name} handled the ball while attempting to intercept the cross, resulting in a foul.`);
            break;
        case "pressure_goalkeeper":
            // Handle pressure goalkeeper action
            logEvent(
                currentTime,
                "pressure_goalkeeper",
                player,
                `${player.name} pressured the goalkeeper, forcing them into a rushed clearance.`
            );
            break;
        case "challenge_header":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} challenged for the header, competing in the air for possession.`
            );
            break;
        case "challenge_header_success":
            logEvent(currentTime, action, player, `${player.name} successfully won the header and gained possession.`);
            break;
        case "challenge_header_failed":
            logEvent(currentTime, action, player, `${player.name} missed the header, and the opponent won possession.`);
            break;
        case "challenge_header_foul":
            logEvent(currentTime, action, player, `${player.name} committed a foul while challenging for the header.`);
            break;
        case "challenge_header_deflected":
            logEvent(currentTime, action, player, `${player.name} touched the ball, but it deflected into a dangerous area.`);
            break;
        case "challenge_header_cleared":
            logEvent(currentTime, action, player, `${player.name} won the header, but the ball was not controlled and needs to be cleared.`);
            break;
        case "challenge_header_offside":
            logEvent(currentTime, action, player, `${player.name} won the header, but the attacking player was offside.`);
            break;
        case "mark_players":
            // Handle mark players action
            logEvent(
                currentTime,
                "mark_players",
                player,
                `${player.name} marked the opposition players tightly, denying them space to receive the ball.`
            );
            break;
        case "injury_minor":
            logEvent(currentTime, action, player, `${player.name} sustained a minor injury but is able to continue playing.`);
            break;
        case "injury_serious":
            logEvent(currentTime, action, player, `${player.name} sustained a serious injury and is unable to continue the game.`);
            break;
        case "injury_fake":
            logEvent(currentTime, action, player, `${player.name} appeared injured but was faking it.`);
            break;
        case "injury_temporary":
            logEvent(currentTime, action, player, `${player.name} felt a knock but is continuing to play after a brief stop.`);
            break;
        case "injury_rehabilitation":
            logEvent(currentTime, action, player, `${player.name} has a minor injury and is receiving treatment off the field.`);
            break;
        case "injury_stoppage":
            logEvent(currentTime, action, player, `${player.name} is injured, and the match is temporarily stopped for treatment.`);
            break;
        case "injury_substitution":
            logEvent(currentTime, action, player, `${player.name} is forced to be substituted due to injury.`);
            break;                                                                
        case "substitute_injury":
            logEvent(currentTime, action, player, `${player.name} is substituted due to an injury.`);
            break;
        case "substitute_tactical":
            logEvent(currentTime, action, player, `${player.name} is substituted for tactical reasons.`);
            break;
        case "substitute_fatigue":
            logEvent(currentTime, action, player, `${player.name} is substituted due to fatigue.`);
            break;
        case "substitute_strategic":
            logEvent(currentTime, action, player, `${player.name} is substituted as part of a strategic move.`);
            break;
        case "substitute_time_wasting":
            logEvent(currentTime, action, player, `${player.name} is substituted to waste time.`);
            break;
        case "substitute_performance":
            logEvent(currentTime, action, player, `${player.name} is substituted due to poor performance.`);
            break;
        case "substitute_multiple":
            logEvent(currentTime, action, player, `${player.name} is part of a planned substitution.`);
            break;
        default:
            console.log(action, player, currentTime)
            break;
    }
}

function simulateAction(team, player, team1, team2, currentTime) {
    const randTime = Math.round(Math.random() * (10 - 1) + 1);

    const team1score = document.getElementById("team-1-score");
    const team2score = document.getElementById("team-2-score");

    const lowPlayerScore = Math.random(); // Range: 0 to 1.0
    const mediumPlayerScore = Math.random() * (2.0 - 1.0) + 1.0; // Range: 1.0 to 2.0
    const highPlayerScore = Math.random() * (2.5 - 2.0) + 2.0; // Range: 2.0 to 2.5
    const veryHighPlayerScore = Math.random() * (3.0 - 2.5) + 2.5; // Range: 2.5 to 3.0

    // Handle valid actions based on the player's position
    switch (action) {
        case "save":
            // Handle save for GK
            logEvent(currentTime, "save", player, `${player.name} made a crucial save to deny the opposition.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            redraw();
            break;

        case "tackle":
            // Handle tackle for LB / RB / CB
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "failed_tackle", player, `${player.name} failed to win the ball in a tackle, allowing the opponent to advance.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
            } else {
                logEvent(currentTime, "tackle", player, `${player.name} made a strong tackle to regain possession.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "clearance":
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "failed_clearance", player, `${player.name} made a poor clearance, putting the team in a dangerous position.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
            } else {
                logEvent(currentTime, "clearance", player, `${player.name} cleared the danger with a powerful clearance.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "goal_kick":
            // Handle goal kick for GK
            logEvent(currentTime, "goal_kick", player, `${player.name} took a goal kick to restart play.`);
            break;

        case "cross":
            // Handle crossing for LB, LM, RM, or any wide player
            logEvent(currentTime, "cross", player, `${player.name} whipped in a dangerous cross into the box.`);
            break;

        case "shoot":
            // Handle shooting for forwards or attacking midfielders
            const shootType = Math.random() < 0.5 ? "shoot" : "long shot";
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "shoot", player, `${player.name} took a ${shootType} at goal, aiming for the top corner.`);
                player.score = Math.min(player.score + veryHighPlayerScore, 10);
                goalkeeper.score = Math.max(goalkeeper.score - mediumPlayerScore, 1);
                team.score++;
            } else {
                logEvent(currentTime, "poor_shot", player, `${player.name} took a poor ${shootType}, sending the ball ${Math.random() < 0.5 ? "wide" : "high"} of the target.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "chip_shot":
            // Handle chip shot for attackers or creative players
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "chip_shot", player, `${player.name} attempted a delicate chip over the goalkeeper.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
                goalkeeper.score = Math.max(goalkeeper.score - mediumPlayerScore, 1);
                team.score++;
            } else {
                logEvent(currentTime, "poor_chip_shot", player, `${player.name} attempted a delicate chip over the goalkeeper, but it missed the target.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "header":
            // Handle heading for attackers or defenders in the box
            logEvent(currentTime, "header", player, `${player.name} rose high to meet the cross with a powerful header.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            redraw();
            break;

        case "penalty":
            const penaltyScored = attemptOutcome(
                action,
                player,
                team === team1 ? team2 : team1
            );
            const gkOpposingPlayer = getRandPlayerFromTeam("save", opposingTeam);
            if (penaltyScored) {
                team.score++;
                logEvent(
                    currentTime,
                    "goal",
                    `${player.name} scores a penalty for ${team.name}.`
                );
                player.score = Math.min(player.score + highPlayerScore, 10);
                gkOpposingPlayer.score = Math.max(gkOpposingPlayer.score - mediumPlayerScore, 1);
            } else {
                logEvent(currentTime, "penalty", player, `${player.name} took a penalty kick.`);
                logEvent(
                    currentTime + randTime,
                    "penalty_save",
                    `${gkOpposingPlayer["name"]}  made a crucial penalty save, diving to deny the striker.`
                );
                gkOpposingPlayer.score = Math.min(gkOpposingPlayer.score + mediumPlayerScore, 10);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "own_goal":
            const ownGoal = attemptOutcome(action, player, team);
            if (ownGoal) {
                logEvent(
                    currentTime,
                    "goal",
                    `${player.name} accidentally scores an own goal!`
                );
                opposingTeam.score++;
                player.score = Math.max(player.score - highPlayerScore, 1);
                redraw();
            }
            break;

        case "support_attack":
            // Action when a player joins the attack, making a run or providing support for attacking players
            logEvent(currentTime, "support_attack", `${player.name} supported the attack, making a run forward to help create opportunities.`);
            break;

        case "intercept":
            // Handle interception for midfielders or defenders
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "intercept", player, `${player.name} intercepted the pass to stop a dangerous attack.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
            } else {
                logEvent(currentTime, "poor_intercept", player, `${player.name} intercepted the pass, but it was too late.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "dribble":
            // Handle dribbling for wingers, attacking midfielders, or forwards
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "dribble", player, `${player.name} dribbled past an opponent with skill and speed.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
            } else {
                logEvent(currentTime, "poor_dribble", player, `${player.name} dribbled past an opponent, but it was too late.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "injury":
            // Handle injury
            logEvent(currentTime, "injury", player, `${player.name} suffered an unfortunate injury and is receiving treatment.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            redraw();
            break;

        case "unforced_error":
            // Action when the player makes a mistake without significant pressure, such as a poor touch
            logEvent(currentTime, "unforced_error", player, `${player.name} made an unforced error, unnecessarily losing possession of the ball.`);
            break;

        case "substitute":
            // Handle substitute action
            logEvent(currentTime, "substitute", player, `${player.name} was substituted off, making way for a fresh player.`);
            // Additional logic to handle player removal from the field
            break;

        case "foul":
            // Handle foul action
            logEvent(currentTime, "foul", player, `${player.name} committed a foul and gave away a free kick.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            redraw();
            break;

        case "yellow_card":
            // Handle yellow card for players
            logEvent(currentTime, "yellow_card", player, `${player.name} received a yellow card for a reckless challenge.`);
            player.score = Math.max(player.score - mediumPlayerScorePlayerScore, 1);
            redraw();
            break;

        case "red_card":
            // Handle red card for players
            logEvent(currentTime, "red_card", player, `${player.name} was shown a red card for a serious foul.`);
            player.score = Math.max(player.score - highPlayerScore, 1);
            redraw();
            break;

        case "assist":
            // Handle assist for players who helped set up a goal
            logEvent(currentTime, "assist", player, `${player.name} provided a brilliant assist, setting up the goal.`);
            player.score = Math.min(player.score + mediumPlayerScore, 10);
            redraw();
            break;

        case "corner_kick":
            // Handle corner kick action
            logEvent(currentTime, "corner_kick", player, `${player.name} delivered a dangerous corner kick into the box.`);
            break;

        case "free_kick":
            // Handle free kick action
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "free_kick", player, `${player.name} stepped up to take a free kick, aiming for goal.`);
                player.score = Math.min(player.score + mediumPlayerScore, 10);
            } else {
                logEvent(currentTime, "free_kick", player, `${player.name}'s free kick was off target or saved by the goalkeeper.`);
            }
            redraw();
            break;

        case "counter_attack":
            // Handle counter attack
            logEvent(currentTime, "counter_attack", player, `${player.name} led a quick counter attack, exploiting the space.`);
            break;

        case "1v1":
            // Handle 1v1 duel (attacker vs defender)
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "1v1", player, `${player.name} faced off in a 1v1 duel and managed to beat the defender.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
            } else {
                logEvent(currentTime, "1v1", player, `${player.name} faced off in a 1v1 duel and lost to the defender.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "work_rate":
            // Handle work rate, showing player's effort in both attack and defense
            logEvent(currentTime, "work_rate", player, `${player.name} showed incredible work rate, covering ground all over the pitch.`);
            break;

        case "positional_awareness":
            // Handle positional awareness, recognizing when a player is in the right position
            logEvent(currentTime, "positional_awareness", player, `${player.name} demonstrated excellent positional awareness, being in the right place at the right time.`);
            break;

        case "dribbling":
            // Handle dribbling action
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "dribbling", player, `${player.name} dribbled past defenders with great control and flair.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
            } else {
                logEvent(currentTime, "poor_dribbling", player, `${player.name} dribbled past defenders, but it was too late.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "concentration":
            // Handle concentration, staying focused during key moments
            logEvent(currentTime, "concentration", player, `${player.name} remained fully concentrated, anticipating the next move.`);
            break;

        case "tracking_back":
            // Handle tracking back, the defensive effort to recover position
            logEvent(currentTime, "tracking_back", player, `${player.name} tracked back to help with defensive duties.`);
            break;

        case "sweeping":
            // Handle sweeping, sweeping up balls and covering defensive spaces
            logEvent(currentTime, "sweeping", player, `${player.name} cleared the ball as the last man, showing excellent sweeping ability.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            redraw();
            break;

        case "stamina":
            // Handle stamina, running hard throughout the match
            logEvent(currentTime, "stamina", player, `${player.name} showed fantastic stamina, covering a lot of ground all game.`);
            break;

        case "pressing":
            // Handle pressing, the aggressive defensive action to regain possession
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "pressing", player, `${player.name} applied intense pressing to win back the ball.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
            } else {
                logEvent(currentTime, "poor_pressing", player, `${player.name} applied intense pressing, but it was too late.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "tactical_fouling":
            // Handle tactical fouling, fouling to stop an opponent's progress strategically
            logEvent(currentTime, "tactical_fouling", player, `${player.name} committed a tactical foul to break up an attack.`);
            player.score = Math.max(player.score + lowPlayerScore, 1);
            redraw();
            break;

        case "communication":
            // Handle communication, often seen in goalkeepers or leaders on the pitch
            logEvent(currentTime, "communication", player, `${player.name} communicated effectively, organizing the defense.`);
            break;

        case "ball_control":
            // Handle ball control, crucial for maintaining possession
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "ball_control", player, `${player.name} displayed excellent ball control under pressure.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
            } else {
                logEvent(currentTime, "poor_ball_control", player, `${player.name} displayed poor ball control under pressure.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "crossing":
            // Handle crossing, particularly for wide players or fullbacks
            logEvent(currentTime, "crossing", player, `${player.name} delivered a pinpoint cross into the box.`);
            break;

        case "finishing":
            // Handle finishing, converting chances into goals
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "finishing", player, `${player.name} showed clinical finishing, scoring from a tight angle.`);
                player.score = Math.min(player.score + highPlayerScore, 10);
            } else {
                logEvent(currentTime, "poor_finishing", player, `${player.name} showed poor finishing, scoring from a wide angle.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "creativity":
            // Handle creativity, making unpredictable plays or assists
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "creativity", player, `${player.name} displayed great creativity, unlocking the defense with a clever pass.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
                redraw();
            }
            break;

        case "reflexes":
            // Handle reflexes, especially for goalkeepers
            if (Math.random() < 0.5) {
                logEvent(currentTime, "reflexes", player, `${player.name} made a rapid reflex save, reacting just in time.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
                redraw();
            }
            break;

        case "vision":
            // Handle vision, spotting opportunities others may not see
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "vision", player, `${player.name} used excellent vision to pick out a perfect pass.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
                redraw();
            }
            break;

        case "movement":
            // Handle movement, especially off the ball to create space
            logEvent(currentTime, "movement", player, `${player.name} made a brilliant off-the-ball run to create space.`);
            break;

        case "teamwork":
            // Handle teamwork, collaborating with teammates to maintain possession or score
            logEvent(currentTime, "teamwork", player, `${player.name} worked well with teammates, combining for a perfect move.`);
            break;

        case "aerial_duels":
            // Handle aerial duels, fighting for headers and long balls
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "aerial_duels", player, `${player.name} won the aerial duel, outjumping his opponent.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
                redraw();
            }
            break;

        case "technique":
            // Handle technique, demonstrating superior skill or control
            logEvent(currentTime, "technique", player, `${player.name} displayed impeccable technique with a delicate pass.`);
            break;

        case "passing":
            // Handle passing, including short and long passes
            if (Math.random() < 0.5) {
                logEvent(currentTime, "failed_tackle", player, `${player.name} failed to win the ball in a tackle, allowing the opponent to advance.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            } else {
                logEvent(currentTime, "passing", player, `${player.name} made a precise pass to maintain the flow of play.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
            }
            redraw();
            break;

        case "tracking":
            // Handle tracking, usually seen in defensive or midfield players
            logEvent(currentTime, "tracking", player, `${player.name} tracked back diligently to support the defense.`);
            break;

        case "anticipation":
            // Handle anticipation, being in the right place to intercept or react
            logEvent(currentTime, "anticipation", player, `${player.name} showed great anticipation, reading the game well.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            redraw();
            break;

        case "composure":
            // Handle composure, staying calm under pressure
            logEvent(currentTime, "composure", player, `${player.name} showed great composure, making a cool decision in a tense moment.`);
            break;

        case "blocking_crosses":
            // Handle blocking crosses, preventing crosses from reaching the box
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "blocking_crosses", player, `${player.name} made a crucial block to stop the cross.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
                redraw();
            }
            break;

        case "passing_range":
            // Handle passing range, the ability to make long and accurate passes
            logEvent(currentTime, "passing_range", player, `${player.name} displayed exceptional passing range, delivering a long ball accurately.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            redraw();
            break;

        case "decision_making":
            // Handle decision making, choosing the best option in key moments
            logEvent(currentTime, "decision_making", player, `${player.name} made a smart decision, choosing the best option under pressure.`);
            break;

        case "flair":
            // Handle flair, using creative skills or moves to entertain or beat defenders
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "flair", player, `${player.name} added flair to the game with a brilliant trick or creative pass.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
                redraw();
            }
            break;

        case "cut_inside":
            // Handle cutting inside, especially for wingers or attacking players
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "cut_inside", player, `${player.name} cut inside from the wing, opening up a chance for a shot or pass.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
                redraw();
            }
            break;

        case "link_up_play":
            // Handle link-up play, combining with teammates to build up attacking play
            logEvent(currentTime, "link_up_play", player, `${player.name} linked up well with teammates to advance the attack.`);
            break;

        case "handling":
            // Handle handling, especially for goalkeepers dealing with crosses or shots
            logEvent(currentTime, "handling", player, `${player.name} handled the ball cleanly under pressure.`);
            break;

        case "cut_out_pass":
            // Handle cutting out a pass, intercepting or blocking a dangerous pass
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "cut_out_pass", player, `${player.name} cut out the pass, stopping a potential attacking opportunity.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
                redraw();
            }
            break;

        case "leadership":
            // Handle leadership, motivating teammates and organizing play
            logEvent(currentTime, "leadership", player, `${player.name} showed great leadership, guiding the team through a tough moment.`);
            break;

        case "positioning":
            // Handle positioning, being in the right place at the right time
            logEvent(currentTime, "positioning", player, `${player.name} showed excellent positioning, always being in the right place at the right time.`);
            break;

        case "hold_up_play":
            // Handle hold-up play, maintaining possession while waiting for support
            logEvent(currentTime, "hold_up_play", player, `${player.name} held up the ball well, waiting for teammates to join the attack.`);
            break;

        case "command_of_area":
            // Handle command of area, especially for goalkeepers controlling the penalty box
            logEvent(currentTime, "command_of_area", player, `${player.name} commanded the area, confidently dealing with aerial threats and crosses.`);
            break;

        case "throwing":
            // Handle throwing, especially for goalkeepers or defenders launching quick throws
            logEvent(currentTime, "throwing", player, `${player.name} made a precise throw to initiate an attack.`);
            break;

        case "clearance_under_pressure":
            // Handle clearance under pressure, clearing the ball when under defensive pressure
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "failed_clearance", player, `${player.name} failed to clear the ball, putting the team under pressure.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
            } else {
                logEvent(currentTime, "clearance_under_pressure", player, `${player.name} cleared the ball under pressure, relieving the defense.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "cover":
            // Handle cover, stepping in to support a teammate or defensive line
            logEvent(currentTime, "cover", player, `${player.name} provided excellent cover, supporting the defense in a crucial moment.`);
            break;

        case "strength":
            // Handle strength, using physical power to hold off opponents or win duels
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "strength", player, `${player.name} showed great strength, holding off an opponent to maintain possession.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
            } else {
                logEvent(currentTime, "lost_duel", player, `${player.name} lost the duel, allowing the opponent to gain possession.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "marking":
            // Handle marking, closely defending an opponent to prevent them from receiving the ball
            logEvent(currentTime, "marking", player, `${player.name} marked his opponent tightly, preventing them from getting a touch on the ball.`);
            break;

        case "curve":
            // Handle curve, bending the ball with spin to create chances or score goals
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "curve", player, `${player.name} struck the ball with a beautiful curve, bending it around the defender.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
                redraw();
            }
            break;

        case "shot_stopping":
            // Handle shot stopping, preventing the ball from entering the net
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "shot_stopping", player, `${player.name} made a fantastic save, stopping the shot from going into the net.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
                redraw();
            }
            break;

        case "kicking":
            // Handle kicking, for goalkeepers, defenders, or anyone launching the ball
            logEvent(currentTime, "kicking", player, `${player.name} delivered a powerful kick to clear the ball upfield.`);
            break;

        case "aggression":
            // Handle aggression, being physically dominant in duels or challenging for the ball
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "aggression", player, `${player.name} showed great aggression, winning the ball in a physical challenge.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
                redraw();
            }
            break;

        case "defensive_heading":
            // Handle defensive heading, using the head to clear or win aerial duels defensively
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "defensive_heading", player, `${player.name} won the aerial duel, clearing the ball with a strong defensive header.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
            } else {
                logEvent(currentTime, "poor_defensive_heading", player, `${player.name} showed poor defensive heading, allowing the opponent to advance.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
            break;

        case "defensive_contribution":
            // Handle defensive contribution, any defensive effort like tackling, blocking, or intercepting
            if (attemptOutcome(action, player, team === team1 ? team2 : team1)) {
                logEvent(currentTime, "defensive_contribution", player, `${player.name} made a crucial defensive contribution, helping to stop the attack.`);
                player.score = Math.min(player.score + lowPlayerScore, 10);
                redraw();
            } else {
                logEvent(currentTime, "poor_defensive_contribution", player, `${player.name} showed poor defensive contribution, allowing the opponent to advance.`);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            break;

        case "volleys":
            // Handle volleys, striking the ball while it's in the air
            logEvent(currentTime, "volleys", player, `${player.name} struck the ball with a perfect volley, testing the goalkeeper.`);
            break;

        case "set_piece_delivery":
            // Handle set-piece delivery, providing the ball for a free kick or corner
            logEvent(currentTime, "set_piece_delivery", player, `${player.name} delivered a perfect set-piece, putting the ball into a dangerous area.`);
            break;

        case "rushing_out":
            // Handle rushing out, especially for goalkeepers or defenders stepping out to challenge the opponent
            logEvent(currentTime, "rushing_out", player, `${player.name} rushed out to challenge the attacker, intercepting the ball.`);
            break;

        case "distribution":
            // Handle distribution, usually for goalkeepers or players initiating an attack after a save or clearance
            logEvent(currentTime, "distribution", player, `${player.name} made an accurate distribution, starting an attack from the back.`);
            break;

        case "offside":
            // Handle offside, when a player is in an offside position
            logEvent(currentTime, "offside", player, `${player.name} was caught offside, halting the attacking move.`);
            break;

        case "overlap":
            // Action when a player makes an overlapping run, typically a full-back supporting a winger or midfielder in attack
            logEvent(currentTime, "overlap", `${player.name} made an overlapping run to support the attack.`);
            break;

        default:
            // If action is not handled explicitly, log a general event
            logEvent(currentTime, "action", player, `${player.name} performed a ${action}, keeping the play alive.`);
            console.log(`Unknown action: ${action}`);
            break;
    }


    team1score.innerText = team1.score;
    team2score.innerText = team2.score;
}

function attemptOutcome(action, player, opponentTeam) {
    if (action === "tackle") {
        const attacker = opponentTeam.players.find(
            (p) => !["GK", "CB"].includes(p.position_in_match)
        );
        // Calculate attacker score
        const opponentScore = (
            attacker.attributes.technical.tackling * 0.3 +
            attacker.attributes.mental.positioning * 0.2 +
            attacker.attributes.mental.anticipation * 0.2 +
            attacker.attributes.physical.strength * 0.15 +
            attacker.attributes.mental.concentration * 0.1 +
            attacker.attributes.physical.balance * 0.05 +
            attacker.attributes.mental.aggression * 0.1
        );
        // Calculate defender score
        const playerScore = (
            player.attributes.technical.dribbling * 0.3 +
            player.attributes.physical.pace * 0.2 +
            player.attributes.physical.agility * 0.2 +
            player.attributes.mental.composure * 0.15 +
            player.attributes.physical.balance * 0.1 +
            player.attributes.technical.first_touch * 0.05
        );

        // Add a random bonus chance to the outcome (range: -5 to +5)
        const bonusChance = Math.random() * 10 - 5;

        // Calculate attempt outcome with bonus chance
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if the player wins the tackle, false otherwise
        return attemptOutcome > 0;
    }
    if (action === "clearance") {
        const defender = player; // The player attempting the clearance
        const attacker = opponentTeam.players.find(
            (p) => ["ST", "CF", "CAM", "LM", "RM"].includes(p.position_in_match)
        );

        // Calculate defender score
        const defenderScore = (
            defender.attributes.technical.tackling * 0.2 +
            defender.attributes.mental.positioning * 0.2 +
            defender.attributes.physical.strength * 0.15 +
            defender.attributes.mental.anticipation * 0.2 +
            defender.attributes.physical.jumping_reach * 0.15 +
            defender.attributes.mental.concentration * 0.1
        );

        // Calculate attacker score
        const attackerScore = (
            attacker.attributes.technical.dribbling * 0.2 +
            attacker.attributes.technical.heading_accuracy * 0.2 +
            attacker.attributes.mental.positioning * 0.2 +
            attacker.attributes.physical.strength * 0.2 +
            attacker.attributes.physical.pace * 0.2
        );

        // Add a random bonus chance to the outcome (range: -3 to +3)
        const bonusChance = Math.random() * 6 - 3;

        // Calculate attempt outcome with bonus chance
        const attemptOutcome = defenderScore - attackerScore + bonusChance;
        console.log(action, attackerScore, defenderScore, bonusChance);

        // Return true if the clearance is successful, false otherwise
        return attemptOutcome > 0;
    }
    if (action === "shoot") {
        const attacker = { ...player }; // The player attempting the shot
        const goalkeeper = opponentTeam.players.find((p) => p.position_in_match === "GK");
        const defender = opponentTeam.players.find(
            (p) => ["CB", "LB", "RB"].includes(p.position_in_match)
        );

        // Calculate attacker score
        const attackerScore = (
            attacker.attributes.technical.finishing * 0.3 +
            attacker.attributes.technical.shot_power * 0.2 +
            attacker.attributes.technical.long_shots * 0.1 +
            attacker.attributes.mental.composure * 0.2 +
            attacker.attributes.mental.positioning * 0.1 +
            attacker.attributes.physical.balance * 0.1
        );

        // Calculate goalkeeper score
        const goalkeeperScore = (
            goalkeeper.attributes.goalkeeping.reflexes * 0.3 +
            goalkeeper.attributes.mental.positioning * 0.3 +
            goalkeeper.attributes.goalkeeping.command_of_area * 0.2 +
            goalkeeper.attributes.goalkeeping.handling * 0.1 +
            goalkeeper.attributes.mental.concentration * 0.1
        );

        // Calculate defender score (if present)
        const defenderScore = defender
            ? (
                defender.attributes.technical.tackling * 0.2 +
                defender.attributes.mental.positioning * 0.2 +
                defender.attributes.mental.anticipation * 0.2 +
                defender.attributes.physical.jumping_reach * 0.2 +
                defender.attributes.mental.aggression * 0.2
            )
            : 0;

        // Add a random bonus chance to the shot outcome (range: 1.5 to 1.9)
        const bonusChance = Math.random() * 0.4 + 1.5;

        // Calculate attempt outcome with bonus chance
        const attemptOutcome = attackerScore * bonusChance - (goalkeeperScore + defenderScore);

        // Return true if the shot results in a goal, false otherwise
        return attemptOutcome > 0;
    }
    if (action === "chip_shot") {
        const attacker = { ...player }; // The player attempting the chip shot
        const goalkeeper = opponentTeam.players.find((p) => p.position_in_match === "GK");
        const defender = opponentTeam.players.find(
            (p) => ["CB", "LB", "RB"].includes(p.position_in_match)
        );

        // Calculate attacker score
        const attackerScore = (
            attacker.attributes.technical.finishing * 0.25 +
            attacker.attributes.mental.composure * 0.2 +
            attacker.attributes.mental.vision * 0.2 +
            attacker.attributes.technical.curve * 0.15 +
            attacker.attributes.technical.technique * 0.1 +
            attacker.attributes.physical.balance * 0.1
        );

        // Calculate goalkeeper score
        const goalkeeperScore = (
            goalkeeper.attributes.mental.positioning * 0.3 +
            goalkeeper.attributes.goalkeeping.reflexes * 0.25 +
            goalkeeper.attributes.goalkeeping.rushing_out * 0.2 +
            goalkeeper.attributes.physical.jumping_reach * 0.15 +
            goalkeeper.attributes.mental.anticipation * 0.1
        );

        // Calculate defender score (if present)
        const defenderScore = defender
            ? (
                defender.attributes.mental.positioning * 0.3 +
                defender.attributes.physical.jumping_reach * 0.3 +
                defender.attributes.mental.concentration * 0.2
            )
            : 0;

        // Add a random bonus chance to the shot outcome (range: -1 to +3)
        const bonusChance = Math.random() * 4 - 1;

        // Calculate attempt outcome with bonus chance
        const attemptOutcome = attackerScore + bonusChance - (goalkeeperScore + defenderScore);

        console.log(action, attackerScore, goalkeeperScore, defenderScore, bonusChance);
        // Return true if the chip shot results in a goal, false otherwise
        return attemptOutcome > 0;
    }
    if (action === "penalty") {
        const attacker = { ...player }; // The player taking the penalty
        const goalkeeper = opponentTeam.players.find((p) => p.position_in_match === "GK");

        // Calculate attacker score
        const attackerScore = (
            attacker.attributes.technical.finishing * 0.4 +
            attacker.attributes.mental.composure * 0.3 +
            attacker.attributes.mental.positioning * 0.1 +
            attacker.attributes.technical.shot_power * 0.1 +
            attacker.attributes.technical.curve * 0.05 +
            attacker.attributes.technical.technique * 0.05
        );

        // Calculate goalkeeper score
        const goalkeeperScore = (
            goalkeeper.attributes.goalkeeping.reflexes * 0.3 +
            goalkeeper.attributes.mental.positioning * 0.2 +
            goalkeeper.attributes.goalkeeping.penalty_saving * 0.3 +
            goalkeeper.attributes.mental.anticipation * 0.1 +
            goalkeeper.attributes.goalkeeping.command_of_area * 0.1
        );

        // Add a random bonus chance to the penalty outcome (range: -2 to +2)
        const bonusChance = Math.random() * 4 - 2;

        // Calculate attempt outcome with bonus chance
        const attemptOutcome = attackerScore - goalkeeperScore + bonusChance;

        console.log(action, attemptOutcome);
        // Return true if the penalty results in a goal, false otherwise
        return attemptOutcome > 0;
    }
    if (action === "own_goal") {
        const defender = player; // The defender who scores the own goal
        const attacker = opponentTeam.players.find(
            (p) => ["ST", "CF", "LM", "RM"].includes(p.position_in_match)
        );

        // Calculate defender's score (negative impact since it's an own goal)
        const defenderScore = (
            defender.attributes.technical.tackling * 0.2 +
            defender.attributes.technical.passing * 0.25 +
            defender.attributes.mental.positioning * 0.2 +
            defender.attributes.mental.composure * 0.15 +
            defender.attributes.mental.concentration * 0.1 +
            defender.attributes.physical.balance * 0.1 +
            defender.attributes.mental.anticipation * 0.1
        );

        // Calculate attacker’s score (positive impact if they are directly involved in the own goal)
        const attackerScore = (
            attacker.attributes.technical.finishing * 0.3 +
            attacker.attributes.technical.dribbling * 0.2
        );

        // Add a random chance factor to simulate mistakes or pressure leading to the own goal (range: -1 to +1)
        const bonusChance = Math.random() * 2 - 1;

        // Calculate attempt outcome with bonus chance
        const attemptOutcome = attackerScore - defenderScore + bonusChance;

        // Return true if an own goal occurs (if attemptOutcome is negative, meaning defender made a mistake)
        return attemptOutcome < 0;
    }
    if (action === "intercept") {
        const defender = player; // The defender trying to intercept the ball
        const attacker = opponentTeam.players.find(
            (p) => !["GK", "CB", "CDM"].includes(p.position_in_match)
        );

        // Calculate defender's score
        const defenderScore = (
            defender.attributes.technical.tackling * 0.3 +
            defender.attributes.mental.anticipation * 0.3 +
            defender.attributes.mental.positioning * 0.2 +
            defender.attributes.mental.concentration * 0.1 +
            defender.attributes.mental.aggression * 0.1
        );

        // Calculate attacker’s score (for passing or dribbling)
        const attackerScore = (
            attacker.attributes.technical.passing * 0.4 +
            attacker.attributes.technical.dribbling * 0.3 +
            attacker.attributes.mental.vision * 0.2 +
            attacker.attributes.mental.decision_making * 0.1
        );

        // Add a random bonus chance to the interception outcome (range: -1 to +1)
        const bonusChance = Math.random() * 2 - 1;

        // Calculate attempt outcome with bonus chance
        const attemptOutcome = defenderScore - attackerScore + bonusChance;

        // Return true if the interception is successful (defender outperforms attacker)
        return attemptOutcome > 0;
    }
    if (action === "dribble" || action === "dribbling") {
        const dribbler = player; // The player attempting the dribble
        const defender = opponentTeam.players.find(
            (p) => ["CB", "LB", "RB", "CDM"].includes(p.position_in_match)
        );

        // Calculate dribbler's score
        const dribblerScore = (
            dribbler.attributes.technical.dribbling * 0.4 +
            dribbler.attributes.physical.agility * 0.3 +
            dribbler.attributes.physical.balance * 0.2 +
            dribbler.attributes.physical.pace * 0.1
        );

        // Calculate defender's score (to stop the dribble)
        const defenderScore = (
            defender.attributes.technical.tackling * 0.4 +
            defender.attributes.mental.anticipation * 0.3 +
            defender.attributes.mental.positioning * 0.2 +
            defender.attributes.physical.strength * 0.1
        );

        // Add a random bonus chance to the dribbling outcome (range: -1 to +1)
        const bonusChance = Math.random() * 2 - 1;

        // Calculate attempt outcome with bonus chance
        const attemptOutcome = dribblerScore - defenderScore + bonusChance;

        // Return true if the dribble is successful (dribbler outperforms defender)
        return attemptOutcome > 0;
    }
    if (action === "free_kick") {
        const kicker = player; // The player taking the free kick
        const goalkeeper = opponentTeam.players.find(p => p.position_in_match === "GK");

        // Calculate free kick taker's score
        const kickerScore = (
            kicker.attributes.technical.free_kick_accuracy * 0.4 +
            kicker.attributes.technical.shot_power * 0.3 +
            kicker.attributes.technical.curve * 0.2 +
            kicker.attributes.mental.composure * 0.1
        );

        // Calculate goalkeeper's score (for saving the free kick)
        const goalkeeperScore = (
            goalkeeper.attributes.goalkeeping.shot_stopping * 0.4 +
            goalkeeper.attributes.goalkeeping.reflexes * 0.3 +
            goalkeeper.attributes.mental.positioning * 0.2 +
            goalkeeper.attributes.goalkeeping.aerial_reach * 0.1
        );

        // Optional: If there's a wall, add some blocking ability
        const wallScore = opponentTeam.players.filter(p => ["CB", "LB", "RB", "CDM"].includes(p.position_in_match))
            .reduce((acc, defender) => acc + defender.attributes.physical.jumping_reach * 0.1, 0);

        // Add a random bonus chance (range: -1 to +1) to simulate variables like wall jumping or goalkeeper's reaction time
        const bonusChance = Math.random() * 2 - 1;

        // Calculate attempt outcome with bonus chance and defensive wall score
        const attemptOutcome = kickerScore - (goalkeeperScore + wallScore) + bonusChance;

        // Return true if the free kick is successful (kicker outperforms goalkeeper and defense)
        return attemptOutcome > 0;
    }
    if (action === "1v1") {
        const attacker = { ...player }; // The attacker in the 1v1 situation
        const defender = opponentTeam.players.find(
            (p) => ["CB", "LB", "RB", "CDM"].includes(p.position_in_match)
        );

        // Calculate attacker's score
        const attackerScore = (
            attacker.attributes.technical.dribbling * 0.3 +
            attacker.attributes.physical.pace * 0.2 +
            attacker.attributes.physical.agility * 0.2 +
            attacker.attributes.physical.balance * 0.1 +
            attacker.attributes.mental.composure * 0.1 +
            attacker.attributes.technical.first_touch * 0.05 +
            attacker.attributes.technical.finishing * 0.1
        );

        // Calculate defender's score (for stopping the attacker)
        const defenderScore = (
            defender.attributes.technical.tackling * 0.3 +
            defender.attributes.mental.anticipation * 0.3 +
            defender.attributes.mental.positioning * 0.2 +
            defender.attributes.physical.strength * 0.1 +
            defender.attributes.mental.aggression * 0.1
        );

        // Add random bonus chance (range: -1 to +1) for factors like attacker’s unpredictability or defender’s error
        const bonusChance = Math.random() * 2 - 1;

        // Calculate outcome of the 1v1 (attacker's score minus defender's score)
        const attemptOutcome = attackerScore - defenderScore + bonusChance;

        // Return true if attacker wins the 1v1 (successful dribble, shot, or pass)
        return attemptOutcome > 0;
    }
    if (action === "pressing") {
        const opponent = opponentTeam.players.find(
            (p) => ["CB", "LB", "RB", "CM", "ST", "CAM"].includes(p.position_in_match) // Ensure opponent is an outfield player
        );

        // Calculate pressing player's score
        const playerScore = (
            player.attributes.mental.aggression * 0.3 +
            player.attributes.mental.anticipation * 0.25 +
            player.attributes.mental.concentration * 0.15 +
            player.attributes.mental.work_rate * 0.15 +
            player.attributes.physical.stamina * 0.1 +
            player.attributes.physical.acceleration * 0.05 +
            player.attributes.physical.agility * 0.05
        );

        // Calculate ball carrier's score (player being pressed)
        const opponentScore = (
            opponent.attributes.technical.ball_control * 0.3 +
            opponent.attributes.mental.composure * 0.2 +
            opponent.attributes.mental.decision_making * 0.2 +
            opponent.attributes.technical.dribbling * 0.15 +
            opponent.attributes.technical.passing * 0.1 +
            opponent.attributes.mental.positioning * 0.05
        );

        // Add random bonus chance (range: -1 to +1) to simulate uncertainty in pressing situations
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the pressing action (player's score minus opponent's score)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if pressing player wins the pressing challenge (forces a mistake or wins the ball)
        return attemptOutcome > 0;
    }
    if (action === "ball_control") {
        const opponent = opponentTeam.players.find(
            (p) => ["CB", "LB", "RB", "CM", "ST", "CAM"].includes(p.position_in_match) // Opponent may apply pressure
        );

        // Calculate ball control player's score
        const playerScore = (
            player.attributes.technical.ball_control * 0.3 +
            player.attributes.technical.first_touch * 0.25 +
            player.attributes.mental.composure * 0.2 +
            player.attributes.technical.dribbling * 0.1 +
            player.attributes.mental.positioning * 0.1 +
            player.attributes.mental.anticipation * 0.05 +
            player.attributes.mental.concentration * 0.05
        );

        // Calculate opponent's pressure score (if opponent is present)
        const opponentScore = (
            opponent.attributes.technical.tackling * 0.3 +
            opponent.attributes.mental.aggression * 0.25 +
            opponent.attributes.mental.anticipation * 0.2 +
            opponent.attributes.physical.agility * 0.15 +
            opponent.attributes.mental.concentration * 0.1
        );

        // Add random bonus chance (range: -1 to +1) to simulate the unpredictability of ball control
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the ball control action (player's score minus opponent's score)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if player successfully controls the ball (wins the ball or maintains possession)
        return attemptOutcome > 0;
    }
    if (action === "finishing") {
        const opponent = opponentTeam.players.find(
            (p) => p.position_in_match === "GK" // Opponent is the goalkeeper defending the shot
        );

        // Calculate player's finishing score
        const playerScore = (
            player.attributes.technical.finishing * 0.4 +
            player.attributes.technical.shot_power * 0.2 +
            player.attributes.mental.positioning * 0.2 +
            player.attributes.mental.composure * 0.15 +
            player.attributes.mental.vision * 0.05
        );

        // Calculate goalkeeper's shot-stopping score
        const opponentScore = (
            opponent.attributes.goalkeeping.shot_stopping * 0.4 + // Key attribute for stopping shots
            opponent.attributes.goalkeeping.reflexes * 0.3 + // Quick reflexes to react to the shot
            opponent.attributes.mental.positioning * 0.2 + // Positioning to cover the goal effectively
            opponent.attributes.goalkeeping.aerial_reach * 0.1 // Ability to handle high shots or headers
        );

        // Add random bonus chance (range: -1 to +1) to simulate the unpredictability of finishing situations
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the finishing action (player's score minus goalkeeper's score)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if the player successfully scores (finishes the shot)
        return attemptOutcome > 0;
    }
    if (action === "creativity") {
        const opponent = opponentTeam.players.find(
            (p) => p.position_in_match === "CB" || p.position_in_match === "CDM" // Defenders or midfielders defending against the creativity
        );

        // Calculate player's creativity score
        const playerScore = (
            player.attributes.mental.flair * 0.4 +
            player.attributes.technical.dribbling * 0.25 +
            player.attributes.mental.vision * 0.15 +
            player.attributes.technical.passing * 0.1 +
            player.attributes.technical.technique * 0.05 +
            player.attributes.mental.composure * 0.05
        );

        // Calculate opponent's defensive score (to determine how well they defend against the creativity)
        const opponentScore = (
            opponent.attributes.mental.anticipation * 0.25 + // Ability to predict and stop creative movements
            opponent.attributes.mental.positioning * 0.25 + // Positioning to cut off the space or intercept passes
            opponent.attributes.mental.aggression * 0.2 + // Willingness to challenge or block the creative moves
            opponent.attributes.technical.marking * 0.15 + // Skill in marking players closely and limiting creative freedom
            opponent.attributes.physical.balance * 0.15 // Ability to stand firm against skillful dribblers and creative attackers
        );

        // Add random bonus chance (range: -1 to +1) to simulate unpredictability of creative moments
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the creativity action (player's creativity score minus opponent's defense score)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if the player's creativity breaks through the defense or results in an assist
        return attemptOutcome > 0;
    }
    if (action === "vision") {
        const opponent = opponentTeam.players.find(
            (p) => p.position_in_match === "CM" || p.position_in_match === "CDM" || p.position_in_match === "CB" // Defenders or midfielders defending against vision-based passes
        );

        // Calculate player's vision score (ability to spot opportunities)
        const playerScore = (
            player.attributes.mental.vision * 0.4 + // The core attribute of vision, ability to spot passes and opportunities
            player.attributes.technical.passing * 0.3 + // The player's ability to deliver accurate and creative passes
            player.attributes.mental.anticipation * 0.2 + // Ability to anticipate play and find spaces
            player.attributes.mental.composure * 0.1 // Composure ensures the player can execute passes under pressure
        );

        // Calculate opponent's defensive score (ability to disrupt vision-based passes)
        const opponentScore = (
            opponent.attributes.mental.positioning * 0.3 + // Positioning to block passing lanes or intercept
            opponent.attributes.mental.teamwork * 0.3 + // Teamwork helps defenders anticipate and block passes
            opponent.attributes.mental.anticipation * 0.2 + // Ability to predict the pass and cut it off
            opponent.attributes.technical.marking * 0.2 // Ability to track players closely and prevent effective passes
        );

        // Add random bonus chance (range: -1 to +1) to simulate the unpredictability of vision-based passes
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the vision action (player's vision score minus opponent's defense score)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if the player successfully executes the vision-based action (e.g., a pass, through ball, or assist)
        return attemptOutcome > 0;
    }
    if (action === "aerial_duels") {
        const opponent = opponentTeam.players.find(
            (p) => p.position_in_match === "CB" || p.position_in_match === "CF" || p.position_in_match === "ST" // Opponent players typically involved in aerial duels
        );

        // Calculate the player's aerial duel score (ability to win aerial duels)
        const playerScore = (
            player.attributes.physical.jumping_reach * 0.4 + // The player's ability to jump and reach the ball
            player.attributes.technical.heading_accuracy * 0.3 + // The ability to accurately direct the ball with a header
            player.attributes.physical.strength * 0.2 + // Strength to hold off opponents in aerial duels
            player.attributes.mental.positioning * 0.1 // Positioning to be in the right spot for the aerial duel
        );

        // Calculate opponent's aerial duel defense score (ability to win aerial duels)
        const opponentScore = (
            opponent.attributes.physical.jumping_reach * 0.4 + // The opponent's ability to jump and challenge for the ball
            opponent.attributes.technical.heading_accuracy * 0.3 + // The opponent's ability to direct the ball with a header
            opponent.attributes.physical.strength * 0.2 + // The opponent's physical strength to win the aerial duel
            opponent.attributes.mental.positioning * 0.1 // The opponent's positioning to be in the right place for the duel
        );

        // Add random bonus chance (range: -1 to +1) to simulate the unpredictability of aerial duels
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the aerial duel (player's aerial duel score minus opponent's aerial duel defense score)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if the player wins the aerial duel (e.g., winning the header or challenging the ball successfully)
        return attemptOutcome > 0;
    }
    if (action === "blocking_crosses") {
        const opponent = opponentTeam.players.find(
            (p) => p.position_in_match === "RM" || p.position_in_match === "LM" // Opponent players delivering the cross (typically wingers or full-backs)
        );

        // Calculate the player's blocking cross score (ability to block or intercept crosses)
        const playerScore = (
            player.attributes.mental.positioning * 0.3 + // Positioning to get in the right spot to block the cross
            player.attributes.mental.anticipation * 0.3 + // Anticipation of where the cross is going to land
            player.attributes.mental.aggression * 0.2 + // Aggression to challenge and block the cross
            player.attributes.technical.tackling * 0.1 + // Tackling ability to prevent the cross effectively
            player.attributes.physical.balance * 0.1 // Balance to stay steady and not be outmuscled while blocking
        );

        // Calculate opponent's crossing score (ability to deliver an effective cross)
        const opponentScore = (
            opponent.attributes.technical.crossing * 0.5 + // Accuracy and effectiveness of the cross
            opponent.attributes.mental.vision * 0.2 + // Ability to see teammates and select the right crossing option
            opponent.attributes.mental.anticipation * 0.1 + // Ability to read the game and time the cross effectively
            opponent.attributes.physical.sprint_speed * 0.1 // Speed to get the cross off quickly
        );

        // Add random bonus chance (range: -1 to +1) to simulate the unpredictability of crossing situations
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the cross-blocking action (player's blocking score minus opponent's crossing score)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if the player successfully blocks the cross (e.g., preventing the cross or intercepting it)
        return attemptOutcome > 0;
    }
    if (action === "cut_inside") {
        const opponent = opponentTeam.players.find(
            (p) => p.position_in_match === "CB" || p.position_in_match === "LB" || p.position_in_match === "RB" // Defenders trying to stop the cut inside
        );

        // Calculate the player's cut inside score (ability to make a successful cut inside)
        const playerScore = (
            player.attributes.technical.dribbling * 0.3 + // Ability to maneuver the ball effectively while cutting inside
            player.attributes.physical.acceleration * 0.25 + // Speed burst when cutting inside
            player.attributes.physical.agility * 0.2 + // Agility to change direction smoothly
            player.attributes.physical.balance * 0.15 + // Balance to maintain control while cutting inside under pressure
            player.attributes.mental.composure * 0.1 + // Composure to stay calm and not rush the move
            player.attributes.technical.first_touch * 0.05 // First touch to ensure a clean control while cutting inside
        );

        // Calculate opponent's defensive score (ability to stop or block the cut inside)
        const opponentScore = (
            opponent.attributes.mental.positioning * 0.3 + // Positioning to intercept or block the player's cut inside
            opponent.attributes.mental.anticipation * 0.3 + // Anticipation to predict and stop the cut inside
            opponent.attributes.mental.aggression * 0.2 + // Aggression to challenge and stop the player from cutting inside
            opponent.attributes.physical.balance * 0.1 + // Ability to maintain stability and not be wrong-footed
            opponent.attributes.physical.acceleration * 0.1 // Speed to react and close down the space for the cut inside
        );

        // Add random bonus chance (range: -1 to +1) to simulate the unpredictability of the cut inside action
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the cut inside action (player's cut inside score minus opponent's defensive score)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if the player successfully cuts inside and executes the action (e.g., shot, pass, or dribble)
        return attemptOutcome > 0;
    }
    if (action === "cut_out_pass") {
        const opponent = opponentTeam.players.find(
            (p) => p.position_in_match !== "GK" // The player passing the ball
        );

        // Calculate the player's ability to cut out the pass (intercept)
        const playerScore = (
            player.attributes.mental.positioning * 0.3 + // Positioning to be in the right place for the interception
            player.attributes.mental.anticipation * 0.3 + // Anticipation to predict the opponent's pass
            player.attributes.mental.concentration * 0.2 + // Focus to track and react to the ball
            player.attributes.mental.aggression * 0.1 + // Aggression to press and challenge the ball carrier
            player.attributes.technical.tackling * 0.1 // Tackling ability to win the ball if necessary
        );

        // Calculate the opponent's passing ability (difficulty of the pass)
        const opponentScore = (
            opponent.attributes.technical.passing * 0.4 + // Passing accuracy and vision to avoid interception
            opponent.attributes.mental.vision * 0.3 + // Vision to spot and execute the pass
            opponent.attributes.mental.work_rate * 0.2 + // Effort to make sure the pass reaches its destination
            opponent.attributes.mental.composure * 0.1 // Composure to remain calm while passing under pressure
        );

        // Add random bonus chance (range: -1 to +1) to simulate the unpredictability of intercepting a pass
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the cut out pass action (player's interception score minus opponent's passing score)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if the player successfully intercepts the pass
        return attemptOutcome > 0;
    }
    if (action === "clearance_under_pressure") {
        const opponent = opponentTeam.players.find(
            (p) => p.position_in_match === "ST" || p.position_in_match === "CF" // The attacking player pressuring the clearance
        );

        // Calculate the player's ability to clear the ball under pressure
        const playerScore = (
            player.attributes.technical.clearance * 0.3 + // Ability to clear the ball under pressure
            player.attributes.mental.composure * 0.25 + // Composure to stay calm and make the right clearance
            player.attributes.mental.decision_making * 0.2 + // Decision-making to choose the right clearance option
            player.attributes.mental.positioning * 0.15 + // Positioning to be in the right place for a clean clearance
            player.attributes.physical.strength * 0.1 // Strength to clear the ball under pressure
        );

        // Calculate the opponent’s pressure on the clearance
        const opponentScore = (
            opponent.attributes.mental.aggression * 0.3 + // Aggression to put pressure on the player clearing the ball
            opponent.attributes.physical.sprint_speed * 0.25 + // Speed to close down the player clearing the ball
            opponent.attributes.mental.work_rate * 0.2 + // Effort to challenge and prevent an easy clearance
            opponent.attributes.mental.anticipation * 0.15 + // Ability to anticipate and block or intercept the clearance
            opponent.attributes.physical.strength * 0.1 // Physical strength to challenge the player clearing the ball
        );

        // Add random bonus chance (range: -1 to +1) to simulate the unpredictability of clearing under pressure
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the clearance under pressure action (player's clearance score minus opponent's pressure score)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if the player successfully clears the ball under pressure
        return attemptOutcome > 0;
    }
    if (action === "strength") {
        const opponent = opponentTeam.players.find(
            (p) => p.position_in_match === "ST" || p.position_in_match === "CF" // The opponent challenging for the ball
        );

        // Calculate the player's strength score
        const playerScore = (
            player.attributes.physical.strength * 0.4 + // Physical power to win challenges and hold off opponents
            player.attributes.physical.balance * 0.2 + // Balance to maintain possession under physical pressure
            player.attributes.mental.aggression * 0.2 + // Aggression to challenge for the ball and win duels
            player.attributes.mental.work_rate * 0.1 + // Effort to keep fighting for the ball
            player.attributes.mental.positioning * 0.1 // Positioning to use strength effectively and shield the ball
        );

        // Calculate the opponent's strength and ability to challenge
        const opponentScore = (
            opponent.attributes.physical.strength * 0.4 + // Opponent’s strength to challenge for the ball
            opponent.attributes.physical.balance * 0.2 + // Opponent’s balance to contest possession
            opponent.attributes.mental.aggression * 0.2 + // Opponent’s aggression to challenge
            opponent.attributes.mental.work_rate * 0.1 + // Opponent’s effort to contest the ball
            opponent.attributes.mental.positioning * 0.1 // Opponent’s positioning to put pressure on the player
        );

        // Add random bonus chance (range: -1 to +1) to simulate the unpredictability of strength duels
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the strength contest (player's strength score minus opponent's strength score)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if the player wins the physical duel or maintains possession
        return attemptOutcome > 0;
    }
    if (action === "shot_stopping") {
        const attacker = opponentTeam.players.find(
            (p) => p.position_in_match === "ST" || p.position_in_match === "CF" // The attacking player taking the shot
        );

        // Calculate the goalkeeper's shot stopping ability
        const playerScore = (
            player.attributes.goalkeeping.reflexes * 0.4 + // Reflexes to quickly react to shots
            player.attributes.goalkeeping.handling * 0.3 + // Handling to control or catch the ball
            player.attributes.mental.positioning * 0.2 + // Positioning to be in the right spot to stop the shot
            player.attributes.mental.composure * 0.1 // Composure to stay calm under pressure
        );

        // Calculate the attacking player's shot power
        const opponentScore = (
            attacker.attributes.technical.shot_power * 0.4 + // Power of the shot taken by the attacker
            attacker.attributes.mental.anticipation * 0.2 + // Anticipation to predict and direct the shot
            attacker.attributes.technical.finishing * 0.2 + // Finishing to ensure the shot is on target
            attacker.attributes.mental.concentration * 0.1 + // Concentration to maintain accuracy when shooting
            attacker.attributes.technical.curve * 0.1 // Curve on the shot to make it harder to stop
        );

        // Add random bonus chance (range: -1 to +1) to simulate the unpredictability of shot stopping
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the shot stopping action (goalkeeper's shot stopping ability minus opponent's shot power)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if the goalkeeper successfully stops the shot
        return attemptOutcome > 0;
    }
    if (action === "aggression") {
        const opponent = opponentTeam.players.find(
            (p) => p.position_in_match === "ST" || p.position_in_match === "CF" // The opponent challenging for the ball
        );

        // Calculate the player's aggression score
        const playerScore = (
            player.attributes.mental.aggression * 0.4 + // The player’s desire to challenge and win the ball
            player.attributes.technical.tackling * 0.2 + // Tackling ability to win challenges
            player.attributes.mental.positioning * 0.2 + // Positioning to put the player in good positions for challenges
            player.attributes.physical.strength * 0.1 + // Strength to hold off opponents in duels
            player.attributes.mental.work_rate * 0.1 // Effort to keep challenging for the ball
        );

        // Calculate the opponent's ability to challenge back (defensive attributes)
        const opponentScore = (
            opponent.attributes.mental.aggression * 0.4 + // Opponent’s aggression to challenge and win the ball
            opponent.attributes.technical.tackling * 0.2 + // Opponent’s tackling ability to stop the player
            opponent.attributes.mental.positioning * 0.2 + // Opponent’s positioning to intercept or challenge the player
            opponent.attributes.physical.strength * 0.1 + // Opponent’s strength to resist the player’s challenge
            opponent.attributes.mental.work_rate * 0.1 // Opponent’s work rate to keep challenging
        );

        // Add random bonus chance (range: -1 to +1) to simulate the unpredictability of aggressive actions
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the aggression action (player’s aggression score minus opponent’s ability to challenge)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if the player wins the aggressive challenge or takes possession of the ball
        return attemptOutcome > 0;
    }
    if (action === "defensive_heading") {
        const opponent = opponentTeam.players.find(
            (p) => p.position_in_match === "ST" || p.position_in_match === "CF" // The opponent challenging for the header
        );

        // Calculate the player's defensive heading ability
        const playerScore = (
            player.attributes.technical.heading_accuracy * 0.4 + // Heading accuracy to direct the ball away from danger
            player.attributes.physical.jumping_reach * 0.3 + // Jumping reach to compete for aerial duels
            player.attributes.physical.strength * 0.2 + // Strength to hold off opponents during aerial challenges
            player.attributes.mental.positioning * 0.1 // Positioning to be in the right place for a successful header
        );

        // Calculate the opponent's ability to challenge for the header
        const opponentScore = (
            opponent.attributes.technical.heading_accuracy * 0.4 + // Opponent’s heading accuracy to direct the ball
            opponent.attributes.physical.jumping_reach * 0.3 + // Opponent’s jumping reach to contest aerial duels
            opponent.attributes.physical.strength * 0.2 + // Opponent’s strength to challenge for headers
            opponent.attributes.mental.positioning * 0.1 // Opponent’s positioning to get to the ball first
        );

        // Add random bonus chance (range: -1 to +1) to simulate the unpredictability of aerial duels
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the defensive heading action (player’s heading ability minus opponent’s challenge)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if the player successfully wins the defensive header
        return attemptOutcome > 0;
    }
    if (action === "defensive_contribution") {
        const opponent = opponentTeam.players.find(
            (p) => p.position_in_match === "ST" || p.position_in_match === "CF" // The opponent trying to attack
        );

        // Calculate the player's defensive contribution ability
        const playerScore = (
            player.attributes.technical.tackling * 0.3 + // Ability to win the ball in tackles
            player.attributes.mental.positioning * 0.2 + // Positioning to block or intercept passes
            player.attributes.mental.anticipation * 0.2 + // Ability to anticipate the opponent's next move
            player.attributes.mental.aggression * 0.1 + // Aggressiveness in defending and challenging for the ball
            player.attributes.physical.strength * 0.1 + // Strength to win physical challenges and hold off opponents
            player.attributes.physical.balance * 0.1 // Balance to maintain stability during defensive actions
        );

        // Calculate the opponent's attacking threat
        const opponentScore = (
            opponent.attributes.mental.positioning * 0.3 + // Positioning to get into dangerous positions and receive the ball
            opponent.attributes.mental.anticipation * 0.2 + // Opponent’s ability to read the game and find attacking space
            opponent.attributes.technical.dribbling * 0.2 + // Dribbling ability to bypass defenders
            opponent.attributes.mental.aggression * 0.1 + // Aggressiveness in pushing forward and trying to break through
            opponent.attributes.physical.strength * 0.1 + // Strength to push through challenges from defenders
            opponent.attributes.physical.balance * 0.1 // Balance to stay on feet during attacking runs
        );

        // Add random bonus chance (range: -1 to +1) to simulate the unpredictability of defensive situations
        const bonusChance = Math.random() * 2 - 1;

        // Calculate the outcome of the defensive contribution action (player’s contribution minus opponent’s attacking threat)
        const attemptOutcome = playerScore - opponentScore + bonusChance;

        // Return true if the player successfully contributes defensively, e.g., intercepts or blocks the opponent's attack
        return attemptOutcome > 0;
    }

    return false;
}

function handlePlayerExit(team, currentTime, player, reason) {
    if (team.bench.length) {
        // Attempt to get a substitute from the team's bench
        const substitute = getRandPlayerFromTeam("exit", team, player);

        if (substitute && (reason !== "red_card")) {
            // Find the index of the exiting player
            const playerIndex = team.players.findIndex(p => p.uuid === player.uuid);

            if (playerIndex !== -1) {
                // Replace the exiting player at the same index
                team.players[playerIndex] = substitute;

                // Remove the substitute from the bench
                team.bench = team.bench.filter((p) => p !== substitute);

                // Log the substitution event
                logEvent(currentTime, "player-in", substitute, `${substitute.name} enters the field, replacing ${player.name}.`);
            } else {
                console.error(`Player ${player.name} not found in team.players.`);
            }
        } else {
            if (reason !== "red_card") {
                // Handle the scenario when no substitutes are available
                logEvent(currentTime, "player-empty", `${team.name} has no substitutes left!`);
            }

            // Remove the exiting player from the active players list
            team.players = team.players.filter((p) => p !== player);
        }
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

function logEvent(time, action, player, message) {
    const html = `
    <div class="flex-shrink-0 avatar-xs acitivity-avatar">
        <div class="avatar-title bg-success-subtle text-success rounded-circle">
            <i class="mdi ${getActionIcon(action)}"></i>
        </div>
    </div>
    <div class="flex-grow-1 ms-3 pt-1">
        <p class="text-muted mb-0 fs-12">${formatMatchTime(time)["minute"]}:${formatMatchTime(time)["second"]
        }<span class="player-dot" style="background-color: ${player.playerColor}"></span></p>
        <h6 class="mb-1">${message}</h6>
    </div>`;
    const parentElement = document.getElementById("match-timeline");
    const newElement = document.createElement("div");
    newElement.classList.add("acitivity-item", "pb-3", "d-flex");
    newElement.innerHTML = html;

    parentElement.insertBefore(newElement, parentElement.firstChild);
    speakText(message);
}

// Function to speak text
function speakText(text) {
    console.log("speak: " + text);

    // Check if speech synthesis is supported
    if ('speechSynthesis' in window) {
        // Create a new SpeechSynthesisUtterance instance
        const utterance = new SpeechSynthesisUtterance(text);

        // Set the voice to a more dynamic, lively voice (can be adjusted based on available voices in the browser)
        let voices = window.speechSynthesis.getVoices();
        let commentatorVoice = voices.find(voice => voice.name.includes("Google UK English Male") || voice.name.includes("Microsoft Mark Desktop"));

        // Set the selected voice if found, or fallback to default
        if (commentatorVoice) {
            utterance.voice = commentatorVoice;
        }

        // Adjust pitch and rate for a football commentator feel
        // utterance.pitch = 1.5;  // Higher pitch for excitement
        // utterance.rate = 1.2;   // Slightly faster rate to simulate a dynamic commentary pace
        // utterance.volume = 1;   // Full volume

        // Start speaking the text
        window.speechSynthesis.speak(utterance);
    } else {
        console.log("Speech synthesis not supported in this browser.");
    }
}

// Function to play crowd noise
function playCrowdNoise() {
    const crowdAudio = new Audio(crowdAudioPath);  // Replace with the correct path to your crowd noise file
    crowdAudio.loop = true;  // Make sure it loops
    crowdAudio.volume = 0.3; // Set a lower volume for background noise
    crowdAudio.play();
    
    // Stop crowd noise after a certain duration (for example, after 60 seconds)
    setTimeout(() => {
        crowdAudio.pause();
        crowdAudio.currentTime = 0;  // Reset the audio to the start
        crowdAudio.play();
    }, 39000);  // Stop after 39 seconds (adjust as necessary)
}

$("#btn-start-match").on('click', () =>{
    $("#btn-start-match").hide();
    $("#btn-cancel-match").removeClass('d-none');

    // Play the crowd noise in the background
    playCrowdNoise();
    // Start the match simulation
    simulateMatch(teamsInMatch);
})