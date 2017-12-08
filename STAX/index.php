<<<<<<< HEAD
<?php require('includes/config.php');

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: stax/index.php'); exit(); }

//if form has been submitted process it
if(isset($_POST['submit'])){

    if (!isset($_POST['username'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['email'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['password'])) $error[] = "Please fill out all fields";

	$username = $_POST['username'];

	//very basic validation
	if(!$user->isValidUsername($username)){
		$error[] = 'Usernames must be at least 3 Alphanumeric characters';
	} else {
		$stmt = $db->prepare('SELECT username FROM members WHERE username = :username');
		$stmt->execute(array(':username' => $username));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['username'])){
			$error[] = 'Username provided is already in use.';
		}

	}

	if(strlen($_POST['password']) < 3){
		$error[] = 'Password is too short.';
	}

	if(strlen($_POST['passwordConfirm']) < 3){
		$error[] = 'Confirm password is too short.';
	}

	if($_POST['password'] != $_POST['passwordConfirm']){
		$error[] = 'Passwords do not match.';
	}

	//email validation
	$email = htmlspecialchars_decode($_POST['email'], ENT_QUOTES);
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $email));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['email'])){
			$error[] = 'Email provided is already in use.';
		}

	}


	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

		//create the activasion code
		$activasion = md5(uniqid(rand(),true));

		try {

			//insert into database with a prepared statement
			$stmt = $db->prepare('INSERT INTO members (username,password,email,active) VALUES (:username, :password, :email, :active)');
			$stmt->execute(array(
				':username' => $username,
				':password' => $hashedpassword,
				':email' => $email,
				':active' => $activasion
			));
			$id = $db->lastInsertId('memberID');

			//send email
			$to = $_POST['email'];
			$subject = "Registration Confirmation";
			$body = "<p>Thank you for registering to the Stax site.</p>
			<p>To activate your account, please click on this link: <a href='".DIR."activate.php?x=$id&y=$activasion'>".DIR."activate.php?x=$id&y=$activasion</a></p>
			<p>Regards Site Admin</p>";

			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();

			//redirect to index page
			header('Location: index.php?action=joined');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

//define page title
$title = 'Register with Stax';

//include header template
require('layout/header.php');
?>


<div class="container">

	<div class="row">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
			<form role="form" method="post" action="" autocomplete="off">
				<h2>Please Sign Up</h2>
				<p>Already a member? <a href='login.php'>Login</a></p>
				<hr>

				<?php
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo '<p class="bg-danger">'.$error.'</p>';
					}
				}

				//if action is joined show sucess
				if(isset($_GET['action']) && $_GET['action'] == 'joined'){
					echo "<h2 class='bg-success'>Registration successful, please check your email to activate your account.</h2>";
				}
				?>

				<div class="form-group">
					<input type="text" name="username" id="username" class="form-control input-lg" placeholder="User Name" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['username'], ENT_QUOTES); } ?>" tabindex="1">
				</div>
				<div class="form-group">
					<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['email'], ENT_QUOTES); } ?>" tabindex="2">
				</div>
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="3">
						</div>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Password" tabindex="4">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Register" class="btn btn-primary btn-block btn-lg" tabindex="5"></div>
				</div>
			</form>
		</div>
	</div>

</div>

<?php
//include header template
require('layout/footer.php');
?>
=======
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
		<a href="new_deal.php"><div class="sidebarButton"><p>Add a Deal</p></div></a>
	</div>

	<div id="mainContents">
		<div style="padding: 5px; background-color: #FFFFFF; border: 1px solid #C0D6C0; border-radius: 5px;"><h3>Add a New Deal</h3>
		</div>

<?php
	$DEALS_PER_PAGE = 10;

	$conn = @mysqli_connect('127.0.0.1', 'root', 'root', 'stax');

	// $host = "staxsmysql.mysql.database.azure.com";
	// $db_name = "stax_";
	// $username = "master_stax@staxsmysql";
	// $password = "dev2017softwareB0C@";

	// $conn = mysqli_init();
	// mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306);

	if(mysqli_connect_errno($conn)){
		die('Failed to connect to MySQL: '.mysqli_connect_error());
	}

	$query = "SELECT * FROM deals LIMIT " . $DEALS_PER_PAGE . " OFFSET " . ($_GET['page'] * $DEALS_PER_PAGE) . ";";

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

		list($width, $height, $type, $attr) = getimagesize( $image );
		if( $height > $width ) {
			$resizingValue = $height;
		}
		else {
			$resizingValue = $width;
		}
		$imageDisplayHeight = $height * 190 / $resizingValue;
		$imageDisplayWidth = $width * 190 / $resizingValue;

		if( substr($dealType, 0, 4) == "Sale" ) {
			echo '
		<div class="deal" id="deal' . $dealID . '" onclick="showPopup(\'deal' . $dealID . '\')">
			<div class="dealImage"><span class="imageHelper"></span><img class="actualImage" src="' . $image . '" alt="Item Image" width="' . $imageDisplayWidth . '" height="' . $imageDisplayHeight . '"></div>
			<div class="dealInfo">
				<div class="dealType"><h4>' . $dealType . '</h4></div>
				<div class="dealItem"><h3>' . $itemName . '</h3></div>
				<div class="dealNewPrice"><h2>Price: $' . $salePrice . '</h2></div>
				<div class="dealOldPrice"><p>Original Price: $' . $originalPrice . '</p></div>
				<input type="hidden" id="deal'.$dealID.'lat" value='.$geoLatitude.' />
				<input type="hidden" id="deal'.$dealID.'long" value='.$geoLongitude.' />
			</div>
			<div class="dealStore">
				<img src="" alt="Store Logo" width="190" height="190">
			</div>
		</div>
			';
		}
		else if( substr($dealType, -4) == "Free" ){
			echo '
		<div class="deal" id="deal' . $dealID . '" onclick="showPopup(\'deal' . $dealID . '\')">
			<div class="dealImage"><span class="imageHelper"></span><img class="actualImage"  src="' . $image . '" alt="Item Image" width="' . $imageDisplayWidth . '" height="' . $imageDisplayHeight . '"></div>
			<div class="dealInfo">
				<div class="dealType"><h4>' . $dealType . '</h4></div>
				<div class="dealItem"><h3>' . $itemName . '</h3></div>
				<div class="dealNewPrice"><h2>Price for One: $' . $salePrice . '</h2></div>
				<input type="hidden" id="deal'.$dealID.'lat" value='.$geoLatitude.' />
				<input type="hidden" id="deal'.$dealID.'long" value='.$geoLongitude.' />
			</div>
			<div class="dealStore">
				<img src="" alt="Store Logo" width="190" height="190">
			</div>
		</div>
			';
		}
	}

?>

		<div id="pageLinks">
<?php
	$totalDealsQuery = "SELECT COUNT(*) FROM deals;";
	$dealsResultSet = mysqli_query( $conn, $totalDealsQuery );
	$totalDeals = mysqli_fetch_array( $dealsResultSet )[0];

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
	<div id="popupImage"><span class="imageHelper"></span><img class="actualPopupImage" src="" height="" width="" alt="No Image Available"></div>
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
>>>>>>> 1726469c6dbd3fb5db05fd547fcb8a90b356b1c7
