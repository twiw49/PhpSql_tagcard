<?php
include('db.php');

if (!$conn) {
  die('Could not connect: ' . mysqli_error($conn));
}

$q = mysqli_real_escape_string($conn, $_GET['q']);

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
$result1 = mysqli_query($conn, $sql);
while($row1 = mysqli_fetch_array($result1)) {
    $card_id = $row1['card_id'];
    $card_content = sqlResult($conn, 'card', 'id', $card_id, 'content');

    $tags = array();
    $sql = "SELECT * FROM `rel_tagcard` WHERE `card_id` = '{$card_id}'";
    $result3 = mysqli_query($conn, $sql);
    while($row3 = mysqli_fetch_array($result3)) {
        $tags_id = $row3['tag_id'];
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
$sql = "SELECT * FROM `rel_tagtag` WHERE `tag_id` = '{$tag_id}' ORDER BY `count` DESC";
$result = mysqli_query($conn, $sql);

$with_tags = array();
while($row = mysqli_fetch_array($result)) {
    $with_tag_id = $row['with_tag_id'];
    $count = $row['count'];
    $with_tag_name = sqlResult($conn, 'tag', 'id', $with_tag_id, 'name');

    $z = array();
    $z['with_tag_id'] = $with_tag_id;
    $z['with_tag_name'] = $with_tag_name;
    $z['count'] = $count;
    $with_tags[] = $z;
}

$r = array();
$r['tag_id'] = $tag_id;
$r['tag_name'] = $tag_name;
$r['cards'] = $cards;
$r['with_tags'] = $with_tags;

echo json_encode($r);

?>