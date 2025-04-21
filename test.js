function hideSpecificColumns() {
    $("#panel_grid .panel-body table th:nth-child(2), #panel_grid .panel-body table td:nth-child(2), " +    // نوع نامه (Letter Type) - possibly a mistake?
        "#panel_grid .panel-body table th:nth-child(10), #panel_grid .panel-body table td:nth-child(10), " + // سازمان (Organization)
        "#panel_grid .panel-body table th:nth-child(1), #panel_grid .panel-body table td:nth-child(1), " +
        "#panel_grid .panel-body table th:nth-child(11), #panel_grid .panel-body table td:nth-child(11), " + // شماره نامه ورودی (Input Letter Number)
        "#panel_grid .panel-body table th:nth-child(12), #panel_grid .panel-body table td:nth-child(12)")    // تاریخ نامه ورودی (Input Letter Date)
        .css("display", "none");
}
