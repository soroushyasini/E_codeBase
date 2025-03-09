<?
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

function save_to_db($inputData) {
    $output = [];
    
    $value1 = isset($inputData['value1']) ? $inputData['value1'] : '';
    $value2 = isset($inputData['value2']) ? $inputData['value2'] : '';
    
    $sql = "INSERT INTO ax_test (value_1, value_2) VALUES ('" . addslashes($value1) . "', '" . addslashes($value2) . "')";
    $result = executeQuery($sql);
    
    if ($result) {
        $output['message'] = 'Data saved successfully';
    } else {
        $output['message'] = 'Failed to save data';
    }
    
    return $output;
}

function update_record($inputData) {
    $output = [];
    
    $id = isset($inputData['id']) ? $inputData['id'] : null;
    $value1 = isset($inputData['value1']) ? $inputData['value1'] : '';
    $value2 = isset($inputData['value2']) ? $inputData['value2'] : '';
    
    if (!$id) {
        $output['message'] = 'No ID provided for update';
        return $output;
    }
    
    $sql = "UPDATE ax_test SET value_1 = '" . addslashes($value1) . "', value_2 = '" . addslashes($value2) . "' WHERE id = '" . addslashes($id) . "'";
    $result = executeQuery($sql);
    
    if ($result) {
        $output['message'] = 'Record updated successfully';
    } else {
        $output['message'] = 'Failed to update record';
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