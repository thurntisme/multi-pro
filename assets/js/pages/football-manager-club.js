$("#formation").on("change", (event) => {
    const formation = event.target.value;
    $("[name='team_formation']").val(formation);
    redraw(formation);
    calculatePlayerAbility(formation, groupTeams[0]);
});

$("#btn-reset-club").on("click", (e) => {
    e.preventDefault();
    const $this = $(e.currentTarget);
    console.log($this.find('.spinner-border'))
    try {
        $.ajax({
            url: apiUrl + '/football-manager/my-club',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({}),
            beforeSend: function () {
                $this.find('.spinner-border').removeClass('d-none');
            },
            success: function (response) {
                if (response.status === 'success') {
                    const {formation, players} = response.data;
                    redraw(formation);
                    calculatePlayerAbility(formation, groupTeams[0]);
                }
                $this.find('.spinner-border').addClass('d-none');
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                $this.find('.spinner-border').addClass('d-none');
            },
        });
    } catch (error) {
        console.error('Error fetching data:', error);
    }
});

const redraw = (formation) => {
    const myTeam = groupTeams[0];
    myTeam.formation = formation;
    renderTeamInFitch([myTeam], {
        isDisplayScore: false,
        circleRadius: 8,
        isDisplayName: myTeam.players === 1 ? true : false
    });
};

redraw($("#formation").val());

let playerSelected, changePlayer;
const playerRowEl =
    "#lineup .my-club-player-row, #subtitle .my-club-player-row";
$(document).on("click", playerRowEl, (e) => {
    $(".my-club-player-row").each((idx, item) => {
        $(item).removeClass("selected");
    });
    const formation = $("[name='team_formation']").val();
    if (!$(e.currentTarget).find(".btn-action")[0].contains(e.target)) {
        $(e.currentTarget).addClass("selected");
        playerSelected = $(e.currentTarget);
        renderPlayerSelected(playerSelected);
        groupTeams[0].playerSelected = playerSelected.attr("data-player-uuid");
        redraw(formation);
    } else {
        if ($(e.currentTarget).find(".btn-change")[0].contains(e.target)) {
            changePlayer = $(e.currentTarget);

            const playerSelectedUuid = playerSelected.attr("data-player-uuid");
            const changePlayerUuid = changePlayer.attr("data-player-uuid");
            handleChangePlayerIndex(formation, playerSelectedUuid, changePlayerUuid);
        }
    }
});

const handleChangePlayerIndex = (formation, playerSelectedUuid, changePlayerUuid) => {
    let playerSelected = $(`[data-player-uuid="${playerSelectedUuid}"]`);
    let changePlayer = $(`[data-player-uuid="${changePlayerUuid}"]`);

    const playerSelectedIndex = allPlayers.findIndex(
        (p) => p.uuid === playerSelectedUuid
    );
    const changePlayerIndex = allPlayers.findIndex(
        (p) => p.uuid === changePlayerUuid
    );

    if (playerSelectedIndex !== -1 && changePlayerIndex !== -1) {
        [allPlayers[playerSelectedIndex], allPlayers[changePlayerIndex]] = [
            allPlayers[changePlayerIndex],
            allPlayers[playerSelectedIndex],
        ];
    }
    groupTeams[0].players = allPlayers.slice(0, 11);
    groupTeams[0].bench = allPlayers.slice(11);
    redraw(formation);
    const newPlayers = [...groupTeams[0].players, ...groupTeams[0].bench];
    $("[name='team_players']").val(JSON.stringify(updateArraysAndGetResult(allBasePlayers, newPlayers)));

    const cloneRow1 = playerSelected.clone(true);
    const cloneRow2 = changePlayer.clone(true);

    playerSelected.replaceWith(cloneRow2);
    changePlayer.replaceWith(cloneRow1);

    playerSelected = null;
    changePlayer = null;
    groupTeams[0].playerSelected = null;

    calculatePlayerAbility(formation, groupTeams[0]);
}

const renderPlayerSelected = (player) => {
    const player_uuid = player.attr("data-player-uuid");
    const player_data = allPlayers.find((p) => p.player_uuid === player_uuid);
    if (player_data) {
        $("#player-info #player-name").text(player_data.name || "");
        $("#player-info #player-nationality").text(player_data.nationality || "");
        $("#player-info #player-best_position").text(
            player_data.best_position || ""
        );
        $("#player-info #player-ability").text(player_data.ability || "");
        $("#player-info #player-playable_positions").text(
            player_data.playable_positions
                ? player_data.playable_positions.join(", ")
                : ""
        );

        const results = {};
        $.each(player_data.attributes, function (category, attributes) {
            const sum = Object.values(attributes).reduce((a, b) => a + b, 0); // Calculate sum
            const maxPossible = 120 * Object.keys(attributes).length; // Calculate max possible value
            const percentage = (sum / maxPossible) * 100; // Calculate percentage
            results[category] = {sum, percentage: percentage.toFixed(2)}; // Store results
        });
        Object.keys(results).forEach((key) => {
            $(`#${key}-label`).text(results[key].sum);
            $(`#${key}-value`).attr("style", `width: ${results[key].percentage}%`);
            $(`#${key}-value`).attr("aria-valuenow", results[key].percentage);
            $(`#${key}-value`).attr("aria-valuemax", results[key].percentage);
        });

        $("#player-age").text(player_data.age);
        $("#player-shirt_number").text(player_data.shirt_number);
        $("#player-height").text(player_data.height);
        $("#player-weight").text(player_data.weight);
        $("#player-nationality").text(player_data.nationality);
        $("#player-market_value").text(player_data.market_value);
        $("#player-contract_wage").text(player_data.contract_wage);
        $("#player-contract_end_date").text(player_data.contract_end_date);
        $("#player-level-num").text(player_data.level.num);
        $("#player-level-percent").attr("style", `width: ${player_data.level.percentageToNextLevel}%`);
        $("#player-level-percent").attr("aria-valuenow", player_data.level.percentageToNextLevel);
        $("#player-level-percent").attr("aria-valuemax", player_data.level.percentageToNextLevel);
        $(".contract-player-id").val(player_data.id);
        $(".contract-player-name").val(player_data.name);
    }
};

const updateArraysAndGetResult = (baseArray, updatedArray) => {
    const result = [];

    // Iterate over the updated array and compare with the base array
    for (let i = 0; i < updatedArray.length; i++) {
        if (JSON.stringify(baseArray[i]) !== JSON.stringify(updatedArray[i])) {
            result.push({...updatedArray[i], new_starting_order: i});
        }
    }

    return result;
}

const calculatePlayerAbility = (formation, team) => {
    const players = [...team.players, ...team.bench];
    const formationData = generateFormation(formation);
    const positions = formationData.map(formation => formation.posName);
    const playerInPosition = players.map((player, index) => {
        const {player_uuid, name, ability, best_position, attributes} = player;
        return {
            player_uuid,
            name,
            ability,
            best_position,
            attributes,
            position_in_match: index < 11 ? positions[index] : player.best_position,
        }
    })
    const payload = {players: playerInPosition};

    try {
        $.ajax({
            url: apiUrl + '/football-manager/club/analysis',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function (response) {
                if (response.status === 'success') {
                    response.data.forEach(player => {
                        const row = $(".my-club-player-row[data-player-uuid='" + player.player_uuid + "']");
                        if (row.length) {
                            $(row).attr('style', `background-color: ${player.bg_color}`);
                            $(row).find('.position').text(player.position_in_match);
                            $(row).find('.position').attr('style', `border-left: solid 4px ${player.position_color}`);
                            $(row).find('.ability').text(player.new_ability);
                        }
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            },
        });
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

// Function to filter players based on conditions
function getFilteredPlayers(players, type = 'sub') {
    const now = new Date();

    return players.filter(player => {
        const joiningDate = new Date(player.joining_date);
        const injuryEndDate = player.injury_end_date ? new Date(player.injury_end_date) : null;

        const isGoodCondition = (
            joiningDate < now && // Joining date is in the past
            (!injuryEndDate || injuryEndDate < now) && // Injury end date is either null or in the past
            player.player_stamina >= 50 && // Stamina is 50 or above
            player.status === 'club' // Status is 'club'
        );

        // Return based on the type ('sub' includes good players, 'lineup' includes others)
        return !player.is_expired && (type === 'sub' ? isGoodCondition : !isGoodCondition);
    });
}

// Event listener
$(document).on("click", "#btn-best-players", (e) => {
    const myLineUp = groupTeams[0].players;
    const bench = groupTeams[0].bench;
    const formation = $("[name='team_formation']").val();

    // Use the filtering function
    const filteredBadPlayers = getFilteredPlayers(myLineUp, 'lineup'); // Players in bad condition
    let filteredGoodPlayers = getFilteredPlayers(bench, 'sub'); // Players in good condition
    const newMyLineUp = JSON.parse(JSON.stringify(myLineUp));

    // Sort filteredGoodPlayers by player_stamina and ability (both descending)
    filteredGoodPlayers.sort((a, b) => {
        if (b.player_stamina === a.player_stamina) {
            return b.ability - a.ability; // If stamina is equal, compare ability
        }
        return b.player_stamina - a.player_stamina;
    });

    // Replace bad players with good players based on positions
    filteredBadPlayers.forEach(badPlayer => {
        const badPlayerIndex = newMyLineUp.findIndex(player => player.id === badPlayer.id);

        if (badPlayerIndex !== -1) {
            // Find the best good player for the bad player's position
            const goodPlayerIndex = filteredGoodPlayers.findIndex(goodPlayer => goodPlayer.best_position === badPlayer.position_in_formation);

            if (goodPlayerIndex !== -1) {
                // Replace bad player with the good player
                handleChangePlayerIndex(formation, newMyLineUp[badPlayerIndex].uuid, filteredGoodPlayers[goodPlayerIndex].uuid)
                newMyLineUp[badPlayerIndex] = filteredGoodPlayers[goodPlayerIndex];
                // Remove the used good player from the list
                filteredGoodPlayers.splice(goodPlayerIndex, 1);
            }
        }
    });
});
