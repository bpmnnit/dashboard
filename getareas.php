<?php

include_once 'functions.php';

$fp = $_REQUEST["fp"];

if(isset($fp)) {
	$conn = connect_db();
  $query = "SELECT si_id, concat(si_area, ' (', si_no, ')') as areas FROM cgsdb.si where si_fp LIKE \"%$fp%\"";
	$result = mysqli_query($conn, $query);
	if(mysqli_num_rows($result) > 0) {
		echo '<option value="" disabled selected>Choose one...</option>';
		while($row = mysqli_fetch_array($result)) {
			echo '<option value="'.$row["si_id"].'">'.$row["areas"].'</option>';
		}
		mysqli_close($conn);
	} else {
		echo 'Data Not Found for field party with ID '.$fp;
		mysqli_close($conn);
	}
} else {
	echo 'Did not get the data from client';
}

?>