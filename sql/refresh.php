<?php
// reset 'card' table - tagged value
$sql = "SELECT * FROM `card`;";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)) {
    $card_id = $row['id'];
    $s_ = "UPDATE `card` SET `tagged` = '0' WHERE `card`.`id` = '{$card_id}';";
    $r_ = mysqli_query($conn, $s_);
}

$sql = "SELECT * FROM `tag`;";
$result = mysqli_query($conn, $sql);

$tag_card_array = array();
while ($row = mysqli_fetch_array($result)) {
    $tag_id = $row['id'];
    $sql = "SELECT * FROM `tag_card` WHERE `tag_id` = '{$tag_id}'";
    $res = mysqli_query($conn, $sql);

    $card_count = mysqli_num_rows($res);
    if ($card_count == 0) {
        $s_del_t = "DELETE FROM `tag` WHERE `tag`.`id` = '{$tag_id}';";
        $r_del_t = mysqli_query($conn, $s_del_t);
    } else {
        $sql_count = "UPDATE `tag` SET `card_count` = '{$card_count}' WHERE `tag`.`id` = '{$tag_id}';";
        $res_count = mysqli_query($conn, $sql_count);
    }

    $card_array = array();
    while ($row_ = mysqli_fetch_array($res)) {
        $card_id = $row_['card_id'];
        array_push($card_array, $card_id);
        $s_ = "UPDATE `card` SET `tagged` = '1' WHERE `card`.`id` = '{$card_id}';";
        $r_ = mysqli_query($conn, $s_);
    };
    $tag_card_array[$tag_id] = $card_array;
};

$s_t = "TRUNCATE tag_tag;";
$r_t = mysqli_query($conn, $s_t);
$tag_tag_array = array();
foreach ($tag_card_array as $tag => $tag_cards) {
    $tag_tag_array[$tag] = array();
    foreach ($tag_cards as $card) {
        $sql = "SELECT * FROM `tag_card` WHERE `card_id` = '{$card}';";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) {
            $with_tag = $row['tag_id'];
            if (!array_key_exists($with_tag, $tag_tag_array[$tag])) {
                $tag_tag_array[$tag][] = $with_tag;
                $s__ = "INSERT INTO `tag_tag` (`tag_id`, `with_tag_id`) VALUES ('{$tag}', '{$with_tag}');";
                $res__ = mysqli_query($conn, $s__);
            }
        };
    }
}
