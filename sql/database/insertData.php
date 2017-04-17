<?php
  include('../db.php');

  if ($_POST['q'] == 'disease') {
      $data = $_POST['data'];
      $data = json_decode($data);
      foreach ($data as $item) {
          $id = $item -> id;
          $id = escapeHtml($id);
          $name = $item -> name;
          $name = escapeHtml($name);
          $synonym_arr = $item -> synonym;
          foreach ($synonym_arr as $syn) {
              $synonym = $syn;
              $synonym = escapeHtml($synonym);

              // disease_synonym
              $sql = "INSERT INTO `disease_synonym` (`disease_id`, `disease_name`, `synonym_name`) VALUES ('{$id}', '{$name}', '{$synonym}');";
              $result = mysqli_query($conn, $sql);
              echo mysqli_error($conn);
          }
          $category = $item -> category;
          $category = escapeHtml($category);
          $filter_sex = $item -> filter_sex;
          $filter_sex = escapeHtml($filter_sex);
          $description = $item -> description;
          $description = escapeHtml($description);
          $descriptionShort = $item -> descriptionShort;
          $descriptionShort = escapeHtml($descriptionShort);
          $medicalCondition = $item -> medicalCondition;
          $medicalCondition = escapeHtml($medicalCondition);
          $treatment = $item -> treatment;
          $treatment = escapeHtml($treatment);
          $symptom_arr = $item -> symptom;
          foreach ($symptom_arr as $symp) {
              $symptom_id = $symp -> id;
              $symptom_id = escapeHtml($symptom_id);
              $symptom_name = $symp -> name;
              $symptom_name = escapeHtml($symptom_name);

              // disease_symptom
              $sql = "INSERT INTO `disease_symptom` (`disease_id`, `disease_name`, `symptom_id`, `symptom_name`) VALUES ('{$id}', '{$name}', '{$symptom_id}', '{$symptom_name}');";
              $result = mysqli_query($conn, $sql);
              echo mysqli_error($conn);
          }

          // disease
          $sql = "INSERT INTO `disease` (`id`, `name`, `category`, `filter_sex`) VALUES ('{$id}', '{$name}', '{$category}', '{$filter_sex}');";
          $result = mysqli_query($conn, $sql);
          echo mysqli_error($conn);

          // disease_info
          $sql = "INSERT INTO `disease_info` (`disease_id`, `description`, `description_short`, `medical_condition`, `treatment`) VALUES ('{$id}', '{$description}', '{$descriptionShort}', '{$medicalCondition}', '{$treatment}');";
          $result = mysqli_query($conn, $sql);
          echo mysqli_error($conn);
      }
  } elseif ($_POST['q'] == 'lab') {
      $data = $_POST['data'];
      $data = json_decode($data);
      foreach ($data as $item) {
          $id = $item -> id;
          $id = escapeHtml($id);
          $name = $item -> name;
          $name = escapeHtml($name);
          $category = $item -> category;
          $category = escapeHtml($category);
          $results = $item -> results;
          foreach ($results as $res) {
              $res_id = $res -> id;
              $res_id = escapeHtml($res_id);
              $res_name = $res -> type;
              $res_name = escapeHtml($res_name);

              // lab_result
              $sql = "INSERT INTO `lab_result` (`lab_id`, `result_id`, `result_name`) VALUES ('{$id}', '{$res_id}', '{$res_name}');";
              $result = mysqli_query($conn, $sql);
              echo mysqli_error($conn);
          }

          // lab
          $sql = "INSERT INTO `lab` (`id`, `name`, `category`) VALUES ('{$id}', '{$name}', '{$category}');";
          $result = mysqli_query($conn, $sql);
          echo mysqli_error($conn);
      }
  } elseif ($_POST['q'] == 'management') {
      $data = $_POST['data'];
      $data = json_decode($data);
      foreach ($data as $item) {
          $id = $item -> id;
          $id = escapeHtml($id);
          $name = $item -> name;
          $name = escapeHtml($name);
          $url = $item -> url;
          $url = escapeHtml($url);

          // management
          $sql = "INSERT INTO `management` (`id`, `name`, `url`) VALUES ('{$id}', '{$name}', '{$url}');";
          $result = mysqli_query($conn, $sql);
          echo mysqli_error($conn);
      }
  } elseif ($_POST['q'] == 'risk_factor') {
      $data = $_POST['data'];
      $data = json_decode($data);
      foreach ($data as $item) {
          $id = $item -> id;
          $id = escapeHtml($id);
          $name = $item -> name;
          $name = escapeHtml($name);
          $filter_sex = $item -> filter_sex;
          $filter_sex = escapeHtml($filter_sex);
          $question = $item -> question;
          $question = escapeHtml($question);

          // risk factor
          $sql = "INSERT INTO `risk_factor` (`id`, `name`, `filter_sex`, `question`) VALUES ('{$id}', '{$name}', '{$filter_sex}', '{$question}');";
          $result = mysqli_query($conn, $sql);
          echo mysqli_error($conn);
      }
  } elseif ($_POST['q'] == 'symptom') {
      $data = $_POST['data'];
      $data = json_decode($data);
      foreach ($data as $item) {
          $id = $item -> id;
          $id = escapeHtml($id);
          $name = $item -> name;
          $name = escapeHtml($name);
          $filter_sex = $item -> filter_sex;
          $filter_sex = escapeHtml($filter_sex);
          $filter_age = $item -> filter_age;
          $filter_age = escapeHtml($filter_age);
          $question = $item -> question;
          $question = escapeHtml($question);

          $image_arr = $item -> image;
          foreach ($image_arr as $image) {
              $source = $image -> source;
              $source = escapeHtml($source);
              $url = $image -> url;
              $url = escapeHtml($url);
          }

          $synonym_arr = $item -> synonym;
          foreach ($synonym_arr as $syn) {
              $synonym = $syn;
              $synonym = escapeHtml($synonym);

              // symptom_synonym
              $sql = "INSERT INTO `symptom_synonym` (`symptom_id`, `symptom_name`, `synonym_name`) VALUES ('{$id}', '{$name}', '{$synonym}');";
              $result = mysqli_query($conn, $sql);
              echo mysqli_error($conn);
          }

          $disease_arr = $item -> disease;
          foreach ($disease_arr as $dis) {
              $disease_id = $dis -> id;
              $disease_id = escapeHtml($disease_id);
              $disease_name = $dis -> name;
              $disease_name = escapeHtml($disease_name);
          }

          $parent = $item -> parent_symp;
          $parent_id = $parent -> id;
          $parent_id = escapeHtml($parent_id);
          $parent_relation = $parent -> relation;
          $parent_relation = escapeHtml($parent_relation);
          $parent_name = $parent -> name;
          $parent_name = escapeHtml($parent_name);

          // symptom_relation
          if ($parent_id) {
              $sql = "INSERT INTO `symptom_relation` (`parent_id`, `parent_name`, `child_id`, `child_name`, `category`) VALUES ('{$parent_id}', '{$parent_name}', '{$id}', '{$name}', '{$parent_relation}');";
              $result = mysqli_query($conn, $sql);
              echo mysqli_error($conn);
          }


          $children = $item -> children_symp;
          foreach ($children as $child) {
              $child_id = $child -> id;
              $child_id = escapeHtml($child_id);
              $child_name = $child -> name;
              $child_name = escapeHtml($child_name);
              $child_relation = $child -> relation;
              $child_relation = escapeHtml($child_relation);

              // symptom_relation
              if ($child_id) {
                  $sql = "INSERT INTO `symptom_relation` (`parent_id`, `parent_name`, `child_id`, `child_name`, `category`) VALUES ('{$id}', '{$name}', '{$child_id}', '{$child_name}', '{$child_relation}');";
                  $result = mysqli_query($conn, $sql);
                  echo mysqli_error($conn);
              }
          }

          $location_arr = $item -> location;
          foreach ($location_arr as $location) {
              $location_name = $location -> name;
              $location_category = $location -> category;

              // symptom_location
              $sql = "INSERT INTO `symptom_location` (`symptom_id`, `symptom_name`, `location_name`, `location_category`) VALUES ('{$id}', '{$name}', '{$location_name}', '{$location_category}');";
              $result = mysqli_query($conn, $sql);
              echo mysqli_error($conn);
          }

          // symptom
          $sql = "INSERT INTO `symptom` (`id`, `name`, `filter_sex`, `filter_age`, `question`) VALUES ('{$id}', '{$name}', '{$filter_sex}', '{$filter_age}', '{$question}');";
          $result = mysqli_query($conn, $sql);
          echo mysqli_error($conn);
      }
  }
