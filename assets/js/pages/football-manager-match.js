const teamsInMatch = groupTeams.map((team, teamIdx) => {
    const playerColor = teamIdx === 0 ? homeTeamColor : awayTeamColor;
    const players = generateFormation(team.formation).map((pos, idx) => {
        return {
            position_in_match: pos.posName,
            score: 5,
            playerColor,
            ...team.players[idx],
        };
    });
    const bench = team.bench.map((player) => {
        return {...player, position_in_match: player.best_position, playerColor}
    });
    return {...team, players, bench};
});

const redraw = () => {
    renderTeamInFitch(teamsInMatch, {circleRadius: 8, isDisplayScore: true, isDisplayName: true, isTeamInMatch: true});
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
    return filterPlayers[Math.floor(Math.random() * filterPlayers.length)];
}

function simulateAction(team, player, team1, team2, currentTime) {
    const GK_actions = [
        "save", // Stopping a shot on goal
        "catch", // Catching the ball, typically from crosses or shots
        "punch", // Punching the ball away when a catch is not possible
        "goal_kick", // Taking a goal kick to restart play from the goal area
        "handling", // Handling the ball cleanly, avoiding handball violations
        "reflexes", // Quick reflexes to react to sudden shots or passes
        "kicking", // Kicking the ball to distribute it to teammates
        "throwing", // Throwing the ball to a teammate for a fast counter-attack or restart
        "1v1", // Dealing with one-on-one situations, where the goalkeeper faces a direct attacker
        "command_of_area", // Organizing and controlling the area around the goal, especially during set pieces
        "rushing_out", // Rushing out to challenge an attacker before they can shoot on goal
        "communication", // Communicating with defenders and directing them during play
        "shot_stopping", // General shot-stopping ability, reacting to any kind of shot
        "distribution", // Distributing the ball, whether long passes, goal kicks, or punts to teammates
        "sweeping" // Acting as a sweeper, clearing balls or stopping attackers outside the penalty area
    ];
    const LRB_actions = [
        "tackle", // Defending the ball by making a tackle to dispossess the opponent
        "clearance", // Clearing the ball out of the defensive area to relieve pressure
        "heading", // Winning headers, especially from crosses or long balls
        "cross", // Delivering a cross into the box from wide positions
        "shoot", // Attempting a shot at goal from open play or set pieces
        "intercept", // Intercepting passes from the opposition to regain possession
        "offside", // Avoiding being caught offside and ensuring proper positioning
        "passing", // Passing the ball to teammates to move the ball upfield
        "dribbling", // Dribbling the ball past opponents to maintain possession or progress the attack
        "crossing", // Delivering accurate crosses into the box for teammates to score
        "finishing", // Attempting to score from opportunities, especially inside the box
        "technique", // General technical ability in passing, dribbling, and ball control
        "volleys", // Striking the ball in mid-air, often used for shots or clearances
        "curve", // Using the inside of the foot to bend the ball for passes, crosses, or shots
        "ball_control", // Maintaining possession of the ball with close, controlled touches
        "tracking_back", // Tracking opposing wingers or attackers and helping with defense
        "overlap", // Overlapping the winger to provide additional attacking support down the flank
        "marking", // Marking opposing players, especially during set pieces or crosses
        "positioning", // Maintaining proper positioning to both defend and support the attack
        "cut_inside", // Dribbling inside from the wing to create shooting or passing opportunities
        "anticipation", // Anticipating the opponent's passes or runs to intercept or block them
        "support_attack", // Moving up the pitch to support attacking plays and create width
        "defensive_heading", // Using headers defensively to clear the ball from dangerous situations
        "aggression" // Playing with aggression to win duels, tackles, and challenge opponents
    ];
    const CB_actions = [
        "tackle", // Winning the ball back from an opponent through a strong or well-timed tackle
        "clearance", // Clearing the ball from the defensive area to relieve pressure
        "heading", // Winning aerial duels to clear or direct the ball, especially during crosses or long balls
        "block", // Blocking shots, passes, or crosses to prevent scoring opportunities
        "intercept", // Intercepting passes to regain possession for the team
        "strength", // Using physical strength to win duels, challenge attackers, and hold off opponents
        "positioning", // Maintaining the correct defensive position to prevent attackers from getting in behind
        "decision_making", // Making quick and effective decisions to choose the best defensive option
        "anticipation", // Reading the game and anticipating passes or movements to intercept or challenge
        "composure", // Remaining calm under pressure to make clear decisions and accurate passes
        "concentration", // Staying focused on the ball and marking assignments for the full duration of the match
        "leadership", // Organizing the defense, communicating effectively, and directing teammates
        "cover", // Providing cover for teammates by positioning yourself to deal with attacking threats
        "tracking", // Tracking the runs of opposing attackers, particularly in the box or on the counter-attack
        "passing", // Passing the ball out from the back to transition to attack
        "blocking_crosses", // Positioning to block or clear crosses into the box
        "clearance_under_pressure", // Making clearances when under pressure from opponents
        "marking", // Tight marking of opposing attackers, especially during set pieces or dangerous situations
        "tactical_fouling", // Committing a foul in a tactical manner to break up an opposition counter-attack or dangerous play
        "communication", // Communicating with teammates to maintain defensive shape and organization
    ];
    const LRM_actions = [
        "dribble", // Dribbling past opponents to maintain possession or create attacking opportunities
        "cross", // Delivering crosses into the box from wide positions to assist strikers
        "intercept", // Intercepting passes from the opposition to regain possession for your team
        "offside", // Avoiding offside positions and timing runs correctly to receive passes
        "passing", // Passing the ball accurately to teammates, either short or long-range
        "crossing", // Delivering accurate crosses from the wing to create goal-scoring chances
        "flair", // Demonstrating creativity and skill in attacking situations, often with tricks or unpredictable moves
        "technique", // General technical ability in controlling the ball, passing, and shooting
        "shoot", // Taking shots at goal, either from distance or in the box
        "ball_control", // Keeping the ball under control, especially in tight spaces or while dribbling
        "tracking_back", // Tracking back to help defend and support the team when losing possession
        "cut_inside", // Dribbling inside from the wing to create shooting or passing opportunities
        "movement", // Making intelligent runs into space to receive passes or stretch the opposition
        "stamina", // Maintaining energy levels throughout the match, especially on the wing where running is key
        "vision", // Seeing the game and making passes or decisions based on a broader understanding of play
        "1v1", // Winning one-on-one duels against defenders with skill or strength
        "counter_attack", // Transitioning quickly from defense to attack when possession is won
        "positional_awareness", // Understanding the position of teammates and opponents to make effective runs or passes
    ];
    const CDM_actions = [
        "intercept", // Intercepting passes to regain possession for the team
        "shield_ball", // Shielding the ball from opponents to retain possession and slow down attacks
        "tackle", // Winning the ball back from an opponent through a well-timed or strong tackle
        "clearance", // Clearing the ball from the defensive area, especially under pressure
        "decision_making", // Making quick and effective decisions on whether to pass, tackle, or move
        "work_rate", // Demonstrating high energy levels and persistence throughout the match, especially in defensive actions
        "vision", // Seeing the game and making the right passes, even under pressure
        "teamwork", // Working well with teammates to organize defensive lines or launch counter-attacks
        "passing", // Passing the ball accurately to start offensive moves or relieve pressure
        "positioning", // Maintaining the right position to break up attacks and cover spaces
        "marking", // Marking opposition players, especially in midfield and defensive areas
        "tracking", // Tracking attacking runs from opposition players to stop counter-attacks
        "pressing", // Applying pressure to the ball carrier to force mistakes or win the ball back
        "distribution", // Distributing the ball effectively to attack or switch play
        "cut_out_pass", // Cutting out or intercepting passes before they reach the target player
        "aerial_duels", // Winning aerial challenges, especially during set pieces or long balls
        "composure", // Staying calm under pressure, making simple decisions to maintain possession
        "counter_attack", // Starting or supporting quick counter-attacks when possession is regained
        "tactical_fouling", // Committing a foul at a strategic moment to disrupt the opposition's flow
    ];
    const CM_actions = [
        "dribble", // Dribbling past opponents to maintain possession or drive forward
        "key_pass", // Creating goal-scoring opportunities by delivering key passes to teammates
        "tackle", // Winning the ball back through strong or well-timed tackles
        "intercept", // Intercepting passes to regain possession for the team
        "clearance", // Clearing the ball from dangerous areas when under pressure
        "passing", // General passing to move the ball across the field, either short or long-range
        "positioning", // Maintaining the correct positioning to support defense and attack
        "composure", // Staying calm under pressure to make better decisions with the ball
        "anticipation", // Anticipating the opponent's moves to intercept or block passes
        "ball_control", // Controlling the ball smoothly, especially under pressure
        "vision", // Seeing the game and making decisions based on the broader flow of play
        "work_rate", // Showing high energy levels, both in attack and defense
        "creativity", // Demonstrating creative playmaking, often with unpredictable or inventive passes
        "passing_range", // Ability to make both short and long passes effectively
        "stamina", // Maintaining performance levels throughout the match, especially in a box-to-box role
        "movement", // Making intelligent runs to receive passes or support attacking plays
        "teamwork", // Working effectively with teammates to build plays and maintain balance in the midfield
        "defensive_contribution", // Helping the defense by tracking back or making defensive tackles
        "crossing", // Delivering crosses from central areas to assist attacking players
        "set_piece_delivery", // Taking free kicks or corners to create goal-scoring opportunities
        "finishing", // Scoring goals from central areas or arriving late into the box
    ];
    const CAM_actions = [
        "key_pass", // Delivering passes that create clear goal-scoring opportunities for teammates
        "shoot", // Taking shots on goal, either from distance or in the box
        "offside", // Timing runs to stay onside and receive passes without being caught by the defenders
        "chip_shot", // Using a delicate chip to lift the ball over the goalkeeper or defenders
        "vision", // Seeing the game and making intelligent decisions based on the flow of play
        "decision_making", // Making the right decisions under pressure, whether to pass, shoot, or dribble
        "flair", // Displaying creativity and flair with unpredictable moves, tricks, or passes
        "technique", // High technical ability in controlling, passing, and shooting the ball
        "finishing", // Scoring goals through accurate and composed finishing in front of goal
        "passing", // General passing to move the ball and set up attacking plays
        "dribble", // Dribbling past defenders to create space or get into goal-scoring positions
        "ball_control", // Maintaining tight control of the ball while under pressure from defenders
        "crossing", // Delivering accurate crosses from central areas to assist attackers
        "movement", // Making intelligent runs into the box or to receive passes in dangerous areas
        "link_up_play", // Combining with teammates to build attacking moves and create chances
        "set_piece_delivery", // Taking free kicks, corners, or other set pieces to create scoring opportunities
        "aerial_duels", // Winning aerial duels, particularly when crossing or defending set pieces
        "creativity", // Producing unexpected moments of brilliance, such as through through-balls or skillful moves
        "pressing", // Pressing the opposition when out of possession to regain the ball high up the field
    ];
    const LRW_actions = [
        "cross", // Delivering accurate crosses from the left wing to assist strikers
        "dribble", // Dribbling past defenders to create space or get into dangerous areas
        "shoot", // Taking shots at goal, especially from wide areas or cutting inside
        "offside", // Timing runs correctly to avoid being caught offside
        "flair", // Demonstrating creativity and skill with tricks, flicks, or unpredictable moves
        "technique", // High technical ability in ball control, passing, and shooting
        "passing", // Passing the ball effectively, including through balls or quick passes to teammates
        "finishing", // Scoring goals from crosses, through balls, or individual efforts
        "ball_control", // Keeping the ball under control, especially in tight spaces or when under pressure
        "cut_inside", // Dribbling inside from the wing to take a shot or make a pass
        "movement", // Making intelligent runs to stretch the defense or receive passes
        "tracking_back", // Tracking back to help defend and support the team when out of possession
        "1v1", // Winning duels against defenders, either through skill or speed
        "stamina", // Maintaining energy levels throughout the match, especially for a winger who needs to cover a lot of ground
        "aerial_duels", // Winning aerial challenges, particularly when the ball is played into the box or during set pieces
        "passing_range", // Making accurate and varied passes, from short to long-range passes to switch play
        "positional_awareness", // Being in the right place at the right time to receive the ball or support the attack
        "counter_attack", // Starting or supporting quick counter-attacks when possession is regained
        "pressing", // Applying pressure to the opponent when out of possession to win the ball back
    ];
    const CF_actions = [
        "shoot", // Taking shots on goal, whether from inside or outside the box
        "offside", // Timing runs correctly to avoid being caught offside
        "penalty", // Taking and converting penalties from the spot
        "heading", // Winning headers, particularly in attacking situations or from crosses
        "chip_shot", // Taking delicate chip shots over the goalkeeper or defenders
        "positioning", // Being in the right place at the right time to receive passes or finish chances
        "finishing", // Composed and accurate finishing to convert chances into goals
        "decision_making", // Making the right decisions, whether to shoot, pass, or dribble
        "technique", // High technical ability in ball control, passing, and shooting
        "link_up_play", // Combining with teammates to create goal-scoring opportunities and maintain possession
        "hold_up_play", // Holding the ball up to bring others into play, especially when under pressure from defenders
        "movement", // Making intelligent runs to get into goal-scoring positions or create space
        "pressing", // Pressing high up the field to regain possession and disrupt the opposition's buildup
        "aerial_duels", // Competing in the air for crosses, long balls, and set pieces
        "counter_attack", // Starting or supporting quick counter-attacks, exploiting spaces left by the opposition
        "stamina", // Maintaining energy levels to keep up the intensity throughout the game
        "passing", // Quick, accurate passes to set up teammates or retain possession in attacking situations
        "creativity", // Showing creativity to break down defenses with unexpected movements or passes
    ];
    const ST_actions = [
        "shoot", // Taking shots on goal, either from inside the box or from long range
        "offside", // Timing runs to avoid being caught offside
        "penalty", // Taking and converting penalties from the spot
        "heading", // Winning headers to score goals or challenge for aerial duels
        "chip_shot", // Using a delicate chip shot over the goalkeeper or defenders
        "finishing", // Composed and accurate finishing to convert chances into goals
        "positioning", // Being in the right place at the right time to receive passes or finish chances
        "dribbling", // Taking on defenders with the ball and creating goal-scoring opportunities
        "composure", // Keeping calm and making the right decisions in front of goal or under pressure
        "anticipation", // Reading the game, predicting where the ball will go, and reacting accordingly
        "link_up_play", // Combining with teammates to create goal-scoring opportunities or to hold possession
        "hold_up_play", // Holding the ball up to bring teammates into the attack, especially when under pressure
        "movement", // Making intelligent runs to break defensive lines and receive the ball in dangerous areas
        "pressing", // Pressuring defenders and goalkeepers to regain possession high up the field
        "aerial_duels", // Competing for headers, especially from crosses or set pieces
        "counter_attack", // Supporting or initiating counter-attacks with speed and decisiveness
        "stamina", // Maintaining energy levels to keep the pressure on throughout the game
        "passing", // Quick and accurate passing to set up teammates or retain possession in attacking situations
        "creativity", // Creating unexpected moments of brilliance, such as flicks, passes, or shots
    ];
    // Define valid actions for each position
    const validActionsByPosition = {
        GK: GK_actions,
        LB: LRB_actions,
        CB: CB_actions,
        RB: LRB_actions,
        LM: LRM_actions,
        CDM: CDM_actions,
        CM: CM_actions,
        CAM: CAM_actions,
        RM: LRM_actions,
        LW: LRW_actions,
        CF: CF_actions,
        ST: ST_actions,
        RW: LRW_actions,
    };

    const {position_in_match} = player;
    const opposingTeam = team === team1 ? team2 : team1;
    const goalkeeper = getRandPlayerFromTeam("save", opposingTeam);
    // Randomly select a valid action for the chosen position
    let actions = validActionsByPosition[position_in_match];
    const randAction = Math.random();
    let action = actions[Math.floor(randAction * actions.length)];
    const isPossibleSub = currentTime > 45 * 60;
    if (randAction < 0.2) {
        if (randAction < 0.01) {
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

    const lowPlayerScore = Math.random(); // Range: 0 to 1.0
    const mediumPlayerScore = Math.random() * (2.0 - 1.0) + 1.0; // Range: 1.0 to 2.0
    const highPlayerScore = Math.random() * (2.5 - 2.0) + 2.0; // Range: 2.0 to 2.5
    const veryHighPlayerScore = Math.random() * (3.0 - 2.5) + 2.5; // Range: 2.5 to 3.0

    action = 'shoot';
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
        const attacker = {...player}; // The player attempting the shot
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

        console.log(attemptOutcome);
        // Return true if the shot results in a goal, false otherwise
        return attemptOutcome > 0;
    }
    if (action === "chip_shot") {
        const attacker = {...player}; // The player attempting the chip shot
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
        const attacker = {...player}; // The player taking the penalty
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
        const attacker = {...player}; // The attacker in the 1v1 situation
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
        <p class="text-muted mb-0 fs-12">${formatTime(time)["minute"]}:${
        formatTime(time)["second"]
    }<span class="player-dot" style="background-color: ${player.playerColor}"></span></p>
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
