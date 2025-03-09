var dyn_uid1 = document.forms[0].id;
var host = PMDynaform.getHostName();
var ws = PMDynaform.getWorkspaceName();
var token = PMDynaform.getAccessToken(); 
var app_uid = PMDynaform.getProjectKeys().caseUID;
var trig_uid = "51793206667820cb64bc291093112118"; // Replace with your actual trigger UID
//grouh, onvan_amva, tarikh, pelak_amval, vasziyat_estefade, tahvil_girande
hideArrow();
appendAjaxLoading();

// Custom function to handle Edit button click
function edit_record(id, grouh, onvan_amval, tarikh, pelak_amval, vasziyat_estefade, tahvil_girande) {
    console.log("Editing record:", { id: id, grouh: grouh, onvan_amval: onvan_amval, tarikh: tarikh, pelak_amval: pelak_amval, vasziyat_estefade: vasziyat_estefade , tahvil_girande: tahvil_girande});

    // Populate web controls
    try {
        $("#record_id").setValue(id || '');
        $("#grouh").setValue(grouh || '');
        $("#onvan_amval").setValue(onvan_amval || '');
        $("#tarikh").setValue(tarikh || '');
        $("#pelak_amval").setValue(pelak_amval || '');
        $("#vasziyat_estefade").setValue(vasziyat_estefade || '');
        $("#tahvil_girande").setValue(tahvil_girande || '');

        console.log("Web controls set - record_id:", $("#record_id").getValue(), 
                    "grouh:", $("#grouh").getValue(), 
                    "onvan_amval:", $("#onvan_amval").getValue(),
                    "tarikh:", $("#tarikh").getValue(),
                    "pelak_amval:", $("#pelak_amval").getValue(),
                    "vasziyat_estefade:", $("#vasziyat_estefade").getValue(),
                    "tahvil_girande:", $("#tahvil_girande").getValue(),
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
    var tarikh = $("#tarikh").getValue();
    var pelak_amval = $("#pelak_amval").getValue();
    var vasziyat_estefade = $("#vasziyat_estefade").getValue();
    var tahvil_girande = $("#tahvil_girande").getValue();
    var temp = { id: id, grouh: grouh, onvan_amval: onvan_amval, tarikh: tarikh, pelak_amval: pelak_amval, vasziyat_estefade: vasziyat_estefade , tahvil_girande: tahvil_girande};

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
        $("#grouh").setValue('');
        $("#onvan_amval").setValue('');
        $("#tarikh").setValue('');
        $("#pelak_amval").setValue('');
        $("#vasziyat_estefade").setValue('');
        $("#tahvil_girande").setValue('');

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
    var grouh = $("#grouh").getValue();
    var onvan_amval = $("#onvan_amval").getValue();
    var tarikh = $("#tarikh").getValue();
    var pelak_amval = $("#pelak_amval").getValue();
    var vasziyat_estefade = $("#vasziyat_estefade").getValue();
    var tahvil_girande = $("#tahvil_girande").getValue();
    var temp = { grouh: grouh, onvan_amval: onvan_amval, tarikh: tarikh, pelak_amval: pelak_amval, vasziyat_estefade: vasziyat_estefade , tahvil_girande: tahvil_girande, id: recordId};
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
        $("#tarikh").setValue('');
        $("#pelak_amval").setValue('');
        $("#vasziyat_estefade").setValue('');
        $("#tahvil_girande").setValue('');
        if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
            Xcrud.reload();
        }
    }).fail(function(xhr, status, error) {
        console.log("Update Error:", xhr.responseText, status, error);
        showMessage('Error: ' + xhr.responseText, 'error', 5000, 'خطا');
        $('#n2_ajax_loading').fadeOut();
    });
});