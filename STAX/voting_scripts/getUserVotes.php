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

	$userID = $_POST['userID'];

	$query = "SELECT dealID, value FROM votes WHERE memberID = '".$userID."';";

	$result = mysqli_query( $conn, $query );

	$echostring = "";
	while($row = mysqli_fetch_array($result)) {
		$echostring .= $row[0] . "." . $row[1] . ",";
	}

	echo $echostring;
?>