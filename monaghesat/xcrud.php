<?
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('PRC_DB_MOZAYEDAT_MONAGHESAT');

$xcrud->columns(array('ID', 'insert_date', 'NAME', 'TYPE', 'MOZAYEDE_SHOMARE', 'DASTGAHEJRAI', 'DASTGAHEJRAI', 'MABLAGH', 'DEADLINE_ASNAD', 'DEADLINE_PASOKH', 'TAHVIL_BAR', 'ZEMANAT_NAMEH', 'ZEMANAT_NAMEH_2', 'AKHZ','VAHED_MARBUTE'));
//$xcrud->columns('GROUH, ONVAN_AMVAL');
//$xcrud->columns('NAME, TYPE, MOZAYEDE_SHOMARE, DASTGAHEJRAI, DASTGAHEJRAI, MABLAGH, n2_date(DEADLINE_ASNAD), DEADLINE_PASOKH, TAHVIL_BAR, ZEMANAT_NAMEH, ZEMANAT_NAMEH_2, AKHZ');
$xcrud->label('ID','ID');
$xcrud->label('insert_date','تاریخ ثبت');
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
$xcrud->label('tamin_konnande','تامین کننده');s
$xcrud->label('VAHED_MARBUTE','واحد');


// Enable delete button
$xcrud->button('javascript:;', 'Delete', 'fa fa-trash', 'btn btn-danger', array(
    'data-task' => 'delete',
    'data-primary' => '{IDs}', // Use the unique identifier 'APP_UID'
));
// Set your table or view from main database (you can write your query directly)
$xcrud->unset_remove(false);
$xcrud->unset_edit(false);
$xcrud->unset_numbers();
@@panel_grid = $xcrud->render();


