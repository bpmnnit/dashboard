<?php

include_once 'functions.php';

$conn = connect_db();
$return_arr = array();

if(isset($_POST["fy"]) && isset($_POST["acqtype"])) {

	$fy = mysqli_real_escape_string($conn, $_POST["fy"]);
	$acqtype = mysqli_real_escape_string($conn, $_POST["acqtype"]);
	$query = "SELECT (SELECT region_name from regions WHERE region_id = target_achievement_region) as Region, target_achievement_basin as Basin, SUM(target_achievement_re_target) as RE_Target , SUM(target_achievement_be_target) as BE_Target, SUM(target_achievement_achievement) as Achievement FROM target_vs_achievement WHERE target_achievement_fy = \"$fy\" AND target_achievement_acq_type LIKE \"%$acqtype%\" GROUP BY target_achievement_region, target_achievement_basin order by target_achievement_region ASC";
	$result = mysqli_query($conn, $query);
	if(mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_array($result)) {
			$reg = $row["Region"];
			$re_target = $row["RE_Target"];
			$be_target = $row["BE_Target"];
			$achievement = $row["Achievement"];
			$basin = $row["Basin"];

			$return_arr[] = array(
				'reg' => $reg,
				're_target' => $re_target,
				'be_target' => $be_target,
				'achievement' => $achievement,
				'basin' => $basin,
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