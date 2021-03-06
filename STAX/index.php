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


		// Fills in popup data and makes it visible
		function showPopup( dealID ) {
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

			// Sets other popup information
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

			// Makes the popup visible
			document.getElementById("cardBackgroundOverlay").style.display = 'block';
			document.getElementById("clickedCard").style.display = 'block';

			// Initialize popup Google map
			initPopMap();
			popUpMapMarker.setPosition({lat: parseFloat(document.getElementById(dealID+'lat').getAttribute('value')), lng: parseFloat(document.getElementById(dealID+'long').getAttribute('value'))});
			popMap.setCenter(popUpMapMarker.getPosition());
		}

		// Set popup display to be invisible
		function hidePopup() {
			document.getElementById("cardBackgroundOverlay").style.display = "none";
			document.getElementById("clickedCard").style.display = "none";
		}

		// Used to prevent user from sorting by distance until their distance is found
		var followDistLink = false;

		// Get user info on signin and chane some related displays
		var USER_EMAIL = "";
		function onSignIn(googleUser) {
			changeHeader();
			USER_EMAIL = gapi.auth2.getAuthInstance().currentUser.get().getBasicProfile().getEmail();
			document.getElementById("favoritesLink").href = "favorites.php?userName=" + USER_EMAIL;
			document.getElementById("dealsHeaderText").innerHTML = "Welcome Back, " + gapi.auth2.getAuthInstance().currentUser.get().getBasicProfile().getGivenName();
			getVotesAndFavorites();
		}

		// Reload page on user signout
		function signOut() {
			var auth2 = gapi.auth2.getAuthInstance();
				auth2.signOut().then(function () {
				auth2.disconnect();
				window.location.replace('index.php');
			});
		}
	</script>

</head>
<body>

<!-- Header -->
<div id="headerSpan">
	<div id="header">
		<a href="index.php"><div id="headerImage"><img src="logo2.png" alt="STAX" height="40" width="40"></div>
		<div id="headerText"><h2>STAX</h2></div></a>
		<script>
			// Display correct buttons based on whether or not user is signed in
			function changeHeader() {
				var userSignedIn = gapi.auth2.getAuthInstance().isSignedIn.get();

				if( userSignedIn ) {
					document.getElementById("signOutButton").style.display = "block";
					document.getElementsByClassName("g-signin2")[0].style.display = "none";
				}
				else {
					document.getElementById("signOutButton").style.display = "none";
					document.getElementsByClassName("g-signin2")[0].style.display = "block";
				}
			}
		</script>
		<div class="g-signin2" data-onsuccess="onSignIn"></div>
		<div id="signOutButton" href="#" onclick="signOut()" style="display:none;"><h4>Sign Out</h4></div>
	</div> 
</div>

<!-- Main Page Contents -->
<div id="contentsSpacer"></div>
<div id="contents">
	<div id="spacer"></div>
	<div id="sidebar">
		<a href="index.php"><div class="sidebarButton" id="activeSidebarButton"><p>Deals</p></div></a>
		<a id="favoritesLink" href="favorites.php"><div class="sidebarButton"><p>Favorites</p></div></a>
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
		// Create hidden input for page to know how results are being sorted
		$sortBy = $_GET['sortBy'];
		if( empty( $sortBy ) || ($sortBy != "oldest" && $sortBy != "priceLowToHigh" && $sortBy != "priceHighToLow" && $sortBy != "distance" ) ) {
			$sortBy = "newest";
		}
		echo '<input type="hidden" id="sortBy" value="' . $sortBy . '">
';
	?>

	<script>
		// Display current sorting option as being selected
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

		// Attempt to get user's location, then call setDistanceURL if successful
		var dealsHeader = document.getElementById( "dealsHeader" );
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(setDistanceURL);
		}
		else {
			dealsHeader.innerHTML = "Geolocation is not supported by this browser.";
		}

		// Set Sort by Distance link to contain users location for sorting purposes
		function setDistanceURL(position) {
			var latitude = position.coords.latitude;
			var longitude = position.coords.longitude;
			var distURL = "index.php?sortBy=distance&latitude="+latitude+"&longitude="+longitude;
			var link = document.getElementById("sidebar").getElementsByTagName("a")[7];
			link.href = distURL;

			// Allow user to sort by distance
			followDistLink = true;
		}



		/***********************************
		 * Voting and Favoriting functions *	
		 ***********************************/

		// Go though all deals on page and set the color of the vote count to green if positive, red if negative, gray if 0
		function updateVoteColors() {
			for( var i=0; i<10; i++ ) {
				var currentTotalVotesDiv = document.getElementsByClassName("totalVotes")[i];
				if( currentTotalVotesDiv === null || !currentTotalVotesDiv ) {
				}
				else {
					var currentTotalVotes = Number( currentTotalVotesDiv.innerHTML );
					if( currentTotalVotes > 0 ) {
						currentTotalVotesDiv.style.color = "#3C7F3F";
					}
					if( currentTotalVotes < 0 ) {
						currentTotalVotesDiv.style.color = "#9B372A";
					}
					if( currentTotalVotes == 0 ) {
						currentTotalVotesDiv.style.color = "#A0A0A0";
					}
				}
			}
		}

		// Booleans used to prevent user from voting or favoriting again before current script has finished executing
		var allowVote = true;
		var allowFavorite = true;

		function callUpvote(inputDealID) {
			if( allowVote && USER_EMAIL != "" ) {
				allowVote = false;
				var upVoteImg = document.getElementById("upvote"+inputDealID);
				var upVoteValue = document.getElementById("upvote"+inputDealID+"Value");

				var downVoteValue = document.getElementById("downvote"+inputDealID+"Value");
				var totalVotesDiv = document.getElementById("deal"+inputDealID).getElementsByClassName("totalVotes")[0];

				// Handle different cases of current votes
				if( upVoteValue.value == "false" ) {
					// User has not upvoted this deal before, so change display to color up arrow
					upVoteImg.src = "green_up_arrow.png?t=" + new Date().getTime();
					upVoteValue.value = "true";

					// Change number of votes based on whether the deal was previously downvoted or not
					if( downVoteValue.value == "true" ) {
						totalVotesDiv.innerHTML = Number(totalVotesDiv.innerHTML) + 2;
					}
					else {
						totalVotesDiv.innerHTML = Number(totalVotesDiv.innerHTML) + 1;
					}
				}
				else {
					// User had upvoted deal previously, so change image to gray up arrow and correct votes
					upVoteImg.src = "gray_up_arrow.png?t=" + new Date().getTime();
					upVoteValue.value = "false";
					totalVotesDiv.innerHTML = Number(totalVotesDiv.innerHTML) - 1;
				}
				// Update all vote colors
				updateVoteColors();

				// No matter the upvote value, downvote will now be false, so turn down arrow gray
				var downVoteImg = document.getElementById("downvote"+inputDealID);
				downVoteImg.src = "gray_down_arrow.png?t=" + new Date().getTime();
				downVoteValue.value = "false";

				// Call upvoting script with AJAX to handle updating vote information in the database
				$.ajax({
					type: 'POST',
					url: 'voting_scripts/upvote.php',
					dataType: 'html',
					data: {userID: USER_EMAIL, dealID: inputDealID},
					success: function() {
						allowVote = true;
					}
				});
			}
			else if( USER_EMAIL == "" ) {
				alert("Please sign in to vote and favorite");
			}
		}

		function callDownvote(inputDealID) {
			if( allowVote && USER_EMAIL != "" ) {
				allowVote = false;
				var downVoteImg = document.getElementById("downvote"+inputDealID);
				var downVoteValue = document.getElementById("downvote"+inputDealID+"Value");

				var upVoteValue = document.getElementById("upvote"+inputDealID+"Value");
				var totalVotesDiv = document.getElementById("deal"+inputDealID).getElementsByClassName("totalVotes")[0];

				// Handle different cases of current votes
				if( downVoteValue.value == "false" ) {
					// User has not downvoted this deal before, so change display to color down arrow
					downVoteImg.src = "red_down_arrow.png?t=" + new Date().getTime();
					downVoteValue.value = "true";

					// Change number of votes based on whether the deal was previously upvoted or not
					if( upVoteValue.value == "true" ) {
						totalVotesDiv.innerHTML = Number(totalVotesDiv.innerHTML) - 2;
					}
					else {
						totalVotesDiv.innerHTML = Number(totalVotesDiv.innerHTML) - 1;
					}
				}
				else {
					downVoteImg.src = "gray_down_arrow.png?t=" + new Date().getTime();
					downVoteValue.value = "false";
					totalVotesDiv.innerHTML = Number(totalVotesDiv.innerHTML) + 1;
				}
				// Update all vote colors
				updateVoteColors();

				// No matter the downvote value, upvote will now be false, so turn up arrow gray
				var upVoteImg = document.getElementById("upvote"+inputDealID);
				upVoteImg.src = "gray_up_arrow.png?t=" + new Date().getTime();
				upVoteValue.value = "false";

				// Call downvoting script with AJAX to handle updating vote information in the database
				$.ajax({
					type: 'POST',
					url: 'voting_scripts/downvote.php',
					dataType: 'html',
					data: {userID: USER_EMAIL, dealID: inputDealID},
					success: function() {
						allowVote = true;
					}
				});
			}
			else if( USER_EMAIL == "" ) {
				alert("Please sign in to vote and favorite");
			}
		}

		function callFavorites(inputDealID){
			if( allowFavorite && USER_EMAIL != "" ) {
				var favoritesImg = document.getElementById("favorites"+inputDealID);
				var favoritesValue = document.getElementById("favorites"+inputDealID+"Value");

				if( favoritesValue.value == "false" ) {
					// Deal was not favorited before, so update display and value to reflect favorite
					favoritesImg.src = "red_heart.png?t=" + new Date().getTime();
					favoritesValue.value = "true";
				}
				else {
					// Deal was favorited before, so make heart gray and value false
					favoritesImg.src = "gray_heart.png?t=" + new Date().getTime();
					favoritesValue.value = "false";
				}
				allowFavorite = false;

				// Use AJAX to call script to update database favorites information
				$.ajax({
					type: 'POST',
					url: 'Favorites/favorites.php',
					datatype: 'html',
					data: {userID: USER_EMAIL, dealID: inputDealID},	
					success: function() {
						allowFavorite = true;
					}		
				});
			}
			else if( USER_EMAIL == "" ) {
				alert("Please sign in to vote and favorite");
			}
		}

		// Get users votes and favorites to correctly display deals on signin and reload
		function getVotesAndFavorites() {
			if( USER_EMAIL != "" ) {

				// Use AJAX to get favorites
				$.ajax({
					type: 'POST',
					url: 'Favorites/getUserFavorites.php',
					datatype: 'html',
					data: {userID: USER_EMAIL},
					success: function(data) {
						var favoritesString = data;

						// Parse returned string to get ids of user's favorited deals
						while( favoritesString != "" ) {
							var nextComma = favoritesString.indexOf(',');
							var nextFavoriteId = favoritesString.substring(0,nextComma);
							var thisDiv = document.getElementById("deal"+nextFavoriteId);
							if( thisDiv === null || !thisDiv ) {
								//Don't do anything if deal isn't on current page
							}
							else {
								// Color heart and update favorite value of user's previously favorited deal
								var favoritesImg = document.getElementById("favorites"+nextFavoriteId);
								var favoritesValue = document.getElementById("favorites"+nextFavoriteId+"Value");
								favoritesImg.src = "red_heart.png?t=" + new Date().getTime();
								favoritesValue.value = "true";
							}
							// Reduce favorites string
							favoritesString = favoritesString.substring( nextComma + 1 );
						}
					}
				});
				
				//Use AJAX to get votes
				$.ajax({
					type: 'POST',
					url: 'voting_scripts/getUserVotes.php',
					datatype: 'html',
					data: {userID: USER_EMAIL},
					success: function(data) {
						var votesString = data;

						// Parse returned string to get ids of user's voted deals
						while( votesString != "" ) {
							var nextComma = votesString.indexOf(',');
							var nextPeriod = votesString.indexOf('.');
							var nextVoteId = votesString.substring(0,nextPeriod);
							var nextVoteValue = votesString.substring(nextPeriod+1,nextComma);
							if( nextVoteValue == "1" ) {
								// If vote was upvote, color up arrow and set value appropriately
								var thisImg = document.getElementById("upvote"+nextVoteId);
								if( thisImg === null || !thisImg ) {}
								else {
									thisImg.src = "green_up_arrow.png?t=" + new Date().getTime();
									document.getElementById("upvote"+nextVoteId+"Value").value = "true";
								}
							}
							else if( nextVoteValue == "-1" ) {
								// If vote was downvote, color down arrow and set value appropriately
								var thisImg = document.getElementById("downvote"+nextVoteId);
								if( thisImg === null || !thisImg ) {}
								else {
									thisImg.src = "red_down_arrow.png?t=" + new Date().getTime();
									document.getElementById("downvote"+nextVoteId+"Value").value = "true";
								}
							}
							votesString = votesString.substring( nextComma + 1 );
						}
					}
				});
			}
		}

		// Update all vote colors
		updateVoteColors();
	</script>
	
	

	<div id="mainContents">
		<div id="dealsHeader" style="padding: 5px; background-color: #FFFFFF; border: 1px solid #C0D6C0; border-radius: 5px;">
			<h3 id="dealsHeaderText">Welcome Back</h3>
		</div>

<?php
	$DEALS_PER_PAGE = 10;

	// Database connection information
	$host = "staxsmysql.mysql.database.azure.com";
	$db_name = "stax_";
	$username = "master_stax@staxsmysql";
	$password = "dev2017softwareB0C@";

	$conn = mysqli_init();
	mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306);

	if(mysqli_connect_errno($conn)){
		die('Failed to connect to MySQL: '.mysqli_connect_error());
	}

	// Queries based on user's sorting method
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

		// Resize image to maintain correct proportions
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

		
		// Echo out deal format with appropriately filled in information
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
			<div class="dealStore" onclick="showPopup(\'deal' . $dealID . '\')">
				<img src="https://maps.googleapis.com/maps/api/staticmap?center=' . $geoLatitude . ',' . $geoLongitude . '&zoom=14&size=190x190&maptype=roadmap&markers=color:red%7C' . $geoLatitude . ',' . $geoLongitude . '&key=AIzaSyB97Z4tKehfoZONpSyFERNZKtTPkxdeDXA" alt="Store Location" width="190" height="190">
			</div>
			<div class="votingColumn">
				<a href="javascript:void(0);" onclick="callUpvote('.$dealID.')">
					<div class="upvoteButton">
						<input type="hidden" id="upvote'.$dealID.'Value" value="false" >
						<img src="gray_up_arrow.png" id="upvote'.$dealID.'" height="20" width="20">
					</div>
				</a>
				<div class="totalVotes" style="color: #A0A0A0;">'.($upVotes-$downVotes).'</div>
				<a href="javascript:void(0);" onclick="callDownvote('.$dealID.')">
					<div class="downvoteButton">
						<input type="hidden" id="downvote'.$dealID.'Value" value="false" >
						<img src="gray_down_arrow.png" id="downvote'.$dealID.'" height="20" width="20">
					</div>
				</a> 
				<a href="javascript:void(0);" onclick="callFavorites('.$dealID.')">
					<div class="favorites">
						<input type="hidden" id="favorites'.$dealID.'Value" value="false" >
						<img src="gray_heart.png" id="favorites'.$dealID.'" height="20" width="20">
					</div>
				</a>
			</div>
		</div>
		';
	}

?>

		<div id="pageLinks">
<?php
	// If necessary, add links for pages 
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
		// Initialize popup Google Map with appropriate information
		function initPopMap() {
			var myLatLng = {lat: 0, lng: 0};

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