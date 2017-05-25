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
};

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


if ($_POST['type'] == 'tag') {
    $tag_name = $_POST['tag_name'];
    $tag_name = escapeHtml($tag_name);
    $tag_category = $_POST['tag_category'];
    $tag_category = escapeHtml($tag_category);

    $sql = "SELECT * FROM `tag` WHERE `name` LIKE '{$tag_name}' AND `category` LIKE '{$tag_category}';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $tag_id = $row['id'];
    $raw_id = $row['raw_id'];

    $query = array();
    $query['id'] = $tag_id;
    $query['name'] = $tag_name;
    $query['category'] = $tag_category;
    $query['raw_id'] = $raw_id;

    $cards = array();
    $sql = "SELECT * FROM `tag_card` WHERE `tag_id` = '{$tag_id}';";
} elseif ($_POST['type'] == 'card') {
    $query = array();
    $cards = array();

    switch ($_POST['query']) {
      case 'all':
        $query['name'] = 'All Cards';
        $sql = "SELECT * FROM `card`;";
        break;
      default:
        $q = $_POST['query'];
        $query['name'] = $q;
        $sql = "SELECT * FROM `card` WHERE `content` LIKE '%$q%'";
        break;
    }
}
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)) {
    if ($_POST['type'] == 'tag') {
        $card_id = $row['card_id'];
    } elseif ($_POST['type'] == 'card') {
        $card_id = $row['id'];
    }
    $card_arr = sqlReuslt_array($conn, 'card', 'id', $card_id, ['content','question','answer','date','tagged']);
    $card_content = $card_arr['content'];
    $card_question = $card_arr['question'];
    $card_answer = $card_arr['answer'];
    $card_date = $card_arr['date'];
    $card_tagged = $card_arr['tagged'];

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
    $sql_ = "SELECT * FROM `tag_card` WHERE `card_id` = '{$card_id}'";
    $result_ = mysqli_query($conn, $sql_);
    while ($row_ = mysqli_fetch_array($result_)) {
        $t_id = $row_['tag_id'];
        $t_name = sqlResult($conn, 'tag', 'id', $t_id, 'name');
        $t_category = sqlResult($conn, 'tag', 'id', $t_id, 'category');
        $t_raw_id = sqlResult($conn, 'tag', 'id', $t_id, 'raw_id');

        $t_info = [];
        $t_info['id'] = $t_id;
        $t_info['name'] = $t_name;
        $t_info['category'] = $t_category;
        $t_info['raw_id'] = $t_raw_id;
        $tags_info[] = $t_info;
    };

    $y = array();
    $y['id'] = $card_id;
    $y['content'] = $card_content;
    $y['question'] = $card_question;
    $y['answer'] = $card_answer;
    $y['choices'] = $card_choices;
    $y['resources'] = $card_resources;
    $y['tags_info'] = $tags_info;
    $y['date'] = $card_date;
    $y['tagged'] = $card_tagged;
    $cards[] = $y;
};

$r = array();
$r['query'] = $query;
$r['cards'] = $cards;

echo json_encode($r);
