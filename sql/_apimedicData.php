<?php
include("db.php");

if ($_POST['q'] == 'issues') {
    $data = json_decode($_POST['myData']);
    foreach ($data as $item) {
        $id = $item -> ID;
        $name = $item -> Name;

        $sql = "INSERT INTO `_issues` (`id`, `name`) VALUES ('{$id}', '{$name}');";
        $result = mysqli_query($conn, $sql);
    }
} elseif ($_POST['q'] == 'symptoms') {
    $data = json_decode($_POST['myData']);
    foreach ($data as $item) {
        $id = $item -> ID;
        $name = $item -> Name;

        $sql = "INSERT INTO `_symptoms` (`id`, `name`) VALUES ('{$id}', '{$name}');";
        $result = mysqli_query($conn, $sql);
    }
} elseif ($_POST['q'] == 'issues_info') {
    $data = json_decode($_POST['myData']);
    foreach ($data as $item) {
        $id = $item -> ID;
        $possible_symptoms = $item -> PossibleSymptoms;
        if ($possible_symptoms) {
            foreach ($possible_symptoms as $symp) {
                $s = "SELECT * FROM `_symptoms` WHERE `name` LIKE '{$symp}';";
                $r = mysqli_query($conn, $s);
                while ($row = mysqli_fetch_array($r)) {
                    $symp_id = $row['id'];
                    $s_ = "INSERT INTO `_issues_symptoms` (`issue_id`, `symp_id`) VALUES ('{$id}', '{$symp_id}');";
                    $r_ = mysqli_query($conn, $s_);
                }
            }
        }
        $name = $item -> Name;
        // $s = "INSERT INTO `_issues_names` (`id`, `name`) VALUES ('{$id}', '{$name}');";
        // $r = mysqli_query($conn, $s);
        $prof_name = $item -> ProfName;
        // $s = "INSERT INTO `_issues_names` (`id`, `name`) VALUES ('{$id}', '{$prof_name}');";
        // $r = mysqli_query($conn, $s);
        $synonyms = $item -> Synonyms;
        if ($synonyms) {
            foreach ($synonyms as $syn) {
                // $s = "INSERT INTO `_issues_names` (`id`, `name`) VALUES ('{$id}', '{$syn}');";
                // $r = mysqli_query($conn, $s);
            }
        }

        $description = $item -> Description;
        $description_short = $item -> DescriptionShort;
        $medical_condition = $item -> MedicalCondition;
        $treatment_description = $item -> TreatmentDescription;

        // $s_ = "INSERT INTO `_issues_info` (`id`, `description`, `description_short`, `medical_condition`, `treatment`) VALUES ('{$id}', '{$description}', '{$description_short}', '{$medical_condition}', '{$treatment_description}');";
        // $r_ = mysqli_query($conn, $s_);
    }
} elseif ($_POST['q'] == 'symp') {
    $data = json_decode($_POST['myData']);

    foreach ($data as $item) {
        $symp_id = $item -> ID;
        $red_flag = $item -> HasRedFlag;
        // $s = "UPDATE `_symptoms` SET `woman` = '1' WHERE `_symptoms`.`id` = {$symp_id};";
        // $r = mysqli_query($conn, $s);

        if ($red_flag) {
            $s = "UPDATE `_symptoms` SET `red_flag` = '1' WHERE `_symptoms`.`id` = {$symp_id};";
            $r = mysqli_query($conn, $s);
        }

        $loc = $item -> HealthSymptomLocationIDs;
        foreach ($loc as $loc_id) {
            $s = "INSERT INTO `_symptoms_loc` (`symp_id`, `loc_id`) VALUES ('{$symp_id}', '{$loc_id}');";
            $r = mysqli_query($conn, $s);
        }

        $name = $item -> Name;
        if ($name) {
            // $s = "INSERT INTO `_symptoms_names` (`id`, `name`) VALUES ('{$symp_id}', '{$name}');";
            // $r = mysqli_query($conn, $s);
        }

        $prof_name = $item -> ProfName;
        if ($prof_name) {
            // $s = "INSERT INTO `_symptoms_names` (`id`, `name`) VALUES ('{$symp_id}', '{$prof_name}');";
            // $r = mysqli_query($conn, $s);
        }

        $synonyms = $item -> Synonyms;
        if ($synonyms) {
            foreach ($synonyms as $syn) {
                // $s = "INSERT INTO `_symptoms_names` (`id`, `name`) VALUES ('{$symp_id}', '{$syn}');";
                // $r = mysqli_query($conn, $s);
            }
        }
    }

    // $s = "SELECT * FROM `symp_family` ORDER BY `parent_id` ASC;";
    // $r = mysqli_query($conn, $s);
    // while ($row = mysqli_fetch_array($r)) {
    //     $rel_id = $row['relation'];
    //     $p_id = $row['parent_id'];
    //     $c_id = $row['child_id'];
    //
    //     $s_ = "SELECT * FROM `symp_family_type` WHERE `id` = {$rel_id};";
    //     $r_ = mysqli_query($conn, $s_);
    //     $row_ = mysqli_fetch_array($r_);
    //     $type = $row_['name'];
    //
    //     $sql = "UPDATE `symp_family` SET `type` = '{$type}' WHERE `symp_family`.`parent_id` = '{$p_id}' AND `symp_family`.`child_id` = '{$c_id}';";
    //     $result = mysqli_query($conn, $sql);
    // }

    // $s  = "SELECT symptom.name, symp_family.child_id, symp_family.type FROM symp_family INNER JOIN symptom ON symp_family.parent_id=symptom.id";
    //
    // $r = mysqli_query($conn, $s);
    // while ($row = mysqli_fetch_array($r)) {
    //     $child_id = $row["child_id"];
    //     $s_ = "SELECT * FROM `symptom` WHERE `id` LIKE '{$child_id}';";
    //     $r_ = mysqli_query($conn, $s_);
    //     $row_ = mysqli_fetch_array($r_);
    //     $child = $row_['name'];
    //
    //     $parent = $row['name'];
    //     $type = $row['type'];
    //
    //     $sql = "INSERT INTO `symp_family_total` (`parent`, `child`, `type`) VALUES ('{$parent}', '{$child}', '{$type}');";
    //     $result = mysqli_query($conn, $sql);
    // }


    // $s = "SELECT * FROM `_symptoms` ORDER BY `id`;";
    // $r = mysqli_query($conn, $s);
    // while ($row = mysqli_fetch_array($r)) {
    //     $symp_id = $row['id'];
    //
    //     $s_ = "SELECT * FROM `_symptoms_names` WHERE `id` = {$symp_id};";
    //     $r_ = mysqli_query($conn, $s_);
    //     if ($row_ = mysqli_fetch_array($r_)) {
    //         $s__ = "UPDATE `_symptoms` SET `otherNames` = '1' WHERE `_symptoms`.`id` = {$symp_id};";
    //         $r__ = mysqli_query($conn, $s__);
    //     }
    // }
} elseif ($_POST['q'] == 'update') {
    $totalRow = json_decode($_POST['totalRow']);
    $newRow = json_decode($_POST['newRow']);
    $updateRow = json_decode($_POST['updateRow']);

    $t = array();
    $ids = array();
    foreach ($totalRow as $item) {
        $id = $item -> ID;
        $newID = $item -> newID;
        if ($id !== $newID) {
            $ids[] = array($newID,$id);
        }
        $name = $item -> Name;
        $location = $item -> HealthSymptomLocationIDs;
        $profName = $item -> ProfName;
        $synonyms = $item -> Synonyms;

        $synonym = array();
        $synonym[] = $name;

        if ($profName) {
            $synonym[] = $profName;
        }
        if ($synonyms) {
            foreach ($synonyms as $syn) {
                if (!in_array($syn, $synonym)) {
                    $synonym[] = $syn;
                }
            }
        }

        $s_ = "SELECT * FROM `_issues_symptoms` WHERE `symp_id` = '{$id}';";
        $q_ = mysqli_query($conn, $s_);
        $disease = array();
        while ($row = mysqli_fetch_array($q_)) {
            $d = array();
            $dis_id = $row['issue_id'];
            $s__ = "SELECT * FROM `_issues_names` WHERE `id` = '{$dis_id}';";
            $r__ = mysqli_query($conn, $s__);
            $row = mysqli_fetch_array($r__);
            $dis_name = $row['name'];
            $d['id'] = $dis_id;
            $d['name'] = $dis_name;
            $disease[] = $d;
        }

        $lo = array();
        if ($location) {
            foreach ($location as $loc) {
                $s = "SELECT * FROM `location` WHERE `id` = '{$loc}';";
                $r = mysqli_query($conn, $s);
                $row = mysqli_fetch_array($r);
                $loc_name = $row['name'];
                $loc_cate = $row['category'];
                $l = array();
                if ($loc_name !== null && $loc_cate !== null) {
                    $l['name'] = $loc_name;
                    $l['category'] = $loc_cate;
                    $lo[] = $l;
                }
            }
        }

        $s = "SELECT * FROM `_symptoms` WHERE `id` = {$id};";
        $r = mysqli_query($conn, $s);
        $row = mysqli_fetch_array($r);

        $filter_sex = $row['filter_sex'];
        if ($filter_sex == 1) {
            $filter_sex = 'male & female';
        } elseif ($filter_sex == 2) {
            $filter_sex = 'male';
        } elseif ($filter_sex == 3) {
            $filter_sex = 'female';
        }

        $filter_age = $row['filter_age'];
        if ($filter_age == 1) {
            $filter_age = 'young & old';
        } elseif ($filter_age == 2) {
            $filter_age = 'young';
        } elseif ($filter_age == 3) {
            $filter_age = 'old';
        }

        $image = array();

        if ($row['image_url']) {
            $img = array();
            $img['image_url'] = $row['image_url'];
            $img['image_source'] = $row['image_source'];

            $image[] = $img;
        }

        $parent_symp['id'] = null;
        $parent_symp['relation'] = null;
        $children_symp = array();

        $newItem = array();

        $newItem['id'] = $id;
        $newItem['newID'] = $newID;
        $newItem['name'] = $name;
        $newItem['filter_sex'] = $filter_sex;
        $newItem['filter_age'] = $filter_age;
        $newItem['image'] = $image;
        $newItem['location'] = $lo;
        $newItem['synonym'] = $synonym;
        $newItem['parent_symp'] = $parent_symp;
        $newItem['children_symp'] = $children_symp;
        $newItem['disease'] = $disease;

        $t[] = $newItem;
    }
    echo json_encode($t);
    // echo json_encode($ids);
}
