<html>
<head>
	<title>Add a Deal</title>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<link href="staxStyle.css" type="text/css" rel="stylesheet">
	<script src="https://apis.google.com/js/platform.js" async defer></script>
	<meta name="google-signin-client_id" content="505886009165-tjniqhjeihi67b94cgiu0fe34ne7e0dg.apps.googleusercontent.com">
	<meta charset="utf-8">
	


	<style type="text/css">
		#mainContents {
			float: left;
			width: calc( 100% );
			margin: 0px;
			padding: 0px;
			padding-top: 0px;
		}

		#map {
			float: left;
			height: 500px;
			width: 100%;
		}

		#description {
			font-family: Roboto;
			font-size: 15px;
			font-weight: 300;
		}

		.controls {
			background-color: #fff;
			border-radius: 2px;
			border: 1px solid transparent;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
			box-sizing: border-box;
			font-family: Roboto;
			font-size: 15px;
			font-weight: 300;
			height: 29px;
			margin-left: 17px;
			margin-top: 10px;
			outline: none;
			padding: 0 11px 0 13px;
			text-overflow: ellipsis;
			width: 400px;
		}

		.controls:focus {
			border-color: #4d90fe;
		}
		.title {
			font-weight: bold;
		}
		#description {
			font-family: Roboto;
			font-size: 15px;
			font-weight: 300;
		}

		#infowindow-content {
			display: none;
		}
		#map #infowindow-content {
			display: inline;
		}

		.pac-card {
			margin: 10px 10px 0 0;
			border-radius: 2px 0 0 2px;
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			outline: none;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
			background-color: #fff;
			font-family: Roboto;
		}

		#pac-container {
			padding-bottom: 12px;
			margin-right: 12px;
		}

		.pac-controls {
			display: inline-block;
			padding: 5px 11px;
		}

		.pac-controls label {
			font-family: Roboto;
			font-size: 13px;
			font-weight: 300;
		}

		#pac-input {
			background-color: #fff;
			font-family: Roboto;
			font-size: 15px;
			font-weight: 300;
			margin-left: 12px;
			padding: 0 11px 0 13px;
			text-overflow: ellipsis;
			width: 400px;
		}

		#pac-input:focus {
			border-color: #4d90fe;
		}

		#title {
			color: #fff;
			background-color: #4d90fe;
			font-size: 25px;
			font-weight: 500;
			padding: 6px 12px;
		}
		#target {
			width: 345px;
		}

	</style>


	<script>

		function signOut() {
    		var auth2 = gapi.auth2.getAuthInstance();
    		auth2.signOut().then(function () {
      		console.log('User signed out.');
      		auth2.disconnect();
      		window.location.replace('index.php');
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


	<div id="contentsSpacer"></div>
	<div id="contents">
		<div id="spacer"></div>
		<div id="mainContents">
			<div style="padding: 5px; background-color: #FFFFFF; border: 1px solid #C0D6C0; border-radius: 5px; width: calc( 100% - 12px);">
				<h3>Add a Deal:</h3>
				<p>Allow location services for this page, then use the search bar to find a store or enter an address, click the icon (not the red popup marker if you search a specific store), then click "Select Store" to go to the next step.</p>
			</div>

			<div id="spacer"></div>	
			<input id="pac-input" class="controls" type="text" placeholder="Search Box">
			<div id="map"></div>
			<script>
				// Note: This example requires that you consent to location sharing when
				// prompted by your browser. If you see the error "The Geolocation service
				// failed.", it means you probably did not give permission for the browser to
				// locate you.
				var map, infoWindow;
				function initMap() {
					map = new google.maps.Map(document.getElementById('map'), {
						center: {lat: -34.397, lng: 150.644},
						zoom: 13
					});
					infoWindow = new google.maps.InfoWindow;

					// Try HTML5 geolocation.
					if (navigator.geolocation) {
						navigator.geolocation.getCurrentPosition(function(position) {
							var pos = {
								lat: position.coords.latitude,
								lng: position.coords.longitude
							};

							infoWindow.setPosition(pos);
							infoWindow.setContent('Location found.');
							infoWindow.open(map);
							map.setCenter(pos);
						}, function() {
							handleLocationError(true, infoWindow, map.getCenter());
						});
					} else {
						// Browser doesn't support Geolocation
						handleLocationError(false, infoWindow, map.getCenter());
					}


					//PlaceID Finder code
					var input = document.getElementById('pac-input');

					var autocomplete = new google.maps.places.Autocomplete(input);
					autocomplete.bindTo('bounds', map);

					// map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

				 	// var infowindowContent = document.getElementById('infowindow-content');
					var infowindowContent = '0';


					//error caused here
					infoWindow.setContent(infowindowContent);




					var marker = new google.maps.Marker({
						map: map
					});
					marker.addListener('click', function() {
						infoWindow.open(map, marker);
					});

					autocomplete.addListener('place_changed', function() {
						infoWindow.close();
						var place = autocomplete.getPlace();
						if (!place.geometry) {
							return;
						}

						if (place.geometry.viewport) {
							map.fitBounds(place.geometry.viewport);
						} else {
							map.setCenter(place.geometry.location);
							map.setZoom(17);
						}

						// Set the position of the marker using the place ID and location.
						marker.setPlace({
							placeId: place.place_id,
							location: place.geometry.location
						});
						marker.setVisible(true);

						infowindowContent.children['place-name'].textContent = place.name;
						infowindowContent.children['select-link'].textContent = '<div>' + '<a href="Form.php">Select Store</a>' + '</div>';
						infowindowContent.children['place-id'].textContent = place.place_id;
						infowindowContent.children['place-address'].textContent =
								place.formatted_address;

						// infowindow = new google.maps.InfoWindow({
						//         content: '<div>' + place.name + '</div>' + '<div>' + '<a href="www.google.com">Select Store</a>' + '</div>' + '<div>' + place.place_id + '</div>' + '<div>' + place.formatted_address + '</div>'
						// });

						infoWindow.open(map, marker);
					});
				
					// end placeid code
					// Create the search box and link it to the UI element.
					var sbox = document.getElementById('pac-input');
					var searchBox = new google.maps.places.SearchBox(sbox);
					map.controls[google.maps.ControlPosition.TOP_LEFT].push(sbox);
					//map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

					// Bias the SearchBox results towards current map's viewport.
					map.addListener('bounds_changed', function() {
						searchBox.setBounds(map.getBounds());
					});

					var markers = [];
					// Listen for the event fired when the user selects a prediction and retrieve
					// more details for that place.
					searchBox.addListener('places_changed', function() {
						var places = searchBox.getPlaces();

						if (places.length == 0) {
							return;
						}

						// Clear out the old markers.
						markers.forEach(function(marker) {
							marker.setMap(null);
						});
						markers = [];

						// For each place, get the icon, name and location.
						var bounds = new google.maps.LatLngBounds();
						places.forEach(function(place) {
							if (!place.geometry) {
								console.log("Returned place contains no geometry");
								return;
							}
							var icon = {
								url: place.icon,
								size: new google.maps.Size(71, 71),
								origin: new google.maps.Point(0, 0),
								anchor: new google.maps.Point(17, 34),
								scaledSize: new google.maps.Size(25, 25)
							};

							// Create a marker for each place.
							var nMarker = new google.maps.Marker({
								map: map,
								icon: icon,
								title: place.name,
								position: place.geometry.location
							});
							markers.push(nMarker);

							//content for infowindow that is opened when marker is clicked
							var contentString = '<div>' + place.name + '</div>' + '<div>' + '<a href="Form.php?Location=' + place.geometry.location + '&storeName=' + place.name + '">Select Store</a>' + '</div>'
							 + '<div>' + place.formatted_address + '</div>' + '<div>' + place.geometry.location + '</div>';
							var markerWindow = new google.maps.InfoWindow({
											content: contentString
							});
							

							if (place.geometry.viewport) {
								// Only geocodes have viewport.
								bounds.union(place.geometry.viewport);
							} else {
								bounds.extend(place.geometry.location);
							}

							// listener/function called when marker is clicked
							nMarker.addListener('click', function() {
											markerWindow.open(map, nMarker);
							});
						});
						map.fitBounds(bounds);



						});

				}


				

				function handleLocationError(browserHasGeolocation, infoWindow, pos) {
					infoWindow.setPosition(pos);
					infoWindow.setContent(browserHasGeolocation ?
																'Error: The Geolocation service failed.' :
																'Error: Your browser doesn\'t support geolocation.');
					infoWindow.open(map);
				}
			</script>
			<script async defer
			src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB97Z4tKehfoZONpSyFERNZKtTPkxdeDXA&libraries=places&callback=initMap">
			</script>

		</div>
	</div>

</body>
</html>



<!-- AIzaSyAdAsk5El6CENKCL1BsJzdQTRGTdhauTnw -->