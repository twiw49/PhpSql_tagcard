<?php
include('db.php');

if (!$conn) {
  die('Could not connect: ' . mysqli_error($conn));
}

$q = htmlspecialchars($_POST['q']);

function sqlResult ($conn, $table, $column, $value, $search) {
  $sql = "SELECT * FROM `$table` WHERE `$column` = '{$value}'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  return $row[$search];
};

/* tag_id & tag_name */
$tag_name = $q;
$tag_id = sqlResult($conn, 'tag', 'name', $tag_name, 'id');

/* cards */
$cards = array();
$sql = "SELECT * FROM `rel_tagcard` WHERE `tag_id` = '{$tag_id}'";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)) {
    $card_id = $row['card_id'];
    $card_content = sqlResult($conn, 'card', 'id', $card_id, 'content');

    $tags = array();
    $sql_ = "SELECT * FROM `rel_tagcard` WHERE `card_id` = '{$card_id}'";
    $result_ = mysqli_query($conn, $sql_);
    while($row_ = mysqli_fetch_array($result_)) {
        $tags_id = $row_['tag_id'];
        $tags_name = sqlResult($conn, 'tag', 'id', $tags_id, 'name');

        $t = array();
        $t['id'] = $tags_id;
        $t['name'] = $tags_name;
        $tags[] = $t;
    };

    $y = array();
    $y['id'] = $card_id;
    $y['content'] = $card_content;
    $y['tags'] = $tags;
    $cards[] = $y;
};

/* with_tags */
$sql_0 = "SELECT * FROM `rel_tagtag` WHERE `tag_id` = '{$tag_id}' ORDER BY `with_count` DESC";
$result_0 = mysqli_query($conn, $sql_0);

$with_tags = array();
while($row_0 = mysqli_fetch_array($result_0)) {
    $with_tag_id = $row_0['with_tag_id'];
    $with_count = $row_0['with_count'];
    $with_tag_name = sqlResult($conn, 'tag', 'id', $with_tag_id, 'name');

    $z = array();
    $z['with_tag_id'] = $with_tag_id;
    $z['with_tag_name'] = $with_tag_name;
    $z['with_count'] = $with_count;
    $with_tags[] = $z;
}

$r = array();
$r['tag_id'] = $tag_id;
$r['tag_name'] = $tag_name;
$r['cards'] = $cards;
$r['with_tags'] = $with_tags;

echo json_encode($r);
?>