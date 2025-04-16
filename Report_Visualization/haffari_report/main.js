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


$(document).ready(function() {
  // Move controls to Tab 1 (مشخصات)
  $("#tab1").append($("#project").closest(".form-group"));
  $("#tab1").append($("#gamaneh").closest(".form-group"));
  $("#tab1").append($("#reload").closest(".form-group"));
  $("#tab1").append($("#subtitle1").closest(".form-group"));
  $("#tab1").append($("#panel2").closest(".form-group"));
  $("#tab1").append($("#json_parse_shift").closest(".form-group"));

  // Move controls to Tab 2 (وضعیت)
  $("#tab2").append($("#person_year").closest(".form-group"));
  $("#tab2").append($("#person_month").closest(".form-group"));
  $("#tab2").append($("#reload_2").closest(".form-group"));
  $("#tab2").append($("#subtitle0000000002").closest(".form-group"));
  $("#tab2").append($("#panel3").closest(".form-group"));

  // Move controls to Tab 3 (مستندات)
  $("#tab3").append($("#image1").closest(".form-group"));


  // Initialize Bootstrap tabs
  $('#myTab a').on('click', function (e) {
    e.preventDefault();
    $(this).tab('show');
  });

  // Set the first tab as active
  $('#myTab a:first').tab('show');
});