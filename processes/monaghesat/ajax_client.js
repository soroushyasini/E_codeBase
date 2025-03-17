var dyn_uid1 = document.forms[0].id;
var host = PMDynaform.getHostName();
var ws = PMDynaform.getWorkspaceName();
var token = PMDynaform.getAccessToken(); 
var app_uid = PMDynaform.getProjectKeys().caseUID;
var trig_uid = "77124592967cd594ad6add1031134547"; // Your trigger UID

$('#record_id').hide();
hideArrow();
appendAjaxLoading();