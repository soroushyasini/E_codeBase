<?
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

function save_to_db($inputData) {
    $output = [];
    
    // Extract input data with defaults
    $project_id = isset($inputData['project_id']) ? $inputData['project_id'] : '';
    $name = isset($inputData['name']) ? $inputData['name'] : '';

    

    // Insert query
    $sql = "INSERT INTO emidco_db_gamaneh (project_id, name) 
            VALUES (
                    '" . addslashes($project_id) . "', 
                    '" . addslashes($name) . "')";
    $result = executeQuery($sql);
    
    // Debug: Log the result of the insert query
    error_log("Insert query result: " . print_r($result, true));
    
    if ($result) {
        $output['message'] = 'با موفقیت ثبت شد';
    } else {
        $output['message'] = 'خطا ! ثبت انجام نشد';
    }
    
    return $output;
}

function update_record($inputData) {
    $output = [];
    
    // Extract input data with defaults (including id for WHERE clause)
    $id = isset($inputData['id']) ? $inputData['id'] : '';
    $project_id = isset($inputData['project_id']) ? $inputData['project_id'] : '';
    $name = isset($inputData['name']) ? $inputData['name'] : '';

    // Update query
    $sql = "UPDATE emidco_db_gamaneh SET 
                project_id = '" . addslashes($project_id) . "', 
                name = '" . addslashes($name) . "' 
            WHERE id = '" . addslashes($id) . "'";
    $result = executeQuery($sql);
    
    if ($result) {
        $output['message'] = 'ریکورد با موفقیت به روز رسانی شد';
    } else {
        $output['message'] = 'خطا ! ریکورد به روز نشد ';
    }
    
    return $output;
}

/* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */
ob_end_clean();
$output = [];

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents("php://input"), $post_vars);
} else {
    $output['error'] = 'Invalid request method';
    header('Content-type: application/json');
    echo G::json_encode($output);
    die();
}

try {
    foreach ($post_vars as $key => $value) {
        if (!is_array($value)) {
            $post_vars[$key] = htmlspecialchars($value);
        }
    }
    foreach ($_GET as $key => $value) {
        if (!is_array($value)) {
            $_GET[$key] = htmlspecialchars($value);
        }
    }
} catch (Exception $e) {
    $output['error'] = 'Parameters Error: ' . $e->getMessage();
    header('Content-type: application/json');
    echo G::json_encode($output);
    die();
}

switch ($_GET['REQ_TYPE']) {
    case "save_to_db":
        $output = save_to_db($post_vars);
        break;
    case "update_record":
        $output = update_record($post_vars);
        break;
    default:
        $output['error'] = 'Request Error';
}

header('Content-type: application/json');
echo G::json_encode($output);
die();