// Function to update the textbox based on dropdown value
function updateTextBox(newVal, oldVal) {
    if (newVal == 'فیزیکی') {
        $("#alarm").setValue("سه روز");
    } else if (newVal == 'آنلاین') {
        $("#alarm").setValue("یک روز");
    }
}
$('#record_id').hide();
// Execute when the Dynaform loads
$(document).ready(function() {
    // Check the initial value of the dropdown and update the textbox accordingly
    if ($("#nahve_sherkat").getValue() != '') {
        updateTextBox($("#nahve_sherkat").getValue(), '');
    }
});

// Execute when the dropdown value changes
$("#nahve_sherkat").setOnchange(updateTextBox);

// Hide the "tamin_konnande" field by default
$("#tamin_konnande").hide();

// Add an onchange event listener to the "akhz" dropdown
$("#akhz").setOnchange(function(newVal, oldVal) {
    if (newVal == "تامین کنندگان") { 
        // Show the "tamin_konnande" field if the selected value is "تامین کنندگان"
        $("#tamin_konnande").show();
    } else {
        // Hide the "tamin_konnande" field for any other value
        $("#tamin_konnande").hide();
    }
});

// Hide fields by default
$("#haffari_area").hide();
$("#category").hide();
$("#subcategory").hide();
$("#products").hide();

// Add an onchange event listener to the "vahed_marbute" dropdown
$("#vahed_marbute").setOnchange(function(newVal, oldVal) {
    if (newVal == "بازرگانی") { 
        $("#category").show();
        $("#subcategory").show();
        $("#products").show();
    } else {
        $("#category").hide();
        $("#subcategory").hide();
        $("#products").hide();
    }
    if (newVal == "حفاری" || newVal == "معدنی") {
        $("#haffari_area").show();
    } else {
        $("#haffari_area").hide();
    }
});



var dyn_uid1 = document.forms[0].id;
var host = PMDynaform.getHostName();
var ws = PMDynaform.getWorkspaceName();
var token = PMDynaform.getAccessToken();
var app_uid = PMDynaform.getProjectKeys().caseUID;
var trig_uid = "920488428680cb2ecac83e1088904180"; // Your trigger UID

// Configuration: Define fields
const fields = [
    { name: 'akhz', type: 'text' },
    { name: 'alarm', type: 'text' },
    { name: 'dastgahejrai', type: 'text' },
    { name: 'deadline_asnad', type: 'date' },
    { name: 'deadline_pasokh', type: 'date' },
    { name: 'mablagh', type: 'text' },
    { name: 'mozayede_shomare', type: 'text' },
    { name: 'nahve_sherkat', type: 'text' },
    { name: 'name', type: 'text' },
    { name: 'tahvil_bar', type: 'text' },
    { name: 'type', type: 'text' },
    { name: 'zemanat_nameh', type: 'text' },
    { name: 'zemanat_nameh_2', type: 'text' },
    { name: 'vahed_marbute', type: 'text' },
    { name: 'tamin_konnande', type: 'text' },
    { name: 'insert_date', type: 'text' },
    { name: 'category', type: 'text' },
    { name: 'subcategory', type: 'text' },
    { name: 'products', type: 'text' },
    { name: 'haffari_area', type: 'text' },
    { name: 'vaze_tamdid', type: 'text' },
    { name: 'vaze_sherkat', type: 'text' }
];

const fileFields = [
    { name: 'file', nameField: 'file_name' },
    { name: 'file_nahaee', nameField: 'file_name_nahaee' }
];

// Initialize form
function initializeForm() {
    hideArrow();
    appendAjaxLoading();
    $("#record_id").hide();
    $("#search_records").hide();
}

// Set form values for editing
function editRecord(data) {
    console.log("Editing record:", data);
    try {
        $("#record_id").setValue(data.record_id || '');

        fields.forEach(field => {
            const value = data[field.name] || '';
            if (field.type === 'date') {
                $(`#${field.name}`).setText(value === "0000-00-00 00:00:00" ? '' : value);
                $(`#${field.name}`).setValue(value === "0000-00-00 00:00:00" ? '' : value);
            } else {
                $(`#${field.name}`).setValue(value);
            }
        });

        fileFields.forEach(fileField => {
            const file = data[fileField.name];
            const fileName = data[fileField.nameField];
            if (file && file !== '' && fileName) {
                const temp = [{
                    appDocUid: file,
                    name: fileName,
                    version: 1,
                    size: 1024
                }];
                getFieldById(fileField.name).createFormMultiFile(temp, 0);
            }
        });

        console.log("Form values set:", {
            record_id: $("#record_id").getValue(),
            ...fields.reduce((acc, field) => {
                acc[field.name] = field.type === 'date' ? $(`#${field.name}`).getText() : $(`#${field.name}`).getValue();
                return acc;
            }, {}),
            file: getFieldById('file').model.getData().value[0]?.appDocUid || '',
            file_name: getFieldById('file').model.getData().value[0]?.name || '',
            file_nahaee: getFieldById('file_nahaee').model.getData().value[0]?.appDocUid || '',
            file_name_nahaee: getFieldById('file_nahaee').model.getData().value[0]?.name || ''
        });
    } catch (e) {
        console.error("Error setting form values:", e);
    }
}

// Collect form data
function collectFormData() {
    const data = { record_id: $("#record_id").getValue() };

    fields.forEach(field => {
        data[field.name] = field.type === 'date' ? $(`#${field.name}`).getText() : $(`#${field.name}`).getValue();
    });

    fileFields.forEach(fileField => {
        const fileValue = getFieldById(fileField.name).model.getData().value;
        data[fileField.name] = fileValue[0]?.appDocUid || '';
        data[fileField.nameField] = fileValue[0]?.name || '';
    });

    return data;
}

// Clear form
function clearForm() {
    $("#record_id").setValue('');
    fields.forEach(field => {
        if (field.type === 'date') {
            $(`#${field.name}`).setText('');
            $(`#${field.name}`).setValue('');
        } else {
            $(`#${field.name}`).setValue('');
        }
    });
    fileFields.forEach(fileField => {
        $(`#${fileField.name}`).find("a.fa-trash").click();
    });
}

// Send AJAX request
function sendAjaxRequest(reqType, data, successMessage) {
    console.log(`${reqType} with data:`, data);
    $('#n2_ajax_loading').fadeIn();

    $.ajax({
        type: 'PUT',
        url: `${host}/api/1.0/${ws}/cases/${app_uid}/execute-trigger/${trig_uid}?AJAX=1&REQ_TYPE=${reqType}`,
        data: data,
        beforeSend: function(xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + token);
        }
    }).done(function(msg) {
        console.log(`${reqType} response:`, msg);
        $('#n2_ajax_loading').fadeOut();
        showMessage(msg.message, 'success', 5000, 'موفقیت');
        clearForm();
        if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
            Xcrud.reload();
        }
    }).fail(function(xhr, status, error) {
        console.error(`${reqType} Error:`, xhr.responseText, status, error);
        $('#n2_ajax_loading').fadeOut();
        let errorMsg = 'Unknown error occurred';
        try {
            const response = JSON.parse(xhr.responseText);
            errorMsg = response.message || response.error || errorMsg;
        } catch (e) {
            errorMsg = xhr.responseText || errorMsg;
        }
        showMessage(errorMsg, 'error', 5000, 'خطا');
    });
}

// Event handlers
$("#create_record").find("button").click(function() {
    const data = collectFormData();
    // Add validation if needed
    sendAjaxRequest('save_to_db', data, 'با موفقیت ثبت شد');
});

$("#button_update").find("button").click(function() {
    const data = collectFormData();
    if (!data.record_id) {
        showMessage('لطفاً ابتدا یک رکورد را برای به‌روزرسانی انتخاب کنید', 'error', 5000, 'خطا');
        return;
    }
    sendAjaxRequest('update_record', data, 'ریکورد با موفقیت به روز رسانی شد');
});

// Initialize
initializeForm();