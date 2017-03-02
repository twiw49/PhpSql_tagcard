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
                    $s_ = "INSERT INTO `_issues_symptoms` (`id`, `symp_id`) VALUES ('{$id}', '{$symp_id}');";
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
            // $s = "UPDATE `_symptoms` SET `red_flag` = '1' WHERE `_symptoms`.`id` = {$symp_id};";
            // $r = mysqli_query($conn, $s);
        }
    }
}
