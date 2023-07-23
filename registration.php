<?php
	$registerfail = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$mysqli = new mysqli("localhost", "ecm1417", "WebDev2021", "users");
		$username = $_POST["username"];

		$sql = "SELECT * FROM `registered_users` WHERE username = ?";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows > 0) {
			$registerfail = true;
		} else {
			$username = $_POST['username'];
			$skin = $_POST['skin'];
			$eyes = $_POST['eyes'];
			$mouth = $_POST['mouth'];

			setcookie('logged_in', 1, time() + (86400 * 7), '/');
			setcookie('user_name', $username, time() + (86400 * 90), '/');
			setcookie('avatar_skin', $skin, time() + (86400 * 90), '/');
			setcookie('avatar_eyes', $eyes, time() + (86400 * 90), '/');
			setcookie('avatar_mouth', $mouth, time() + (86400 * 90), '/');
			
			$sql = "INSERT INTO `registered_users` (username, skin, eyes, mouth) VALUES ('$username','$skin','$eyes','$mouth')";
			mysqli_query($mysqli, $sql);
			header('Location: index.php');
			exit;
		}	
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration Page</title>
	<link rel="icon" type="image/x-icon" href="assets/favicon.ico">
	<link rel="stylesheet" href="styles.css">
	<style>
	.navbar .active{
		background-color: darkblue;
	}
	.form{
		font-family: Verdana;
		background-color:white;
		margin: 0;
		padding: 20px 30px;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		border-radius: 6px;
	}
	input[type=text]{
		width: 85%;
		padding: 12px;
		margin: 6px;
		display: inline-block;
		border: 2px solid gray;
		border-radius: 4px;
	}
	.avatar{
	  display: flex;
	  flex-direction: column;
	  justify-content: center;
	  align-items: center;
	  gap: 3px;
	  padding: 10px;
	  box-sizing: border-box;
	  margin-bottom:20px;
	}
	.asset-container{
		display: flex;
		flex-direction: row;
		justify-content: center;
		align-items: center;
		gap: 5px;
	}
	.asset-container img{
		max-width: 30px;
		padding: 7px;
		border: 2px solid gray;
		border-radius: 4px;
		cursor: pointer;
	}
	.asset-container .active{
		border-color: red;
	}
	.preview {
	  position: relative;
	  width: 70px;
	  height: 70px;
	  border: 2px solid gray;
	  border-radius:4px;
	  margin-bottom: 20px;
	}
	.preview img {
	  position: absolute;
	  top: 14%;
	  left: 14%;
	  width: 50px;
	  height: 50px;
	  object-fit: contain;
	}

	.preview .eyes {
	  top: 20%;
	}

	.preview .mouth {
	  top: 60%;
	}
	.avatar-preview {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		gap: 3px;
		padding: 10px;
	}
	#submit-button {
	  background-color: blue;
	  color: white;
	  padding: 10px 20px;
	  border: none;
	  border-radius: 4px;
	  cursor: pointer;
	  margin-bottom:10px;
	}
	#submit-button:hover {
	  background-color: darkblue;
	}
	#prompt{
		background-color: red;
		border: none;
		border-radius: 2px;
		color: white;
		padding: 6px;
		font-size: 12px;
		margin-bottom: 20px;
	}
	</style>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
	<div id="main">
		<div class="navbar">
			<a href="index.php" style="float:left">Home</a>
			<a href="pairs.php" style="float:right">Play Pairs</a>
			<a href="leaderboard.php" style="float:right">Leaderboard</a>
			<a href="registration.php" style="float:right" class="active">Registration</a>
		</div>
		<div class="form">
			<h3>Register</h3>
			<form method="post">
					<?php
						if($registerfail == true){
							echo '<div id="prompt">Username has already been taken,<br>';
							echo 'please choose another one.</div>';
						}
					?>
					<label for="username">Username:</label>
					<input type="text" id="username" name="username" required><br><br>
				<p>Avatar:</p>
				<div class="avatar">
					<div class="preview">
						<img class="skin" src="/assets/skin/red.png">
						<img class="eyes" src="/assets/eyes/closed.png">
						<img class="mouth" src="/assets/mouth/surprise.png">
					</div>
					<div class="asset-container">
						<img src="/assets/skin/red.png"/>
						<img src="/assets/skin/yellow.png"/>
						<img src="/assets/skin/green.png"/>
						<input type="hidden" name="skin" value="">
					</div>
					<div class="asset-container">
						<img class="eyes" src="/assets/eyes/closed.png"/>
						<img class="eyes" src="/assets/eyes/laughing.png"/>
						<img class="eyes" src="/assets/eyes/long.png"/>
						<img class="eyes" src="/assets/eyes/normal.png"/>
						<img class="eyes" src="/assets/eyes/rolling.png"/>
						<img class="eyes" src="/assets/eyes/winking.png"/>
						<input type="hidden" name="eyes" value="">
					</div>
					<div class="asset-container">
						<img class="mouth" src="/assets/mouth/surprise.png"/>
						<img class="mouth" src="/assets/mouth/open.png"/>
						<img class="mouth" src="/assets/mouth/sad.png"/>
						<img class="mouth" src="/assets/mouth/smiling.png"/>
						<img class="mouth" src="/assets/mouth/straight.png"/>
						<img class="mouth" src="/assets/mouth/teeth.png"/>
						<input type="hidden" name="mouth" value="">
					</div>
				</div>
				<input type="submit" value="Register" id="submit-button">
			</form>
			<div class="login">
				<p style="display: inline-block; margin-right:4px;"> Already registered?</p><a href="login.php">Click here to login</a>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			  function updatePreview() {
				var skinUrl = $('.avatar .asset-container:nth-child(2) img.active').attr('src');
				var eyesUrl = $('.avatar .asset-container:nth-child(3) img.active').attr('src');
				var mouthUrl = $('.avatar .asset-container:nth-child(4) img.active').attr('src');
				$('.avatar .preview').html('<img src="' + skinUrl + '"/><img src="' + eyesUrl + '"/><img src="' + mouthUrl + '"/>');
			  }

			  $('.asset-container img').click(function() {
				$(this).siblings().removeClass('active');
				$(this).addClass('active');

				// Update hidden input field
				var imageUrl = $(this).attr('src');
				$(this).parent().find('input[type="hidden"]').val(imageUrl);

				// Update avatar preview
				updatePreview();
			});
		  
			// Trigger event on page load: sets default assets for avatar picker
			$('.avatar .asset-container:nth-child(2) img:first-child').click();
			$('.avatar .asset-container:nth-child(3) img:first-child').click();
			$('.avatar .asset-container:nth-child(4) img:first-child').click();
		});
		const form = document.querySelector('form');
		const usernameInput = document.getElementById('username');
		
		usernameInput.addEventListener('input', function() {
		  this.setCustomValidity(''); // clear custom validity message
		});
		
		form.addEventListener('submit', function(event) {
		  event.preventDefault(); // prevent form from submitting
		  const regex = /[!"@#%&*()+=^{}\[\]—;:“’<>?/]/;
		  if (regex.test(usernameInput.value)) {
			usernameInput.setCustomValidity('Please enter a username without the following characters: ” ! @ # % & * ( ) + = ˆ { } [ ] — ; : “ ’ < > ? /');
			usernameInput.reportValidity();
			return;
		  }
		  this.submit();
		});
	</script>
</body>

</html>
