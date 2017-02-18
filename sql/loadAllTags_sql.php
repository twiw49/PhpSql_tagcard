<?php
include("db.php");

$sql = "SELECT * FROM `tag` ORDER BY `card_count` DESC;";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)) {
  echo "<li class='list-group-item' style='display:none;'>".$row['name']."<span class='badge'>".$row['card_count']."</span></li>";
};
?>