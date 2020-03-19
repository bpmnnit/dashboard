<?php

include_once 'functions.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>All Data</title>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body onload="load_data()">
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
					<p class="text-center" style="font-size: 150%">All Data</p>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div id="table_result">
						
					</div>
				</div>
			</div>
		<div style="clear:both"></div>
	</body>
</html>
<script>
$(document).ready(function(){
	$('#ta_fetch').click(function() {
		var fy = $('#fyselect').val();
		var acqtype = $('#acqtypeselect option:selected').text();
		if(fy.length == 0 || acqtype.length == 0) {
			alert("Please provide all the inputs.");
			return false;
		}
		
		load_data(fy, acqtype);			
	});	

	function writeTable(fy, reg, re_target, be_target, achievement, acqtype, len) {
		
		$('#table_result').empty();
		var html = '<h4>Financial Year Wise Data</h4><p class="text-left"><strong>' + acqtype + '</strong></p>';		
		$('#table_result').append(html);
	}
});

function load_data() {
	$.ajax({
		url:"ta_data_fetch.php",
		method:"POST",
		dataType: 'JSON',
		data:{},
		//contentType:"application/json; charset=utf-8",
		success:function(data) {

			var len = data.length;
			var reg = new Array(len);
			var re_target = new Array(len);
			var be_target = new Array(len);
			var achievement = new Array(len);
			var fy = new Array(len);

			/*	
			var total_be_target = 0.0;
			var total_re_target = 0.0;
			var total_achievement = 0.0;
			*/

			for (var i = 0; i < len; i++) {
				reg[i] = data[i].reg;
				re_target[i] = data[i].re_target;
				be_target[i] = data[i].be_target;
				achievement[i] = data[i].achievement;
				fy[i] = data[i].fy;
			}

			for (var i = 0; i < len; i++) {
				re_target[i] = re_target[i] || 0.0;
				be_target[i] = be_target[i] || 0.0;
				achievement[i] = achievement[i] || 0.0;
				fy[i] = fy[i] || 0.0;
				reg[i] = reg[i] || '';
			}
			
			/*
			for (var i = 0; i < len; i++) {
				total_be_target += parseFloat(be_target[i]);
				total_re_target += parseFloat(re_target[i]);
				total_achievement += parseFloat(achievement[i]);
			}
			*/

			console.log(len);
			console.log(reg);
			console.log(re_target);
			console.log(be_target);
			console.log(achievement);
			console.log(fy);
			console.log(data);

			//var yAxisText = ("3D".localeCompare(acqtype)) ? "LKM" : "SKM";

			writeTable(fy, reg, re_target, be_target, achievement, "3D", len);

			/*
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
					text: acqtype + ' Target vs Achievement for FY: ' + fy,
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
			*/
		}
	});
}
</script>

<script type="text/javascript">
	$("#fpselect").select2();
</script>