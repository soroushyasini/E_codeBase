
libXcrudLoad();
$xcrud = Xcrud::get_instance();
$xcrud->table('prc_db_mozayedat_monaghesat');
// Define columns in the same order as frontend edit_record
$xcrud->columns('id, type, akhz, name, mozayede_shomare, mablagh, dastgahejrai, nahve_sherkat, deadline_asnad, tahvil_bar, deadline_pasokh, alarm, zemanat_nameh, zemanat_nameh_2, insert_date, vahed_marbute, tamin_konnande, category, subcategory, products, haffari_area, file, file_name, file_nahaee, file_name_nahaee, vaze_tamdid, vaze_sherkat');
$xcrud->label('type', 'نوع');
$xcrud->label('akhz', 'اخذ');
$xcrud->label('name', 'نام');
$xcrud->label('mozayede_shomare', 'شماره مناقصه');
$xcrud->label('mablagh', 'مبلغ');
$xcrud->label('dastgahejrai', 'دستگاه اجرایی');
$xcrud->label('nahve_sherkat', 'نحوه شرکت');
$xcrud->label('deadline_asnad', 'مهلت اسناد');
$xcrud->label('tahvil_bar', 'تحویل بار');
$xcrud->label('deadline_pasokh', 'مهلت پاسخ');
$xcrud->label('alarm', 'هشدار');
$xcrud->label('zemanat_nameh', 'ضمانت‌نامه');
$xcrud->label('zemanat_nameh_2', 'ضمانت‌نامه دوم');
$xcrud->label('insert_date', 'تاریخ درج');
$xcrud->label('vahed_marbute', 'واحد مربوطه');
$xcrud->label('tamin_konnande', 'تامین‌کننده');
$xcrud->label('left_days', 'روزهای باقی‌مانده');
$xcrud->label('category', 'دسته‌بندی');
$xcrud->label('subcategory', 'زیرمجموعه');
$xcrud->label('products', 'محصولات');
$xcrud->label('haffari_area', 'منطقه حفاری');
$xcrud->label('file', 'فایل');
$xcrud->label('file_name', 'نام فایل');
$xcrud->label('file_nahaee', 'فایل نهایی');
$xcrud->label('file_name_nahaee', 'نام فایل نهایی');
$xcrud->label('vaze_tamdid', 'وضعیت تمدید');
$xcrud->label('vaze_sherkat', 'وضعیت شرکت');

// Add a custom Edit button with inline JavaScript call, matching frontend order
$xcrud->button(
    "javascript:edit_record('{id}', '{type}', '{akhz}', '{name}', '{mozayede_shomare}', '{mablagh}', '{dastgahejrai}', '{nahve_sherkat}', '{deadline_asnad}', '{tahvil_bar}', '{deadline_pasokh}', '{alarm}', '{zemanat_nameh}', '{zemanat_nameh_2}', '{insert_date}', '{vahed_marbute}', '{tamin_konnande}', '{category}', '{subcategory}', '{products}', '{haffari_area}', '{file}', '{file_name}', '{file_nahaee}', '{file_name_nahaee}', '{vaze_tamdid}', '{vaze_sherkat}');",
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