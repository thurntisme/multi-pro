const teamsInMatch = groupTeams.map((team) => {
    const players = generateFormation(team.formation).map((pos, idx) => {
        return {
            position_in_match: pos.posName,
            score: 5,
            ...team.players[idx],
        };
    });
    const bench = team.bench.map((player) => {return {...player, position_in_match: player.best_position}});
    return {...team, players, bench};
});

const redraw = () => {
    renderTeamInFitch(teamsInMatch, {circleRadius: 8, isDisplayScore: true});
};
redraw();

function simulateMatch(teamsInMatch) {
    const team1 = teamsInMatch[0];
    const team2 = teamsInMatch[1];
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
            $("#btn-accept-match").removeAttr("disabled");
            $(document).on("click",function() {
                const result = teamsInMatch.map(team => {
                    return {
                        name: team.name,
                        score: team.score,
                        players: [...team.players, ...team.bench].map(player => {return {name: player.name, position: player.position_in_match, score: player.score.toFixed(1)}})
                    }
                });
                console.log(result);
            });
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
                if (Math.random() < 0.5) {
                    simulateAction(team, player, team1, team2, currentTimeInSeconds);
                }
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
    const randPlayer = filterPlayers[Math.floor(Math.random() * filterPlayers.length)];
    return randPlayer;
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
            "penaltySave",
        ],
        LB: [
            "tackle",
            "cutInside",
            "clearance",
            "heading",
            "cross",
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
            "heading",
            "block",
            "intercept",
            "ownGoal",
        ],
        RB: [
            "tackle",
            "cutInside",
            "clearance",
            "heading",
            "cross",
            "ownGoal",
            "longShot",
            "freeKick",
            "intercept",
            "offside",
        ],
        LM: [
            "cutInside",
            "dribble",
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
            "ownGoal",
            "longShot",
            "freeKick",
            "clearance",
        ],
        CM: [
            "dribble",
            "keyPass",
            "longShot",
            "tackle",
            "intercept",
            "clearance",
            "freeKick",
        ],
        CAM: [
            "keyPass",
            "shoot",
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
            "heading",
            "chipShot",
            "longShot",
            "freeKick",
        ],
        ST: [
            "shoot",
            "offside",
            "ownGoal",
            "penalty",
            "heading",
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
    const {position_in_match} = player;
    const opposingTeam = team === team1 ? team2 : team1;
    const goalkeeper = getRandPlayerFromTeam("save", opposingTeam);
    // Randomly select a valid action for the chosen position
    let actions = validActionsByPosition[position_in_match];
    const randAction = Math.random();
    let action = actions[Math.floor(randAction * actions.length)];
    const isPossibleSub = currentTime > 45 * 60;
    if (randAction < 0.2){
        if (randAction < 0.01){
            action = "injury";
        } else if (randAction < 0.1 && isPossibleSub) {
            action = "substitute";
        } else {
            action = "foul";
        }
    }

    const randTime = Math.round(Math.random() * (10 - 1) + 1);

    const team1score = document.getElementById("team-1-score");
    const team2score = document.getElementById("team-2-score");

    const lowPlayerScore = Math.random() * 1.0; // Range: 0 to 1.0
    const mediumPlayerScore = Math.random() * (2.0 - 1.0) + 1.0; // Range: 1.0 to 2.0
    const highPlayerScore = Math.random() * (2.5 - 2.0) + 2.0; // Range: 2.0 to 2.5
    const veryHighPlayerScore = Math.random() * (3.0 - 2.5) + 2.5; // Range: 2.5 to 3.0

    switch (action) {
        case "block":
            const shootType = Math.random() < 0.5 ? "shoot" : "long shot";
            const opposingShootPlayer = getRandPlayerFromTeam(action, opposingTeam);
            logEvent(
                currentTime,
                "block",
                `${player.name} blocked a ${shootType} from ${
                    opposingShootPlayer["name"]
                }.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            redraw();
            break;

        case "corner":
            logEvent(currentTime, "corner", `${player.name} took a corner kick.`);
            break;

        case "shoot":
        case "longShot":
        case "freeKick":
        case "chipShot":
        case "heading":
            const scored = attemptOutcome(
                action,
                player,
                team === team1 ? team2 : team1
            );
            const assistPlayer = getRandPlayerFromTeam(action, team, player);
            const actionName = action
                .replace(/([a-z])([A-Z])/g, "$1 $2")
                .toLowerCase();
            if (action !== "freeKick" && Math.random() < 0.3) {
                if (["shoot"].includes(action)) {
                    logEvent(
                        currentTime - randTime,
                        "cutInside",
                        `${player.name} cut inside from the wing.`
                    );
                } else {
                    if (validActionsByPosition[assistPlayer.position_in_match].includes("cross")) {
                        logEvent(
                            currentTime - randTime,
                            "cross",
                            `${assistPlayer.name} delivered a cross into the box.`
                        );
                    }
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
                player.score = Math.min(player.score + veryHighPlayerScore, 10);
                goalkeeper.score = Math.max(goalkeeper.score - mediumPlayerScore, 1);
                team.score++;
            } else {
                if (Math.random() < 0.5) {
                    player.score = Math.min(player.score + lowPlayerScore, 10);
                    const attackerName = ` from ${player.name}'s ${actionName}`;
                    goalkeeper.score = Math.min(goalkeeper.score + lowPlayerScore, 10);
                    const goalkeeperActions = ["save", "catch", "punch"];
                    const gkAction =
                        goalkeeperActions[
                            Math.floor(Math.random() * goalkeeperActions.length)
                            ];
                    let gkActionName = `${goalkeeper.name} made a save ${attackerName}.`;
                    if (gkAction === "catch") {
                        gkActionName = `${goalkeeper.name} caught the ball ${attackerName}.`;
                    }
                    if (gkAction === "punch") {
                        gkActionName = `${goalkeeper.name} punched the ball away ${attackerName}.`;
                    }

                    logEvent(currentTime, "save", gkActionName);
                } else {
                    logEvent(
                        currentTime,
                        "miss-goal",
                        `${player.name} do a ${actionName} but misses.`
                    );
                    player.score = Math.max(player.score - lowPlayerScore, 1);
                }
            }
            redraw();
            break;

        case "foul":
            if (player.foul) {
                player.foul++;
            } else {
                player.foul = 1;
            }
            if (randAction < 0.1) {
                if (randAction < 0.01){
                    player.foul_card = "red";
                } else {
                    player.foul_card = "yellow";
                }
            } else {
                if (player.foul / 3 === 1) {
                    player.foul_card = "yellow";
                }
            }
            if (player.foul_card === "yellow") {
                if (player.foul / 3 >= 2) {
                    player.foul_card = "red";
                }
            }
            let foulCardText = '';
            if (player.foul_card === 'yellow') {
                foulCardText = `, got a yellow card`;
            }
            logEvent(currentTime, "foul", `${player.name} commits a foul${foulCardText}.`);
            if (player.foul_card === 'red') {
                handlePlayerExit(team, currentTime + randTime, {...player, isExit: true}, "red_card");
            }
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
            const opposingDribblePlayer = getRandPlayerFromTeam(action, opposingTeam);
            logEvent(
                currentTime,
                "intercept",
                `${player.name} intercepted the ball from ${
                    opposingDribblePlayer["name"]
                }.`
            );
            player.score = Math.min(player.score + mediumPlayerScore, 10);
            opposingDribblePlayer.score = Math.max(opposingDribblePlayer.score - lowPlayerScore, 1);
            redraw();
            break;

        case "injury":
            logEvent(currentTime, "injury", `${player.name} sustained an injury and required assistance.`);
            handlePlayerExit(team, currentTime + randTime, {...player, isExit: true}, "injury");
            break;

        case "substitute ":
            logEvent(currentTime, "substitute ", `${player.name} was substituted.`);
            handlePlayerExit(team, currentTime + randTime, {...player, isExit: true}, "substitute ");
            break;

        case "tackle":
            logEvent(
                currentTime,
                "tackle",
                `${player.name} successfully tackles an opponent.`
            );
            player.score = Math.max(player.score + lowPlayerScore, 1);
            redraw();
            break;

        case "offside":
            let passType = "";
            const passPlayer = getRandPlayerFromTeam(action, team, player);
            passPlayer.score = Math.min(passPlayer.score + lowPlayerScore, 10);
            if (Math.random() < 0.3) {
                passType = "made a key pass to";
            } else if (Math.random() < 0.6) {
                passType = "made a long pass to";
            } else {
                passType = "passed the ball to";
            }
            logEvent(
                currentTime - randTime,
                "pass",
                `${passPlayer.name} ${passType} ${player.name}.`
            );
            logEvent(currentTime, "offside", `${player.name} was caught offside.`);
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
                logEvent(currentTime, "penalty", `${player.name} took a penalty kick.`);
                logEvent(
                    currentTime + randTime,
                    "penaltySave",
                    `${gkOpposingPlayer["name"]} saved a penalty kick.`
                );
                gkOpposingPlayer.score = Math.min(gkOpposingPlayer.score + mediumPlayerScore, 10);
                player.score = Math.max(player.score - lowPlayerScore, 1);
            }
            redraw();
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
                player.score = Math.max(player.score - highPlayerScore, 1);
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
    const {finishing, heading, free_kick_accuracy, marking, long_shots} =
        attacker.attributes.technical;
    const {composure, flair, off_the_ball} = attacker.attributes.mental;
    const {strength, jumping_reach} = attacker.attributes.physical;

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
            decision * 0.3 + // Poor decision-making increases the chance
            composure * 0.4 + // Low composure increases likelihood
            concentration * 0.2 + // Lack of focus contributes to own goals
            marking * 0.1; // Good marking can reduce the likelihood

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
                const {heading, marking} = player.attributes.technical;
                const {jumping_reach} = player.attributes.physical;
                return {heading, marking, jumping_reach};
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
    if (action === "heading") {
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
                const {heading, marking} = player.attributes.technical;
                const {jumping_reach, strength} = player.attributes.physical;
                return {heading, marking, jumping_reach, strength};
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

function handlePlayerExit(team, currentTime, player, reason) {
    if (team.bench.length){
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
                logEvent(currentTime, "player-in", `${substitute.name} enters the field, replacing ${player.name}.`);
            } else {
                console.error(`Player ${player.name} not found in team.players.`);
            }
        } else {
            if (reason !== "red_card"){
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
