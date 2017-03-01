<?php
include("db.php");

if ($_POST['q'] == 'tag_delete') {
	$tag_name = $_POST['tag_name'];
	$card_id = $_POST['card_id'];

	$s_id = "SELECT * FROM `tag` WHERE `name` LIKE '{$tag_name}'";
	$r_id = mysqli_query($conn, $s_id);
	$row = mysqli_fetch_array($r_id);
	$tag_id = $row['id'];

	$s_del_c = "DELETE FROM `rel_tagcard` WHERE `rel_tagcard`.`tag_id` = '{$tag_id}' AND `rel_tagcard`.`card_id` = '{$card_id}';";
	$r_del_c = mysqli_query($conn, $s_del_c);

} else if ($_POST['q'] == 'card_delete') {
	$card_id = $_POST['card_id'];

	$s_del_c = "DELETE FROM `card` WHERE `card`.`id` = '{$card_id}';";
	$r_del_c = mysqli_query($conn, $s_del_c);

	$s_del_t = "DELETE FROM `rel_tagcard` WHERE `rel_tagcard`.`card_id` = '{$card_id}';";
	$r_del_t = mysqli_query($conn, $s_del_t);

} else if ($_POST['q'] == 'tag_add') {
	$tag_name = $_POST['tag_name'];
	$tag_name = htmlspecialchars($tag_name);
	$card_id = $_POST['card_id'];

  $sql = "INSERT INTO `tag` (`id`, `name`) VALUES (NULL, '{$tag_name}');";
  if (!mysqli_query($conn, $sql)) {
    $find_id = "SELECT * FROM `tag` WHERE `name` = '{$tag_name}';";
    $result = mysqli_query($conn, $find_id);
    $row = mysqli_fetch_array($result);
    $new_tag_id = $row['id'];
  } else {
    $new_tag_id = mysqli_insert_id($conn);
  }

  $sql = "INSERT INTO `rel_tagcard` (`card_id`, `tag_id`) VALUES ('{$card_id}', '{$new_tag_id}');";
  $result = mysqli_query($conn, $sql);

} else if ($_POST['q'] == 'card_edit') {
	$card_id = $_POST['card_id'];
	$card_content = $_POST['card_content'];
	$card_content = htmlspecialchars($card_content);

	$s_edit = "UPDATE `card` SET `content` = '{$card_content}' WHERE `card`.`id` = '{$card_id}';";
	$r_edit = mysqli_query($conn, $s_edit);
}
	include('refresh.php');
?>