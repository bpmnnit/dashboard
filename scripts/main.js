/*
function load_data(fp, fromdate, todate)
{
	$.ajax({
		url:"fetch.php",
		method:"post",
		data:{fp, fromdate, todate},
		success:function(data)
		{
			$('#result').html(data);
		}
	});
}
*/
function create_fp_area() {
	var fp = document.getElementById('fpselect').value;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("areaselect").innerHTML = '';
			document.getElementById("areaselect").innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET", "getareas.php?fp=" + fp, true);
	xmlhttp.send();
}

function drop_down_acq_type() {
	var si_id = document.getElementById('areaselect').value;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("acqtypeselect").innerHTML = '';
			document.getElementById("acqtypeselect").innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET", "getacqtype.php?si_id=" + si_id, true);
	xmlhttp.send();
}

function create_dates_limit() {
	var si = document.getElementById('acqtypeselect').value;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			dates = this.responseText;
			dates = dates.split('|');
			max_date = dates[1]; min_date = dates[0];
			document.getElementById("fromdate").value = min_date;
			document.getElementById("todate").value = max_date;
		}
	};
	xmlhttp.open("GET", "getdates.php?si=" + si, true);
	xmlhttp.send();
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}

function displayDates() {
  var e = document.getElementById('year_type');
  var year_type = e.value;
  if(year_type === 'fy' || year_type === 'fs') {
    document.getElementById('fy-or-fs').style.display = 'block';
    document.getElementById('date-wise').style.display = 'none';
  }
  if(year_type === 'dt') {
    document.getElementById('fy-or-fs').style.display = 'none';
    document.getElementById('date-wise').style.display = 'block';
  }
}

function load_data_year_wise(yt, yr, acqtype, result_table, result_graph) {
  $.ajax({
    url:"ta_fetch.php",
    method:"POST",
    dataType: 'JSON',
    data:{yt: yt, yr: yr, acqtype: acqtype},
    //contentType:"application/json; charset=utf-8",
    success:function(data) {

      var len = data.length;
      var si = new Array(len);
      var area = new Array(len);
      var fp = new Array(len);
      var region = new Array(len);
      var re = new Array(len);
      var be = new Array(len);
      var ach = new Array(len);

      var total_be_target = 0.0;
      var total_re_target = 0.0;
      var total_achievement = 0.0;

      for (var i = 0; i < len; i++) {
        si[i] = data[i].si;
        area[i] = data[i].area;
        fp[i] = data[i].fp;
        region[i] = data[i].region;
        re[i] = data[i].re;
        be[i] = data[i].be;
        ach[i] = data[i].ach;
      }

      for (var i = 0; i < len; i++) {
        re[i] = re[i] || 0.0;
        be[i] = be[i] || 0.0;
        ach[i] = ach[i] || 0.0;
      }

      for (var i = 0; i < len; i++) {
        total_be_target += parseFloat(be[i]);
        total_re_target += parseFloat(re[i]);
        total_achievement += parseFloat(ach[i]);
      }

      var regions = region.filter((item, i, ar) => ar.indexOf(item) === i); // getting unique regions
      var achmnt = new Array(regions.length).fill(0.0);
      var betgt = new Array(regions.length).fill(0.0);
      var retgt = new Array(regions.length).fill(0.0);

      var j = 0;
      for(var i = 0; i < len - 1; i++) {
        if(region[i].localeCompare(region[i + 1]) === 0) {
          achmnt[j] += parseFloat(ach[i]);
          betgt[j] += parseFloat(be[i]);
          retgt[j] += parseFloat(re[i]);
        } else {
          achmnt[j] += parseFloat(ach[i]);
          betgt[j] += parseFloat(be[i]);
          retgt[j] += parseFloat(re[i]);
          j += 1;
        }
      }
      achmnt[achmnt.length - 1] += parseFloat(ach[len - 1]);
      betgt[betgt.length - 1] += parseFloat(be[len - 1]);
      retgt[retgt.length - 1] += parseFloat(re[len - 1]);

      console.log(len);
      console.log(si);
      console.log(area);
      console.log(fp);
      console.log(region);
      console.log(regions);
      console.log(achmnt);
      console.log(betgt);
      console.log(retgt);
      console.log(re);
      console.log(be);
      console.log(ach);
      console.log(data);

      var yAxisText = ("3D".localeCompare(acqtype)) ? "LKM" : "SKM";

      writeTable(yr, si, area, fp, region, re, be, ach, acqtype, len, total_be_target, total_re_target, total_achievement, yAxisText, result_table);

      var trace1 = {
        x: regions,
        y: betgt,
        name: 'BE Target',
        type: 'bar'
      };
      var trace2 = {
        x: regions,
        y: retgt,
        name: 'RE Target',
        type: 'bar'
      };
      var trace3 = {
        x: regions,
        y: achmnt,
        name: 'Achievement',
        type: 'bar'
      };

      var fy_graph_data = [trace1, trace2, trace3];
      var layout = {
        barmode: 'group',
        title: {
          text: 'Target vs Achievement ' + '(' + acqtype + ')' + ' for FY: ' + yr,
          font: {
            family: 'Courier New, monospace',
            size: 20
          },
          xref: 'paper',
          x: 0.05,
        },
        xaxis: {
          title: {
            text: 'Regions',
            font: {
              family: 'Courier New, monospace',
              size: 18,
              color: '#7f7f7f'
            },
          },
        },
        yaxis: {
          title: {
            text: yAxisText,
            font: {
              family: 'Courier New, monospace',
              size: 18,
              color: '#7f7f7f'
            },
          },
        },
      };
      Plotly.newPlot(result_graph, fy_graph_data, layout);
    }
  });
}

function writeTable(yr, si, area, fp, region, re, be, ach, acqtype, len, total_be_target, total_re_target, total_achievement, yAxisText, result_table) {

  var regions = new Array();

  for (var i = 0; i < region.length; i++) {
    var s = region[i];
    if(!(s in regions)) {
      regions[s] = new Array();
      regions[s]['rowspan'] = 0;
    }
    regions[s]['printed'] = 'no';
    regions[s]['rowspan'] += 1;
  }

  $('#'.concat(result_table)).empty();
  var html = '<h4>Target Achievement - Financial Year: ' + yr + '</h4>' + '<p class="text-left"><strong>' + acqtype + '</strong></p>';
  html += '<table class="table table-bordered table-hover"><thead class="thead-dark"><tr><th scope="col">Region</th><th scope="col">SI</th><th scope="col">Area</th><th scope="col">FP</th><th scope="col" class="text-right">BE Target</th><th scope="col" class="text-right">RE Target</th><th scope="col" class="text-right">Achievement</th></tr></thead><tbody>';
  for (var i = 0; i < region.length; i++) {
    if(regions[region[i]]['printed'] == 'no') {
      html += '<tr><td rowspan = "' + regions[region[i]]['rowspan'] + '">' + region[i] + '</td>';
      regions[region[i]]['printed'] = 'yes';
    }
    
    if(si[i] === 'NULL SI') { si[i] = ''; }
    if(fp[i] === 'GP-NULL') { fp[i] = ''; }
    
    html += '<td class="text-right">' + si[i] +  '</td><td class="text-right">' + area[i] +  '</td><td class="text-right">' + fp[i] +  '</td><td class="text-right">' + be[i] +  '</td><td class="text-right">' + re[i] + '</td><td class="text-right">' + parseFloat(ach[i]).toFixed(4) + '</td></tr>'; 
  }
  html += '<tr><td colspan="4" class="text-right"><strong>Total (' + yAxisText + ')</strong></td><td class="text-right"><strong>' + total_be_target + '</strong></td><td class="text-right"><strong>' + total_re_target + '</strong></td><td class="text-right"><strong>' + parseFloat(total_achievement).toFixed(4) + '</strong></td></tr>';
  html += '</tbody></table>';
  $('#'.concat(result_table)).append(html);
}

function load_data_date_wise(fromdt, todt, acqtype, result_table, result_graph) {
  $.ajax({
    url:"ta_fetch_dt_wise.php",
    method:"POST",
    dataType: 'JSON',
    data:{fromdt: fromdt, todt: todt, acqtype: acqtype},
    //contentType:"application/json; charset=utf-8",
    success:function(data) {

      var len = data.length;
      var si = new Array(len);
      var area = new Array(len);
      var fp = new Array(len);
      var region = new Array(len);
      var ach = new Array(len);

      var total_achievement = 0.0;

      for(var i = 0; i < len; i++) {
        si[i] = data[i].si;
        area[i] = data[i].area;
        fp[i] = data[i].fp;
        region[i] = data[i].region;
        ach[i] = data[i].ach;
      }

      var regions = region.filter((item, i, ar) => ar.indexOf(item) === i); // getting unique regions
      var achmnt = new Array(regions.length).fill(0.0);

      for(var i = 0; i < len; i++) {
        ach[i] = ach[i] || 0.0;
      }

      for(var i = 0; i < len; i++) {
        total_achievement += parseFloat(ach[i]);
      }

      var j = 0;
      for(var i = 0; i < len - 1; i++) {
        if(region[i].localeCompare(region[i + 1]) === 0) {
          achmnt[j] += parseFloat(ach[i]);
        } else {
          achmnt[j] += parseFloat(ach[i]);
          j += 1;
        }
      }
      achmnt[achmnt.length - 1] += parseFloat(ach[len - 1]);

      console.log(len);
      console.log(si);
      console.log(area);
      console.log(fp);
      console.log(region);
      console.log(regions);
      console.log(achmnt);
      console.log(ach);
      console.log(data);

      var yAxisText = ("3D".localeCompare(acqtype)) ? "LKM" : "SKM";

      writeTableDateWise(fromdt, todt, si, area, fp, region, ach, acqtype, len, total_achievement, yAxisText, result_table);

      var trace1 = {
        x: regions,
        y: achmnt,
        name: 'Achievement',
        type: 'bar',
      };

      var fy_graph_data = [trace1];
      var layout = {
        barmode: 'group',
        title: {
          text: 'Achievement ' + '(' + acqtype + ')' + ' between: ' + fromdt + ' to ' + todt,
          font: {
            family: 'Courier New, monospace',
            size: 20
          },
          xref: 'paper',
          x: 0.05,
        },
        xaxis: {
          title: {
            text: 'Regions',
            font: {
              family: 'Courier New, monospace',
              size: 18,
              color: '#7f7f7f'
            },
          },
        },
        yaxis: {
          title: {
            text: yAxisText,
            font: {
              family: 'Courier New, monospace',
              size: 18,
              color: '#7f7f7f'
            },
          },
        },
      };
      Plotly.newPlot(result_graph, fy_graph_data, layout);
    }
  });
}

function writeTableDateWise(fromdt, todt, si, area, fp, region, ach, acqtype, len, total_achievement, yAxisText, result_table) {

  var regions = new Array();

  for (var i = 0; i < region.length; i++) {
    var s = region[i];
    if(!(s in regions)) {
      regions[s] = new Array();
      regions[s]['rowspan'] = 0;
    }
    regions[s]['printed'] = 'no';
    regions[s]['rowspan'] += 1;
  }

  $('#'.concat(result_table)).empty();
  var html = '<h4>Achievement Between: ' + fromdt + ' to ' + todt + '</h4>' + '<p class="text-left"><strong>' + acqtype + '</strong></p>';
  html += '<table class="table table-bordered table-hover"><thead class="thead-dark"><tr><th scope="col">Region</th><th scope="col">SI</th><th scope="col">Area</th><th scope="col">FP</th><th scope="col" class="text-right">Achievement</th></tr></thead><tbody>';
  for (var i = 0; i < region.length; i++) {
    if(regions[region[i]]['printed'] == 'no') {
      html += '<tr><td rowspan = "' + regions[region[i]]['rowspan'] + '">' + region[i] + '</td>';
      regions[region[i]]['printed'] = 'yes';
    }
    
    if(si[i] === 'NULL SI') { si[i] = ''; }
    if(fp[i] === 'GP-NULL') { fp[i] = ''; }
    
    html += '<td class="text-right">' + si[i] +  '</td><td class="text-right">' + area[i] +  '</td><td class="text-right">' + fp[i] +  '</td><td class="text-right">' + parseFloat(ach[i]).toFixed(4) + '</td></tr>'; 
  }
  html += '<tr><td colspan="4" class="text-right"><strong>Total (' + yAxisText + ')</strong></td><td class="text-right"><strong>' + parseFloat(total_achievement).toFixed(4) + '</strong></td></tr>';
  html += '</tbody></table>';
  $('#'.concat(result_table)).append(html);
}


function fetch_data() {
  var yt = document.getElementById('year_type').value;
  if(yt === 'fy' || yt === 'fs') {
    var yr = document.getElementById('fyselect').value;
    if(yr.length === 0) {
      alert("Please provide all the inputs.");
      return false;
    }
    load_data_year_wise(yt, yr, '3D', 'result_table_3D', 'result_graph_3D');
    load_data_year_wise(yt, yr, '2D', 'result_table_2D', 'result_graph_2D');
  } else if(yt === 'dt') {
    var fromdt = document.getElementById('fromdt').value;
    var todt = document.getElementById('todt').value;
    if(fromdt.length === 0 || todt.length === 0) {
      alert("Please provide all the inputs.");
      return false;
    }
    load_data_date_wise(fromdt, todt, '3D', 'result_table_3D', 'result_graph_3D');
    load_data_date_wise(fromdt, todt, '2D', 'result_table_2D', 'result_graph_2D');
  }
};