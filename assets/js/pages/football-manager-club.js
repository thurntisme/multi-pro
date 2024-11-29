$("#formation").on("change", (event) => {
  const formation = event.target.value;
  $("[name='team_formation'").val(formation);
  redraw(formation);
});

const redraw = (formation) => {
  const myTeam = groupTeams[0];
  myTeam.formation = formation;
  renderTeamInFitch(myTeam, { isDisplayScore: false, circleRadius: 8 });
};

redraw($("#formation").val());
