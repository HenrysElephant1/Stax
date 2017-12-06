<html>
	<head>
		<title>Form</title>
	</head>
	<body>
		<?php
	// Obtain a connection object by connecting to the db
	$connection = @mysqli_connect ('localhost', 'root', 'motdepasse', 'lab6');
	//please fill these parameters with the actual data
	if(mysqli_connect_errno())
	{
		echo "<h4>Failed to connect to MySQL: </h4>".mysqli_connect_error();
	}
	else
	{
		echo "<h4>Successfully connected to MySQL: </h4>"; 
	}

	//print_r($_FILES);

	//upload images to the server
	if(!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], 'uploads/' . $_FILES['fileToUpload']['name'])){
    die('Error uploading file - check destination is writeable.');
}




	//collect information from the form

	$ProductName = $_REQUEST['productName'];
	$name = $_REQUEST['productName'];
	$type = $_REQUEST['type'];
	$salePrice = $_REQUEST['salePrice'];
	$origPrice = 0;
	$storeName = $_REQUEST['storeName'];
	if($type == "buyXGetXFree")
	{
		if ($_REQUEST['itemsToBuy'] > 1)
		{ $ProductName = "Buy {$_REQUEST['itemsToBuy']} " . $name . "s and get {$_REQUEST['freeItems']} free!";}
		else {$ProductName = "Buy {$_REQUEST['itemsToBuy']} " . $name . " and get {$_REQUEST['freeItems']} free!";}

	}
	else {
		$origPrice =  $_REQUEST['origPrice'];
	}
	$store_id = $storeName . $Location;
	$Image = $_REQUEST['Image'];
	$Location = $_REQUEST['Location'];
	echo '<p>',$ProductName,'</p>';
	echo '<p>',$name,'</p>';
	echo '<p>',$type,'</p>';
	echo '<p>',$salePrice,'</p>';
	echo '<p>',$Location,'</p>';

	$query = "INSERT INTO deals(dealName, dealType, votes, dealLoc, org_price, sale_price, store_id, dealID) VALUES ('$ProductName','$type',0,'$Location','$origPrice', '$salePrice', '$store_id')";
	#send request to mysql
	$result = mysqli_query( $connection, $query );
	echo "Successfully inserted into database";

	#send back to store.php
	include 'store.php';
	?>
  	</body>
 </html>