libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('prc_db_burse_kala');
// Define columns in the same order as frontend edit_record
$xcrud->columns('id, name_kala, tarikh_arzeh, moshakhasat, hajm_ghabel_arzeh, baste_bandi, tolid_konnande, gheymat, makan_tahvil, arz, tasfiyeh, tarikh_tahvil, min_arzeh, min_kharid, max_afzayesh_arzeh');
$xcrud->label('name_kala', 'نام کالا');
$xcrud->label('tarikh_tahvil', 'تاریخ تحویل');
$xcrud->label('moshakhasat', 'مشخصات');
$xcrud->label('hajm_ghabel_arzeh', 'حجم قابل عرضه');
$xcrud->label('baste_bandi', 'بسته‌بندی');
$xcrud->label('tolid_konnande', 'تولیدکننده');
$xcrud->label('gheymat', 'قیمت');
$xcrud->label('makan_tahvil', 'مکان تحویل');
$xcrud->label('arz', 'ارز');
$xcrud->label('tasfiyeh', 'تسویه');
$xcrud->label('tarikh_tahvil', 'تاریخ تحویل');
$xcrud->label('min_arzeh', 'حداقل عرضه');
$xcrud->label('min_kharid', 'حداقل خرید');
$xcrud->label('max_afzayesh_arzeh', 'حداکثر افزایش عرضه');

// Add a custom Edit button with inline JavaScript call, matching frontend order
$xcrud->button(
    "javascript:edit_record('{id}', '{name_kala}', '{tarikh_arzeh}', '{moshakhasat}', '{hajm_ghabel_arzeh}', '{baste_bandi}', '{tolid_konnande}','{gheymat}', '{makan_tahvil}', '{arz}', '{tasfiyeh}', '{tarikh_tahvil}', '{min_arzeh}', '{min_kharid}', '{max_afzayesh_arzeh}', '{file}', '{file_name}');",
    'Edit',
    'glyphicon glyphicon-edit',
    'btn btn-default btn-sm'
);

// Hide default edit button
$xcrud->unset_edit();
$xcrud->unset_remove(false);
$xcrud->order_by('id', 'desc');
@@panel_grid = $xcrud->render();









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
function loadFromWebControl() {
    try {
        // Get JSON string from the text field (replace with your field ID)
        const jsonString = $("#ratio_json").getValue(); // Update this ID to match your field

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
        $("#ratio_json").html(tableHtml);
    } catch (error) {
        console.error('Error loading data:', error.message);
        $("#ratio_json").html('<p style="color: red;">Error: ' + error.message + '</p>');
    }
}

// Call the function when the Dynaform loads
//$(document).ready(function() {
    // Delay slightly to ensure Dynaform fields are loaded
//    setTimeout(loadFromWebControl, 100);
//});
</script>