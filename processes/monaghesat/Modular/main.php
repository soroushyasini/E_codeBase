<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Placeholder for ProcessMaker document validation
function checkDocumentExists($file) {
    // Implement ProcessMaker-specific document check (e.g., verify file in storage)
    return !empty($file);
}

// Configuration: Define fields for reuse
$fields = [
    'akhz', 'alarm', 'dastgahejrai', 'deadline_asnad', 'deadline_pasokh', 'mablagh',
    'mozayede_shomare', 'nahve_sherkat', 'name', 'tahvil_bar', 'type', 'zemanat_nameh',
    'zemanat_nameh_2', 'vahed_marbute', 'tamin_konnande', 'insert_date', 'category',
    'subcategory', 'products', 'haffari_area', 'file', 'file_name', 'vaze_tamdid',
    'vaze_sherkat', 'file_nahaee', 'file_name_nahaee'
];

// Sanitize input data
function sanitizeInput($data) {
    $sanitized = [];
    foreach ($data as $key => $value) {
        if (!is_array($value)) {
            $sanitized[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        } else {
            $sanitized[$key] = $value;
        }
    }
    return $sanitized;
}

// Extract and validate input data
function extractInput($inputData, $fields) {
    $extracted = [];
    foreach ($fields as $field) {
        $extracted[$field] = isset($inputData[$field]) ? trim($inputData[$field]) : '';
    }
    return $extracted;
}

// Validate files
function validateFiles($data, $fileFields = ['file', 'file_nahaee']) {
    foreach ($fileFields as $field) {
        if (!empty($data[$field]) && !checkDocumentExists($data[$field])) {
            return ['message' => 'فایل مشخص‌شده یافت نشد: ' . $field, 'error' => true];
        }
    }
    return [];
}

// Build SQL values for insert/update
function buildSqlValues($data, $fields) {
    $values = [];
    foreach ($fields as $field) {
        $values[] = "'" . addslashes($data[$field]) . "'";
    }
    return $values;
}

// Insert record into database
function insertRecord($data, $fields, $table = 'prc_db_mozayedat_monaghesat') {
    try {
        $columns = implode(', ', $fields);
        $values = implode(', ', buildSqlValues($data, $fields));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        
        $result = executeQuery($sql);
        
        if ($result) {
            error_log("Record inserted successfully: name={$data['name']}");
            return ['message' => 'با موفقیت ثبت شد'];
        } else {
            error_log("Insert failed: SQL=$sql");
            return ['message' => 'خطا در ثبت: نتیجه نامعتبر', 'error' => true];
        }
    } catch (Exception $e) {
        error_log("Insert error: " . $e->getMessage() . " | SQL: $sql");
        return ['message' => 'خطا در ثبت: ' . $e->getMessage(), 'error' => true];
    }
}

// Update record in database
function updateRecord($data, $fields, $record_id, $table = 'prc_db_mozayedat_monaghesat') {
    try {
        $updates = [];
        foreach ($fields as $field) {
            $updates[] = "$field = '" . addslashes($data[$field]) . "'";
        }
        $updateStr = implode(', ', $updates);
        $sql = "UPDATE $table SET $updateStr WHERE id = '" . addslashes($record_id) . "'";
        
        $result = executeQuery($sql);
        
        if ($result) {
            error_log("Record updated successfully: id=$record_id, name={$data['name']}");
            return ['message' => 'ریکورد با موفقیت به روز رسانی شد'];
        } else {
            error_log("Update failed: SQL=$sql");
            return ['message' => 'خطا در به‌روزرسانی: نتیجه نامعتبر', 'error' => true];
        }
    } catch (Exception $e) {
        error_log("Update error: " . $e->getMessage() . " | SQL: $sql");
        return ['message' => 'خطا در به‌روزرسانی: ' . $e->getMessage(), 'error' => true];
    }
}

// Handle save to database
function handleSaveToDb($inputData, $fields) {
    $data = extractInput($inputData, $fields);
    
    // Validate files
    $fileValidation = validateFiles($data);
    if (!empty($fileValidation)) {
        return $fileValidation;
    }
    
    return insertRecord($data, $fields);
}

// Handle update record
function handleUpdateRecord($inputData, $fields) {
    $record_id = isset($inputData['record_id']) ? trim($inputData['record_id']) : '';
    if (empty($record_id)) {
        return ['message' => 'شناسه رکورد نامعتبر است', 'error' => true];
    }
    
    $data = extractInput($inputData, $fields);
    
    // Validate files
    $fileValidation = validateFiles($data);
    if (!empty($fileValidation)) {
        return $fileValidation;
    }
    
    return updateRecord($data, $fields, $record_id);
}

// Main request handler
function handleRequest($fields) {
    ob_end_clean();
    $output = [];
    
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        $output['error'] = 'روش درخواست نامعتبر است';
        header('Content-type: application/json');
        echo G::json_encode($output);
        die();
    }
    
    // Parse PUT request data
    parse_str(file_get_contents("php://input"), $post_vars);
    
    // Sanitize inputs
    try {
        $post_vars = sanitizeInput($post_vars);
        $_GET = sanitizeInput($_GET);
    } catch (Exception $e) {
        $output['error'] = 'خطا در پردازش پارامترها: ' . $e->getMessage();
        header('Content-type: application/json');
        echo G::json_encode($output);
        die();
    }
    
    // Handle request type
    switch ($_GET['REQ_TYPE'] ?? '') {
        case "save_to_db":
            $output = handleSaveToDb($post_vars, $fields);
            break;
        case "update_record":
            $output = handleUpdateRecord($post_vars, $fields);
            break;
        default:
            $output['error'] = 'نوع درخواست نامعتبر است';
    }
    
    header('Content-type: application/json');
    echo G::json_encode($output);
    die();
}

// Execute main handler
handleRequest($fields);
?>