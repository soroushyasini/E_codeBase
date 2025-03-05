var dyn_uid1 = document.forms[0].id;
var step_mode1 = getFormById(dyn_uid1).model.attributes.mode;
var host = PMDynaform.getHostName();
function hideInternalLetters() {
    $("#panel_grid .panel-body table tr.xcrud-row").each(function() {
        var letterType = $(this).find("td:nth-child(2)").text().trim();
        if (letterType === "نامه داخلی") {
            $(this).hide();
        }
    });
}

$("document").ready( function(){
  	hideInternalLetters();
	hideArrow();
  	var target = document.querySelector("#panel_grid .panel-body");
    var observer = new MutationObserver(function() {
        hideInternalLetters();
    });
    observer.observe(target, { childList: true, subtree: true });
	appendAjaxLoading();
	dropdown_letter_type();
	
	$("#form\\[button_search\\]").addClass("btn-success");
	$("#form\\[button_delete_search\\]").addClass("btn-danger");
	
	$("#button_search").click(function(){ search_letter(); });
	$("#button_delete_search").click(function(){ delete_search(); });
	
	$("#subtitle_advanced span").prepend('<span class="glyphicon glyphicon-chevron-down downAdvanced" onclick="HideAdvanced()"></span><span class="glyphicon glyphicon-chevron-left leftAdvanced" onclick="ShowAdvanced()"></span>')
	HideAdvanced();
	$(".glyphicon").css("cursor","pointer");
	
	if($('#hidden_js').getValue() != '')
		eval($('#hidden_js').getValue());
});

