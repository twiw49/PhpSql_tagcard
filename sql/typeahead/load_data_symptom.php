<?php
header('Content-Type: application/json');

include("../db.php");

$sql = "SELECT * FROM `symptom`;";
$result = mysqli_query($conn, $sql);
$arr = array();
while ($row = mysqli_fetch_array($result)) {
    $r = htmlspecialchars_decode($row['name']);
    array_push($arr, $r);
};

echo json_encode($arr);
