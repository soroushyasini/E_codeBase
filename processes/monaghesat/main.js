// Function to update the textbox based on dropdown value
function updateTextBox(newVal, oldVal) {
  if (newVal == 'فیزیکی') {
      $("#alarm").setValue("سه روز");
  } else if (newVal == 'آنلاین') {
      $("#alarm").setValue("یک روز");
  }
}

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
$("#haffari_area").hide();
$("#category").hide();
$("#subcategory").hide();
$("#products").hide();
$("#vahed_marbute").setOnchange(function(newVal, oldVal) {
if (newVal == "بازرگانی") { 
  $("#category").show();
  $("#subcategory").show();
  $("#products").show();
} else {
  // Hide the "tamin_konnande" field for any other value
  $("#category").hide();
  $("#subcategory").hide();
  $("#products").hide();
}
 if (newVal == "حفاری") {
    $("#haffari_area").show();
 } else { 
   $("#haffari_area").hide();
 }
});

