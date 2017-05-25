<?php
include("db.php");

function escapeHtml($name)
{
    global $conn;
    $name = htmlspecialchars($name);
    $name = mysqli_real_escape_string($conn, $name);
    return $name;
}

// tag_delete
if ($_POST['q'] == 'tag_delete') {
    $tag_raw_id = $_POST['tag_raw_id'];
    $card_id = $_POST['card_id'];

    $s_id = "SELECT * FROM `tag` WHERE `raw_id` LIKE '{$tag_raw_id}';";
    $r_id = mysqli_query($conn, $s_id);
    $row = mysqli_fetch_array($r_id);
    $tag_id = $row['id'];

    $s_del_c = "DELETE FROM `tag_card` WHERE `tag_card`.`tag_id` = '{$tag_id}' AND `tag_card`.`card_id` = '{$card_id}';";
    $r_del_c = mysqli_query($conn, $s_del_c);

    include('refresh.php');
    echo json_encode('');
}

// card_delete
elseif ($_POST['q'] == 'card_delete') {
    $card_id = $_POST['card_id'];

    $s_del_c = "DELETE FROM `card` WHERE `card`.`id` = '{$card_id}';";
    $r_del_c = mysqli_query($conn, $s_del_c);

    $s_del_t = "DELETE FROM `tag_card` WHERE `tag_card`.`card_id` = '{$card_id}';";
    $r_del_t = mysqli_query($conn, $s_del_t);

    include('refresh.php');
    echo json_encode('');
}

// tag_add
elseif ($_POST['q'] == 'tag_add') {
    // $q_tag = $_POST['q_tag'];
    $tag_name = $_POST['tag_name'];
    $tag_name = escapeHtml($tag_name);
    $card_id = $_POST['card_id'];
    $tag_category = $_POST['tag_category'];

    $s = "SELECT * FROM `{$tag_category}` WHERE `name` LIKE '{$tag_name}'";
    $r = mysqli_query($conn, $s);
    $row = mysqli_fetch_array($r);
    $tag_raw_id = $row['id'];

    $sql = "INSERT INTO `tag` (`id`, `name`, `category`, `raw_id`) VALUES (NULL, '{$tag_name}', '{$tag_category}', '{$tag_raw_id}');";
    if (!mysqli_query($conn, $sql)) {
        $find_id = "SELECT * FROM `tag` WHERE `raw_id` = '{$tag_raw_id}';";
        $result = mysqli_query($conn, $find_id);
        $row = mysqli_fetch_array($result);
        $new_tag_id = $row['id'];
    } else {
        $new_tag_id = mysqli_insert_id($conn);
    }

    $sql = "INSERT INTO `tag_card` (`card_id`, `tag_id`) VALUES ('{$card_id}', '{$new_tag_id}');";
    $result = mysqli_query($conn, $sql);

    $sql = "UPDATE `card` SET date = now() WHERE id = '{$card_id}';";
    $result = mysqli_query($conn, $sql);

    include('refresh.php');

    $tag_info = array();
    $tag_info['id'] = $new_tag_id;
    $tag_info['name'] = htmlspecialchars_decode($tag_name);
    $tag_info['category'] = $tag_category;
    $tag_info['raw_id'] = $tag_raw_id;
    echo json_encode($tag_info);
}

// card_edit
elseif ($_POST['q'] == 'card_edit') {
    $card_id = $_POST['card_id'];
    $card_content = $_POST['card_content'];
    $card_content = escapeHtml($card_content);

    $s_edit = "UPDATE `card` SET `content` = '{$card_content}' WHERE `card`.`id` = '{$card_id}';";
    $r_edit = mysqli_query($conn, $s_edit);

    include('refresh.php');
    echo json_encode('');
}
