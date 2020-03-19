<?php

include_once 'functions.php';

$conn = connect_db();
$output = '';

if(isset($_POST["fp"]) && isset($_POST["fromdate"]) && isset($_POST["todate"])) {

	$fp = mysqli_real_escape_string($conn, $_POST["fp"]);
	$fromdate = mysqli_real_escape_string($conn, $_POST["fromdate"]);
	$todate = mysqli_real_escape_string($conn, $_POST["todate"]);
	$query = "SELECT * FROM dpr_onland WHERE dpr_field_party = $fp AND dpr_date BETWEEN \"$fromdate\" AND \"$todate\"";
	$result = mysqli_query($conn, $query);
	if(mysqli_num_rows($result) > 0) {
		$output .= '<div class="table-responsive">
						<table class="table table-striped table-bordered">
							<tr>
								<th>Acc</th>
								<th>Rej</th>
								<th>Skp</th>
								<th>Rep</th>
							</tr>';
		while($row = mysqli_fetch_array($result))
		{
			$output .= '
				<tr>
					<td>'.$row["dpr_shots_acc"].'</td>
					<td>'.$row["dpr_shots_rej"].'</td>
					<td>'.$row["dpr_shots_skip"].'</td>
					<td>'.$row["dpr_shots_rep"].'</td>
				</tr>
			';
		}
		echo $output;
		mysqli_close($connect);
	}
	else
	{
		echo 'Data Not Found';
	}
} else {
	echo 'Did not get the data from client';
}	

?>