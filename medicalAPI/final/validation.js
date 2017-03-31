var url = "medicalAPI/final/final_symptom.json";
$.getJSON(url, function(data) {
  for (var i = 0; i < data.length; i++) {
    if (data[i]['children_symp'].length > 0) {
      for (var j = 0; j < data[i]['children_symp'].length; j++) {
        // console.log(data[i]['children_symp'][j]['name']);
        // console.log('id : ' + data[i]['children_symp'][j]['id']);
        for (var k = 0; k < data.length; k++) {
          if (data[k].name == data[i]['children_symp'][j]['name']) {
            if (data[k].id == data[i]['children_symp'][j]['id']) {} else {
              console.log(data[k].id);
              console.log(data[k].name);
              console.log(data[i]['children_symp'][j]['id']);
              data[i]['children_symp'][j]['id'] = data[k].id;
            }
          }
        }
      }
    }
    console.log('success');
  }
  // console.log(JSON.stringify(data));
});

var url = "medicalAPI/final/final_symptom.json";
$.getJSON(url, function(data) {
  for (var i = 0; i < data.length; i++) {
    if (data[i]['parent_symp']['id'] !== null) {
      // console.log(data[i]['parent_symp']);
      for (var k = 0; k < data.length; k++) {
        if (data[k].name == data[i]['parent_symp']['name']) {
          if (data[k].id == data[i]['parent_symp']['id']) {} else {
            console.log(data[k].id);
            console.log(data[k].name);
            console.log(data[i]['parent_symp']['id']);
            data[i]['parent_symp']['id'] = data[k].id;
          }
        }
      }
    }
    console.log('success');
  }
  // console.log(JSON.stringify(data));
});

var url = "medicalAPI/final/final_disease.json";
$.getJSON(url, function(data) {
  for (var i = 0; i < data.length; i++) {
    if (data[i]['symptom'].length > 0) {
      for (var j = 0; j < data[i]['symptom'].length; j++) {
        var url_ = "medicalAPI/final/final_symptom.json";
        $.ajax({
          url: url_,
          async: false,
          type: "GET",
          success: function(data_) {
            for (var k = 0; k < data_.length; k++) {
              if (data_[k]['name'] == data[i]['symptom'][j]['name']) {
                if (data_[k]['id'] !== data[i]['symptom'][j]['id']) {
                  console.log(data_[k]['id']);
                  console.log(data_[k]['name']);
                  console.log(data[i]['symptom'][j]['id']);
                  data[i]['symptom'][j]['id'] = data_[k]['id'];
                }
              }
            }
            console.log('success');
          }
        })
      }
    }
  }
  // console.log(JSON.stringify(data));
});

var url = "medicalAPI/final/final_symptom.json";
$.getJSON(url, function(data) {
  for (var i = 0; i < data.length; i++) {
    if (data[i]['disease'].length > 0) {
      for (var j = 0; j < data[i]['disease'].length; j++) {
        var url_ = "medicalAPI/final/final_disease.json";
        $.ajax({
          url: url_,
          async: false,
          type: "GET",
          success: function(data_) {
            for (var k = 0; k < data_.length; k++) {
              if (data_[k]['name'] == data[i]['disease'][j]['name']) {
                if (data_[k]['id'] !== data[i]['disease'][j]['id']) {
                  console.log(data_[k]['id']);
                  console.log(data_[k]['name']);
                  console.log(data[i]['disease'][j]['id']);
                  data[i]['disease'][j]['id'] = data_[k]['id'];
                }
              }
            }
            console.log('success');
          }
        })
      }
    }
  }
  // console.log(JSON.stringify(data));
});
