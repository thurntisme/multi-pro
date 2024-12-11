$("#formation").on("change", (event) => {
    const formation = event.target.value;
    $("[name='team_formation']").val(formation);
    redraw(formation);
});

const redraw = (formation) => {
    const myTeam = groupTeams[0];
    myTeam.formation = formation;
    renderTeamInFitch([myTeam], {isDisplayScore: false, circleRadius: 8});
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
    if (!$(e.currentTarget).find(".btn-group")[0].contains(e.target)) {
        $(e.currentTarget).addClass("selected");
        playerSelected = $(e.currentTarget);
        renderPlayerSelected(playerSelected);
        groupTeams[0].playerSelected = playerSelected.attr("data-player-uuid");
        redraw(formation);
    } else {
        if ($(e.currentTarget).find(".btn-change")[0].contains(e.target)) {
            changePlayer = $(e.currentTarget);

            const playerSelectedUuid = playerSelected.attr("data-player-uuid");
            const playerSelectedIndex = allPlayers.findIndex(
                (p) => p.uuid === playerSelectedUuid
            );
            const changePlayerUuid = changePlayer.attr("data-player-uuid");
            const changePlayerIndex = allPlayers.findIndex(
                (p) => p.uuid === changePlayerUuid
            );

            if (playerSelectedIndex !== -1 && changePlayerIndex !== -1) {
                [allPlayers[playerSelectedIndex], allPlayers[changePlayerIndex]] = [
                    allPlayers[changePlayerIndex],
                    allPlayers[playerSelectedIndex],
                ];
            }
            const newLineUpPlayers = allPlayers.slice(0, 11);
            groupTeams[0].players = newLineUpPlayers;
            redraw(formation);

            const cloneRow1 = playerSelected.clone(true);
            const cloneRow2 = changePlayer.clone(true);

            playerSelected.replaceWith(cloneRow2);
            changePlayer.replaceWith(cloneRow1);

            playerSelected = null;
            changePlayer = null;
            groupTeams[0].playerSelected = null;
        }
    }
});

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
    }
};
