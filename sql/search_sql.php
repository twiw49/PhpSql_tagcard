<?php
include('db.php');

if (!$conn) {
    die('Could not connect: ' . mysqli_error($conn));
}

$q = $_POST['q'];

function sqlResult($conn, $table, $column, $value, $search)
{
    $value = mysqli_real_escape_string($conn, $value);
    $value = htmlspecialchars($value);
    $sql = "SELECT * FROM `$table` WHERE `$column` = '{$value}'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    return htmlspecialchars_decode($row[$search]);
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
    $card_question = sqlResult($conn, 'card', 'id', $card_id, 'question');
    $card_answer = sqlResult($conn, 'card', 'id', $card_id, 'answer');

    $card_choices = array();
    $sql_ = "SELECT * FROM `card_choice` WHERE `card_id` = '{$card_id}'";
    $result_ = mysqli_query($conn, $sql_);
    while ($row_ = mysqli_fetch_array($result_)) {
        $choice_number = $row_['choice_number'];
        $choice_name = $row_['choice_name'];
        $card_choices[$choice_number] = $choice_name;
    }

    $card_resources = array();
    $sql_ = "SELECT * FROM `card_resource` WHERE `card_id` = '{$card_id}';";
    $result_ = mysqli_query($conn, $sql_);
    while ($row_ = mysqli_fetch_array($result_)) {
        $card_rsc = array();
        $rsc_id = $row_['rsc_id'];
        $rsc_type = $row_['rsc_type'];
        $rsc_name = $row_['rsc_name'];
        if ($rsc_type == 'image') {
            $s_ = "SELECT * FROM `card_resource_image` WHERE `card_id` = '{$card_id}' AND `rsc_id` = '{$rsc_id}'";
            $re_ = mysqli_query($conn, $s_);
            $ro_ = mysqli_fetch_array($re_);
            $rsc_path = $ro_['rsc_path'];

            $card_rsc['id'] = $rsc_id;
            $card_rsc['name'] = $rsc_name;
            $card_rsc['type'] = $rsc_type;
            $card_rsc['path'] = $rsc_path;
        } elseif ($rsc_type == 'table') {
            $s_ = "SELECT * FROM `card_resource_table` WHERE `card_id` = '{$card_id}' AND `rsc_id` = '{$rsc_id}'";
            $re_ = mysqli_query($conn, $s_);
            $rsc_table = array();
            while ($ro_ = mysqli_fetch_array($re_)) {
                array_push($rsc_table, $ro_['rsc_result']);
            }

            $card_rsc['id'] = $rsc_id;
            $card_rsc['name'] = $rsc_name;
            $card_rsc['type'] = $rsc_type;
            $card_rsc['result'] = $rsc_table;
        }
        $card_resources[] = $card_rsc;
    }

    $tags_ele = array();
    $tags_info = array();
    $sql_ = "SELECT * FROM `tag_card` WHERE `card_id` = '{$card_id}'";
    $result_ = mysqli_query($conn, $sql_);
    while ($row_ = mysqli_fetch_array($result_)) {
        $t_id = $row_['tag_id'];
        $t_name = sqlResult($conn, 'tag', 'id', $t_id, 'name');

        array_push($tags_ele, $t_name);
        $t_info = array();
        $t_info['id'] = $t_id;
        $t_info['name'] = $t_name;
        $tags_info[] = $t_info;
    };

    $y = array();
    $y['id'] = $card_id;
    $y['content'] = $card_content;
    $y['question'] = $card_question;
    $y['answer'] = $card_answer;
    $y['choices'] = $card_choices;
    $y['resources'] = $card_resources;
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
