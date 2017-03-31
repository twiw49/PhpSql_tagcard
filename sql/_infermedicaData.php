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
        $filter_sex = $item -> filter_sex;

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

        $s_sex = "SELECT * FROM `filter_sex` WHERE `name` LIKE '{$filter_sex}';";
        $r_sex = mysqli_query($conn, $s_sex);
        $row = mysqli_fetch_array($r_sex);
        $sex_id = $row['id'];

        $s_dis = "INSERT INTO `disease` (`id`, `name`, `category`, `prevalence`, `acuteness`, `severity`, `filter_sex`) VALUES ('{$id}', '{$name}', '{$cate_id}', '{$pre_id}', '{$acu_id}', '{$sev_id}', '{$sex_id}');";
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
                $s = "INSERT INTO `symp_family_type` (`id`, `name`) VALUES (null, '{$parent_relation}');";
                $r = mysqli_query($conn, $s);
                */

                $s_child_rel_type = "SELECT * FROM `symp_family_type` WHERE `name` LIKE '{$child_relation}';";
                $r_child_rel_type = mysqli_query($conn, $s_child_rel_type);
                $row = mysqli_fetch_array($r_child_rel_type);
                $child_rel_type_id = $row['id'];

                $s_child = "INSERT INTO `symp_family` (`parent_id`, `child_id`, `relation`) VALUES ('{$id}', '{$child_id}', '{$child_rel_type_id}');";
                $r_child = mysqli_query($conn, $s_child);
            }
            $hasChild = 1;
        } else {
            $hasChild = 0;
        }

        $parent_id = $item -> parent_id;
        if ($parent_id) {
            $parent_relation = $item -> parent_relation;
            $s_parent_rel_type = "SELECT * FROM `symp_family_type` WHERE `name` LIKE '{$parent_relation}';";
            $r_parent_rel_type = mysqli_query($conn, $s_parent_rel_type);
            $row = mysqli_fetch_array($r_parent_rel_type);
            $parent_rel_type_id = $row['id'];

            $s_parent = "INSERT INTO `symp_family` (`parent_id`, `child_id`, `relation`) VALUES ('{$parent_id}', '{$id}', '{$parent_rel_type_id}');";
            $r_parent = mysqli_query($conn, $s_parent);
            $hasParent = 1;
        } else {
            $hasParent = 0;
        }

        $filter_sex = $item -> filter_sex;
        $s_sex = "SELECT * FROM `filter_sex` WHERE `name` LIKE '{$filter_sex}';";
        $r_sex = mysqli_query($conn, $s_sex);
        $row = mysqli_fetch_array($r_sex);
        $sex_id = $row['id'];

        $s_total = "INSERT INTO `symptom` (`id`, `name`, `image_source`, `image_url`, `hasChild`, `hasParent`, `filter_sex`) VALUES ('{$id}', '{$name}', '{$image_source}', '{$image_url}', '{$hasChild}', '{$hasParent}', '{$sex_id}');";
        $r_total = mysqli_query($conn, $s_total);
    }
} elseif ($_POST['q'] == 'infer') {
    // "id": "s_177",
    // "name": "Cheek swelling",
    // "filter_sex": "male & female",
    // "filter_age": "young & old",
    // "image": [],
    // "location": ["(Category) Head, throat & neck", "Face & eyes"],
    // "synonym": ["Cheek swelling", "Swollen cheek"],
    // "parent & child": [],
    // "disease_id": ["15", "106", "180", "202", "255", "388", "449"],
    // "disease": ["Allergy", "Epidemic parotitis", "Angioedema", "Allergy to bee or wasp venom", "Bruise", "Salivary gland stone", "Dental abscess"]

    $data = json_decode($_POST['data']);
    $r = array();
    foreach ($data as $item) {
        $id = $item -> id;
        $name = $item -> name;
        $filter_sex = $item -> sex_filter;
        if ($filter_sex == 'both') {
            $filter_sex = "male & female";
        }
        $filter_age = null;
        $image = array();
        if ($item -> image_source) {
            $image['image_source'] = $item -> image_source;
            $image['image_url'] = $item -> image_url;
        }
        $location = array();
        $synonym = array();
        $synonym[] = $name;
        $parent_symp = array();
        $parent_symp['id'] = $item -> parent_id;
        $parent_symp['relation'] = $item -> parent_relation;
        $children_symp = array();
        $children = $item -> children;
        foreach ($children as $child) {
            $c = array();
            $c['id'] = $child -> id;
            $c['relation'] = $child -> parent_relation;
            $children_symp[] = $c;
        }

        $i = array();
        $i['id'] = $id;
        $i['name'] = $name;
        $i['filter_sex'] = $filter_sex;
        $i['filter_age'] = $filter_age;
        $i['image'] = $image;
        $i['location'] = $location;
        $i['synonym'] = $synonym;
        $i['parent_symp'] = $parent_symp;
        $i['children_symp'] = $children_symp;
        $i['disease'] = array();

        $r[] = $i;
    }
    echo json_encode($r);
} elseif ($_POST['q'] == 'final_dis') {
    $data = json_decode($_POST['data']);
    foreach ($data as $item) {
        $synonym = $item -> synonym;
        foreach ($synonym as $name) {
            $s_ = "SELECT * FROM `disease` WHERE `name` LIKE '{$name}';";
            $r_ = mysqli_query($conn, $s_);
            var_dump($r_ -> num_rows !== 0);
            if ($r_ -> num_rows !== 0) {
                $row = mysqli_fetch_array($r_);
                $infer_name = $row['name'];
                echo($infer_name);

                $infer_id = $row['id'];
                $infer_cate_id = $row['category'];
                $s_cate = "SELECT * FROM `d_category` WHERE `id` LIKE '{$infer_cate_id}';";
                $r_cate = mysqli_query($conn, $s_cate);
                $row_ = mysqli_fetch_array($r_cate);
                $infer_category_name = $row_['name'];

                $sex_filter_id = $row['sex_filter'];
                $s_sex = "SELECT * FROM `filter_sex` WHERE `id` LIKE '{$sex_filter_id}';";
                $r_sex = mysqli_query($conn, $s_sex);
                $row__ = mysqli_fetch_array($r_sex);
                $sex_filter_name = $row__['name'];

                $item -> assocID = $infer_id;
                $item -> categories = $infer_category_name;
                $item -> sex_filter = $sex_filter_name;
            }
        }
    }
    echo json_encode($data);
} elseif ($_POST['q'] == 'symp') {
    $data = json_decode($_POST['data']);
    foreach ($data as $item) {
        $parent_symp = $item -> parent_symp;
        if ($parent_id = $parent_symp -> id) {
            $s_ = "SELECT * FROM `symptom` WHERE `id` LIKE '{$parent_id}';";
            $r_ = mysqli_query($conn, $s_);
            $row = mysqli_fetch_array($r_);
            $parent_name = $row['name'];
            $parent_symp -> name = $parent_name;
        } else {
            $parent_symp -> name = null;
        }
        $children_symp = $item -> children_symp;
        $name = $item -> name;
        if (count($children_symp) > 0) {
            foreach ($children_symp as $child) {
                $child_id = $child -> id;
                $s_ = "SELECT * FROM `symptom` WHERE `id` LIKE '{$child_id}';";
                $r_ = mysqli_query($conn, $s_);
                $row = mysqli_fetch_array($r_);
                $child_name = $row['name'];
                $child -> name = $child_name;
            }
        } else {
            $children_symp = array();
        }
    }
    echo json_encode($data);
}
