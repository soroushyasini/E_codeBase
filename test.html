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
function edit_record(record_id, name_kala, tarikh_arzeh, moshakhasat, hajm_ghabel_arzeh, baste_bandi, tolid_konnande,gheymat, makan_tahvil, arz, tasfiyeh, tarikh_tahvil, min_arzeh, min_kharid, max_afzayesh_arzeh, file, file_name) {
  console.log("Editing record:", { record_id, name_kala, tarikh_arzeh, moshakhasat, hajm_ghabel_arzeh, baste_bandi, tolid_konnande, gheymat, makan_tahvil, arz, tasfiyeh, tarikh_tahvil, min_arzeh, min_kharid, max_afzayesh_arzeh });
  try {
    tarikh_tahvil = (tarikh_tahvil === "0000-00-00 00:00:00") ? "" : tarikh_tahvil;
    $("#record_id").setValue(record_id || '');
    $("#name_kala").setValue(name_kala || '');
    $("#tarikh_arzeh").setValue(tarikh_arzeh || '');
    $("#tarikh_arzeh").setText(tarikh_arzeh || ''); 
    $("#moshakhasat").setValue(moshakhasat || '');
    $("#hajm_ghabel_arzeh").setValue(hajm_ghabel_arzeh || '');
    $("#baste_bandi").setValue(baste_bandi || '');
    $("#tolid_konnande").setValue(tolid_konnande || '');
    $("#gheymat").setValue(gheymat || '');
    $("#makan_tahvil").setValue(makan_tahvil || '');
    $("#arz").setValue(arz || '');
    $("#tasfiyeh").setValue(tasfiyeh || '');
    $("#tarikh_tahvil").setValue(tarikh_tahvil || '');
    $("#min_arzeh").setValue(min_arzeh || '');
    $("#min_kharid").setValue(min_kharid || '');
    $("#max_afzayesh_arzeh").setValue(max_afzayesh_arzeh || '');

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
                "name_kala:", $("#name_kala").getValue(),
                "tarikh_arzeh:", $("#tarikh_arzeh").getText(),
                "moshakhasat:", $("#moshakhasat").getValue(),
                "hajm_ghabel_arzeh:", $("#hajm_ghabel_arzeh").getValue(),
                "baste_bandi:", $("#baste_bandi").getValue(),
                "tolid_konnande:", $("#tolid_konnande").getValue(),
                "gheymat:", $("#gheymat").getValue(),
                "makan_tahvil:", $("#makan_tahvil").getValue(),
                "arz:", $("#arz").getValue(),
                "tasfiyeh:", $("#tasfiyeh").getValue(),
                "tarikh_tahvil:", $("#tarikh_tahvil").getText(),
                "min_arzeh:", $("#min_arzeh").getValue(),
                "min_kharid:", $("#min_kharid").getValue(),
                "max_afzayesh_arzeh:", $("#max_afzayesh_arzeh").getValue()
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
 // if (baste_bandi === "اموالی" && (!min_arzeh || min_arzeh.trim() === "")) {
 //   showMessage('لطفاً حداقل عرضه را وارد کنید', 'error', 5000, 'خطا');
  //  return;
//  }
 // if (!hajm_ghabel_arzeh || hajm_ghabel_arzeh.trim() === "") {
//    showMessage('لطفاً حجم قابل عرضه را وارد کنید', 'error', 5000, 'خطا');
//    return;
//  }
//  if (!makan_tahvil || makan_tahvil.trim() === "") {
//    showMessage('لطفاً مکان تحویل را وارد کنید', 'error', 5000, 'خطا');
//    return;
//  }
//  if (!baste_bandi || baste_bandi.trim() === "") {
//    showMessage('لطفاً بسته‌بندی را وارد کنید', 'error', 5000, 'خطا');
//    return;
//  }    
//  if (!min_kharid || min_kharid.trim() === "") {
//    showMessage('لطفاً حداقل خرید را وارد کنید', 'error', 5000, 'خطا');
//    return;
//  }    

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
    record_id: record_id,
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
    $("#name_kala").setValue('');
    $("#tarikh_arzeh").setText('');
    $("#tarikh_arzeh").setValue('');
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
  }).fail(function(xhr, status, error) {
    console.log("Update Error:", xhr.responseText, status, error);
    showMessage('Error: ' + xhr.responseText, 'error', 5000, 'خطا');
    $('#n2_ajax_loading').fadeOut();
  });
});