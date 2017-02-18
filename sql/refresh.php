<?php
$sql = "SELECT * FROM `tag`;";
$result = mysqli_query($conn, $sql);

$tag_card_array = array();
while($row = mysqli_fetch_array($result)) {
  $tag_id = $row['id'];

  $sql = "SELECT * FROM `rel_tagcard` WHERE `tag_id` = '{$tag_id}'";
  $res = mysqli_query($conn, $sql);

  $count = mysqli_num_rows($res);
  $sql_count = "UPDATE `tag` SET `card_count` = '{$count}' WHERE `tag`.`id` = '{$tag_id}';";
  $res_count = mysqli_query($conn, $sql_count);

  $card_array = array();
  while($row_0 = mysqli_fetch_array($res)) {
    array_push($card_array, $row_0['card_id']);
  };
  $tag_card_array[$tag_id] = $card_array;
};

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