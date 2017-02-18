<?php
foreach ($tags as $tag) {
	$tag = mysqli_real_escape_string($conn, $tag);
  $sql = "INSERT INTO `tag` (`id`, `name`) VALUES (NULL, '{$tag}');";
  if (!mysqli_query($conn, $sql)) {
    $id_query = "SELECT * FROM `tag` WHERE `name` = '{$tag}';";
    $result = mysqli_query($conn, $id_query);
    $row = mysqli_fetch_array($result);
    $new_tag_id = $row['id'];
  } else {
    $new_tag_id = mysqli_insert_id($conn);
  }

  $sql = "INSERT INTO `rel_tagcard` (`card_id`, `tag_id`) VALUES ('{$new_card_id}', '{$new_tag_id}');";
  $result = mysqli_query($conn, $sql);
}

$sql = "SELECT `id` FROM `tag`";
$result = mysqli_query($conn, $sql);
$tag_array = array();
while($row = mysqli_fetch_array($result)) {
  array_push($tag_array, $row['id']);
};

$tag_card_array = array();
foreach ($tag_array as $tag_item) {
  $sql = "SELECT `card_id` FROM `rel_tagcard` WHERE `tag_id` = '{$tag_item}';";
  $result = mysqli_query($conn, $sql);
  $card_array = array();
  while($row = mysqli_fetch_array($result)) {
    array_push($card_array, $row['card_id']);
  };
  $tag_card_array[$tag_item] = $card_array;
}

$tag_tag_array = array();
foreach ($tag_card_array as $tag_item => $tag_card_item) {
  $tag_tag_array[$tag_item] = array();
  foreach ($tag_card_item as $card_item) {
    $sql = "SELECT `tag_id` FROM `rel_tagcard` WHERE `card_id` = '{$card_item}';";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)) {
        if (array_key_exists($row['tag_id'], $tag_tag_array[$tag_item])) {
          $tag_tag_array[$tag_item][$row['tag_id']] += 1;
        } else {
          $tag_tag_array[$tag_item][$row['tag_id']] = 1;
        }
    };
  }
}

foreach ($tag_tag_array as $tag_item => $with_tags) {
	foreach ($with_tags as $with_tag => $count) {
		$sql = "INSERT INTO `rel_tagtag` (`tag_id`, `with_tag_id`, `count`) VALUES ('{$tag_item}', '{$with_tag}', '{$count}');";
		$result = mysqli_query($conn, $sql);
	}
}
?>