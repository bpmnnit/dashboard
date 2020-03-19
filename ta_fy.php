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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
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
				<div class="col-lg-12">
					<p class="text-center" style="font-size: 150%">Financial Year Wise Data</p>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-3"></div>
				<div class="col-lg-3">
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<label class="input-group-text" for="fyselect">FY</label>
						</div>
						<select class="js-example-responsive" style="width: 75%;" id="fyselect">
							<option value="" disabled selected>Choose one...</option>
							<?php 
								$conn = connect_db();
								$query = 'SELECT DISTINCT(target_achievement_fy) FROM target_vs_achievement ORDER BY target_achievement_fy ASC';
								$result = mysqli_query($conn, $query);
								if ($result) {
									while ($row = $result->fetch_assoc()) {
										echo '<option value="'.$row['target_achievement_fy'].'">'.$row['target_achievement_fy'].'</option>';
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
							<label class="input-group-text" for="acqtypeselect">Acq Type</label>
						</div>
						<select class="js-example-responsive" style="width: 75%;" id="acqtypeselect">
							<option value="" disabled selected>Choose one...</option>
							<option value="3D">3D</option>
							<option value="2D">2D</option>
						</select>
					</div>
				</div>
				<div class="col-lg-1">
					<button id="ta_fetch" type="button" class="btn btn-info">Go!</button>
				</div>
				<div class="col-lg-2"></div>
			</div>
			<div class="row">
				<div class="col-lg-4">
					<div id="table_result">
						
					</div>
				</div>
				<div class="col-lg-6">
					<div id="result"></div>
				</div>
				<div class="col-lg-2"></div>
			</div>
		<div style="clear:both"></div>
	</body>
</html>
<script>
$(document).ready(function(){
	function load_data(fy, acqtype) {
		$.ajax({
			url:"ta_fetch.php",
			method:"POST",
			dataType: 'JSON',
			data:{fy:fy, acqtype:acqtype},
			//contentType:"application/json; charset=utf-8",
			success:function(data) {

				var len = data.length;
				var reg = new Array(len);
				var re_target = new Array(len);
				var be_target = new Array(len);
				var achievement = new Array(len);
				var basin = new Array(len);

				var total_be_target = 0.0;
				var total_re_target = 0.0;
				var total_achievement = 0.0;

				for (var i = 0; i < len; i++) {
					reg[i] = data[i].reg;
					basin[i] = data[i].basin;
					re_target[i] = data[i].re_target;
					be_target[i] = data[i].be_target;
					achievement[i] = data[i].achievement;
				}

				for (var i = 0; i < len; i++) {
					re_target[i] = re_target[i] || 0.0;
					be_target[i] = be_target[i] || 0.0;
					achievement[i] = achievement[i] || 0.0;
				}
	
				for (var i = 0; i < len; i++) {
					total_be_target += parseFloat(be_target[i]);
					total_re_target += parseFloat(re_target[i]);
					total_achievement += parseFloat(achievement[i]);
				}

				console.log(len);
				console.log(reg);
				console.log(basin);
				console.log(re_target);
				console.log(be_target);
				console.log(achievement);
				console.log(data);

				var yAxisText = ("3D".localeCompare(acqtype)) ? "LKM" : "SKM";

				writeTable(fy, reg, basin, re_target, be_target, achievement, acqtype, len, total_be_target, total_re_target, total_achievement, yAxisText);

				var trace1 = {
					x: reg,
					y: be_target,
					name: 'BE Target',
					type: 'bar'
				};
				var trace2 = {
					x: reg,
					y: re_target,
					name: 'RE Target',
					type: 'bar'
				};
				var trace3 = {
					x: reg,
					y: achievement,
					name: 'Achievement',
					type: 'bar'
				};

				var fy_graph_data = [trace1, trace2, trace3];
				var layout = {
					barmode: 'group',
					title: {
						text: 'Target vs Achievement for FY: ' + fy,
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
				Plotly.newPlot('result', fy_graph_data, layout);
			}
		});
	}

	$('#ta_fetch').click(function() {
		var fy = $('#fyselect').val();
		var acqtype = $('#acqtypeselect option:selected').text();
		if(fy.length == 0 || acqtype.length == 0) {
			alert("Please provide all the inputs.");
			return false;
		}
		
		load_data(fy, acqtype);			
	});	

	function writeTable(fy, reg, basin, re_target, be_target, achievement, acqtype, len, total_be_target, total_re_target, total_achievement, yAxisText) {

		var regs = new Array();

		for (var i = 0; i < reg.length; i++) {
			var s = reg[i];
			if(!(s in regs)) {
				regs[s] = new Array();
				regs[s]['rowspan'] = 0;
			}
			regs[s]['printed'] = 'no';
			regs[s]['rowspan'] += 1;
		}

		$('#table_result').empty();
		var html = '<h4>Target Achievement - Financial Year: ' + fy + '</h4>' + '<p class="text-left"><strong>' + acqtype + '</strong></p>';
		html += '<table class="table table-bordered table-hover"><thead class="thead-dark"><tr><th scope="col">Region</th><th scope="col">Basin</th><th scope="col" class="text-right">BE Target</th><th scope="col" class="text-right">RE Target</th><th scope="col" class="text-right">Achievement</th></tr></thead><tbody>';
		for (var i = 0; i < reg.length; i++) {
			if(regs[reg[i]]['printed'] == 'no') {
				html += '<tr><td rowspan = "' + regs[reg[i]]['rowspan'] + '">' + reg[i] + '</td>';
				regs[reg[i]]['printed'] = 'yes';
			}
			html += '<td>' + basin[i] + '</td><td class="text-right">' + be_target[i] +  '</td><td class="text-right">' + re_target[i] + '</td><td class="text-right">' + parseFloat(achievement[i]).toFixed(4) + '</td></tr>'; 
		}
		html += '<tr><td colspan="2" class="text-right"><strong>Total (' + yAxisText + ')</strong></td><td class="text-right"><strong>' + total_be_target + '</strong></td><td class="text-right"><strong>' + total_re_target + '</strong></td><td class="text-right"><strong>' + parseFloat(total_achievement).toFixed(4) + '</strong></td></tr>';
		html += '</tbody></table>';
		$('#table_result').append(html);
	}
});
</script>

<script type="text/javascript">
	$("#fpselect").select2();
</script>