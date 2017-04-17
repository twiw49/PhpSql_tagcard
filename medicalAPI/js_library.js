var symp_child_symp = function() {
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
};

var symp_parent_symp = function() {
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
};

var symp_disease_symp = function() {
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
}

var disease_symp_disease = function() {
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
};

var load_data = function(load_json_url) {
  var url = load_json_url;
  $.getJSON(url, function(data) {
    for (var i = 0; i < data.length; i++) {
      console.log(data[i]);
    }
  })
};

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
// load_send_data("medicalAPI/final/final_disease.json", "sql/database/insertData.php", "disease");
// load_send_data("medicalAPI/final/final_lab_test.json", "sql/database/insertData.php", "lab");
// load_send_data("medicalAPI/final/final_procedure.json", "sql/database/insertData.php", "management");
// load_send_data("medicalAPI/final/final_risk_factor.json", "sql/database/insertData.php", "risk_factor");
// load_send_data("medicalAPI/final/final_symptom.json", "sql/database/insertData.php", "symptom");

var load_apimedic_data = function() {
  var issue_id;
  var loc_id;
  var subloc_id;
  var baseUrl = 'https://healthservice.priaid.ch/';
  var apiUrl = {
    loadSymptoms: baseUrl + 'symptoms',
    loadIssues: baseUrl + 'issues',
    loadIssueInfo: baseUrl + 'issues/' + issue_id + '/info',
    loadDiagnosis: baseUrl + 'diagnosis',
    loadSpecialisations: baseUrl + 'diagnosis/specialisations',
    loadProposedSymptoms: baseUrl + 'symptoms/proposed',
    loadBodyLocations: baseUrl + 'body/locations',
    loadBodySublocations: baseUrl + 'body/locations/' + loc_id,
    loadBodySublocationSymptoms: baseUrl + 'symptoms/' + subloc_id + '/man', // '/woman/boy/girl'
    loadRedFlagText: baseUrl + 'redflag'
  };

  var vars = {};
  vars.token =
    'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImNob2lfZGFlX2t5dUBwcmlhaWQuY2giLCJyb2xlIjoiVXNlciIsImh0dHA6Ly9zY2hlbWFzLnhtbHNvYXAub3JnL3dzLzIwMDUvMDUvaWRlbnRpdHkvY2xhaW1zL3NpZCI6IjIzMCIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvdmVyc2lvbiI6Ijk4IiwiaHR0cDovL2V4YW1wbGUub3JnL2NsYWltcy9saW1pdCI6Ijk5OTk5OTk5OSIsImh0dHA6Ly9leGFtcGxlLm9yZy9jbGFpbXMvbWVtYmVyc2hpcCI6IkJhc2ljIiwiaHR0cDovL2V4YW1wbGUub3JnL2NsYWltcy9sYW5ndWFnZSI6ImVuLWdiIiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9leHBpcmF0aW9uIjoiMjA5OS0xMi0zMSIsImh0dHA6Ly9leGFtcGxlLm9yZy9jbGFpbXMvbWVtYmVyc2hpcHN0YXJ0IjoiMjAwMC0wMS0wMSIsImlzcyI6Imh0dHBzOi8vYXV0aHNlcnZpY2UucHJpYWlkLmNoIiwiYXVkIjoiaHR0cHM6Ly9oZWFsdGhzZXJ2aWNlLnByaWFpZC5jaCIsImV4cCI6MTQ5MTE5MzE5NywibmJmIjoxNDkxMTg1OTk3fQ.Q5rv5IEMBhpkrXfo0h6kfLzFmIYZsDuszcCyoxpoL84';
  vars.language = 'en-gb';
  vars.format = 'json';

  var url = apiUrl.loadSymptoms + '?' + $.param(vars);
  console.log(url);

  $.ajax({
      url: url,
      type: "GET",
      dataType: "json"
      // async: false
    })
    .done(function(data, status, xhr) {
      if (xhr.statusText == 'OK') {
        console.log(data);
      }
    })
}

var load_infermedica_data = function() {
  $.ajax({
      type: 'GET',
      url: "https://api.infermedica.com/v2/conditions",
      headers: {
        "Accept": "application/json",
        "App-Id": "38109532",
        "App-Key": "09c8e28729551740f9994f05b6b29a62",
        "Dev-Mode": "true"
      },
      dataType: 'json'
    })
    .done(function(data, status, xhr) {
      console.log(data);
    })
    .fail(function(data, status) {
      console.log(data);
    })
}
