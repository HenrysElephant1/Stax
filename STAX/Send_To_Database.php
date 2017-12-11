<html>
	<head>
		<title>Form</title>
	</head>
	<body>
		<?php
		// Obtain a connection object by connecting to the db
		//$connection = @mysqli_connect('127.0.0.1', 'root', 'root', 'stax');

		$host = "staxsmysql.mysql.database.azure.com";
		$db_name = "stax_";
		$username = "master_stax@staxsmysql";
		$password = "dev2017softwareB0C@";

		$connection = mysqli_init();
		mysqli_real_connect($connection, $host, $username, $password, $db_name, 3306);

		if(mysqli_connect_errno())
		{
			echo "<h4>Failed to connect to MySQL: </h4>". mysqli_connect_error();
		}


		// $imagePathName = 'uploads/' . $_FILES['fileToUpload']['name'];
		// //upload images to the server
		// if(!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imagePathName)){
	 //    	die('Error uploading file - check destination is writeable.');
		// }

		//collect information from the form
		$userName = $_REQUEST['userName'];
		$name = $_REQUEST['productName'];
		$type = $_REQUEST['type'];
		if($type == "buyXGetXFree") {
			$type = "Buy {$_REQUEST['itemsToBuy']}, Get {$_REQUEST['freeItems']} Free";
		}

		$salePrice = $_REQUEST['salePrice'];
		$orgPrice = $_REQUEST['orgPrice'];
		if( empty($orgPrice) ) {
			$orgPrice = 0;
		}

		$storeName = $_REQUEST['storeName'];

		$Image = $_REQUEST['Image'];

		$Location = $_REQUEST['Location'];
		// echo '<p>' . $name . '</p>';
		// echo '<p>' . $type . '</p>';
		// echo '<p>' . $salePrice . '</p>';
		// echo '<p>' . $Location . '</p>';
		$commaPos = strpos( $Location, ',' );
		$lat = (float)substr($Location, 1, $commaPos);
		$long = (float)substr($Location, $commaPos + 1, strlen($Location) - $commaPos - 2);
		// echo '<p>' . $lat . ',' . $long . '</p>';


		$query = "INSERT INTO deals(item, dealType, upVotes, downVotes, geoLatitude, geoLongitude, orgPrice, salePrice, storeName, image, memberID) VALUES ('$name', '$type', 0, 0, $lat, $long, $orgPrice, $salePrice, '$storeName', '$Image', '$userName');";

		if( !mysqli_query( $connection, $query ) ) {
			echo("Error description: " . mysqli_error($connection));
	 	}

		mysqli_close( $connection );
		?>
		<meta http-equiv="refresh" content="0;URL=index.php" />
  	</body>
 </html>
