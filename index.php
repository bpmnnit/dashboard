<?php
	include_once 'functions.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Geophysical Services - Dashboard</title>

		<script src="scripts/jquery.min.js"></script>
		<script src="scripts/plotly-latest.min.js"></script>
		<script src="scripts/popper.min.js"></script>
		<script src="scripts/bootstrap.min.js"></script>
		<script src="scripts/select2.min.js"></script>
		<script src="scripts/main.js"></script>

		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link href="css/select2.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="bar">
						<p class="text-center">Geophysical Services Dashboard</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-2">
					<div class="input-group">
						<div class="input-group-prepend">
							<label class="input-group-text" for="fpselect">Field Party</label>
						</div>
						<select class="js-example-responsive" style="width: 60%;" id="fpselect" onchange="create_fp_area();">
							<option value="" disabled selected>Choose one...</option>
							<?php 
								$conn = connect_db();
								$query = 'SELECT * FROM field_parties ORDER BY field_party_name';
								$result = mysqli_query($conn, $query);
								if ($result) {
									while ($row = $result->fetch_assoc()) {
										echo '<option value="'.$row['field_party_id'].'">'.$row['field_party_name'].'</option>';
									}
								}
								mysqli_close($conn);
							?>
						</select>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="input-group">
						<div class="input-group-prepend">
							<label class="input-group-text" for="areaselect">Area</label>
						</div>
						<select id="areaselect" style="width: 75%;" onchange="drop_down_acq_type();"></select>
					</div>
				</div>
				<div class="col-lg-2">
					<div class="input-group">
						<div class="input-group-prepend">
							<label class="input-group-text" for="acqtypeselect">Type</label>
						</div>
						<select id="acqtypeselect" style="width: 75%;" onchange="create_dates_limit();">
						</select>
					</div>
				</div>
				<div class="col-lg-2">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">From Date</span>
						</div>
						<input id="fromdate" type="date" class="form-control">
					</div>
				</div>
				<div class="col-lg-2">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">To Date</span>
						</div>
						<input id="todate" type="date" class="form-control">
					</div>
				</div>
				<div class="col-lg-1">
					<button id="fetch" type="button" class="btn btn-info">Go!</button>
				</div>	
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div id="result"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4">
					<div id="table_result">
						
					</div>
				</div>
				<div class="col-lg-4">
					<div id="pie_result"></div>
				</div>
				<div class="col-lg-4"></div>
			</div>
		<div style="clear:both"></div>
	</body>
</html>
<script>
$(document).ready(function(){
	function load_data(fp, fromdate, todate, fp_text, dprarea, atype, si) {
		$.ajax({
			url:"fetch.php",
			method:"POST",
			dataType: 'JSON',
			data:{fp:fp, fromdate:fromdate, todate:todate, dprarea:dprarea, atype:atype, si: si},
			//contentType:"application/json; charset=utf-8",
			success:function(data) {

				var len = data.length - 1;
				var acc = new Array(len);
				var rej = new Array(len);
				var skp = new Array(len);
				var rep = new Array(len);
				var cov = new Array(len);
				var dt = new Array(len);

				var total_acc = 0;
				var total_rej = 0;
				var total_skp = 0;
				var total_rep = 0;
				var total_cov = 0.0;
				var mgh = parseInt(data[len].mgh);

				for (var i = 0; i < len; i++) {
					acc[i] = data[i].acc; total_acc += parseInt(data[i].acc);
					rej[i] = data[i].rej; total_rej += parseInt(data[i].rej);
					skp[i] = data[i].skp; total_skp += parseInt(data[i].skp);
					rep[i] = data[i].rep; total_rep += parseInt(data[i].rep);
					cov[i] = data[i].cov; total_cov += parseFloat(data[i].cov);
					dt[i] = data[i].dt;
				}
        
        $('#result').show();  
        $('#table_result').show();  
        $('#pie_result').show();

				console.log(acc);
				console.log(rej);
				console.log(skp);
				console.log(rep);
				console.log(cov);
				console.log(dt);

				console.log(total_acc);
				console.log(total_rej);
				console.log(total_skp);
				console.log(total_rep);
				console.log(total_cov);
				console.log(mgh);

				var trace1 = {
					x: dt,
					y: acc,
					name: 'Accepted',
					type: 'bar',
					marker: {
						color: 'rgb(14,197,29)',
					}
				};
				var trace2 = {
					x: dt,
					y: rej,
					name: 'Rejected',
					type: 'bar',
					marker: {
						color: 'rgb(227,0,0)',
					}
				};
				var trace3 = {
					x: dt,
					y: skp,
					name: 'Skippped',
					type: 'bar',
					marker: {
						color: 'rgb(0,35,255)',
					}
				};
				var trace4 = {
					x: dt,
					y: rep,
					name: 'Repeated',
					type: 'bar',
					marker: {
						color: 'rgb(242,225,25)',
					}
				};

				var colors = ['rgb(14,197,29)', 'rgb(227,0,0)', 'rgb(0,35,255)', 'rgb(242,225,25)'];

				var pie_data = [{
					values: [total_acc, total_rej, total_skp, total_rep],
					labels: ['Accepted', 'Rejected', 'Skippped', 'Repeated'],
					type: 'pie',
					hoverinfo: 'label+value+percent',
					marker: {
						colors: colors,
					}
				}];
				var pie_layout = {
					title: {
						text: 'Shots Distribution',
						font: {
							family: 'Courier New, monospace',
							size: 24
						},
						xref: 'paper',
						x: 0.05,
					},
					height: 400,
					width: 600,
					showlegend: true,
					margin: 0,
				};

				var graph_data = [trace1, trace2, trace3, trace4];
				var layout = {
					barmode: 'stack',
					title: {
						text: 'DPR: ' + fp_text + ' (' + fromdate + ' to ' + todate + ')',
						font: {
							family: 'Courier New, monospace',
							size: 24
						},
						xref: 'paper',
						x: 0.05,
					},
					xaxis: {
						title: {
							text: 'Date',
							font: {
								family: 'Courier New, monospace',
								size: 18,
								color: '#7f7f7f'
							},
						},
					},
					yaxis: {
						title: {
							text: 'Shots',
							font: {
								family: 'Courier New, monospace',
								size: 18,
								color: '#7f7f7f'
							},
						},
					},
					shapes: [
				    {
				        type: 'line',
				        xref: 'paper',
				        x0: 0,
				        y0: mgh,
				        x1: 1,
				        y1: mgh,
				        line:{
				            color: 'rgb(255, 0, 0)',
				            width: 2,
				            dash:'dashdot'
				        }
				    }
				    ]
				};
				Plotly.newPlot('result', graph_data, layout);
				Plotly.newPlot('pie_result', pie_data, pie_layout);
				writeTable(total_acc, total_rej, total_skp, total_rep, total_cov, fp_text, fromdate, todate);
			}
		});
	};

	$('#fetch').click(function() {
    $('#result').hide();
    $('#table_result').hide();
    $('#pie_result').hide();
    
		var fp = $('#fpselect').val();
		var fp_text = $('#fpselect option:selected').text();
		var dprarea = $('#areaselect option:selected').text();
		var atype = $('#acqtypeselect option:selected').text();
		var si = $('#acqtypeselect option:selected').val();
		if(fp.length == 0) {
			alert("Please select a field party.");
			return false;
		}
		var fromdate = $('#fromdate').val();
		var todate = $('#todate').val();
		if(fromdate.length == 0) {
			alert("Start date is nil.");
			return false;
		}
		else if(fromdate.length > 0 && todate.length == 0) {
			todate = formatDate(new Date());
		}
		var fromdt= new Date(fromdate);
		var todt = new Date(todate);
		if(todt.getTime() < fromdt.getTime()) {
			alert("To date is less than from date");
			return false;
		}

		load_data(fp, fromdate, todate, fp_text, dprarea, atype, si);	
	});	

	function writeTable(total_acc, total_rej, total_skp, total_rep, total_cov, fp_text, fromdate, todate) {

		$('#table_result').empty();
		var html = '<h4>' + fp_text + '</h4><p class="text-left">' + fromdate + ' to ' + todate + '</p>';
		html += '<table class="table table-bordered table-striped table-hover"><thead class="thead-dark"><tr><th scope="col">Item</th><th scope="col">Value</th></tr></thead>';
		html += '<tbody><tr><td>Accepted Shots</td><td>' + total_acc + '</td></tr><tr><td>Rejected Shots</td><td>' + total_rej + '</td></tr><tr><td>Skipped Shots</td><td>' + total_skp + '</td></tr><tr><td>Repeated Shots</td><td>' + total_rep + '</td></tr><tr><td>Total Coverage (SKM/LKM)</td><td>' + total_cov.toFixed(4) + '</td></tr></tbody></table>';
		$('#table_result').append(html);
	}
});
</script>

<script type="text/javascript">
	$("#fpselect").select2();
</script>