$("#formation").on("change", (event) => {
  const formation = event.target.value;
  $("[name='team_formation']").val(formation);
  redraw(formation);
});

const redraw = (formation) => {
  const myTeam = groupTeams[0];
  myTeam.formation = formation;
  renderTeamInFitch([myTeam], { isDisplayScore: false, circleRadius: 8 });
};

redraw($("#formation").val());

let playerSelected, changePlayer;
const playerRowEl =
  "#lineup .my-club-player-row, #subtitle .my-club-player-row";
$(document).on("click", playerRowEl, (e) => {
  if (!$(e.currentTarget).find(".btn-group")[0].contains(e.target)) {
    $(e.currentTarget).siblings().removeClass("selected");
    $(e.currentTarget).addClass("selected");
    playerSelected = $(e.currentTarget);
    renderPlayerSelected(playerSelected);
  } else {
    if ($(e.currentTarget).find(".btn-change")[0].contains(e.target)) {
      changePlayer = $(e.currentTarget);

      const cloneRow1 = playerSelected.clone(true);
      const cloneRow2 = changePlayer.clone(true);

      playerSelected.replaceWith(cloneRow2);
      changePlayer.replaceWith(cloneRow1);

      playerSelected = null;
      changePlayer = null;
    }
  }
});

const renderPlayerSelected = (player) => {
  const player_uuid = player.attr("data-player-uuid");
  const player_data = allPlayers.find((p) => p.player_uuid === player_uuid);
  if (player_data) {
    console.log(player_data);
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
      results[category] = { sum, percentage: percentage.toFixed(2) }; // Store results
    });
    Object.keys(results).forEach((key) => {
      $(`#${key}-label`).text(results[key].sum);
      $(`#${key}-value`).attr("style", `width: ${results[key].percentage}%`);
      $(`#${key}-value`).attr("aria-valuenow", results[key].percentage);
      $(`#${key}-value`).attr("aria-valuemax", results[key].percentage);
    });
  }
};
