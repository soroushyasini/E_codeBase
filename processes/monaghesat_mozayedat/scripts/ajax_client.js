var dyn_uid1 = document.forms[0].id;
var host = PMDynaform.getHostName();
var ws = PMDynaform.getWorkspaceName();
var token = PMDynaform.getAccessToken(); 
var app_uid = PMDynaform.getProjectKeys().caseUID;
var trig_uid = "77124592967cd594ad6add1031134547"; // Your trigger UID

$('#record_id').hide();
hideArrow();
appendAjaxLoading();

function edit_record(insert_date, type, akhz, tamin_konnande, name, mozayede_shomare, mablagh, dastgahejrai, zemanat_nameh, zemanat_nameh_2, nahve_sherkat, deadline_asnad, tahvil_bar, deadline_pasokh, vahed_marbute, alarm, monagheseh_peyvast, category, subcategory, products, record_id, haffari_area) {
    console.log("Editing record:", { insert_date, type, akhz, tamin_konnande, name, mozayede_shomare, mablagh, dastgahejrai, zemanat_nameh, zemanat_nameh_2, nahve_sherkat, deadline_asnad, tahvil_bar, deadline_pasokh, vahed_marbute, alarm, monagheseh_peyvast, category, subcategory, products, record_id, haffari_area });
    try {
        insert_date = (insert_date === "0000-00-00 00:00:00") ? "" : insert_date;
        $("#insert_date").setValue(insert_date || '');
        $("#type").setValue(type || '');
        $("#akhz").setValue(akhz || '');
        $("#tamin_konnande").setValue(tamin_konnande || '');
        $("#name").setValue(name || '');
        $("#mozayede_shomare").setValue(mozayede_shomare || '');
        $("#mablagh").setValue(mablagh || '');
        $("#dastgahejrai").setValue(dastgahejrai || '');
        $("#zemanat_nameh").setValue(zemanat_nameh || '');
        $("#zemanat_nameh_2").setValue(zemanat_nameh_2 || '');
        $("#nahve_sherkat").setValue(nahve_sherkat || '');
        $("#deadline_asnad").setValue(deadline_asnad || '');
        $("#tahvil_bar").setValue(tahvil_bar || '');
        $("#deadline_pasokh").setValue(deadline_pasokh || '');
        $("#vahed_marbute").setValue(vahed_marbute || '');
        $("#alarm").setValue(alarm || '');
        $("#monagheseh_peyvast").setValue(monagheseh_peyvast || '');
        $("#category").setValue(category || '');
        $("#subcategory").setValue(subcategory || '');
        $("#products").setValue(products || '');
        $("#record_id").setValue(record_id || '');
        $("#haffari_area").setValue(haffari_area || '');
        console.log("Web controls set - insert_date:", $("#insert_date").getValue(), 
                    "type:", $("#type").getValue(), 
                    "akhz:", $("#akhz").getValue(),
                    "tamin_konnande:", $("#tamin_konnande").getValue(),
                    "name:", $("#name").getValue(),
                    "mozayede_shomare:", $("#mozayede_shomare").getValue(),
                    "mablagh:", $("#mablagh").getValue(),
                    "dastgahejrai:", $("#dastgahejrai").getValue(),
                    "zemanat_nameh:", $("#zemanat_nameh").getValue(),
                    "zemanat_nameh_2:", $("#zemanat_nameh_2").getValue(),
                    "nahve_sherkat:", $("#nahve_sherkat").getValue(),
                    "deadline_asnad:", $("#deadline_asnad").getValue(),
                    "tahvil_bar:", $("#tahvil_bar").getValue(),
                    "deadline_pasokh:", $("#deadline_pasokh").getValue(),
                    "vahed_marbute:", $("#vahed_marbute").getValue(),
                    "alarm:", $("#alarm").getValue(),
                    "monagheseh_peyvast:", $("#monagheseh_peyvast").getValue(),
                    "category:", $("#category").getValue(),
                    "subcategory:", $("#subcategory").getValue(),
                    "products:", $("#products").getValue(),
                    "record_id:", $("#record_id").getValue(),
                    "haffari_area:", $("#haffari_area").getValue()
                );
    } catch (e) {
        console.log("Error setting values:", e);
    }
}

// Create new record
$("#create_record").find("button").click(function() {
    var insert_date = $("#insert_date").getValue();
    var type = $("#type").getValue();
    var akhz = $("#akhz").getValue();
    var tamin_konnande = $("#tamin_konnande").getValue();
    var name = $("#name").getValue();
    var mozayede_shomare = $("#mozayede_shomare").getValue();
    var mablagh = $("#mablagh").getValue();
    var dastgahejrai = $("#dastgahejrai").getValue();
    var zemanat_nameh = $("#zemanat_nameh").getValue();
    var zemanat_nameh_2 = $("#zemanat_nameh_2").getValue();
    var nahve_sherkat = $("#nahve_sherkat").getValue();
    var deadline_asnad = $("#deadline_asnad").getValue();
    var tahvil_bar = $("#tahvil_bar").getValue();
    var deadline_pasokh = $("#deadline_pasokh").getValue();
    var vahed_marbute = $("#vahed_marbute").getValue();
    var alarm = $("#alarm").getValue();
    var monagheseh_peyvast = $("#monagheseh_peyvast").getValue();
    var category = $("#category").getValue();
    var subcategory = $("#subcategory").getValue();
    var products = $("#products").getValue();
    var record_id = $("#record_id").getValue();
    var haffari_area = $("#haffari_area").getValue();
    
    var temp = { 
        insert_date: insert_date,
        type: type,
        akhz: akhz,
        tamin_konnande: tamin_konnande,
        name: name,
        mozayede_shomare: mozayede_shomare,
        mablagh: mablagh,
        dastgahejrai: dastgahejrai,
        zemanat_nameh: zemanat_nameh,
        zemanat_nameh_2: zemanat_nameh_2,
        nahve_sherkat: nahve_sherkat,
        deadline_asnad: deadline_asnad,
        tahvil_bar: tahvil_bar,
        deadline_pasokh: deadline_pasokh,
        vahed_marbute: vahed_marbute,
        alarm: alarm,
        monagheseh_peyvast: monagheseh_peyvast,
        category: category,
        subcategory: subcategory,
        products: products,
        record_id: record_id,
        haffari_area: haffari_area
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
        $("#insert_date").setValue('');
        $("#type").setValue('');
        $("#akhz").setValue('');
        $("#tamin_konnande").setValue('');
        $("#name").setValue('');
        $("#mozayede_shomare").setValue('');
        $("#mablagh").setValue('');
        $("#dastgahejrai").setValue('');
        $("#zemanat_nameh").setValue('');
        $("#zemanat_nameh_2").setValue('');
        $("#nahve_sherkat").setValue('');
        $("#deadline_asnad").setValue('');
        $("#tahvil_bar").setValue('');
        $("#deadline_pasokh").setValue('');
        $("#vahed_marbute").setValue('');
        $("#alarm").setValue('');
        $("#monagheseh_peyvast").setValue('');
        $("#category").setValue('');
        $("#subcategory").setValue('');
        $("#products").setValue('');
        $("#record_id").setValue('');
        $("#haffari_area").setValue('');
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
    var record_id = $("#record_id").getValue();
    if (!record_id) {
        showMessage('Please select a record to update first', 'error', 5000, 'خطا');
        return;
    }
    var insert_date = $("#insert_date").getValue();
    var type = $("#type").getValue();
    var akhz = $("#akhz").getValue();
    var tamin_konnande = $("#tamin_konnande").getValue();
    var name = $("#name").getValue();
    var mozayede_shomare = $("#mozayede_shomare").getValue();
    var mablagh = $("#mablagh").getValue();
    var dastgahejrai = $("#dastgahejrai").getValue();
    var zemanat_nameh = $("#zemanat_nameh").getValue();
    var zemanat_nameh_2 = $("#zemanat_nameh_2").getValue();
    var nahve_sherkat = $("#nahve_sherkat").getValue();
    var deadline_asnad = $("#deadline_asnad").getValue();
    var tahvil_bar = $("#tahvil_bar").getValue();
    var deadline_pasokh = $("#deadline_pasokh").getValue();
    var vahed_marbute = $("#vahed_marbute").getValue();
    var alarm = $("#alarm").getValue();
    var monagheseh_peyvast = $("#monagheseh_peyvast").getValue();
    var category = $("#category").getValue();
    var subcategory = $("#subcategory").getValue();
    var products = $("#products").getValue();
    var haffari_area = $("#haffari_area").getValue();
    
    var temp = { 
        insert_date: insert_date,
        type: type,
        akhz: akhz,
        tamin_konnande: tamin_konnande,
        name: name,
        mozayede_shomare: mozayede_shomare,
        mablagh: mablagh,
        dastgahejrai: dastgahejrai,
        zemanat_nameh: zemanat_nameh,
        zemanat_nameh_2: zemanat_nameh_2,
        nahve_sherkat: nahve_sherkat,
        deadline_asnad: deadline_asnad,
        tahvil_bar: tahvil_bar,
        deadline_pasokh: deadline_pasokh,
        vahed_marbute: vahed_marbute,
        alarm: alarm,
        monagheseh_peyvast: monagheseh_peyvast,
        category: category,
        subcategory: subcategory,
        products: products,
        record_id: record_id,
        haffari_area: haffari_area
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
        $("#insert_date").setValue('');
        $("#type").setValue('');
        $("#akhz").setValue('');
        $("#tamin_konnande").setValue('');
        $("#name").setValue('');
        $("#mozayede_shomare").setValue('');
        $("#mablagh").setValue('');
        $("#dastgahejrai").setValue('');
        $("#zemanat_nameh").setValue('');
        $("#zemanat_nameh_2").setValue('');
        $("#nahve_sherkat").setValue('');
        $("#deadline_asnad").setValue('');
        $("#tahvil_bar").setValue('');
        $("#deadline_pasokh").setValue('');
        $("#vahed_marbute").setValue('');
        $("#alarm").setValue('');
        $("#monagheseh_peyvast").setValue('');
        $("#category").setValue('');
        $("#subcategory").setValue('');
        $("#products").setValue('');
        $("#record_id").setValue('');
        $("#haffari_area").setValue('');
        if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
            Xcrud.reload();
        }
    }).fail(function(xhr, status, error) {
        console.log("Update Error:", xhr.responseText, status, error);
        showMessage('Error: ' + xhr.responseText, 'error', 5000, 'خطا');
        $('#n2_ajax_loading').fadeOut();
    });
});