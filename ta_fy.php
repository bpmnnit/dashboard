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
				<div class="col-lg-12">
					<p class="text-center" style="font-size: 150%">Financial-Year/Field-Season/Date Wise Data</p>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-5 col-md-12 col-sm-12">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <label class="input-group-text" for="year_type">Select an Option</label>
            </div>
            <select class="custom-select" style="width: 80%;" id="year_type" onchange="displayDates()">
              <option value="" disabled selected>Select One...</option>
              <option value="fy">Financial Year</option>
              <option value="fs">Field Season</option>
              <option value="dt">Date Range</option>
            </select>
          </div>
        </div>
				<div class="col-lg-5 col-md-12 col-sm-12" id="fy-or-fs">
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<label class="input-group-text" for="fyselect">FY/FS</label>
						</div>
						<select class="custom-select" style="width: 80%;" id="fyselect">
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
        <div class="col-lg-5 col-md-12 col-sm-12" id="date-wise" style="display:none">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">From</span>
            </div>
            <input id="fromdt" type="date" class="form-control">
						<div class="input-group-prepend">
							<span class="input-group-text">To</span>
						</div>
						<input id="todt" type="date" class="form-control">
					</div>
        </div>
        <div class="col-lg-2">
					<button id="ta_fetch" type="button" class="btn btn-info" onclick="fetch_data()">Go!</button>
				</div>
      </div>
			<div class="row">
				<div class="col-lg-6 col-md-12 col-sm-12">
					<div id="result_table_3D">
						
					</div>
				</div>
      	<div class="col-lg-6 col-md-12 col-sm-12">
					<div id="result_graph_3D"></div>
				</div>
			</div>
      <div class="row">
				<div class="col-lg-6 col-md-12 col-sm-12">
					<div id="result_table_2D">
						
					</div>
				</div>
				<div class="col-lg-6 col-md-12 col-sm-12">
					<div id="result_graph_2D"></div>
				</div>
			</div>
		<div style="clear:both"></div>
	</body>
</html>