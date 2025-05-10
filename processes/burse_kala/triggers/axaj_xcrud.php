<?
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('prc_db_burse_kala');
// Define columns in the same order as frontend edit_record
$xcrud->columns('id, name_kala, tarikh_arzeh, moshakhasat, hajm_ghabel_arzeh, baste_bandi, tolid_konnande, gheymat, makan_tahvil, arz, tasfiyeh, tarikh_tahvil, min_arzeh, min_kharid, max_afzayesh_arzeh');
$xcrud->label('name_kala', 'نام کالا');
$xcrud->label('tarikh_arzeh', 'تاریخ عرضه');
$xcrud->label('moshakhasat', 'مشخصات');
$xcrud->label('hajm_ghabel_arzeh', 'حجم قابل عرضه');
$xcrud->label('baste_bandi', 'بسته‌بندی');
$xcrud->label('tolid_konnande', 'تولیدکننده');
$xcrud->label('gheymat', 'قیمت');
$xcrud->label('makan_tahvil', 'مکان تحویل');
$xcrud->label('arz', 'ارز');
$xcrud->label('tasfiyeh', 'تسویه');
$xcrud->label('tarikh_tahvil', 'تاریخ تحویل');
$xcrud->label('min_arzeh', 'حداقل عرضه');
$xcrud->label('min_kharid', 'حداقل خرید');
$xcrud->label('max_afzayesh_arzeh', 'حداکثر افزایش عرضه');

// Add a custom Edit button with inline JavaScript call, matching frontend order
$xcrud->button(
    "javascript:edit_record('{id}', '{name_kala}', '{tarikh_arzeh}', '{moshakhasat}', '{hajm_ghabel_arzeh}', '{baste_bandi}', '{tolid_konnande}','{gheymat}', '{makan_tahvil}', '{arz}', '{tasfiyeh}', '{tarikh_tahvil}', '{min_arzeh}', '{min_kharid}', '{max_afzayesh_arzeh}', '{file}', '{file_name}');",
    'Edit',
    'glyphicon glyphicon-edit',
    'btn btn-default btn-sm'
);

// Hide default edit button
$xcrud->unset_edit();
$xcrud->unset_remove(false);
$xcrud->unset_csv(false);
$xcrud->order_by('id', 'desc');
@@panel_grid = $xcrud->render();