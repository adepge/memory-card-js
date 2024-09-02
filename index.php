<?php
session_start();
if (isset($_COOKIE['logged_in'])) {
  $_SESSION['user_name'] = $_COOKIE['user_name'];
  $_SESSION['avatar_skin'] = $_COOKIE['avatar_skin'];
  $_SESSION['avatar_eyes'] = $_COOKIE['avatar_eyes'];
  $_SESSION['avatar_mouth'] = $_COOKIE['avatar_mouth'];
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Welcome to Pairs!</title>
	<link rel="icon" type="image/x-icon" href="assets/favicon.ico">
	<link rel="stylesheet" href="styles.css">
	<style>
	.navbar .active{
		background-color: darkblue;
	}
	.splash{
		width: 400px;
		height: 140px;
		font-family: ARCADEPI;
		background-color:white;
		margin: 0;
		padding: 10px 20px;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		border-radius: 6px;
		text-align: center;
	}
	.button {
	  font-family: ARCADEPI;
	  background-color: green;
	  color: white;
	  padding: 10px 20px;
	  border: none;
	  border-radius: 4px;
	  cursor: pointer;
	  text-decoration: none;
	}
	.button:hover {
	  background-color: darkgreen;
	}
	</style>
</head>
<body>
<div id="main">
  <div class="navbar">
    <?php
        if (isset($_SESSION['user_name'])) {
            $user = $_SESSION['user_name'];
            echo '<a href="index.php" style="float:left" class="active">Welcome, ' . $user . '</a>';
        } else {
            echo '<a href="index.php" style="float:left" class="active">Home</a>';
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
            echo '<div style="float:left; padding-top:6px; padding-bottom: 4px; padding-right:6px;" class="active">';
            echo '<a href="editAvatar.php" style="padding: 0px 0px;"><img src="avatar.php?skin=' . $skin . '&eyes=' . $eyes . '&mouth=' . $mouth . '" width="30px" height="30px"></a>';
		echo '</div>';
        } else {
            echo '<a href="registration.php" style="float:right">Registration</a>';
        }
    ?>
  </div>
  <div class="splash">
	<?php
	if (isset($_SESSION['user_name'])) {
		$user = $_SESSION['user_name'];
		echo '<h2 style="margin-bottom: 40px;">Welcome to Pairs, ' . $user . '!</h2>';
		echo '<a href="pairs.php" class="button">Click here to play</a>';
	} else {
		echo "<h3 style='margin-bottom: 40px;'>You're not using a registered session?</h3>";
		echo '<a href="registration.php">Register now</a>';
	}
	?>
  </div>
</div>
</body>
</html>
