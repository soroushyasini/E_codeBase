<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

function save_to_db($inputData) {
    $output = [];
    
    // Extract input data with defaults
    $insert_date = isset($inputData['insert_date']) ? $inputData['insert_date'] : '';
    $type = isset($inputData['type']) ? $inputData['type'] : '';
    $akhz = isset($inputData['akhz']) ? $inputData['akhz'] : '';
    $tamin_konnande = isset($inputData['tamin_konnande']) ? $inputData['tamin_konnande'] : '';
    $name = isset($inputData['name']) ? $inputData['name'] : '';
    $mozayede_shomare = isset($inputData['mozayede_shomare']) ? $inputData['mozayede_shomare'] : '';
    $mablagh = isset($inputData['mablagh']) ? $inputData['mablagh'] : '';
    $dastgahejrai = isset($inputData['dastgahejrai']) ? $inputData['dastgahejrai'] : '';
    $zemanat_nameh = isset($inputData['zemanat_nameh']) ? $inputData['zemanat_nameh'] : '';
    $zemanat_nameh_2 = isset($inputData['zemanat_nameh_2']) ? $inputData['zemanat_nameh_2'] : '';
    $nahve_sherkat = isset($inputData['nahve_sherkat']) ? $inputData['nahve_sherkat'] : '';
    $deadline_asnad = isset($inputData['deadline_asnad']) ? $inputData['deadline_asnad'] : '';
    $tahvil_bar = isset($inputData['tahvil_bar']) ? $inputData['tahvil_bar'] : '';
    $deadline_pasokh = isset($inputData['deadline_pasokh']) ? $inputData['deadline_pasokh'] : '';
    $vahed_marbute = isset($inputData['vahed_marbute']) ? $inputData['vahed_marbute'] : '';
    $alarm = isset($inputData['alarm']) ? $inputData['alarm'] : '';
    $monagheseh_peyvast = isset($inputData['monagheseh_peyvast']) ? $inputData['monagheseh_peyvast'] : '';
    $category = isset($inputData['category']) ? $inputData['category'] : '';
    $subcategory = isset($inputData['subcategory']) ? $inputData['subcategory'] : '';
    $products = isset($inputData['products']) ? $inputData['products'] : '';
    $record_id = isset($inputData['record_id']) ? $inputData['record_id'] : '';
    $haffari_area = isset($inputData['haffari_area']) ? $inputData['haffari_area'] : '';
    
    // Insert query
    $sql = "INSERT INTO prc_db_amin_amval (insert_date, type, akhz, tamin_konnande, name, mozayede_shomare, mablagh, dastgahejrai, zemanat_nameh, zemanat_nameh_2, nahve_sherkat, deadline_asnad, tahvil_bar, deadline_pasokh, vahed_marbute, alarm, monagheseh_peyvast, category, subcategory, products, record_id, haffari_area) 
            VALUES ('" . addslashes($insert_date) . "', 
                    '" . addslashes($type) . "', 
                    '" . addslashes($akhz) . "', 
                    '" . addslashes($tamin_konnande) . "', 
                    '" . addslashes($name) . "', 
                    '" . addslashes($mozayede_shomare) . "', 
                    '" . addslashes($mablagh) . "', 
                    '" . addslashes($dastgahejrai) . "', 
                    '" . addslashes($zemanat_nameh) . "', 
                    '" . addslashes($zemanat_nameh_2) . "', 
                    '" . addslashes($nahve_sherkat) . "', 
                    '" . addslashes($deadline_asnad) . "', 
                    '" . addslashes($tahvil_bar) . "', 
                    '" . addslashes($deadline_pasokh) . "', 
                    '" . addslashes($vahed_marbute) . "', 
                    '" . addslashes($alarm) . "', 
                    '" . addslashes($monagheseh_peyvast) . "', 
                    '" . addslashes($category) . "', 
                    '" . addslashes($subcategory) . "', 
                    '" . addslashes($products) . "', 
                    '" . addslashes($record_id) . "', 
                    '" . addslashes($haffari_area) . "')";
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
    $record_id = isset($inputData['record_id']) ? $inputData['record_id'] : null;
    $insert_date = isset($inputData['insert_date']) ? $inputData['insert_date'] : '';
    $type = isset($inputData['type']) ? $inputData['type'] : '';
    $akhz = isset($inputData['akhz']) ? $inputData['akhz'] : '';
    $tamin_konnande = isset($inputData['tamin_konnande']) ? $inputData['tamin_konnande'] : '';
    $name = isset($inputData['name']) ? $inputData['name'] : '';
    $mozayede_shomare = isset($inputData['mozayede_shomare']) ? $inputData['mozayede_shomare'] : '';
    $mablagh = isset($inputData['mablagh']) ? $inputData['mablagh'] : '';
    $dastgahejrai = isset($inputData['dastgahejrai']) ? $inputData['dastgahejrai'] : '';
    $zemanat_nameh = isset($inputData['zemanat_nameh']) ? $inputData['zemanat_nameh'] : '';
    $zemanat_nameh_2 = isset($inputData['zemanat_nameh_2']) ? $inputData['zemanat_nameh_2'] : '';
    $nahve_sherkat = isset($inputData['nahve_sherkat']) ? $inputData['nahve_sherkat'] : '';
    $deadline_asnad = isset($inputData['deadline_asnad']) ? $inputData['deadline_asnad'] : '';
    $tahvil_bar = isset($inputData['tahvil_bar']) ? $inputData['tahvil_bar'] : '';
    $deadline_pasokh = isset($inputData['deadline_pasokh']) ? $inputData['deadline_pasokh'] : '';
    $vahed_marbute = isset($inputData['vahed_marbute']) ? $inputData['vahed_marbute'] : '';
    $alarm = isset($inputData['alarm']) ? $inputData['alarm'] : '';
    $monagheseh_peyvast = isset($inputData['monagheseh_peyvast']) ? $inputData['monagheseh_peyvast'] : '';
    $category = isset($inputData['category']) ? $inputData['category'] : '';
    $subcategory = isset($inputData['subcategory']) ? $inputData['subcategory'] : '';
    $products = isset($inputData['products']) ? $inputData['products'] : '';
    $haffari_area = isset($inputData['haffari_area']) ? $inputData['haffari_area'] : '';
    
    if (!$record_id) {
        $output['message'] = 'No record_id provided for update';
        return $output;
    }
    
    // Update query
    $sql = "UPDATE prc_db_amin_amval SET 
                insert_date = '" . addslashes($insert_date) . "', 
                type = '" . addslashes($type) . "', 
                akhz = '" . addslashes($akhz) . "', 
                tamin_konnande = '" . addslashes($tamin_konnande) . "', 
                name = '" . addslashes($name) . "', 
                mozayede_shomare = '" . addslashes($mozayede_shomare) . "', 
                mablagh = '" . addslashes($mablagh) . "', 
                dastgahejrai = '" . addslashes($dastgahejrai) . "', 
                zemanat_nameh = '" . addslashes($zemanat_nameh) . "', 
                zemanat_nameh_2 = '" . addslashes($zemanat_nameh_2) . "', 
                nahve_sherkat = '" . addslashes($nahve_sherkat) . "', 
                deadline_asnad = '" . addslashes($deadline_asnad) . "', 
                tahvil_bar = '" . addslashes($tahvil_bar) . "', 
                deadline_pasokh = '" . addslashes($deadline_pasokh) . "', 
                vahed_marbute = '" . addslashes($vahed_marbute) . "', 
                alarm = '" . addslashes($alarm) . "', 
                monagheseh_peyvast = '" . addslashes($monagheseh_peyvast) . "', 
                category = '" . addslashes($category) . "', 
                subcategory = '" . addslashes($subcategory) . "', 
                products = '" . addslashes($products) . "', 
                haffari_area = '" . addslashes($haffari_area) . "' 
            WHERE record_id = '" . addslashes($record_id) . "'";
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