<?php

include_once 'functions.php';

$si = $_REQUEST["si"];
$si_start_date = '';
$si_end_date = '';
if(isset($si)) {
	$conn = connect_db();
	$query = "SELECT si_start_date FROM si where si_id = $si";
	$result = mysqli_query($conn, $query);
	if(mysqli_num_rows($result) == 1) {
		$row = mysqli_fetch_array($result);
		$si_start_date = $row['si_start_date'];
		mysqli_close($conn);
	} else {
		echo 'Data Not Found for Acquisition Type: '.$acq;
		mysqli_close($conn);
	}

	$conn = connect_db();
	$query = "SELECT si_end_date FROM si where si_id = $si";
	$result = mysqli_query($conn, $query);
	if(mysqli_num_rows($result) == 1) {
		$row = mysqli_fetch_array($result);
		$si_end_date = $row['si_end_date'];
		mysqli_close($conn);
	} else {
		echo 'Data Not Found for Acquisition Type: '.$acq;
		mysqli_close($conn);
	}
	
	echo $si_start_date .'|'.$si_end_date;
	
} else {
	echo 'Did not get the data from client';
}

?>