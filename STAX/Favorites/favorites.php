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
$dealID = $_POST['dealID'];

if( !empty( $userID ) && !empty( $dealID ) ) {
	$getUserFavoriteFromTable = "SELECT * FROM favorites WHERE memberID = $userID AND dealID = $dealID;";
	$userVoteResult = mysqli_query( $conn, $getUserFavoriteFromTable );

	if ( mysqli_num_rows($userVoteResult) == 0 ) {
		//Put user into votes table
		$addUserToFavoriteTable = "INSERT INTO votes( memberID, dealID, value ) VALUES ('$userID', $dealID, 1);";
		mysqli_query( $conn, $addUserToFavoriteTable );

		//Increment upvotes in deals table
		$addFavoriteQuery = "UPDATE deals SET upvotes = upvotes + 1 WHERE dealID = $dealID;";
		mysqli_query( $conn, $addFavoriteQuery );
	}
	
	
?>