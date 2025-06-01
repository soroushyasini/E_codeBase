
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('prc_kart_vizit');
// Define columns in the same order as frontend edit_record
$xcrud->columns('id, name, semat, cellphone, contact_person, email, number, tozihat, vahed_zirabt');
$xcrud->label('id', 'شناسه');
$xcrud->label('cellphone', 'شماره موبایل');
$xcrud->label('contact_person', 'شخص تماس');
$xcrud->label('email', 'ایمیل');
$xcrud->label('name', 'نام');
$xcrud->label('number', 'شماره');
$xcrud->label('semat', 'سمت');
$xcrud->label('tozihat', 'توضیحات');
$xcrud->label('vahed_zirabt', 'واحد زیرابطه');

// Add a custom Edit button with inline JavaScript call, matching frontend order
$xcrud->button(
    "javascript:edit_record('{id}', '{cellphone}', '{contact_person}', '{email}', '{name}', '{number}', '{semat}', '{tozihat}', '{vahed_zirabt}', '{file}', '{file_name}');",
    'Edit',
    'glyphicon glyphicon-edit',
    'btn btn-default btn-sm'
);

// Hide default edit button
$xcrud->unset_edit();
$xcrud->unset_remove(false);
$xcrud->unset_numbers(true);
$xcrud->unset_csv(false);
$xcrud->order_by('id', 'desc');
@@panel_grid = $xcrud->render();
