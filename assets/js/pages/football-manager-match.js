const url = new URL(window.location.href);
const payload = { match_uuid: url.searchParams.get('uuid') };

const teamsInMatch = groupTeams.map((team, teamIdx) => {
    const playerColor = teamIdx === 0 ? homeTeamColor : awayTeamColor;
    const players = generateFormation(team.formation).map((pos, idx) => {
        return {
            teamIdx,
            position_in_match: pos.posName,
            score: 5,
            goals_in_match: 0,
            own_goals_in_match: 0,
            playerColor,
            is_played: true,
            is_injury: false,
            ...team.players[idx],
        };
    });
    const bench = team.bench.map((player) => {
        return {
            ...player,
            position_in_match: player.best_position,
            playerColor,
            teamIdx,
            score: 5,
            goals_in_match: 0,
            own_goals_in_match: 0,
            is_played: false,
            is_injury: false
        }
    });
    return { ...team, players, bench };
});

const redraw = () => {
    renderTeamInFitch(teamsInMatch, {
        circleRadius: 8,
        isDisplayScore: true,
        isDisplayName: true,
        isTeamInMatch: true,
        isDisplayBall: true
    });
};
redraw();

// Define valid actions for each position
const validActionsByPosition = {
    GK: ["goal_kick", "catch_cross", "punch", "clearance", "mark_players", "block_shot"],
    LB: ["intercept", "tackle", "overlap", "cross", "cut_inside", "long_ball", "block_cross", "track_runner", "intercept_cross", "contain", "block_shot", "press_receiver", "long_shot", "long_pass"],
    CB: ["press", "mark_strikers", "clearance", "intercept", "tackle", "block_shot", "block_cross", "attack_header", "mark", "challenge_header", "recover_ball", "contain", "press_receiver", "long_pass"],
    RB: ["intercept", "tackle", "overlap", "cross", "cut_inside", "long_ball", "block_cross", "track_runner", "intercept_cross", "contain", "block_shot", "press_receiver", "long_shot", "long_pass"],
    LM: ["rebound", "cross", "dribble", "cut_inside", "pass", "long_shot", "skill_move", "block_cross", "press_receiver"],
    CDM: ["press", "mark_strikers", "block_pass", "intercept", "tackle", "pass", "long_ball", "shield_ball", "switch_play", "shift_defensive_line", "contain", "block_shot", "press_receiver", "attack_header", "challenge_header", "long_shot", "long_pass", "distribute_ball"],
    CM: ["press", "pass", "block_pass", "dribble", "long_shot", "through_ball", "tackle", "intercept", "counter_attack", "contain", "block_shot", "press_receiver", "attack_header", "challenge_header", "long_pass", "distribute_ball"],
    CAM: ["rebound", "step_over", "block_pass", "dribble", "pass", "through_ball", "shoot", "cut_inside", "skill_move", "block_shot", "press_receiver", "long_shot", "distribute_ball"],
    RM: ["rebound", "cross", "dribble", "cut_inside", "pass", "long_shot", "skill_move", "block_cross", "press_receiver"],
    LW: ["rebound", "step_over", "cross", "dribble", "cut_inside", "pass", "shoot", "skill_move", "block_shot"],
    CF: ["rebound", "challenge_header", "step_over", "shoot", "lay_off", "pass", "press_defender", "attack_header", "dribble", "hold_up_play"],
    ST: ["rebound", "challenge_header", "step_over", "shoot", "attack_header", "hold_up_play", "press_defender", "run_in_behind", "tap_in", "block_shot", "press_receiver"],
    RW: ["rebound", "step_over", "cross", "dribble", "cut_inside", "pass", "shoot", "skill_move", "block_shot"],
};

const defaultActions = ["pass", "long_pass", "goal_kick", "throw_in"];

function simulateMatch(teamsInMatch) {
    const team1 = teamsInMatch[0];
    const team2 = teamsInMatch[1];
    const matchTime = 90 * 60; // Total match duration in minutes
    const maxHalfTime = 45 * 60; // Total match duration in minutes
    const maxExtraTime = 10; // Maximum possible extra time in minutes
    const realTimeRate = 1;
    let currentTime = 0;
    let currentTimeInSeconds = 0;
    let matchTimeInSeconds = 0;
    let halfTimePassed = false;
    let extraTimePassed = false;
    let matchOver = false;
    let totalMatchTime = 0;

    const recordTime = Math.floor(Math.random() * (2642 - 275 + 1)) + 275;
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

        if (matchTimeInSeconds % realTimeRate === 0) {
            if (totalMatchTime === recordTime) {
                try {
                    // $.ajax({
                    //     url: apiUrl + '/football-manager/match/record',
                    //     method: 'POST',
                    //     contentType: 'application/json',
                    //     data: JSON.stringify({
                    //         ...payload,
                    //         players: teamsInMatch[teamsInMatch[0].is_my_team ? 0 : 1].players
                    //     }),
                    //     success: function (response) {
                    //         console.log(response);
                    //     },
                    //     error: function (xhr, status, error) {
                    //         console.error('Error:', error);
                    //     },
                    // });
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }

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
                $("#btn-match-info").removeClass("d-none");
                let bestPlayerUuid = null;
                let bestPlayerScore = 0;
                const result = teamsInMatch.map(team => {
                    return {
                        name: team.name,
                        score: team.score,
                        players: [...team.players, ...team.bench]
                            .filter(player => player.is_played)
                            .map(player => {
                                if (player.score > bestPlayerScore) {
                                    bestPlayerUuid = player.uuid;
                                    bestPlayerScore = player.score;
                                }
                                return {
                                    uuid: player.uuid,
                                    name: player.name,
                                    level: player.level,
                                    position: player.position_in_match,
                                    score: player.score.toFixed(1),
                                    goals: player?.goals_in_match || 0,
                                    assists: 0,
                                    own_goals: player?.own_goals_in_match || 0,
                                    is_injury: player.is_injury,
                                    form: player.form,
                                    recovery_time: player.recovery_time,
                                    yellow_cards: player?.yellow_cards_in_match || 0,
                                    red_cards: player?.red_cards_in_match || 0,
                                    remaining_stamina: Math.round(player.player_stamina - (100 - player.attributes.physical.stamina) / 100 * formatMatchTime(totalMatchTime)['minute']),
                                }
                            })
                    }
                });
                const match_result = {
                    draft_home_score: result[0].score,
                    draft_away_score: result[1].score,
                    players: result[teamsInMatch[0].is_my_team ? 0 : 1].players
                };
                $(document).on("click", "#btn-match-info", function () {
                    const matchAttributes = $("#matchInfoBackdrop #matchAttributes");
                    matchAttributes.html('<p class="mb-0">Data processing...</p>');
                    let playerAttrContent = '';
                    result.forEach(team => {
                        playerAttrContent += `<div class="col-6 pt-3 mt-3 border-top-dashed border-1 border-dark border-opacity-25">
                            <h6 class="card-title flex-grow-1 mb-3 fs-15 text-capitalize">${team.name}</h6>
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="text-muted"></td>
                                        <td class="text-muted"></td>
                                        <td class="text-muted"></td>
                                        <th class="text-center px-1" style="width: 52px;">
                                            <img src="${goalImagePath}" class="img-responsive avatar-xxs" alt="goals" />
                                        </th>
                                        <th class="text-center px-1" style="width: 52px;">
                                            <img src="${yellowCardImagePath}" class="img-responsive avatar-xxs" alt="yellow card" />
                                        </th>
                                        <th class="text-center px-1" style="width: 52px;">
                                            <img src="${redCardImagePath}" class="img-responsive avatar-xxs" alt="red card" />
                                        </th>
                                    </tr>`;

                        team.players.forEach(function (player) {
                            playerAttrContent += `<tr>
                                        <th class="ps-0 text-capitalize text-start" scope="row">
                                            ${player.name} ${player.is_injury ? '(Injury)' : ''}
                                            ${player.uuid === bestPlayerUuid ? '<span class="badge bg-success">Best</span>' : ''}
                                        </th>
                                        <td class="text-muted">${player.position}</td>
                                        <td class="text-muted">${player.score}</td>
                                        <td class="text-muted">
                                            ${player.goals} ${player.own_goals ? `(${player.own_goals}G)` : ''}
                                        </td>
                                        <td class="text-muted">${player.yellow_cards}</td>
                                        <td class="text-muted">${player.red_cards}</td>
                                    </tr>`;
                        })

                        playerAttrContent += `</tbody>
                            </table>
                        </div>`;
                    });
                    matchAttributes.empty();
                    const matchResult = `<p class="mb-1 fs-16">
                    <span class="text-muted me-2 w-25 d-inline-block" style="text-align: right">${result[0].name}</span>
                    <span class="text-black fs-20" style="width: 20px">${result[0].score}</span><span class="text-muted mx-1">:</span><span class="text-black fs-20" style="width: 20px">${result[1].score}</span>
                    <span class="text-muted ms-2 w-25 d-inline-block" style="text-align: left">${result[1].name}</span>
                    </p>`;
                    matchAttributes.append(matchResult);
                    const playerAttrHtml = `<div class="row">${playerAttrContent}</div>`;
                    matchAttributes.append(playerAttrHtml);
                });
                payload.result = JSON.stringify(match_result);
                try {
                    // $.ajax({
                    //     url: apiUrl + '/football-manager/match/result',
                    //     method: 'POST',
                    //     contentType: 'application/json',
                    //     data: JSON.stringify(payload),
                    //     success: function (response) {
                    //         if (response.status === "success") {
                    //             $('#match-form').removeClass('d-none');
                    //         }
                    //     },
                    //     error: function (xhr, status, error) {
                    //         console.error('Error:', error);
                    //     },
                    // });
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
                $("#match-form").on("submit", function (e) {
                    e.preventDefault();
                    $("[name=match_result]").val(JSON.stringify(result));
                    this.submit();
                });
                return;
            }

            if (currentTimeInSeconds === currentTime) {
                currentTime += Math.floor(Math.random() * 60) + 30;
                if (!currentTimeInSeconds) {
                    logEvent(currentTimeInSeconds, "start", '', "Match start");
                } else {
                    const defaultAction = performFollowAction(prevAction, prevPlayer, currentTimeInSeconds);
                    prevAction = defaultAction.followAction;
                    prevPlayer = defaultAction.followPlayer;
                }
            }

            totalMatchTime++;
            currentTimeInSeconds++;
        }
        // Increment the total time
        matchTimeInSeconds++;
    }, 10); // Delay of 1 second per iteration
}

// Helper function to get a random player from a filtered list
function getRandomPlayer(team, filterCondition) {
    const players = team.players.filter(filterCondition);
    return players[Math.floor(Math.random() * players.length)];
}

// Helper function to get a random next action from a list
function getRandomNextAction(possibleActions) {
    return possibleActions[Math.floor(Math.random() * possibleActions.length)];
}

// Helper function to randomly determine if action is teammate or opponent
function getRandomActionType() {
    return Math.random() < 0.5 ? "teammate" : "opponent";
}

function performFollowAction(prevAction, prevPlayer, currentTime) {
    let followPlayer = null;
    let followAction = null;
    let description = '';
    if (prevAction && prevPlayer) {
        let nextAction = '';
        let opponentAction = '';
        switch (prevAction) {
            case "kick_off":
                if (prevPlayer) {
                    description = `${prevPlayer.name} steps up to restart the game with a kick-off after the goal. Their team looks to build an attack immediately.`;
                    nextAction = getRandomNextAction(["pass", "long_pass", "dribble"]);
                    opponentAction = getRandomNextAction(["press", "mark", "intercept"]);
                }
                break;

            case "goal_kick":
                if (prevPlayer) {
                    description = `${prevPlayer.name} takes the goal kick, launching the ball high up the field to create an opportunity.`;
                    nextAction = getRandomNextAction(["challenge_header", "distribute_ball"]);
                    opponentAction = getRandomNextAction(["intercept", "press", "regain_possession"]);
                }
                break;
            
            case "pass":
                if (prevPlayer) {
                    description = `${prevPlayer.name} makes a pass, trying to connect with a teammate and advance the play.`;
                    nextAction = getRandomNextAction(["long_pass", "dribble", "shoot", "long_shot"]);
                    opponentAction = getRandomNextAction(["block", "tackle", "intercept"]);
                }
                break;
            
            case "long_pass":
                if (prevPlayer) {
                    description = `${prevPlayer.name} plays a long pass, sending the ball deep into the opponent's half to create an attacking opportunity.`;
                    nextAction = getRandomNextAction(["throw_in", "challenge_header", "control_ball", "long_shot"]);
                    opponentAction = getRandomNextAction(["intercept", "challenge_header", "mark"]);
                }
                break;

            case "throw_in":
                if (prevPlayer) {
                    description = `${prevPlayer.name} takes the throw-in, looking to find a teammate and restart the play quickly.`;
                    nextAction = getRandomNextAction(["pass", "long_pass", "dribble", "cross"]);
                    opponentAction = getRandomNextAction(["press", "mark", "intercept"]);
                }
                break;

            case "dribble":
                description = "The player takes on defenders with a confident dribble, advancing the ball.";
                nextAction = getRandomNextAction(["pass", "shoot", "cross"]);
                opponentAction = getRandomNextAction(["tackle", "press", "block"]);
                break;

            case "distribute_ball":
                description = "The goalkeeper calmly distributes the ball to a nearby teammate to maintain possession.";
                nextAction = getRandomNextAction(["pass", "shoot", "cross"]);
                opponentAction = getRandomNextAction(["tackle", "press", "block"]);
                break;

            case "intercept":
                description = "The player steps in and intercepts the opponent's pass, regaining possession.";
                nextAction = getRandomNextAction(["pass", "shoot", "cross", "throw_in"]);
                opponentAction = getRandomNextAction(["tackle", "press", "block", "throw_in"]);
                break;

            case "press":
                description = "The team applies pressure to force an error from the opponent.";
                nextAction = getRandomNextAction(["tackle", "intercept", "regain_possession"]);
                opponentAction = getRandomNextAction(["pass", "clearance", "dribble"]);
                break;

            case "block":
                description = "The player makes a vital block, stopping the ball in its tracks.";
                nextAction = getRandomNextAction(["regain_possession", "clearance", "counter_attack"]);
                opponentAction = getRandomNextAction(["retry_pass", "recover", "switch_play"]);
                break;

            case "challenge_header":
                description = "The player leaps high for an aerial duel, contesting the ball with determination.";
                nextAction = getRandomNextAction(["pass", "flick_on", "control_ball", "throw_in"]);
                opponentAction = getRandomNextAction(["press", "mark", "regain_possession", "throw_in"]);
                break;

            case "shoot":
                description = "The player takes a shot at goal, aiming to beat the goalkeeper.";
                nextAction = getRandomNextAction(["goal", "saved", "rebound", "miss"]);
                opponentAction = getRandomNextAction(["block", "intercept", "save", "foul"]);
                break;

            case "long_shot":
                description = `${prevPlayer.name} took a powerful long shot, testing the goalkeeper from a distance.`;
                nextAction = getRandomNextAction(["rebound", "pass"]);
                opponentAction = getRandomNextAction(["block_shot"]);
                break;

            case "volley":
                description = "The player attempts a spectacular volley, striking the ball mid-air.";
                nextAction = getRandomNextAction(["goal", "saved", "rebound", "miss"]);
                opponentAction = getRandomNextAction(["block", "deflect", "intercept", "foul"]);
                break;

            case "tackle":
                description = "The player goes in for a strong tackle to win the ball back.";
                nextAction = getRandomNextAction(["pass", "shoot", "cross", "throw_in"]);
                opponentAction = getRandomNextAction(["tackle", "press", "block", "throw_in"]);
                break;

            case "cross":
                description = "The player whips in a cross, aiming to create a scoring opportunity.";
                nextAction = getRandomNextAction(["attack_header", "volley", "shoot", "pass"]);
                opponentAction = getRandomNextAction(["block_cross", "clearance", "intercept"]);
                break;

            case "control_ball":
                description = "The player controls the ball skillfully, preparing for the next move.";
                nextAction = getRandomNextAction(["dribble", "pass", "shoot", "clearance"]);
                opponentAction = getRandomNextAction(["press", "tackle", "block", "mark", "intercept"]);
                break;

            case "mark":
                description = "The player stays close to their opponent, cutting off passing options.";
                nextAction = getRandomNextAction(["intercept", "block", "tackle"]);
                opponentAction = getRandomNextAction(["dribble", "skill_move", "pass"]);
                break;

            case "attack_header":
                description = "The player rises to meet the cross with a powerful attacking header.";
                nextAction = getRandomNextAction(["goal", "assist", "clear", "pass"]);
                opponentAction = getRandomNextAction(["defend", "block", "intercept", "foul"]);
                break;

            case "regain_possession":
                description = "The team successfully regains possession and looks to build an attack.";
                nextAction = getRandomNextAction(["dribble", "pass", "shoot", "clearance"]);
                opponentAction = getRandomNextAction(["press", "tackle", "mark"]);
                break;

            case "block_cross":
                description = "The defender steps in to block the cross, denying a scoring chance.";
                nextAction = getRandomNextAction(["clearance", "pass", "long_ball", "intercept"]);
                opponentAction = getRandomNextAction(["cross", "dribble", "cut_inside"]);
                break;

            case "long_ball":
                description = "The player launches a long ball forward, looking to create an opportunity.";
                nextAction = getRandomNextAction(["challenge_header", "control_ball", "shoot", "pass"]);
                opponentAction = getRandomNextAction(["press", "intercept", "challenge_header", "throw_in"]);
                break;

            case "skill_move":
                description = "The player executes a dazzling skill move to get past their marker.";
                nextAction = getRandomNextAction(["dribble", "pass", "shoot", "cross"]);
                opponentAction = getRandomNextAction(["tackle", "intercept"]);
                break;

            case "flick_on":
                description = "The player flicks the ball on with a deft touch, redirecting it to a teammate.";
                nextAction = getRandomNextAction(["control_ball", "pass", "shoot", "cross"]);
                opponentAction = getRandomNextAction(["press", "intercept", "block"]);
                break;

            case "clearance":
                description = "The defender clears the ball to relieve pressure on their team.";
                nextAction = getRandomNextAction(["counter_attack", "intercept", "pass"]);
                opponentAction = getRandomNextAction(["press", "block", "throw_in"]);
                break;

            case "counter_attack":
                description = "The team launches a rapid counter-attack, exploiting the opponent's disorganization.";
                nextAction = getRandomNextAction(["dribble", "pass", "shoot", "cross"]);
                opponentAction = getRandomNextAction(["tackle", "press", "recover_ball"]);
                break;

            case "corner_kick":
                if (prevPlayer) {
                    description = `${prevPlayer.name} steps up to take the corner kick, delivering the ball into the box to create a scoring opportunity.`;
                    nextAction = getRandomNextAction(["attack_header", "volley", "shoot", "clearance", "throw_in"]);
                    opponentAction = getRandomNextAction(["block_cross", "clearance", "intercept", "throw_in"]);
                }
                break;

            case "rebound":
                if (prevPlayer) {
                    description = `${prevPlayer.name} reacts quickly to the rebound, pouncing on the loose ball to create another opportunity.`;
                    nextAction = getRandomNextAction(["shoot", "pass"]);
                    opponentAction = getRandomNextAction(["block", "intercept", "press"]);
                }
                break;

            default:
                console.warn("Unknown action type", prevAction);
        }

        if (nextAction && opponentAction) {
            const {outcomeDescription, outcomeAction, outcomePlayer} = getOutcomeAction(prevAction, prevPlayer);
            if (outcomeAction && outcomePlayer) {
                followAction = outcomeAction;
                followPlayer = outcomePlayer;
            } else {
                let actionType = getRandomActionType();
                if (actionType === "teammate") {
                    followAction = nextAction;
                    followPlayer = randomPlayerWithAction(followAction, prevPlayer.teamIdx);
                } else {
                    followAction = opponentAction;
                    followPlayer = randomPlayerWithAction(followAction, prevPlayer.teamIdx === 0 ? 1 : 0);
                }
            }
            if (prevAction && prevPlayer && (outcomeDescription || description)) { 
                logEvent(currentTime, prevAction, prevPlayer, outcomeDescription || description);
            }
        }
    } else {
        const randomActionIndex = Math.floor(Math.random() * defaultActions.length);
        const randomAction = defaultActions[randomActionIndex];
        const randomTeamIndex = Math.floor(Math.random() * teamsInMatch.length);
        const team = teamsInMatch[randomTeamIndex];
        followAction = randomAction;
        switch (followAction) {
            case "goal_kick":
                followPlayer = team.players.find(p => p.position_in_match === "GK");
                if (followPlayer) {
                    description = `${followPlayer.name} executed a precise goal kick, launching the ball forward to maintain possession.`;
                }
                break;

            case "pass":
                followPlayer = getRandomPlayer(team, p => p.position_in_match !== "GK" && !p?.is_off);
                if (followPlayer) {
                    description = `${followPlayer.name} delivered an accurate pass, keeping the attack alive.`;
                }
                break;

            case "long_pass":
                followPlayer = getRandomPlayer(team, p => p.position_in_match !== "GK" && !p?.is_off);
                if (followPlayer) {
                    description = `${followPlayer.name} launched a long pass, attempting to bypass the opposition's midfield.`;
                }
                break;

            case "throw_in":
                followPlayer = getRandomPlayer(team, p => ["LB", "RB", "LM", "RM"].includes(p.position_in_match) && !p?.is_off);
                if (followPlayer) {
                    description = `${followPlayer.name} performed a quick throw-in, aiming to restart play efficiently.`;
                }
                break;
        }
        if (followAction && followPlayer && description) {
            logEvent(currentTime, followAction, followPlayer, description);
        }
    }
    if(followPlayer){
        teamsInMatch[0].playerSelected = null;
        teamsInMatch[1].playerSelected = null;
        teamsInMatch[followPlayer.teamIdx].playerSelected = followPlayer.uuid;
    }
    redraw();
    const team1score = document.getElementById("team-1-score");
    const team2score = document.getElementById("team-2-score");
    team1score.innerText = teamsInMatch[0].score;
    team2score.innerText = teamsInMatch[1].score;
    console.log({ followAction, followPlayer })
    return { followAction, followPlayer }
}

function getOutcomeAction(followAction, followPlayer){
    if (followAction === "long_shot"){
        return getLongShotOutcome(followPlayer);
    }
    if (followAction === "shoot"){
        return getShootOutcome(followPlayer);
    }

    return {outcomeDescription: '', outcomeAction: [], outcomePlayer: null};
}

function randomPlayerFromPositions(players, positions) {
    if (positions.length === 0) {
        return null; // No positions left to check, return null
    }
    
    const randomPosition = positions[Math.floor(Math.random() * positions.length)];
    const randomPlayers = players.filter(p => p.position_in_match === randomPosition && !p?.is_off);
    
    if (randomPlayers.length > 0) {
        return randomPlayers[Math.floor(Math.random() * randomPlayers.length)]; // Return a random player
    }

    // Remove the current position and try again recursively
    return randomPlayerFromPositions(players, positions.filter(position => position !== randomPosition));
}

function randomPlayerWithAction(action, teamIdx) {
    const positionsWithAction = Object.keys(validActionsByPosition).filter(position =>
        validActionsByPosition[position].includes(action)
    );
    return randomPlayerFromPositions(teamsInMatch[teamIdx].players, positionsWithAction);
}

function randomPlayerKickOff(players) {
    return randomPlayerFromPositions(players, ["CF", "ST"])
}

function randomPlayerThrowIn(players) {
    return randomPlayerFromPositions(players, ["LB", "RB", "LM", "RM"])
}

function randomPlayerCornerKick(players) {
    return randomPlayerFromPositions(players, ["LB", "RB", "LM", "RM", "CM", "CAM", "LW", "RW"])
}

function getLongShotOutcome(player){
    return getShotOutcome(player, "long_shot")
}

function getShootOutcome(player) {
    return getShotOutcome(player, "shoot")
}

function getShotOutcome(player, shoot_type) {
    const opponentPlayers = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players;
    const goalkeeper = randomPlayerFromPositions(opponentPlayers, ['GK']);
    const defender = randomPlayerFromPositions(opponentPlayers, ["CB", "CDM"]);
    
    const defensiveImpact = (defender.attributes.mental.positioning + defender.attributes.mental.anticipation + defender.attributes.mental.bravery) / 300;

    // Base probabilities for long shots
    let baseGoalChance = 0.1;   // Base 10% chance of scoring
    let baseMissChance = 0.3;   // Base 30% chance of missing
    let baseBlockedChance = 0.4; // Base 40% chance of being blocked
    let baseSaveChance = 0.2;   // Base 20% chance of a save

    const longShotsImpact = player.attributes.technical.long_shots / 100; // Skill in long shots
    const finishingImpact = player.attributes.technical.finishing / 100; // Scales with finishing skill
    const balanceImpact = player.attributes.physical.balance / 100; // Stability while shooting
    const shotPowerImpact = player.attributes.technical.shot_power / 100; // Harder shots reduce save/block chances
    const composureImpact = player.attributes.mental.composure / 100; // Staying calm under pressure

    let totalSkillImpact = 0;
    if (shoot_type === 'long_shot'){
        totalSkillImpact = (longShotsImpact + shotPowerImpact + composureImpact) / 3;
    } else {
        totalSkillImpact = (finishingImpact + shotPowerImpact + composureImpact + balanceImpact) / 4;
    }

    // Adjust for goalkeeping attributes
    const goalkeeperSkill = (goalkeeper.attributes.goalkeeping.reflexes + goalkeeper.attributes.goalkeeping.shot_stopping + goalkeeper.attributes.goalkeeping.handling) / 300;

    // Calculate adjusted probabilities
    const goalChance = baseGoalChance + totalSkillImpact - goalkeeperSkill / 2; // Better players score more, good GKs reduce this
    const saveChance = baseSaveChance + goalkeeperSkill - totalSkillImpact / 2; // Better GKs save more, good players reduce this
    const missChance = baseMissChance - totalSkillImpact / 3; // Skilled players miss less
    const blockedChance = baseBlockedChance + defensiveImpact - totalSkillImpact / 4; // Random defender increases block chance

    // Ensure probabilities add up to 1
    const total = goalChance + saveChance + missChance + blockedChance;
    const adjustedGoalChance = goalChance / total;
    const adjustedSaveChance = saveChance / total;
    const adjustedMissChance = missChance / total;
    const adjustedBlockedChance = blockedChance / total;

    let outcomeDescription = '';
    let outcomeAction = '';
    let outcomePlayer = null;
    // Random outcome based on probabilities
    const chance = Math.random();
    if (chance < adjustedGoalChance) {
        outcomeDescription = `${player.name} took a long shot and scored a stunning goal from distance!`;
        player.score = Math.min(player.score + generatePlayerScore('high'), 10);
        player.goals_in_match++;
        teamsInMatch[player.teamIdx].score++;
        outcomeAction = "kick_off";
        outcomePlayer = randomPlayerKickOff(teamsInMatch[player.teamIdx === 0 ? 1: 0].players)
    }
    else if (chance < adjustedGoalChance + adjustedSaveChance){
        outcomeDescription = `${player.name} took a ${shoot_type.replace("_", " ")},`;
        goalkeeper.score = Math.min(goalkeeper.score + generatePlayerScore('low'), 10);

        // Determine the next action after the save
        const nextActionType = getRandomNextAction(["saved", "corner", "not_cleared"]);
        switch (nextActionType) {
            case "saved":
                outcomeDescription += " but the goalkeeper made a remarkable save! The ball remains in play as the goalkeeper holds onto it.";
                outcomeAction = getRandomNextAction(["long_ball", "pass"]);
                outcomePlayer = goalkeeper;
                break;
            case "corner":
                outcomeDescription += " the ball deflected off the goalkeeper and went out for a corner kick.";
                outcomeAction = "corner_kick";
                outcomePlayer = randomPlayerCornerKick(teamsInMatch[player.teamIdx].players)
                break;
            case "not_cleared":
                outcomeDescription += 
                " the goalkeeper couldn't fully clear the ball, and it deflected back into play, creating a dangerous rebound situation!";
                outcomeAction = "rebound";
                outcomePlayer = randomPlayerWithAction(outcomeAction, player.teamIdx);
                break;
            default:
                outcomeDescription += " but the goalkeeper made a remarkable save!.";
        }
    }
    else if (chance < adjustedGoalChance + adjustedSaveChance + adjustedMissChance){
        outcomeDescription = `${player.name}'s ${shoot_type.replace("_", " ")} missed the target and went over the crossbar.`;
        player.score = Math.max(player.score - generatePlayerScore('low'), 1);
        outcomeAction = "corner_kick";
        outcomePlayer = goalkeeper;
    }
    else if (chance < adjustedGoalChance + adjustedSaveChance + adjustedMissChance + adjustedBlockedChance) {
        outcomeDescription = `${player.name}'s ${shoot_type.replace("_", " ")} was blocked by a defender,`;
        defender.score = Math.min(defender.score + generatePlayerScore("low"), 10);
    
        const nextActionType = getRandomNextAction(["saved", "corner", "own_goal", "not_cleared"]);
        switch (nextActionType) {
            case "saved":
                outcomeDescription += " stopping a potential threat.";
                outcomeAction = getRandomNextAction(["long_ball", "pass"]);
                outcomePlayer = randomPlayerWithAction(outcomeAction, defender.teamIdx);
                break;
    
            case "corner":
                outcomeDescription += " the ball went out for a corner kick.";
                outcomeAction = "corner_kick";
                outcomePlayer = randomPlayerCornerKick(teamsInMatch[player.teamIdx].players);
                break;

            case "not_cleared":
                outcomeDescription += 
                " but the ball deflected back into play, creating a dangerous rebound opportunity!";
                outcomeAction = "rebound";
                outcomePlayer = randomPlayerWithAction(outcomeAction, player.teamIdx);
                break;
    
            case "own_goal":
                outcomeDescription += " resulting in an own goal!";
                player.score = Math.min(player.score + generatePlayerScore("medium"), 10);
                defender.score = Math.max(defender.score - generatePlayerScore("medium"), 1);
                defender.own_goals_in_match = (defender.own_goals_in_match || 0) + 1; 
                teamsInMatch[player.teamIdx === 0 ? 1 : 0].score++; 
                outcomeAction = "kick_off";
                outcomePlayer = randomPlayerKickOff(teamsInMatch[player.teamIdx === 0 ? 1: 0].players)
                break;
    
            default:
                outcomeDescription += " was blocked by a defender.";
        }
    }

    return {outcomeDescription, outcomeAction, outcomePlayer}
}

function getCatchCrossOutcome(player) {
    let opponentPlayer;
    const attackers = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => ["LB", "RB", "LM", "RM", "LW", "RW"].includes(p.position_in_match));
    if (attackers.length > 0) {
        opponentPlayer = attackers[Math.floor(Math.random() * attackers.length)];
    } else {
        const allPlayers = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => p.position_in_match !== "CB");
        opponentPlayer = allPlayers[Math.floor(Math.random() * allPlayers.length)];
    }
    // Base probabilities for cross outcomes
    let baseSuccessChance = 0.3; // 30% base chance of successful catch
    let baseFailChance = 0.3;    // 30% base chance of failure to catch
    let basePunchChance = 0.15;  // 15% base chance of punching the ball away
    let baseOwnGoalChance = 0.1; // 10% base chance of own goal
    let baseInterceptedChance = 0.15; // 15% base chance of the cross being intercepted

    // Adjust probabilities based on the goalkeeper's attributes
    const handlingImpact = player.attributes.goalkeeping.handling / 100;
    const reflexesImpact = player.attributes.goalkeeping.reflexes / 100;
    const commandOfAreaImpact = player.attributes.goalkeeping.command_of_area / 100;
    const goalkeeperSkill = (handlingImpact + reflexesImpact + commandOfAreaImpact) / 3;

    // Adjust probabilities based on opponent player's crossing skill
    const crossingImpact = opponentPlayer.attributes.technical.crossing / 100;
    const curveImpact = opponentPlayer.attributes.technical.curve / 100;
    const opponentSkill = (crossingImpact + curveImpact) / 2;

    // Modify chances dynamically
    let successChance = baseSuccessChance + goalkeeperSkill - opponentSkill / 2;
    let failChance = baseFailChance - goalkeeperSkill / 2 + opponentSkill / 3;
    let punchChance = basePunchChance + goalkeeperSkill / 3 - opponentSkill / 3;
    let ownGoalChance = baseOwnGoalChance;
    let interceptedChance = baseInterceptedChance + (1 - opponentSkill) / 3;

    // Normalize probabilities
    const total = successChance + failChance + punchChance + ownGoalChance + interceptedChance;
    successChance /= total;
    failChance /= total;
    punchChance /= total;
    ownGoalChance /= total;
    interceptedChance /= total;

    // Determine outcome
    const chance = Math.random();
    if (chance < successChance) {
        return "success"; // Goalkeeper successfully catches the ball
    } else if (chance < successChance + failChance) {
        return "fail"; // Goalkeeper fails to catch the ball
    } else if (chance < successChance + failChance + punchChance) {
        return "punch"; // Goalkeeper punches the ball away
    } else if (chance < successChance + failChance + punchChance + ownGoalChance) {
        return "own_goal"; // Goalkeeper accidentally scores an own goal
    } else {
        return ""; // Cross is intercepted by a defender
    }
}

function getPassOutcome(player) {
    // Base probabilities
    let baseSuccessChance = 0.7;    // 70% base chance of a successful pass
    let baseInterceptedChance = 0.15; // 15% base chance of interception
    let baseBlockedChance = 0.1;    // 10% base chance of being blocked
    let baseMissedChance = 0.05;    // 5% base chance of the pass being off-target

    // Adjust probabilities based on player's short passing skill
    const shortPassingImpact = player.attributes.technical.short_passing / 100;

    // Adjust interception chance based on opponent defenders
    let defensiveImpact = 0;
    const opponentDefenders = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => !["GK", "CB"].includes(p.position_in_match) && !p?.is_off);
    let randomOpponentPlayer;
    if (opponentDefenders.length > 0) {
        randomOpponentPlayer = opponentDefenders[Math.floor(Math.random() * opponentDefenders.length)];
        defensiveImpact = (randomOpponentPlayer.attributes.mental.anticipation + randomOpponentPlayer.attributes.mental.positioning) / 200;
    }

    // Modify probabilities dynamically
    let successChance = baseSuccessChance + shortPassingImpact - defensiveImpact / 2;
    let interceptedChance = baseInterceptedChance + defensiveImpact / 2 - shortPassingImpact / 4;
    let blockedChance = baseBlockedChance + defensiveImpact / 4 - shortPassingImpact / 3;
    let missedChance = baseMissedChance - shortPassingImpact / 5;

    // Normalize probabilities to ensure they sum to 1
    const total = successChance + interceptedChance + blockedChance + missedChance;
    successChance /= total;
    interceptedChance /= total;
    blockedChance /= total;
    missedChance /= total;

    // Determine the outcome
    let outcome;
    let nextPlayer = randomOpponentPlayer;
    let nextAction = '';
    const chance = Math.random();
    if (chance < successChance) {
        outcome = "successful";
        const followAction = guestFollowReaction('pass', player);
        nextPlayer = followAction.player;
        nextAction = followAction.action;
    } else if (chance < successChance + interceptedChance) {
        outcome = "intercepted";
        const opponentAction = guestOpponentAction('intercept');
        nextAction = opponentAction.action;
    } else if (chance < successChance + interceptedChance + blockedChance) {
        outcome = "blocked";
        const opponentAction = guestOpponentAction('block');
        nextAction = opponentAction.action;
    } else {
        outcome = "missed"; // Pass goes out of bounds or off-target
    }

    return {
        outcome, nextPlayer, nextAction
    }
}

function getChallengeHeaderOutcome(player) {
    // Base probabilities
    let baseSuccessChance = 0.4;    // 40% base chance of success
    let baseFailedChance = 0.2;     // 20% base chance of failure
    let baseFoulChance = 0.15;      // 15% base chance of a foul
    let baseDeflectedChance = 0.1;  // 10% base chance of deflection
    let baseClearedChance = 0.1;    // 10% base chance of clearance
    let baseOffsideChance = 0.05;   // 5% base chance of being offside

    // Adjust probabilities based on player attributes
    const opponentPlayers = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => !["GK"].includes(p.position_in_match) && !p?.is_off);
    const opponent = opponentPlayers[Math.floor(Math.random() * opponentPlayers.length)];
    const playerHeaderImpact = player.attributes.technical.heading_accuracy / 100;
    const playerJumpImpact = player.attributes.physical.jumping_reach / 100;
    const opponentJumpImpact = opponent.attributes.physical.jumping_reach / 100;
    const opponentHeaderImpact = opponent.attributes.technical.heading_accuracy / 100;

    // Factor in relative attributes
    const skillDifference = playerHeaderImpact + playerJumpImpact - opponentHeaderImpact - opponentJumpImpact;

    // Adjust base probabilities
    baseSuccessChance += skillDifference * 0.2; // Skill difference affects success chance
    baseFailedChance -= skillDifference * 0.1; // Skill difference reduces failure chance
    baseFoulChance += Math.abs(skillDifference) * 0.05; // Close contests may increase fouls
    baseDeflectedChance -= skillDifference * 0.05; // Better skills reduce deflections
    baseClearedChance += opponentHeaderImpact * 0.05; // Opponent skill affects clearance chance

    // Ensure probabilities sum to 1
    const total =
        baseSuccessChance +
        baseFailedChance +
        baseFoulChance +
        baseDeflectedChance +
        baseClearedChance +
        baseOffsideChance;

    baseSuccessChance /= total;
    baseFailedChance /= total;
    baseFoulChance /= total;
    baseDeflectedChance /= total;
    baseClearedChance /= total;
    baseOffsideChance /= total;

    // Determine the outcome
    const chance = Math.random();
    let nextPlayer = opponent;
    let nextAction = guestOpponentAction('challenge_header')['action'];
    if (chance < baseSuccessChance) {
        outcome = "success";
    } else if (chance < baseSuccessChance + baseFailedChance) {
        outcome = "failed";
    } else if (chance < baseSuccessChance + baseFailedChance + baseFoulChance) {
        outcome = "foul";
    } else if (chance < baseSuccessChance + baseFailedChance + baseFoulChance + baseDeflectedChance) {
        outcome = "deflected";
    } else if (chance < baseSuccessChance + baseFailedChance + baseFoulChance + baseDeflectedChance + baseClearedChance) {
        outcome = "cleared";
    } else {
        outcome = "offside";
    }

    if (["success", "cleared"].includes(outcome)) {
        const followAction = guestFollowReaction('challenge_header', player);
        nextPlayer = followAction.player;
        nextAction = followAction.action;
    }

    return { outcome, nextPlayer, nextAction }
}

function getGoalKickOutcome(player) {
    const currentAction = "goal_kick";
    const rand = Math.random();
    const nextReaction = rand > 0.5 ? playerActions[currentAction] : opponentReactions[currentAction];
    const reactionIndex = Math.floor(Math.random() * nextReaction.length);
    const nextAction = nextReaction[reactionIndex];
    const positionsWithAction = Object.keys(validActionsByPosition).filter(position =>
        validActionsByPosition[position].includes(nextAction)
    );

    let players = [];
    if (rand > 0.5) {
        players = teamsInMatch[player.teamIdx].players.filter(p => positionsWithAction.includes(p.position_in_match));
    } else {
        players = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => positionsWithAction.includes(p.position_in_match));
    }
    const nextPlayer = players[Math.floor(Math.random() * players.length)];

    return { nextPlayer, nextAction }
}

function getLongPassOutcome(player) {
    // Base probabilities
    let baseSuccessChance = 0.6;    // 60% base chance of a successful long pass
    let baseInterceptedChance = 0.2; // 20% base chance of interception
    let baseBlockedChance = 0.1;    // 10% base chance of being blocked
    let baseMissedChance = 0.05;    // 5% base chance of going out of bounds
    let baseChippedChance = 0.05;   // 5% base chance of a chipped pass

    // Adjust probabilities based on player's long passing skill
    const longPassingImpact = player.attributes.technical.long_passing / 100;

    // Adjust interception and block chances based on opponent defenders
    let defensiveImpact = 0;
    const opponentDefenders = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => !["GK", "CB"].includes(p.position_in_match));
    if (opponentDefenders.length > 0) {
        const randomDefender = opponentDefenders[Math.floor(Math.random() * opponentDefenders.length)];
        defensiveImpact = (randomDefender.attributes.mental.anticipation + randomDefender.attributes.mental.positioning) / 200;
    }

    // Modify probabilities dynamically
    let successChance = baseSuccessChance + longPassingImpact - defensiveImpact / 2;
    let interceptedChance = baseInterceptedChance + defensiveImpact / 2 - longPassingImpact / 4;
    let blockedChance = baseBlockedChance + defensiveImpact / 4 - longPassingImpact / 3;
    let missedChance = baseMissedChance - longPassingImpact / 5;
    let chippedChance = baseChippedChance + longPassingImpact / 5;

    // Normalize probabilities to ensure they sum to 1
    const total = successChance + interceptedChance + blockedChance + missedChance + chippedChance;
    successChance /= total;
    interceptedChance /= total;
    blockedChance /= total;
    missedChance /= total;
    chippedChance /= total;

    // Determine the outcome
    const chance = Math.random();
    if (chance < successChance) {
        return "successful"; // Pass reaches the target
    } else if (chance < successChance + interceptedChance) {
        return "intercepted"; // Opponent intercepts the pass
    } else if (chance < successChance + interceptedChance + blockedChance) {
        return "blocked"; // Pass is blocked by an opponent
    } else if (chance < successChance + interceptedChance + blockedChance + missedChance) {
        return "missed"; // Pass goes out of bounds or off-target
    } else {
        return "chipped"; // Successful chip pass over the defender
    }
}

function getDribbleOutcome(player) {
    // Base probabilities
    let baseSuccessChance = 0.5;    // 50% base chance of a successful dribble
    let baseTackledChance = 0.25;  // 25% base chance of being tackled
    let baseFouledChance = 0.15;   // 15% base chance of being fouled
    let baseLoseControlChance = 0.1; // 10% base chance of losing control

    // Adjust probabilities based on player's dribbling skill
    const dribblingImpact = player.attributes.technical.dribbling / 100;

    // Adjust probabilities based on opponent defender's tackling ability
    let tacklingImpact = 0;
    const opponentDefenders = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => !["GK", "CB"].includes(p.position_in_match));
    if (opponentDefenders.length > 0) {
        const randomDefender = opponentDefenders[Math.floor(Math.random() * opponentDefenders.length)];
        tacklingImpact = randomDefender
            ? randomDefender.attributes.technical.tackling / 100
            : 0; // Use 0 if no defender is present
    }

    // Modify probabilities dynamically
    let successChance = baseSuccessChance + dribblingImpact - tacklingImpact / 2;
    let tackledChance = baseTackledChance + tacklingImpact / 2 - dribblingImpact / 4;
    let fouledChance = baseFouledChance + tacklingImpact / 4 - dribblingImpact / 5;
    let loseControlChance = baseLoseControlChance - dribblingImpact / 5;

    // Normalize probabilities to ensure they sum to 1
    const total = successChance + tackledChance + fouledChance + loseControlChance;
    successChance /= total;
    tackledChance /= total;
    fouledChance /= total;
    loseControlChance /= total;

    // Determine the outcome
    const chance = Math.random();
    let description = '';
    let actionType = 'opponent';
    if (chance < successChance) {
        description = `${player.name} dribbled past the defender successfully.`;
        actionType = 'teammate';
    } else if (chance < successChance + tackledChance) {
        description = `${player.name} was tackled and lost the ball.`
    } else if (chance < successChance + tackledChance + fouledChance) {
        description = `${player.name} was fouled while attempting a dribble.`;
    } else {
        description = `${player.name} lost control of the ball during the dribble.`;
    }

    return { description, actionType }
}

function getInterceptOutcome(player) {
    // Base probabilities
    let baseSuccessChance = 0.45;    // 45% base chance of successful interception
    let baseMissedChance = 0.25;    // 25% base chance of missing
    let baseDeflectionChance = 0.15; // 15% base chance of deflecting the ball
    let baseFoulChance = 0.1;       // 10% base chance of committing a foul
    let baseOwnGoalChance = 0.05;   // 5% base chance of an own goal

    // Adjust probabilities based on player's defensive skills
    const positioningImpact = player.attributes.mental.positioning / 100;
    const anticipationImpact = player.attributes.mental.anticipation / 100;
    const tacklingImpact = player.attributes.technical.tackling / 100;

    // Adjust probabilities based on pass accuracy (higher pass accuracy reduces interception chances)
    let passAccuracyImpact = 0;
    const attackers = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => !["GK", "CB"].includes(p.position_in_match));
    if (attackers.length > 0) {
        const randomAttacker = attackers[Math.floor(Math.random() * attackers.length)];
        passAccuracyImpact = randomAttacker
            ? randomAttacker.attributes.technical.short_passing / 100
            : 0; // Use 0 if no defender is present
    }

    // Modify probabilities dynamically
    let successChance = baseSuccessChance + positioningImpact + anticipationImpact - passAccuracyImpact / 2;
    let missedChance = baseMissedChance - (positioningImpact + anticipationImpact) / 4 + passAccuracyImpact / 2;
    let deflectionChance = baseDeflectionChance + tacklingImpact / 2 - anticipationImpact / 3;
    let foulChance = baseFoulChance + tacklingImpact / 3 - positioningImpact / 4;
    let ownGoalChance = baseOwnGoalChance - tacklingImpact / 5;

    // Normalize probabilities to ensure they sum to 1
    const total = successChance + missedChance + deflectionChance + foulChance + ownGoalChance;
    successChance /= total;
    missedChance /= total;
    deflectionChance /= total;
    foulChance /= total;
    ownGoalChance /= total;

    // Determine the outcome
    const chance = Math.random();
    let description = '';
    let actionType = 'opponent';
    const lowPlayerScore = generatePlayerScore('low');
    if (chance < successChance) {
        description = `${player.name} successfully intercepted the ball and regained possession.`;
        player.score = Math.min(player.score + lowPlayerScore, 10);
        actionType = 'teammate';
    } else if (chance < successChance + missedChance) {
        description = `${player.name} attempted to intercept but missed, allowing the ball to continue.`;
        player.score = Math.max(player.score - lowPlayerScore, 1);
    } else if (chance < successChance + missedChance + deflectionChance) {
        description = `${player.name} got a touch on the ball, causing a deflection but no possession.`;
        player.score = Math.max(player.score - lowPlayerScore, 1);
    } else if (chance < successChance + missedChance + deflectionChance + foulChance) {
        description = `${player.name} fouled the opponent while trying to intercept.`;
        player.score = Math.max(player.score - lowPlayerScore, 1);
    } else if (chance < successChance + missedChance + deflectionChance + foulChance + ownGoalChance) {
        description = `${player.name} attempted an interception but accidentally deflected the ball into their own net!`;
        player.score = Math.max(player.score - mediumPlayerScore, 1);
        teamsInMatch[player.teamIdx === 0 ? 1 : 0].score++;
        player.own_goals_in_match++;
    } else {
        description = '';
    }

    return { description, actionType }
}

function getAttackHeaderOutcome(player) {
    const goalkeeper = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.find(p => p.position_in_match === "GK");
    // Base probabilities
    let baseGoalChance = 0.35;       // 35% base chance of scoring
    let baseSavedChance = 0.25;      // 25% base chance of a save
    let baseMissChance = 0.2;        // 20% base chance of missing
    let baseBlockedChance = 0.2;     // 20% base chance of being blocked

    // Adjust probabilities based on the attacker's attributes
    const headingAccuracyImpact = player.attributes.technical.heading_accuracy / 100;
    const jumpingReachImpact = player.attributes.physical.jumping_reach / 100;

    // Adjust probabilities based on the goalkeeper's attributes
    const gkReflexesImpact = goalkeeper.attributes.goalkeeping.reflexes / 100;
    const gkHandlingImpact = goalkeeper.attributes.goalkeeping.handling / 100;

    // Adjust probabilities based on defending players (average blocking capability)
    let opponentPlayer;
    const defendingPlayers = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => ["CB", "CDM"].includes(p.position_in_match));
    if (defendingPlayers.length > 0) {
        opponentPlayer = defendingPlayers[Math.floor(Math.random() * defendingPlayers.length)];
    } else {
        const allPlayers = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => !["GK", "CB", "CDM"].includes(p.position_in_match));
        opponentPlayer = allPlayers[Math.floor(Math.random() * allPlayers.length)];
    }
    const avgBlockingSkill = opponentPlayer.attributes.technical.tackling / 100;

    // Modify probabilities dynamically
    let goalChance = baseGoalChance + headingAccuracyImpact + jumpingReachImpact - gkReflexesImpact / 2;
    let savedChance = baseSavedChance + gkReflexesImpact / 2 + gkHandlingImpact / 2;
    let missChance = baseMissChance - headingAccuracyImpact / 2 - jumpingReachImpact / 4;
    let blockedChance = baseBlockedChance + avgBlockingSkill;

    // Normalize probabilities to ensure they sum to 1
    const total = goalChance + savedChance + missChance + blockedChance;
    goalChance /= total;
    savedChance /= total;
    missChance /= total;
    blockedChance /= total;

    // Determine the outcome
    const chance = Math.random();
    const lowPlayerScore = generatePlayerScore('low');
    const highPlayerScore = generatePlayerScore('high');
    let description;
    let actionType;
    let opponentAction;
    if (chance < goalChance) {
        description = `${player.name} leapt high and delivered a powerful header into the back of the net!`;
        player.score = Math.min(player.score + highPlayerScore, 10);
        player.goals_in_match++;
        teamsInMatch[player.teamIdx].score++;
    } else if (chance < goalChance + savedChance) {
        description = `${player.name} aimed a header at the goal, but the goalkeeper made a stunning save!`;
        goalkeeper.score = Math.min(goalkeeper.score + lowPlayerScore, 10);
        actionType = 'opponent';
        opponentAction = 'long_ball';
    } else if (chance < goalChance + savedChance + missChance) {
        description = `${player.name} tried a header but missed the target completely.`;
        player.score = Math.max(player.score - lowPlayerScore, 1);
    } else if (chance < goalChance + savedChance + missChance + blockedChance) {
        description = `${player.name} directed a header toward goal, but a defender blocked it with a brave effort!`;
    } else {
        description = '';
    }
    return { description, actionType, opponentAction }
}

function getTackleOutcome(player) {
    let chance = Math.random(); // Generate a random chance
    if (chance < 0.45) return "successful"; // 45% chance of a successful tackle
    else if (chance < 0.75) return "missed"; // 30% chance of missing the tackle
    else if (chance < 0.9) return "foul"; // 15% chance of committing a foul
    else if (chance < 0.97) return "deflected"; // 7% chance of deflecting the ball
    else return "own_goal"; // 3% chance of an own goal
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
    const goalkeeper = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.find(p => p.position_in_match === "GK");
    let description;
    let opponentAction;
    const lowPlayerScore = generatePlayerScore('low');
    const highPlayerScore = generatePlayerScore('high');
    if (chance < 0.3) {
        description = `${player.name} executed a stunning volley to score a spectacular goal!`;
        player.score = Math.min(player.score + highPlayerScore, 10);
        player.goals_in_match++;
        teamsInMatch[player.teamIdx].score++;
    }
    else if (chance < 0.5) {
        description = `${player.name} struck a volley on target, but the goalkeeper made an excellent save.`;
        goalkeeper.score = Math.min(goalkeeper.score + lowPlayerScore, 10);
        opponentAction = "long_ball";
    }
    else if (chance < 0.7) {
        description = `${player.name} attempted a volley but missed the target.`;
    }
    else if (chance < 0.85) {
        description = `${player.name}'s volley was blocked by a defender.`
    }
    else {
        description = `${player.name}'s volley was deflected, and the ball is now in play as a rebound.`;
        opponentAction = 'rebound';
    }

    return { description, actionType: opponentAction ? 'opponent' : null, opponentAction }
}

function getTapInOutcome(player) {
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.7) return "goal"; // 70% chance of a successful tap-in
    else if (chance < 0.85) return "missed"; // 15% chance of missing the tap-in
    else if (chance < 0.95) return "saved"; // 10% chance of the goalkeeper making a save
    else return "blocked"; // 5% chance of being blocked by a defender
}

function getFoulOutcome(player) {
    let outcome = '';
    let opponentPlayer = '';
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.02) outcome = "red_card"; // 2% chance of a red card (serious foul)
    else if (chance < 0.08) outcome = "yellow_card"; // 6% chance of a yellow card (reckless foul)
    else if (chance < 0.14) outcome = "penalty_kick_success"; // 6% chance of a successful penalty kick (foul in the box)
    else if (chance < 0.26) outcome = "penalty_kick_fail"; // 12% chance of a failed penalty kick (foul in the box)
    else if (chance < 0.32) outcome = "free_kick_success"; // 6% chance of a successful free kick (foul outside the box)
    else if (chance < 0.42) outcome = "free_kick_fail"; // 10% chance of a failed free kick (foul outside the box)
    else outcome = "no_card"; // 58% chance of no card (minor foul)

    if (outcome === 'penalty_kick_success' || outcome === 'free_kick_success') {
        const opponentTeam = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => !p?.is_off && p.position_in_match !== 'GK');
        opponentPlayer = opponentTeam[Math.floor(Math.random() * opponentTeam.length)];
    }

    return {
        outcome,
        opponentPlayer,
    };
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

function getInjuryOutcome(player) {
    let outcome = '';
    let substitutePlayer = null;
    let chance = Math.random(); // Random chance for simplicity
    if (chance < 0.4) outcome = "minor"; // 40% chance of a minor injury
    else if (chance < 0.6) outcome = "serious"; // 20% chance of a serious injury
    else if (chance < 0.75) outcome = "fake"; // 15% chance of faking an injury
    else if (chance < 0.85) outcome = "temporary"; // 10% chance of a temporary injury
    else if (chance < 0.95) outcome = "rehabilitation"; // 10% chance of requiring rehabilitation but continuing
    else outcome = "stoppage"; // 5% chance of an injury stoppage
    if (outcome === "serious" || outcome === "stoppage" || outcome === "substitution") {
        substitutePlayer = performActionSubstitute(player)
    }
    return { outcome, opponentPlayer: substitutePlayer };
}

function getSubstituteOutcome(player) {
    let outcome = '';
    let substitutePlayer = performActionSubstitute(player);
    if (substitutePlayer) {
        let chance = Math.random(); // Random chance for simplicity
        if (chance < 0.25) outcome = "injury"; // 25% chance of substitution due to injury
        else if (chance < 0.5) outcome = "tactical"; // 25% chance of tactical substitution
        else if (chance < 0.65) outcome = "fatigue"; // 15% chance of fatigue substitution
        else if (chance < 0.8) outcome = "strategic"; // 15% chance of strategic substitution
        else if (chance < 0.9) outcome = "time_wasting"; // 10% chance of time-wasting substitution
        else if (chance < 0.95) outcome = "performance"; // 5% chance of performance-based substitution
        else outcome = ""; // 5% chance of part of multiple substitutions
    }
    return { outcome, opponentPlayer: substitutePlayer };
}

// Function to perform an action and simulate opponent reaction
function performNextAction(currentAction, prevPlayer, currentTimeInSeconds) {
    console.log("performNextAction: ", currentAction, prevPlayer);

    if (currentAction === "intercept") {
        return guestNextPlayer(currentAction, getInterceptOutcome(prevPlayer, currentTimeInSeconds), prevPlayer);
    }


    return null;
}

function guestFollowReaction(currentAction, prevPlayer) {
    const possibleFollowUps = playerActions[currentAction];

    if (possibleFollowUps) {
        const followUpIndex = Math.floor(Math.random() * possibleFollowUps.length);
        const nextAction = possibleFollowUps[followUpIndex];
        const positionsWithAction = Object.keys(validActionsByPosition).filter(position =>
            validActionsByPosition[position].includes(nextAction)
        );
        const randomPosition = positionsWithAction[Math.floor(Math.random() * positionsWithAction.length)];
        const teamPlayers = teamsInMatch[prevPlayer.teamIdx].players.filter(p => p.position_in_match === randomPosition && !p?.is_off);
        const teamPlayer = teamPlayers[Math.floor(Math.random() * teamPlayers.length)];
        return { player: teamPlayer, action: nextAction };
    } else {
        return { player: null, action: null };
    }
}

/**
 * Determines the next player based on the given action, action type, and current player.
 *
 * @function guestNextPlayer
 * @param {string} action - The action to be performed (e.g., "pass", "shoot").
 * @param {string} actionType - The type of action, either "teammate" or "opponent".
 * @param {Object} player - The current player object, containing details such as team index and position.
 * @param {number} player.teamIdx - The index of the player's team (0 or 1).
 * @param {string} player.position_in_match - The current position of the player in the match.
 * @returns {Object|null} A randomly selected player object matching the action and type, or `null` if no players are available.
 */
function guestNextPlayer(action, actionType, player) {
    if (!action) return null;
    const positionsWithAction = Object.keys(validActionsByPosition).filter(position =>
        validActionsByPosition[position].includes(action)
    );
    let players;
    if (actionType === "teammate") {
        players = teamsInMatch[player.teamIdx].players.filter(p => positionsWithAction.includes(p.position_in_match));
    } else {
        players = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => positionsWithAction.includes(p.position_in_match));
    }
    return players[Math.floor(Math.random() * players.length)];
}

function guestOpponentAction(currentAction) {
    const possibleReactions = playerActions[currentAction];

    if (possibleReactions) {
        const reactionIndex = Math.floor(Math.random() * possibleReactions.length);
        const opponentAction = possibleReactions[reactionIndex];

        return { action: opponentAction };
    } else {
        return { action: null };
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

function generatePlayerScore(scoreType) {
    const allowedTypes = ["low", "medium", "high"];
    if (!allowedTypes.includes(scoreType)) {
        throw new Error(`Invalid scoreType. Allowed values are: ${allowedTypes.join(", ")}`);
    }

    switch (scoreType) {
        case "low":
            return 0.1 + Math.random() * 0.9; // Range: 0.1 to 1.0
        case "medium":
            return 1.0 + Math.random(); // Range: 1.0 to 2.0
        case "high":
            return 2.0 + Math.random(); // Range: 2.0 to 3.0
    }
}

function simulatePlayerAction(action, player, currentTime, opponentPlayer) {
    const team1score = document.getElementById("team-1-score");
    const team2score = document.getElementById("team-2-score");

    // Generate random scores for players with updated ranges
    const lowPlayerScore = 0.1 + Math.random() * 0.9; // Range: 0.1 to 1.0
    const mediumPlayerScore = 1.0 + Math.random(); // Range: 1.0 to 2.0
    const highPlayerScore = 2.0 + Math.random(); // Range: 2.0 to 3.0
    teamsInMatch[0].playerSelected = null;
    teamsInMatch[1].playerSelected = null;
    teamsInMatch[player.teamIdx].playerSelected = player.uuid;

    switch (action) {
        case "goal_kick":
            logEvent(
                currentTime,
                "goal_kick",
                player,
                `${player.name} took a goal kick, sending the ball upfield to initiate an attack.`
            );
            break;
        case "press":
            logEvent(
                currentTime,
                "press",
                player,
                `${player.name} pressed high, trying to regain possession and disrupt the goal kick.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "mark_strikers":
            logEvent(
                currentTime,
                "mark_strikers",
                player,
                `${player.name} marked the striker closely, aiming to disrupt the goal kick distribution.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "step_over":
            logEvent(
                currentTime,
                "step_over",
                player,
                `${player.name} performed a step-over to deceive the defender and create space for a potential attack.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "block_pass":
            logEvent(
                currentTime,
                "block_pass",
                player,
                `${player.name} blocked a pass attempt, intercepting the ball to regain possession for the team.`
            );
            break;
        case "distribute_ball":
            logEvent(
                currentTime,
                "distribute_ball",
                player,
                `${player.name} distributed the ball with precision, finding a teammate to start the attack.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "long_ball":
            logEvent(
                currentTime,
                "long_ball",
                player,
                `${player.name} launched a long ball upfield, aiming to bypass the opposition's defense.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "catch_cross":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} caught the cross with confidence, securing possession.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "catch_cross_own_goal":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} disastrously mishandled the cross, resulting in an own goal!`
            );
            player.score = Math.max(player.score - mediumPlayerScore, 1);
            teamsInMatch[player.teamIdx === 0 ? 1 : 0].score++;
            player.own_goals_in_match++;
            break;
        case "catch_cross_success":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} caught the cross cleanly, ending the attack.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "catch_cross_fail":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} missed the cross! The ball is loose in the box.`
            );
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "catch_cross_punch":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} punched the cross away, clearing the danger.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;

        case "punch":
            logEvent(
                currentTime,
                "punch",
                player,
                `${player.name} punched the ball away, clearing the danger from the box.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "clearance":
            logEvent(
                currentTime,
                "clearance",
                player,
                `${player.name} cleared the ball away from danger, ensuring no immediate threats.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "pass":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} made a well-timed pass, setting up a potential attack.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "pass_successful":
            logEvent(currentTime, action, player, `${player.name} made a brilliant pass to their teammate.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "pass_intercepted":
            logEvent(currentTime, action, player, `${player.name}'s pass was intercepted by the opposition.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "pass_blocked":
            logEvent(currentTime, action, player, `${player.name}'s pass was blocked by a defender.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "pass_missed":
            logEvent(currentTime, action, player, `${player.name}'s pass went out of bounds.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "long_pass":
            logEvent(
                currentTime,
                "long_pass",
                player,
                `${player.name} delivered a long pass, attempting to break the opposition's defensive line.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "long_pass_successful":
            logEvent(currentTime, action, player, `${player.name} made a successful long pass to their teammate.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "long_pass_intercepted":
            logEvent(currentTime, action, player, `${player.name}'s long pass was intercepted by the opposition.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "long_pass_blocked":
            logEvent(currentTime, action, player, `${player.name}'s long pass was blocked by a defender.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "long_pass_missed":
            logEvent(currentTime, action, player, `${player.name}'s long pass went out of bounds.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "long_pass_chipped":
            logEvent(currentTime, action, player, `${player.name} executed a brilliant chip pass over the defender.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "dribble":
            logEvent(
                currentTime,
                "dribble",
                player,
                `${player.name} skillfully dribbled past the defender, advancing the ball forward.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "intercept":
            logEvent(
                currentTime,
                "intercept",
                player,
                `${player.name} intercepted the pass, regaining possession for the team.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "tackle":
            logEvent(
                currentTime,
                "tackle",
                player,
                `${player.name} made a crucial tackle, winning the ball back for the team.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "tackle_successful":
            logEvent(currentTime, action, player, `${player.name} successfully tackled the opponent and regained possession.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "tackle_missed":
            logEvent(currentTime, action, player, `${player.name} attempted a tackle but missed, and the opponent retained possession.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "tackle_foul":
            logEvent(currentTime, action, player, `${player.name} committed a foul while attempting a tackle.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "tackle_deflected":
            logEvent(currentTime, action, player, `${player.name}'s tackle deflected the ball into a neutral area.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "tackle_own_goal":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} disastrously deflected the ball into their own net during the tackle!`
            );
            player.score = Math.max(player.score - mediumPlayerScore, 1);
            teamsInMatch[player.teamIdx === 0 ? 1 : 0].score++;
            player.own_goals_in_match++;
            break;
        case "make_decoy_run":
            logEvent(
                currentTime,
                "make_decoy_run",
                player,
                `${player.name} made a decoy run, drawing defenders away from the ball carrier to create space for a teammate.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "overlap":
            // Handle overlap action
            logEvent(
                currentTime,
                "overlap",
                player,
                `${player.name} made an overlapping run, providing an option for the ball carrier.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "cross":
            // Handle cross action
            logEvent(
                currentTime,
                "cross",
                player,
                `${player.name} delivered a dangerous cross into the box, looking for a teammate.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "cut_inside":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} cut inside, looking for a shot or a pass to break the defense.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "cut_inside_successful":
            logEvent(currentTime, action, player, `${player.name} successfully cut inside, creating space for a pass or shot.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "cut_inside_blocked":
            logEvent(currentTime, action, player, `${player.name}'s attempt to cut inside was blocked by a defender.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "cut_inside_fouled":
            logEvent(currentTime, action, player, `${player.name} was fouled while attempting to cut inside.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "cut_inside_lost_possession":
            logEvent(currentTime, action, player, `${player.name} lost possession while trying to cut inside.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "attack_header":
            logEvent(
                currentTime,
                "attack_header",
                player,
                `${player.name} went for a header, challenging for the ball in the air.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "volley":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} took a powerful volley, attempting to score in a spectacular fashion.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "tap_in_goal":
            logEvent(currentTime, action, player, `${player.name} easily taps the ball into the goal to score!`);
            player.score = Math.min(player.score + highPlayerScore, 10);
            player.goals_in_match++;
            teamsInMatch[player.teamIdx].score++;
            break;
        case "tap_in_missed":
            logEvent(currentTime, action, player, `${player.name} missed the tap-in opportunity, sending the ball wide.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "tap_in_saved":
            logEvent(currentTime, action, player, `${player.name} attempted a tap-in, but the goalkeeper made a quick save.`);
            break;
        case "tap_in_blocked":
            logEvent(currentTime, action, player, `${player.name}'s tap-in attempt was blocked by a defender.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
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
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "lay_off":
            // Handle lay off action
            logEvent(
                currentTime,
                "lay_off",
                player,
                `${player.name} laid off the ball to a teammate, setting up a potential shot.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "control":
            // Handle control action
            logEvent(
                currentTime,
                "control",
                player,
                `${player.name} took control of the ball, settling it to prepare for the next move.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "block_shot":
            // Handle block shot action
            logEvent(
                currentTime,
                "block_shot",
                player,
                `${player.name} made a crucial block to stop the shot and keep the team in the game.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "mark":
            // Handle mark action
            logEvent(
                currentTime,
                "mark",
                player,
                `${player.name} marked the opposition closely, preventing them from receiving the ball.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "rebound":
            logEvent(
                currentTime,
                "rebound",
                player,
                `${player.name} capitalized on the rebound, pouncing on the loose ball to score.`
            );
            player.score = Math.min(player.score + highPlayerScore, 10);
            player.goals_in_match++;
            teamsInMatch[player.teamIdx].score++;
            break;
        case "press_defender":
            // Handle press defender action
            logEvent(
                currentTime,
                "press_defender",
                player,
                `${player.name} applied pressure on the defender, forcing a mistake or rushed pass.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "skill_move":
            // Handle skill move action
            logEvent(
                currentTime,
                "skill_move",
                player,
                `${player.name} performed a skill move, dazzling the defender and advancing with the ball.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "shield_ball":
            // Handle shield ball action
            logEvent(
                currentTime,
                "shield_ball",
                player,
                `${player.name} shielded the ball from the defender, maintaining possession under pressure.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "switch_play":
            // Handle switch play action
            logEvent(
                currentTime,
                "switch_play",
                player,
                `${player.name} switched the play to the opposite side, looking for space and an attacking option.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
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
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "run_in_behind":
            // Handle run in behind action
            logEvent(
                currentTime,
                "run_in_behind",
                player,
                `${player.name} made a run in behind the defense, looking to receive a through ball or cross.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "counter_attack":
            // Handle counter attack action
            logEvent(
                currentTime,
                "counter_attack",
                player,
                `${player.name} launched a counter-attack, exploiting the space left by the opposition.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "foul":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} committed a foul, giving away a free kick to the opposition.`
            );
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "foul_penalty_kick_success":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} committed a foul in the penalty area. ${opponentPlayer.name} stepped up and confidently converted the penalty into a goal!`
            );
            player.score = Math.max(player.score - lowPlayerScore, 1);
            teamsInMatch[player.teamIdx === 0 ? 1 : 0].score++;
            opponentPlayer.score = Math.min(opponentPlayer.score + highPlayerScore, 10);
            opponentPlayer.goals_in_match++;
            break;
        case "foul_penalty_kick_fail":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} committed a foul in the box, but the penalty kick was missed!`
            );
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "foul_free_kick_success":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} committed a foul, leading to a free kick. ${opponentPlayer.name} took full advantage and scored a brilliant goal!`
            );
            player.score = Math.max(player.score - lowPlayerScore, 1);
            teamsInMatch[player.teamIdx === 0 ? 1 : 0].score++;
            opponentPlayer.score = Math.min(opponentPlayer.score + highPlayerScore, 10);
            opponentPlayer.goals_in_match++;
            break;
        case "foul_free_kick_fail":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} committed a foul, but the free kick was wasted!`
            );
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "foul_yellow_card":
            player.score = Math.max(player.score - mediumPlayerScore, 1);
            if (!player.yellow_cards_in_match) {
                player.yellow_cards_in_match = 1;
                logEvent(currentTime, action, player, `${player.name} committed a foul and received a yellow card.`);
            } else if (player.yellow_cards_in_match === 1) {
                player.yellow_cards_in_match = 2;
                player.red_cards_in_match = 1;
                player.is_off = true;
                logEvent(currentTime, action, player,
                    `${player.name} committed another foul, resulting in a second yellow card and a subsequent red card. ${player.name} has been sent off the field.`);
            }
            break;
        case "foul_red_card":
            logEvent(currentTime, action, player, `${player.name} committed a serious foul and received a red card, resulting in a sending off.`);
            player.score = Math.max(player.score - highPlayerScore, 1);
            player.red_cards_in_match = 1;
            player.is_off = true;
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
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "recover_ball_success":
            logEvent(currentTime, action, player, `${player.name} successfully recovered the ball and gained possession.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "recover_ball_failed":
            logEvent(currentTime, action, player, `${player.name} attempted to recover the ball but was unsuccessful.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "recover_ball_tackle":
            logEvent(currentTime, action, player, `${player.name} successfully tackled the opponent, but the ball remains contested.`);
            break;
        case "recover_ball_clearance":
            logEvent(currentTime, action, player, `${player.name} cleared the ball under pressure, but it was not a clean recovery.`);
            break;
        case "recover_ball_pressured":
            logEvent(currentTime, action, player, `${player.name} was pressured by the opponent and lost possession.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "contain":
            logEvent(
                currentTime,
                "contain",
                player,
                `${player.name} contained the attacker, preventing them from advancing further.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
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
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "track_runner":
            // Handle track runner action
            logEvent(
                currentTime,
                "track_runner",
                player,
                `${player.name} tracked the opposing runner, staying close to prevent a dangerous move.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "block_cross":
            // Handle block cross action
            logEvent(
                currentTime,
                "block_cross",
                player,
                `${player.name} blocked the cross, preventing any attacking opportunity from the wing.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "block_cross_success":
            logEvent(currentTime, action, player, `${player.name} successfully blocked the cross and denied the attacking team.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "block_cross_deflected":
            logEvent(currentTime, action, player, `${player.name} got a touch on the cross, but it deflected into a dangerous area.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "block_cross_cleared":
            logEvent(currentTime, action, player, `${player.name} blocked the cross, but the ball remains in play and needs to be cleared.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "block_cross_failed":
            logEvent(currentTime, action, player, `${player.name} missed the block, and the cross reached its intended target.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "block_cross_offside":
            logEvent(currentTime, action, player, `${player.name} blocked the cross, but the attacking player was offside.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "block_cross_handled":
            logEvent(currentTime, action, player, `${player.name} handled the ball while attempting to block the cross, resulting in a foul.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "shift_defensive_line":
            logEvent(
                currentTime,
                "shift_defensive_line",
                player,
                `${player.name} shifted the defensive line, ensuring better coverage against the opposition's attack.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "cross":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} intercepted the cross, preventing any threat in the box.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "intercept_cross_success":
            logEvent(currentTime, action, player, `${player.name} successfully intercepted the cross and denied the attack.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "intercept_cross_deflected":
            logEvent(currentTime, action, player, `${player.name} got a touch on the cross, but it deflected into a dangerous area.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "intercept_cross_cleared":
            logEvent(currentTime, action, player, `${player.name} intercepted the cross, but the ball needs to be cleared.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "intercept_cross_failed":
            logEvent(currentTime, action, player, `${player.name} missed the interception, and the cross reached its target.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "intercept_cross_offside":
            logEvent(currentTime, action, player, `${player.name} intercepted the cross, but the attacking player was offside.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "intercept_cross_handled":
            logEvent(currentTime, action, player, `${player.name} handled the ball while attempting to intercept the cross, resulting in a foul.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "challenge_header":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} challenged for the header, competing in the air for possession.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "challenge_header_success":
            logEvent(currentTime, action, player, `${player.name} successfully won the header and gained possession.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "challenge_header_failed":
            logEvent(currentTime, action, player, `${player.name} missed the header, and the opponent won possession.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "challenge_header_foul":
            logEvent(currentTime, action, player, `${player.name} committed a foul while challenging for the header.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "challenge_header_deflected":
            logEvent(currentTime, action, player, `${player.name} touched the ball, but it deflected into a dangerous area.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "challenge_header_cleared":
            logEvent(currentTime, action, player, `${player.name} won the header, but the ball was not controlled and needs to be cleared.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "challenge_header_offside":
            logEvent(currentTime, action, player, `${player.name} won the header, but the attacking player was offside.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "mark_players":
            logEvent(
                currentTime,
                "mark_players",
                player,
                `${player.name} marked the opposition players tightly, denying them space to receive the ball.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "injury":
            logEvent(currentTime, action, player, `${player.name} has sustained an injury during the match and is receiving medical attention. The extent of the injury is yet to be determined.`);
            break;
        case "injury_minor":
            logEvent(currentTime, action, player, `${player.name} sustained a minor injury but is able to continue playing.`);
            break;
        case "injury_serious":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} sustained a serious injury and is unable to continue the game.` +
                (opponentPlayer?.name ? ` Opponent involved: ${opponentPlayer.name}.` : "")
            );
            if (opponentPlayer) {
                teamsInMatch[player.teamIdx].playerSelected = opponentPlayer.uuid;
            }
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
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} is injured, and the match is temporarily stopped for treatment.` +
                (opponentPlayer?.name ? ` Opponent involved: ${opponentPlayer.name}.` : "")
            );
            if (opponentPlayer) {
                teamsInMatch[player.teamIdx].playerSelected = opponentPlayer.uuid;
            }
            break;
        case "injury_substitution":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} is forced to be substituted due to injury.` +
                (opponentPlayer?.name ? ` Opponent involved: ${opponentPlayer.name}.` : "")
            );
            if (opponentPlayer) {
                teamsInMatch[player.teamIdx].playerSelected = opponentPlayer.uuid;
            }
            break;
        case "substitute_injury":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} is substituted due to an injury.` +
                (opponentPlayer?.name ? ` Opponent involved: ${opponentPlayer.name}.` : "")
            );
            if (opponentPlayer) {
                teamsInMatch[player.teamIdx].playerSelected = opponentPlayer.uuid;
            }
            break;
        case "substitute_tactical":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} is substituted for tactical reasons.` +
                (opponentPlayer?.name ? ` Opponent involved: ${opponentPlayer.name}.` : "")
            );
            if (opponentPlayer) {
                teamsInMatch[player.teamIdx].playerSelected = opponentPlayer.uuid;
            }
            break;
        case "substitute_fatigue":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} is substituted due to fatigue.` +
                (opponentPlayer?.name ? ` Opponent involved: ${opponentPlayer.name}.` : "")
            );
            if (opponentPlayer) {
                teamsInMatch[player.teamIdx].playerSelected = opponentPlayer.uuid;
            }
            break;
        case "substitute_strategic":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} is substituted as part of a strategic move.` +
                (opponentPlayer?.name ? ` Opponent involved: ${opponentPlayer.name}.` : "")
            );
            if (opponentPlayer) {
                teamsInMatch[player.teamIdx].playerSelected = opponentPlayer.uuid;
            }
            break;
        case "substitute_time_wasting":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} is substituted to waste time.` +
                (opponentPlayer?.name ? ` Opponent involved: ${opponentPlayer.name}.` : "")
            );
            if (opponentPlayer) {
                teamsInMatch[player.teamIdx].playerSelected = opponentPlayer.uuid;
            }
            break;
        case "substitute_performance":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} is substituted due to poor performance.` +
                (opponentPlayer?.name ? ` Opponent involved: ${opponentPlayer.name}.` : "")
            );
            if (opponentPlayer) {
                teamsInMatch[player.teamIdx].playerSelected = opponentPlayer.uuid;
            }
            break;

        default:
            console.log("no action", action, player, currentTime)
            break;
    }

    redraw();

    team1score.innerText = teamsInMatch[0].score;
    team2score.innerText = teamsInMatch[1].score;
}

function performActionSubstitute(player) {
    if (!player || !player.is_played) {
        return null;
    }

    // Find the team the player belongs to
    const team = teamsInMatch[player.teamIdx];

    if (!team) {
        return null;
    }

    // Find a suitable bench player to substitute
    const benchPlayers = team.bench.filter(benchPlayer =>
        !benchPlayer.is_played && !benchPlayer.is_injury && !benchPlayer?.is_off &&
        (benchPlayer.position_in_match === player.position_in_match ||
            benchPlayer.playable_positions.includes(player.position_in_match))
    );

    let substitutePlayer;
    if (benchPlayers.length === 0) {
        // Determine the filter condition based on the position of the player
        const isGK = player.position_in_match === "GK";
        const filteredBenchPlayers = team.bench.filter(benchPlayer =>
            !benchPlayer.is_played && !benchPlayer.is_injury && !benchPlayer?.is_off &&
            (isGK ? benchPlayer.position_in_match === "GK" : benchPlayer.position_in_match !== "GK")
        );

        substitutePlayer = filteredBenchPlayers[Math.floor(Math.random() * filteredBenchPlayers.length)];
    } else {
        substitutePlayer = benchPlayers[Math.floor(Math.random() * benchPlayers.length)];
    }

    if (!substitutePlayer) return null;

    // Prepare the updated bench player
    const updatedBenchPlayer = {
        ...substitutePlayer,
        is_played: true
    };

    // Update the team's players and bench
    team.players = team.players.map(p =>
        p.uuid === player.uuid ? updatedBenchPlayer : p
    );

    team.bench = team.bench.map(b =>
        b.uuid === substitutePlayer.uuid ? player : b
    );

    return substitutePlayer;
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

$("#btn-start-match").on('click', () => {
    $("#btn-start-match").hide();
    $("#btn-cancel-match").removeClass('d-none');

    // Play the crowd noise in the background
    playCrowdNoise();
    // Start the match simulation
    simulateMatch(teamsInMatch);
})