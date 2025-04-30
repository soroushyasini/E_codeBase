<?
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('prc_db_mozayedat_monaghesat');
// Define columns in the same order as frontend edit_record


// Add a custom Edit button with inline JavaScript call, matching frontend order
$xcrud->button(
    "javascript:edit_record('{id}', '{mablagh}');",
    'Edit',
    'glyphicon glyphicon-edit',
    'btn btn-default btn-sm'
);

// Hide default edit button
$xcrud->unset_edit();
$xcrud->unset_remove(false);
$xcrud->order_by('id', 'desc');
@@panel_grid = $xcrud->render();
