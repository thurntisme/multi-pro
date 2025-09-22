const url = new URL(window.location.href);
const payload = {match_uuid: url.searchParams.get('uuid')};

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
    return {...team, players, bench};
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

function simulateMatch(teamsInMatch) {
    const team1 = teamsInMatch[0];
    const team2 = teamsInMatch[1];
    const matchTime = 90 * 60; // Total match duration in minutes
    const maxHalfTime = 45 * 60; // Total match duration in minutes
    const maxExtraTime = 10; // Maximum possible extra time in minutes
    const realTimeRate = 10;
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
                    $.ajax({
                        url: apiUrl + '/football-manager/match/record',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            ...payload,
                            players: teamsInMatch[teamsInMatch[0].is_my_team ? 0 : 1].players
                        }),
                        success: function (response) {
                            console.log(response);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error:', error);
                        },
                    });
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
                                        <th class="text-center px-1" style="width: 60px;">
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
                    $.ajax({
                        url: apiUrl + '/football-manager/match/result',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(payload),
                        success: function (response) {
                            if (response.status === "success") {
                                $('#match-form').removeClass('d-none');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error:', error);
                        },
                    });
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
    if (!prevAction || !prevPlayer) {
        const randomTeamIndex = Math.floor(Math.random() * teamsInMatch.length);
        const team = teamsInMatch[randomTeamIndex];
        prevAction = "pass";
        prevPlayer = getRandomPlayer(team, p => p.position_in_match !== "GK" && !p?.is_off);
    }
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
                const short_passing = prevPlayer.attributes.technical.short_passing;
                const short_passing_success = short_passing / 100 + Math.random() > 0.9;

                if (short_passing_success) {
                    description = `${prevPlayer.name} makes a perfectly weighted pass, finding a teammate in stride and advancing the play smoothly.`;
                    prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10);
                } else {
                    description = `${prevPlayer.name} attempts a pass but it's slightly off-target, causing the ball to be intercepted or lose possession.`;
                    prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1);
                }

                nextAction = getRandomNextAction(["long_pass", "dribble", "shoot", "long_shot"]);
                opponentAction = getRandomNextAction(["block", "tackle", "intercept"]);
            }
            break;

        case "long_pass":
            if (prevPlayer) {
                const passQuality = prevPlayer.attributes.technical.long_passing;
                const long_pass_success = passQuality / 100 + Math.random() > 0.9;

                if (long_pass_success) {
                    description = `${prevPlayer.name} delivers a perfectly weighted long pass, splitting the defense and sending the ball deep into the opponent's half to set up an attacking opportunity.`;
                    prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10);
                } else {
                    description = `${prevPlayer.name} attempts a long pass, but it’s slightly overhit or misdirected, giving the opponent a chance to intercept or clear the ball.`;
                    prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1);
                }

                nextAction = getRandomNextAction(["throw_in", "challenge_header", "control_ball", "long_shot", "dribble"]);
                opponentAction = getRandomNextAction(["intercept", "challenge_header", "mark", "press", "clearance"]);
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
            if (prevPlayer) {
                const dribbleSuccess = prevPlayer.attributes.technical.dribbling / 100 + Math.random() > 0.9;

                if (dribbleSuccess) {
                    description = `${prevPlayer.name} takes on defenders with confidence, gliding past them with impressive skill and precision as they surge forward.`;
                    prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10);
                } else {
                    description = `${prevPlayer.name} tries to dribble past the opposition but gets dispossessed as the defender applies pressure.`;
                    prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1);
                }

                nextAction = getRandomNextAction(["pass", "shoot", "cross", "cut_in", "long_pass"]);
                opponentAction = getRandomNextAction(["tackle", "press", "block", "mark", "intercept"]);
            }
            break;

        case "distribute_ball":
            const distributeSuccess = Math.random() > 0.5; // Random chance for successful distribution

            if (distributeSuccess) {
                description = `${prevPlayer.name} calmly distributes the ball to a nearby teammate, making a precise pass to maintain possession and restart the play.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} attempts to distribute the ball, but it's slightly off-target or intercepted, allowing the opponent to regain possession.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["pass", "long_pass", "clearance", "counter_attack"]);
            opponentAction = getRandomNextAction(["press", "block", "intercept", "challenge"]);
            break;

        case "press":
            const pressSuccess = Math.random() > 0.5; // Random chance for successful press

            if (pressSuccess) {
                description = `${prevPlayer.name} leads the team in applying intense pressure, forcing the opponent into a mistake and closing down space quickly.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} attempts to press, but the opponent manages to hold off the pressure or make a quick pass to evade the challenge.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["tackle", "intercept", "regain_possession", "block", "counter_attack"]);
            opponentAction = getRandomNextAction(["pass", "clearance", "dribble", "skill_move", "hold_up_ball"]);
            break;

        case "long_shot":
            description = `${prevPlayer.name} took a powerful long shot, testing the goalkeeper from a distance.`;
            nextAction = getRandomNextAction(["rebound", "pass"]);
            opponentAction = getRandomNextAction(["block_shot"]);
            break;

        case "intercept":
            const interceptSuccess = Math.random() > 0.5; // Random chance for successful intercept

            if (interceptSuccess) {
                description = `${prevPlayer.name} steps in expertly to intercept the opponent's pass, regaining possession with quick reflexes.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} attempts to intercept the ball but misses, allowing the opponent to retain possession.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["pass", "shoot", "cross", "throw_in", "dribble"]);
            opponentAction = getRandomNextAction(["tackle", "press", "block", "throw_in", "regain_possession"]);
            break;

        case "block":
            const blockSuccess = Math.random() > 0.5; // Random chance for successful block

            if (blockSuccess) {
                description = `${prevPlayer.name} makes a crucial block, stopping the ball in its tracks to deny the opposition a chance.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} tries to block the shot, but the ball slips past or is redirected.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["regain_possession", "clearance", "counter_attack", "pass"]);
            opponentAction = getRandomNextAction(["retry_pass", "recover", "switch_play", "shoot"]);
            break;

        case "challenge_header":
            if (Math.random() > 0.5) {
                description = `${prevPlayer.name} leaps high, challenging for the ball with determination in an aerial duel.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} goes for the header but misses the ball or is outjumped by the opponent.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["pass", "flick_on", "control_ball", "throw_in", "shoot"]);
            opponentAction = getRandomNextAction(["press", "mark", "regain_possession", "throw_in", "defend"]);
            break;

        case "shoot":
            description = `${prevPlayer.name} takes a decisive shot at goal, aiming for precision to beat the goalkeeper.`;
            nextAction = getRandomNextAction(["goal", "saved", "rebound", "miss", "assist"]);
            opponentAction = getRandomNextAction(["block", "intercept", "save", "foul", "pressure"]);
            break;

        case "volley":
            const volleySuccess = Math.random() > 0.5; // Random chance for successful volley

            if (volleySuccess) {
                description = `${prevPlayer.name} attempts a stunning volley, striking the ball with perfect timing mid-air, sending it toward goal.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} attempts a volley, but it goes wide or over the bar, missing the target.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["goal", "saved", "rebound", "miss", "assist"]);
            opponentAction = getRandomNextAction(["block", "deflect", "intercept", "foul", "clear"]);
            break;

        case "tackle":
            const tackleSuccess = Math.random() > 0.5; // Random chance for successful tackle

            if (tackleSuccess) {
                description = `${prevPlayer.name} goes in for a crunching tackle, determined to win back possession and break up the play.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} attempts a tackle but misses or is outpaced, allowing the opponent to maintain possession.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["pass", "shoot", "cross", "throw_in", "regain_possession"]);
            opponentAction = getRandomNextAction(["tackle", "press", "block", "throw_in", "dribble"]);
            break;

        case "cross":
            const crossSuccess = Math.random() > 0.5; // Random chance for successful cross

            if (crossSuccess) {
                description = `${prevPlayer.name} delivers a perfectly placed cross, looking to set up a scoring opportunity for a teammate.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} delivers a cross, but it's either too high, too low, or intercepted by the defender.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["attack_header", "volley", "shoot", "pass", "clear"]);
            opponentAction = getRandomNextAction(["block_cross", "clearance", "intercept", "defend"]);
            break;

        case "control_ball":
            const controlSuccess = Math.random() > 0.5; // Random chance for successful ball control

            if (controlSuccess) {
                description = `${prevPlayer.name} expertly controls the ball, showcasing finesse while preparing for the next move.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} attempts to control the ball but miscontrols it, allowing the opponent to regain possession.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["dribble", "pass", "shoot", "clearance", "long_pass"]);
            opponentAction = getRandomNextAction(["press", "tackle", "block", "mark", "intercept"]);
            break;

        case "mark":
            const markSuccess = Math.random() > 0.5; // Random chance for successful marking

            if (markSuccess) {
                description = `${prevPlayer.name} sticks closely to their opponent, cutting off any passing lanes and limiting options, preventing the opposition from advancing.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} tries to mark their opponent but gets beaten, allowing space for the attacker to receive the ball.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["intercept", "block", "tackle", "regain_possession"]);
            opponentAction = getRandomNextAction(["dribble", "skill_move", "pass", "cross"]);
            break;

        case "attack_header":
            const headerSuccess = Math.random() > 0.5; // Random chance for successful header

            if (headerSuccess) {
                description = `${prevPlayer.name} rises above the defenders to meet the cross with a powerful attacking header, sending it toward goal.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} leaps but misses the header, failing to make contact with the cross.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["goal", "assist", "clear", "pass", "counter_attack"]);
            opponentAction = getRandomNextAction(["defend", "block", "intercept", "foul", "clearance"]);
            break;

        case "regain_possession":
            const regainSuccess = Math.random() > 0.5; // Random chance for successful possession regain

            if (regainSuccess) {
                description = `${prevPlayer.name} successfully regains possession and looks to build an attack, shifting momentum in their favor.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} fails to regain possession, allowing the opposition to maintain control of the ball.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["dribble", "pass", "shoot", "clearance"]);
            opponentAction = getRandomNextAction(["press", "tackle", "mark"]);
            break;

        case "block_cross":
            const blockCrossSuccess = Math.random() > 0.5; // Random chance for successful cross block

            if (blockCrossSuccess) {
                description = `${prevPlayer.name} steps in to block the cross, denying a scoring chance and clearing the danger.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} tries to block the cross but is too late, allowing the ball to reach its target.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["clearance", "pass", "long_ball", "intercept"]);
            opponentAction = getRandomNextAction(["cross", "dribble", "cut_inside"]);
            break;

        case "long_ball":
            const longBallSuccess = Math.random() > 0.5; // Random chance for successful long ball

            if (longBallSuccess) {
                description = `${prevPlayer.name} launches a precise long ball forward, aiming to catch the defense off guard and create a counter-attacking opportunity.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} attempts a long ball but overhits it, sending the ball out of play or losing possession.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["challenge_header", "control_ball", "shoot", "pass", "dribble"]);
            opponentAction = getRandomNextAction(["press", "intercept", "challenge_header", "throw_in", "track"]);
            break;

        case "skill_move":
            const skillMoveSuccess = Math.random() > 0.5; // Random chance for successful skill move

            if (skillMoveSuccess) {
                description = `${prevPlayer.name} dazzles with a brilliant skill move, effortlessly evading their marker and driving forward.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} tries a skill move but is easily read by the defender, who closes them down.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["dribble", "pass", "shoot", "cross", "cut_in"]);
            opponentAction = getRandomNextAction(["tackle", "intercept", "block", "regain_possession"]);
            break;

        case "flick_on":
            const flickOnSuccess = Math.random() > 0.5; // Random chance for successful flick on

            if (flickOnSuccess) {
                description = `${prevPlayer.name} flicks the ball on with a subtle yet skillful touch, redirecting it to a teammate in stride.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} tries to flick the ball on, but misjudges the touch, allowing the defender to intercept.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["control_ball", "pass", "shoot", "cross", "dribble"]);
            opponentAction = getRandomNextAction(["press", "intercept", "block", "track"]);
            break;

        case "clearance":
            const clearanceSuccess = Math.random() > 0.5; // Random chance for successful clearance

            if (clearanceSuccess) {
                description = `${prevPlayer.name} clears the ball to relieve pressure on their team, maintaining defensive stability.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} clears the ball poorly, giving possession back to the opponent.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["counter_attack", "intercept", "pass"]);
            opponentAction = getRandomNextAction(["press", "block", "throw_in"]);
            break;

        case "counter_attack":
            const counterAttackSuccess = Math.random() > 0.5; // Random chance for successful counter-attack

            if (counterAttackSuccess) {
                description = `${prevPlayer.name} launches a rapid counter-attack, exploiting the opponent's disorganization and quickly moving forward.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} tries to counter-attack but loses possession due to a poorly timed pass or an interception.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

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
            const reboundSuccess = Math.random() > 0.5; // Random chance for successful rebound

            if (reboundSuccess) {
                description = `${prevPlayer.name} reacts quickly to the rebound, pouncing on the loose ball to create another opportunity.`;
                prevPlayer.score = Math.min(prevPlayer.score + generatePlayerScore('low'), 10); // Increase score for success
            } else {
                description = `${prevPlayer.name} misses the rebound, allowing the opposition to recover the ball.`;
                prevPlayer.score = Math.max(prevPlayer.score - generatePlayerScore('low'), 1); // Decrease score for failure
            }

            nextAction = getRandomNextAction(["shoot", "pass"]);
            opponentAction = getRandomNextAction(["block", "intercept", "press"]);
            break;

        default:
            console.warn("Unknown action type", prevAction);
    }
    if (nextAction && opponentAction && prevAction && prevPlayer) {
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
        if (outcomeDescription) {
            description = outcomeDescription;
        }
    }
    if (followAction && followPlayer && description) {
        logEvent(currentTime, prevAction, prevPlayer, description);
        teamsInMatch[0].playerSelected = null;
        teamsInMatch[1].playerSelected = null;
        teamsInMatch[prevPlayer.teamIdx].playerSelected = prevPlayer.uuid;
    }
    redraw();
    const team1score = document.getElementById("team-1-score");
    const team2score = document.getElementById("team-2-score");
    team1score.innerText = teamsInMatch[0].score;
    team2score.innerText = teamsInMatch[1].score;
    return {followAction, followPlayer}
}

function getOutcomeAction(followAction, followPlayer) {
    if (followAction === "long_shot") {
        return getLongShotOutcome(followPlayer);
    }
    if (followAction === "shoot") {
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
    if (action === "throw_in") {
        return randomPlayerThrowIn(teamsInMatch[teamIdx].players);
    }
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

function getLongShotOutcome(player) {
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

    let totalSkillImpact;
    if (shoot_type === 'long_shot') {
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
        outcomePlayer = randomPlayerKickOff(teamsInMatch[player.teamIdx === 0 ? 1 : 0].players)
    } else if (chance < adjustedGoalChance + adjustedSaveChance) {
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
    } else if (chance < adjustedGoalChance + adjustedSaveChance + adjustedMissChance) {
        outcomeDescription = `${player.name}'s ${shoot_type.replace("_", " ")} missed the target and went over the crossbar.`;
        player.score = Math.max(player.score - generatePlayerScore('low'), 1);
        outcomeAction = "corner_kick";
        outcomePlayer = goalkeeper;
    } else if (chance < adjustedGoalChance + adjustedSaveChance + adjustedMissChance + adjustedBlockedChance) {
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
                teamsInMatch[player.teamIdx].score++;
                outcomeAction = "kick_off";
                outcomePlayer = randomPlayerKickOff(teamsInMatch[player.teamIdx === 0 ? 1 : 0].players)
                break;

            default:
                outcomeDescription += " was blocked by a defender.";
        }
    }

    return {outcomeDescription, outcomeAction, outcomePlayer}
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