<?php

include_once 'functions.php';

$conn = connect_db();
$return_arr = array();

if(isset($_POST["yt"]) && isset($_POST["yr"]) && isset($_POST["acqtype"])) {

	$yt = mysqli_real_escape_string($conn, $_POST["yt"]);
	$fy = mysqli_real_escape_string($conn, $_POST["yr"]);
  $acqtype = mysqli_real_escape_string($conn, $_POST["acqtype"]);
  if($yt == 'fy') {
    //$query = "SELECT (SELECT region_name from regions WHERE region_id = target_achievement_region) as Region, target_achievement_basin as Basin, SUM(target_achievement_re_target) as RE_Target , SUM(target_achievement_be_target) as BE_Target, SUM(target_achievement_achievement) as Achievement FROM target_vs_achievement WHERE target_achievement_fy = \"$fy\" AND target_achievement_acq_type LIKE \"%$acqtype%\" GROUP BY target_achievement_region, target_achievement_basin order by target_achievement_region ASC";

    $query = 'SELECT (SELECT si_no FROM si WHERE si_id = target_achievement_si) AS SI, (SELECT field_party_name FROM field_parties WHERE field_party_id = target_achievement_field_party) AS FP, (SELECT region_name FROM regions WHERE region_id = target_achievement_region) AS Region,
      target_achievement_re_target AS RE, target_achievement_be_target AS BE, target_achievement_achievement AS Ach,  target_achievement_area AS Area FROM target_vs_achievement WHERE target_achievement_fy = "'.$fy.'" AND target_achievement_acq_type LIKE "%'.$acqtype.'%" ORDER BY Region';
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_array($result)) {
        $si = $row["SI"];
        $area = $row["Area"];
        $fp = $row["FP"];
        $region = $row["Region"];
        $re = $row["RE"];
        $be = $row["BE"];
        $ach = $row["Ach"];

        $return_arr[] = array(
          'si' => $si,
          'area' => $area,
          'fp' => $fp,
          'region' => $region,
          're' => $re,
          'be' => $be,
          'ach' => $ach,
        );
      }
      echo json_encode($return_arr);
      mysqli_close($conn);
    } else {
      echo 'Data Not Found';
    }
  }
} else {
	echo 'Did not get the data from client';
}	

?>