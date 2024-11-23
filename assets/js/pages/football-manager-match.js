const canvas = document.getElementById("footballPitch");
const ctx = canvas.getContext("2d");
// Pitch Dimensions
const width = canvas.width;
const height = canvas.height;
// Colors
const pitchColor = "#4CAF50";
const lineColor = "#FFFFFF";

// Football Pitch
const drawFootballPitch = () => {
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
    drawCircle(width / 2, height / 2, 40, lineColor, false);
    drawCircle(width / 2, height / 2, 2, lineColor, true);
    drawRect(10, height / 2 - 40, 30, 80);
    drawRect(width - 40, height / 2 - 40, 30, 80);
    drawCircle(20, height / 2, 2, lineColor, true);
    drawCircle(width - 20, height / 2, 2, lineColor, true);
};

const team1Color = "#FF0000"; // Red
const team2Color = "#0000FF"; // Blue

let ballX = width / 2;
let ballY = height / 2;

// Formations
const GK = {x: (width * 4) / 100, y: height / 2};
const LB = {x: (width * 20) / 100, y: height / 6};
const RB = {x: (width * 20) / 100, y: height * 5 / 6};
const LCB = {x: (width * 12) / 100, y: height / 3};
const RCB = {x: (width * 12) / 100, y: (height * 2) / 3};
const LCM = {x: (width * 28) / 100, y: height / 3};
const RCM = {x: (width * 28) / 100, y: (height * 2) / 3};
const LM = {x: (width * 36) / 100, y: height / 6};
const RM = {x: (width * 36) / 100, y: height * 5 / 6};
const LF = {x: (width * 45) / 100, y: height * 3 / 8};
const RF = {x: (width * 45) / 100, y: height * 5 / 8};
const formations = {
    442: [GK, LB, RB, LCB, RCB, LCM, RCM, LM, RM, LF, RF],
};

let team1Positions = formations["442"];

// Helper function to draw lines
function drawLine(x1, y1, x2, y2) {
    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);
    ctx.strokeStyle = lineColor;
    ctx.lineWidth = 2;
    ctx.stroke();
}

// Helper function to draw circles
function drawCircle(x, y, radius, color, isFilled = true) {
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

// Draw penalty and goal areas (simplified for brevity)
function drawRect(x, y, w, h) {
    ctx.strokeStyle = lineColor;
    ctx.lineWidth = 2;
    ctx.strokeRect(x, y, w, h);
}

// Function to draw player numbers
function drawPlayerNumber(x, y, number) {
    ctx.font = "10px Arial";
    ctx.fillStyle = "#FFFFFF";
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.fillText(number, x, y);
}

// Draw Player Position
const drawPlayerPositions = () => {
    team1Positions.forEach((pos, index) => {
        drawCircle(pos.x, pos.y, 8, team1Color);
        drawPlayerNumber(pos.x, pos.y, index + 1);
    });
    team1Positions.forEach((pos, index) => {
        const posX = width - pos.x;
        drawCircle(posX, pos.y, 8, team2Color);
        drawPlayerNumber(posX, pos.y, index + 1);
    });
};

// Draw a ball at a specific position
function drawBall(x, y) {
    ctx.beginPath();
    ctx.arc(x, y, 5, 0, Math.PI * 2); // Ball with radius 5
    ctx.fillStyle = "white";
    ctx.fill();
    ctx.strokeStyle = "black";
    ctx.stroke();
}

drawFootballPitch();
drawPlayerPositions();
drawBall(width / 2, height / 2);
