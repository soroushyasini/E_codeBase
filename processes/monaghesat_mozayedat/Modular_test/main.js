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
function edit_record(record_id, type, akhz, name, mozayede_shomare, mablagh, dastgahejrai, nahve_sherkat, deadline_asnad, tahvil_bar, deadline_pasokh, alarm, zemanat_nameh, zemanat_nameh_2,insert_date , vahed_marbute, tamin_konnande, date_created, left_days, category, subcategory, products, haffari_area, file, file_name, file_nahaee, file_name_nahaee, vaze_tamdid, vaze_sherkat) {
  console.log("Editing record:", { record_id, type, akhz, name, mozayede_shomare, mablagh, dastgahejrai, nahve_sherkat, deadline_asnad, tahvil_bar, deadline_pasokh, alarm, zemanat_nameh, zemanat_nameh_2, insert_date, vahed_marbute, tamin_konnande, date_created, left_days, category, subcategory, products, haffari_area, file, file_name, file_nahaee, file_name_nahaee, vaze_tamdid, vaze_sherkat });
  try {
    deadline_asnad = (deadline_asnad === "0000-00-00 00:00:00") ? "" : deadline_asnad;
    tahvil_bar = (tahvil_bar === "0000-00-00 00:00:00") ? "" : tahvil_bar;
    deadline_pasokh = (deadline_pasokh === "0000-00-00 00:00:00") ? "" : deadline_pasokh;
    insert_date = (insert_date === "0000-00-00 00:00:00") ? "" : insert_date;
    date_created = (date_created === "0000-00-00 00:00:00") ? "" : date_created;
    $("#record_id").setValue(record_id || '');
    $("#type").setValue(type || '');
    $("#akhz").setValue(akhz || '');
    $("#name").setValue(name || '');
    $("#mozayede_shomare").setValue(mozayede_shomare || '');
    $("#mablagh").setValue(mablagh || '');
    $("#dastgahejrai").setValue(dastgahejrai || '');
    $("#nahve_sherkat").setValue(nahve_sherkat || '');
    $("#deadline_asnad").setValue(deadline_asnad || '');
    $("#deadline_asnad").setText(deadline_asnad || '');
    $("#tahvil_bar").setValue(tahvil_bar || '');
    $("#tahvil_bar").setText(tahvil_bar || '');
    $("#deadline_pasokh").setValue(deadline_pasokh || '');
    $("#deadline_pasokh").setText(deadline_pasokh || '');
    $("#alarm").setValue(alarm || '');
    $("#zemanat_nameh").setValue(zemanat_nameh || '');
    $("#zemanat_nameh_2").setValue(zemanat_nameh_2 || '');
    $("#insert_date").setValue(deadline_pasokh || '');
    $("#insert_date").setText(deadline_pasokh || '');
    $("#vahed_marbute").setValue(vahed_marbute || '');
    $("#tamin_konnande").setValue(tamin_konnande || '');
    $("#category").setValue(category || '');
    $("#subcategory").setValue(subcategory || '');
    $("#products").setValue(products || '');
    $("#haffari_area").setValue(haffari_area || '');
    $("#file").setValue(file || '');
    $("#file_name").setValue(file_name || '');
    $("#file_nahaee").setValue(file_nahaee || '');
    $("#file_name_nahaee").setValue(file_name_nahaee || '');
    $("#vaze_tamdid").setValue(vaze_tamdid || '');
    $("#vaze_sherkat").setValue(vaze_sherkat || '');

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

    if(file_nahaee !== '' && file_nahaee !=undefined && file_name_nahaee !=undefined){
      var temp = new Array();
      var temp1 = new Object();
      temp1.appDocUid = file_nahaee;
      temp1.name = file_name_nahaee;
      temp1.version = 1;
      temp1.size = 1024;
      temp[0] = temp1;
      getFieldById('file_nahaee').createFormMultiFile(temp, 0);
    }

    console.log("Web controls set - record_id:", $("#record_id").getValue(), 
                "type:", $("#type").getValue(),
                "akhz:", $("#akhz").getValue(),
                "name:", $("#name").getValue(),
                "mozayede_shomare:", $("#mozayede_shomare").getValue(),
                "mablagh:", $("#mablagh").getValue(),
                "dastgahejrai:", $("#dastgahejrai").getValue(),
                "nahve_sherkat:", $("#nahve_sherkat").getValue(),
                "deadline_asnad:", $("#deadline_asnad").getText(),
                "tahvil_bar:", $("#tahvil_bar").getText(),
                "deadline_pasokh:", $("#deadline_pasokh").getText(),
                "alarm:", $("#alarm").getValue(),
                "zemanat_nameh:", $("#zemanat_nameh").getValue(),
                "zemanat_nameh_2:", $("#zemanat_nameh_2").getValue(),
                "insert_date:", $("#insert_date").getText(),
                "vahed_marbute:", $("#vahed_marbute").getValue(),
                "tamin_konnande:", $("#tamin_konnande").getValue(),
                "category:", $("#category").getValue(),
                "subcategory:", $("#subcategory").getValue(),
                "products:", $("#products").getValue(),
                "haffari_area:", $("#haffari_area").getValue(),
                "file:", $("#file").getValue(),
                "file_name:", $("#file_name").getValue(),
                "file_nahaee:", $("#file_nahaee").getValue(),
                "file_name_nahaee:", $("#file_name_nahaee").getValue(),
                "vaze_tamdid:", $("#vaze_tamdid").getValue(),
                "vaze_sherkat:", $("#vaze_sherkat").getValue()
               );
  } catch (e) {
    console.log("Error setting values:", e);
  }
}

$("#create_record").find("button").click(function() {
  var record_id = $("#record_id").getValue();
  var type = $("#type").getValue();
  var akhz = $("#akhz").getValue();
  var name = $("#name").getValue();
  var mozayede_shomare = $("#mozayede_shomare").getValue();
  var mablagh = $("#mablagh").getValue();
  var dastgahejrai = $("#dastgahejrai").getValue();
  var nahve_sherkat = $("#nahve_sherkat").getValue();
  var deadline_asnad = $("#deadline_asnad").getText();
  var tahvil_bar = $("#tahvil_bar").getText();
  var deadline_pasokh = $("#deadline_pasokh").getText();
  var alarm = $("#alarm").getValue();
  var zemanat_nameh = $("#zemanat_nameh").getValue();
  var zemanat_nameh_2 = $("#zemanat_nameh_2").getValue();
  var insert_date = $("#insert_date").getText();
  var vahed_marbute = $("#vahed_marbute").getValue();
  var tamin_konnande = $("#tamin_konnande").getValue();
  var category = $("#category").getValue();
  var subcategory = $("#subcategory").getValue();
  var products = $("#products").getValue();
  var haffari_area = $("#haffari_area").getValue();
  var file = '';
  var file_name = '';
  var file_nahaee = '';
  var file_name_nahaee = '';
  var fileValue = getFieldById('file').model.getData().value;
  var fileNahaeeValue = getFieldById('file_nahaee').model.getData().value;
  if(fileValue[0] !=undefined){
    file = fileValue[0].appDocUid;
    file_name = fileValue[0].name;
  }
  if(fileNahaeeValue[0] !=undefined){
    file_nahaee = fileNahaeeValue[0].appDocUid;
    file_name_nahaee = fileNahaeeValue[0].name;
  }
  var vaze_tamdid = $("#vaze_tamdid").getValue();
  var vaze_sherkat = $("#vaze_sherkat").getValue();

  var temp = { 
    record_id: record_id, 
    type: type,
    akhz: akhz,
    name: name,
    mozayede_shomare: mozayede_shomare,
    mablagh: mablagh,
    dastgahejrai: dastgahejrai,
    nahve_sherkat: nahve_sherkat,
    deadline_asnad: deadline_asnad,
    tahvil_bar: tahvil_bar,
    deadline_pasokh: deadline_pasokh,
    alarm: alarm,
    zemanat_nameh: zemanat_nameh,
    zemanat_nameh_2: zemanat_nameh_2,
    insert_date: insert_date,
    vahed_marbute: vahed_marbute,
    tamin_konnande: tamin_konnande,
    category: category,
    subcategory: subcategory,
    products: products,
    haffari_area: haffari_area,
    file: file,
    file_name: file_name,
    file_nahaee: file_nahaee,
    file_name_nahaee: file_name_nahaee,
    vaze_tamdid: vaze_tamdid,
    vaze_sherkat: vaze_sherkat
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
    if (msg.message === 'این رکورد قبلاً ثبت شده است' || (msg.message && msg.message.startsWith('Error:'))) {
      showMessage(msg.message, 'error', 5000, 'خطا');
    } else {
      showMessage(msg.message, 'success', 5000, 'موفقیت');
      $("#record_id").setValue('');
      $("#type").setValue('');
      $("#akhz").setValue('');
      $("#name").setValue('');
      $("#mozayede_shomare").setValue('');
      $("#mablagh").setValue('');
      $("#dastgahejrai").setValue('');
      $("#nahve_sherkat").setValue('');
      $("#deadline_asnad").setValue('');
      $("#deadline_asnad").setText('');
      $("#tahvil_bar").setValue('');
      $("#tahvil_bar").setText('');
      $("#deadline_pasokh").setValue('');
      $("#deadline_pasokh").setText('');
      $("#alarm").setValue('');
      $("#zemanat_nameh").setValue('');
      $("#zemanat_nameh_2").setValue('');
      $("#insert_date").setValue('');
      $("#insert_date").setText('');
      $("#vahed_marbute").setValue('');
      $("#tamin_konnande").setValue('');
      $("#category").setValue('');
      $("#subcategory").setValue('');
      $("#products").setValue('');
      $("#haffari_area").setValue('');
      $("#file").find("a.fa-trash").click();
      $("#file_nahaee").find("a.fa-trash").click();
      $("#vaze_tamdid").setValue('');
      $("#vaze_sherkat").setValue('');
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
  var type = $("#type").getValue();
  var akhz = $("#akhz").getValue();
  var name = $("#name").getValue();
  var mozayede_shomare = $("#mozayede_shomare").getValue();
  var mablagh = $("#mablagh").getValue();
  var dastgahejrai = $("#dastgahejrai").getValue();
  var nahve_sherkat = $("#nahve_sherkat").getValue();
  var deadline_asnad = $("#deadline_asnad").getText();
  var tahvil_bar = $("#tahvil_bar").getText();
  var deadline_pasokh = $("#deadline_pasokh").getText();
  var alarm = $("#alarm").getValue();
  var zemanat_nameh = $("#zemanat_nameh").getValue();
  var zemanat_nameh_2 = $("#zemanat_nameh_2").getValue();
  var insert_date = $("#insert_date").getText();
  var vahed_marbute = $("#vahed_marbute").getValue();
  var tamin_konnande = $("#tamin_konnande").getValue();
  var category = $("#category").getValue();
  var subcategory = $("#subcategory").getValue();
  var products = $("#products").getValue();
  var haffari_area = $("#haffari_area").getValue();
  var file = '';
  var file_name = '';
  var file_nahaee = '';
  var file_name_nahaee = '';
  var fileValue = getFieldById('file').model.getData().value;
  var fileNahaeeValue = getFieldById('file_nahaee').model.getData().value;
  if(fileValue[0] !=undefined){
    file = fileValue[0].appDocUid;
    file_name = fileValue[0].name;
  }
  if(fileNahaeeValue[0] !=undefined){
    file_nahaee = fileNahaeeValue[0].appDocUid;
    file_name_nahaee = fileNahaeeValue[0].name;
  }
  var vaze_tamdid = $("#vaze_tamdid").getValue();
  var vaze_sherkat = $("#vaze_sherkat").getValue();

  var temp = { 
    record_id: record_id, 
    type: type,
    akhz: akhz,
    name: name,
    mozayede_shomare: mozayede_shomare,
    mablagh: mablagh,
    dastgahejrai: dastgahejrai,
    nahve_sherkat: nahve_sherkat,
    deadline_asnad: deadline_asnad,
    tahvil_bar: tahvil_bar,
    deadline_pasokh: deadline_pasokh,
    alarm: alarm,
    zemanat_nameh: zemanat_nameh,
    zemanat_nameh_2: zemanat_nameh_2,
    insert_date: insert_date,
    vahed_marbute: vahed_marbute,
    tamin_konnande: tamin_konnande,
    category: category,
    subcategory: subcategory,
    products: products,
    haffari_area: haffari_area,
    file: file,
    file_name: file_name,
    file_nahaee: file_nahaee,
    file_name_nahaee: file_name_nahaee,
    vaze_tamdid: vaze_tamdid,
    vaze_sherkat: vaze_sherkat
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
    $("#type").setValue('');
    $("#akhz").setValue('');
    $("#name").setValue('');
    $("#mozayede_shomare").setValue('');
    $("#mablagh").setValue('');
    $("#dastgahejrai").setValue('');
    $("#nahve_sherkat").setValue('');
    $("#deadline_asnad").setValue('');
    $("#deadline_asnad").setText('');
    $("#tahvil_bar").setValue('');
    $("#tahvil_bar").setText('');
    $("#deadline_pasokh").setValue('');
    $("#deadline_pasokh").setText('');
    $("#alarm").setValue('');
    $("#zemanat_nameh").setValue('');
    $("#zemanat_nameh_2").setValue('');
    $("#insert_date").setValue('');
    $("#insert_date").setText('');
    $("#vahed_marbute").setValue('');
    $("#tamin_konnande").setValue('');
    $("#category").setValue('');
    $("#subcategory").setValue('');
    $("#products").setValue('');
    $("#haffari_area").setValue('');
    $("#file").find("a.fa-trash").click();
    $("#file_nahaee").find("a.fa-trash").click();
    $("#vaze_tamdid").setValue('');
    $("#vaze_sherkat").setValue('');
    if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
      Xcrud.reload();
    }
  }).fail(function(xhr, status, error) {
    console.log("Update Error:", xhr.responseText, status, error);
    showMessage('Error: ' + xhr.responseText, 'error', 5000, 'خطا');
    $('#n2_ajax_loading').fadeOut();
  });
});