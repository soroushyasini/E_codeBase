var dyn_uid1 = document.forms[0].id;
var host = PMDynaform.getHostName();
var ws = PMDynaform.getWorkspaceName();
var token = PMDynaform.getAccessToken(); 
var app_uid = PMDynaform.getProjectKeys().caseUID;
var trig_uid = "703540661683c173bc4b082093387355"; // Your trigger UID
hideArrow();
appendAjaxLoading();
$("#record_id").hide();
$("#search_records").hide();

function edit_record(id, cellphone, contact_person, email, name, number, semat, tozihat, vahed_zirabt, file, file_name) {
  console.log("Editing record:", { id, cellphone, contact_person, email, name, number, semat, tozihat, vahed_zirabt });
  try {
    $("#record_id").setValue(id || '');
    $("#cellphone").setValue(cellphone || '');
    $("#contact_person").setValue(contact_person || '');
    $("#email").setValue(email || '');
    $("#name").setValue(name || '');
    $("#number").setValue(number || '');
    $("#semat").setValue(semat || '');
    $("#tozihat").setValue(tozihat || '');
    $("#vahed_zirabt").setValue(vahed_zirabt || '');

    if(file !== '' && file != undefined && file_name != undefined){
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
                "cellphone:", $("#cellphone").getValue(),
                "contact_person:", $("#contact_person").getValue(),
                "email:", $("#email").getValue(),
                "name:", $("#name").getValue(),
                "number:", $("#number").getValue(),
                "semat:", $("#semat").getValue(),
                "tozihat:", $("#tozihat").getValue(),
                "vahed_zirabt:", $("#vahed_zirabt").getValue()
               );
  } catch (e) {
    console.log("Error setting values:", e);
  }
}

$("#create_record").find("button").click(function() {
  var record_id = $("#record_id").getValue();
  var cellphone = $("#cellphone").getValue();
  var contact_person = $("#contact_person").getValue();
  var email = $("#email").getValue();
  var name = $("#name").getValue();
  var number = $("#number").getValue();
  var semat = $("#semat").getValue();
  var tozihat = $("#tozihat").getValue();
  var vahed_zirabt = $("#vahed_zirabt").getValue();
  var file = '';
  var file_name = '';
  var fileField = getFieldById('file');
  if (fileField && fileField.model && fileField.model.getData()) {
    var fileValue = fileField.model.getData().value;
    if (fileValue && fileValue[0] != undefined) {
      file = fileValue[0].appDocUid;
      file_name = fileValue[0].name;
    }
  }

  var temp = { 
    record_id: record_id, 
    cellphone: cellphone,
    contact_person: contact_person, 
    email: email, 
    name: name, 
    number: number, 
    semat: semat, 
    tozihat: tozihat, 
    vahed_zirabt: vahed_zirabt,
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
      $("#cellphone").setValue('');
      $("#contact_person").setValue('');
      $("#email").setValue('');
      $("#name").setValue('');
      $("#number").setValue('');
      $("#semat").setValue('');
      $("#tozihat").setValue('');
      $("#vahed_zirabt").setValue('');
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
  var cellphone = $("#cellphone").getValue();
  var contact_person = $("#contact_person").getValue();
  var email = $("#email").getValue();
  var name = $("#name").getValue();
  var number = $("#number").getValue();
  var semat = $("#semat").getValue();
  var tozihat = $("#tozihat").getValue();
  var vahed_zirabt = $("#vahed_zirabt").getValue();
  var file = '';
  var file_name = '';
  var fileField = getFieldById('file');
  if (fileField && fileField.model && fileField.model.getData()) {
    var fileValue = fileField.model.getData().value;
    if (fileValue && fileValue[0] != undefined) {
      file = fileValue[0].appDocUid;
      file_name = fileValue[0].name;
    }
  }
  var temp = { 
    cellphone: cellphone, 
    contact_person: contact_person, 
    email: email, 
    name: name, 
    number: number, 
    semat: semat, 
    tozihat: tozihat, 
    vahed_zirabt: vahed_zirabt, 
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
    $("#cellphone").setValue('');
    $("#contact_person").setValue('');
    $("#email").setValue('');
    $("#name").setValue('');
    $("#number").setValue('');
    $("#semat").setValue('');
    $("#tozihat").setValue('');
    $("#vahed_zirabt").setValue('');
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

// New Clear button handler
$("#clear_form").find("button").click(function() {
  console.log("Clear button clicked");
  try {
    $("#record_id").setValue('');
    $("#cellphone").setValue('');
    $("#contact_person").setValue('');
    $("#email").setValue('');
    $("#name").setValue('');
    $("#number").setValue('');
    $("#semat").setValue('');
    $("#tozihat").setValue('');
    $("#vahed_zirabt").setValue('');
    $("#file").find("a.fa-trash").click();
    showMessage('فرم با موفقیت پاک شد', 'success', 5000, 'موفقیت');
    console.log("All form fields cleared");
  } catch (e) {
    console.error("Error clearing form:", e);
    showMessage('خطا در پاک کردن فرم', 'error', 5000, 'خطا');
  }
});