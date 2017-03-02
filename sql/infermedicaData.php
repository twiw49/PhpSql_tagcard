<?php
include("db.php");
if ($_POST['q'] == 'disease') {
    $data = json_decode($_POST['myData']);

    foreach ($data as $item) {
        $id = $item -> id;
        $name = $item -> name;
        $category = $item -> category[0];
        $prevalence = $item -> prevalence;
        $acuteness = $item -> acuteness;
        $severity = $item -> severity;
        $sex_filter = $item -> sex_filter;

    /* INSERT
        $s_cate = "INSERT INTO `d_category` (`id`,`name`) VALUES(NULL, '{$category}');";
        $r_cate = mysqli_query($conn, $s_cate);

        $s_pre = "INSERT INTO `d_prevalence` (`id`,`name`) VALUES(NULL, '{$prevalence}');";
        $r_pre = mysqli_query($conn, $s_pre);

        $s_sev = "INSERT INTO `d_severity` (`id`,`name`) VALUES(NULL, '{$severity}');";
        $r_sev = mysqli_query($conn, $s_sev);

        $s_acu = "INSERT INTO `d_acuteness` (`id`,`name`) VALUES(NULL, '{$acuteness}');";
        $r_acu = mysqli_query($conn, $s_acu);
    */

        $s_acu = "SELECT * FROM `d_acuteness` WHERE `name` LIKE '{$acuteness}';";
        $r_acu = mysqli_query($conn, $s_acu);
        $row = mysqli_fetch_array($r_acu);
        $acu_id = $row['id'];

        $s_cate = "SELECT * FROM `d_category` WHERE `name` LIKE '{$category}';";
        $r_cate = mysqli_query($conn, $s_cate);
        $row = mysqli_fetch_array($r_cate);
        $cate_id = $row['id'];

        $s_pre = "SELECT * FROM `d_prevalence` WHERE `name` LIKE '{$prevalence}';";
        $r_pre = mysqli_query($conn, $s_pre);
        $row = mysqli_fetch_array($r_pre);
        $pre_id = $row['id'];

        $s_sev = "SELECT * FROM `d_severity` WHERE `name` LIKE '{$severity}';";
        $r_sev = mysqli_query($conn, $s_sev);
        $row = mysqli_fetch_array($r_sev);
        $sev_id = $row['id'];

        $s_sex = "SELECT * FROM `sex_filter` WHERE `name` LIKE '{$sex_filter}';";
        $r_sex = mysqli_query($conn, $s_sex);
        $row = mysqli_fetch_array($r_sex);
        $sex_id = $row['id'];

        $s_dis = "INSERT INTO `disease` (`id`, `name`, `category`, `prevalence`, `acuteness`, `severity`, `sex_filter`) VALUES ('{$id}', '{$name}', '{$cate_id}', '{$pre_id}', '{$acu_id}', '{$sev_id}', '{$sex_id}');";
        $r_dis = mysqli_query($conn, $s_dis);
    }
} elseif ($_POST['q'] == "lab") {
    $data = json_decode($_POST['myData']);

    foreach ($data as $item) {
        $id = $item -> id;
        $name = $item -> name;
        $category = $item -> category;
        $result = $item -> results;

        /*
        $s_cate = "INSERT INTO `category_lab` (`id`,`name`) VALUES(NULL, '{$category}');";
        $r_cate = mysqli_query($conn, $s_cate);
        */

        $s_cate = "SELECT * FROM `lab_category` WHERE `name` LIKE '{$category}';";
        $r_cate = mysqli_query($conn, $s_cate);
        $row = mysqli_fetch_array($r_cate);
        $cate_id = $row['id'];

        $s_lab = "INSERT INTO `lab` (`id`, `name`, `category`) VALUES ('{$id}', '{$name}', '{$cate_id}');";
        $r_lab = mysqli_query($conn, $s_lab);
    }
} elseif ($_POST['q'] == "symptoms") {
    $data = json_decode($_POST['myData']);

    foreach ($data as $item) {
        $id = $item -> id;
        $name = $item -> name;
        $image_source = $item -> image_source;
        $image_url = $item -> image_url;

        $children = $item -> children;
        if ($children) {
            foreach ($children as $child) {
                $child_id = $child -> id;
                $child_relation = $child -> parent_relation;

                /*
                $s = "INSERT INTO `s_rel_type` (`id`, `name`) VALUES (null, '{$parent_relation}');";
                $r = mysqli_query($conn, $s);
                */

                $s_child_rel_type = "SELECT * FROM `s_rel_type` WHERE `name` LIKE '{$child_relation}';";
                $r_child_rel_type = mysqli_query($conn, $s_child_rel_type);
                $row = mysqli_fetch_array($r_child_rel_type);
                $child_rel_type_id = $row['id'];

                $s_child = "INSERT INTO `s_rel` (`parent_id`, `child_id`, `relation`) VALUES ('{$id}', '{$child_id}', '{$child_rel_type_id}');";
                $r_child = mysqli_query($conn, $s_child);
            }
            $hasChild = 1;
        } else {
            $hasChild = 0;
        }

        $parent_id = $item -> parent_id;
        if ($parent_id) {
            $parent_relation = $item -> parent_relation;
            $s_parent_rel_type = "SELECT * FROM `s_rel_type` WHERE `name` LIKE '{$parent_relation}';";
            $r_parent_rel_type = mysqli_query($conn, $s_parent_rel_type);
            $row = mysqli_fetch_array($r_parent_rel_type);
            $parent_rel_type_id = $row['id'];

            $s_parent = "INSERT INTO `s_rel` (`parent_id`, `child_id`, `relation`) VALUES ('{$parent_id}', '{$id}', '{$parent_rel_type_id}');";
            $r_parent = mysqli_query($conn, $s_parent);
            $hasParent = 1;
        } else {
            $hasParent = 0;
        }

        $sex_filter = $item -> sex_filter;
        $s_sex = "SELECT * FROM `sex_filter` WHERE `name` LIKE '{$sex_filter}';";
        $r_sex = mysqli_query($conn, $s_sex);
        $row = mysqli_fetch_array($r_sex);
        $sex_id = $row['id'];

        $s_total = "INSERT INTO `symptom` (`id`, `name`, `image_source`, `image_url`, `hasChild`, `hasParent`, `sex_filter`) VALUES ('{$id}', '{$name}', '{$image_source}', '{$image_url}', '{$hasChild}', '{$hasParent}', '{$sex_id}');";
        $r_total = mysqli_query($conn, $s_total);
    }
}
