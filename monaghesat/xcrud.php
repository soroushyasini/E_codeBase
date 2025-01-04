<?
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('PRC_DB_MOZAYEDAT_MONAGHESAT');

$xcrud->columns(array('NAME', 'TYPE', 'MOZAYEDE_SHOMARE', 'DASTGAHEJRAI', 'DASTGAHEJRAI', 'MABLAGH', 'DEADLINE_ASNAD', 'DEADLINE_PASOKH', 'TAHVIL_BAR', 'ZEMANAT_NAMEH', 'ZEMANAT_NAMEH_2', 'AKHZ'));

$xcrud->label('NAME','عنوان ');
$xcrud->label('TYPE','نوع ');
$xcrud->label('MOZAYEDE_SHOMARE','شماره مزایده/مناقصه');
$xcrud->label('DASTGAHEJRAI','دستگاه اجرایی');
$xcrud->label('MABLAHG','مبلغ (تومان)');
$xcrud->label('DEADLINE_ASNAD','مهلت گرفتن اسناد');
$xcrud->label('DEADLINE_PASOKH',' مهلت  پاسخگویی');
$xcrud->label('TAHVIL_BAR','نحوه تحویل');
$xcrud->label('ZEMANAT_NAMEH','ضمانت‌نامه');
$xcrud->label('ZEMANAT_NAMEH_2','ضمانت‌نامه ثانویه');
$xcrud->label('AKHZ','منبع');
// Set your table or view from main database (you can write your query directly)

$xcrud->unset_edit(false);
@@panel_grid = $xcrud->render();

