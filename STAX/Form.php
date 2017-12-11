<html>
<head>
	<title>Add a Deal</title>
	<link href="staxStyle.css" type="text/css" rel="stylesheet">
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<script src="https://apis.google.com/js/platform.js" async defer></script>
	<meta name="google-signin-client_id" content="505886009165-tjniqhjeihi67b94cgiu0fe34ne7e0dg.apps.googleusercontent.com">

	<style type="text/css">
		#mainContents {
			width: calc( 100% );
			padding: 0px;
		}

		#dealForm {
			float: left;
			width: calc( 100% - 12px );
			background-color: #FFFFFF;
			border: 1px solid #C0D6C0;
			border-radius: 5px;
			padding: 5px;
		}

		#storeName {
			float: left;
			padding-bottom: 5px;
		}

		#enterProductName {
			float: left;
			clear: left;
			padding-bottom: 5px;
			width: 300px;
		}

		#selectDealType {
			float: left;
			clear: left;
			padding-bottom: 5px;
			width: 300px;
		}

		#prices {
			float: left;
			clear: left;
			width: 400px;
			height: 40px;
		}

		#leftColumn {
			float: left;
			padding: 0px;
			margin: 0px;
			width: 400px;
		}

		#rightColumn {
			float: left;
			width: 588px;
		}

		#submitButtons {
			float: left;
			width: 100%;
			text-align: center;
		}

		#selectImageFile {
			float: left;
			width: 300px;
			height: 250px;
			line-height: 250px;
			vertical-align: middle;
		}

		#previewImage { 
			float: right;
			width: 250px;
			height: 250px;
			border: 1px solid gray;
			text-align: center;
			white-space: nowrap;
		}

		.imageHelper {
			display: inline-block;
			height: 100%;
			vertical-align: middle;
		}

		.uploadedImage {
			vertical-align: middle;
			max-height: 250px;
			max-width: 250px;
		}
	</style>


	<script>
		function isNumberKey(evt) {
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
			return true;
	   }

		function signOut() {
    		var auth2 = gapi.auth2.getAuthInstance();
    		auth2.signOut().then(function () {
      		console.log('User signed out.');
      		auth2.disconnect();
      		window.location.replace('index.php');
    	});
    	}

    	var USER_EMAIL = "";
    	function onSignIn(googleUser) {
    		changeHeader();
    		USER_EMAIL = gapi.auth2.getAuthInstance().currentUser.get().getBasicProfile().getEmail();
    		document.getElementById("userNamePost").value = USER_EMAIL;
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
						document.getElementsByClassName("g-signin2")[0].style.display = "none";
					}
					else {
						document.getElementById("accountTab").style.display = "none";
						document.getElementById("signOutButton").style.display = "none";
						document.getElementsByClassName("g-signin2")[0].style.display = "block";
					}
				}
			</script>
			<div class="g-signin2" data-onsuccess="onSignIn"></div>
			<div id="accountTab" style="display:none;"><h4>Your Account</h4></div>
			<div id="signOutButton" href="#" onclick="signOut()" style="display:none;"><h4>Sign Out</h4></div>
		</div> 
	</div>

	<div id="contentsSpacer"></div>
	<div id="contents">
		<div id="spacer"></div>
		<div id="mainContents">
			<div style="padding: 5px; background-color: #FFFFFF; border: 1px solid #C0D6C0; border-radius: 5px; width: calc( 100% - 12px);"> 
				<h3>Add a Deal:</h3>
				<p>Fill out the rest of the deal information, or <a href="new_deal.php" style="text-decoration: underline;">Change Deal Location</a>.</p>
			</div>
			<div id="spacer"></div>
			<div id="dealForm">
				<form method="POST" enctype="multipart/form-data" action="Send_To_Database.php">
				<input type="hidden" id="userNamePost" name="userName" value="">
				<div id="leftColumn">
					<div id="storeName"><p><b>Store:</b> <?php echo $_REQUEST['storeName']; ?></p></div>
					<div id="enterProductName">	
						<b>Product Name:</b>
						<p><textarea name="productName" rows="3" cols="45" required style="resize: none; font-size: 16px;"></textarea></p>
					</div>
					<div id="selectDealType">
						<b>Select Deal Type:</b><br>
						<select name="type" size="2" style="font-size: 16px;" required> 
							<option value="Sale" onclick='changeForm("Sale")' selected >Sale</option>
							<option value="buyXGetXFree" onclick='changeForm("buyXGetXFree")'>Buy X get X free</option>
						</select>
					</div>
					<div id="prices">
						<p id="orgPrice"></p>
						<p id="salePrice"></p>
					</div>
				</div>
				<div id="rightColumn">
					<div id="selectImageFile">
						<a href="#" onclick="openPicker()">Select File</a>
						<input type="hidden" id="imageFileToUpload" name="Image" value="">
						<!-- <p><b>Image:&nbsp </b><input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" onchange="loadFile(event)" required ></p> -->
					</div>
					<div id="previewImage">
						<span class="imageHelper"></span><img id="uploadedImage"/>
					</div>
					<script>
						var loadFile = function(event) {
							var uploadedImage = document.getElementById('uploadedImage');
							uploadedImage.src = URL.createObjectURL(event.target.files[0]);
						};

						var img = document.getElementById("uploadedImage");

						img.onload = function() {
							var width  = img.naturalWidth;
							var height = img.naturalHeight;
							var resizingValue;
							if( height > width ) {
								resizingValue = height;
							}
							else {
								resizingValue = width;
							}
							imageDisplayHeight = height * 250 / resizingValue;
							imageDisplayWidth = width * 250 / resizingValue;

							img.height = imageDisplayHeight;
							img.width = imageDisplayWidth;

							document.getElementById("previewImage").style.display = "block";
						}
					</script>


					<script src="https://static.filestackapi.com/v3/filestack.js"></script>
					<script type="text/javascript">
						var fsClient = filestack.init('ArzLhFWrdQKcx6QBrQB1iz');
						function openPicker() {
							fsClient.pick({
								accept: 'image/*',
								fromSources:["local_file_system","imagesearch","facebook","instagram","dropbox"],
								maxFiles: 1
							}).then(function(response) {
						  		// declare this function to handle response
								//handleFilestack(response);
								// var imageURL = result.filesUploaded[0].url; 
  								// link.href=imageURL; 
  								// link.innerHTML=imageURL;
  								var imageURL = response['filesUploaded'][0]['url'];
  								var uploadedImage = document.getElementById('uploadedImage');
								uploadedImage.src = imageURL;
								document.getElementById("imageFileToUpload").value = imageURL;
							});
						}

						function checkImageUploaded() {
							if( document.getElementById("imageFileToUpload").value == "" ) {
								alert("Please select an image to upload");
								return false;
							}
							else {
								return true;
							}
						}
					</script>
				</div>

				<?php
					$Location = $_REQUEST['Location'];
					$storeName = $_REQUEST['storeName'];
					echo '<input type="hidden" name="Location" value="', $Location, '"/>';
					echo '<input type="hidden" name="storeName" value="', $storeName, '"/>';
				?>
				<div id="submitButtons"> 
					<input type="submit" onclick="return checkImageUploaded()" value="Submit" /> &nbsp <input type="reset" />
				</div>
				</form>


				<script>
					function changeForm(option){
						if(option == 'buyXGetXFree')
						{
							var oldHTML = document.getElementById('salePrice').innerHTML;
							var newHTML = '<b>Price:</b>&nbsp $<input type="int" name="salePrice" size="10" maxlength="30" required />';
							document.getElementById('salePrice').innerHTML = newHTML;

							oldHTML = document.getElementById('orgPrice').innerHTML;
							newHTML = '<b>Buy </b><input type="int" name="itemsToBuy" size="10" maxlength="2" onkeypress="return isNumberKey(event)" required /><b> items and get </b><input type="int" name="freeItems" size="10" maxlength="2" onkeypress="return isNumberKey(event)" required /><b> items free</b>';
							document.getElementById('orgPrice').innerHTML = newHTML;
						}
						else{
							var oldHTML = document.getElementById('salePrice').innerHTML;
							var newHTML = '<b>Sale Price:</b>&nbsp $<input type="int" name="salePrice" size="10" maxlength="30" onkeypress="return isNumberKey(event)" required />';
							document.getElementById('salePrice').innerHTML = newHTML;

							oldHTML = document.getElementById('orgPrice').innerHTML;
							newHTML = '<b>Original Price:</b>&nbsp $<input type="int" name="orgPrice" size="10" maxlength="30" onkeypress="return isNumberKey(event)" required />';
							document.getElementById('orgPrice').innerHTML = newHTML;
						}
					}

					changeForm("Sale");
				</script>
			</div>
		</div>
	</div>
</body>
</html>
<!--  -->
