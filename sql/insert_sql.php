<?php
  include('db.php');

  /* Insert Submit */
  if (isset($_POST['insert'])) {
      $content = htmlspecialchars($_POST['content']);
      $tags = $_POST['tags'];

      $sql = "INSERT INTO `card` (`id`, `content`) VALUES (NULL, '{$content}');";
      $result = mysqli_query($conn, $sql);
      $new_card_id = mysqli_insert_id($conn);

      foreach ($tags as $tag) {
          $tag = htmlspecialchars($tag);
          $sql = "INSERT INTO `tag` (`id`, `name`) VALUES (NULL, '{$tag}');";
          if (!mysqli_query($conn, $sql)) {
              $find_id = "SELECT * FROM `tag` WHERE `name` = '{$tag}';";
              $result = mysqli_query($conn, $find_id);
              $row = mysqli_fetch_array($result);
              $new_tag_id = $row['id'];
          } else {
              $new_tag_id = mysqli_insert_id($conn);
          }

          $sql = "INSERT INTO `rel_tagcard` (`card_id`, `tag_id`) VALUES ('{$new_card_id}', '{$new_tag_id}');";
          $result = mysqli_query($conn, $sql);
      }
  }
  echo "Success!";

  include('refresh.php');
