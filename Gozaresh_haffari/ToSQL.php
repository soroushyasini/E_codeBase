<?


// Get selected values from each Checkbox Group
$sarparastSelected = @=sarparast_checkgroup;
$negahbanSelected = @=negahban_checkgroup;
$zaminshenasSelected = @=zaminshenas_checkgroup;
$driverSelected = @=driver_checkgroup;
$sarHaffarSelected = @=sar_haffar_checkgroup;
$haffarSelected = @=haffar_checkgroup;
$kargarSelected = @=kargar_checkgroup;
$komakHaffarSelected = @=komak_haffar_checkgroup;
$check_1_6_checkgroupSelected = @=check_1_6_checkgroup;
$check_1_6_checkgroupSelected = @=check_7_14_checkgroup;
// get the data from form, the groups are excluded : 
$Projects = @@Projects;
$form_serial_number_str = @@form_serial_number_str;
$Gamane_name = @@Gamane_name;
$dastgah_name = @@dastgah_name;
$shift = @@shift;
$form_date = @@form_date;
$dastgah_saat = @@dastgah_saat;
$start_our_str = @@start_our_str;
$end_our_str = @@end_our_str;
$drill_start_flt = @@drill_start_flt;
$drill_end_flt = @@drill_end_flt;
$drill_amount = @@drill_amount;
$corebox_start_int = @@corebox_start_int;
$corebox_end_int = @@corebox_end_int;
$corebox_amount = @@corebox_amount;
$water_flt = @@water_flt;
$gaso_flt = @@gaso_flt;
$oil_flt = @@oil_flt;
$supermix_flt = @@supermix_flt;
$bentonite_flt = @@bentonite_flt;
$aux_kargar = @@aux_kargar;
$aux_komak_haffar = @@aux_komak_haffar;
$list_vorudi = @@list_vorudi;
$list_khruji = @@list_khruji;
$text_checkbox_tozihat = @@text_checkbox_tozihat;
$text_sharh_haffari = @@text_sharh_haffari;

// Convert selected values to comma-separated strings
$sarparastString = implode(',', $sarparastSelected);
$negahbanString = implode(',', $negahbanSelected);
$zaminshenasString = implode(',', $zaminshenasSelected);
$driverString = implode(',', $driverSelected);
$sarHaffarString = implode(',', $sarHaffarSelected);
$haffarString = implode(',', $haffarSelected);
$kargarString = implode(',', $kargarSelected);
$komakHaffarString = implode(',', $komakHaffarSelected);
$check_1_6_checkgroupString = implode(',', $check_1_6_checkgroupSelected)
$check_7_14_checkgroupString = implode(',', $check_7_14_checkgroupSelected)
// Insert into the database
$query = "INSERT INTO prc_test_production_table (
    id, Projects, form_serial_number_str, Gamane_name, dastgah_name, shift, form_date, 
    dastgah_saat, start_our_str, end_our_str, drill_start_flt, drill_end_flt, 
    drill_amount, corebox_start_int, corebox_end_int, corebox_amount, water_flt, 
    gaso_flt, oil_flt, supermix_flt, bentonite_flt, sarparast, negahban, zaminshenas, 
    driver, sar_haffar, haffar, kargar, komak_haffar, aux_kargar, aux_komak_haffar, 
    list_vorudi, list_khruji, text_checkbox_tozihat, text_sharh_haffari, 
    check_1_6_checkgroup, check_7_14_checkgroup
) VALUES (
    NULL, '$Projects', '$form_serial_number_str', '$Gamane_name', '$dastgah_name', '$shift', DATE(n2_date('$form_date')), 
    '$dastgah_saat', '$start_our_str', '$end_our_str', $drill_start_flt, $drill_end_flt, 
    $drill_amount, $corebox_start_int, $corebox_end_int, $corebox_amount, $water_flt, 
    $gaso_flt, $oil_flt, $supermix_flt, $bentonite_flt, '$sarparastString', '$negahbanString', '$zaminshenasString', 
    '$driverString', '$sarHaffarString', '$haffarString', '$kargarString', '$komakHaffarString', 
    '$aux_kargar', '$aux_komak_haffar', '$list_vorudi', '$list_khruji', '$text_checkbox_tozihat', 
    '$text_sharh_haffari', '$check_1_6_checkgroupString', '$check_7_14_checkgroupString'
);";
executeQuery($query);





// Clear the session variables
unset(@@Projects);
unset(@@form_serial_number_str);
unset(@@Gamane_name);
unset(@@dastgah_name);
unset(@@shift);
unset(@@form_date);
unset(@@dastgah_saat);
unset(@@start_our_str);
unset(@@end_our_str);
unset(@@drill_start_flt);
unset(@@drill_end_flt);
unset(@@drill_amount);
unset(@@corebox_start_int);
unset(@@corebox_end_int);
unset(@@corebox_amount);
unset(@@water_flt);
unset(@@gaso_flt);
unset(@@oil_flt);
unset(@@supermix_flt);
unset(@@bentonite_flt);
unset(@@aux_kargar);
unset(@@aux_komak_haffar);
unset(@@list_vorudi);
unset(@@list_khruji);
unset(@@text_checkbox_tozihat);
unset(@@text_sharh_haffari);