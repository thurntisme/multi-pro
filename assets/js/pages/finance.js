if (document.querySelector("#datepicker-range")) {
  flatpickr("#datepicker-range", {
    mode: "range",
    dateFormat: "d M, Y",
  });
}

if (document.querySelector("#revenue-expenses-charts")) {
  var revenueExpensesChartsColors = ["red", "green"];
  var options = {
    series: [
      {
        name: "Revenue",
        data: [20, 25, 30, 35, 40, 55, 70, 110, 150, 180, 210, 250],
      },
      {
        name: "Expenses",
        data: [12, 17, 45, 42, 24, 35, 42, 75, 102, 108, 156, 199],
      },
    ],
    chart: {
      height: 290,
      type: "area",
      toolbar: "false",
    },
    dataLabels: {
      enabled: false,
    },
    stroke: {
      curve: "smooth",
      width: 2,
    },
    xaxis: {
      categories: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],
    },
    yaxis: {
      labels: {
        formatter: function (value) {
          return "$" + value + "k";
        },
      },
      tickAmount: 5,
      min: 0,
      max: 260,
    },
    colors: revenueExpensesChartsColors,
    fill: {
      opacity: 0.06,
      colors: revenueExpensesChartsColors,
      type: "solid",
    },
  };
  var chart = new ApexCharts(
    document.querySelector("#revenue-expenses-charts"),
    options
  );
  chart.render();
}

// Simple Donut Charts
if (document.querySelector("#store-visits-source")) {
  var chartDonutBasicColors = [
    "#405189",
    "#0ab39c",
    "#f7b84b",
    "#f06548",
    "#299cdb",
  ];
  var options = {
    series: [44, 55, 41, 17, 15],
    labels: ["Direct", "Social", "Email", "Other", "Referrals"],
    chart: {
      height: 333,
      type: "donut",
    },
    legend: {
      position: "bottom",
    },
    stroke: {
      show: false,
    },
    dataLabels: {
      dropShadow: {
        enabled: false,
      },
    },
    colors: chartDonutBasicColors,
  };

  var chart = new ApexCharts(
    document.querySelector("#store-visits-source"),
    options
  );
  chart.render();
}
