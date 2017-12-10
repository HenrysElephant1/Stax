<!DOCTYPE html>
<html>
<head>
	<title>STAX Deals</title>
	<link href="staxStyle.css" type="text/css" rel="stylesheet">
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<script src="https://apis.google.com/js/platform.js" async defer></script>
	<meta name="google-signin-client_id" content="505886009165-tjniqhjeihi67b94cgiu0fe34ne7e0dg.apps.googleusercontent.com">
	<meta charset="utf-8">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
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

			var dealType = document.getElementById(dealID).getElementsByClassName("dealType")[0].innerHTML;
			document.getElementById("popupDealType").innerHTML = dealType;

			var dealItem = document.getElementById(dealID).getElementsByClassName("dealItem")[0].innerHTML;
			document.getElementById("popupDealItem").innerHTML = dealItem;

			var dealNewPrice = document.getElementById(dealID).getElementsByClassName("dealNewPrice")[0].innerHTML;
			document.getElementById("popupDealNewPrice").innerHTML = dealNewPrice;

			var dealOldPrice = document.getElementById(dealID).getElementsByClassName("dealOldPrice")[0].innerHTML;
			document.getElementById("popupDealOldPrice").innerHTML = dealOldPrice;

			var displayDealStore = document.getElementById(dealID).getElementsByClassName("displayDealStore")[0].innerHTML;
			document.getElementById("popupDisplayDealStore").innerHTML = displayDealStore;

			document.getElementById("cardBackgroundOverlay").style.display = 'block';
			document.getElementById("clickedCard").style.display = 'block';

			initPopMap();

			popUpMapMarker.setPosition({lat: parseFloat(document.getElementById(dealID+'lat').getAttribute('value')), lng: parseFloat(document.getElementById(dealID+'long').getAttribute('value'))});
			popMap.setCenter(popUpMapMarker.getPosition());
		}

		function hidePopup() {
			document.getElementById("cardBackgroundOverlay").style.display = "none";
			document.getElementById("clickedCard").style.display = "none";
			document.getElementById("popupDealItem").innerHTML = "";
			document.getElementById("popupDealNewPrice").innerHTML = "";
			document.getElementById("popupDealOldPrice").innerHTML = "";
		}

		var followDistLink = false;

		function signOut() {
    		var auth2 = gapi.auth2.getAuthInstance();
    		auth2.signOut().then(function () {
      		console.log('User signed out.');
      		auth2.disconnect();
    	});
    	}

    	function onSignIn(googleUser) {
    		changeHeader();
    	}

	</script>
</head>
<body>
<div id="headerSpan">
	<div id="header">
		<a href="index.php"><div id="headerImage"><img src="logo2.png" alt="STAX" height="40" width="40"></div>
		<div id="headerText"><h2>STAX</h2></div></a>
		<script>
			function changeHeader() {
				var userSignedIn = gapi.auth2.getAuthInstance().isSignedIn.get();

				if( userSignedIn ) {
					document.getElementById("accountTab").style.display = "block";
					document.getElementById("signOutButton").style.display = "block";
					// document.getElementsByClassName("g-signin2")[0].style.display = "none";
				}
				else {
					document.getElementById("accountTab").style.display = "none";
					document.getElementById("signOutButton").style.display = "none";
					// document.getElementsByClassName("g-signin2")[0].style.display = "block";
				}
			}
		</script>
		<div class="g-signin2" data-onsuccess="onSignIn"></div>
		<div id="accountTab" style="display:none;"><h4>Your Account</h4></div>
		<div id="signOutButton" href="#" onclick="signOut()"><h4>Sign Out</h4></div>
	</div> 
</div>

<div id="contentsSpacer"></div>
<div id="contents">
	<div id="spacer"></div>
	<div id="sidebar">
		<a href="index.php"><div class="sidebarButton" id="activeSidebarButton"><p>Deals</p></div></a>
		<a href="#header"><div class="sidebarButton"><p>Favorites</p></div></a>
		<a href="new_deal.php"><div class="sidebarButton"><p>Add a Deal</p></div></a>
		<div id="sidebarSpacer" style="height: 10px;"></div>

		<h4>Sort By:</h4>
		<a href="index.php?sortBy=newest"><div class="sidebarButton" id="newestSort"><p>Newest</p></div></a>
		<a href="index.php?sortBy=oldest"><div class="sidebarButton" id="oldestSort"><p>Oldest</p></div></a>
		<a href="index.php?sortBy=priceLowToHigh"><div class="sidebarButton" id="priceLowToHighSort"><p>Price Low to High</p></div></a>
		<a href="index.php?sortBy=priceHighToLow"><div class="sidebarButton" id="priceHighToLowSort"><p>Price High to Low</p></div></a>
		<a href="" onclick="return followDistLink;"><div class="sidebarButton" id="distanceSort"><p>Distance From Me</p></div></a>
	</div>

	<?php
		$sortBy = $_GET['sortBy'];
		if( empty( $sortBy ) || ($sortBy != "oldest" && $sortBy != "priceLowToHigh" && $sortBy != "priceHighToLow" && $sortBy != "distance" ) ) {
			$sortBy = "newest";
		}
		echo '<input type="hidden" id="sortBy" value="' . $sortBy . '">
';
	?>

	<script>
		var sortBy = document.getElementById("sortBy").value;
		if( sortBy == "newest" ) {
			document.getElementById("newestSort").id = "activeSidebarButton";
		}
		if( sortBy == "oldest" ) {
			document.getElementById("oldestSort").id = "activeSidebarButton";
		}
		else if( sortBy == "priceLowToHigh" ) {
			document.getElementById("priceLowToHighSort").id = "activeSidebarButton";
		}
		else if( sortBy == "priceHighToLow" ) {
			document.getElementById("priceHighToLowSort").id = "activeSidebarButton";
		}
		else if( sortBy == "distance" ) {
			document.getElementById("distanceSort").id = "activeSidebarButton";
		}

		var dealsHeader = document.getElementById( "dealsHeader" );
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(setDistanceURL);
		}
		else {
			dealsHeader.innerHTML = "Geolocation is not supported by this browser.";
		}

		function setDistanceURL(position) {
			var latitude = position.coords.latitude;
			var longitude = position.coords.longitude;
			
			var distURL = "index.php?sortBy=distance&latitude="+latitude+"&longitude="+longitude;
			var link = document.getElementById("sidebar").getElementsByTagName("a")[7];
			link.href = distURL;
			followDistLink = true;
		}
	</script>

	<p></p>
	<script type="text/javascript">
		function callUpvote(inputDealID) {
			$.ajax({
			    type: 'POST',
			    url: 'voting_scripts/upvote.php',
			    dataType: 'html',
			    data: {userID: 10, dealID: inputDealID},
			});

			var dealDiv = "deal" + inputDealID;
			var curVotes = Number(document.getElementById( dealDiv ).getElementsByClassName("totalVotes")[0].innerHTML);
			document.getElementById( dealDiv ).getElementsByClassName("totalVotes")[0].innerHTML = curVotes + 1;
		}

		function callDownvote(inputDealID) {
			$.ajax({
			    type: 'POST',
			    url: 'voting_scripts/downvote.php',
			    dataType: 'html',
			    data: {userID: 10, dealID: inputDealID},
			});

			var dealDiv = "deal" + inputDealID;
			var curVotes = Number(document.getElementById( dealDiv ).getElementsByClassName("totalVotes")[0].innerHTML);
			document.getElementById( dealDiv ).getElementsByClassName("totalVotes")[0].innerHTML = curVotes - 1;
		}
	</script>
	 
	<script type="text/javascript">
		function callFavorites(inputuserID){
			$.ajax({
				type: 'POST',
				url: 'Favorites/favorites.php',
				datatype: 'html',
				data: {userID: 10, dealID: inputuserID},				
			});
		
		}
	</script>
	
	

	<div id="mainContents">
		<div id="dealsHeader" style="padding: 5px; background-color: #FFFFFF; border: 1px solid #C0D6C0; border-radius: 5px;">
			<h3>Welcome Back, USER</h3>
		</div>

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

	if( $sortBy == "oldest" ) {
		$query = "SELECT * FROM deals 
			ORDER BY dealID 
			LIMIT " . $DEALS_PER_PAGE . " 
			OFFSET " . ($_GET['page'] * $DEALS_PER_PAGE) . ";";
	}
	else if( $sortBy == "priceLowToHigh" ) {
		$query = "SELECT * FROM deals 
		ORDER BY salePrice 
		LIMIT " . $DEALS_PER_PAGE . " 
		OFFSET " . ($_GET['page'] * $DEALS_PER_PAGE) . ";";
	}
	else if( $sortBy == "priceHighToLow" ) {
		$query = "SELECT * FROM deals 
		ORDER BY salePrice DESC 
		LIMIT " . $DEALS_PER_PAGE . " 
		OFFSET " . ($_GET['page'] * $DEALS_PER_PAGE) . ";";
	}

	else if( $sortBy == "distance" ) {
		$userLat = $_GET['latitude'];
		$userLong = $_GET['longitude'];


		$query = "SELECT * FROM deals
			ORDER BY (POWER(geoLatitude - " . $userLat . " , 2) + POWER(geoLongitude - " . $userLong . " , 2))
			LIMIT " . $DEALS_PER_PAGE . " 
			OFFSET " . ($_GET['page'] * $DEALS_PER_PAGE) . ";";
	}
	else {
		$query = "SELECT * FROM deals 
		ORDER BY dealID DESC 
		LIMIT " . $DEALS_PER_PAGE . " 
		OFFSET " . ($_GET['page'] * $DEALS_PER_PAGE) . ";";
	}

	$resultset = mysqli_query( $conn, $query );
	
	while( $row = mysqli_fetch_array($resultset, MYSQLI_NUM) ) {
		$dealID = $row[0];
		$itemName = $row[1];
		$dealType = $row[2];
		$upVotes = $row[3];
		$downVotes = $row[4];
		$geoLatitude = $row[5];
		$geoLongitude = $row[6];
		$originalPrice = $row[7];
		$salePrice = $row[8];
		$storeName = $row[9];
		$image = $row[10];
		$memberID = $row[11];

		if( list($width, $height, $type, $attr) = @getimagesize( $image ) ) {
			if( $height > $width ) {
				$resizingValue = $height;
			}
			else {
				$resizingValue = $width;
			}
			$imageDisplayHeight = $height * 190 / $resizingValue;
			$imageDisplayWidth = $width * 190 / $resizingValue;
		}
		else {
			$imageDisplayHeight = 0;
			$imageDisplayWidth = 0;
		}

		
		echo '
		<div class="deal" id="deal' . $dealID . '">
			<div class="dealImage" onclick="showPopup(\'deal' . $dealID . '\')"><span class="imageHelper"></span><img class="actualImage" src="' . $image . '" alt="Item Image" width="' . $imageDisplayWidth . '" height="' . $imageDisplayHeight . '"></div>
			<div class="dealInfo" onclick="showPopup(\'deal' . $dealID . '\')">
				<div class="dealType"><h4>' . $dealType . '</h4></div>
				<div class="dealItem"><h3>' . $itemName . '</h3></div>';
		if( substr($dealType, 0, 4) == "Sale" ) {
			echo' 
				<div class="dealNewPrice"><h2>Price: $' . money_format('%i', $salePrice) . '</h2></div>
				<div class="dealOldPrice"><h4>Original price: $' . money_format('%i', $originalPrice) . '</h4></div>';
		}
		else if( substr($dealType, -4) == "Free" ) {
			echo'
				<div class="dealNewPrice"><h2>Price for One: $' . money_format('%i', $salePrice) . '</h2></div>
				<div class="dealOldPrice style="display: hidden;"> </div>';
		}
		echo'
				<div class="displayDealStore"><p>Location: ' . $storeName . '</p></div>
				<input type="hidden" id="deal'.$dealID.'lat" value='.$geoLatitude.' />
				<input type="hidden" id="deal'.$dealID.'long" value='.$geoLongitude.' />
			</div>
			<div class="votingColumn">
				<a href="javascript:void(0);" onclick="callUpvote('.$dealID.')"><div class="upvoteButton"><p>Upvote</p></div></a>
				<p><div class="totalVotes">'.($upVotes-$downVotes).'</div></p>
				<a href="javascript:void(0);" onclick="callDownvote('.$dealID.')"><div class="downvoteButton"><p>Downvote</p></div></a> 
			
				<div class "favorites">
					<a href="javascript:void(0);" onclick="callFavorites('.$memberID.')"><p>Favorite</p></a>
				</div>
			</div>
			
			
			';
			
			
		echo'	
			<div class="dealStore" onclick="showPopup(\'deal' . $dealID . '\')">
				<img src="https://maps.googleapis.com/maps/api/staticmap?center=' . $geoLatitude . ',' . $geoLongitude . '&zoom=14&size=190x190&maptype=roadmap&markers=color:red%7C' . $geoLatitude . ',' . $geoLongitude . '&key=AIzaSyB97Z4tKehfoZONpSyFERNZKtTPkxdeDXA" alt="Store Location" width="190" height="190">
			</div>
		</div>
		';
	}

?>

		<div id="pageLinks">
<?php
	$totalDealsQuery = "SELECT COUNT(*) FROM deals;";
	$dealsResultSet = mysqli_query( $conn, $totalDealsQuery );
	$totalDeals = mysqli_fetch_array( $dealsResultSet )[0];
	$currentURL = "index.php?sortBy=" . $sortBy;

	if( empty($_GET['page']) || $page < 0 || $page > $totalDeals / $DEALS_PER_PAGE ) {
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
					<input name=\"sortBy\" type=\"hidden\" value=".$sortBy.">
					<input type=\"submit\" value=\"Previous Page\">
				</form>
			</div>";
	}	if( $totalDeals > $DEALS_PER_PAGE * ($page + 1) ) {
		$nextPage = (string)($page + 1);
		echo "
			<div id=\"nextPage\">
				<form action=\"index.php\" method=\"get\">
					<input name=\"page\" type=\"hidden\" value=".$nextPage.">
					<input name=\"sortBy\" type=\"hidden\" value=".$sortBy.">
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
	<div id="popupImage"><span class="imageHelper"></span><img class="actualPopupImage" src="" height="" width="" alt="No Image Available"></div>
	<div id="popupInfo">
		<div id="popupDealType"><h4>Deal Type</h4></div>
		<div id="popupDealItem"><h3>Item</h3></div>
		<div id="popupDealNewPrice"><h2>New Price</h2></div>
		<div id="popupDealOldPrice"><p>Original Price</p></div>
		<div id="popupDisplayDealStore"><p>Deal Store</p></div>
	</div>
	<div id="popupMap"></div>

	<script>

	  
		function initPopMap() {
			var myLatLng = {lat: 0, lng: 180};

			popMap = new google.maps.Map(document.getElementById("popupMap"), {
				zoom: 16,
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
