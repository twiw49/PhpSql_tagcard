<?php
header('Content-Type: application/json');

include("../db.php");

$group = array();
$s = "SELECT * FROM `category_lab`;";
$r = mysqli_query($conn, $s);
while ($row_ = mysqli_fetch_array($r)) {
    $cate_name = htmlspecialchars_decode($row_['name']);

    $sql = "SELECT * FROM `lab` WHERE `category` = '{$cate_name}';";
    $result = mysqli_query($conn, $sql);
    $arr = array();
    while ($row = mysqli_fetch_array($result)) {
        array_push($arr, htmlspecialchars_decode($row['name']));
    };

    $group[$cate_name] = $arr;
}
echo json_encode(array(
    "data" => $group
    )
);
