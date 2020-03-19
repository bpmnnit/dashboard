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

?>