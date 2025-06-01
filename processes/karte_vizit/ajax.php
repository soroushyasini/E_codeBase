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
    $id = isset($inputData['record_id']) ? trim($inputData['record_id']) : '';
    $cellphone = isset($inputData['cellphone']) ? trim($inputData['cellphone']) : '';
    $contact_person = isset($inputData['contact_person']) ? trim($inputData['contact_person']) : '';
    $email = isset($inputData['email']) ? trim($inputData['email']) : '';
    $name = isset($inputData['name']) ? trim($inputData['name']) : '';
    $number = isset($inputData['number']) ? trim($inputData['number']) : '';
    $semat = isset($inputData['semat']) ? trim($inputData['semat']) : '';
    $tozihat = isset($inputData['tozihat']) ? trim($inputData['tozihat']) : '';
    $vahed_zirabt = isset($inputData['vahed_zirabt']) ? trim($inputData['vahed_zirabt']) : '';
    $file = isset($inputData['file']) ? trim($inputData['file']) : '';
    $file_name = isset($inputData['file_name']) ? trim($inputData['file_name']) : '';

    // Validate file if provided
    if (!empty($file) && !checkDocumentExists($file)) {
        $output['message'] = 'فایل مشخص‌شده یافت نشد';
        error_log("Invalid file: $file");
        return $output;
    }

    // Insert query with escaped values
    try {
        $sql = "INSERT INTO prc_kart_vizit (
            id, cellphone, contact_person, email, name, number, semat, 
            tozihat, vahed_zirabt, file, file_name
        ) VALUES (
            '" . addslashes($id) . "', 
            '" . addslashes($cellphone) . "', 
            '" . addslashes($contact_person) . "', 
            '" . addslashes($email) . "', 
            '" . addslashes($name) . "',
            '" . addslashes($number) . "',
            '" . addslashes($semat) . "',
            '" . addslashes($tozihat) . "', 
            '" . addslashes($vahed_zirabt) . "', 
            '" . addslashes($file) . "', 
            '" . addslashes($file_name) . "'
        )";
        
        $result = executeQuery($sql);
        
        if ($result) {
            $output['message'] = 'با موفقیت ثبت شد';
            error_log("Record inserted successfully: id=$id, name=$name");
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
    $id = isset($inputData['record_id']) ? trim($inputData['record_id']) : '';
    $cellphone = isset($inputData['cellphone']) ? trim($inputData['cellphone']) : '';
    $contact_person = isset($inputData['contact_person']) ? trim($inputData['contact_person']) : '';
    $email = isset($inputData['email']) ? trim($inputData['email']) : '';
    $name = isset($inputData['name']) ? trim($inputData['name']) : '';
    $number = isset($inputData['number']) ? trim($inputData['number']) : '';
    $semat = isset($inputData['semat']) ? trim($inputData['semat']) : '';
    $tozihat = isset($inputData['tozihat']) ? trim($inputData['tozihat']) : '';
    $vahed_zirabt = isset($inputData['vahed_zirabt']) ? trim($inputData['vahed_zirabt']) : '';
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
        $sql = "UPDATE prc_kart_vizit SET 
            cellphone = '" . addslashes($cellphone) . "', 
            contact_person = '" . addslashes($contact_person) . "', 
            email = '" . addslashes($email) . "', 
            name = '" . addslashes($name) . "', 
            number = '" . addslashes($number) . "', 
            semat = '" . addslashes($semat) . "', 
            tozihat = '" . addslashes($tozihat) . "', 
            vahed_zirabt = '" . addslashes($vahed_zirabt) . "', 
            file = '" . addslashes($file) . "', 
            file_name = '" . addslashes($file_name) . "' 
            WHERE id = '" . addslashes($id) . "'";
        
        $result = executeQuery($sql);
        
        if ($result) {
            $output['message'] = 'ریکورد با موفقیت به روز رسانی شد';
            error_log("Record updated successfully: id=$id, name=$name");
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
