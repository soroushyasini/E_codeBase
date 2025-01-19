<?


// $names = @@result; // Fetch the result from the query
// $options = [];

// foreach ($names as $name) {
//     $options[] = [
//         'value' => $name['komak_haffar'],
//         'label' => $name['komak_haffar']
//     ];
// }

// @=selected_names_options = json_encode($options); // Store the options in a variable

// $komakHaffar = @=komak_haffar_checkboxgroup; // Get selected names from the Checkbox Group
// $namesString = implode(',', $komakHaffar); // Convert array to comma-separated string

// // Insert into the database
// $query = "INSERT INTO prc_db_test_gozaresh (komak_haffar) VALUES ('$namesString')";
// executeQuery($query);

// Get selected values from each Checkbox Group
$sarparastSelected = @=sarparast_checkgroup;
$negahbanSelected = @=negahban_checkgroup;
$zaminshenasSelected = @=zaminshenas_checkgroup;
$driverSelected = @=driver_checkgroup;
$sarHaffarSelected = @=sar_haffar_checkgroup;
$haffarSelected = @=haffar_checkgroup;
$kargarSelected = @=kargar_checkgroup;
$komakHaffarSelected = @=komak_haffar_checkgroup;

// Convert selected values to comma-separated strings
$sarparastString = implode(',', $sarparastSelected);
$negahbanString = implode(',', $negahbanSelected);
$zaminshenasString = implode(',', $zaminshenasSelected);
$driverString = implode(',', $driverSelected);
$sarHaffarString = implode(',', $sarHaffarSelected);
$haffarString = implode(',', $haffarSelected);
$kargarString = implode(',', $kargarSelected);
$komakHaffarString = implode(',', $komakHaffarSelected);

// Insert into the database
$query = "INSERT INTO prc_test_production_table (
    sarparast, negahban, zaminshenas, driver, sar_haffar, haffar, kargar, komak_haffar
) VALUES (
    '$sarparastString', '$negahbanString', '$zaminshenasString', '$driverString', 
    '$sarHaffarString', '$haffarString', '$kargarString', '$komakHaffarString'
);";
executeQuery($query);