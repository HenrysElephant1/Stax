<!DOCTYPE html>
<html>
<head>
	<title>Favorites</title>
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
	$query = "SELECT * FROM favorites;";
	$result = mysqli_query( $conn, $query );
	while($row = mysqli_fetch_assoc($result)) {
		echo "<tr><td>" . $row["memberID"] . "</td><td>" . $row["dealID"] . "</td></tr>";
	}
	echo "</table>";

	echo "<br><br><br>";

	$query2 = "SELECT deals.dealID, deals.item, deals.dealType, deals.upvotes, deals.downvotes, deals.geoLatitude, deals.geoLongitude, deals.orgPrice, deals.salePrice, deals.storeName, deals.image, deals.memberID FROM deals 
		INNER JOIN favorites
		ON deals.dealID = favorites.dealID
		WHERE favorites.memberID = 'jason@the-cummings.com'
		ORDER BY deals.salePrice;";

	$result2 = mysqli_query( $conn, $query2 );
	while($row = mysqli_fetch_array($result2)) {
		echo $row[0] . "<br/>";
	}

	?>
</body>
</html>