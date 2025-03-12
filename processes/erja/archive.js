// Global variables (assuming these are needed elsewhere)
var dyn_uid1 = document.forms[0].id;
var step_mode1 = getFormById(dyn_uid1).model.attributes.mode;
var host = PMDynaform.getHostName();

// Function to hide specific columns (adjusted indices based on your comment)
function hideSpecificColumns() {
    $("#panel_grid .panel-body table th:nth-child(2), #panel_grid .panel-body table td:nth-child(2), " +    // نوع نامه (Letter Type) - possibly a mistake?
        "#panel_grid .panel-body table th:nth-child(10), #panel_grid .panel-body table td:nth-child(10), " + // سازمان (Organization)
        "#panel_grid .panel-body table th:nth-child(11), #panel_grid .panel-body table td:nth-child(11), " + // شماره نامه ورودی (Input Letter Number)
        "#panel_grid .panel-body table th:nth-child(12), #panel_grid .panel-body table td:nth-child(12)")    // تاریخ نامه ورودی (Input Letter Date)
        .css("display", "none");
}

// Function to hide rows with "نامه خروجی" or "نامه ورودی"
function hideNonInternalLetters() {
    $("#panel_grid .panel-body table tr.xcrud-row").each(function() {
        var letterType = $(this).find("td:nth-child(2)").text().trim();
        if (letterType === "نامه خروجی" || letterType === "نامه ورودی") {
            $(this).hide();
        }
    });
}

// Consolidated initialization and observer setup
$(document).ready(function() {
    // Initial setup functions
    function initializeUI() {
        hideSpecificColumns();
        hideNonInternalLetters();
        hideArrow(); // Assuming this is defined elsewhere
        appendAjaxLoading(); // Assuming this is defined elsewhere
        dropdown_letter_type(); // Assuming this is defined elsewhere

        // Style buttons
        $("#form\\[button_search\\]").addClass("btn-success");
        $("#form\\[button_delete_search\\]").addClass("btn-danger");

        // Trigger initial search
        search_letter();

        // Bind delete search button
        $("#button_delete_search").click(function() {
            delete_search(); // Assuming this is defined elsewhere
        });

        // Add advanced search toggle icons
        $("#subtitle_advanced span").prepend(
            '<span class="glyphicon glyphicon-chevron-down downAdvanced" onclick="HideAdvanced()"></span>' +
            '<span class="glyphicon glyphicon-chevron-left leftAdvanced" onclick="ShowAdvanced()"></span>'
        );
        HideAdvanced(); // Assuming this is defined elsewhere
        $(".glyphicon").css("cursor", "pointer");

        // Execute hidden JS if present
        if ($('#hidden_js').getValue() !== '') {
            eval($('#hidden_js').getValue()); // Be cautious with eval
        }
    }

    // Run initial setup
    initializeUI();

    // Single MutationObserver for all dynamic updates
    var target = document.querySelector("#panel_grid .panel-body");
    if (target) {
        var observer = new MutationObserver(function(mutations) {
            hideSpecificColumns();    // Re-hide columns
            hideNonInternalLetters(); // Re-hide rows
        });
        observer.observe(target, { childList: true, subtree: true });
    } else {
        console.warn("Target '#panel_grid .panel-body' not found! Adjust the selector if needed.");
    }
});