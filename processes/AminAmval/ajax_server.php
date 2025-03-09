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
    
    if ($result) {
        $output['message'] = 'Data saved successfully';
    } else {
        $output['message'] = 'Failed to save data';
    }
    
    return $output;
}

function update_record($inputData) {
    $output = [];
    
    // Extract input data with defaults
    $id = isset($inputData['id']) ? $inputData['id'] : null;
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
    
    if (!$id) {
        $output['message'] = 'No ID provided for update';
        return $output;
    }
    
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