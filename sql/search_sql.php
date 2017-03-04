<?php
include('db.php');

if (!$conn) {
    die('Could not connect: ' . mysqli_error($conn));
}

$q = htmlspecialchars($_POST['q']);

function sqlResult($conn, $table, $column, $value, $search)
{
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
$sql = "SELECT * FROM `tag_card` WHERE `tag_id` = '{$tag_id}'";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)) {
    $card_id = $row['card_id'];
    $card_content = sqlResult($conn, 'card', 'id', $card_id, 'content');

    $tags_ele = array();
    $tags_info = array();
    $sql_ = "SELECT * FROM `tag_card` WHERE `card_id` = '{$card_id}'";
    $result_ = mysqli_query($conn, $sql_);
    while ($row_ = mysqli_fetch_array($result_)) {
        $t_id = $row_['tag_id'];
        $t_name = sqlResult($conn, 'tag', 'id', $t_id, 'name');

        array_push($tags_ele, $t_name);
        $t_info = array();
        $t_info['name'] = $t_name;
        $t_info['id'] = $t_name;
        $tags_info[] = $t_info;
    };

    $y = array();
    $y['id'] = $card_id;
    $y['content'] = $card_content;
    $y['tags'] = $tags_ele;
    $y['tags_info'] = $tags_info;
    $cards[] = $y;
};

/* with_tags */
$sql_0 = "SELECT * FROM `tag_tag` WHERE `tag_id` = '{$tag_id}' ORDER BY `with_count` DESC";
$result_0 = mysqli_query($conn, $sql_0);

$with_tags = array();
while ($row_0 = mysqli_fetch_array($result_0)) {
    $with_tag_id = $row_0['with_tag_id'];
    $with_tag_name = sqlResult($conn, 'tag', 'id', $with_tag_id, 'name');
    $with_count = $row_0['with_count'];

    $with_with_tags = array();
    $sql_1 = "SELECT * FROM `tag_tag` WHERE `tag_id` = '{$with_tag_id}';";
    $result_1 = mysqli_query($conn, $sql_1);
    while ($row_1 = mysqli_fetch_array($result_1)) {
        $w = sqlResult($conn, 'tag', 'id', $row_1['with_tag_id'], 'name');
        array_push($with_with_tags, $w);
    }

    $z = array();
    $z['id'] = $with_tag_id;
    $z['name'] = $with_tag_name;
    $z['q_with_count'] = $with_count;
    $z['with_with_tags'] = $with_with_tags;
    $with_tags[] = $z;
}

$r = array();
$r['tag_id'] = $tag_id;
$r['tag_name'] = $tag_name;
$r['cards'] = $cards;
$r['with_tags'] = $with_tags;

echo json_encode($r);
