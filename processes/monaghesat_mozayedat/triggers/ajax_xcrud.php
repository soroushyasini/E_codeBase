<?
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('prc_db_mozayedat_monaghesat');
// Define a callback to convert tarikh to Shamsi
//$xcrud->columns('id, grouh, onvan_amval, tedad, type, moshakhase, makan, sherkat, sanad, n2_date(tarikh), pelak_amval, vasziyat_estefade, tahvil_girande'); // List all columns
$xcrud->columns('AKHZ,ALARM,category,DASTGAHEJRAI,date_created,DEADLINE_ASNAD,DEADLINE_PASOKH,file_field,haffari_area,id,INSERT_DATE,LEFT_DAYS,MABLAGH,MOZAYEDE_SHOMARE,NAHVE_SHERKAT,NAME,products,subcategory,TAHVIL_BAR,TAMIN_KONNANDE,TYPE,VAHED_MARBUTE,ZEMANAT_NAMEH,ZEMANAT_NAMEH_2');
// $xcrud->label('id','کد');
// $xcrud->label('grouh','گروه کالا ');
// $xcrud->label('tedad','تعداد');
// $xcrud->label('type',' نوع');
// $xcrud->label('makan','مکان ');
// $xcrud->label('onvan_amval',' عنوان اموال');
// $xcrud->label('moshakhase','مشخصه');
// $xcrud->label('sherkat','شرکت');
// $xcrud->label('pelak_amval','پلاک اموال');
// $xcrud->label('vasziyat_estefade','وضعیت');
// $xcrud->label('sanad','سند');
// $xcrud->label('tahvil_girande',' تحویل گیرنده');
// $xcrud->label('tarikh','تاریخ تحویل');

// Add a custom Edit button with inline JavaScript call (passes original Gregorian tarikh)
$xcrud->button(
    "javascript:edit_record('{AKHZ}', '{ALARM}', '{category}', '{DASTGAHEJRAI}', '{date_created}', '{DEADLINE_ASNAD}', '{DEADLINE_PASOKH}', '{file_field}', '{haffari_area}', '{id}', '{INSERT_DATE}', '{LEFT_DAYS}', '{MABLAGH}', '{MOZAYEDE_SHOMARE}', '{NAHVE_SHERKAT}', '{NAME}', '{products}', '{subcategory}', '{TAHVIL_BAR}', '{TAMIN_KONNANDE}', '{TYPE}', '{VAHED_MARBUTE}', '{ZEMANAT_NAMEH}', '{ZEMANAT_NAMEH_2}')
);",
    'Edit',
    'glyphicon glyphicon-edit',
    'btn btn-default btn-sm'
);

// Hide default edit button (optional)
//$xcrud->order_by('ID','desc');
$xcrud->unset_edit();
$xcrud->unset_remove(false);
@@panel_grid = $xcrud->render();

