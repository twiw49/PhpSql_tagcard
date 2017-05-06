<?php
include('../db.php');

function insertRsc($item, $cycle, $card_id)
{
    global $conn;
    for ($x = 1; $x <= $cycle; $x++) {
        $r = 'rsc_'.$x;
        if (isset($item -> $r)) {
            $rsc = $item -> $r;
            $rsc_name = $rsc -> name;
            $rsc_id = $r;
            if (isset($rsc -> path)) {
                $rsc_type = 'image';
                $s = "INSERT INTO `card_resource` (`card_id`, `rsc_id`, `rsc_type`, `rsc_name`) VALUES ('{$card_id}', '{$rsc_id}', '{$rsc_type}', '{$rsc_name}');";
                $res = mysqli_query($conn, $s);
                echo mysqli_error($conn);

                $rsc_path = $rsc -> path;
                $sql = "INSERT INTO `card_resource_image` (`id`, `card_id`, `rsc_id`, `rsc_type`, `rsc_name`, `rsc_path`) VALUES (NULL, '{$card_id}', '{$rsc_id}', '{$rsc_type}', '{$rsc_name}', '{$rsc_path}');";
                $result = mysqli_query($conn, $sql);
                echo mysqli_error($conn);
            } else {
                $rsc_type = 'table';
                $s = "INSERT INTO `card_resource` (`card_id`, `rsc_id`, `rsc_type`, `rsc_name`) VALUES ('{$card_id}', '{$rsc_id}', '{$rsc_type}', '{$rsc_name}');";
                $res = mysqli_query($conn, $s);
                echo mysqli_error($conn);
                $rsc_results = $rsc -> result;
                foreach ($rsc_results as $rsc_result) {
                    $sql = "INSERT INTO `card_resource_table` (`id`, `card_id`, `rsc_id`, `rsc_type`, `rsc_name`, `rsc_result`) VALUES (NULL, '{$card_id}', '{$rsc_id}', '{$rsc_type}', '{$rsc_name}', '{$rsc_result}');";
                    $result = mysqli_query($conn, $sql);
                    echo mysqli_error($conn);
                }
            }
        }
    }
}

function truncate_data($array)
{
    global $conn;
    foreach ($array as $item) {
        $sql = "TRUNCATE `{$item}`;";
        $result = mysqli_query($conn, $sql);
        echo mysqli_error($conn);
    }
}

if ($_POST['q']=="reset") {
    $array = ['card','card_choice','card_resource','card_resource_image','card_resource_table','tag_card','tag_tag'];
    truncate_data($array);
}

if ($_POST['q']=="card") {
    $data = $_POST['data'];
    $data = json_decode($data);
    foreach ($data as $item) {
        $content = $item -> content;
        $content = mysqli_real_escape_string($conn, $content);
        $number = $item -> number;
        $number = mysqli_real_escape_string($conn, $number);
        $question = $item -> question;
        $question = mysqli_real_escape_string($conn, $question);
        $choices = $item -> choices;

        $sql = "INSERT INTO `card` (`id`, `content`, `number`, `question`) VALUES (NULL, '{$content}', '{$number}', '{$question}');";
        $result = mysqli_query($conn, $sql);
        echo mysqli_error($conn);

        $card_id = mysqli_insert_id($conn);

        foreach ($choices as $choice_number => $choice_name) {
            $choice_name = mysqli_real_escape_string($conn, $choice_name);
            $sql = "INSERT INTO `card_choice` (`card_id`, `choice_number`, `choice_name`) VALUES ('{$card_id}', '{$choice_number}', '{$choice_name}');";
            $result = mysqli_query($conn, $sql);
            echo mysqli_error($conn);
        }

        insertRsc($item, 10, $card_id);

        $sql = "INSERT INTO `tag_card` (`card_id`, `tag_id`) VALUES ('{$card_id}', '1');";
        $result = mysqli_query($conn, $sql);
        $sql = "INSERT INTO `tag_card` (`card_id`, `tag_id`) VALUES ('{$card_id}', '2');";
        $result = mysqli_query($conn, $sql);
        include('../refresh.php');
    }
}
