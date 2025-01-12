<?
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('prc_db_phonebook');

$xcrud->columns(array('name', 'number', 'dakheli', 'semat', 'contact_person', 'email', 'vahed_zirabt', 'shakhsiyat', 'tozihat'));

$xcrud->label('name','نام فرد/سازمان');
$xcrud->label('number','شماره تماس');
$xcrud->label('dakheli','شماره داخلی');
$xcrud->label('semat',' سمت/واحد');
$xcrud->label('contact_person','نام شخص ');
$xcrud->label('email','ایمیل/وب‌سایت');
$xcrud->label('vahed_zirabt','واحد ذی‌ربط داخلی');
$xcrud->label('shakhsiyat','نوع مخاطب');
$xcrud->label('tozihat','توضیحات');
// Set your table or view from main database (you can write your query directly)

$xcrud->unset_edit(false);
@@panel_grid = $xcrud->render();
