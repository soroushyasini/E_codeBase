<?
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('emidco_db_gamaneh');
// Define a callback to convert tarikh to Shamsi
//$xcrud->columns('id, grouh, onvan_amval, tedad, type, moshakhase, makan, sherkat, sanad, n2_date(tarikh), pelak_amval, vasziyat_estefade, tahvil_girande'); // List all columns
$xcrud->columns('id, project_id, name');
$xcrud->label('id','کد');
$xcrud->label('project_id','کد سایت حفاری');
$xcrud->label('name','نام گمانه');

// Add a custom Edit button with inline JavaScript call (passes original Gregorian tarikh)

$xcrud->button(
    "javascript:edit_record('{id}', '{project_id}', '{name}');",
    'Edit',
    'glyphicon glyphicon-edit',
    'btn btn-default btn-sm'
);

// Hide default edit button (optional)
//$xcrud->order_by('ID','desc');
$xcrud->unset_edit();
$xcrud->unset_remove(false);
@@panel_grid = $xcrud->render();

