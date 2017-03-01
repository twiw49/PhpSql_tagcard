<?php
$sql = "SELECT * FROM `tag`;";
$result = mysqli_query($conn, $sql);

$tag_card_array = array();
while ($row = mysqli_fetch_array($result)) {
    $tag_id = $row['id'];

    $sql = "SELECT * FROM `rel_tagcard` WHERE `tag_id` = '{$tag_id}'";
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
    while ($row_0 = mysqli_fetch_array($res)) {
        array_push($card_array, $row_0['card_id']);
    };
    $tag_card_array[$tag_id] = $card_array;
};

$tag_tag_array = array();
foreach ($tag_card_array as $tag => $tag_cards) {
    $tag_tag_array[$tag] = array();
    foreach ($tag_cards as $card) {
        $sql = "SELECT * FROM `rel_tagcard` WHERE `card_id` = '{$card}';";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) {
            $with_tag = $row['tag_id'];
            if (array_key_exists($with_tag, $tag_tag_array[$tag])) {
                $tag_tag_array[$tag][$with_tag] += 1;
            } else {
                $tag_tag_array[$tag][$with_tag] = 1;
            }
        };
    }
}

$s_t = "TRUNCATE rel_tagtag;";
$r_t = mysqli_query($conn, $s_t);
foreach ($tag_tag_array as $tag => $with_tags) {
    foreach ($with_tags as $with_tag => $with_count) {
        $sql = "INSERT INTO `rel_tagtag` (`tag_id`, `with_tag_id`, `with_count`)
		VALUES ('{$tag}', '{$with_tag}', '{$with_count}');";
        $result = mysqli_query($conn, $sql);
    }
}
