<?

libXcrudLoad();
$xcrud = Xcrud::get_instance();

$xcrud->table('table1');
$xcrud->join2("table2", "b", "table1.column1 = b.column2","left");
$xcrud->join2("GROUP_USER", "c", "table1.column1 = c.column2","left");

$xcrud->table_name('تست');
$xcrud->limit_list('10,25');

$xcrud->columns(["column3","column4","column5" =>"if((b.column6 is null),'',b.column6)","column7"]);

$xcrud->label('column3','عنوان 1');
$xcrud->label('column4','عنوان 2');
$xcrud->label('column5','عنوان 3');
$xcrud->label('column7','عنوان 4');

$xcrud->where("column8 =",0);
$xcrud->where("column9 =",1);
$xcrud->where("if((c.column10 is null),'',c.column10) = '{$_SESSION['USER_LOGGED']}'");

$xcrud->order_by('column11','desc');
$xcrud->search_columns('column3,column4,column5','column4');

$xcrud->button('javascript:edit(\'{column11}\');', 'ویرایش', 'glyphicon glyphicon-edit');
$xcrud->button('javascript:delete(\'{column11}\',\'{column4}\');', 'حذف', 'glyphicon glyphicon-remove');
$xcrud->button('javascript:detail(\'{column11}\');', 'جزئیات', 'glyphicon glyphicon-th-list');

$xcrud->unset_excel(false);

return $xcrud->render();


i have a dropdown menu in processmaker and its id is akhz
i want my dynaform shows a text field