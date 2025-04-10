<?php
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('prc_db_burse_kala');
// Define a callback to convert tarikh_tahvil to Shamsi (if needed, uncomment and adjust)
//$xcrud->columns('id, name_kala, tarikh_arzeh, baste_bandi, tolid_konnande, gheymat, pishpardakht, hajm_ghabel_arzeh, min_arzeh, min_kharid, max_afzayesh_arzeh, makan_tahvil, moshakhasat, arz, vahed, n2_date(tarikh_tahvil)');
$xcrud->columns('id, name_kala, tarikh_arzeh, baste_bandi, tolid_konnande, gheymat, pishpardakht, hajm_ghabel_arzeh, min_arzeh, min_kharid, max_afzabel_arzeh, makan_tahvil, moshakhasat, arz, vahed, tarikh_tahvil');
$xcrud->label('id', 'کد');
$xcrud->label('name_kala', 'نام کالا');
$xcrud->label('tarikh_arzeh', 'تاریخ عرضه');
$xcrud->label('baste_bandi', 'بسته‌بندی');
$xcrud->label('tolid_konnande', 'تولیدکننده');
$xcrud->label('gheymat', 'قیمت');
$xcrud->label('pishpardakht', 'پیش‌پرداخت');
$xcrud->label('hajm_ghabel_arzeh', 'حجم قابل عرضه');
$xcrud->label('min_arzeh', 'حداقل عرضه');
$xcrud->label('min_kharid', 'حداقل خرید');
$xcrud->label('max_afzayesh_arzeh', 'حداکثر افزایش عرضه');
$xcrud->label('makan_tahvil', 'مکان تحویل');
$xcrud->label('moshakhasat', 'مشخصات');
$xcrud->label('arz', 'ارز');
$xcrud->label('vahed', 'واحد');
$xcrud->label('tarikh_tahvil', 'تاریخ تحویل');

// Add a custom Edit button with inline JavaScript call (passes original Gregorian tarikh_tahvil)
$xcrud->button(
    "javascript:edit_record('{id}', '{name_kala}', '{tarikh_arzeh}', '{baste_bandi}', '{tolid_konnande}', '{gheymat}', '{pishpardakht}', '{hajm_ghabel_arzeh}', '{min_arzeh}', '{min_kharid}', '{max_afzayesh_arzeh}', '{makan_tahvil}', '{moshakhasat}', '{arz}', '{vahed}', '{tarikh_tahvil}');",
    'Edit',
    'glyphicon glyphicon-edit',
    'btn btn-default btn-sm'
);

// Hide default edit button
$xcrud->unset_edit();
$xcrud->unset_remove(false);
@@panel_grid = $xcrud->render();