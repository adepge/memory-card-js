<?php
	$loginfail = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mysqli = new mysqli("db", "ecm1417", "WebDev2021", "pairs_game");
		$username = $_POST["username"];

		$sql = "SELECT * FROM `registered_users` WHERE username = ?";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows == 0) {
			$loginfail = true;
		} else {
			$row = $result->fetch_assoc();
			$skin = $row["skin"];
			$eyes = $row["eyes"];
			$mouth = $row["mouth"];
			if($username == $_COOKIE['user_name']){
				setcookie('logged_in', 1, time() + (86400 * 7), '/');
				header('Location: index.php');
				exit;
			} else {
				setcookie('logged_in', 1, time() + (86400 * 7), '/');
				setcookie('user_name', $username, time() + (86400 * 90), '/');
				setcookie('avatar_skin', $skin, time() + (86400 * 90), '/');
				setcookie('avatar_eyes', $eyes, time() + (86400 * 90), '/');
				setcookie('avatar_mouth', $mouth, time() + (86400 * 90), '/');
				header('Location: index.php');
				exit;
			}
		}	
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
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
			<a href="index.php" style="float:left">Home</a>
			<a href="pairs.php" style="float:right">Play Pairs</a>
			<a href="leaderboard.php" style="float:right">Leaderboard</a>
			<a href="registration.php" style="float:right" class="active">Login</a>
		</div>
		<div class="form">
			<h3>Login</h3>
			<form method="post">
				<?php
					if($loginfail == true){
						echo '<div id="prompt">Username does not exist.</div>';
					}
				?>
				<label for="username">Username:</label>
				<input type="text" name="username" required><br><br>
			<input type="submit" value="Login" id="submit-button">
			</form>
			<div class="login">
				<p style="display: inline-block; margin-right:4px;"> Not yet registered?</p><a href="registration.php">Click here to register</a>
			</div>
		</div>
	</div>
</body>
</html>
