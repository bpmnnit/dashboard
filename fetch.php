<?php

include_once 'functions.php';

$conn = connect_db();
$return_arr = array();

if(isset($_POST["si"]) && isset($_POST["fromdate"]) && isset($_POST["todate"])) {

	$si = mysqli_real_escape_string($conn, $_POST["si"]);
	$fromdate = mysqli_real_escape_string($conn, $_POST["fromdate"]);
	$todate = mysqli_real_escape_string($conn, $_POST["todate"]);
	//$dprarea = mysqli_real_escape_string($conn, $_POST["dprarea"]);
	//$atype = mysqli_real_escape_string($conn, $_POST["atype"]);
	//$query = "SELECT * FROM dpr_onland WHERE dpr_field_party = $fp AND dpr_area = \"$dprarea\" AND dpr_acq_type = \"$atype\" AND dpr_date BETWEEN \"$fromdate\" AND \"$todate\"";
	$query = "SELECT dpr_onland.* FROM dpr_onland LEFT JOIN si ON dpr_onland.dpr_si = si.si_id WHERE dpr_onland.dpr_date BETWEEN \"$fromdate\" AND \"$todate\" and dpr_onland.dpr_si = $si ORDER BY dpr_onland.dpr_date ASC";
	//echo $query;
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

		$query = "SELECT si.si_mgh FROM si WHERE si.si_id = $si";
		$result = mysqli_query($conn, $query);

		while($row = mysqli_fetch_array($result)) {
			$mgh = $row["si_mgh"];

			$return_arr[] = array(
				'mgh' => $mgh,
			);
		}

		echo json_encode($return_arr);
		mysqli_close($conn);
	}
	else
	{
		echo 'Data Not Found';
	}
} else {
	echo 'Did not get the data from client';
}	

?>