var dyn_uid1 = document.forms[0].id;
var host = PMDynaform.getHostName();
var ws = PMDynaform.getWorkspaceName();
var token = PMDynaform.getAccessToken(); 
var app_uid = PMDynaform.getProjectKeys().caseUID;
var trig_uid = "51793206667820cb64bc291093112118"; // Replace with your actual trigger UID

hideArrow();
appendAjaxLoading();

// Custom function to handle Edit button click
function edit_record(id, value_1, value_2) {
    console.log("Editing record:", { id: id, value_1: value_1, value_2: value_2 });

    // Populate web controls
    try {
        $("#record_id").setValue(id || '');
        $("#webcontrol1").setValue(value_1 || '');
        $("#webcontrol2").setValue(value_2 || '');

        console.log("Web controls set - record_id:", $("#record_id").getValue(), 
                    "webcontrol1:", $("#webcontrol1").getValue(), 
                    "webcontrol2:", $("#webcontrol2").getValue());
    } catch (e) {
        console.log("Error setting values:", e);
    }
}

// Create new record
$("#button123").find("button").click(function() {
    var value1 = $("#webcontrol1").getValue();
    var value2 = $("#webcontrol2").getValue();
    var temp = { value1: value1, value2: value2 };

    console.log("Creating new record with data:", temp);
    $('#n2_ajax_loading').fadeIn();

    $.ajax({
        type: 'PUT',
        url: host + '/api/1.0/' + ws + '/cases/' + app_uid + '/execute-trigger/' + trig_uid + '?AJAX=1&REQ_TYPE=save_to_db',
        data: temp,
        beforeSend: function(xhr) { xhr.setRequestHeader('Authorization', 'Bearer ' + token); }
    }).done(function(msg) {
        console.log("Create response:", msg);
        showMessage(msg.message, 'success', 5000, 'موفقیت');
        $('#n2_ajax_loading').fadeOut();
        $("#record_id").setValue('');
        $("#webcontrol1").setValue('');
        $("#webcontrol2").setValue('');
        if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
            Xcrud.reload();
        }
    }).fail(function(xhr, status, error) {
        console.log("Create Error:", xhr.responseText, status, error);
        showMessage('Error: ' + xhr.responseText, 'error', 5000, 'خطا');
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

    var value1 = $("#webcontrol1").getValue();
    var value2 = $("#webcontrol2").getValue();
    var temp = { value1: value1, value2: value2, id: recordId };

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
        $("#webcontrol1").setValue('');
        $("#webcontrol2").setValue('');
        if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
            Xcrud.reload();
        }
    }).fail(function(xhr, status, error) {
        console.log("Update Error:", xhr.responseText, status, error);
        showMessage('Error: ' + xhr.responseText, 'error', 5000, 'خطا');
        $('#n2_ajax_loading').fadeOut();
    });
});