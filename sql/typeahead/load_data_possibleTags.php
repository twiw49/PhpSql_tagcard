<?php
header('Content-Type: application/json');

include("../db.php");

$group = array();

$s = "SELECT * FROM `symptom`;";
$r = mysqli_query($conn, $s);
$arr = array();
while ($row = mysqli_fetch_array($r)) {
    $id = $row['id'];
    $name = $row['name'];
    $s_ = "SELECT * FROM `symptom_synonym` WHERE `symptom_id` = '{$id}';";
    $r_ = mysqli_query($conn, $s_);
    while ($row_ = mysqli_fetch_array($r_)) {
        if ($row['name'] !== $row_['synonym_name']) {
            $name = $name.' / '.$row_['synonym_name'];
        }
    }
    array_push($arr, htmlspecialchars_decode($name));
}
$group['symptom'] = $arr;

$s = "SELECT * FROM `disease`;";
$r = mysqli_query($conn, $s);
$arr = array();
while ($row = mysqli_fetch_array($r)) {
    $id = $row['id'];
    $name = $row['name'];
    $s_ = "SELECT * FROM `disease_synonym` WHERE `disease_id` = '{$id}';";
    $r_ = mysqli_query($conn, $s_);
    while ($row_ = mysqli_fetch_array($r_)) {
        if ($row['name'] !== $row_['synonym_name']) {
            $name = $name.' / '.$row_['synonym_name'];
        }
    }
    array_push($arr, htmlspecialchars_decode($name));
}
$group['disease'] = $arr;

$s = "SELECT * FROM `risk_factor`;";
$r = mysqli_query($conn, $s);
$arr = array();
while ($row = mysqli_fetch_array($r)) {
    array_push($arr, htmlspecialchars_decode($row['name']));
}
$group['risk_factor'] = $arr;

$s = "SELECT * FROM `management`;";
$r = mysqli_query($conn, $s);
$arr = array();
while ($row = mysqli_fetch_array($r)) {
    $id = $row['id'];
    $name = $row['name'];
    $s_ = "SELECT * FROM `management_synonym` WHERE `management_id` = '{$id}';";
    $r_ = mysqli_query($conn, $s_);
    while ($row_ = mysqli_fetch_array($r_)) {
        if ($row['name'] !== $row_['synonym_name']) {
            $name = $name.' / '.$row_['synonym_name'];
        }
    }
    array_push($arr, htmlspecialchars_decode($name));
}
$group['management'] = $arr;

$s = "SELECT * FROM `lab`;";
$r = mysqli_query($conn, $s);
$arr = array();
while ($row = mysqli_fetch_array($r)) {
    array_push($arr, htmlspecialchars_decode($row['name']));
}
$group['lab'] = $arr;

$s = "SELECT * FROM `location`;";
$r = mysqli_query($conn, $s);
$arr = array();
while ($row = mysqli_fetch_array($r)) {
    array_push($arr, htmlspecialchars_decode($row['name']));
}
$group['location'] = $arr;

echo json_encode(array(
    "data" => $group
    )
);