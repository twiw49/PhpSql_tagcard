var rscProcess = function(item, rsc, rsc_) {
  if (item[rsc]) {
    var re1 = /^\[.+?\]/;
    item[rsc_] = {};
    item[rsc_].name = item[rsc].match(re1)[0].replace('[', '').replace(']', '').split(',')[0];
    if (item[rsc].match(re1)[0].replace('[', '').replace(']', '').split(',')[1]) {
      item[rsc_].path = './images/' + item[rsc].match(re1)[0].replace('[', '').replace(']', '').split(',')[1] + '.png';
    }
    if (item[rsc].replace(re1, '').trim()) {
      item[rsc_].result = item[rsc].replace(re1, '').trim().split(', ');
    }
  };
  delete item[rsc];
};

var load_data = function(load_json_url) {
  var url = load_json_url;
  $.getJSON(url, function(data) {
    for (var i = 0; i < data.length; i++) {
      data[i].choices = {};

      for (var j = 0; j < data[i].choice.split(', ').length; j++) {
        var str = data[i].choice.split(', ')[j];
        var num = /[1-5]/
        var num_ = /[1-5]\)\s*/
        var num_only = str.match(num)[0];
        var num_delete = str.replace(num_, '');

        data[i].choices[num_only] = num_delete.trim();
      }
      delete data[i].choice;

      data[i].content = data[i].content.trim();
      data[i].content = data[i].content.replace(/\r?\n|\r/g, " ");
      data[i].question = data[i].question.trim();
      data[i].question = data[i].question.replace(/\r?\n|\r/g, " ");

      for (var k = 1; k <= 4; k++) {
        var rsc = 'rsc' + k;
        var rsc_ = 'rsc_' + k;
        rscProcess(data[i], rsc, rsc_);
      }
    }
    console.log(JSON.stringify(data));
  })
};

// load_data("sql/kmle/jsondata/7601.json");

var load_send_data = function(load_json_url, send_php_url, query) {
  var url = load_json_url; // var url = "medicalAPI/final/final_disease.json"
  $.getJSON(url, function(data) {
    console.log(data.length);
    $.ajax({
        type: "POST",
        url: send_php_url, // url: "sql/infermedicaData.php"
        data: {
          q: query, // q: "disease"
          data: JSON.stringify(data)
        }
      })
      .done(function(data) {
        console.log(data);
      })
      .fail(function(error) {
        console.log(error);
      })
  })
}

load_send_data("sql/kmle/processed/7601.json", "sql/kmle/cardInsert.php", "card");
load_send_data("sql/kmle/processed/7602.json", "sql/kmle/cardInsert.php", "card");
