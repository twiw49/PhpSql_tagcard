<?php
include("db.php");
if ($_POST['q'] == 'issues') {
    $data = json_decode($_POST['myData']);
    foreach ($data as $item) {
        $id = $item -> ID;
        $name = $item -> Name;
        echo $id.$name;

        $sql = "INSERT INTO `_issues` (`id`, `name`) VALUES ('{$id}', '{$name}');";
        $result = mysqli_query($conn, $sql);
    }
} elseif ($_POST['q'] == 'symptoms') {
    $data = json_decode($_POST['myData']);
    foreach ($data as $item) {
        $id = $item -> ID;
        $name = $item -> Name;
        echo $id.$name;

        $sql = "INSERT INTO `_symptoms` (`id`, `name`) VALUES ('{$id}', '{$name}');";
        $result = mysqli_query($conn, $sql);
    }
}
