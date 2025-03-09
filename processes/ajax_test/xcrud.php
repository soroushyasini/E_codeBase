<?
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('ax_test');

// Add a custom Edit button with inline JavaScript call
$xcrud->button(
    "javascript:edit_record('{id}', '{value_1}', '{value_2}');", // URL with function call
    'Edit',                                               // Label
    'glyphicon glyphicon-edit',                           // Icon
    'btn btn-default btn-sm'                              // CSS classes
);

// Hide default edit button (optional)
$xcrud->unset_edit();

@@panel_grid = $xcrud->render();