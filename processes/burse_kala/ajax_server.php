
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Placeholder for ProcessMaker document validation (implement as needed)
function checkDocumentExists($file) {
    // Implement ProcessMaker-specific document check (e.g., verify file in storage)
    // For now, assume valid if non-empty
    return !empty($file);
}

function save_to_db($inputData) {
    $output = [];

    // Extract input data
    $name_kala = isset($inputData['name_kala']) ? trim($inputData['name_kala']) : '';
    $tarikh_arzeh = isset($inputData['tarikh_arzeh']) ? trim($inputData['tarikh_arzeh']) : '';
    $moshakhasat = isset($inputData['moshakhasat']) ? trim($inputData['moshakhasat']) : '';
    $hajm_ghabel_arzeh = isset($inputData['hajm_ghabel_arzeh']) ? trim($inputData['hajm_ghabel_arzeh']) : '';
    $baste_bandi = isset($inputData['baste_bandi']) ? trim($inputData['baste_bandi']) : '';
    $tolid_konnande = isset($inputData['tolid_konnande']) ? trim($inputData['tolid_konnande']) : '';
    $gheymat = isset($inputData['gheymat']) ? trim($inputData['gheymat']) : '';
    $makan_tahvil = isset($inputData['makan_tahvil']) ? trim($inputData['makan_tahvil']) : '';
    $arz = isset($inputData['arz']) ? trim($inputData['arz']) : '';
    $tasfiyeh = isset($inputData['tasfiyeh']) ? trim($inputData['tasfiyeh']) : '';
    $tarikh_tahvil = isset($inputData['tarikh_tahvil']) ? trim($inputData['tarikh_tahvil']) : '';
    $min_arzeh = isset($inputData['min_arzeh']) ? trim($inputData['min_arzeh']) : '';
    $min_kharid = isset($inputData['min_kharid']) ? trim($inputData['min_kharid']) : '';
    $max_afzayesh_arzeh = isset($inputData['max_afzayesh_arzeh']) ? trim($inputData['max_afzayesh_arzeh']) : '';
    $file = isset($inputData['file']) ? trim($inputData['file']) : '';
    $file_name = isset($inputData['file_name']) ? trim($inputData['file_name']) : '';

    // Check for duplicate min_arzeh if provided
    if (!empty($min_arzeh)) {
        try {
            $check_sql = "SELECT COUNT(*) as count FROM prc_db_burse_kala WHERE min_arzeh = '" . addslashes($min_arzeh) . "'";
            $check_result = executeQuery($check_sql);
            
            // Assuming executeQuery returns an array with the result
            $count = isset($check_result[0]['count']) ? (int)$check_result[0]['count'] : 0;
            if ($count > 0) {
                $output['message'] = 'این حداقل عرضه قبلاً ثبت شده است';
                error_log("Duplicate min_arzeh detected: $min_arzeh");
                return $output;
            }
        } catch (Exception $e) {
            error_log("Duplicate check error: " . $e->getMessage());
            $output['message'] = 'خطا در بررسی تکرار حداقل عرضه';
            return $output;
        }
    }

    // Validate file if provided
    if (!empty($file) && !checkDocumentExists($file)) {
        $output['message'] = 'فایل مشخص‌شده یافت نشد';
        error_log("Invalid file: $file");
        return $output;
    }

    // Insert query with escaped values
    try {
        $sql = "INSERT INTO prc_db_burse_kala (
            name_kala, tarikh_arzeh, moshakhasat, hajm_ghabel_arzeh, baste_bandi, 
            tolid_konnande, gheymat, makan_tahvil, arz, tasfiyeh, tarikh_tahvil, 
            min_arzeh, min_kharid, file, file_name, max_afzayesh_arzeh
        ) VALUES (
            '" . addslashes($name_kala) . "', 
            '" . addslashes($tarikh_arzeh) . "', 
            '" . addslashes($moshakhasat) . "', 
            '" . addslashes($hajm_ghabel_arzeh) . "', 
            '" . addslashes($baste_bandi) . "', 
            '" . addslashes($tolid_konnande) . "',
            '" . addslashes($gheymat) . "',
            '" . addslashes($makan_tahvil) . "', 
            '" . addslashes($arz) . "', 
            '" . addslashes($tasfiyeh) . "', 
            '" . addslashes($tarikh_tahvil) . "', 
            '" . addslashes($min_arzeh) . "', 
            '" . addslashes($min_kharid) . "', 
            '" . addslashes($file) . "', 
            '" . addslashes($file_name) . "', 
            '" . addslashes($max_afzayesh_arzeh) . "'
        )";
        
        $result = executeQuery($sql);
        
        if ($result) {
            $output['message'] = 'با موفقیت ثبت شد';
            error_log("Record inserted successfully: name_kala=$name_kala, min_arzeh=$min_arzeh");
        } else {
            $output['message'] = 'خطا در ثبت: نتیجه نامعتبر';
            error_log("Insert failed: SQL=$sql");
        }
    } catch (Exception $e) {
        error_log("Insert error: " . $e->getMessage() . " | SQL: $sql");
        $output['message'] = 'خطا در ثبت: ' . $e->getMessage();
    }

    return $output;
}

function update_record($inputData) {
    $output = [];

    // Extract input data
    $record_id = isset($inputData['record_id']) ? trim($inputData['record_id']) : '';
    $name_kala = isset($inputData['name_kala']) ? trim($inputData['name_kala']) : '';
    $tarikh_arzeh = isset($inputData['tarikh_arzeh']) ? trim($inputData['tarikh_arzeh']) : '';
    $moshakhasat = isset($inputData['moshakhasat']) ? trim($inputData['moshakhasat']) : '';
    $hajm_ghabel_arzeh = isset($inputData['hajm_ghabel_arzeh']) ? trim($inputData['hajm_ghabel_arzeh']) : '';
    $baste_bandi = isset($inputData['baste_bandi']) ? trim($inputData['baste_bandi']) : '';
    $tolid_konnande = isset($inputData['tolid_konnande']) ? trim($inputData['tolid_konnande']) : '';
    $gheymat = isset($inputData['gheymat']) ? trim($inputData['gheymat']) : '';
    $makan_tahvil = isset($inputData['makan_tahvil']) ? trim($inputData['makan_tahvil']) : '';
    $arz = isset($inputData['arz']) ? trim($inputData['arz']) : '';
    $tasfiyeh = isset($inputData['tasfiyeh']) ? trim($inputData['tasfiyeh']) : '';
    $tarikh_tahvil = isset($inputData['tarikh_tahvil']) ? trim($inputData['tarikh_tahvil']) : '';
    $min_arzeh = isset($inputData['min_arzeh']) ? trim($inputData['min_arzeh']) : '';
    $min_kharid = isset($inputData['min_kharid']) ? trim($inputData['min_kharid']) : '';
    $max_afzayesh_arzeh = isset($inputData['max_afzayesh_arzeh']) ? trim($inputData['max_afzayesh_arzeh']) : '';
    $file = isset($inputData['file']) ? trim($inputData['file']) : '';
    $file_name = isset($inputData['file_name']) ? trim($inputData['file_name']) : '';

    // Validate file if provided
    if (!empty($file) && !checkDocumentExists($file)) {
        $output['message'] = 'فایل مشخص‌شده یافت نشد';
        error_log("Invalid file: $file");
        return $output;
    }

    // Update query with escaped values
    try {
        $sql = "UPDATE prc_db_burse_kala SET 
            name_kala = '" . addslashes($name_kala) . "', 
            tarikh_arzeh = '" . addslashes($tarikh_arzeh) . "', 
            moshakhasat = '" . addslashes($moshakhasat) . "', 
            hajm_ghabel_arzeh = '" . addslashes($hajm_ghabel_arzeh) . "', 
            baste_bandi = '" . addslashes($baste_bandi) . "', 
            tolid_konnande = '" . addslashes($tolid_konnande) . "', 
            gheymat = '" . addslashes($gheymat) . "', 
            makan_tahvil = '" . addslashes($makan_tahvil) . "', 
            arz = '" . addslashes($arz) . "', 
            tasfiyeh = '" . addslashes($tasfiyeh) . "', 
            tarikh_tahvil = '" . addslashes($tarikh_tahvil) . "', 
            min_arzeh = '" . addslashes($min_arzeh) . "', 
            min_kharid = '" . addslashes($min_kharid) . "', 
            file = '" . addslashes($file) . "', 
            file_name = '" . addslashes($file_name) . "', 
            max_afzayesh_arzeh = '" . addslashes($max_afzayesh_arzeh) . "' 
            WHERE id = '" . addslashes($record_id) . "'";
        
        $result = executeQuery($sql);
        
        if ($result) {
            $output['message'] = 'ریکورد با موفقیت به روز رسانی شد';
            error_log("Record updated successfully: id=$record_id, name_kala=$name_kala");
        } else {
            $output['message'] = 'خطا در به‌روزرسانی: نتیجه نامعتبر';
            error_log("Update failed: SQL=$sql");
        }
    } catch (Exception $e) {
        error_log("Update error: " . $e->getMessage() . " | SQL: $sql");
        $output['message'] = 'خطا در به‌روزرسانی: ' . $e->getMessage();
    }

    return $output;
}

/* ***** Main Request Handler ***** */
ob_end_clean();
$output = [];

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    $output['error'] = 'روش درخواست نامعتبر است';
    header('Content-type: application/json');
    echo G::json_encode($output);
    die();
}

// Parse PUT request data
parse_str(file_get_contents("php://input"), $post_vars);

try {
    // Sanitize inputs to prevent XSS (though not needed for DB operations)
    foreach ($post_vars as $key => $value) {
        if (!is_array($value)) {
            $post_vars[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
    }
    foreach ($_GET as $key => $value) {
        if (!is_array($value)) {
            $_GET[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
    }
} catch (Exception $e) {
    $output['error'] = 'خطا در پردازش پارامترها: ' . $e->getMessage();
    header('Content-type: application/json');
    echo G::json_encode($output);
    die();
}

// Handle request type
switch ($_GET['REQ_TYPE'] ?? '') {
    case "save_to_db":
        $output = save_to_db($post_vars);
        break;
    case "update_record":
        $output = update_record($post_vars);
        break;
    default:
        $output['error'] = 'نوع درخواست نامعتبر است';
}

header('Content-type: application/json');
echo G::json_encode($output);
die();