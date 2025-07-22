var dyn_uid1 = document.forms[0].id;
var host = PMDynaform.getHostName();
var ws = PMDynaform.getWorkspaceName();
var token = PMDynaform.getAccessToken(); 
var app_uid = PMDynaform.getProjectKeys().caseUID;
var trig_uid = "77503568067f1098e6855e5076345161"; // Your trigger UID
// $("#type").setOnchange(function(newVal, oldVal) {
//   if (newVal == "اموالی") { 
//     // Show the "tamin_konnande" field if the selected value is "تامین کنندگان"
//     $("#pelak_amval").show();
//   } else {
//     // Hide the "tamin_konnande" field for any other value
//     $("#pelak_amval").hide();
//   }});

hideArrow();
appendAjaxLoading();


function edit_record(id, project_id, name) {
    console.log("Editing record:", { id, project_id, name });
    try {

        $("#record_id").setValue(id || '');
        $("#project_id").setValue(project_id || '');
        $("#name").setValue(name || '');

        console.log("Web controls set - record_id:", $("#record_id").getValue(), 
                    "project_id:", $("#project_id").getValue(), 
                    "name:", $("#name").getValue(),

                );
    } catch (e) {
        console.log("Error setting values:", e);
    }
}

// Create new record
$("#create_record").find("button").click(function() {
    var id = $("#record_id").getValue();
    var project_id = $("#project_id").getValue();
    var name = $("#name").getValue();
    var temp = { 
        id: id, 
        project_id: project_id, 
        name: name
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
        $("#project_id").setValue('');
        $("#name").setValue('');

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
    var project_id = $("#project_id").getValue();
    var name = $("#name").getValue();

    var temp = { 
        project_id: project_id, 
        name: name, 

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
        $("#project_id").setValue('');
        $("#name").setValue('');

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

