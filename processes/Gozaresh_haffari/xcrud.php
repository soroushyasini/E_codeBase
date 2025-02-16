<?
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('prc_db_gozaresh_ruzane');

$xcrud->columns('Projects, form_serial_number_str, Gamane_name, dastgah_name, shift, form_date, 
    dastgah_saat, start_our_str, end_our_str, drill_start_flt, drill_end_flt, 
    drill_amount, corebox_start_int, corebox_end_int, corebox_amount, water_flt, 
    gaso_flt, oil_flt, supermix_flt, bentonite_flt, sarparast, negahban, zaminshenas, 
    driver, sar_haffar, haffar, kargar, komak_haffar, aux_kargar, aux_komak_haffar, 
    list_vorudi, list_khruji, text_checkbox_tozihat, text_sharh_haffari, 
    check_1_6_checkgroup, check_7_14_checkgroup, soda_flt, cement_flt, is_stopped, stop_causes, stop_time, pack_lv, iradat');

// Set Persian labels for columns
$xcrud->label('Projects', 'پروژه‌ها');
$xcrud->label('form_serial_number_str', 'شماره فرم');
$xcrud->label('Gamane_name', 'نام گمانه');
$xcrud->label('dastgah_name', 'نام دستگاه');
$xcrud->label('shift', 'شیفت');
$xcrud->label('form_date', 'تاریخ فرم');
$xcrud->label('dastgah_saat', 'ساعت دستگاه');
$xcrud->label('start_our_str', 'زمان شروع');
$xcrud->label('end_our_str', 'زمان پایان');
$xcrud->label('drill_start_flt', 'شروع حفاری');
$xcrud->label('drill_end_flt', 'پایان حفاری');
$xcrud->label('drill_amount', 'مقدار حفاری');
$xcrud->label('corebox_start_int', 'شروع جعبه مغزه');
$xcrud->label('corebox_end_int', 'پایان جعبه مغزه');
$xcrud->label('corebox_amount', 'تعداد جعبه مغزه');
$xcrud->label('water_flt', 'مقدار آب');
$xcrud->label('gaso_flt', 'مقدار گازوئیل');
$xcrud->label('oil_flt', 'مقدار روغن');
$xcrud->label('supermix_flt', 'سوپر میکس');
$xcrud->label('bentonite_flt', 'بنتونیت');
$xcrud->label('sarparast', 'سرپرست');
$xcrud->label('negahban', 'نگهبان');
$xcrud->label('zaminshenas', 'زمین‌شناس');
$xcrud->label('driver', 'راننده');
$xcrud->label('sar_haffar', 'سر حفار');
$xcrud->label('haffar', 'حفار');
$xcrud->label('kargar', 'کارگر');
$xcrud->label('komak_haffar', 'کمک حفار');
$xcrud->label('aux_kargar', 'کارگر کمکی');
$xcrud->label('aux_komak_haffar', 'کمک کارگر اضافی');
$xcrud->label('list_vorudi', 'لیست ورودی');
$xcrud->label('list_khruji', 'لیست خروجی');
$xcrud->label('text_checkbox_tozihat', 'توضیحات');
$xcrud->label('text_sharh_haffari', 'شرح حفاری');
$xcrud->label('check_1_6_checkgroup', 'بررسی ۱-۶');
$xcrud->label('check_7_14_checkgroup', 'بررسی ۷-۱۴');
$xcrud->label('soda_flt', 'مقدار سودا');
$xcrud->label('cement_flt', 'مقدار سیمان');
$xcrud->label('is_stopped', 'توقف شده');
$xcrud->label('stop_causes', 'دلایل توقف');
$xcrud->label('stop_time', 'مدت توقف');
$xcrud->label('pack_lv', 'پک LV');
$xcrud->label('iradat', 'ایرادات');

// Enable edit and remove buttons
$xcrud->unset_remove(false);
$xcrud->unset_edit(false);
$xcrud->unset_numbers();
$xcrud->column_cut(12); // all columns
// Render the xCRUD instance
@@panel_grid = $xcrud->render();
