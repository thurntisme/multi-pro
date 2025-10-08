const tableId = "#football-table";
const modalId = "#infoModal";

const footballDataTables = new DataTable(tableId, {
  ajax: {
    url: "/api/football/market/list",
    dataSrc: "data",
  },
  dom: '<"d-flex justify-content-between align-items-center mb-2"l<"d-flex gap-2"f<"custom-filter d-flex gap-2">>>t<"d-flex justify-content-between mt-2"ip>',
  initComplete: function () {
    const api = this.api();
    renderCustomFilter(api);
  },
  columns: [
    { data: "name" },
    {
      data: "position",
      render: function (data, type, row) {
        const html = row.playablePositions.map(function (position) {
          if (position === data) return `<span class="fw-bold">${data}</span>`;
          return `${position}`;
        });
        return html.join(", ");
      },
    },
    { data: "rating" },
    { data: "nationality" },
    { data: "edition" },
    {
      data: "id",
      className: "text-center",
      render: function (data, type, row) {
        return `<button type="button" class="btn btn-soft-info btn-sm waves-effect waves-light btn-view">Info</button>`;
      },
    },
  ],
  responsive: true,
  paging: true,
  searching: true,
});

const infoModal = new bootstrap.Modal($(modalId));
$(tableId).on("click", ".btn-view", function () {
  const data = footballDataTables.row($(this).closest("tr")).data();
  fillPlayerInfo(data);

  infoModal.show();
});
$(modalId).on("hidden.bs.modal", function () {
  $("#playerAvatar").attr("src", "");
  $("#playerModal .modal-body span").text("");
  $('#playerModal .modal-body div[id^="attr"]').empty();
});

function renderCustomFilter(api) {
  const filters = [
    { name: "position", label: "Positions", col: 1 },
    { name: "Nationality", label: "Nationalities", col: 3 },
    { name: "edition", label: "Editions", col: 4 },
  ];

  filters.forEach((filter) => {
    const $filter = $(
      `<select class="form-select form-select-sm w-auto" name="${filter.name}"><option value="">All ${filter.label}</option></select>`
    );
    $(".custom-filter").append($filter);

    api
      .column(filter.col)
      .data()
      .unique()
      .sort()
      .each(function (d) {
        if (d) $filter.append(`<option value="${d}">${d}</option>`);
      });

    $filter.on("change", function () {
      const val = $(this).val();
      if (val) {
        if (filter.col === 1) {
          api.column(filter.col).search(val, true, false).draw();
        } else {
          api
            .column(filter.col)
            .search("^" + val + "$", true, false)
            .draw();
        }
      } else {
        api.column(filter.col).search("").draw();
      }
    });
  });
}

function fillPlayerInfo(data) {
  $("#playerAvatar").attr("src", data.avatarUrl);
  $("#playerName").text(data.name);
  $("#playerPosition").text(data.position);
  $("#playerRating").text(data.rating);
  $("#playerEdition").text(data.edition);
  $("#playerNationality").text(data.nationality);
  $("#playerBirthday").text(data.birthday);
  $("#playerHeight").text(data.height);
  $("#playerWeight").text(data.weight);
  $("#playerFoot").text(data.foot);
  $("#playerFitness").text(data.fitness);
  $("#playerForm").text(data.form);
  $("#playerSalary").text(data.salary.toLocaleString());
  $("#playerContractLength").text(data.contractLength);
  $("#playerMarketValue").text(data.marketValue.toLocaleString());
  $("#playerPotential").text(data.potential);
  $("#playerSkillMoves").text(data.skillMoves);
  $("#playerPreferredRole").text(data.preferredRole || "—");
  $("#playerPersonality").text(data.personality || "—");
  $("#playerInjuryProne").text(data.injuryProne);
  $("#playerConsistency").text(data.consistency);

  // 🧠 Attribute groups
  const { attributes } = data;

  // Helper function render attributes
  function renderAttributes(attrList, targetId) {
    let html = '<ul class="list-unstyled mb-2">';
    for (const key in attrList) {
      html += `<li><strong>${key}</strong>: ${attrList[key]}</li>`;
    }
    html += "</ul>";
    $(targetId).html(html);
  }

  renderAttributes(
    {
      pace: attributes.pace,
      acceleration: attributes.acceleration,
      agility: attributes.agility,
      stamina: attributes.stamina,
      strength: attributes.strength,
      jumping: attributes.jumping,
    },
    "#attrPhysical"
  );

  renderAttributes(
    {
      dribbling: attributes.dribbling,
      ballControl: attributes.ballControl,
      crossing: attributes.crossing,
      shooting: attributes.shooting,
      longShots: attributes.longShots,
      reactions: attributes.reactions,
      heading: attributes.heading,
      tackling: attributes.tackling,
      defending: attributes.defending,
      finishing: attributes.finishing,
      shortPassing: attributes.shortPassing,
      longPassing: attributes.longPassing,
      powerShots: attributes.powerShots,
      setPieces: attributes.setPieces,
    },
    "#attrTechnical"
  );

  renderAttributes(
    {
      vision: attributes.vision,
      positioning: attributes.positioning,
      anticipation: attributes.anticipation,
      decisionMaking: attributes.decisionMaking,
      composure: attributes.composure,
      concentration: attributes.concentration,
      workRate: attributes.workRate,
      leadership: attributes.leadership,
      flair: attributes.flair,
      creativity: attributes.creativity,
    },
    "#attrMental"
  );

  renderAttributes(
    {
      reflexes: attributes.reflexes,
      diving: attributes.diving,
      handling: attributes.handling,
      kicking: attributes.kicking,
      positioningGK: attributes.positioningGK,
      oneOnOne: attributes.oneOnOne,
      commandOfArea: attributes.commandOfArea,
    },
    "#attrGoalkeeper"
  );
}
