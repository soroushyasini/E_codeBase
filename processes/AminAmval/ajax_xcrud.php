<?
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('prc_db_amin_amval');

// Define a callback to convert tarikh to Shamsi
$xcrud->columns('id, grouh, onvan_amval, tedad, type, moshakhase, makan, sherkat, sanad, n2_date(tarikh), pelak_amval, vasziyat_estefade, tahvil_girande'); // List all columns
$xcrud->query('SELECT id, grouh, onvan_amval, tedad, type, moshakhase, makan, sherkat, sanad, n2_date(tarikh), pelak_amval, vasziyat_estefade, tahvil_girande FROM prc_db_amin_amval');
// Add a custom Edit button with inline JavaScript call (passes original Gregorian tarikh)
$xcrud->button(
    "javascript:edit_record('{id}', '{grouh}', '{onvan_amval}', '{tedad}', '{type}', '{moshakhase}', '{makan}', '{sherkat}', '{sanad}', '{n2_date(tarikh)}', '{pelak_amval}', '{vasziyat_estefade}', '{tahvil_girande}');",
    'Edit',
    'glyphicon glyphicon-edit',
    'btn btn-default btn-sm'
);

// Hide default edit button (optional)
$xcrud->unset_edit();

@@panel_grid = $xcrud->render();

