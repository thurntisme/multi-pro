// Colors
const pitchColor = "#4CAF50";
const lineColor = "#FFFFFF";
const team1Color = "#FF0000"; // Red
const team2Color = "#0000FF"; // Blue
const playerScore = 5;

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
    drawCircle(width / 2, height / 2, lineColor, height / 6, false);
    drawRect(10, height / 2 - 40, 30, 80);
    drawRect(width - 40, height / 2 - 40, 30, 80);
  }

  // Initial draw
  redraw();
};
drawFootballPitch();

// Draw a ball at a specific position
function drawBall() {
  ctx.beginPath();
  ctx.arc(width / 2, height / 2, 5, 0, Math.PI * 2); // Ball with radius 5
  ctx.fillStyle = "white";
  ctx.fill();
  ctx.strokeStyle = "black";
  ctx.stroke();
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

function getPositionColor(position) {
  const positionColors = {
    Goalkeepers: "#ff8811",
    Defenders: "#3ec300",
    Midfielders: "#337ca0",
    Attackers: "#ff1d15",
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

function renderTeamInFitch(
  teams,
  conditions = { isDisplayBall, isDisplayScore, circleRadius }
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
          score: playerScore,
        };
      }),
      bench: team.bench,
      playerSelected: team?.playerSelected,
    };
    teamData.players.forEach((pos, index) => {
      drawCircle(
        pos.x,
        pos.y,
        getPositionColor(pos.posName),
        conditions?.circleRadius
      );
      if (team?.playerSelected && team?.playerSelected == pos.uuid) {
        drawCircle(pos.x, pos.y, "#fff", 12, false);
      }
      drawPlayerNumber(pos.x, pos.y, index + 1);
      drawPlayerName(pos.x, pos.y, pos.name);
      if (conditions?.isDisplayScore) {
        drawPlayerScore(pos);
      }
    });
  });
}
