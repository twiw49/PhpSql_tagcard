<?php
include("db.php");

/* tag.name, 각 태그 당 태그된 카드의 수 */

$sql = "SELECT * FROM `tag`;";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)) {
  $id = $row['id'];
  $sql = "SELECT * FROM `rel_tagcard` WHERE `tag_id` = '{$id}'";
  $res = mysqli_query($conn, $sql);
  $count = mysqli_num_rows($res);
  echo "<li class='list-group-item' style='display:none;'>".$row['name']."<span class='badge'>".$count."</span></li>";
};
?>