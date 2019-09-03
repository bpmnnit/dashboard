<?php

include_once 'functions.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Geophysical Services - Dashboard</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
				<div class="col-lg-1"></div>
				<div class="col-lg-3">
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<label class="input-group-text" for="fpselect">Field Party</label>
						</div>
						<select class="custom-select" id="fpselect">
							<option value="" disabled selected>Choose one...</option>
							<?php 
								$conn = connect_db();
								$query = 'SELECT * FROM field_parties';
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
							<span class="input-group-text">From Date</span>
						</div>
						<input id="fromdate" type="date" class="form-control">
					</div>
				</div>
				<div class="col-lg-3">
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
				<div class="col-lg-1"></div>	
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
	function load_data(fp, fromdate, todate, fp_text) {
		$.ajax({
			url:"fetch.php",
			method:"POST",
			dataType: 'JSON',
			data:{fp:fp, fromdate:fromdate, todate:todate},
			//contentType:"application/json; charset=utf-8",
			success:function(data) {

				var len = data.length;
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

				for (var i = 0; i < len; i++) {
					acc[i] = data[i].acc; total_acc += parseInt(data[i].acc);
					rej[i] = data[i].rej; total_rej += parseInt(data[i].rej);
					skp[i] = data[i].skp; total_skp += parseInt(data[i].skp);
					rep[i] = data[i].rep; total_rep += parseInt(data[i].rep);
					cov[i] = data[i].cov; total_cov += parseFloat(data[i].cov);
					dt[i] = data[i].dt;						
				}

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

				var trace1 = {
					x: dt,
					y: acc,
					name: 'Accepted',
					type: 'bar'
				};
				var trace2 = {
					x: dt,
					y: rej,
					name: 'Rejected',
					type: 'bar'
				};
				var trace3 = {
					x: dt,
					y: skp,
					name: 'Skippped',
					type: 'bar'
				};
				var trace4 = {
					x: dt,
					y: rep,
					name: 'Repeated',
					type: 'bar'
				};

				var pie_data = [{
					values: [total_acc, total_rej, total_skp, total_rep],
					labels: ['Accepted', 'Rejected', 'Skippped', 'Repeated'],
					type: 'pie',
					hoverinfo: 'label+value+percent',
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
				};
				Plotly.newPlot('result', graph_data, layout);
				Plotly.newPlot('pie_result', pie_data, pie_layout);
				writeTable(total_acc, total_rej, total_skp, total_rep, total_cov, fp_text, fromdate, todate);
			}
		});
	}

	$('#fetch').click(function() {
		var fp = $('#fpselect').val();
		var fp_text = $('#fpselect option:selected').text();
		if(fp.length == 0) {
			alert("Please select a field party.");
			return false;
		}
		var fromdate = $('#fromdate').val();
		var todate = $('#todate').val();
		if(fromdate.length == 0 || todate.length == 0) {
			alert(fromdate + ' ' + todate);
			return false;
		}
		var fromdt= new Date(fromdate);
		var todt = new Date(todate);
		if(todt.getTime() < fromdt.getTime()) {
			alert("To date is less than from date");
			return false;
		}

		load_data(fp, fromdate, todate, fp_text);			
	});	

	function writeTable(total_acc, total_rej, total_skp, total_rep, total_cov, fp_text, fromdate, todate) {
		
		$('#table_result').empty();
		var html = '<h4>' + fp_text + '</h4><p class="text-left">' + fromdate + ' to ' + todate + '</p>';
		html += '<table class="table table-bordered table-striped table-hover"><thead class="thead-dark"><tr><th scope="col">Item</th><th scope="col">Value</th></tr></thead>';
		html += '<tbody><tr><td>Accepted Shots</td><td>' + total_acc + '</td></tr><tr><td>Rejected Shots</td><td>' + total_rej + '</td></tr><tr><td>Skipped Shots</td><td>' + total_skp + '</td></tr><tr><td>Repeated Shots</td><td>' + total_rep + '</td></tr><tr><td>Total Coverage (SKM)</td><td>' + total_cov.toFixed(4) + '</td></tr></tbody></table>';
		$('#table_result').append(html);
	}
});
</script>