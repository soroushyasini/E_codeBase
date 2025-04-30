<?
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('prc_db_amin_amval');
// her is column names :
// APP_NUMBER
// APP_STATUS
// APP_UID
// GROUH
// MAKAN
// MOSHAKHASE
// ONVAN_AMVAL
// PELAK_AMVAL
// SANAD
// TAHVIL_GIRANDE
// TARIKH
// TEDAD
// TYPE
// VASZIYAT_ESTEFADE

$xcrud->columns(array('id','GROUH', 'TEDAD', 'TYPE','MAKAN', 'ONVAN_AMVAL', 'MOSHAKHASE', 'PELAK_AMVAL', 'SANAD', 'TAHVIL_GIRANDE', 'TARIKH', 'VASZIYAT_ESTEFADE'));
$xcrud->label('id','ID');
$xcrud->label('GROUH','گروه کالا ');
$xcrud->label('TEDAD','تعداد');
$xcrud->label('TYPE',' نوع');
$xcrud->label('MAKAN','مکان ');
$xcrud->label('ONVAN_AMVAL',' عنوان اموال');
$xcrud->label('MOSHAKHASE','مشخصه');
$xcrud->label('PELAK_AMVAL','پلاک اموال');
$xcrud->label('SANAD','سند');
$xcrud->label('TAHVIL_GIRANDE',' تحویل گیرنده');
$xcrud->label('TARIKH','تاریخ تحویل');

$xcrud->label('VASZIYAT_ESTEFADE','وضعیت استفاده');
// Set your table or view from main database (you can write your query directly)
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
$xcrud->unset_csv(false);
@@panel_grid = $xcrud->render();