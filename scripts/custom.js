$(document).ready(function() {
  $.ajax({
    type: 'GET',
    url: 'fetchdpr.php',
    dataType: 'JSON',
    success: function(response) {
      var len = response.length - 1;
      var id = new Array(len);
      var date = new Array(len);
      var streamer_profile = new Array(len);
      var sail_line = new Array(len);
      var line_no = new Array(len);
      var type = new Array(len);
      var direction = new Array(len);
      var sp_from = new Array(len);
      var sp_to = new Array(len);
      var preplot_sp_from = new Array(len);
      var preplot_sp_to = new Array(len);
      var shots = new Array(len);
      var bad_sp = new Array(len);
      var shots_acc = new Array(len);
      var cmps = new Array(len);
      var prime = new Array(len);
      var infill = new Array(len);
      var chargeable_prime = new Array(len);
      var chargeable_infill = new Array(len);
      var ros = new Array(len);
      var remarks_line = new Array(len);
      var standby_hrs = new Array(len);
      var chargeable_standbyhrs = new Array(len);
      var remarks_standby = new Array(len);
      var ntbp = new Array(len);
      var proj = new Array(len);
      var cum_prime = new Array(len).fill(0.0);
      var cum_infill = new Array(len).fill(0.0);

      var proj_total_prime = 38840.0; 

      var total_prime = 0.0;
      var total_infill = 0.0;
      var total_shots = 0;
      var total_bad_sp = 0;

      for (var i = 0; i < len; i++) {
        prime[i] = response[i].prime; total_prime += parseFloat(response[i].prime); (i > 0) ? cum_prime[i] += parseFloat(response[i].prime) + cum_prime[i - 1]: parseFloat(response[i].prime);
        infill[i] = response[i].infill; total_infill += parseFloat(response[i].infill); (i > 0) ? cum_infill[i] += parseFloat(response[i].infill) + cum_infill[i - 1] : parseFloat(response[i].infill);
        shots[i] = response[i].shots; total_shots += parseFloat(response[i].shots);
        bad_sp[i] = response[i].bad_sp; total_bad_sp += parseFloat(response[i].bad_sp);
        date[i] = response[i].date;
      }

      var trace_prime = {
        x: date,
        y: prime,
        type: 'bar',
        name: 'Prime',
        marker: {
          color: 'rgb(49,130,189)',
          opacity: 0.7,
        }
      };

      var trace_infill = {
        x: date,
        y: infill,
        type: 'bar',
        name: 'Infill',
        marker: {
          color: 'rgb(204,204,204)',
          opacity: 0.7
        }
      };

      var trace_shots = {
        x: date,
        y: shots,
        type: 'bar',
        name: 'Aceepted Shots',
        marker: {
          color: 'rgb(148, 39, 9)',
          opacity: 0.7,
        }
      };

      var trace_bad_sp = {
        x: date,
        y: bad_sp,
        type: 'bar',
        name: 'Bad SPs',
        marker: {
          color: 'rgb(10, 171, 173)',
          opacity: 0.7
        }
      };

      var trace_proj_total_prime = {
        x: [proj_total_prime, total_prime],
        y: ['Target', 'Achievement'],
        orientation: 'h',
        type: 'bar',
        marker: {
          color: ['rgb(252, 3, 3, 0.8)', 'rgb(214, 204, 11, 0.8)'],
          opacity: 0.7
        }
      };

      var cum_trace_prime = {
        x: date,
        y: cum_prime,
        type: 'scatter',
        mode: 'lines',
        name: 'Cum. Prime',
        marker: {
          color: 'rgb(58, 81, 251)',
          opacity: 0.7,
        }
      };

      var cum_trace_infill = {
        x: date,
        y: cum_infill,
        type: 'scatter',
        mode: 'lines',
        name: 'Cum. Infill',
        marker: {
          color: 'rgb(225, 81, 251)',
          opacity: 0.7
        }
      };

      var trace_data_daily_coverage = [trace_prime, trace_infill];
      var trace_data_accepted_vs_bad_shots = [trace_shots, trace_bad_sp];
      var trace_data_proj_total_prime = [trace_proj_total_prime];
      var trace_data_cum_coverage = [cum_trace_prime, cum_trace_infill];

      var layout_daily_coverage = {
        title: 'Daily Coverage',
        xaxis: {
          tickangle: -45
        },
        barmode: 'group'
      };

      var layout_daily_accepted_vs_bad_shots = {
        title: 'Accepted v/s Bad Shots',
        xaxis: {
          tickangle: -45
        },
        barmode: 'group'
      };

      var layout_proj_total_prime = {
        title: 'Target Prime v/s Achievement',
        barmode: 'group'
      };

      var layout_cum_coverage = {
        title: 'Cumulative Coverage'
      };

      Plotly.newPlot('daily_coverage', trace_data_daily_coverage, layout_daily_coverage);
      Plotly.newPlot('accepted_vs_bad_shots', trace_data_accepted_vs_bad_shots, layout_daily_accepted_vs_bad_shots);
      Plotly.newPlot('target_prime_vs_ach', trace_data_proj_total_prime, layout_proj_total_prime);
      Plotly.newPlot('cumulative_coverage', trace_data_cum_coverage, layout_cum_coverage);
    }
  });
});