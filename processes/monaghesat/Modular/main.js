var dyn_uid1 = document.forms[0].id;
var host = PMDynaform.getHostName();
var ws = PMDynaform.getWorkspaceName();
var token = PMDynaform.getAccessToken(); 
var app_uid = PMDynaform.getProjectKeys().caseUID;
var trig_uid = "5336943216808a977a23b53093799957"; // Your trigger UID
hideArrow();
appendAjaxLoading();



console.log("Searching table with:", {
    id, insert_date, type, akhz, tamin_konnande, name, mozayede_shomare, mablagh, 
    dastgahejrai, zemanat_nameh, zemanat_nameh_2, nahve_sherkat, deadline_asnad, 
    tahvil_bar, deadline_pasokh, vahed_marbute, monagheseh_peyvast, category, 
    subcategory, products, haffari_area
});



class FormManager {
    constructor() {
        this.formFields = [
'id', 'insert_date', 'type', 'akhz', 'tamin_konnande', 'name', 'mozayede_shomare', 'mablagh',
'dastgahejrai', 'zemanat_nameh', 'zemanat_nameh_2', 'nahve_sherkat', 'deadline_asnad',
'tahvil_bar', 'deadline_pasokh', 'vahed_marbute', 'monagheseh_peyvast', 'category',
'subcategory', 'products', 'haffari_area', 'file'

        ];
    }

    resetForm() {
        this.formFields.forEach(field => {
            if (field !== 'file') {
                $(`#${field}`).setValue('').setText('');
            }
        });
        getFieldById('file').model.setData({ value: [] });
    }

    editRecord(data) {
        try {
            this.formFields.forEach(field => {
                if (field !== 'file' && data[field] !== undefined) {
                    $(`#${field}`).setValue(data[field] || '');
                    if (['tarikh_arzeh', 'tarikh_tahvil'].includes(field)) {
                        $(`#${field}`).setText(data[field] || '');
                    }
                }
            });
            if (data.file && data.file_name) {
                const fileData = [{
                    appDocUid: data.file,
                    name: data.file_name,
                    version: 1,
                    size: 1024
                }];
                getFieldById('file').createFormMultiFile(fileData, 0);
            }
        } catch (e) {
            console.error('Error setting form values:', e);
        }
    }

    submitForm(action, data) {
        const url = `${PMDynaform.getHostName()}/api/1.0/${PMDynaform.getWorkspaceName()}/cases/${PMDynaform.getProjectKeys().caseUID}/execute-trigger/97129461467f75d4d73b3d3065894189?AJAX=1&REQ_TYPE=${action}`;
        $('#n2_ajax_loading').fadeIn();
        return $.ajax({
            type: 'PUT',
            url,
            data,
            beforeSend: xhr => xhr.setRequestHeader('Authorization', `Bearer ${PMDynaform.getAccessToken()}`)
        }).done(msg => {
            showMessage(msg.message, msg.message.includes('Error') ? 'error' : 'success', 5000, msg.message.includes('Error') ? 'خطا' : 'موفقیت');
            if (!msg.message.includes('Error')) {
                this.resetForm();
                if (typeof Xcrud !== 'undefined' && typeof Xcrud.reload === 'function') {
                    Xcrud.reload();
                }
            }
        }).fail((xhr, status, error) => {
            let errorMsg = xhr.responseText || 'Unknown error occurred';
            try {
                errorMsg = JSON.parse(xhr.responseText).message || errorMsg;
            } catch (e) {}
            showMessage(errorMsg, 'error', 5000, 'خطا');
        }).always(() => $('#n2_ajax_loading').fadeOut());
    }
}

// Usage
const formManager = new FormManager();
$("#create_record").find("button").click(() => {
    const data = formManager.formFields.reduce((obj, field) => {
        obj[field] = field === 'file' ? getFieldById('file').model.getData().value[0]?.appDocUid || '' : $(`#${field}`).getValue();
        if (['tarikh_arzeh', 'tarikh_tahvil'].includes(field)) {
            obj[field] = $(`#${field}`).getText();
        }
        return obj;
    }, {});
    data.file_name = getFieldById('file').model.getData().value[0]?.name || '';
    formManager.submitForm('save_to_db', data);
});

$("#button_update").find("button").click(() => {
    const data = formManager.formFields.reduce((obj, field) => {
        obj[field] = field === 'file' ? getFieldById('file').model.getData().value[0]?.appDocUid || '' : $(`#${field}`).getValue();
        if (['tarikh_arzeh', 'tarikh_tahvil'].includes(field)) {
            obj[field] = $(`#${field}`).getText();
        }
        return obj;
    }, {});
    data.file_name = getFieldById('file').model.getData().value[0]?.name || '';
    formManager.submitForm('update_record', data);
});