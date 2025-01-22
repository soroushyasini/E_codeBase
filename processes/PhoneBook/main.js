$("#shakhsiyat").setOnchange(function(newVal, oldVal) {
    if (newVal == "حقیقی") { 
      // Show the "tamin_konnande" field if the selected value is "تامین کنندگان"
      $("#dakheli").hide();
      $("#semat").hide();
      $("#contact_person").hide();
      $("#dakheli").hide();
    } else {
      // Hide the "tamin_konnande" field for any other value
      $("#dakheli").show();
      $("#semat").show();
      $("#contact_person").show();
      $("#dakheli").show();
    }
  });