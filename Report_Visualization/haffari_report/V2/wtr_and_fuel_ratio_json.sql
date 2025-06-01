WITH AggregatedData AS (
    SELECT 
        Gamane_name,
        SUM(drill_amount) AS drill,
        SUM(water_flt) AS water,
        SUM(gaso_flt) AS fuel,
        CASE 
            WHEN SUM(drill_amount) > 0 
            THEN ROUND(SUM(water_flt) / SUM(drill_amount), 1) 
            ELSE NULL 
        END AS wtr_ratio,
        CASE 
            WHEN SUM(drill_amount) > 0 
            THEN ROUND(SUM(gaso_flt) / SUM(drill_amount), 1) 
            ELSE NULL 
        END AS fl_ratio
    FROM prc_db_gozaresh_ruzane_copy2
    WHERE Gamane_name LIKE "RCH27"
   -- GROUP BY Gamane_name
)
SELECT 
    JSON_ARRAYAGG(
        JSON_OBJECT(
            'site', Gamane_name,
            'drill', drill,
            'water', water,
            'fuel', fuel,
            'wtr_ratio', wtr_ratio,
            'fl_ratio', fl_ratio
        )
    ) AS json_result
FROM AggregatedData;









<!-- Include Google Fonts for Persian text (Vazir font) -->
<link href="https://fonts.googleapis.com/css2?family=Vazir:wght@400;700&display=swap" rel="stylesheet">

<style>
/* General table styling */
table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Vazir', sans-serif;
    direction: ltr; /* Left-to-right for English data */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 20px 0;
    border-radius: 8px;
    overflow: hidden;
}

/* Header row */
tr.header {
    background-color: #4a90e2; /* Blue background */
    color: white;
    font-size: 1.2em;
    font-weight: bold;
}

/* Data row */
tr.data-row {
    background-color: #ffffff; /* White background for data */
}

/* Table cells */
td, th {
    padding: 12px 15px;
    text-align: center;
    border-bottom: 1px solid #e0e0e0;
}

/* Align text as needed */
th {
    text-align: center;
}
td:first-child {
    text-align: center; /* Align site to the left for readability */
}
td:nth-child(n+2) {
    text-align: center; /* Align numeric values to the right */
}

/* Hover effect on data row */
tr.data-row:hover {
    background-color: #e6f0fa; /* Light blue on hover */
    transition: background-color 0.3s ease;
}

/* Ensure table borders are clean */
th, td {
    border-right: 1px solid #e0e0e0;
}
th:last-child, td:last-child {
    border-right: none;
}

/* Responsive design for smaller screens */
@media (max-width: 600px) {
    table, th, td {
        font-size: 0.9em;
        padding: 8px;
    }
}
</style>

<div id="resourceDrillTable"></div>

<script>
//function loadDataFromWebControl() {
    try {
        // Get JSON string from the text field (replace with your field ID)
        const jsonString = $("#monthly_person_drill_json").getValue(); // Update this ID to match your field

        // Log the raw JSON string for debugging
        console.log('Raw JSON string:', jsonString);

        // Check if jsonString is empty or not a string
        if (!jsonString || typeof jsonString !== 'string') {
            throw new Error('JSON string is empty or not a string');
        }

        // Trim whitespace and check for valid JSON
        const trimmedJsonString = jsonString.trim();
        if (!trimmedJsonString.startsWith('[') || !trimmedJsonString.endsWith(']')) {
            throw new Error('JSON string does not appear to be a valid array');
        }

        // Parse the JSON
        const jsonData = JSON.parse(trimmedJsonString);

        // Verify jsonData is an array
        if (!Array.isArray(jsonData)) {
            throw new Error('Parsed JSON is not an array');
        }

        // Generate table HTML
        let tableHtml = '<table>';

        // Add header row
        tableHtml += '<tr class="header"><th>نام گمانه</th><th>سوخت مصرفی</th><th>عمق نهایی</th><th>آب مصرفی</th><th>مصرف سوخت به ازای هر متر</th><th>مصرف اب به ازای هر متر</th></tr>';

        // Add data row for the single object
        jsonData.forEach(function(entry) {
            // Validate entry structure
            if (!entry.site || entry.fuel === undefined || entry.drill === undefined || 
                entry.water === undefined || entry.fl_ratio === undefined || entry.wtr_ratio === undefined) {
                throw new Error('Invalid data structure');
            }

            tableHtml += '<tr class="data-row">';
            tableHtml += '<td>' + entry.site + '</td>';
            tableHtml += '<td>' + entry.fuel + '</td>';
            tableHtml += '<td>' + entry.drill + '</td>';
            tableHtml += '<td>' + entry.water + '</td>';
            tableHtml += '<td>' + entry.fl_ratio + '</td>';
            tableHtml += '<td>' + entry.wtr_ratio + '</td>';
            tableHtml += '</tr>';
        });

        // Close the table
        tableHtml += '</table>';

        // Insert the table into the div
        $("#resourceDrillTable").html(tableHtml);
    } catch (error) {
        console.error('Error loading data:', error.message);
        $("#resourceDrillTable").html('<p style="color: red;">Error: ' + error.message + '</p>');
    }
}

// Call the function when the Dynaform loads
$(document).ready(function() {
    // Delay slightly to ensure Dynaform fields are loaded
    setTimeout(loadDataFromWebControl, 100);
});
</script>