<?php
include("db.php");

if ($_POST['q'] == 'tag_delete') {
	$tag_name = $_POST['tag_name'];
	$card_id = $_POST['card_id'];

	$s_id = "SELECT * FROM `tag` WHERE `name` LIKE '{$tag_name}'";
	$r_id = mysqli_query($conn, $s_id);
	$row = mysqli_fetch_array($r_id);
	$tag_id = $row['id'];
	$tag_count = $row['card_count'];

	if ($tag_count == 0) {
		$s_del_t = "DELETE FROM `tag` WHERE `tag`.`name` = '{$tag_name}';";
		$r_del_t = mysqli_query($conn, $s_del_t);
	}

	$s_del_c = "DELETE FROM `rel_tagcard` WHERE `rel_tagcard`.`tag_id` = '{$tag_id}' AND `rel_tagcard`.`card_id` = '{$card_id}';";
	$r_del_c = mysqli_query($conn, $s_del_c);
}
	echo $tag_name." deleted!!";

	include('refresh.php');
?>