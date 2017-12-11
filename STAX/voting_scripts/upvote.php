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
		$getUserVoteFromTable = "SELECT * FROM votes WHERE memberID = '$userID' AND dealID = $dealID;";
		$userVoteResult = mysqli_query( $conn, $getUserVoteFromTable );

		if ( mysqli_num_rows($userVoteResult) == 0 ) {
			//Put user into votes table
			$addUserToVotesTable = "INSERT INTO votes( memberID, dealID, value ) VALUES ('$userID', $dealID, 1);";
			mysqli_query( $conn, $addUserToVotesTable );

			//Increment upvotes in deals table
			$addUpvoteQuery = "UPDATE deals SET upvotes = upvotes + 1 WHERE dealID = $dealID;";
			mysqli_query( $conn, $addUpvoteQuery );
		}
		else {
			while($row = mysqli_fetch_assoc($userVoteResult)) {
				if ( $row["value"] == -1 ) {
					//Update vote in votes table
					$changeUserVoteQuery = "UPDATE votes SET value = 1 WHERE dealID = $dealID;";
					mysqli_query( $conn, $changeUserVoteQuery );

					//Increment upvotes in deals table
					$addUpvoteQuery = "UPDATE deals SET upvotes = upvotes + 1 WHERE dealID = $dealID;";
					mysqli_query( $conn, $addUpvoteQuery )
					;
					//Decrement downvotes in deals table
					$addUpvoteQuery = "UPDATE deals SET downvotes = downvotes - 1 WHERE dealID = $dealID;";
					mysqli_query( $conn, $addUpvoteQuery );

				}
				else {
					//Update vote in votes table
					$changeUserVoteQuery = "DELETE FROM votes WHERE memberID = '$userID' AND dealID = $dealID;";
					mysqli_query( $conn, $changeUserVoteQuery );

					//Decrement upvotes in deals table
					$removeUpvoteQuery = "UPDATE deals SET upvotes = upvotes - 1 WHERE dealID = $dealID;";
					mysqli_query( $conn, $removeUpvoteQuery );

				}
			}
		}
	}
?>