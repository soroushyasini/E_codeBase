<?

//help link after import and enable plugin
//http://localhost/plugin/libXcrud/documentation/index.html

//create an instance from Xcrud object
libXcrudLoad();
$xcrud = Xcrud::get_instance();

//Set your table or view from main database (you can write your query directly)
$xcrud->table('pmt_amin_amval_copy1');

//Other optional configs:
//Set table name
//$xcrud->table_name('Groups List');
//Set paging limit
$xcrud->limit(10);
$xcrud->limit_list('10,25');

//Select columns and Set labels
$xcrud->columns('GROUH, ONVAN_AMVAL');
$xcrud->additional_columns('TYPE');
$xcrud->label('GROUH', 'گروه');
$xcrud->label('ONVAN_AMVAL', 'عنوان عموال');

//Set conditions 
//$xcrud->where('GRP_ID', array(1, 2, 3));
//$xcrud->where('GRP_ID !=', @@APP_NUMBER);
//$xcrud->where('GRP_ID =', 1);

//Set order by
//$xcrud->order_by('GRP_ID','desc');
//Set group by
//$xcrud->GROUP_by('GRP_ID');

//Change show type in dropdowns
//$xcrud->change_type('GRP_STATUS','select','',array('values'=>array('ACTIVE'=>'Is Active','INACTIVE'=>'Is Not Active')));

//Set columns search and default search
//$xcrud->search_columns('code,title,type','title');

//Create buttons
$xcrud->button('javascript:edit_form(\'{GRP_ID}\');', 'edit', 'glyphicon glyphicon-check');
$xcrud->button('javascript:show_detail(\'{GRP_ID}\');', 'details ', 'glyphicon glyphicon-tasks');

//hide some options
$xcrud->unset_add();
$xcrud->unset_edit();
$xcrud->unset_view();
$xcrud->unset_remove();
$xcrud->unset_csv();
$xcrud->unset_print();
$xcrud->unset_search();

//render result to panel
@@panel_grid = $xcrud->render();