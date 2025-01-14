let playerElSelected, changePlayer;
const playerRowEl = ".my-club-player-row";

let playerUuidSelected = allPlayers[0].uuid;

$(document).on("click", playerRowEl, (e) => {
  $(".my-club-player-row").each((idx, item) => {
    $(item).removeClass("selected");
  });
  $(e.currentTarget).addClass("selected");
  playerElSelected = $(e.currentTarget);
  renderPlayerSelected(playerElSelected);
  $("#shirt_number").val("");
});

$(document).on("change", "#shirt_number", () => {
  const num = $("#shirt_number").val(); // Get the selected value
  const prevPlayerSelected = allPlayers.find(
    (player) => player.shirt_number === +num // Ensure `num` is a number for comparison
  );
  const playerSelected = allPlayers.find(
    (player) => player.uuid === playerUuidSelected
  );

  if (playerSelected) {
    if (prevPlayerSelected) {
      const prevNum = playerSelected.shirt_number;
      prevPlayerSelected.shirt_number = prevNum; // Swap shirt numbers
    }
    playerSelected.shirt_number = +num;

    // Update the UI
    allPlayers.forEach((player) => {
      $(`tr[data-player-uuid="${player.uuid}"]`)
        .find(".shirt_number")
        .text(player.shirt_number);
    });
  }
});

const renderPlayerSelected = (playerEl) => {
  playerUuidSelected = playerEl.attr("data-player-uuid");
  const player_data = allPlayers.find(
    (p) => p.player_uuid === playerUuidSelected
  );
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

    $("#player-age").text(player_data.age);
    $("#player-height").text(player_data.height);
    $("#player-weight").text(player_data.weight);
    $("#player-nationality").text(player_data.nationality);
    $("#player-market_value").text(player_data.market_value);
    $("#player-contract_wage").text(player_data.contract_wage);
    $("#player-contract_end_date").text(player_data.contract_end_date);
    $("#player-level-num").text(player_data.level.num);
    $("#player-level-percent").attr(
      "style",
      `width: ${player_data.level.percentageToNextLevel}%`
    );
    $("#player-level-percent").attr(
      "aria-valuenow",
      player_data.level.percentageToNextLevel
    );
    $("#player-level-percent").attr(
      "aria-valuemax",
      player_data.level.percentageToNextLevel
    );
    $("#player-shirt_number").text(player_data.shirt_number);
  }
};

const updateArraysAndGetResult = (baseArray, updatedArray) => {
  const result = [];

  // Iterate over the updated array and compare with the base array
  for (let i = 0; i < updatedArray.length; i++) {
    if (JSON.stringify(baseArray[i]) !== JSON.stringify(updatedArray[i])) {
      result.push({ ...updatedArray[i], new_starting_order: i });
    }
  }

  return result;
};

$("#my-players-form").on("submit", function (e) {
  e.preventDefault();
  const team_players = allPlayers.filter(
    (player, index) =>
      JSON.stringify(player) !== JSON.stringify(allBasePlayers[index])
  );
  $("[name=team_players]").val(
    JSON.stringify(
      team_players.map((player) => {
        return {
          id: player.id,
          uuid: player.uuid,
          shirt_number: player.shirt_number,
        };
      })
    )
  );
  if (!team_players.length) {
    Swal.fire({
      title: "Nothing to update!",
      icon: "warning",
      showCancelButton: !0,
      customClass: {
        cancelButton: "btn btn-primary w-xs mt-2",
      },
      cancelButtonText: "Dismiss",
      buttonsStyling: !1,
      showCancelButton: !0,
      showConfirmButton: !1,
      showCloseButton: !0,
    });
  } else {
    this.submit();
  }
});
