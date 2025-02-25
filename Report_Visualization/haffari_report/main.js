// This will get the existing chart instance
$("#reload").find("button").click(function(){
    // This will get the existing chart instance
   
       //loadDataFromWebControl()
   });
   $("#textVar02").hide();
   $("#project").hide();
   $("#reload").hide();
   $("#gamaneh").hide();
   
   var year = $("#person_year").getValue();
   var month = $("#person_month").getValue();
   var yearmonth = year + month;
   $("#text01").setValue(yearmonth);
   
function updateOtherControl() {
    // Retrieve the selected value from the dropdown
    var selectedValue = $("#person_month").getValue();

    // Only update if a value is selected (non-empty)
    if (selectedValue !== "") {
        // Set the value of the other control (e.g., a text field)
        var year = $("#person_year").getText();
        var month = $("#person_month").getValue();
        var datePattern = year + "-" + month + "-%";
        $("#date_filter").setValue(datePattern);
    }
}

// Run the function initially in case a value is pre-selected
updateOtherControl();

// Set the onchange event of the dropdown to trigger our function
$("#person_month").setOnchange(updateOtherControl);


// This will get the existing chart instance
$("#button1").find("button").click(function(){
// This will get the existing chart instance
    populateJsonData1()});