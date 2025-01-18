$names = @@result; // Fetch the result from the query
$options = [];

foreach ($names as $name) {
    $options[] = [
        'value' => $name['komak_haffar'],
        'label' => $name['komak_haffar']
    ];
}

@=selected_names_options = json_encode($options); // Store the options in a variable

$selectedNames = @=selected_names; // Get selected names from the Checkbox Group
$namesString = implode(',', $selectedNames); // Convert array to comma-separated string

// Insert into the database
$query = "INSERT INTO prc_db_test_gozaresh (komak_haffar) VALUES ('$namesString')";
executeQuery($query);