let footballDataTables = new DataTable("#football-table", {
  ajax: {
    url: "/api/football/market/list",
    dataSrc: "data",
  },
  columns: [
    { data: "name" },
    { data: "position" },
    { data: "rating" },
    { data: "edition" },
    {
      data: "id",
      className: "text-center",
      render: function (data, type, row) {
        return `<button type="button" class="btn btn-soft-info btn-sm waves-effect waves-light">Info</button>`;
      },
    },
  ],
  responsive: true,
  paging: true,
  searching: true,
});
