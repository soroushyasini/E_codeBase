<?
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('prc_db_gozaresh_ruzane');
$xcrud->columns(array('id',
                      'Gamane_name','dastgah_name',
                      'shift','form_date','dastgah_saat','start_our_str','end_our_str','drill_start_flt',
                      'drill_end_flt','drill_amount','corebox_start_int','corebox_end_int','corebox_amount',
                      'water_flt','gaso_flt','oil_flt','supermix_flt','bentonite_flt','sarparast','negahban',
                      'zaminshenas','driver','sar_haffar','haffar','kargar','komak_haffar','aux_kargar',
                      'aux_komak_haffar','list_vorudi','list_khruji','text_checkbox_tozihat','text_sharh_haffari',
                      'check_1_6_checkgroup','check_7_14_checkgroup','insert_date','soda_flt','cement_flt',true));


$xcrud->unset_numbers();
                    // Enable delete button
$xcrud->button('javascript:;', 'Delete', 'fa fa-trash', 'btn btn-danger', array(
    'data-task' => 'delete',
    'data-primary' => '{id}', // Use the unique identifier 'APP_UID'
               ));
                    // Set your table or view from main database (you can write your query directly)
$xcrud->unset_remove(false);
$xcrud->unset_edit(false);
$xcrud->unset_numbers();
@@panel_grid = $xcrud->render();
                    
                    //,'Projects','form_serial_number_str',
                    

                    
// Corrected columns array (removed 'true')
$xcrud->columns(array(
    'id', 'Gamane_name', 'dastgah_name', 'shift', 'form_date', 'dastgah_saat', 
    'start_our_str', 'end_our_str', 'drill_start_flt', 'drill_end_flt', 'drill_amount', 
    'corebox_start_int', 'corebox_end_int', 'corebox_amount', 'water_flt', 'gaso_flt', 
    'oil_flt', 'supermix_flt', 'bentonite_flt', 'sarparast', 'negahban', 'zaminshenas', 
    'driver', 'sar_haffar', 'haffar', 'kargar', 'komak_haffar', 'aux_kargar', 
    'aux_komak_haffar', 'list_vorudi', 'list_khruji', 'text_checkbox_tozihat', 
    'text_sharh_haffari', //'check_1_6_checkgroup', 'check_7_14_checkgroup', 
    'insert_date', 'soda_flt', 'cement_flt'
));

$xcrud->unset_numbers();

// Enable delete button
$xcrud->button('javascript:;', 'Delete', 'fa fa-trash', 'btn btn-danger', array(
    'data-task' => 'delete',
    'data-primary' => '{id}' // Use the unique identifier 'id'
));