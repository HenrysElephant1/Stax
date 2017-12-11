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
	
	$query = "DELETE FROM votes;";
	$result = mysqli_query( $conn, $query );

	$query2 = "UPDATE deals SET upvotes = 0;";
	$result2 = mysqli_query( $conn, $query2 );

	$query3 = "UPDATE deals SET downvotes = 0;";
	$result3 = mysqli_query( $conn, $query3 );

	$query4 = "DELETE FROM favorites;";
	$result4 = mysqli_query( $conn, $query4 );
	?>