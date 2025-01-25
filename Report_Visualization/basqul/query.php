<?
// Execute the SQL query
$result = executeQuery("
    SELECT 
        DATE(n2_date(created_at)) AS date, 
        COUNT(id) AS record_count 
    FROM 
        prc_db_phonebook_emidco 
    GROUP BY 
        DATE(n2_date(created_at))
    ORDER BY 
        date ASC;
");

// Check if the query returned any results
if (!empty($result)) {
    // Convert the result array to JSON
    @@jsonResult = json_encode($result);
} else {
    // Handle the case where no results are returned
    @@jsonResult = json_encode(["error" => "No results found"]);
}




// Execute the SQL query
$result = executeQuery("
    SELECT 
        DATE(n2_date(created_at)) AS date, 
        COUNT(id) AS record_count 
    FROM 
        prc_db_phonebook_emidco 
    GROUP BY 
        DATE(n2_date(created_at))
    ORDER BY 
        date ASC;
");

// Check if the query returned any results
if (!empty($result)) {
    // Convert the result array to JSON
    $jsonResult = json_encode($result);
} else {
    // Handle the case where no results are returned
    $jsonResult = json_encode(["error" => "No results found"]);
}

// Output the JSON data as a JavaScript variable
echo "<script>var jsonResult = $jsonResult;</script>";






// Execute the SQL query
$result = executeQuery("
    SELECT 
        DATE(n2_date(created_at)) AS date, 
        COUNT(id) AS record_count 
    FROM 
        prc_db_phonebook_emidco 
    GROUP BY 
        DATE(n2_date(created_at))
    ORDER BY 
        date ASC;
");

// Check if the query returned any results
if (!empty($result)) {
    // Convert the result array to JSON
    $jsonResult = json_encode($result); // Correct variable assignment
} else {
    // Handle the case where no results are returned
    $jsonResult = json_encode(["error" => "No results found"]);
}

SELECT 
    DATE(n2_date(created_at)) AS date, 
    COUNT(id) AS record_count 
FROM 
    prc_db_phonebook_emidco 
GROUP BY 
    DATE(n2_date(created_at))
ORDER BY 
    date ASC;


    SELECT 
    JSON_OBJECT(
        'date', JSON_ARRAYAGG(date), -- X-axis: Dates
        'count', JSON_ARRAYAGG(record_count) -- Y-axis: Record counts
    ) AS chart_data
FROM (
    SELECT 
        DATE(n2_date(created_at)) AS date, -- Replace `created_at` with the correct column name
        COUNT(id) AS record_count 
    FROM 
        prc_db_phonebook_emidco 
    GROUP BY 
        DATE(n2_date(created_at)) -- Replace `created_at` with the correct column name
    ORDER BY 
        DATE(n2_date(created_at)) ASC -- Replace `created_at` with the correct column name
) AS subquery;


SELECT 
    JSON_ARRAYAGG(
        JSON_OBJECT(
            'date', date, 
            'count', record_count
        )
    ) AS chart_data
FROM (
    SELECT 
        DATE(n2_date(created_at)) AS date, 
        COUNT(id) AS record_count 
    FROM 
        prc_db_phonebook_emidco 
    GROUP BY 
        DATE(n2_date(created_at)) 
    ORDER BY 
        DATE(n2_date(created_at)) ASC 
) AS subquery;