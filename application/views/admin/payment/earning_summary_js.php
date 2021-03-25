<script type="text/javascript">

  "use strict";
  var month_chart = document.getElementById("month-chart").getContext('2d');

  var month_chart_bg_color = month_chart.createLinearGradient(0, 0, 0, 70);
  month_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
  month_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

  var myChart = new Chart(month_chart, {
    type: 'line',
    data: {
      labels: <?php echo json_encode(array_keys($array_month));?>,
      datasets: [{
        label: '<?php echo $this->lang->line("Earning");?>',
        data: <?php echo json_encode(array_values($array_month)) ;?>,
        backgroundColor: month_chart_bg_color,
        borderWidth: 3,
        borderColor: 'rgba(63,82,227,1)',
        pointBorderWidth: 0,
        pointBorderColor: 'transparent',
        pointRadius: 3,
        pointBackgroundColor: 'transparent',
        pointHoverBackgroundColor: 'rgba(63,82,227,1)',
      }]
    },
    options: {
      layout: {
        padding: {
          bottom: -1,
          left: -1
        }
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          gridLines: {
            display: false,
            drawBorder: false,
          },
          ticks: {
            beginAtZero: true,
            display: false
          }
        }],
        xAxes: [{
          gridLines: {
            drawBorder: false,
            display: false,
          },
          ticks: {
            display: false
          }
        }]
      },
    }
  });

  var year_chart = document.getElementById("year-chart").getContext('2d');

  var year_chart_bg_color = year_chart.createLinearGradient(0, 0, 0, 80);
  year_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
  year_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

  var myChart = new Chart(year_chart, {
    type: 'line',
    data: {
      labels: <?php echo json_encode(array_keys($array_year));?>,
      datasets: [{
        label:  '<?php echo $this->lang->line("Earning");?>',
        data: <?php echo json_encode(array_values($array_year));?>,
        borderWidth: 2,
        backgroundColor: year_chart_bg_color,
        borderWidth: 3,
        borderColor: 'rgba(63,82,227,1)',
        pointBorderWidth: 0,
        pointBorderColor: 'transparent',
        pointRadius: 3,
        pointBackgroundColor: 'transparent',
        pointHoverBackgroundColor: 'rgba(63,82,227,1)',
      }]
    },
    options: {
      layout: {
        padding: {
          bottom: -1,
          left: -1
        }
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          gridLines: {
            display: false,
            drawBorder: false,
          },
          ticks: {
            beginAtZero: true,
            display: false
          }
        }],
        xAxes: [{
          gridLines: {
            drawBorder: false,
            display: false,
          },
          ticks: {
            display: false
          }
        }]
      },
    }
  });

  var ctx = document.getElementById("comparison-chart").getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?php echo json_encode(array_values($month_names));?>,
      datasets: [{
        label: '<?php echo $year; ?>',
        data: <?php echo json_encode(array_values($this_year_earning));?>,
        borderWidth: 2,
        backgroundColor: 'rgba(63,82,227,.8)',
        borderWidth: 0,
        borderColor: 'transparent',
        pointBorderWidth: 0,
        pointRadius: 3.5,
        pointBackgroundColor: 'transparent',
        pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
      },
      {
        label: '<?php echo $lastyear; ?>',
        data: <?php echo json_encode(array_values($last_year_earning));?>,
        borderWidth: 2,
        backgroundColor: 'rgba(254,86,83,.7)',
        borderWidth: 0,
        borderColor: 'transparent',
        pointBorderWidth: 0 ,
        pointRadius: 3.5,
        pointBackgroundColor: 'transparent',
        pointHoverBackgroundColor: 'rgba(254,86,83,.8)',
      }]
    },
    options: {
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          gridLines: {
            // display: false,
            drawBorder: false,
            color: '#f2f2f2',
          },
          ticks: {
            beginAtZero: true,
            stepSize: <?php echo $steps; ?>,
            callback: function(value, index, values) {
              return value;
            }
          }
        }],
        xAxes: [{
          gridLines: {
            display: false,
            tickMarkLength: 15,
          }
        }]
      },
    }
  });
</script>