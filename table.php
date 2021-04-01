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
          <?php
            $conn = connect_db();
            $query = 'SELECT * FROM si ORDER BY si_region';
            $result = mysqli_query($conn, $query);
              if ($result) {
                echo '
                  <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                      <tr><th>Region</th><th>SI</th><th>Area</th><th>2D/3D</th><th>Volume</th><th>Planned Shots</th><th>Coverage Shots</th><th>Remaining Shots</th></tr>
                    </thead>
                    <tbody>
                ';
                while ($row = $result->fetch_assoc()) {
                  $region = fetch_region_name($row['si_region']);
                  $coverage_shots = fetch_coverage_shots($row['si_id']);
                  echo '<tr><td>'.$region.'</td><td>'.$row['si_no'].'</td><td>'.$row['si_area'].'</td><td>'.$row['si_acq_type'].'</td><td>'.$row['si_qow'].'</td><td>'.$row['si_total_shot'].'</td><td>'.$coverage_shots.'</td><td>'.strval(intval($row['si_total_shot']) - intval($coverage_shots)).'</td></tr>';
                }
                echo '</tbody></table>';
              }
              mysqli_close($conn);
          ?>
        </div>
			</div>
      <div style="clear:both">
      
      </div>
    </div>
	</body>
</html>