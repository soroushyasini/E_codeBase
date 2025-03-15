var dyn_uid1 = document.forms[0].id;
var host = PMDynaform.getHostName();
var ws = PMDynaform.getWorkspaceName();
var token = PMDynaform.getAccessToken(); 
var app_uid = PMDynaform.getProjectKeys().caseUID;
var trig_uid = "77124592967cd594ad6add1031134547"; // Your trigger UID

$('#record_id').hide();
hideArrow();
appendAjaxLoading();

// Function to update XCRUD column titles
// function updateXcrudTitles() {
//     if ($('.xcrud-list').length) {
//         var columnTitles = [

//             'ID',               // id
//             'گروه کالا',       // grouh
//             'عنوان اموال',     // onvan_amval
//             'تعداد',           // tedad
//             'نوع',             // type
//             'مشخصه',           // moshakhase
//             'مکان',            // makan
//             'شرکت',            // sherkat
//             'سند',             // sanad
//             'تاریخ تحویل',     // n2_date(tarikh)
//             'پلاک اموال',      // pelak_amval
//             'وضعیت استفاده',  // vasziyat_estefade
//             'تحویل گیرنده',   // tahvil_girande
//             ''                  // actions column
//         ];

//         $('.xcrud-list thead tr.xcrud-th th').each(function(index) {
//             if (index < columnTitles.length) {
//                 $(this).text(columnTitles[index]);
//             }
//         });

//         console.log("XCRUD column titles updated to Persian");
//     }
// }

function search_table() {
    // Collect values from web controls
    var id = $("#record_id").getValue() || '';
    var grouh = $("#grouh").getValue() || '';
    var onvan_amval = $("#onvan_amval").getValue() || '';
    var tedad = $("#tedad").getValue() || '';
    var type = $("#type").getValue() || '';
    var moshakhase = $("#moshakhase").getValue() || '';
    var makan = $("#makan").getValue() || '';
    var sherkat = $("#sherkat").getValue() || '';
    var sanad = $("#sanad").getValue() || '';
    var tarikh = $("#tarikh").getValue() || '';
    var pelak_amval = $("#pelak_amval").getValue() || '';
    var vasziyat_estefade = $("#vasziyat_estefade").getValue() || '';
    var tahvil_girande = $("#tahvil_girande").getValue() || '';

    console.log("Searching table with:", {
        id, grouh, onvan_amval, tedad, type, moshakhase, makan, 
        sherkat, sanad, tarikh, pelak_amval, vasziyat_estefade, tahvil_girande
    });

    // Show loading indicator
    $('#n2_ajax_loading').fadeIn();

    // Check if Xcrud is available
    if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
        // Build filter conditions
        var filters = [];
        if (id) filters.push({ field: 'id', value: id, type: 'equals' });
        if (grouh) filters.push({ field: 'grouh', value: grouh, type: 'like' });
        if (onvan_amval) filters.push({ field: 'onvan_amval', value: onvan_amval, type: 'like' });
        if (tedad) filters.push({ field: 'tedad', value: tedad, type: 'equals' });
        if (type) filters.push({ field: 'type', value: type, type: 'like' });
        if (moshakhase) filters.push({ field: 'moshakhase', value: moshakhase, type: 'like' });
        if (makan) filters.push({ field: 'makan', value: makan, type: 'like' });
        if (sherkat) filters.push({ field: 'sherkat', value: sherkat, type: 'like' });
        if (sanad) filters.push({ field: 'sanad', value: sanad, type: 'like' });
        if (tarikh) filters.push({ field: 'tarikh', value: tarikh, type: 'equals' });
        if (pelak_amval) filters.push({ field: 'pelak_amval', value: pelak_amval, type: 'like' });
        if (vasziyat_estefade) filters.push({ field: 'vasziyat_estefade', value: vasziyat_estefade, type: 'like' });
        if (tahvil_girande) filters.push({ field: 'tahvil_girande', value: tahvil_girande, type: 'like' });

        // Since Xcrud doesn’t have a direct set_filter, we’ll use its search mechanism
        if (filters.length > 0) {
            // For simplicity, combine all filters into a single "phrase" for Xcrud’s global search
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
                    // Reload the table with filtered data
                    Xcrud.reload();
                    console.log("Table filtered successfully");
                    $('#n2_ajax_loading').fadeOut();
                   // setTimeout(updateXcrudTitles, 500); // Update titles after reload
                },
                error: function(xhr, status, error) {
                    console.log("Search Error:", xhr.responseText, status, error);
                    showMessage('Error: ' + xhr.responseText, 'error', 5000, 'خطا');
                    $('#n2_ajax_loading').fadeOut();
                }
            });
        } else {
            // If no filters, just reload the full table
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
function edit_record(id, grouh, onvan_amval, tedad, type, moshakhase, makan, sherkat, sanad, tarikh, pelak_amval, vasziyat_estefade, tahvil_girande) {
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
        tahvil_girande: tahvil_girande 
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
        if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
            Xcrud.reload();
           // setTimeout(updateXcrudTitles, 500); // Fallback: Update titles after reload
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

// Initialize and observe table updates
// $(document).ready(function() {
//     // Run initially
//     updateXcrudTitles();

//     // Set up MutationObserver to detect table reloads
//     var target = document.querySelector('#panel_grid .panel-body'); // Target the parent container
//     if (target) {
//         var observer = new MutationObserver(function(mutations) {
//             setTimeout(updateXcrudTitles, 100); // Small delay to ensure table is rendered
//         });
//         observer.observe(target, { childList: true, subtree: true });
//     } else {
//         console.warn("Target '#panel_grid .panel-body' not found initially. Using fallback method.");
//     }
// });