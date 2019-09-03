<?php

include_once 'functions.php';

$conn = connect_db();
$return_arr = array();

if(isset($_POST["fp"]) && isset($_POST["fromdate"]) && isset($_POST["todate"])) {

	$fp = mysqli_real_escape_string($conn, $_POST["fp"]);
	$fromdate = mysqli_real_escape_string($conn, $_POST["fromdate"]);
	$todate = mysqli_real_escape_string($conn, $_POST["todate"]);
	$query = "SELECT * FROM dpr_onland WHERE dpr_field_party = $fp AND dpr_date BETWEEN \"$fromdate\" AND \"$todate\"";
	$result = mysqli_query($conn, $query);
	if(mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_array($result)) {
			$acc = $row["dpr_shots_acc"];
			$rej = $row["dpr_shots_rej"];
			$skp = $row["dpr_shots_skip"];
			$rep = $row["dpr_shots_rep"];
			$cov = $row["dpr_coverage"];
			$dt = $row["dpr_date"];

			$return_arr[] = array(
				'acc' => $acc,
				'rej' => $rej,
				'skp' => $skp,
				'rep' => $rep,
				'cov' => $cov,
				'dt' => $dt,
			);
		}
		echo json_encode($return_arr);
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