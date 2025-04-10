<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

function save_to_db($inputData) {
    $output = [];
    
    // Extract input data with defaults for new variables
    $name_kala = isset($inputData['name_kala']) ? $inputData['name_kala'] : '';
    $tarikh_arzeh = isset($inputData['tarikh_arzeh']) ? $inputData['tarikh_arzeh'] : '';
    $baste_bandi = isset($inputData['baste_bandi']) ? $inputData['baste_bandi'] : '';
    $tolid_konnande = isset($inputData['tolid_konnande']) ? $inputData['tolid_konnande'] : '';
    $gheymat = isset($inputData['gheymat']) ? $inputData['gheymat'] : '';
    $pishpardakht = isset($inputData['pishpardakht']) ? $inputData['pishpardakht'] : '';
    $hajm_ghabel_arzeh = isset($inputData['hajm_ghabel_arzeh']) ? $inputData['hajm_ghabel_arzeh'] : '';
    $min_arzeh = isset($inputData['min_arzeh']) ? $inputData['min_arzeh'] : '';
    $min_kharid = isset($inputData['min_kharid']) ? $inputData['min_kharid'] : '';
    $max_afzayesh_arzeh = isset($inputData['max_afzayesh_arzeh']) ? $inputData['max_afzayesh_arzeh'] : '';
    $makan_tahvil = isset($inputData['makan_tahvil']) ? $inputData['makan_tahvil'] : '';
    $moshakhasat = isset($inputData['moshakhasat']) ? $inputData['moshakhasat'] : '';
    $arz = isset($inputData['arz']) ? $inputData['arz'] : '';
    $vahed = isset($inputData['vahed']) ? $inputData['vahed'] : '';
    $tarikh_tahvil = isset($inputData['tarikh_tahvil']) ? $inputData['tarikh_tahvil'] : '';
    
    // Insert query with new variables
    $sql = "INSERT INTO prc_db_burse_kala (name_kala, tarikh_arzeh, baste_bandi, tolid_konnande, gheymat, pishpardakht, hajm_ghabel_arzeh, min_arzeh, min_kharid, max_afzayesh_arzeh, makan_tahvil, moshakhasat, arz, vahed, tarikh_tahvil) 
            VALUES ('" . addslashes($name_kala) . "', 
                    '" . addslashes($tarikh_arzeh) . "', 
                    '" . addslashes($baste_bandi) . "', 
                    '" . addslashes($tolid_konnande) . "', 
                    '" . addslashes($gheymat) . "', 
                    '" . addslashes($pishpardakht) . "', 
                    '" . addslashes($hajm_ghabel_arzeh) . "', 
                    '" . addslashes($min_arzeh) . "', 
                    '" . addslashes($min_kharid) . "', 
                    '" . addslashes($max_afzayesh_arzeh) . "', 
                    '" . addslashes($makan_tahvil) . "', 
                    '" . addslashes($moshakhasat) . "', 
                    '" . addslashes($arz) . "', 
                    '" . addslashes($vahed) . "', 
                    '" . addslashes($tarikh_tahvil) . "')";
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
    $name_kala = isset($inputData['name_kala']) ? $inputData['name_kala'] : '';
    $tarikh_arzeh = isset($inputData['tarikh_arzeh']) ? $inputData['tarikh_arzeh'] : '';
    $baste_bandi = isset($inputData['baste_bandi']) ? $inputData['baste_bandi'] : '';
    $tolid_konnande = isset($inputData['tolid_konnande']) ? $inputData['tolid_konnande'] : '';
    $gheymat = isset($inputData['gheymat']) ? $inputData['gheymat'] : '';
    $pishpardakht = isset($inputData['pishpardakht']) ? $inputData['pishpardakht'] : '';
    $hajm_ghabel_arzeh = isset($inputData['hajm_ghabel_arzeh']) ? $inputData['hajm_ghabel_arzeh'] : '';
    $min_arzeh = isset($inputData['min_arzeh']) ? $inputData['min_arzeh'] : '';
    $min_kharid = isset($inputData['min_kharid']) ? $inputData['min_kharid'] : '';
    $max_afzayesh_arzeh = isset($inputData['max_afzayesh_arzeh']) ? $inputData['max_afzayesh_arzeh'] : '';
    $makan_tahvil = isset($inputData['makan_tahvil']) ? $inputData['makan_tahvil'] : '';
    $moshakhasat = isset($inputData['moshakhasat']) ? $inputData['moshakhasat'] : '';
    $arz = isset($inputData['arz']) ? $inputData['arz'] : '';
    $vahed = isset($inputData['vahed']) ? $inputData['vahed'] : '';
    $tarikh_tahvil = isset($inputData['tarikh_tahvil']) ? $inputData['tarikh_tahvil'] : '';
    
    // Update query with new variables
    $sql = "UPDATE prc_db_burse_kala SET 
                name_kala = '" . addslashes($name_kala) . "', 
                tarikh_arzeh = '" . addslashes($tarikh_arzeh) . "', 
                baste_bandi = '" . addslashes($baste_bandi) . "', 
                tolid_konnande = '" . addslashes($tolid_konnande) . "', 
                gheymat = '" . addslashes($gheymat) . "', 
                pishpardakht = '" . addslashes($pishpardakht) . "', 
                hajm_ghabel_arzeh = '" . addslashes($hajm_ghabel_arzeh) . "', 
                min_arzeh = '" . addslashes($min_arzeh) . "', 
                min_kharid = '" . addslashes($min_kharid) . "', 
                max_afzayesh_arzeh = '" . addslashes($max_afzayesh_arzeh) . "', 
                makan_tahvil = '" . addslashes($makan_tahvil) . "', 
                moshakhasat = '" . addslashes($moshakhasat) . "', 
                arz = '" . addslashes($arz) . "', 
                vahed = '" . addslashes($vahed) . "', 
                tarikh_tahvil = '" . addslashes($tarikh_tahvil) . "' 
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