<?php
session_start();
if (!isset($_COOKIE['logged_in'])) {
  	header('Location: index.php');
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Play Pairs</title>
	<link rel="icon" type="image/x-icon" href="assets/favicon.ico">
	<link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" href="pairsgame.css">
	<style>
		.navbar .active{
			background-color: darkblue;
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
    <a href="pairs.php" style="float:right" class="active">Play Pairs</a>
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
  	<div class="content-box">
		<div class="settings">
			<div style="float: left;">Level:</div>
			<div id="level" style="float: left; padding-left: 0px;">0</div>
			<div style="float: left;">Score:</div>
			<div id="score" style="float: left; padding-left: 0px;">0</div>
			<div style="float: left;">Moves:</div>
			<div id="moves" style="float: left; padding-left: 0px;">0</div>
			<div class="placeholder-text hidden" style="float: left;">Time:</div>
			<div id="timer" class="hidden" style="float: left; padding-left: 0px;">0:00</div>
			<img class="gamebutton" id="music" style="float: right;" onclick="toggleMusic()" src="assets/game/musicon.png">
			<img class="gamebutton" id="sfx" style="float: right;" onclick="toggleSoundEffects()" src="assets/game/sfxon.png">
		</div>
		<div class="prompt hidden">
			<div id="message">
				<p>Congratulations, you completed the game!</p>
				<button id="restart" onclick="restartGame()">Play again</button>
				<button id="submit" onclick="exportScores()">Submit score</button>
			</div>
		</div>
		<div class="overlay inactive"></div>
		<div class="card-container hidden"></div>
		<button id="start">Start Game</button>
	</div>
</div>
</body>
<script src="assets/lib/howler.js"></script>
<script src="pairs.js"></script>
</html>
