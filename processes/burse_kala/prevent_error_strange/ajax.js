var dyn_uid1 = document.forms[0].id;
var host = PMDynaform.getHostName();
var ws = PMDynaform.getWorkspaceName();
var token = PMDynaform.getAccessToken(); 
var app_uid = PMDynaform.getProjectKeys().caseUID;
var trig_uid = "97129461467f75d4d73b3d3065894189"; // Your trigger UID
hideArrow();
appendAjaxLoading();
$("#record_id").hide();
$("#search_records").hide();
function edit_record( file, file_name) {
  console.log("Editing record:", { record_id});
  try {
    $("#record_id").setValue(record_id || '');



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

    console.log("Web controls set - record_id:", $("#record_id").getValue()

               );
  } catch (e) {
    console.log("Error setting values:", e);
  }
}

$("#create_record").find("button").click(function() {
  var record_id = $("#record_id").getValue();
  var name_kala = $("#name_kala").getValue();
  var tarikh_arzeh = $("#tarikh_arzeh").getText();
  var moshakhasat = $("#moshakhasat").getValue();
  var hajm_ghabel_arzeh = $("#hajm_ghabel_arzeh").getValue();
  var baste_bandi = $("#baste_bandi").getValue();
  var tolid_konnande = $("#tolid_konnande").getValue();
  var gheymat = $("#gheymat").getValue();
  var makan_tahvil = $("#makan_tahvil").getValue();
  var arz = $("#arz").getValue();
  var tasfiyeh = $("#tasfiyeh").getValue();
  var tarikh_tahvil = $("#tarikh_tahvil").getText();
  var min_arzeh = $("#min_arzeh").getValue();
  var min_kharid = $("#min_kharid").getValue();
  var max_afzayesh_arzeh = $("#max_afzayesh_arzeh").getValue();
  var file = '';
  var file_name = '';
  var fileValue = getFieldById('file').model.getData().value;
  if(fileValue[0] !=undefined){
    file = fileValue[0].appDocUid;
    file_name = fileValue[0].name;
  }

  var temp = { 
    record_id: record_id, 
    name_kala: name_kala,
    tarikh_arzeh: tarikh_arzeh, 
    moshakhasat: moshakhasat, 
    hajm_ghabel_arzeh: hajm_ghabel_arzeh, 
    baste_bandi: baste_bandi, 
    tolid_konnande: tolid_konnande, 
    gheymat: gheymat, 
    makan_tahvil: makan_tahvil, 
    arz: arz, 
    tasfiyeh: tasfiyeh, 
    tarikh_tahvil: tarikh_tahvil, 
    min_arzeh: min_arzeh, 
    min_kharid: min_kharid, 
    max_afzayesh_arzeh: max_afzayesh_arzeh,
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
    if (msg.message === 'این حداقل عرضه قبلاً ثبت شده است' || (msg.message && msg.message.startsWith('Error:'))) {
      showMessage(msg.message, 'error', 5000, 'خطا');
    } else {
      showMessage(msg.message, 'success', 5000, 'موفقیت');
      $("#record_id").setValue('');
      $("#name_kala").setValue('');
      $("#tarikh_arzeh").setValue('');
      $("#tarikh_arzeh").setText('');
      $("#moshakhasat").setValue('');
      $("#hajm_ghabel_arzeh").setValue('');
      $("#baste_bandi").setValue('');
      $("#tolid_konnande").setValue('');
      $("#gheymat").setValue('');
      $("#makan_tahvil").setValue('');
      $("#arz").setValue('');
      $("#tasfiyeh").setValue('');
      $("#tarikh_tahvil").setValue('');
      $("#tarikh_tahvil").setText('');
      $("#min_arzeh").setValue('');
      $("#min_kharid").setValue('');
      $("#max_afzayesh_arzeh").setValue('');
      $("#file").find("a.fa-trash").click();
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

$("#button_update").find("button").click(function() {
  var record_id = $("#record_id").getValue();
  if (!record_id) {
    showMessage('لطفاً ابتدا یک رکورد را برای به‌روزرسانی انتخاب کنید', 'error', 5000, 'خطا');
    return;
  }
  var file = '';
  var file_name = '';
  var fileValue = getFieldById('file').model.getData().value;
  if(fileValue[0] !=undefined){
    file = fileValue[0].appDocUid;
    file_name = fileValue[0].name;
  }
  var temp = { 
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

    $("#file").find("a.fa-trash").click();
    if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
      Xcrud.reload();
    }
  }).fail(function(xhr, status, error) {
    console.log("Update Error:", xhr.responseText, status, error);
    showMessage('Error: ' + xhr.responseText, 'error', 5000, 'خطا');
    $('#n2_ajax_loading').fadeOut();
  });
});