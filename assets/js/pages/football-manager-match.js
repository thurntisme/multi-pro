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
    tap_in: ["rebound", "press_defender"], // Capitalize on close chances or press after misses.
    // After recovering the ball, the player can either distribute it quickly or attempt to dribble forward.
    recover_ball: ["distribute_ball", "dribble"],
    long_pass: ["target_teammate", "clear_pressure", "switch_play"],
    rebound: ["shoot", "control_ball", "pass"],
    contain: ["block_pass", "force_wide"],
    block: ["block_shot", "block_pass", "block_cross"],
    block_cross: ["distribute_ball", "pass"],
    counter_attack: ["sprint_forward", "pass"],
    long_pass: ["dribble", "switch_play", "shoot"],
    press_receiver: ["pass", "dribble", "shield_ball"],
    skill_move: ["step_over", "nutmeg", "fake_shot"],
    step_over: ["cut_inside", "pass", "dribble", "shoot"],
    sprint_forward: ["pass", "skill_move"],
    track_runner: ["cross", "pass", "skill_move", "dribble"],
    shift_defensive_line: ["clearance"],
    mark_players: ["block_pass"],
    block_pass: ["long_shot", "long_pass", "pass"],
    press: ["block"],
    make_decoy_run: ["shoot", "overlap"],
    intercept_cross: ["pass", "long_pass", "distribute_ball"]
};

// Define possible opponent reactions
const opponentReactions = {
    // Opponents press to regain possession or tightly mark strikers during a goal kick.
    goal_kick: ["press", "mark_strikers"], // Apply pressure to the defense or mark attackers to disrupt distribution.
    // After a save, opponents pressure the goalkeeper or mark attacking players to limit options.
    save: ["mark_players"], // Prevent quick distribution or cover potential threats.
    // When the goalkeeper catches a cross, opponents can challenge or maintain defensive positioning.
    catch_cross: ["mark"], // Contest the catch or mark nearby players.
    // Following a punch, opponents aim to recover loose balls or block the clearance.
    punch: ["clearance"], // Quickly react to second balls or stop the follow-up clearance.
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
    long_shot: ["block_shot"], // Prevent the shot from reaching goal or rely on the goalkeeper.
    // Skill moves are countered by tackles or containment strategies.
    skill_move: ["tackle", "contain"], // Challenge the player or deny space.
    // Shielding the ball is met with pressure or tackling to regain possession.
    shield_ball: ["tackle", "press"], // Challenge the ball carrier or force mistakes.
    // Switching play causes opponents to shift their defensive line or intercept the pass.
    switch_play: ["shift_defensive_line", "intercept"], // Realign defense or cut off the switch.
    // Through balls prompt interception attempts or blocking the resulting shot.
    through_ball: ["intercept", "block_shot"], // Stop the pass or defend against the attack.
    // Shooting is countered with blocks or goalkeeper saves.
    shoot: ["block"], // Defend the shot or rely on the goalkeeper.
    // Lay-offs are met with interception attempts or immediate pressure.
    lay_off: ["intercept", "press"], // Cut off the pass or apply pressure.
    // Pressing defenders leads to dribbling away or a quick pass to avoid pressure.
    press_defender: ["dribble", "pass"], // Evade pressure or find a teammate.
    // Holding up play is countered by tackles or pressing the ball carrier.
    hold_up_play: ["tackle", "press"], // Regain possession or disrupt the player.
    // Runs in behind trigger defensive tracking or attempts to block the pass.
    run_in_behind: ["track_run", "block_pass"], // Prevent the run or cut off the supply
    // Tap-ins are defended by blocking the shot or making a save.
    tap_in: ["block_shot"], // Stop the close-range attempt or rely on the goalkeeper.
    recover_ball: ["press"],
    long_pass: ["intercept", "mark_receiver"],
    rebound: ["clearance", "block_shot"],
    contain: ["dribble", "switch_play"],
    block: ["press"],
    counter_attack: ["press", "foul"],
    long_pass: ["intercept", "press_receiver"],
    press_receiver: ["block_pass"],
    skill_move: ["intercept", "tackle"],
    step_over: ["intercept", "tackle", "intercept"],
    sprint_forward: ["track_back", "tackle"],
    track_runner: ["block_pass"],
    shift_defensive_line: ["exploit_space", "test_high_line"],
    mark_players: ["make_decoy_run"],
    block_pass: ["press"],
    press: ["dribble", "pass", "shield_ball"],
    make_decoy_run: ["mark"],
    intercept_cross: ["press"],
    block_cross: ["press"],
};

// Define valid actions for each position
const validActionsByPosition = {
    GK: ["goal_kick", "catch_cross", "punch", "clearance", "distribute_ball", "mark_players", "block_shot"],
    LB: ["intercept", "tackle", "overlap", "cross", "cut_inside", "long_ball", "block_cross", "track_runner", "intercept_cross", "contain", "block_shot", "press_receiver", "long_shot"],
    CB: ["press", "mark_strikers", "clearance", "intercept", "tackle", "block_shot", "block_cross", "header", "mark", "challenge_header", "recover_ball", "contain", "press_receiver"],
    RB: ["intercept", "tackle", "overlap", "cross", "cut_inside", "long_ball", "block_cross", "track_runner", "intercept_cross", "contain", "block_shot", "press_receiver", "long_shot"],
    LM: ["cross", "dribble", "cut_inside", "pass", "long_shot", "skill_move", "block_cross", "press_receiver"],
    CDM: ["mark_strikers", "block_pass", "intercept", "tackle", "pass", "long_ball", "shield_ball", "switch_play", "shift_defensive_line", "contain", "block_shot", "press_receiver", "header", "challenge_header", "long_shot"],
    CM: ["pass", "block_pass", "dribble", "long_shot", "through_ball", "tackle", "intercept", "counter_attack", "contain", "block_shot", "press_receiver", "header", "challenge_header"],
    CAM: ["rebound", "step_over", "block_pass", "dribble", "pass", "through_ball", "shoot", "cut_inside", "skill_move", "block_shot", "press_receiver", "long_shot"],
    RM: ["rebound", "cross", "dribble", "cut_inside", "pass", "long_shot", "skill_move", "block_cross", "press_receiver"],
    LW: ["rebound", "step_over", "cross", "dribble", "cut_inside", "pass", "shoot", "skill_move", "block_shot"],
    CF: ["rebound", "step_over", "shoot", "lay_off", "pass", "press_defender", "header", "dribble", "hold_up_play"],
    ST: ["rebound", "step_over", "shoot", "header", "hold_up_play", "press_defender", "run_in_behind", "tap_in", "block_shot", "press_receiver"],
    RW: ["rebound", "step_over", "cross", "dribble", "cut_inside", "pass", "shoot", "skill_move", "block_shot"],
};

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
                    let action = null;
                    let player = null;
                    if (!prevAction) {
                        // Simulate an action
                        const team = Math.random() < 0.5 ? team1 : team2;
                        const teamPlayers = team.players.filter(player => !player?.is_off);
                        player = teamPlayers[Math.floor(Math.random() * teamPlayers.length)];
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
                        const {outcomeAction, opponentPlayer} = performOutcomeAction(action, player);
                        console.log({player: player.name, outcomeAction, opponentPlayer: opponentPlayer.name ?? ''})
                        simulatePlayerAction(outcomeAction, player, currentTimeInSeconds, opponentPlayer);
                    }
                }
            }

            totalMatchTime++;
            currentTimeInSeconds++;
        }
        // Increment the total time
        matchTimeInSeconds++;
    }, 10); // Delay of 1 second per iteration
}

function getActionFromPlayer(player, currentTimeInSeconds) {
    const {position_in_match} = player;

    // Select possible actions based on the player's position
    let actions = validActionsByPosition[position_in_match];

    // Randomly select an action from the available actions
    const randAction = Math.random();
    let action = actions[Math.floor(randAction * actions.length)];

    // Determine if a substitution is possible (after 45 minutes, for example)
    const isPossibleSub = currentTimeInSeconds > 45 * 60;

    // Check if the randomly chosen action should be a special event (injury, substitute, or foul)
    // if (randAction < 0.2) { // 20% chance for special actions
    //     if (randAction < 0.01) {  // 1% chance for injury
    //         action = "injury";
    //     } else if (randAction < 0.1 && isPossibleSub) {  // 9% chance for substitute (only after 45 mins)
    //         action = "substitute";
    //     } else {  // 10% chance for foul
    //         action = "foul";
    //     }
    // }
    if (randAction < 0.2 && isPossibleSub) { 
        action = "substitute";
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
            const opponentPlayers = teamsInMatch[prevPlayer.teamIdx === 0 ? 1 : 0].players.filter(p => p.position_in_match === randomPosition && !p?.is_off);
            const opponentPlayer = opponentPlayers[Math.floor(Math.random() * opponentPlayers.length)];

            return {player: opponentPlayer, action: opponentAction};
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
        const teamPlayers = teamsInMatch[prevPlayer.teamIdx].players.filter(p => p.position_in_match === randomPosition && !p?.is_off);
        const teamPlayer = teamPlayers[Math.floor(Math.random() * teamPlayers.length)];
        return {player: teamPlayer, action: nextAction};
    } else {
        return {player: null, action: null};
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

function performOutcomeAction(action, player) {
    let outcome;
    let opponentPlayer = '';

    switch (action) {
        case "shoot":
            const shootOutcome = getShootOutcome(player);
            outcome = shootOutcome.outcome;
            opponentPlayer = shootOutcome.opponentPlayer;
            break;
        case "long_shot":
            const longShotOutcome = getShootOutcome(player);
            outcome = longShotOutcome.outcome;
            opponentPlayer = longShotOutcome.opponentPlayer;
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
            const foulOutcome = getFoulOutcome(player);
            outcome = foulOutcome.outcome;
            opponentPlayer = foulOutcome.opponentPlayer;
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
            const injuryOutcome = getInjuryOutcome(player);
            outcome = injuryOutcome.outcome;
            opponentPlayer = injuryOutcome.opponentPlayer;
            break;
        case "substitute":
            const substituteOutcome = getSubstituteOutcome(player);
            outcome = substituteOutcome.outcome;
            opponentPlayer = substituteOutcome.opponentPlayer;
            break;
        case "tap_in":
            outcome = getHeaderOutcome(player);
            break;
        default:
            outcome = '';
            // console.log(action)
            break;
    }

    return {
        outcomeAction: `${action}${outcome ? `_${outcome}` : ""}`,
        opponentPlayer,
    };
}

function getShootOutcome(player) {
    const goalkeeper = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.find(p => p.position_in_match === "GK");
    // Base probabilities
    let baseGoalChance = 0.2;
    let baseMissChance = 0.2;
    let baseBlockedChance = 0.3;
    let baseSaveChance = 0.3;

    // Influence from player's attributes
    const finishingImpact = player.attributes.technical.finishing / 100; // Scales with finishing skill
    const composureImpact = player.attributes.mental.composure / 100; // Adds calmness under pressure
    const shotPowerImpact = player.attributes.technical.shot_power / 100; // Harder shots are less likely to be saved
    const balanceImpact = player.attributes.physical.balance / 100; // Stability while shooting
    const totalSkillImpact = (finishingImpact + composureImpact + shotPowerImpact + balanceImpact) / 4;

    // Adjust for goalkeeping attributes
    const goalkeeperSkill = (goalkeeper.attributes.goalkeeping.reflexes + goalkeeper.attributes.goalkeeping.shot_stopping + goalkeeper.attributes.goalkeeping.handling) / 300;

    // Calculate adjusted probabilities
    const goalChance = baseGoalChance + totalSkillImpact - goalkeeperSkill / 2; // Better goalkeepers reduce goal chance
    const saveChance = baseSaveChance + goalkeeperSkill - totalSkillImpact / 2; // Better shooters reduce save chance
    const missChance = baseMissChance - totalSkillImpact / 2; // Skilled players miss less
    const blockedChance = baseBlockedChance - totalSkillImpact / 3; // Skilled players avoid blocks better

    // Ensure probabilities add up to 1
    const total = goalChance + saveChance + missChance + blockedChance;
    const adjustedGoalChance = goalChance / total;
    const adjustedSaveChance = saveChance / total;
    const adjustedMissChance = missChance / total;

    let outcome;
    // Random outcome based on probabilities
    const chance = Math.random();
    if (chance < adjustedGoalChance) outcome = "goal";
    else if (chance < adjustedGoalChance + adjustedSaveChance) outcome = "save";
    else if (chance < adjustedGoalChance + adjustedSaveChance + adjustedMissChance) outcome = "miss";
    else outcome = "blocked";

    return {
        outcome,
        opponentPlayer: goalkeeper
    }
}

function getLongShotOutcome(player) {
    const goalkeeper = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.find(p => p.position_in_match === "GK");
    const defenders = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => p.position_in_match === "CB");
    // Select a random defender if any are present
    let defensiveImpact = 0; // Default if no defenders
    if (defenders.length > 0) {
        const randomDefender = defenders[Math.floor(Math.random() * defenders.length)];
        defensiveImpact = (randomDefender.attributes.mental.positioning + randomDefender.attributes.mental.anticipation + randomDefender.attributes.mental.bravery) / 300;
    }

    // Base probabilities for long shots
    let baseGoalChance = 0.1;   // Base 10% chance of scoring
    let baseMissChance = 0.3;   // Base 30% chance of missing
    let baseBlockedChance = 0.4; // Base 40% chance of being blocked
    let baseSaveChance = 0.2;   // Base 20% chance of a save

    // Influence from player's attributes
    const longShotsImpact = player.attributes.technical.long_shots / 100; // Skill in long shots
    const shotPowerImpact = player.attributes.technical.shot_power / 100; // Harder shots reduce save/block chances
    const composureImpact = player.attributes.mental.composure / 100; // Staying calm under pressure
    const totalSkillImpact = (longShotsImpact + shotPowerImpact + composureImpact) / 3;

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

    let outcome;
    // Random outcome based on probabilities
    const chance = Math.random();
    if (chance < adjustedGoalChance) outcome = "goal";
    else if (chance < adjustedGoalChance + adjustedSaveChance) outcome = "save";
    else if (chance < adjustedGoalChance + adjustedSaveChance + adjustedMissChance) outcome = "miss";
    else if (chance < adjustedGoalChance + adjustedSaveChance + adjustedMissChance + adjustedBlockedChance) outcome = "blocked";

    return {
        outcome,
        opponentPlayer: goalkeeper
    }
}

function getCatchCrossOutcome(player) {
    let opponentPlayer;
    const attackers = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => ["LB", "RB", "LM", "RM", "LW", "RW"].includes( p.position_in_match));
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
    const opponentDefenders = teamsInMatch[player.teamIdx === 0 ? 1 : 0].players.filter(p => !["GK", "CB"].includes( p.position_in_match));
    if (opponentDefenders.length > 0) {
        const randomDefender = opponentDefenders[Math.floor(Math.random() * opponentDefenders.length)];
        defensiveImpact = (randomDefender.attributes.mental.anticipation + randomDefender.attributes.mental.positioning) / 200;
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
    const chance = Math.random();
    if (chance < successChance) {
        return "successful"; // Pass reaches the target
    } else if (chance < successChance + interceptedChance) {
        return "intercepted"; // Opponent intercepts the pass
    } else if (chance < successChance + interceptedChance + blockedChance) {
        return "blocked"; // Pass is blocked by an opponent
    } else {
        return "missed"; // Pass goes out of bounds or off-target
    }
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
    let chance = Math.random(); // Generate a random chance
    if (chance < 0.45) return "successful"; // 45% chance of successful interception
    else if (chance < 0.7) return "missed"; // 25% chance of missing the interception
    else if (chance < 0.85) return "deflection"; // 15% chance of deflecting the ball
    else if (chance < 0.95) return "foul"; // 10% chance of committing a foul
    else return "own_goal"; // 5% chance of an own goal
}

function getHeaderOutcome(player) {
    let chance = Math.random(); // Generate a random chance
    if (chance < 0.35) return "goal"; // 35% chance of scoring a goal
    else if (chance < 0.6) return "saved"; // 25% chance of the goalkeeper making a save
    else if (chance < 0.8) return "miss"; // 20% chance of missing the target
    else return "blocked"; // 20% chance of a defender blocking the header
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
    return {outcome, opponentPlayer: substitutePlayer};
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
    return {outcome, opponentPlayer: substitutePlayer};
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
        case "header_goal":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} leapt high and delivered a powerful header into the back of the net!`
            );
            player.score = Math.min(player.score + highPlayerScore, 10);
            player.goals_in_match++;
            teamsInMatch[player.teamIdx].score++;
            break;
        case "header_saved":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} aimed a header at the goal, but the goalkeeper made a stunning save!`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "header_miss":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} tried a header but missed the target completely.`
            );
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "header_blocked":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} directed a header toward goal, but a defender blocked it with a brave effort!`
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
        case "dribble_successful":
            logEvent(currentTime, action, player, `${player.name} dribbled past the defender successfully.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "dribble_tackled":
            logEvent(currentTime, action, player, `${player.name} was tackled and lost the ball.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "dribble_fouled":
            logEvent(currentTime, action, player, `${player.name} was fouled while attempting a dribble.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "dribble_lose_control":
            logEvent(currentTime, action, player, `${player.name} lost control of the ball during the dribble.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
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
        case "intercept_successful":
            logEvent(currentTime, action, player, `${player.name} successfully intercepted the ball and regained possession.`);
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "intercept_own_goal":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} attempted an interception but accidentally deflected the ball into their own net!`
            );
            player.score = Math.max(player.score - mediumPlayerScore, 1);
            teamsInMatch[player.teamIdx === 0 ? 1 : 0].score++;
            player.own_goals_in_match++;
            break;
        case "intercept_missed":
            logEvent(currentTime, action, player, `${player.name} attempted to intercept but missed, allowing the ball to continue.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "intercept_deflection":
            logEvent(currentTime, action, player, `${player.name} got a touch on the ball, causing a deflection but no possession.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "intercept_foul":
            logEvent(currentTime, action, player, `${player.name} fouled the opponent while trying to intercept.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
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
        case "header":
            logEvent(
                currentTime,
                "header",
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
        case "volley_goal":
            logEvent(currentTime, action, player, `${player.name} executed a stunning volley to score a spectacular goal!`);
            player.score = Math.min(player.score + highPlayerScore, 10);
            player.goals_in_match++;
            teamsInMatch[player.teamIdx].score++;
            break;
        case "volley_saved":
            logEvent(currentTime, action, player, `${player.name} struck a volley on target, but the goalkeeper made an excellent save.`);
            break;
        case "volley_missed":
            logEvent(currentTime, action, player, `${player.name} attempted a volley but missed the target.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "volley_blocked":
            logEvent(currentTime, action, player, `${player.name}'s volley was blocked by a defender.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "volley_rebound":
            logEvent(currentTime, action, player, `${player.name}'s volley was deflected, and the ball is now in play as a rebound.`);
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "tap_in":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} calmly tapped the ball into the net to finish off a great team move.`
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
        case "shoot_goal":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} took a shot and scored a fantastic goal!`
            );
            player.score = Math.min(player.score + highPlayerScore, 10);
            player.goals_in_match++;
            opponentPlayer.score = Math.max(opponentPlayer.score - mediumPlayerScore, 1);
            teamsInMatch[player.teamIdx].score++;
            break;
        case "shoot_save":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} took a shot, but ${opponentPlayer.name} made a brilliant save!`
            );
            opponentPlayer.score = Math.min(opponentPlayer.score + lowPlayerScore, 10);
            break;
        case "shoot_miss":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} took a shot, but it went wide of the goal.`
            );
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "shoot_blocked":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name}'s shot was blocked by a defender, stopping a potential goal.`
            );
            player.score = Math.max(player.score - lowPlayerScore, 1);
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
        case "long_shot":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} took a powerful long shot, testing the goalkeeper from a distance.`
            );
            player.score = Math.min(player.score + lowPlayerScore, 10);
            break;
        case "long_shot_goal":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} took a long shot and scored a stunning goal from distance!`
            );
            player.score = Math.min(player.score + highPlayerScore, 10);
            player.goals_in_match++;
            teamsInMatch[player.teamIdx].score++;
            break;
        case "long_shot_save":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name} took a long shot, but the goalkeeper made a remarkable save!`
            );
            opponentPlayer.score = Math.min(opponentPlayer.score + lowPlayerScore, 10);
            break;
        case "long_shot_miss":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name}'s long shot missed the target and went over the crossbar.`
            );
            player.score = Math.max(player.score - lowPlayerScore, 1);
            break;
        case "long_shot_blocked":
            logEvent(
                currentTime,
                action,
                player,
                `${player.name}'s long shot was blocked by a defender, stopping a potential threat.`
            );
            player.score = Math.max(player.score - lowPlayerScore, 1);
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
        case "intercept_cross":
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
            console.log(action, player, currentTime)
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