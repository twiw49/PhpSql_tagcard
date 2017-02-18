<?php
include('db.php');

/* Insert Submit */

if(isset($_POST['insert'])){
  $content = mysqli_real_escape_string($conn, $_POST['content']);
  $tags = $_POST['tag'];

  $sql = "INSERT INTO `card` (`id`, `content`) VALUES (NULL, '{$content}');";
  $result = mysqli_query($conn, $sql);
  $new_card_id = mysqli_insert_id($conn);
}
echo "Success!";

include('refresh.php');
?>