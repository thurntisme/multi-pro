// Colors
const pitchColor = "#4CAF50";
const lineColor = "#FFFFFF";
const playerScore = 5;
const homeTeamColor = "#337ca0";
const awayTeamColor = "#ff1d20";

// Helper function to draw circles
function drawCircle(x, y, color, radius = 10, isFilled = true) {
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

    // Draw penalty and goal areas (simplified for brevity)
    function drawRect(x, y, w, h) {
        ctx.strokeStyle = lineColor;
        ctx.lineWidth = 2;
        ctx.strokeRect(x, y, w, h);
    }

    function drawArc(centerX, centerY, radius, startAngle, endAngle) {
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, startAngle, endAngle);
        ctx.strokeStyle = lineColor;
        ctx.lineWidth = 2;
        ctx.stroke();
    }

    // Function to redraw the pitch and players
    function redraw() {
        // Redraw pitch
        ctx.fillStyle = pitchColor;
        ctx.fillRect(0, 0, width, height);

        if (width === 320) {
            // Redraw lines
            drawLine(10, 10, width - 10, 10);
            drawLine(width - 10, 10, width - 10, height - 10);
            drawLine(width - 10, height - 10, 10, height - 10);
            drawLine(10, height - 10, 10, 10);
            drawLine(width / 2, 10, width / 2, height - 10);

            // Redraw center circle and other elements
            drawCircle(width / 2, height / 2, lineColor, 15, false);

            // Left goal box
            drawRect(5, height / 2 - 7, 5, 14);
            drawRect(10, height / 2 - 14, 14, 28);
            drawRect(10, height / 2 - 32, 30, 64);
            drawArc(30, height / 2, 15, 1.75 * Math.PI, 0.25 * Math.PI);

            // Right goal box
            drawRect(width - 10, height / 2 - 7, 5, 14);
            drawRect(width - 24, height / 2 - 14, 14, 28);
            drawRect(width - 40, height / 2 - 32, 30, 64);
            drawArc(width - 30, height / 2, 15, 0.75 * Math.PI, 1.25 * Math.PI);

            // Draw conner arc
            drawArc(10, 10, 10, 2 * Math.PI, 0.5 * Math.PI);
            drawArc(width - 10, 10, 10, 0.5 * Math.PI, Math.PI);
            drawArc(width - 10, height - 10, 10, Math.PI, 1.5 * Math.PI);
            drawArc(10, height - 10, 10, 1.5 * Math.PI, 2 * Math.PI);
        } else {
            // Redraw lines
            drawLine(20, 20, width - 20, 20);
            drawLine(width - 20, 20, width - 20, height - 20);
            drawLine(width - 20, height - 20, 20, height - 20);
            drawLine(20, height - 20, 20, 20);
            drawLine(width / 2, 20, width / 2, height - 20);

            // Redraw center circle and other elements
            drawCircle(width / 2, height / 2, lineColor, 45, false);

            // Left goal box
            drawRect(10, height / 2 - 20, 10, 40);
            drawRect(20, height / 2 - 40, 30, 80);
            drawRect(20, 90, 90, 190);
            drawArc(78, height / 2, 45, 1.75 * Math.PI, 0.25 * Math.PI);
            drawCircle(74, height / 2, lineColor, 3, true);

            // Right goal box
            drawRect(width - 20, height / 2 - 20, 10, 40);
            drawRect(width - 50, height / 2 - 40, 30, 80);
            drawRect(width - 110, 90, 90, 190);
            drawArc(width - 78, height / 2, 45, 0.75 * Math.PI, 1.25 * Math.PI);
            drawCircle(width - 74, height / 2, lineColor, 3, true);

            drawArc(78, height / 2, 45, 1.75 * Math.PI, 0.25 * Math.PI);

            // Draw conner arc
            drawArc(20, 20, 10, 2 * Math.PI, 0.5 * Math.PI);
            drawArc(width - 20, 20, 10, 0.5 * Math.PI, Math.PI);
            drawArc(width - 20, height - 20, 10, Math.PI, 1.5 * Math.PI);
            drawArc(20, height - 20, 10, 1.5 * Math.PI, 2 * Math.PI);
        }
    }

    // Initial draw
    redraw();
};
drawFootballPitch();

// Draw a ball at a specific position
function drawBall() {
    const ballRadius = 6;
    ctx.beginPath();
    ctx.arc(width / 2, height / 2, ballRadius, 0, Math.PI * 2); // Ball with radius 5
    ctx.fillStyle = "white";
    ctx.fill();

    if (ballImagePath) {
        const ballImage = new Image(); // Create a new Image object
        ballImage.src = ballImagePath; // Set the path to your image file

        // Once the image is loaded, draw it on the canvas
        ballImage.onload = function () {
            ctx.drawImage(
                ballImage,
                width / 2 - ballRadius, // x position (centered)
                height / 2 - ballRadius, // y position (centered)
                ballRadius * 2, // Width of the image
                ballRadius * 2  // Height of the image
            );
        };
    } else {
        ctx.strokeStyle = "black";
        ctx.stroke();
    }
}

// Function to draw player names
function drawPlayerName(x, y, name) {
    const playerName = getShortPlayerName(name);
    drawRoundedRect(x - playerName.length * 2.5 - 5, y + 13, playerName.length * 5 + 10, 14, 5, '#fff');

    ctx.font = "10px Arial";
    ctx.fillStyle = "#000";
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.fillText(playerName, x, y + 20);
}

function getShortPlayerName(fullName) {
    const nameParts = fullName.trim().split(" "); // Split the name into parts
    if (nameParts.length < 2) return fullName; // If there's no last name, return the full name

    const firstNameInitial = nameParts[0][0].toUpperCase(); // Get the first initial
    const lastName = nameParts[nameParts.length - 1]; // Get the last name

    return `${firstNameInitial}. ${lastName}`;
}

function getPositionColor(position) {
    const positionColors = {
        Goalkeepers: "#ff8811",
        Defenders: "#3ec300",
        Midfielders: "#337ca0",
        Attackers: "#ff1d20",
    };

    for (const [group, positions] of Object.entries(positionGroups)) {
        if (positions.includes(position)) {
            return positionColors[group];
        }
    }

    return "gray"; // Default color if position is not found
}

// Function to draw player numbers
function drawPlayerNumber(x, y, number) {
    ctx.font = "10px Arial";
    ctx.fillStyle = "#FFFFFF";
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.fillText(number, x, y);
}

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
    const {x, y, score} = player;
    // Draw circle around the score
    const scoreX = x;
    const scoreY = y + 30;
    const maxScore = 10; // Maximum score
    const normalizedScore = score / maxScore; // Normalize score between 0 and 1

    // Use normalizedScore to create a gradient from blue -> green -> yellow -> red
    const red = Math.min(255, Math.floor(normalizedScore * 255));
    const green = Math.min(
        255,
        Math.floor((1 - Math.abs(normalizedScore - 0.5) * 2) * 255)
    ); // Peaks in the middle
    const blue = Math.min(255, Math.floor((1 - normalizedScore) * 255));

    let backgroundColor = `rgb(${red}, ${green}, ${blue})`;

    // Calculate luminance for dynamic text color
    const luminance = (0.299 * red + 0.587 * green + 0.114 * blue) / 255;
    let textColor = luminance > 0.5 ? "#000000" : "#ffffff";

    if (player?.is_off) {
        backgroundColor = 'gray';
        textColor = '#fff';
    }

    drawRoundedRect(scoreX - 11, scoreY, 22, 14, 5, backgroundColor);

    // Add player score
    ctx.fillStyle = textColor;
    ctx.font = "10px Arial";
    ctx.textAlign = "center";
    ctx.fillText(score.toFixed(1), scoreX, scoreY + 7);
}

function renderTeamInFitch(
    teams,
    conditions = {isDisplayBall, isDisplayScore, circleRadius, isDisplayName, isTeamInMatch}
) {
    drawFootballPitch();
    if (conditions?.isDisplayBall) {
        drawBall();
    }
    teams.forEach((team, teamIdx) => {
        const teamData = {
            name: team.name,
            score: 0,
            players: generateFormation(team.formation).map((pos, idx) => {
                return {
                    ...pos,
                    x: teamIdx === 0 ? pos.x : width - pos.x,
                    y: teamIdx === 0 ? pos.y : height - pos.y,
                    ...team.players[idx],
                };
            }),
            bench: team.bench,
            playerSelected: team?.playerSelected,
        };
        teamData.players.forEach((pos, index) => {
            let playerColor = getPositionColor(pos.posName);
            if (conditions?.isTeamInMatch) {
                playerColor = pos.playerColor ?? 'gray';
            }
            if (pos?.is_off) {
                playerColor = 'gray';
            }
            if (pos?.yellow_cards_in_match === 1) {
                drawFoulCard(pos, 'yellow');
            }
            if (pos?.red_cards_in_match === 1) {
                drawFoulCard(pos, 'red');
            }
            if (pos?.goals_in_match) {
                drawPlayerGoal(pos);
            }
            drawCircle(
                pos.x,
                pos.y,
                playerColor,
                conditions?.circleRadius
            );
            if (team?.playerSelected && team?.playerSelected === pos.uuid) {
                drawCircle(pos.x, pos.y, "#fff", 12, false);
            }
            drawPlayerNumber(pos.x, pos.y, pos.shirt_number ?? index + 1);
            if (conditions?.isDisplayName) {
                drawPlayerName(pos.x, pos.y + 2, pos.name);
            }
            if (conditions?.isDisplayScore) {
                drawPlayerScore(pos);
            }
        });
    });
}

function drawFoulCard(pos, type) {
    const {x, y} = pos;
    const cardImage = new Image();
    cardImage.src = type === 'yellow' ? yellowCardImagePath : redCardImagePath;

    cardImage.onload = function () {
        const cardWidth = 12;
        const cardHeight = 14;
        const cardX = x + 4;
        const cardY = y - 14;

        ctx.drawImage(
            cardImage,
            cardX,
            cardY,
            cardWidth,
            cardHeight
        );
    };
}

function drawPlayerGoal(pos) {
    const {x, y, goals_in_match} = pos;
    const cardImage = new Image();
    cardImage.src = goalImagePath;

    cardImage.onload = function () {
        const cardWidth = 12;
        const cardHeight = 12;
        const spacing = 2; // Space between goal markers

        for (let i = 0; i < goals_in_match; i++) {
            // Calculate the total width of all images, including the spaces between them
            const totalWidth = goals_in_match * cardWidth + (goals_in_match - 1) * spacing;

            // Calculate the starting X position to center the images
            const cardX = x - totalWidth / 2 + i * (cardWidth + spacing);

            const cardY = y + 4;

            ctx.drawImage(
                cardImage,
                cardX,
                cardY,
                cardWidth,
                cardHeight
            );
        }
    };
}
