<?php
  $TITLE = "Insert";
  include('include/header.php');
?>

<div class="container">
    <div class="form-group">
      <label for="content">Contetnt:</label>
      <textarea class="form-control" rows="5" id="content"></textarea>
    </div>
    <div class="form-group">
      <label for="add_tag">Tag:</label>
      <input type="text" class="form-control" name="tag" id="add_tag" onkeydown="captureReturnKey(event)">
      <p></p>
      <div id="tag-group"></div>
    </div>
    <input type="submit" class="btn btn-success form-control" id="insert" value="Insert">
    <div id="flash"></div>
    <div id="show"></div>
</div>

<script>
$('#content').focus();

function captureReturnKey(e) {
  if (e.keyCode == 13 && e.srcElement.value !== '') {
    var tagBtn = document.createElement('input');
    tagBtn.setAttribute("type", "button");
    tagBtn.setAttribute("class", "btn btn-primary active selected_tag");
    tagBtn.setAttribute("value", e.srcElement.value);
    tagBtn.style.borderRadius = 0;
    tagBtn.style.outline = "1px solid white";
    tagBtn.readOnly = true;
    document.getElementById('tag-group').appendChild(tagBtn);
    e.srcElement.value = "";
  }
};

$(function() {
  $("#insert").click(function() {
    var content = $("#content").val();
    var tag = $( ".selected_tag" )
    .map(function() {
      return this.value;
    })
    .get();

    var dataString = {
      insert: true,
      content: content,
      tag: tag
    };

    if(content == '' || tag == '') {
      alert('Content or Tag is Empty!');
      $("#content").focus();
    } else {
      $("#flash").show();
      $("#flash").fadeIn(400).html('<span class="load">Loading..</span>');
      $.ajax({
        type: "POST",
        url: "sql/insert_sql.php",
        data: dataString,
        cache: true,
        success: function(html){
          $("#show").text(html);
          $("#flash").hide();
          $('#content').val('').focus();
          $(".selected_tag").remove();
        }
      });
    }
  });
});
</script>

<?php
  include('include/footer.php');
?>