<!DOCTYPE html>
<html>
<head>
	<title>Your Deals</title>
	<link href="staxStyle.css" type="text/css" rel="stylesheet">
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> -->
	<script>
		var popMap;
		var popUpMapMarker;
		function showPopup( dealID ) {
			// var currentScrollHeight = document.documentElement.scrollTop || document.body.scrollTop;
			// var contentsDiv = document.getElementById("contents");
			// var mainContentsDiv = document.getElementById("mainContents");
			// mainContentsDiv.setAttribute( "-webkit-transform", "translateY(-" + currentScrollHeight + ")");
			// mainContentsDiv.setAttribute( "-ms-transform", "translateY(-" + currentScrollHeight + ")");
			// mainContentsDiv.style.top = "-" + currentScrollHeight + "px";
			// mainContentsDiv.style.overflow = "hidden";

			// Fill in image attributes
			

			var dealDiv = document.getElementById( dealID );
			var dealImage =  dealDiv.getElementsByTagName( "img" )[0];
			var imageSrc = dealImage.getAttribute("src");
			var imageWidth = Math.floor( 290 / 190 * dealImage.getAttribute("width") );
			var imageHeight = Math.floor( 290 / 190 * dealImage.getAttribute("height") );
			var popupImage = document.getElementById("popupImage").getElementsByTagName("img")[0];
			popupImage.setAttribute("src", imageSrc);
			popupImage.setAttribute("height", imageHeight);
			popupImage.setAttribute("width", imageWidth);

			var dealItem = document.getElementById(dealID).getElementsByClassName("dealItem")[0].innerHTML;
			document.getElementById("popupDealItem").innerHTML = dealItem;

			var dealNewPrice = document.getElementById(dealID).getElementsByClassName("dealNewPrice")[0].innerHTML;
			document.getElementById("popupDealNewPrice").innerHTML = dealNewPrice;

			var dealOldPrice = document.getElementById(dealID).getElementsByClassName("dealOldPrice")[0].innerHTML;
			document.getElementById("popupDealOldPrice").innerHTML = dealOldPrice;

			document.getElementById("cardBackgroundOverlay").style.display = 'block';
			document.getElementById("clickedCard").style.display = 'block';

			initPopMap();


			popUpMapMarker.setPosition({lat: parseFloat(document.getElementById(dealID+'lat').getAttribute('value')), lng: parseFloat(document.getElementById(dealID+'long').getAttribute('value'))});
			popMap.setCenter(popUpMapMarker.getPosition());


		}

		function hidePopup() {
			document.getElementById("cardBackgroundOverlay").style.display = 'none';
			document.getElementById("clickedCard").style.display = 'none';
			document.getElementById("popupDealItem").innerHTML = "";
			document.getElementById("popupDealNewPrice").innerHTML = "";
			document.getElementById("popupDealOldPrice").innerHTML = "";
		}
	</script>
</head>
<body>
<div id="headerSpan">
	<div id="header">
		<a href="index.php"><div id="headerImage"><img src="logo2.png" alt="STAX" height="40" width="40"></div>
		<div id="headerText"><h2>STAX</h2></div></a>
		<div id="accountTab"><h4>Your Account</h4></div>
	</div> 
</div>
<div id="contentsSpacer"></div>
<div id="contents">
	<div id="spacer"></div>
	<div id="sidebar">
		<a href="index.php"><div class="sidebarButton" id="activeSidebarButton"><p>Deals</p></div></a>
		<a href="#header"><div class="sidebarButton"><p>Favorites</p></div></a>
		<a href="#header"><div class="sidebarButton"><p>Add a Deal</p></div></a>
	</div>

	<div id="mainContents">
		<div style="padding: 5px; background-color: #F6FAF6; border: 1px solid #C0D6C0; border-radius: 5px;"><h3>Welcome back, USER</h3></div>

<?php
	$DEALS_PER_PAGE = 10;

	// $conn = @mysqli_connect('127.0.0.1', 'root', 'root', 'stax');

	$host = "staxsmysql.mysql.database.azure.com";
	$db_name = "stax_";
	$username = "master_stax@staxsmysql";
	$password = "dev2017softwareB0C@";

	$conn = mysqli_init();
	mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306);
	if(mysqli_connect_errno($conn)){
		die('Failed to connect to MySQL: '.mysqli_connect_error());
	}

	$query = "SELECT * FROM deals LIMIT " . $DEALS_PER_PAGE . " OFFSET " . ($_GET['page'] * $DEALS_PER_PAGE) . ";";

	$resultset = mysqli_query( $conn, $query );
	
	while( $row = mysqli_fetch_array($resultset, MYSQLI_NUM) ) {
		$image = "baguette.jpeg";
		$itemName = $row[1];
		$itemLat = $row[5];
		$itemLong = $row[6];
		$originalPrice = $row[7];
		$salePrice = $row[8];
		$storeName = $row[9];
		$storeImage = "france.png";

		echo '
		<div class="deal" id="deal' . $row[0] . '" onclick="showPopup(\'deal' . $row[0] . '\')">
			<div class="dealImage"><img src="' . $image . '" alt="Item Image" width="190" height="190"></div>
			<div class="dealInfo">
				<div class="dealItem"><h3>' . $itemName . '</h3></div>
				<div class="dealNewPrice"><h2>Sale Price: $' . $salePrice . '</h2></div>
				<div class="dealOldPrice"><p>Original Price: $' . $originalPrice . '</p></div>
				<input type="hidden" id="deal'.$row[0].'lat" value='.$itemLat.' />
				<input type="hidden" id="deal'.$row[0].'long" value='.$itemLong.' />
			</div>
			<div id="map" class="dealStore">
			</div>
		</div>';

		
	}

	

	// for( $i=0; $i<10; $i++ ) {
	// 	$image = "baguette.jpeg";
	// 	$itemName = "Baguette";
	// 	$salePrice = "1" . $i . "0.00";
	// 	$originalPrice = "German Invasion";
	// 	$storeImage = "france.png";

	// 	echo '
	// 	<div class="deal" id="deal' . $i . '" onclick="showPopup(\'deal' . $i . '\')">
	// 		<div class="dealImage"><img src="' . $image . '" alt="Item Image" width="190" height="190"></div>
	// 		<div class="dealInfo">
	// 			<div class="dealItem"><h3>' . $itemName . '</h3></div>
	// 			<div class="dealNewPrice"><h2>Sale Price: $' . $salePrice . '</h2></div>
	// 			<div class="dealOldPrice"><p>Original Price: $' . $originalPrice . '</p></div>
	// 		</div>
	// 		<div class="dealStore">
	// 			<img src="' . $storeImage . '" alt="Store Logo" width="190" height="190">
	// 		</div>
	// 	</div>';
	// }
?>

		<div id="pageLinks">
<?php
	$totalDealsQuery = "SELECT COUNT(*) FROM deals;";
	$dealsResultSet = mysqli_query( $conn, $totalDealsQuery );
	$totalDeals = mysqli_fetch_array( $dealsResultSet )[0];

	if( empty($_GET['page']) || $page < 0 || $page > (int)($totalDeals / $DEALS_PER_PAGE) ) {
		$page = 0;
	}
	else { 
		$page = $_GET['page'];
	}

	if( $page != 0 ) {
		$previousPage = (string)($page - 1);
		echo "			<div id=\"prevPage\">
				<form action=\"index.php\" method=\"get\">
					<input name=\"page\" type=\"hidden\" value=".$previousPage.">
					<input type=\"submit\" value=\"Previous Page\">
				</form>
			</div>";
	}	if( $totalDeals > $DEALS_PER_PAGE * ($page + 1) ) {
		$nextPage = (string)($page + 1);
		echo "
			<div id=\"nextPage\">
				<form action=\"index.php\" method=\"get\">
					<input name=\"page\" type=\"hidden\" value=".$nextPage.">
					<input type=\"submit\" value=\"Next Page\">
				</form>
			</div>";
	}


	mysqli_close( $conn );
?>
		
		</div>
	</div>
</div>

<div id="cardBackgroundOverlay" onclick="hidePopup()"></div>
<div id="clickedCard">
	<div id="popupImage"><img src="" height="" width="" alt="No Image Available"></div>
	<div id="popupInfo">
		<div id="popupDealItem"><h3>Item</h3></div>
		<div id="popupDealNewPrice"><h2>New Price</h2></div>
		<div id="popupDealOldPrice"><p>Original Price</p></div>
	</div>
	<div id="popupMap"></div>

	<script>

	  
      function initPopMap() {
        var myLatLng = {lat: 0, lng: 180};

        popMap = new google.maps.Map(document.getElementById("popupMap"), {
          zoom: 8,
          center: myLatLng,
          gestureHandling: "cooperative"
        });

        popUpMapMarker = new google.maps.Marker({
          position: myLatLng,
          map: popMap,
          title: "popUpMapMarker"
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB97Z4tKehfoZONpSyFERNZKtTPkxdeDXA&callback=initPopMap">
    </script>


</div>
</body>
</html>