<?php
  $TITLE = "Search";
  include('include/header.php');
?>

<div class="container">
  <div class="row">
    <div class="col-xs-12">
      <div class="form-group">
        <input type="text" class="form-control" name="search" id="search" placeholder="Write your Keyword" onkeyup="typeInput(event)">
        <ul class="list-group" id="alltags">
        </ul>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12" id="searchResult">
    </div>
  </div>
</div>

<script>
$('#search').focus();

$(document).ready(function () {
  $.ajax({
    url: "https://api.infermedica.com/v2/info",
    type: "POST",
    data: $(this).serialize(),
    dataType: "json",
    success: function(data) {
      // 삽입 후 데이터베이스 정보를 새로 불러와 화면에 표시
      myOutput();
    }
  })
};

(function loadAllTags() {
  var xhr = new XMLHttpRequest();
  xhr.onload = function() {
    if(xhr.status === 200) { // 서버의 응답이 정상이면
      document.getElementById('alltags').innerHTML = xhr.responseText;
    }
  };
  xhr.open("GET", "sql/loadAllTags_sql.php", true);
  xhr.send(null);
})();

function filterTags(q) {
  var ul = document.getElementById("alltags");
  var li = ul.getElementsByTagName("li");
  for (var i = 0; i < li.length; i++) {
    if (li[i].firstChild.nodeValue.toUpperCase().indexOf(q) > -1 && q !== '') {
      li[i].style.display = "";
    } else {
      li[i].style.display = "none";
    }
  }
};

function searchDatabase(str) {
  var xhr = new XMLHttpRequest();
  xhr.onload = function() {
    if(xhr.status === 200) { // 서버의 응답이 정상이면
      // Deserializing (String → Object)
      responseObject = JSON.parse(xhr.responseText);
      document.getElementById("search").value = '';
      filterTags();
    }
  };
  xhr.open("GET", "sql/search_sql.php?q="+str, true); /* 3번째 parameter : 비동기화 여부 */
  xhr.send(null);
};

function typeInput(e) {
  var query = document.getElementById("search").value.toUpperCase();
  if (e.keyCode == 13) {
    searchDatabase(query);
  } else {
    filterTags(query);
  }
};

</script>

<?php
  include('include/footer.php');
?>