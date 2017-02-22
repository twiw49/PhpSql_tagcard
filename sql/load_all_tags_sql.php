<?php
header('Content-Type: application/json');

include("db.php");

$sql = "SELECT * FROM `tag` ORDER BY `card_count` DESC;";
$result = mysqli_query($conn, $sql);
$arr = array();
while($row = mysqli_fetch_array($result)) {
  array_push($arr, $row['name']);
};

echo json_encode($arr);
?>