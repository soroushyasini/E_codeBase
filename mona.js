var dyn_uid1 = document.forms[0].id;
var host = PMDynaform.getHostName();
var ws = PMDynaform.getWorkspaceName();
var token = PMDynaform.getAccessToken(); 
var app_uid = PMDynaform.getProjectKeys().caseUID;
var trig_uid = "77124592967cd594ad6add1031134547"; // Your trigger UID

$('#record_id').hide();

hideArrow();
appendAjaxLoading();


// Custom function to handle Edit button click
function edit_record(id, grouh, onvan_amval, tedad, type, moshakhase, makan, sherkat, sanad, tarikh, pelak_amval, vasziyat_estefade, tahvil_girande,file,file_name) {
  console.log("Editing record:", { id, grouh, onvan_amval, tedad, type, moshakhase, makan, sherkat, sanad, tarikh, pelak_amval, vasziyat_estefade, tahvil_girande });
  try {
    tarikh = (tarikh === "0000-00-00 00:00:00") ? "" : tarikh;
    $("#record_id").setValue(id || '');
    $("#grouh").setValue(grouh || '');
    $("#onvan_amval").setValue(onvan_amval || '');
    $("#tedad").setValue(tedad || '');
    $("#type").setValue(type || '');
    $("#moshakhase").setValue(moshakhase || '');
    $("#makan").setValue(makan || '');
    $("#sherkat").setValue(sherkat || '');
    $("#sanad").setValue(sanad || '');
    $("#tarikh").setValue(tarikh || '');
    $("#pelak_amval").setValue(pelak_amval || '');
    $("#vasziyat_estefade").setValue(vasziyat_estefade || '');
    $("#tahvil_girande").setValue(tahvil_girande || '');

    if(file !== '' && file !=undefined && file_name !=undefined){
      var temp = new Array();
      var temp1 = new Object();
      temp1.appDocUid = file;
      temp1.name = file_name;
      temp1.version = 1;
      temp1.size = 1024;
      temp[0] = temp1;
      getFieldById('file').createFormMultiFile(temp, 0);
    }

    console.log("Web controls set - record_id:", $("#record_id").getValue(), 
                "grouh:", $("#grouh").getValue(), 
                "onvan_amval:", $("#onvan_amval").getValue(),
                "tedad:", $("#tedad").getValue(),
                "type:", $("#type").getValue(),
                "moshakhase:", $("#moshakhase").getValue(),
                "makan:", $("#makan").getValue(),
                "sherkat:", $("#sherkat").getValue(),
                "sanad:", $("#sanad").getValue(),
                "tarikh:", $("#tarikh").getValue(),
                "pelak_amval:", $("#pelak_amval").getValue(),
                "vasziyat_estefade:", $("#vasziyat_estefade").getValue(),
                "tahvil_girande:", $("#tahvil_girande").getValue()
               );
  } catch (e) {
    console.log("Error setting values:", e);
  }
}

// Create new record
$("#create_record").find("button").click(function() {
  var id = $("#record_id").getValue();
  var grouh = $("#grouh").getValue();
  var onvan_amval = $("#onvan_amval").getValue();
  var tedad = $("#tedad").getValue();
  var type = $("#type").getValue();
  var moshakhase = $("#moshakhase").getValue();
  var makan = $("#makan").getValue();
  var sherkat = $("#sherkat").getValue();
  var sanad = $("#sanad").getValue();
  var tarikh = $("#tarikh").getValue();
  var pelak_amval = $("#pelak_amval").getValue();
  var vasziyat_estefade = $("#vasziyat_estefade").getValue();
  var tahvil_girande = $("#tahvil_girande").getValue();
  var file = '';
  var file_name = '';
  var fileValue = getFieldById('file').model.getData().value;
  if(fileValue[0] !=undefined){
    file = fileValue[0].appDocUid; //get file_uid
    file_name = fileValue[0].name; //get file_name
  }


  var temp = { 
    id: id, 
    grouh: grouh, 
    onvan_amval: onvan_amval, 
    tedad: tedad, 
    type: type, 
    moshakhase: moshakhase, 
    makan: makan, 
    sherkat: sherkat, 
    sanad: sanad, 
    tarikh: tarikh, 
    pelak_amval: pelak_amval, 
    vasziyat_estefade: vasziyat_estefade, 
    tahvil_girande: tahvil_girande,
    file: file,
    file_name: file_name
  };

  console.log("Creating new record with data:", temp);
  $('#n2_ajax_loading').fadeIn();

  $.ajax({
    type: 'PUT',
    url: host + '/api/1.0/' + ws + '/cases/' + app_uid + '/execute-trigger/' + trig_uid + '?AJAX=1&REQ_TYPE=save_to_db',
    data: temp,
    beforeSend: function(xhr) { xhr.setRequestHeader('Authorization', 'Bearer ' + token); }
  }).done(function(msg) {
    console.log("Create response:", msg);
    if (msg.message === 'این پلاک اموال قبلاً ثبت شده است' || (msg.message && msg.message.startsWith('Error:'))) {
      showMessage(msg.message, 'error', 5000, 'خطا');
    } else {
      showMessage(msg.message, 'success', 5000, 'موفقیت');
      $("#record_id").setValue('');
      $("#grouh").setValue('');
      $("#onvan_amval").setValue('');
      $("#tedad").setValue('');
      $("#type").setValue('');
      $("#moshakhase").setValue('');
      $("#makan").setValue('');
      $("#sherkat").setValue('');
      $("#sanad").setValue('');
      $("#tarikh").setValue('');
      $("#pelak_amval").setValue('');
      $("#vasziyat_estefade").setValue('');
      $("#tahvil_girande").setValue('');
      $("#file").find("a.fa-trash").click(); //cleal filwe
      if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
        Xcrud.reload();
      }
    }
    $('#n2_ajax_loading').fadeOut();
  })
    .fail(function(xhr, status, error) {
    console.log("Create Error:", xhr.responseText, status, error);
    let errorMsg = 'Unknown error occurred';
    try {
      const response = JSON.parse(xhr.responseText);
      errorMsg = response.message || errorMsg;
    } catch (e) {
      errorMsg = xhr.responseText || errorMsg;
    }
    showMessage(errorMsg, 'error', 5000, 'خطا');
    $('#n2_ajax_loading').fadeOut();
  });
});

// Update existing record
$("#button_update").find("button").click(function() {
  var recordId = $("#record_id").getValue();
  if (!recordId) {
    showMessage('Please select a record to update first', 'error', 5000, 'خطا');
    return;
  }
  var grouh = $("#grouh").getValue();
  var onvan_amval = $("#onvan_amval").getValue();
  var tedad = $("#tedad").getValue();
  var type = $("#type").getValue();
  var moshakhase = $("#moshakhase").getValue();
  var makan = $("#makan").getValue();
  var sherkat = $("#sherkat").getValue();
  var sanad = $("#sanad").getValue();
  var tarikh = $("#tarikh").getValue();
  var pelak_amval = $("#pelak_amval").getValue();
  var vasziyat_estefade = $("#vasziyat_estefade").getValue();
  var tahvil_girande = $("#tahvil_girande").getValue();
  var file = '';
  var file_name = '';
  var fileValue = getFieldById('file').model.getData().value;
  if(fileValue[0] !=undefined){
    file = fileValue[0].appDocUid; //get file_uid
    file_name = fileValue[0].name; //get file_name
  }
  var temp = { 
    grouh: grouh, 
    onvan_amval: onvan_amval, 
    tedad: tedad, 
    type: type, 
    moshakhase: moshakhase, 
    makan: makan, 
    sherkat: sherkat, 
    sanad: sanad, 
    tarikh: tarikh, 
    pelak_amval: pelak_amval, 
    vasziyat_estefade: vasziyat_estefade, 
    tahvil_girande: tahvil_girande, 
    id: recordId ,
    file: file,
    file_name: file_name
  };

  console.log("Updating record with data:", temp);
  $('#n2_ajax_loading').fadeIn();

  $.ajax({
    type: 'PUT',
    url: host + '/api/1.0/' + ws + '/cases/' + app_uid + '/execute-trigger/' + trig_uid + '?AJAX=1&REQ_TYPE=update_record',
    data: temp,
    beforeSend: function(xhr) { xhr.setRequestHeader('Authorization', 'Bearer ' + token); }
  }).done(function(msg) {
    console.log("Update response:", msg);
    showMessage(msg.message, 'success', 5000, 'موفقیت');
    $('#n2_ajax_loading').fadeOut();
    $("#record_id").setValue('');
    $("#grouh").setValue('');
    $("#onvan_amval").setValue('');
    $("#tedad").setValue('');
    $("#type").setValue('');
    $("#moshakhase").setValue('');
    $("#makan").setValue('');
    $("#sherkat").setValue('');
    $("#sanad").setValue('');
    $("#tarikh").setValue('');
    $("#pelak_amval").setValue('');
    $("#vasziyat_estefade").setValue('');
    $("#tahvil_girande").setValue('');
    $("#file").find("a.fa-trash").click(); //clean file
    if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
      Xcrud.reload();
      // setTimeout(updateXcrudTitles, 500); // Fallback: Update titles after reload
    }
  }).fail(function(xhr, status, error) {
    console.log("Update Error:", xhr.responseText, status, error);
    showMessage('Error: ' + xhr.responseText, 'error', 5000, 'خطا');
    $('#n2_ajax_loading').fadeOut();
  });
});
$("#search_records").find("button").click(function() {
  search_table();
});

