<?php

include_once 'functions.php';

$conn = connect_db();
$return_arr = array();

if(isset($_POST["fromdt"]) && isset($_POST["todt"]) && isset($_POST["acqtype"])) {

	$fromdt = mysqli_real_escape_string($conn, $_POST["fromdt"]);
	$todt = mysqli_real_escape_string($conn, $_POST["todt"]);
  $acqtype = mysqli_real_escape_string($conn, $_POST["acqtype"]);
 
  $query = "SELECT region_name AS Region, si_no AS SI, si_area AS Area, field_party_name AS FP, SUM(dpr_coverage) AS Ach FROM dpr_onland
	  INNER JOIN si ON dpr_onland.dpr_si = si.si_id 
    INNER JOIN field_parties ON dpr_onland.dpr_field_party = field_parties.field_party_id 
    INNER JOIN regions ON si.si_region = regions.region_id 
    WHERE dpr_onland.dpr_date BETWEEN \"$fromdt\" AND \"$todt\" AND si_acq_type LIKE \"%$acqtype%\"
    GROUP BY Area, SI, FP ORDER BY Region";

  $result = mysqli_query($conn, $query);
  
  if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
      $si = $row["SI"];
      $area = $row["Area"];
      $fp = $row["FP"];
      $region = $row["Region"];
      $ach = $row["Ach"];

      $return_arr[] = array(
        'si' => $si,
        'area' => $area,
        'fp' => $fp,
        'region' => $region,
        'ach' => $ach,
      );
    }
    echo json_encode($return_arr);
    mysqli_close($conn);
  } else {
    echo 'Data Not Found';
  }
}	

?>