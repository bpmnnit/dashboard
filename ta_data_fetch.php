<?php

include_once 'functions.php';

$conn = connect_db();
$return_arr = array();

$query = "SELECT SUM(target_achievement_be_target) as BE_Target, SUM(target_achievement_re_target) as RE_Target, SUM(target_achievement_achievement) as Achievement, (SELECT region_name FROM regions WHERE region_id = target_achievement_region) as Region, target_achievement_fy as FY FROM target_vs_achievement WHERE target_achievement_acq_type LIKE '%3D%' GROUP BY target_achievement_fy, target_achievement_region ORDER BY target_achievement_fy ASC";

$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_array($result)) {
		$reg = $row["Region"];
		$re_target = $row["RE_Target"];
		$be_target = $row["BE_Target"];
		$achievement = $row["Achievement"];
		$fy = $row["FY"];

		$return_arr[] = array(
			'reg' => $reg,
			're_target' => $re_target,
			'be_target' => $be_target,
			'achievement' => $achievement,
			'fy' => $fy,
		);
	}
	echo json_encode($return_arr);
	mysqli_close($conn);
}
else
{
	echo 'Data Not Found';
}

?>