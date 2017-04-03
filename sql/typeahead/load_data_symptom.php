<?php
header('Content-Type: application/json');

include("../db.php");

$sql = "SELECT * FROM `symptom`;";
$result = mysqli_query($conn, $sql);
$arr = array();
while ($row = mysqli_fetch_array($result)) {
    $r = $row['name'];
    // $r = $r.'<br />'.$row['id'];
    array_push($arr, $r);
};

echo json_encode($arr);
