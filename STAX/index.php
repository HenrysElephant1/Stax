<!DOCTYPE html>
<html>
<head>
	<title>Stand-In</title>
	<link href="staxStyle.css" type="text/css" rel="stylesheet">
	<script>
		function showPopup( dealID ) {
			var dealDiv = document.getElementById( dealID );

			// Fill in image attributes
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
		}

		function hidePopup() {
			document.getElementById("cardBackgroundOverlay").style.display = 'none';
			document.getElementById("clickedCard").style.display = 'none';
		}
	</script>
</head>
<body>
<div id="headerSpan">
	<div id="header"><!-- Original header:
		<div id="headerImage"><img src="moneybag.png" alt="Money Muthafucka" height="36" width="36"></div>
		<div id="headerText"><h2>STAX</h2></div> -->
		<div id="headerImage"><a href="index.php"><img src="staxbook.png" alt="We're not a ripoff please don't sue us" height="36" width="100"></a></div>
		<div id="accountTab"><h4>Your Account</h4></div>
	</div> 
</div>
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
	for( $i=0; $i<10; $i++ ) {
		$image = "baguette.jpeg";
		$itemName = "Baguette";
		$salePrice = "100.00";
		$originalPrice = "German Invasion";
		$storeImage = "france.png";

		echo '
		<div class="deal" id="deal' . $i . '" onclick="showPopup(\'deal' . $i . '\')">
			<div class="dealImage"><img src="' . $image . '" alt="Item Image" width="190" height="190"></div>
			<div class="dealInfo">
				<div class="dealItem"><h3>' . $itemName . '</h3></div>
				<div class="dealNewPrice"><h2>Sale Price: $' . $salePrice . '</h2></div>
				<div class="dealOldPrice"><p>Original Price: $' . $originalPrice . '</p></div>
			</div>
			<div class="dealStore">
				<img src="' . $storeImage . '" alt="Store Logo" width="190" height="190">
			</div>
		</div>';
	}
?>
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
</div>

</body>
</html>