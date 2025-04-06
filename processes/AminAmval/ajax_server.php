<?
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

function save_to_db($inputData) {
    $output = [];
    
    // Extract input data with defaults
    $grouh = isset($inputData['grouh']) ? $inputData['grouh'] : '';
    $onvan_amval = isset($inputData['onvan_amval']) ? $inputData['onvan_amval'] : '';
    $tedad = isset($inputData['tedad']) ? $inputData['tedad'] : '';
    $type = isset($inputData['type']) ? $inputData['type'] : '';
    $moshakhase = isset($inputData['moshakhase']) ? $inputData['moshakhase'] : '';
    $makan = isset($inputData['makan']) ? $inputData['makan'] : '';
    $sherkat = isset($inputData['sherkat']) ? $inputData['sherkat'] : '';
    $sanad = isset($inputData['sanad']) ? $inputData['sanad'] : '';
    $tarikh = isset($inputData['tarikh']) ? $inputData['tarikh'] : '';
    $pelak_amval = isset($inputData['pelak_amval']) ? $inputData['pelak_amval'] : '';
    $vasziyat_estefade = isset($inputData['vasziyat_estefade']) ? $inputData['vasziyat_estefade'] : '';
    $tahvil_girande = isset($inputData['tahvil_girande']) ? $inputData['tahvil_girande'] : '';
    
    // Check for duplicate pelak_amval only if it's not empty
    if (!empty($pelak_amval)) {
        $check_sql = "SELECT COUNT(*) as count FROM prc_db_amin_amval WHERE pelak_amval = '" . addslashes($pelak_amval) . "'";
        $check_result = executeQuery($check_sql);
        
        // Debug: Log the result of the check query
        error_log("Check query result: " . print_r($check_result, true));
        
        if ($check_result === false) {
            $output['message'] = 'Error: Failed to check for duplicates';
            return $output;
        }
        
        // Handle the nested array structure
        if (is_array($check_result) && isset($check_result[1]) && is_array($check_result[1]) && isset($check_result[1]['count'])) {
            $count = (int)$check_result[1]['count'];
            error_log("Parsed count: " . $count);
        } else {
            $output['message'] = 'Error: Invalid response from duplicate check';
            error_log("Unexpected structure: " . print_r($check_result, true));
            return $output;
        }
        
        if ($count > 0) {
            $output['message'] = 'Error: An item with this pelak_amval already exists';
            return $output;
        }
    }
    
    // Insert query
    $sql = "INSERT INTO prc_db_amin_amval (grouh, onvan_amval, tedad, type, moshakhase, makan, sherkat, sanad, tarikh, pelak_amval, vasziyat_estefade, tahvil_girande) 
            VALUES ('" . addslashes($grouh) . "', 
                    '" . addslashes($onvan_amval) . "', 
                    '" . addslashes($tedad) . "', 
                    '" . addslashes($type) . "', 
                    '" . addslashes($moshakhase) . "', 
                    '" . addslashes($makan) . "', 
                    '" . addslashes($sherkat) . "', 
                    '" . addslashes($sanad) . "', 
                    '" . addslashes($tarikh) . "', 
                    '" . addslashes($pelak_amval) . "', 
                    '" . addslashes($vasziyat_estefade) . "', 
                    '" . addslashes($tahvil_girande) . "')";
    $result = executeQuery($sql);
    
    // Debug: Log the result of the insert query
    error_log("Insert query result: " . print_r($result, true));
    
    if ($result) {
        $output['message'] = 'Data saved successfully';
    } else {
        $output['message'] = 'Error: Failed to save data';
    }
    
    return $output;
}

function update_record($inputData) {
    $output = [];
    
    // Extract input data with defaults (including id for WHERE clause)
    $id = isset($inputData['id']) ? $inputData['id'] : '';
    $grouh = isset($inputData['grouh']) ? $inputData['grouh'] : '';
    $onvan_amval = isset($inputData['onvan_amval']) ? $inputData['onvan_amval'] : '';
    $tedad = isset($inputData['tedad']) ? $inputData['tedad'] : '';
    $type = isset($inputData['type']) ? $inputData['type'] : '';
    $moshakhase = isset($inputData['moshakhase']) ? $inputData['moshakhase'] : '';
    $makan = isset($inputData['makan']) ? $inputData['makan'] : '';
    $sherkat = isset($inputData['sherkat']) ? $inputData['sherkat'] : '';
    $sanad = isset($inputData['sanad']) ? $inputData['sanad'] : '';
    $tarikh = isset($inputData['tarikh']) ? $inputData['tarikh'] : '';
    $pelak_amval = isset($inputData['pelak_amval']) ? $inputData['pelak_amval'] : '';
    $vasziyat_estefade = isset($inputData['vasziyat_estefade']) ? $inputData['vasziyat_estefade'] : '';
    $tahvil_girande = isset($inputData['tahvil_girande']) ? $inputData['tahvil_girande'] : '';
    
    // Update query
    $sql = "UPDATE prc_db_amin_amval SET 
                grouh = '" . addslashes($grouh) . "', 
                onvan_amval = '" . addslashes($onvan_amval) . "', 
                tedad = '" . addslashes($tedad) . "', 
                type = '" . addslashes($type) . "', 
                moshakhase = '" . addslashes($moshakhase) . "', 
                makan = '" . addslashes($makan) . "', 
                sherkat = '" . addslashes($sherkat) . "', 
                sanad = '" . addslashes($sanad) . "', 
                tarikh = '" . addslashes($tarikh) . "', 
                pelak_amval = '" . addslashes($pelak_amval) . "', 
                vasziyat_estefade = '" . addslashes($vasziyat_estefade) . "', 
                tahvil_girande = '" . addslashes($tahvil_girande) . "' 
            WHERE id = '" . addslashes($id) . "'";
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