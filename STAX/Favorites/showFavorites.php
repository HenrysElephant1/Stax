<!DOCTYPE html>
<html>
<head>
	<title>Votes</title>
</head>
<body>
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
	echo "<table>";
	$query = "SELECT * FROM votes;";
	$result = mysqli_query( $conn, $query );
	while($row = mysqli_fetch_assoc($result)) {
		echo "<tr><td>" . $row["memberID"] . "</td><td>" . $row["dealID"] . "</td></tr>";
	}
	echo "</table>";
	?>
</body>
</html>