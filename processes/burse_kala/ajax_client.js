var dyn_uid1 = document.forms[0].id;
var host = PMDynaform.getHostName();
var ws = PMDynaform.getWorkspaceName();
var token = PMDynaform.getAccessToken(); 
var app_uid = PMDynaform.getProjectKeys().caseUID;
var trig_uid = "77503568067f1098e6855e5076345161"; // Your trigger UID
hideArrow();
appendAjaxLoading();

function search_table() {
    // Collect values from web controls with new variables
    var id = $("#record_id").getValue() || '';
    var name_kala = $("#name_kala").getValue() || '';
    var tarikh_arzeh = $("#tarikh_arzeh").getValue() || '';
    var baste_bandi = $("#baste_bandi").getValue() || '';
    var tolid_konnande = $("#tolid_konnande").getValue() || '';
    var gheymat = $("#gheymat").getValue() || '';
    var pishpardakht = $("#pishpardakht").getValue() || '';
    var hajm_ghabel_arzeh = $("#hajm_ghabel_arzeh").getValue() || '';
    var min_arzeh = $("#min_arzeh").getValue() || '';
    var min_kharid = $("#min_kharid").getValue() || '';
    var max_afzayesh_arzeh = $("#max_afzayesh_arzeh").getValue() || '';
    var makan_tahvil = $("#makan_tahvil").getValue() || '';
    var moshakhasat = $("#moshakhasat").getValue() || '';
    var arz = $("#arz").getValue() || '';
    var vahed = $("#vahed").getValue() || '';
    var tarikh_tahvil = $("#tarikh_tahvil").getValue() || '';

    console.log("Searching table with:", {
        id, name_kala, tarikh_arzeh, baste_bandi, tolid_konnande, gheymat, pishpardakht, 
        hajm_ghabel_arzeh, min_arzeh, min_kharid, max_afzayesh_arzeh, makan_tahvil, 
        moshakhasat, arz, vahed, tarikh_tahvil
    });

    // Show loading indicator
    $('#n2_ajax_loading').fadeIn();

    // Check if Xcrud is available
    if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
        // Build filter conditions with new variables
        var filters = [];
        if (id) filters.push({ field: 'id', value: id, type: 'equals' });
        if (name_kala) filters.push({ field: 'name_kala', value: name_kala, type: 'like' });
        if (tarikh_arzeh) filters.push({ field: 'tarikh_arzeh', value: tarikh_arzeh, type: 'equals' });
        if (baste_bandi) filters.push({ field: 'baste_bandi', value: baste_bandi, type: 'like' });
        if (tolid_konnande) filters.push({ field: 'tolid_konnande', value: tolid_konnande, type: 'like' });
        if (gheymat) filters.push({ field: 'gheymat', value: gheymat, type: 'equals' });
        if (pishpardakht) filters.push({ field: 'pishpardakht', value: pishpardakht, type: 'equals' });
        if (hajm_ghabel_arzeh) filters.push({ field: 'hajm_ghabel_arzeh', value: hajm_ghabel_arzeh, type: 'equals' });
        if (min_arzeh) filters.push({ field: 'min_arzeh', value: min_arzeh, type: 'equals' });
        if (min_kharid) filters.push({ field: 'min_kharid', value: min_kharid, type: 'equals' });
        if (max_afzayesh_arzeh) filters.push({ field: 'max_afzayesh_arzeh', value: max_afzayesh_arzeh, type: 'equals' });
        if (makan_tahvil) filters.push({ field: 'makan_tahvil', value: makan_tahvil, type: 'like' });
        if (moshakhasat) filters.push({ field: 'moshakhasat', value: moshakhasat, type: 'like' });
        if (arz) filters.push({ field: 'arz', value: arz, type: 'like' });
        if (vahed) filters.push({ field: 'vahed', value: vahed, type: 'like' });
        if (tarikh_tahvil) filters.push({ field: 'tarikh_tahvil', value: tarikh_tahvil, type: 'equals' });

        // Since Xcrud doesn’t have a direct set_filter, we’ll use its search mechanism
        if (filters.length > 0) {
            var searchPhrase = filters.map(f => f.value).join(" ");
            var searchFields = filters.map(f => f.field).join(",");

            // Trigger Xcrud reload with search parameters
            $.ajax({
                url: Xcrud.config('url'), // Xcrud’s internal URL (adjust if needed)
                type: 'POST',
                data: {
                    task: 'search',
                    phrase: searchPhrase,
                    fields: searchFields,
                    search: 1
                },
                success: function(response) {
                    Xcrud.reload();
                    console.log("Table filtered successfully");
                    $('#n2_ajax_loading').fadeOut();
                },
                error: function(xhr, status, error) {
                    console.log("Search Error:", xhr.responseText, status, error);
                    showMessage('Error: ' + xhr.responseText, 'error', 5000, 'خطا');
                    $('#n2_ajax_loading').fadeOut();
                }
            });
        } else {
            Xcrud.reload();
            $('#n2_ajax_loading').fadeOut();
        }
    } else {
        console.warn("Xcrud not available or reload function missing");
        showMessage('Xcrud is not initialized properly', 'error', 5000, 'خطا');
        $('#n2_ajax_loading').fadeOut();
    }
}

// Custom function to handle Edit button click
function edit_record(id, name_kala, tarikh_arzeh, baste_bandi, tolid_konnande, gheymat, pishpardakht, hajm_ghabel_arzeh, min_arzeh, min_kharid, max_afzayesh_arzeh, makan_tahvil, moshakhasat, arz, vahed, tarikh_tahvil) {
    console.log("Editing record:", { id, name_kala, tarikh_arzeh, baste_bandi, tolid_konnande, gheymat, pishpardakht, hajm_ghabel_arzeh, min_arzeh, min_kharid, max_afzayesh_arzeh, makan_tahvil, moshakhasat, arz, vahed, tarikh_tahvil });
    try {
        tarikh_tahvil = (tarikh_tahvil === "0000-00-00 00:00:00") ? "" : tarikh_tahvil;
        $("#record_id").setValue(id || '');
        $("#name_kala").setValue(name_kala || '');
        $("#tarikh_arzeh").setValue(tarikh_arzeh || '');
        $("#baste_bandi").setValue(baste_bandi || '');
        $("#tolid_konnande").setValue(tolid_konnande || '');
        $("#gheymat").setValue(gheymat || '');
        $("#pishpardakht").setValue(pishpardakht || '');
        $("#hajm_ghabel_arzeh").setValue(hajm_ghabel_arzeh || '');
        $("#min_arzeh").setValue(min_arzeh || '');
        $("#min_kharid").setValue(min_kharid || '');
        $("#max_afzayesh_arzeh").setValue(max_afzayesh_arzeh || '');
        $("#makan_tahvil").setValue(makan_tahvil || '');
        $("#moshakhasat").setValue(moshakhasat || '');
        $("#arz").setValue(arz || '');
        $("#vahed").setValue(vahed || '');
        $("#tarikh_tahvil").setValue(tarikh_tahvil || '');
        console.log("Web controls set - record_id:", $("#record_id").getValue(), 
                    "name_kala:", $("#name_kala").getValue(), 
                    "tarikh_arzeh:", $("#tarikh_arzeh").getValue(),
                    "baste_bandi:", $("#baste_bandi").getValue(),
                    "tolid_konnande:", $("#tolid_konnande").getValue(),
                    "gheymat:", $("#gheymat").getValue(),
                    "pishpardakht:", $("#pishpardakht").getValue(),
                    "hajm_ghabel_arzeh:", $("#hajm_ghabel_arzeh").getValue(),
                    "min_arzeh:", $("#min_arzeh").getValue(),
                    "min_kharid:", $("#min_kharid").getValue(),
                    "max_afzayesh_arzeh:", $("#max_afzayesh_arzeh").getValue(),
                    "makan_tahvil:", $("#makan_tahvil").getValue(),
                    "moshakhasat:", $("#moshakhasat").getValue(),
                    "arz:", $("#arz").getValue(),
                    "vahed:", $("#vahed").getValue(),
                    "tarikh_tahvil:", $("#tarikh_tahvil").getValue()
                );
    } catch (e) {
        console.log("Error setting values:", e);
    }
}

// Create new record
$("#create_record").find("button").click(function() {
    var id = $("#record_id").getValue();
    var name_kala = $("#name_kala").getValue();
    var tarikh_arzeh = $("#tarikh_arzeh").getValue();
    var baste_bandi = $("#baste_bandi").getValue();
    var tolid_konnande = $("#tolid_konnande").getValue();
    var gheymat = $("#gheymat").getValue();
    var pishpardakht = $("#pishpardakht").getValue();
    var hajm_ghabel_arzeh = $("#hajm_ghabel_arzeh").getValue();
    var min_arzeh = $("#min_arzeh").getValue();
    var min_kharid = $("#min_kharid").getValue();
    var max_afzayesh_arzeh = $("#max_afzayesh_arzeh").getValue();
    var makan_tahvil = $("#makan_tahvil").getValue();
    var moshakhasat = $("#moshakhasat").getValue();
    var arz = $("#arz").getValue();
    var vahed = $("#vahed").getValue();
    var tarikh_tahvil = $("#tarikh_tahvil").getValue();
    var temp = { 
        id: id, 
        name_kala: name_kala, 
        tarikh_arzeh: tarikh_arzeh, 
        baste_bandi: baste_bandi, 
        tolid_konnande: tolid_konnande, 
        gheymat: gheymat, 
        pishpardakht: pishpardakht, 
        hajm_ghabel_arzeh: hajm_ghabel_arzeh, 
        min_arzeh: min_arzeh, 
        min_kharid: min_kharid, 
        max_afzayesh_arzeh: max_afzayesh_arzeh, 
        makan_tahvil: makan_tahvil, 
        moshakhasat: moshakhasat, 
        arz: arz, 
        vahed: vahed, 
        tarikh_tahvil: tarikh_tahvil 
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
        if (msg.message && msg.message.startsWith('Error:')) {
            showMessage(msg.message, 'error', 5000, 'خطا');
        } else {
            showMessage(msg.message, 'success', 5000, 'موفقیت');
            $("#record_id").setValue('');
            $("#name_kala").setValue('');
            $("#tarikh_arzeh").setValue('');
            $("#baste_bandi").setValue('');
            $("#tolid_konnande").setValue('');
            $("#gheymat").setValue('');
            $("#pishpardakht").setValue('');
            $("#hajm_ghabel_arzeh").setValue('');
            $("#min_arzeh").setValue('');
            $("#min_kharid").setValue('');
            $("#max_afzayesh_arzeh").setValue('');
            $("#makan_tahvil").setValue('');
            $("#moshakhasat").setValue('');
            $("#arz").setValue('');
            $("#vahed").setValue('');
            $("#tarikh_tahvil").setValue('');
            if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
                Xcrud.reload();
            }
        }
        $('#n2_ajax_loading').fadeOut();
    }).fail(function(xhr, status, error) {
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
    var name_kala = $("#name_kala").getValue();
    var tarikh_arzeh = $("#tarikh_arzeh").getValue();
    var baste_bandi = $("#baste_bandi").getValue();
    var tolid_konnande = $("#tolid_konnande").getValue();
    var gheymat = $("#gheymat").getValue();
    var pishpardakht = $("#pishpardakht").getValue();
    var hajm_ghabel_arzeh = $("#hajm_ghabel_arzeh").getValue();
    var min_arzeh = $("#min_arzeh").getValue();
    var min_kharid = $("#min_kharid").getValue();
    var max_afzayesh_arzeh = $("#max_afzayesh_arzeh").getValue();
    var makan_tahvil = $("#makan_tahvil").getValue();
    var moshakhasat = $("#moshakhasat").getValue();
    var arz = $("#arz").getValue();
    var vahed = $("#vahed").getValue();
    var tarikh_tahvil = $("#tarikh_tahvil").getValue();
    var temp = { 
        name_kala: name_kala, 
        tarikh_arzeh: tarikh_arzeh, 
        baste_bandi: baste_bandi, 
        tolid_konnande: tolid_konnande, 
        gheymat: gheymat, 
        pishpardakht: pishpardakht, 
        hajm_ghabel_arzeh: hajm_ghabel_arzeh, 
        min_arzeh: min_arzeh, 
        min_kharid: min_kharid, 
        max_afzayesh_arzeh: max_afzayesh_arzeh, 
        makan_tahvil: makan_tahvil, 
        moshakhasat: moshakhasat, 
        arz: arz, 
        vahed: vahed, 
        tarikh_tahvil: tarikh_tahvil, 
        id: recordId 
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
        $("#tarikh_arzeh").setValue('');
        $("#baste_bandi").setValue('');
        $("#tolid_konnande").setValue('');
        $("#gheymat").setValue('');
        $("#pishpardakht").setValue('');
        $("#hajm_ghabel_arzeh").setValue('');
        $("#min_arzeh").setValue('');
        $("#min_kharid").setValue('');
        $("#max_afzayesh_arzeh").setValue('');
        $("#makan_tahvil").setValue('');
        $("#moshakhasat").setValue('');
        $("#arz").setValue('');
        $("#vahed").setValue('');
        $("#tarikh_tahvil").setValue('');
        if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
            Xcrud.reload();
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