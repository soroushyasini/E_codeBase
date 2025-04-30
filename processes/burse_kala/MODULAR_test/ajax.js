class FormManager {
    constructor() {
        this.formFields = [
            'record_id', 'name_kala', 'tarikh_arzeh', 'moshakhasat',
            'hajm_ghabel_arzeh', 'baste_bandi', 'tolid_konnande', 'gheymat',
            'makan_tahvil', 'arz', 'tasfiyeh', 'tarikh_tahvil', 'min_arzeh',
            'min_kharid', 'max_afzayesh_arzeh', 'file'
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