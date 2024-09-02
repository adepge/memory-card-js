<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$mysqli = new mysqli("db", "ecm1417", "WebDev2021", "pairs_game");
	
	$username = $_SESSION['user_name'];
	$skin = $_POST['skin'];
	$eyes = $_POST['eyes'];
	$mouth = $_POST['mouth'];
	
	setcookie('avatar_skin', $skin, time() + (86400 * 90), '/');
	setcookie('avatar_eyes', $eyes, time() + (86400 * 90), '/');
	setcookie('avatar_mouth', $mouth, time() + (86400 * 90), '/');

	$sql = "INSERT INTO `registered_users` (username, skin, eyes, mouth) VALUES ('$username','$skin','$eyes','$mouth')";
	mysqli_query($mysqli, $sql);
	
	header('Location: index.php');
	exit;	
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Avatar</title>
	<link rel="icon" type="image/x-icon" href="assets/favicon.ico">
	<link rel="stylesheet" href="styles.css">
	<style>
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
	.navbar .active{
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
    <?php
        if (isset($_SESSION['user_name'])) {
            $user = $_SESSION['user_name'];
            echo '<a href="index.php" style="float:left">Welcome, ' . $user . '</a>';
        } else {
            echo '<a href="index.php" style="float:left">Home</a>';
        }
    ?>
    <a href="pairs.php" style="float:right">Play Pairs</a>
    <a href="leaderboard.php" style="float:right">Leaderboard</a>
    <?php
        if (isset($_SESSION['user_name'])) {
            echo '<a href="logout.php" style="float:right">Logout</a>';
			
            // Get user avatar from session or cookie
            $skin = $_SESSION['avatar_skin'];
            $eyes = $_SESSION['avatar_eyes'];
            $mouth = $_SESSION['avatar_mouth'];

            // Display avatar
            echo '<div style="float:left; margin-top: 6px;">';
            echo '<a href="editAvatar.php" style="padding: 0px 0px;"><img src="avatar.php?skin=' . $skin . '&eyes=' . $eyes . '&mouth=' . $mouth . '" width="30px" height="30px"></a>';
			echo '</div>';
        } else {
            echo '<a href="registration.php" style="float:right">Registration</a>';
        }
    ?>
  </div>
		<div class="form">
			<h3>Register</h3>
			<form method="post">
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
				<input type="submit" value="Change my avatar" id="submit-button">
			</form>
		</div>
	</div>
	<script>
		function getCookie(c_name) {
			if (document.cookie.length > 0) {
				c_start = document.cookie.indexOf(c_name + "=");
				if (c_start != -1) {
					c_start = c_start + c_name.length + 1;
					c_end = document.cookie.indexOf(";", c_start);
					if (c_end == -1) {
						c_end = document.cookie.length;
					}
					return unescape(document.cookie.substring(c_start, c_end));
				}
			}
			return "";
		}
		
		var skin = getCookie('avatar_skin');
		var eyes = getCookie('avatar_eyes');
		var mouth = getCookie('avatar_mouth');
		
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

				var imageUrl = $(this).attr('src');
				$(this).parent().find('input[type="hidden"]').val(imageUrl);
				updatePreview();
			});
			
			$('.avatar .asset-container:nth-child(2)').find("img[src$='" + skin + "']").click();
			$('.avatar .asset-container:nth-child(3)').find("img[src$='" + eyes + "']").click();
			$('.avatar .asset-container:nth-child(4)').find("img[src$='" + mouth + "']").click();
		});
	</script>
</body>

</html>
