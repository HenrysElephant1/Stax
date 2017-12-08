<html>
	<head>
		<title>Form</title>
	</head>
	<body>


		<form method="POST" enctype="multipart/form-data" action="Send_To_Database.php">
		<p>Product Name:</p>
		<p>&nbsp <textarea name="productName" rows="10" cols="30" required></textarea></p>
		<select name="type" size="3" style="width=500px height=300px" required> 
				<option value="buyXGetXFree" onclick='changeForm("buyXGetXFree")'>          Buy X get X free          </option>
				<option value="Sale" onclick='changeForm("Sale")'>          Sale          </option> </select>
		<p id="orgPrice"></p>
		<p id="salePrice"></p>
		<p>Image:&nbsp <input type="file" name="fileToUpload" id="fileToUpload" /></p>
	<?php
		$Location = $_REQUEST['Location'];
		$storeName = $_REQUEST['storeName'];
		echo '<input type="hidden" name="Location" value="', $Location, '"/>';
		echo '<input type="hidden" name="storeName" value="', $storeName, '"/>';
	?>
		<input type="submit" value="Submit" /> &nbsp <input type="reset" />
		</form>
		<script>
			

			function changeForm(option){
				if(option == 'buyXGetXFree')
				{
					var oldHTML = document.getElementById('salePrice').innerHTML;
					var newHTML = 'Price:&nbsp <input type="int" name="salePrice" size="10" maxlength="30" required />';
					document.getElementById('salePrice').innerHTML = newHTML;

					oldHTML = document.getElementById('orgPrice').innerHTML;
					newHTML = 'Buy <input type="int" name="itemsToBuy" size="10" maxlength="2" required /> items and get <input type="int" name="freeItems" size="10" maxlength="2" required /> items free';
					document.getElementById('orgPrice').innerHTML = newHTML;
				}
				else{
					var oldHTML = document.getElementById('salePrice').innerHTML;
					var newHTML = 'Sale Price:&nbsp <input type="int" name="salePrice" size="10" maxlength="30" required />';
					document.getElementById('salePrice').innerHTML = newHTML;

					oldHTML = document.getElementById('orgPrice').innerHTML;
					newHTML = 'Original Price:&nbsp <input type="int" name="orgPrice" size="10" maxlength="30" required />';
					document.getElementById('orgPrice').innerHTML = newHTML;
				}
			}
		</script>


	</body>
 </html>
