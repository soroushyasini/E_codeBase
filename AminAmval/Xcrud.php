<?
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('??');
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

$xcrud->columns(array('GROUH', 'MAKAN', 'MOSHAKHASE', 'ONVAN_AMVAL', 'PELAK_AMVAL', 'SANAD', 'TAHVIL_GIRANDE', 'TARIKH', 'TEDAD', 'TYPE', 'VASZIYAT_ESTEFADE'));

$xcrud->label('GROUH','گروه کالا ');
$xcrud->label('MAKAN','مکان ');
$xcrud->label('MOSHAKHASE','مشخصه');
$xcrud->label('ONVAN_AMVAL',' عنوان اموال');
$xcrud->label('PELAK_AMVAL','پلاک اموال');
$xcrud->label('SANAD','سند');
$xcrud->label('TAHVIL_GIRANDE',' تحویل گیرنده');
$xcrud->label('TARIKH','تاریخ تحویل');
$xcrud->label('TEDAD','تعداد');
$xcrud->label('TYPE',' نوع');
$xcrud->label('VASZIYAT_ESTEFADE','وضعیت استفاده');
// Set your table or view from main database (you can write your query directly)

$xcrud->unset_edit(false);
@@panel_grid = $xcrud->render();

