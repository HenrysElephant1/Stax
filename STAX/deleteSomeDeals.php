<?php
	$host = "staxsmysql.mysql.database.azure.com";
	$db_name = "stax_";
	$username = "master_stax@staxsmysql";
	$password = "dev2017softwareB0C@";

	$conn = mysqli_init();
	mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306);

	if(mysqli_connect_errno($conn)){
		die('Failed to connect to MySQL: '.mysqli_connect_error());
	}
	else {
		echo "Connected";
	}

	$query5 = "DELETE FROM deals WHERE dealID = 35;";
	$result5 = mysqli_query( $conn, $query5 );

	$query6 = "DELETE FROM deals WHERE dealID = 34;";
	$result6 = mysqli_query( $conn, $query6 );

	$query7 = "DELETE FROM deals WHERE dealID = 32;";
	$result7 = mysqli_query( $conn, $query7 );
?>