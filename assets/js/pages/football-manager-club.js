// Football Pitch
const drawFootballPitch = () => {
    const canvas = document.getElementById('footballPitch');
    const ctx = canvas.getContext('2d');

    // Pitch Dimensions
    const width = canvas.width;
    const height = canvas.height;

    // Colors
    const pitchColor = '#4CAF50';
    const lineColor = '#FFFFFF';
    const team1Color = '#FF0000'; // Red

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

    // Formations
    const formations = {
        '442': [
            {x: 25, y: height / 2}, // Goalkeeper
            {x: 90, y: height / 4 - 10}, {x: 90, y: height * 3 / 4 + 10}, // Left and Right Back
            {x: 60, y: height / 3}, {x: 60, y: height * 2 / 3}, // Center Backs
            {x: 200, y: height / 4 - 10}, {x: 200, y: height * 3 / 4 + 10}, // Left and Right Midfielders
            {x: 140, y: height / 4 + 20}, {x: 140, y: height * 3 / 4 - 20}, // Midfielders
            {x: 260, y: height / 4 + 25}, {x: 260, y: height * 3 / 4 - 25}, // Forwards
        ],
        '433': [
            {x: 50, y: height / 2}, // Goalkeeper
            {x: 100, y: height / 4}, {x: 100, y: height * 3 / 4}, // Left and Right Back
            {x: 150, y: height / 3}, {x: 150, y: height * 2 / 3}, // Center Backs
            {x: 250, y: height / 4}, {x: 250, y: height / 2}, {x: 250, y: height * 3 / 4}, // Midfielders
            {x: 350, y: height / 6}, {x: 350, y: height / 2}, {x: 350, y: height * 5 / 6}, // Forwards
        ],
        '352': [
            {x: 50, y: height / 2}, // Goalkeeper
            {x: 100, y: height / 4}, {x: 100, y: height * 3 / 4}, // Left and Right Back
            {x: 150, y: height / 2}, // Center Back
            {x: 250, y: height / 4}, {x: 250, y: height * 3 / 4}, // Midfielders
            {x: 300, y: height / 2}, {x: 300, y: height / 6}, {x: 300, y: height * 5 / 6}, // Forwards
            {x: 350, y: height / 3}, {x: 350, y: height * 2 / 3}, // Strikers
        ],
    };

    let team1Positions = formations['442'];

    const team1Players = [
        "Goalkeeper", "Left Back", "Right Back", "Left Center Back", "Right Center Back",
        "Left Midfielder", "Right Midfielder", "Center Midfielder", "Center Midfielder", "Left Forward", "Right Forward"
    ];

    // Function to draw player numbers
    function drawPlayerNumber(x, y, number) {
        ctx.font = '10px Arial';
        ctx.fillStyle = '#FFFFFF';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(number, x, y);
    }

    // Function to draw player names
    function drawPlayerName(x, y, name) {
        ctx.font = '10px Arial';
        ctx.fillStyle = '#FFFFFF';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(name, x, y + 20);
    }

    // Draw Team 1
    const drawPlayerPositions = () => {
        team1Positions.forEach((pos, index) => {
            drawCircle(pos.x, pos.y, 8, team1Color);
            drawPlayerNumber(pos.x, pos.y, index + 1);
            drawPlayerName(pos.x, pos.y, team1Players[index]);
        });
    }
    drawPlayerPositions();

    // Variables for drag-and-drop functionality
    let selectedPlayer = null;
    let offsetX = 0, offsetY = 0;

    // Function to detect if a player is clicked
    function getPlayerAt(x, y) {
        for (let i = 0; i < team1Positions.length; i++) {
            const player = team1Positions[i];
            const distance = Math.sqrt(Math.pow(x - player.x, 2) + Math.pow(y - player.y, 2));
            if (distance <= 10) {
                return {player: player, team: 'team1', index: i};
            }
        }
        for (let i = 0; i < team2Positions.length; i++) {
            const player = team2Positions[i];
            const distance = Math.sqrt(Math.pow(x - player.x, 2) + Math.pow(y - player.y, 2));
            if (distance <= 10) {
                return {player: player, team: 'team2', index: i};
            }
        }
        return null;
    }

    // Mouse down event to select player
    canvas.addEventListener('mousedown', (event) => {
        const mouseX = event.offsetX;
        const mouseY = event.offsetY;
        const playerData = getPlayerAt(mouseX, mouseY);
        if (playerData) {
            selectedPlayer = playerData;
            offsetX = mouseX - selectedPlayer.player.x;
            offsetY = mouseY - selectedPlayer.player.y;
        }
    });

    // Mouse move event to drag player
    canvas.addEventListener('mousemove', (event) => {
        if (selectedPlayer) {
            const mouseX = event.offsetX;
            const mouseY = event.offsetY;
            selectedPlayer.player.x = mouseX - offsetX;
            selectedPlayer.player.y = mouseY - offsetY;
            redraw();
        }
    });

    // Mouse up event to release player
    canvas.addEventListener('mouseup', () => {
        selectedPlayer = null;
    });

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
        drawCircle(width / 2, height / 2, 40, lineColor, false);
        drawCircle(width / 2, height / 2, 2, lineColor, true);
        drawRect(10, height / 2 - 40, 30, 80);
        drawRect(width - 40, height / 2 - 40, 30, 80);
        drawCircle(20, height / 2, 2, lineColor, true);
        drawCircle(width - 20, height / 2, 2, lineColor, true);

        // Redraw players
        drawPlayerPositions()
    }

    // Handle formation change
    document.getElementById('formation').addEventListener('change', (event) => {
        const formation = event.target.value;
        team1Positions = formations[formation];
        redraw();
    });

    // Initial draw
    redraw();
}
drawFootballPitch();

// Match Event Timeline
const drawMatchTimeline = () => {
    const events = [
        {time: 15, event: "Goal", player: "Player A"},
        {time: 35, event: "Yellow Card", player: "Player B"},
        {time: 50, event: "Goal", player: "Player C"},
        {time: 80, event: "Red Card", player: "Player D"},
    ];

    const width = 800;
    const height = 100;

    const svg = d3.select("#timeline")
        .append("svg")
        .attr("width", width)
        .attr("height", height);

    const x = d3.scaleLinear().domain([0, 90]).range([0, width]);

    svg.selectAll("circle")
        .data(events)
        .enter()
        .append("circle")
        .attr("cx", d => x(d.time))
        .attr("cy", height / 2)
        .attr("r", 10)
        .attr("fill", "orange");

    svg.selectAll("text")
        .data(events)
        .enter()
        .append("text")
        .attr("x", d => x(d.time) + 15)
        .attr("y", height / 2)
        .text(d => `${d.event} (${d.player})`)
        .attr("fill", "black");
}
