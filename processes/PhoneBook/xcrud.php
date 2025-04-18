<?
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('prc_db_phonebook_emidco');

$xcrud->columns(array('id','name','number','dakheli','cellphone','contact_person','semat','email','vahed_zirabt','tozihat'));

$xcrud->label('id','ID');
$xcrud->label('name','نام مخاطب');
$xcrud->label('number','شماره تماس');
$xcrud->label('dakheli','شماره داخلی');
$xcrud->label('semat','سمت');
$xcrud->label('contact_person','شخص رابط');
$xcrud->label('email','ایمیل/سایت');
$xcrud->label('vahed_zirabt','واحد ذی‌ربط');
$xcrud->label('tozihat','توضیحات');
$xcrud->label('cellphone',' شماره موبایل');
// $xcrud->label('USER_LOGGED','ID');
// $xcrud->label('created_at','ID'); ,'cellphone'

$xcrud->unset_numbers();
// Enable delete button
$xcrud->button('javascript:;', 'Delete', 'fa fa-trash', 'btn btn-danger', array(
    'data-task' => 'delete',
    'data-primary' => '{id}', // Use the unique identifier 'id'
));
// Set your table or view from main database (you can write your query directly)
$xcrud->unset_remove(false);
$xcrud->unset_edit(false);
$xcrud->unset_numbers();
@@panel_grid = $xcrud->render();


