<?php

include_once 'functions.php';

$si_id = $_REQUEST["si_id"];

if(isset($si_id)) {
	$conn = connect_db();
	$query = "SELECT si_id, si_acq_type FROM si WHERE si_id = $si_id";
	$result = mysqli_query($conn, $query);
	if(mysqli_num_rows($result) > 0) {
		echo '<option value="" disabled selected>Choose one...</option>';
		while($row = mysqli_fetch_array($result)) {
			echo '<option value="'.$row["si_id"].'">'.$row["si_acq_type"].'</option>';
		}
		mysqli_close($conn);
	} else {
		echo 'Data Not Found for DPR Area '.$area;
		mysqli_close($conn);
	}
} else {
	echo 'Did not get the data from client';
}

?>