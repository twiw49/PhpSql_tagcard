<?php
include('db.php');

if (!$conn) {
    die('Could not connect: ' . mysqli_error($conn));
}

function escapeHtml($name)
{
    global $conn;
    $name = htmlspecialchars($name);
    $name = mysqli_real_escape_string($conn, $name);
    return $name;
}

function sqlResult($conn, $table, $column, $value, $search)
{
    $value = escapeHtml($value);
    $sql = "SELECT * FROM `$table` WHERE `$column` = '{$value}'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    return htmlspecialchars_decode($row[$search]);
};

function sqlReuslt_array($conn, $table, $column, $value, $arr)
{
    $value = escapeHtml($value);
    $sql = "SELECT * FROM `$table` WHERE `$column` = '{$value}'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $r = array();
    foreach ($arr as $item) {
        $r[$item] = htmlspecialchars_decode($row[$item]);
    }
    return $r;
};


/* tag_id & tag_name */
$q = $_POST['q'];
$q = escapeHtml($q);
$c = $_POST['c'];
$c = escapeHtml($c);
if ($c == "Risk Factor") {
    $c = "risk_factor";
};
$sql = "SELECT * FROM `tag` WHERE `name` LIKE '{$q}' AND `category` LIKE '{$c}';";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$tag_id = $row['id'];
$id_in_category = $row['id_in_category'];

$query = array();
$query['id'] = $tag_id;
$query['name'] = $q;
$query['category'] = $c;
$query['id_in_category'] = $id_in_category;

// $tag_name = $q;
// $tag_category = $c;
// $tag_id_in_category = $row['id_in_category'];

/* cards */
$cards = array();
$with_tags = array();
$sql = "SELECT * FROM `tag_card` WHERE `tag_id` = '{$tag_id}'";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)) {
    $card_id = $row['card_id'];
    $card_arr = sqlReuslt_array($conn, 'card', 'id', $card_id, ['content','question','answer']);
    $card_content = $card_arr['content'];
    $card_question = $card_arr['question'];
    $card_answer = $card_arr['answer'];

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

    $tags_info = array();
    $tags_category = array();
    $tags_id_in_category = array();

    $sql_ = "SELECT * FROM `tag_card` WHERE `card_id` = '{$card_id}'";
    $result_ = mysqli_query($conn, $sql_);
    while ($row_ = mysqli_fetch_array($result_)) {
        $t_id = $row_['tag_id'];
        $t_name = sqlResult($conn, 'tag', 'id', $t_id, 'name');
        $t_category = sqlResult($conn, 'tag', 'id', $t_id, 'category');
        $t_id_in_category = sqlResult($conn, 'tag', 'id', $t_id, 'id_in_category');
        //$t_with_count
        $s_ = "SELECT * FROM `tag_tag` WHERE `tag_id` = '{$t_id}' AND `with_tag_id` = '{$tag_id}';";
        $r_ = mysqli_query($conn, $s_);
        $row__ = mysqli_fetch_array($r_);
        $t_with_count = $row__['with_count'];

        array_push($tags_id_in_category, $t_id_in_category);
        if (!in_array($t_category, $tags_category) && $t_category !== '') {
            array_push($tags_category, $t_category);
        }

        $t_info = array();
        $t_info['id'] = $t_id;
        $t_info['name'] = $t_name;
        $t_info['category'] = $t_category;
        $t_info['id_in_category'] = $t_id_in_category;
        $t_info['with_count'] = $t_with_count;
        $tags_info[] = $t_info;

        if (!in_array($t_info, $with_tags)) {
            array_push($with_tags, $t_info);
        }
    };

    $y = array();
    $y['id'] = $card_id;
    $y['content'] = $card_content;
    $y['question'] = $card_question;
    $y['answer'] = $card_answer;
    $y['choices'] = $card_choices;
    $y['resources'] = $card_resources;
    $y['tags_id_in_category'] = $tags_id_in_category;
    $y['tags_category'] = $tags_category;
    $y['tags_info'] = $tags_info;
    $cards[] = $y;
};

$r = array();
$r['query'] = $query;
$r['cards'] = $cards;
$r['with_tags'] = $with_tags;

echo json_encode($r);
