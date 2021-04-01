<?php

function connect_db() {
	$host = 'localhost';
	$user = 'root';
	$password = 'harekrishna';
	$db = 'cgsdb';

	$conn = mysqli_connect($host, $user, $password, $db);

	if(!$conn) { die('Connection faild: ' . mysqli_connect_error()); }

	return $conn;
}

function fetch_region_name($region_id) {
  $conn = connect_db();
  $query = "SELECT region_name FROM regions WHERE region_id = $region_id";
  if($r = mysqli_query($conn, $query)) {
    $row = mysqli_fetch_row($r);
    return $row[0];
  } else {
    return '';
  }
  mysqli_close($conn);
}

function fetch_coverage_shots($si_id) {
  $conn = connect_db();
  $query = "SELECT SUM(dpr_shots_acc) + SUM(dpr_shots_rej) + SUM(dpr_shots_skip) + SUM(dpr_shots_rec) - SUM(dpr_shots_rep) as dpr_cov_shots FROM dpr_onland where dpr_si = $si_id";
  if($r = mysqli_query($conn, $query)) {
    $row = mysqli_fetch_row($r);
    return $row[0];
  } else {
    return '';
  }
  mysqli_close($conn);
}

?>