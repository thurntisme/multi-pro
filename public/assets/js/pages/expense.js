document.addEventListener("DOMContentLoaded", function () {
  const chartDom = document.getElementById("expenseLineChart");
  const myChart = echarts.init(chartDom);
  const pieChartDom = document.getElementById("expensePieChart");
  const pieChart = echarts.init(pieChartDom);

  const lineChartOptions = {
    tooltip: {
      trigger: "axis",
      backgroundColor: "#fff",
      borderColor: "#ccc",
      borderWidth: 1,
      textStyle: {
        color: "#333",
      },
    },
    legend: {
      data: ["This Week", "Last Week"],
      bottom: 0,
    },
    grid: {
      left: "3%",
      right: "4%",
      bottom: "12%",
      containLabel: true,
    },
    xAxis: {
      type: "category",
      data: days,
      axisTick: {
        show: false,
      },
      axisLine: {
        lineStyle: {
          color: "#aaa",
        },
      },
    },
    yAxis: {
      type: "value",
      name: "Amount ($)",
      axisLine: {
        show: false,
      },
      splitLine: {
        lineStyle: {
          color: "#eee",
        },
      },
    },
    series: [
      {
        name: "This Week",
        type: "line",
        smooth: true,
        data: thisWeek,
        symbol: "circle",
        symbolSize: 8,
        lineStyle: {
          width: 3,
          color: "#0d6efd",
        },
        itemStyle: {
          color: "#0d6efd",
        },
        areaStyle: {
          color: "rgba(13, 110, 253, 0.1)",
        },
      },
      {
        name: "Last Week",
        type: "line",
        smooth: true,
        data: lastWeek,
        symbol: "circle",
        symbolSize: 8,
        lineStyle: {
          width: 3,
          color: "#dc3545",
        },
        itemStyle: {
          color: "#dc3545",
        },
        areaStyle: {
          color: "rgba(220, 53, 69, 0.1)",
        },
      },
    ],
  };
  myChart.setOption(lineChartOptions);

  const pieChartOptions = {
    tooltip: {
      trigger: "item",
      formatter: "{b}: ${c} ({d}%)",
      backgroundColor: "#fff",
      borderColor: "#ccc",
      borderWidth: 1,
      textStyle: {
        color: "#333",
      },
    },
    legend: {
      orient: "horizontal",
      bottom: 0,
    },
    series: [
      {
        name: "Expense Category",
        type: "pie",
        radius: ["35%", "60%"],
        avoidLabelOverlap: false,
        itemStyle: {
          borderRadius: 6,
          borderColor: "#fff",
          borderWidth: 2,
        },
        label: {
          show: true,
          formatter: "{b}\n${c}",
          fontSize: 13,
        },
        emphasis: {
          label: {
            show: true,
            fontSize: 15,
            fontWeight: "bold",
          },
        },
        labelLine: {
          show: true,
          length: 15,
          length2: 10,
        },
        data: cateBreakdownData,
      },
    ],
    color: ["#0d6efd", "#dc3545", "#ffc107", "#20c997", "#6f42c1"],
  };
  pieChart.setOption(pieChartOptions);

  window.addEventListener("resize", () => {
    myChart.resize();
    pieChart.resize();
  });
});
