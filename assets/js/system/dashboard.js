  $(function() {
      "use strict";
  
      var myChart1; 
      $(document).ready(function() {
        $(document).on('click', '.no_action', function(event) {
          event.preventDefault();
        });
        var stepsize = dashboard_step_size; 
        var image_vs_video_post = document.getElementById('image_vs_video_post').getContext('2d');
        var myChart1 = new Chart(image_vs_video_post, {
          type: 'line',
          data: {
            labels: dashboard_image_video_compare_list,
            datasets: [{
              label: global_lang_image,
              data: dashboard_image_post_list,
              borderWidth: 2,
              backgroundColor: 'rgba(254,86,83,.7)',
              borderWidth: 0,
              borderColor: 'transparent',
              pointBorderWidth: 0,
              pointRadius: 3.5,
              pointBackgroundColor: 'transparent',
              pointHoverBackgroundColor: 'rgba(254,86,83,.8)',
            },
            {
              label: global_lang_video,
              data: dashboard_video_post_list,
              borderWidth: 2,
              backgroundColor: 'rgba(63,82,227,.8)',
              borderWidth: 0,
              borderColor: 'transparent',
              pointBorderWidth: 0 ,
              pointRadius: 3.5,
              pointBackgroundColor: 'transparent',
              pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
            }]
          },
          options: {
            legend: {
              display: false
            },
            scales: {
              yAxes: [{
                gridLines: {
                  drawBorder: false,
                  color: '#f2f2f2',
                },
                ticks: {
                  beginAtZero: true,
                  stepSize: stepsize,
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
      });
  });