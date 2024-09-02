<?php
$servername = "db";
$username = "ecm1417";
$password = "WebDev2021";
$dbname = "pairs_game";

$conn = new mysqli($servername, $username, $password, $dbname);

$tables = array('1', '2', '3','4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20');
$scores = array();
foreach ($tables as $table) {
  $sql = "SELECT MAX(score) AS highest_score FROM `" . $table . "`";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $scores[$table] = $row['highest_score'];
    }
  } else {
    $scores[$table] = 0;
  }
}

echo json_encode($scores);

$conn->close();
?>