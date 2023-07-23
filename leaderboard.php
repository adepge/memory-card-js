<!DOCTYPE html>
<?php
session_start();
$user = $_SESSION['user_name'];
$numLevels = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$scores = json_decode($_POST["levelScores"]);
$numLevels = count($scores);
$extraLevels = $numLevels - 10;
}

// Database connection
$servername = "localhost";
$username = "ecm1417";
$password = "WebDev2021";
$dbname = "leaderboard";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if($numLevels > 0){
	if($extraLevels > 0){
		for ($x = 0; $x < $extraLevels; $x++) {
		$extraTable = 11 + $x;
		$sql = "CREATE TABLE IF NOT EXISTS $extraTable(
			username TEXT PRIMARY KEY,
			score INT)";
		mysqli_query($conn, $sql);
		}
	}
	$level = 1;
	$total = 0;
	foreach ($scores as $value) {
		$total = $total + $value;
		$sql = "INSERT INTO `" . $level . "` (username, score) VALUES ('$user','$value')";
		mysqli_query($conn, $sql);
		$level = $level + 1;
	}
	$sql = "INSERT INTO `All` (username, score) VALUES ('$user','$total')";
	mysqli_query($conn, $sql);
	header("Refresh: 4");
}
$delay = 4;
if (!isset($_COOKIE['refreshed'])) {
  setcookie('refreshed', 'true', time()+ (60 * 3));
  header("Refresh: $delay");
}
?>
<html>
<head>
	<title>Leaderboard</title>
	<link rel="icon" type="image/x-icon" href="assets/favicon.ico">
	<link rel="stylesheet" href="styles.css">
	<style>
		.navbar .active{
			background-color: darkblue;
		}
		#leaderboard{
			width: 45%;
			height: 65%;
			font-family: Verdana;
			background-color: rgb(128,128,128,0.9); /* RGB values correspond to background-color:gray */
			backdrop-filter: blur(5px);
			margin: 0;
			padding: 20px;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			border-radius: 6px;
			display: flex;
			flex-direction: column;
		}
		#level-selector{
			flex-basis: 10%;
			display: flex;
			flex-wrap: wrap;
			gap: 4px;
			justify-content: center;
			align-items: center;
		}
		#table{
			flex-basis: 90%;
		}
		.level{
			width: 45px;
			flex-basis: auto;
			display: flex;
			aspect-ratio: 1 / 1;
			background-color: white;
			align-items: center;
			justify-content: center;
			border-radius: 4px;
			font-family: ARCADEPI;
			color: orange;
			font-size: 20px;
			text-shadow: 1px 1px #383838;
			cursor: pointer;
			box-shadow: 2px 1px #383838;
		}
		.level:hover{
			background-color: lightgray;
		}
		table{
			margin-top: 20px;
			font-family: ARCADEPI;
			border-collapse: collapse;
			width: 100%;
		}
		td{
			padding: 5px;
			color: white;
		}
		th{
			padding: 5px;
			padding-top: 10px;
			padding-bottom: 10px;
			text-align: left;
			color: white;
			text-shadow: 2px 2px #383838;
		}
		.selected{
			background-color: lightgray;
		}
		.leaderboard{
			display: none;
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
		<a href="leaderboard.php" style="float:right" class="active">Leaderboard</a>
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
	  <div id="leaderboard">
		<div id="level-selector">
			<div class="level" onclick="showLeaderboard('leaderboardall')">All</div>
			<div class="level" onclick="showLeaderboard('leaderboard1')">1</div>
			<div class="level" onclick="showLeaderboard('leaderboard2')">2</div>
			<div class="level" onclick="showLeaderboard('leaderboard3')">3</div>
			<div class="level" onclick="showLeaderboard('leaderboard4')">4</div>
			<div class="level" onclick="showLeaderboard('leaderboard5')">5</div>
			<div class="level" onclick="showLeaderboard('leaderboard6')">6</div>
			<div class="level" onclick="showLeaderboard('leaderboard7')">7</div>
			<div class="level" onclick="showLeaderboard('leaderboard8')">8</div>
			<div class="level" onclick="showLeaderboard('leaderboard9')">9</div>
			<div class="level" onclick="showLeaderboard('leaderboard10')">10</div>
		</div>
		<div id="table">
			<div class="leaderboard" id="leaderboardall">
				<?php
					$query = "SELECT * FROM `All` ORDER BY score DESC";
					$result = mysqli_query($conn, $query);

					echo "<table>";
					echo "<tr><th style='font-size:25px;'>#</th><th>Username</th><th>Score</th></tr>";
					$position = 1;
					while ($row = mysqli_fetch_assoc($result)) {
					  echo "<tr><td>" . $position++ . "</td><td>" . $row["username"] . "</td><td>" . $row["score"] . "</td></tr>";
					}
					echo "</table>";
				?>
			</div>
			<div class="leaderboard" id="leaderboard1">
				<?php
					$query = "SELECT * FROM `1` ORDER BY score DESC";
					$result = mysqli_query($conn, $query);

					echo "<table>";
					echo "<tr><th style='font-size:25px;'>#</th><th>Username</th><th>Score</th></tr>";
					$position = 1;
					while ($row = mysqli_fetch_assoc($result)) {
					  echo "<tr><td>" . $position++ . "</td><td>" . $row["username"] . "</td><td>" . $row["score"] . "</td></tr>";
					}
					echo "</table>";
				?>
			</div>
			<div class="leaderboard" id="leaderboard2">
				<?php
					$query = "SELECT * FROM `2` ORDER BY score DESC";
					$result = mysqli_query($conn, $query);

					echo "<table>";
					echo "<tr><th style='font-size:25px;'>#</th><th>Username</th><th>Score</th></tr>";
					$position = 1;
					while ($row = mysqli_fetch_assoc($result)) {
					  echo "<tr><td>" . $position++ . "</td><td>" . $row["username"] . "</td><td>" . $row["score"] . "</td></tr>";
					}
					echo "</table>";
				?>
			</div>
			<div class="leaderboard" id="leaderboard3">
				<?php
					$query = "SELECT * FROM `3` ORDER BY score DESC";
					$result = mysqli_query($conn, $query);

					echo "<table>";
					echo "<tr><th style='font-size:25px;'>#</th><th>Username</th><th>Score</th></tr>";
					$position = 1;
					while ($row = mysqli_fetch_assoc($result)) {
					  echo "<tr><td>" . $position++ . "</td><td>" . $row["username"] . "</td><td>" . $row["score"] . "</td></tr>";
					}
					echo "</table>";
				?>
			</div>
			<div class="leaderboard" id="leaderboard4">
				<?php
					$query = "SELECT * FROM `4` ORDER BY score DESC";
					$result = mysqli_query($conn, $query);

					echo "<table>";
					echo "<tr><th style='font-size:25px;'>#</th><th>Username</th><th>Score</th></tr>";
					$position = 1;
					while ($row = mysqli_fetch_assoc($result)) {
					  echo "<tr><td>" . $position++ . "</td><td>" . $row["username"] . "</td><td>" . $row["score"] . "</td></tr>";
					}
					echo "</table>";
				?>			
			</div>
			<div class="leaderboard" id="leaderboard5">
				<?php
					$query = "SELECT * FROM `5` ORDER BY score DESC";
					$result = mysqli_query($conn, $query);

					echo "<table>";
					echo "<tr><th style='font-size:25px;'>#</th><th>Username</th><th>Score</th></tr>";
					$position = 1;
					while ($row = mysqli_fetch_assoc($result)) {
					  echo "<tr><td>" . $position++ . "</td><td>" . $row["username"] . "</td><td>" . $row["score"] . "</td></tr>";
					}
					echo "</table>";
				?>			
			</div>
			<div class="leaderboard" id="leaderboard6">
				<?php
					$query = "SELECT * FROM `6` ORDER BY score DESC";
					$result = mysqli_query($conn, $query);

					echo "<table>";
					echo "<tr><th style='font-size:25px;'>#</th><th>Username</th><th>Score</th></tr>";
					$position = 1;
					while ($row = mysqli_fetch_assoc($result)) {
					  echo "<tr><td>" . $position++ . "</td><td>" . $row["username"] . "</td><td>" . $row["score"] . "</td></tr>";
					}
					echo "</table>";
				?>			
			</div>
			<div class="leaderboard" id="leaderboard7">
				<?php
					$query = "SELECT * FROM `7` ORDER BY score DESC";
					$result = mysqli_query($conn, $query);

					echo "<table>";
					echo "<tr><th style='font-size:25px;'>#</th><th>Username</th><th>Score</th></tr>";
					$position = 1;
					while ($row = mysqli_fetch_assoc($result)) {
					  echo "<tr><td>" . $position++ . "</td><td>" . $row["username"] . "</td><td>" . $row["score"] . "</td></tr>";
					}
					echo "</table>";
				?>			
			</div>
			<div class="leaderboard" id="leaderboard8">
				<?php
					$query = "SELECT * FROM `8` ORDER BY score DESC";
					$result = mysqli_query($conn, $query);

					echo "<table>";
					echo "<tr><th style='font-size:25px;'>#</th><th>Username</th><th>Score</th></tr>";
					$position = 1;
					while ($row = mysqli_fetch_assoc($result)) {
					  echo "<tr><td>" . $position++ . "</td><td>" . $row["username"] . "</td><td>" . $row["score"] . "</td></tr>";
					}
					echo "</table>";
				?>			
			</div>
			<div class="leaderboard" id="leaderboard9">
				<?php
					$query = "SELECT * FROM `9` ORDER BY score DESC";
					$result = mysqli_query($conn, $query);

					echo "<table>";
					echo "<tr><th style='font-size:25px;'>#</th><th>Username</th><th>Score</th></tr>";
					$position = 1;
					while ($row = mysqli_fetch_assoc($result)) {
					  echo "<tr><td>" . $position++ . "</td><td>" . $row["username"] . "</td><td>" . $row["score"] . "</td></tr>";
					}
					echo "</table>";
				?>			
			</div>
			<div class="leaderboard" id="leaderboard10">
				<?php
					$query = "SELECT * FROM `10` ORDER BY score DESC";
					$result = mysqli_query($conn, $query);

					echo "<table>";
					echo "<tr><th style='font-size:25px;'>#</th><th>Username</th><th>Score</th></tr>";
					$position = 1;
					while ($row = mysqli_fetch_assoc($result)) {
					  echo "<tr><td>" . $position++ . "</td><td>" . $row["username"] . "</td><td>" . $row["score"] . "</td></tr>";
					}
					echo "</table>";
				?>			
			</div>
			<?php
				if($numLevels > 0){
					if($extraLevels > 0){
						for ($x = 0; $x < $extraLevels; $x++) {
							$extraTable = 11 + $x;
							echo "<div class='leaderboard' id='leaderboard". $extraTable . "'>";
							$query = "SELECT * FROM `" . $extraTable . "` ORDER BY score DESC";
							$result = mysqli_query($conn, $query);

							echo "<table>";
							echo "<tr><th style='font-size:25px;'>#</th><th>Username</th><th>Score</th></tr>";
							$position = 1;
							while ($row = mysqli_fetch_assoc($result)) {
							  echo "<tr><td>" . $position++ . "</td><td>" . $row["username"] . "</td><td>" . $row["score"] . "</td></tr>";
							}
							echo "</table>";
							echo "</div>";
						}
					}
				}
			?>
		</div>
	  </div>
</div>
<script>
	$(document).ready(function() {
		  $('.level').click(function() {
			$(this).siblings().removeClass('selected');
			$(this).addClass('selected');
		});
	});
	function showLeaderboard(leaderboardID) {
	  $('.leaderboard').hide();
	  $('#' + leaderboardID).show();
	}
</script>
</body>
</html>
