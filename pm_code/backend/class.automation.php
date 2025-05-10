<?php
class pb_automation
{
	private $pluginName = 'automation';
	private $pluginDir;
	private $config;
	private $process = '7142874775a376ac9a1d112057958403';
	private $task_view = '2082522295a3f580e133084010607793';
	private $admin_user = '00000000000000000000000000000001';
	private $workspace;
	public function __construct()
	{
		$this->pluginDir = PATH_PLUGINS . $this->pluginName;
		$this->workspace = ((PM_VERSION == '3.2.1' || PM_VERSION == '3.2.1-community') ? SYS_SYS : config("system.workspace"));
	}
	
	private function empty_redis($memKeys = ['auto'])
	{
		if(autoCheckRedis()){
			global $RedisObj;
			foreach($memKeys as $memKey){
				$keys = $RedisObj->keys("$memKey*");
				if ($keys) $RedisObj->del($keys);
			}
		}
	}
	
	public function is_file_image($filename)
	{
		$regex = '/\.(jpe?g|bmp|png|JPE?G|BMP|PNG)(?:[\?\#].*)?$/';
		return preg_match($regex, $filename);
	}

	public function validateDate($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	
	public function is_valid_datetime($dateTime)
	{
		$dateTime = explode(' ', $dateTime);
		$date = $dateTime[0];

		$format = 'Y-m-d';
		$oDate = new DateTime($date);
		if($date && $oDate->format($format) === $date)
			return true;

		$format = 'Y/m/d';
		$oDate = new DateTime($date);
		if($date && $oDate->format($format) === $date)
			return true;

		/*$dateTime = strtotime($dateTime);
		if(empty($dateTime)){
			return false;
		}else{
			$dateTime = gmdate('Y-m-d H:i:s', $dateTime);
			if(preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)){
				if(checkdate($matches[2], $matches[3], $matches[1])){
					return true;
				}
			}
		}*/

		return false;
	}

	public function persian_date_e2p($g_y, $g_m, $g_d, $mod='')
	{
		$d_4 = $g_y % 4;
		$g_a = [0, 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
		$doy_g = $g_a[(int)$g_m] + $g_d;
		if($d_4 == 0 AND $g_m > 2){
			$doy_g++;
		}
		$d_33 = (int)(((($g_y - 16) % 132) / 132) * 4);
		$a = ($d_33 == 3 OR $d_33 < ($d_4 - 1) OR $d_4 == 0) ? 286 : 287;
		$b = (($d_33 == 1 OR $d_33 == 2) AND ($d_33 == $d_4 OR $d_4 == 1)) ? 78 : (($d_33 == 3 AND $d_4 == 0) ? 80 : 79);
		if((int)(($g_y - 10) / 63) == 30){
			$a--;
			$b++;
		}
		if($doy_g > $b){
			$jy = $g_y - 621;
			$doy_j = $doy_g - $b;
		}else{
			$jy = $g_y - 622;
			$doy_j = $doy_g + $a;
		}
		if($doy_j < 187){
			$a=0;
			$b=31;
			$c=1;
		}else{
			$a=186;
			$b=30;
			$c=7;
		}
		$jm = (int)(($doy_j - $a - 1) / $b);
		$jd = $doy_j - $a - ($jm * $b);
		$jm += $c;
		
		if($jm < 10){
			$jm = '0'.$jm;
		}
		
		if($jd < 10){
			$jd = '0'.$jd;
		}
		
		return($mod == '') ? [$jy, $jm, $jd] : $jy.$mod.$jm.$mod.$jd;
	}

	public function persian_date_e2p_na($date)
	{
		if(!$this->is_valid_datetime($date)){
			return $date;
		}
		
		$date_delimiter = substr($date, 4, 1);
		
		$temp = @explode(' ', $date);
		$date = $temp[0];
		
		$date_array = @explode($date_delimiter, $date);
		$date_array_o = $this->persian_date_e2p($date_array[0], $date_array[1], $date_array[2]);
		
		$date_o = implode('/', $date_array_o);
		$date_o = $date_o.((isset($temp[1]) && !empty($temp[1]))?' '.$temp[1]:'');
		
		return $date_o;
	}
	
	public function auto_check_user_in_group($GRP_UID, $USR_UID)
	{
		$exist = 0;
		$query = '
			select *
			from GROUP_USER
			where GRP_UID = "'.$GRP_UID.'" and USR_UID = "'.$USR_UID.'"
		';
		$result = executeQuery($query);
		if(count($result) > 0)
			$exist = 1;
		return $exist;
	}

	public function auto_get_department_user($id)
	{
		$query = '
			select a.`USR_UID`
			from `USERS` as a
			where a.DEP_UID = "'.$id.'";
		';
		return executeQuery($query);
	}

	public function auto_get_group_user($id)
	{
		$query = '
			select a.`USR_UID`
			from `GROUP_USER` as a
			where a.GRP_UID = "'.$id.'";
		';
		return executeQuery($query);
	}

	public function auto_get_inputDocPath()
	{
		$server = (G::is_https() ? 'https://':'http://') . $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
		return $server . '/sys' . config("system.workspace") . '/' . SYS_LANG . '/' . SYS_SKIN . '/cases/cases_ShowDocument?a=';
	}

	public function auto_get_letter_type_name($letter_type)
	{
		global $RedisObj;
		$memKey = 'autoGetLetterTypeName'.$letter_type;
		if (!autoCheckRedis() || ($letter_type_name = $RedisObj->get($memKey)) === false) {
			$letter_type_name = '';
			$query = '
				select `name`
				from auto_based_info
				where deleted = 0 and `value` = "'.$letter_type.'"
			';
			$result = executeQuery($query);
			if(is_array($result) && count($result) > 0)
				$letter_type_name = $result[1]['name'];
			
			if(autoCheckRedis()){
				$RedisObj->set($memKey, $letter_type_name);
				$RedisObj->expire($memKey, 8*60*60);
			}
		}

		return $letter_type_name;
	}

	public function auto_create_new_case($user, $processData = [], $caseStatus = 'TO_DO')
	{
		$processData['letter_type_name'] = $this->auto_get_letter_type_name($processData['letter_type']);
		
		$userInfo = userInfo($_SESSION['USER_LOGGED']);
		$processData['letter_author_id'] = $_SESSION['USER_LOGGED'];
		$processData['letter_author'] = $userInfo['firstname'] . ' ' . $userInfo['lastname'];
		
		PMFAssignUserToGroup($this->admin_user, $this->task_view);
		$new_app = PMFNewCase($this->process, $this->admin_user, $this->task_view, $processData, $caseStatus);
		
		$con = Propel::getConnection('workflow');
		$stmt = $con->createStatement();
		
		$query = "
			UPDATE `APPLICATION` 
			SET `APP_INIT_USER` = '".$user."', `APP_CUR_USER` = '".$user."'
			WHERE `APP_UID` = '".$new_app."';
		";
		$stmt->executeQuery($query);
		
		$query = "
			UPDATE `APP_DELEGATION`
			SET `USR_UID` = '".$user."', `DEL_INIT_DATE` = NULL
				".($this->validateDate($processData['due_date'])?", DEL_TASK_DUE_DATE = replace(DEL_TASK_DUE_DATE, DEL_TASK_DUE_DATE, '".$processData['due_date'].' '.date('H:i:s')."')":'')."
			WHERE `APP_UID` = '".$new_app."';
		";
		$stmt->executeQuery($query);
		
		$query = "
			UPDATE `LIST_INBOX`
			SET `USR_UID` = '".$user."', `DEL_INIT_DATE` = NULL,
				`DEL_PREVIOUS_USR_UID` = '".$_SESSION['USER_LOGGED']."',
				`DEL_PREVIOUS_USR_USERNAME` = '".$userInfo['username']."',
				`DEL_PREVIOUS_USR_FIRSTNAME` = '".$userInfo['firstname']."',
				`DEL_PREVIOUS_USR_LASTNAME` = '".$userInfo['lastname']."'
				".($this->validateDate($processData['due_date'])?", DEL_DUE_DATE = replace(DEL_DUE_DATE, DEL_DUE_DATE, '".$processData['due_date'].' '.date('H:i:s')."')":'')."
			WHERE `APP_UID` = '".$new_app."';
		";
		$stmt->executeQuery($query);
		
		$query = "
			UPDATE `APP_CACHE_VIEW`
			SET `USR_UID` = '".$user."', `DEL_INIT_DATE` = NULL, `APP_STATUS` = '".$caseStatus."',
				`PREVIOUS_USR_UID` = '".$_SESSION['USER_LOGGED']."',
				`APP_DEL_PREVIOUS_USER` = '".$userInfo['lastname'].' '.$userInfo['firstname']."'
				".($this->validateDate($processData['due_date'])?", DEL_TASK_DUE_DATE = replace(DEL_TASK_DUE_DATE, DEL_TASK_DUE_DATE, '".$processData['due_date'].' '.date('H:i:s')."')":'')."
			WHERE `APP_UID` = '".$new_app."';
		";
		$stmt->executeQuery($query);
		
		return $new_app;
	}

	public function auto_insert_notification($suggest_user, $processData = [], $type = 'letter')
	{
		if($this->auto_get_config('send_notification') != 1)
			return;

		$userInfo = userInfo($_SESSION['USER_LOGGED']);
		$processData[$type.'_author'] = $userInfo['firstname'] . ' ' . $userInfo['lastname'];
		$processData[$type.'_author_id'] = $userInfo['username'];

		$userInfo = userInfo($suggest_user);
		$processData['to_mail'] = $userInfo['mail'];
		$processData['to_mobile'] = $userInfo['phone'];
		$processData['to_id'] = $userInfo['username'];

		try {
			$author_mail = $this->auto_get_mail_server();
			if(!empty($author_mail) && $author_mail){
				$aAttachFiles = [];
				if(isset($processData['word_name']) && !empty($processData['word_name'])){
					$ext = explode('.', $processData['word_name']);
					$ext[count($ext) - 1] = 'pdf';
					$filename_pdf = implode('.', $ext);
					$download_path_pdf = PATH_DATA . 'pishrobpms/automation/prints/'.$filename_pdf;
					if(file_exists($download_path_pdf))
						$aAttachFiles["محتوای نامه.pdf"] = $download_path_pdf;
				}
				PMFSendMessage($processData['new_app'], $author_mail, $processData['to_mail'], '', '', $processData['subject'], 'notification_'.$type.'.html', $processData, $aAttachFiles);
			}

			if(file_exists(PATH_DATA . 'pishrobpms' . PATH_SEP . 'automation' . PATH_SEP . 'class.notification.php')){
				require_once(PATH_DATA . 'pishrobpms' . PATH_SEP . 'automation' . PATH_SEP . 'class.notification.php');
				pb_automationNotification::send($processData);
			}

			if(function_exists('sm_send_notification')){
				$misc1 = 'شماره نامه: '. $processData['letter_number'];
				$misc2 = 'موضوع نامه: '. $processData['subject'];
				$misc3 = 'شما یک نامه جدید دریافت کرده اید، لطفا آن را بررسی نمایید.';
				$inputData = [
					'to_id' => $processData['to_id'],
					'author_id' => $processData[$type.'_author_id'],
					'author_name' => $processData[$type.'_author'],
					'misc1' => $misc1,
					'misc1' => $misc2,
					'misc3' => $misc3
				];
				sm_send_notification($inputData);
			}
		} catch(Exception $e){
			error_log($e->getMessage(), 0);
		}
	}

	private function auto_get_mail_server()
	{
		global $RedisObj;
		$memKey = 'autoMessAccount';
		if (!autoCheckRedis() || ($account = $RedisObj->get($memKey)) === false) {
			$query = '
				select MESS_ACCOUNT
				from EMAIL_SERVER
				where MESS_DEFAULT = 1
			';
			$result = executeQuery($query);
			if(count($result))
				$account = $result[1]['MESS_ACCOUNT'];

			if(autoCheckRedis()){
				$RedisObj->set($memKey, $account);
				$RedisObj->expire($memKey, 8*60*60);
			}
		}

		return $account;
	}

	public function auto_set_content($letter_id)
	{
		$query = '
			select a.*, b.`print_type`, c.`name` as organization_name
			from `auto_letter` as a join `auto_template` as b on(a.template_id = b.id)
				 left join `auto_organization` as c on(a.organization_id = c.id)
			where a.`id` = "'.$letter_id.'";
		';
		$result = executeQuery($query);
		
		$data = [];
		$data['letter_id'] = $result[1]['id'];
		$data['template_id'] = $result[1]['template_id'];
		$data['letter_number'] = $result[1]['number'];
		$data['letter_type'] = $result[1]['letter_type'];
		$data['subject'] = $result[1]['subject'];
		$data['letter_date'] = $result[1]['letter_date'];
		$data['insert_date'] = $result[1]['insert_date'];
		$data['sign'] = '';
		//$content = htmlspecialchars_decode($result[1]['content']);
		
		$subject = '<p style="margin:0px;text-align:right;"><b>موضوع: '.$result[1]['subject'].'</b></p>';
		$content = $result[1]['content'];
		
		require_once(PATH_PLUGINS . 'automation/classes/Html2Text.php');
		$content = Html2Text\Html2Text::convert($content);
		
		$receiver = '';
		if($result[1]['letter_type'] == 'internal'){
			$query = '
				select a.*, CONCAT(b.`USR_FIRSTNAME`," ",b.USR_LASTNAME) as to_user_name, b.USR_POSITION as `to_user_position`
				from `auto_letter_receiver` as a join `USERS` as b on(a.to_user = b.USR_UID)
				where a.`letter_id` = "'.$letter_id.'" and a.`receiver_type` = "main";
			';
			$result = executeQuery($query);
			foreach($result as $row){
				$receiver .= '<p style="margin:0px;text-align:right;"><b>'.$row['to_user_name'].' / '.$row['to_user_position'].'</b></p>';
			}
		}
		else if($result[1]['letter_type'] == 'export'){
			$receiver .= '<p style="margin:0px;text-align:right;"><b>'.$result[1]['organization_name'].'</b></p>';
		}
		
		$content = $subject.$receiver.$content;
		
		$query = '
			select a.*, b.sign_file, c.APP_DOC_FILENAME
			from `auto_letter_sign` as a join `auto_signer` as b on(a.USR_UID = b.USR_UID)
				 join `APP_DOCUMENT` as c on(b.sign_file = c.APP_DOC_UID)
			where a.`letter_id` = "'.$letter_id.'" and b.`'.$data['letter_type'].'` = 1 and b.deleted = 0
			order by id desc;
		';
		$result = executeQuery($query);
		
		if(count($result) > 0){
			$path = $this->auto_get_inputDocPath().$result[1]['sign_file'];
			$type = pathinfo($result[1]['APP_DOC_FILENAME'], PATHINFO_EXTENSION);
			$type = strtolower($type);
			if(G::is_https()){
				$arrContextOptions = [
					"ssl" => [
						"verify_peer" => false,
						"verify_peer_name" => false
					]
				];
				$image_data = file_get_contents($path, false, stream_context_create($arrContextOptions));
			}
			else
				$image_data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
			$data['sign'] = $base64;
		}
		
		$inputData = [
			'template_id' => $data['template_id'],
			'letter_date' => $data['letter_date'],
			'letter_id' => $data['letter_id']
		];
		$variables = $this->auto_get_variables_data($inputData);
		foreach($variables as $key=>$value)
			$data[$key] = $value;
		$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		
		$query = '
			INSERT INTO `auto_print`(
				`letter_id`, `USR_UID`, `content`, `variables`
			)
			VALUES (
				"'.$letter_id.'", "'.$_SESSION['USER_LOGGED'].'", "'.str_replace('ی', 'ي', str_replace('"', '\"', $content)).'", "'.str_replace('ی', 'ي', str_replace('"', '\"', $data)).'"
			);
		';
		executeQuery($query);
	}

	public function auto_get_config($type = '')
	{
		if(!is_array($this->config))
			$this->config = parse_ini_file(PATH_PLUGINS . 'automation/config/pluginConfig.ini');
		if($type == 'date_type')
			return ((isset($this->config['date_type']) && !empty($this->config['date_type']))?$this->config['date_type']:'j');
		if($type == 'date_format')
			return ((isset($this->config['date_format']) && !empty($this->config['date_format']))?$this->config['date_format']:'M d, Y');
		if($type == 'send_notification')
			return ((isset($this->config['send_notification']) && $this->config['send_notification'] != '')?$this->config['send_notification']:0);
		if(!empty($type)){
			if(isset($this->config[$type]))
				return $this->config[$type];
			return '';
		}
		return $this->config;
	}

	/* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */

	public function auto_add_permission($from_user, $to_user, $letter_type)
	{
		try{
			$query = '
				INSERT INTO `auto_permission` (`from_user`, `to_user`, `letter_type`, `insert_user`, `insert_date`)
				SELECT * FROM (SELECT "'.$from_user.'" as a, "'.$to_user.'" as b, "'.$letter_type.'" as c, "'.$_SESSION['USER_LOGGED'].'" as d, NOW() as e) AS tmp
				WHERE NOT EXISTS (
					SELECT id FROM `auto_permission` WHERE `from_user` = "'.$from_user.'" and `to_user` = "'.$to_user.'" and `letter_type` = "'.$letter_type.'"
				) LIMIT 1;
			';
			executeQuery($query);
		} catch(Exception $e){
			error_log($e->getMessage(), 0);
		}
	}

	public function auto_delete_permission($from_user, $to_user, $letter_type)
	{
		$query = '
			DELETE FROM `auto_permission`
			WHERE from_user = "'.$from_user.'" AND to_user = "'.$to_user.'" AND letter_type = "'.$letter_type.'";
		';
		executeQuery($query);
	}

	public function auto_search_permission_format($inputData = [])
	{
		libXcrudLoad();
		$xcrud = Xcrud::get_instance();

		$xcrud->table('view_auto_permission_format');
		$xcrud->table_name('مجوزهای ارجاعات');
		$xcrud->limit_list('10,25');

		$xcrud->columns(['letter_type','from_type','name','to_type','to_name'=>'if(not isnull(user_name),user_name,if(not isnull(group_name),group_name,if(not isnull(department_name),department_name,dabirkhaneh_name)))']);
		$xcrud->label('letter_type','نوع نامه');
		$xcrud->label('from_type','از نوع کاربری');
		$xcrud->label('name','از');
		$xcrud->label('to_type','به نوع کاربری');
		$xcrud->label('to_name','به');
		
		$xcrud->change_type('letter_type','select','',array('values'=>array('internal'=>'نامه داخلی','import'=>'نامه ورودی','export'=>'نامه خروجی')));
		$xcrud->change_type('from_type','select','',array('values'=>array('dabirkhaneh'=>'دبیرخانه','department'=>'دپارتمان','group'=>'گروه کاربری','user'=>'کاربر')));
		$xcrud->change_type('to_type','select','',array('values'=>array('dabirkhaneh'=>'دبیرخانه','department'=>'دپارتمان','group'=>'گروه کاربری','user'=>'کاربر')));

		$xcrud->button('javascript:delete_permission_format(\'{id}\', \'{from_id}\', \'{to_id}\');', 'حذف', 'glyphicon glyphicon-remove');

		$xcrud->search_columns('letter_type,from_type,name,to_type,to_name');
		$xcrud->unset_excel(false);

		return $xcrud->render();
	}

	public function auto_add_permission_format($inputData = [])
	{
		$output = [];
		$output['data'] = [
			'result' => [],
			'message' => ''
		];

		if(empty($inputData['user_type_from'])){
			$output['error'] = 'نوع کاربر ارجاع از را مشخص کنید';
			return $output;
		}
		else if(empty($inputData['user_type_to'])){
			$output['error'] = 'نوع کاربر ارجاع به را مشخص کنید';
			return $output;
		}
		else if(!is_array($inputData['letter_type']) || count($inputData['letter_type']) == 0){
			$output['error'] = 'نوع نامه را مشخص کنید';
			return $output;
		}

		$letter_type = $inputData['letter_type'];
		$from_id = '';
		$to_id = '';

		if($inputData['user_type_from'] == 'dabirkhaneh')
			$from_id = $inputData['dabirkhaneh_from'];
		else if($inputData['user_type_from'] == 'department')
			$from_id = $inputData['department_from'];
		else if($inputData['user_type_from'] == 'group')
			$from_id = $inputData['group_from'];
		else if($inputData['user_type_from'] == 'user')
			$from_id = $inputData['user_from'];

		if($inputData['user_type_to'] == 'dabirkhaneh')
			$to_id = $inputData['dabirkhaneh_to'];
		else if($inputData['user_type_to'] == 'department')
			$to_id = $inputData['department_to'];
		else if($inputData['user_type_to'] == 'group')
			$to_id = $inputData['group_to'];
		else if($inputData['user_type_to'] == 'user')
			$to_id = $inputData['user_to'];

		if($from_id == $to_id && $inputData['user_type_from'] == $inputData['user_type_to'] && $inputData['user_type_from'] == 'user'){
			$output['error'] = 'امکان ثبت ارجاع از یک کاربر به همان کاربر وجود ندارد';
			return $output;
		}

		for($i=0;$i<count($letter_type);$i++){
			$query = '
				select a.*
				from `auto_permission_format` as a
				where a.`deleted` = 0 AND 
					  a.`from_type` = "'.$inputData['user_type_from'].'" AND a.`to_type` = "'.$inputData['user_type_to'].'" AND
					  a.`from_id` = "'.$from_id.'" AND a.`to_id` = "'.$to_id.'" AND
					  a.`letter_type` = "'.$letter_type[$i].'";
			';
			$result = executeQuery($query);
			if(count($result) > 0){
				continue;
			}

			$query = '
				INSERT INTO `auto_permission_format`(
					`from_type`, `from_id`, `to_type`, `to_id`, `letter_type`, `insert_user`, `insert_date`
				)
				VALUES (
					"'.$inputData['user_type_from'].'", "'.$from_id.'", "'.$inputData['user_type_to'].'", "'.$to_id.'",
					"'.$letter_type[$i].'", "'.$_SESSION['USER_LOGGED'].'", NOW()
				);
			';
			executeQuery($query);

			$temp = [
				'from_type' => $inputData['user_type_from'],
				'to_type' => $inputData['user_type_to'],
				'from_id' => $from_id,
				'to_id' => $to_id,
				'letter_type' => $letter_type[$i]
			];
			$this->refresh_permission($temp);
		}

		$message = 'مجوزهای ارجاعات با موفقیت ثبت شد';
		$output['data']['message'] = $message;
		return $output;
	}

	public function auto_delete_permission_format($inputData = [])
	{
		$output = [];

		if(empty($inputData['id'])){
			$output['error'] = 'شناسه حذف به درستی مشخص نشده است';
			return $output;
		}

		$query = '
			UPDATE `auto_permission_format`
			SET `deleted` = 1
			WHERE `deleted` = 0 and id = "'.$inputData['id'].'" AND from_id = "'.$inputData['user_from'].'" AND to_id = "'.$inputData['user_to'].'";
		';
		$result = executeQuery($query);

		if($result == 0)
			$output['error'] = 'درخواست نامعتبر است';
		else{
			$this->refresh_permissions();

			$output['data'] = [
				'message'=>'حذف با موفقیت انجام شد'
			];
		}
		return $output;
	}

	public function refresh_permissions()
	{
		$query = '
			DELETE FROM `auto_permission`
			WHERE 1;
		';
		executeQuery($query);

		$query = '
			select *
			from `auto_permission_format`
			where `deleted` = 0;
		';
		$result = executeQuery($query);
		if(count($result) > 0){
			foreach($result as $row)
				$this->refresh_permission($row);
		}
	}

	private function refresh_permission($inputData = [])
	{
		$from_type = $inputData['from_type'];
		$to_type = $inputData['to_type'];
		$from_id = $inputData['from_id'];
		$to_id = $inputData['to_id'];
		$letter_type = $inputData['letter_type'];

		$from1 = '`USERS` as b on(a.from_type = "user" and a.from_id = b.USR_UID)';
		if($from_type == 'dabirkhaneh')
			$from1 = '`auto_dabirkhaneh` as b on(a.from_type = "dabirkhaneh" and a.from_id = b.id and b.deleted = 0)';
		else if($from_type == 'group')
			$from1 = '`GROUPWF` as b on(a.from_type = "group" and a.from_id = b.GRP_UID)';
		else if($from_type == 'department')
			$from1 = '`DEPARTMENT` as b on(a.from_type = "department" and a.from_id = b.DEP_UID)';

		$from2 = '`USERS` as c on(a.to_type = "user" and a.to_id = c.USR_UID)';
		if($from_type == 'dabirkhaneh')
			$from2 = '`auto_dabirkhaneh` as c on(a.to_type = "dabirkhaneh" and a.to_id = c.id and c.deleted = 0)';
		else if($from_type == 'group')
			$from2 = '`GROUPWF` as c on(a.to_type = "group" and a.to_id = c.GRP_UID)';
		else if($from_type == 'department')
			$from2 = '`DEPARTMENT` as c on(a.to_type = "department" and a.to_id = c.DEP_UID)';

		$query = '
			select a.*
			from `auto_permission_format` as a
				 join '.$from1.'
				 join '.$from2.'
			where a.`deleted` = 0 AND
				  a.`from_type` = "'.$from_type.'" AND a.`to_type` = "'.$to_type.'" AND
				  a.`from_id` = "'.$from_id.'" AND a.`to_id` = "'.$to_id.'" AND
				  a.`letter_type` = "'.$letter_type.'"
		';
		$result = executeQuery($query);

		if($from_type == 'dabirkhaneh')
			$result1 = $this->auto_get_dabrikhaneh_user($from_id);
		else if($from_type == 'department')
			$result1 = $this->auto_get_department_user($from_id);
		else if($from_type == 'group')
			$result1 = $this->auto_get_group_user($from_id);
		else if($from_type == 'user'){
			$result1 = [];
			$result1[1] = [];
			$result1[1]['USR_UID'] = $from_id;
		}

		if(is_array($result1) && count($result1) > 0){
			if($to_type == 'dabirkhaneh')
				$result2 = $this->auto_get_dabrikhaneh_user($to_id);
			else if($to_type == 'department')
				$result2 = $this->auto_get_department_user($to_id);
			else if($to_type == 'group')
				$result2 = $this->auto_get_group_user($to_id);
			else if($to_type == 'user'){
				$result2 = [];
				$result2[1] = [];
				$result2[1]['USR_UID'] = $to_id;
			}
		}
		foreach($result1 as $row1){
			foreach($result2 as $row2)
				$this->auto_add_permission($row1['USR_UID'], $row2['USR_UID'], $letter_type);
		}
	}
	
	/* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */

	public function auto_is_signer($inputData)
	{
		if($inputData['letter_type'] != 'internal' && $inputData['letter_type'] != 'export')
			return 0;
		
		$query = '
			select a.`id`
			from `auto_signer` as a
			where a.`USR_UID` = "'.$inputData['USR_UID'].'" and a.`'.$inputData['letter_type'].'` = 1 and a.deleted = 0;
		';
		$result = executeQuery($query);
		if(count($result) > 0){
			return 1;
		}
		return 0;
	}

	public function auto_search_signer()
	{
		$query = '
			select a.*, CONCAT(b.`USR_FIRSTNAME`," ",b.USR_LASTNAME) as signer_name
			from `auto_signer` as a join `USERS` as b on(a.USR_UID = b.USR_UID)
			where a.`deleted` = 0
			order by signer_name;
		';
		return executeQuery($query);
	}

	public function auto_add_signer($inputData = [])
	{
		$output = [];
		
		$uploadedFiles = $inputData['uploadedFiles'];
		
		if(empty($inputData['user'])){
			$output['error'] = 'کاربر را مشخص کنید';
			return $output;
		}
		else if(empty($inputData['internal']) && empty($inputData['export'])){
			$output['error'] = 'حداقل یک نوع نامه را مشخص کنید';
			return $output;
		}
		else if(count($uploadedFiles) == 0){
			$output['error'] = 'تصویر امضا را مشخص کنید';
			return $output;
		}
		else if(count($uploadedFiles) > 1){
			$output['error'] = 'یک تصویر امضا می توانید درج کنید';
			return $output;
		}
		
		$query = '
			select a.*, CONCAT(b.`USR_FIRSTNAME`," ",b.USR_LASTNAME) as signer_name
			from `auto_signer` as a join `USERS` as b on(a.USR_UID = b.USR_UID)
			where a.`deleted` = 0 AND a.USR_UID = "'.$inputData['user'].'";
		';
		$result = executeQuery($query);
		if(count($result) > 0){
			$output['error'] = 'این فرد قبلا به عنوان امضا کننده درج شده است';
			return $output;
		}
		
		$sign_file = '';
		if(count($uploadedFiles) > 0){
			foreach($uploadedFiles as $row){
				if(!empty($row[0])){
					$sign_file = $row[0];
					break;
				}
			}
		}
		
		$query = '
			INSERT INTO `auto_signer`(
				`USR_UID`, `internal`, `export`, `sign_file`, `insert_user`, `insert_date`
			)
			VALUES (
				"'.$inputData['user'].'", "'.$inputData['internal'].'", "'.$inputData['export'].'", "'.$sign_file.'", "'.$_SESSION['USER_LOGGED'].'", NOW()
			);
		';
		executeQuery($query);
		
		$query = '
			select a.*, CONCAT(b.`USR_FIRSTNAME`," ",b.USR_LASTNAME) as signer_name
			from `auto_signer` as a join `USERS` as b on(a.USR_UID = b.USR_UID)
			where a.`deleted` = 0 AND a.USR_UID = "'.$inputData['user'].'";
		';
		$result = executeQuery($query);

		$output['data'] = $result[1];
		
		$message = 'امضا کننده با موفقیت ثبت شد';
		$output['data']['message'] = $message;
		return $output;
	}

	public function auto_update_signer($inputData = [])
	{
		$output = [];
		
		if(empty($inputData['id'])){
			$output['error'] = 'شناسه ویرایش به درستی مشخص نشده است';
			return $output;
		}
		else if(empty($inputData['signer'])){
			$output['error'] = 'کاربر را مشخص کنید';
			return $output;
		}
		
		$query = '
			UPDATE `auto_signer`
			SET `internal` = "'.$inputData['internal'].'",
				`export` = "'.$inputData['export'].'",
				update_user = "'.$_SESSION['USER_LOGGED'].'",
				update_date = NOW()
			WHERE id = "'.$inputData['id'].'" AND USR_UID = "'.$inputData['signer'].'";
		';
		executeQuery($query);
		
		$output['data'] = [
			'message'=>'ویرایش با موفقیت انجام شد'
		];
		return $output;
	}

	public function auto_delete_signer($inputData = [])
	{
		$output = [];
		
		if(empty($inputData['id'])){
			$output['error'] = 'شناسه حذف به درستی مشخص نشده است';
			return $output;
		}
		else if(empty($inputData['signer'])){
			$output['error'] = 'کاربر را مشخص کنید';
			return $output;
		}
		
		$query = '
			UPDATE `auto_signer`
			SET `deleted` = 1,
				update_user = "'.$_SESSION['USER_LOGGED'].'",
				update_date = NOW()
			WHERE id = "'.$inputData['id'].'" AND USR_UID = "'.$inputData['signer'].'";
		';
		executeQuery($query);
		
		$output['data'] = [
			'message'=>'حذف با موفقیت انجام شد'
		];
		return $output;
	}

	/* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */

	public function auto_search_organization($inputData = [])
	{
		if(!$this->auto_check_user_in_group('8429038916173170cd93fc7087369477', $_SESSION['USER_LOGGED'])){
			return '
				<p style="font-size: 15px;"><strong>اجازه مشاهده سازمان ها را ندارید</strong></p>
				<style>
				  #grid_panel{border-width: 2px;border-color: burlywood;border-radius: 10px;background-color: antiquewhite;}
				</style>
			';
		}

		libXcrudLoad();
		$xcrud = Xcrud::get_instance();

		$xcrud->table('auto_organization');
        $xcrud->join2("USERS", "c", "auto_organization.insert_user = c.USR_UID");
        $xcrud->join2("auto_template", "b", "`auto_organization`.`template_id` = `b`.`id` and `b`.`deleted` = 0", "left");
		$xcrud->join2("USERS", "d", "auto_organization.update_user = d.USR_UID", "left");
		$xcrud->table_name('لیست سازمان ها');
		$xcrud->limit_list('10,25');

		$xcrud->columns(['name','phone','template_name'=>'b.name','import','export','last_name'=>'if(auto_organization.update_date, concat(d.USR_FIRSTNAME, " ", d.USR_LASTNAME), concat(c.USR_FIRSTNAME, " ", c.USR_LASTNAME))','last_date'=>'if(auto_organization.update_date, n2_date(auto_organization.update_date), n2_date(auto_organization.insert_date))']);
		$xcrud->label('name','نام');
		$xcrud->label('phone','شماره تماس');
		$xcrud->label('template_name','قالب پیش فرض');
		$xcrud->label('import','نامه ورودی');
		$xcrud->label('export','نامه خروجی');
		$xcrud->label('last_name','ثبت/ویرایش کننده');
		$xcrud->label('last_date','آخرین تغییر');

		$xcrud->where('deleted =', '0');

		if($this->auto_check_user_in_group('4553686926173174c4ba748048142518', $_SESSION['USER_LOGGED']))
			$xcrud->button('javascript:edit_organization(\'{id}\', \'{template_id}\', \'{name}\', \'{phone}\', \'{shenaseh_melli}\', \'{code_eghtesadi}\', \'{code_posti}\', \'{address}\', \'{import}\', \'{export}\');', 'ویرایش', 'glyphicon glyphicon-edit');
		if($this->auto_check_user_in_group('26958508161731752b23f91016970558', $_SESSION['USER_LOGGED']))
			$xcrud->button('javascript:delete_organization(\'{id}\', \'{name}\');', 'حذف', 'glyphicon glyphicon-remove');

		$xcrud->search_columns('name','name');
		$xcrud->unset_excel(false);

		return $xcrud->render();
	}

	public function auto_search_organization_persons($inputData = [])
	{
		$query = '
			select `name`, `post`
			from `auto_organization_persons`
			where `deleted` = 0 and organization_id = "'.$inputData['id'].'"
		';
		return executeQuery($query);
	}

	public function auto_add_organization($inputData = [])
	{
		$output = [];
		
		if(!$this->auto_check_user_in_group('26895766961731743663237095810293', $_SESSION['USER_LOGGED'])){
			$output['error'] = 'اجازه افزودن سازمان ندارید';
			return $output;
		}
		if(empty($inputData['organization_name'])){
			$output['error'] = 'نام سازمان را مشخص کنید';
			return $output;
		}
		else if(empty($inputData['import']) && empty($inputData['export'])){
			$output['error'] = 'حداقل یک نوع نامه را مشخص کنید';
			return $output;
		}
		
		$query = '
			select a.*
			from `auto_organization` as a
			where a.`deleted` = 0 AND a.`name` = "'.$inputData['organization_name'].'";
		';
		$result = executeQuery($query);
		if(count($result) > 0){
			$output['error'] = 'این سازمان قبلا ثبت شده است';
			return $output;
		}
		
		$query = '
			INSERT INTO `auto_organization`(
				`template_id`, `name`, `phone`, `shenaseh_melli`, `code_eghtesadi`, `code_posti`, `address`, `import`, `export`, `insert_user`, `insert_date`
			)
			VALUES (
				"'.$inputData['template'].'", "'.$inputData['organization_name'].'", "'.$inputData['phone'].'", "'.$inputData['shenaseh_melli'].'",
				"'.$inputData['code_eghtesadi'].'", "'.$inputData['code_posti'].'", "'.$inputData['address'].'", "'.$inputData['import'].'", "'.$inputData['export'].'",
				"'.$_SESSION['USER_LOGGED'].'", NOW()
			);
		';
		executeQuery($query);
		
		$query = '
			select LAST_INSERT_ID() as last_insert_id;
		';
		$result = executeQuery($query);
		if(count($result) > 0){
			$organization_id = $result[1]['last_insert_id'];
			foreach($inputData['persons'] as $row){
				$query = '
					INSERT INTO `auto_organization_persons`(
						`organization_id`, `name`, `post`, `insert_user`, `insert_date`
					)
					VALUES (
						"'.$organization_id.'", "'.$row[0].'", "'.$row[1].'",
						"'.$_SESSION['USER_LOGGED'].'", NOW()
					);
				';
				executeQuery($query);
			}
		}
		
		$output['success'] = 'سازمان بیرونی با موفقیت ثبت شد';
		return $output;
	}

	public function auto_edit_organization($inputData = [])
	{
		$output = [];
		
		if(!$this->auto_check_user_in_group('4553686926173174c4ba748048142518', $_SESSION['USER_LOGGED'])){
			$output['error'] = 'اجازه ویرایش سازمان ندارید';
			return $output;
		}
		if(empty($inputData['organization_name'])){
			$output['error'] = 'نام سازمان را مشخص کنید';
			return $output;
		}
		else if(empty($inputData['import']) && empty($inputData['export'])){
			$output['error'] = 'حداقل یک نوع نامه را مشخص کنید';
			return $output;
		}
		else if(empty($inputData['id'])){
			$output['error'] = 'شناسه ویرایش به درستی مشخص نشده است';
			return $output;
		}
		
		$query = '
			select a.*
			from `auto_organization` as a
			where a.`deleted` = 0 AND a.`name` = "'.$inputData['organization_name'].'" AND a.`id` != "'.$inputData['id'].'";
		';
		$result = executeQuery($query);
		if(count($result) > 0){
			$output['error'] = 'این سازمان قبلا ثبت شده است';
			return $output;
		}
		
		$query = '
			UPDATE `auto_organization`
			SET `name` = "'.$inputData['organization_name'].'",
				`phone` = "'.$inputData['phone'].'",
				`shenaseh_melli` = "'.$inputData['shenaseh_melli'].'",
				`code_eghtesadi` = "'.$inputData['code_eghtesadi'].'",
				`code_posti` = "'.$inputData['code_posti'].'",
				`address` = "'.$inputData['address'].'",
				`template_id` = "'.$inputData['template'].'",
				`import` = "'.$inputData['import'].'",
				`export` = "'.$inputData['export'].'",
				`update_user` = "'.$_SESSION['USER_LOGGED'].'",
				`update_date` = NOW()
			WHERE `id` = "'.$inputData['id'].'";
		';
		executeQuery($query);
		
		$query = '
			UPDATE `auto_organization_persons`
			SET `deleted` = 1,
				`update_user` = "'.$_SESSION['USER_LOGGED'].'",
				`update_date` = NOW()
			WHERE `organization_id` = "'.$inputData['id'].'";
		';
		executeQuery($query);
		
		foreach($inputData['persons'] as $row){
			$query = '
				INSERT INTO `auto_organization_persons`(
					`organization_id`, `name`, `post`, `insert_user`, `insert_date`
				)
				VALUES (
					"'.$inputData['id'].'", "'.$row[0].'", "'.$row[1].'",
					"'.$_SESSION['USER_LOGGED'].'", NOW()
				);
			';
			executeQuery($query);
		}
		
		$this->empty_redis(['autoSetOrganizationTemplate']);
		
		$output['success'] = 'سازمان بیرونی با موفقیت ویرایش شد';
		return $output;
	}

	public function auto_delete_organization($inputData = [])
	{
		$output = [];
		
		if(!$this->auto_check_user_in_group('26958508161731752b23f91016970558', $_SESSION['USER_LOGGED'])){
			$output['error'] = 'اجازه حذف سازمان ندارید';
			return $output;
		}
		if(empty($inputData['id'])){
			$output['error'] = 'شناسه حذف به درستی مشخص نشده است';
			return $output;
		}
		else if(empty($inputData['organization_name'])){
			$output['error'] = 'سازمان را مشخص کنید';
			return $output;
		}
		
		$query = '
			UPDATE `auto_organization`
			SET `deleted` = 1,
				`update_user` = "'.$_SESSION['USER_LOGGED'].'",
				`update_date` = NOW()
			WHERE id = "'.$inputData['id'].'" AND `name` = "'.$inputData['organization_name'].'";
		';
		executeQuery($query);
		
		$query = '
			UPDATE `auto_organization_persons`
			SET `deleted` = 1,
				`update_user` = "'.$_SESSION['USER_LOGGED'].'",
				`update_date` = NOW()
			WHERE `organization_id` = "'.$inputData['id'].'";
		';
		executeQuery($query);
		
		$this->empty_redis(['autoSetOrganizationTemplate']);
		
		$output['success'] = 'حذف با موفقیت انجام شد';
		return $output;
	}

	/* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */

	public function auto_get_number_structure($dabirkhaneh_id)
	{
		global $RedisObj;
		$memKey = 'autoGetNumberStructure'.$dabirkhaneh_id;
		if (!autoCheckRedis() || ($number_structure = $RedisObj->get($memKey)) === false) {
			$query = '
				select `number_structure`
				from `auto_dabirkhaneh`
				where `id` = "'.$dabirkhaneh_id.'";
			';
			$result = executeQuery($query);
			if(count($result))
				$number_structure = $result[1]['number_structure'];
			
			if(autoCheckRedis()){
				$RedisObj->set($memKey, $number_structure);
				$RedisObj->expire($memKey, 8*60*60);
			}
		}

		return (!empty($number_structure)?$this->auto_get_variable_replace($number_structure):'');
	}

	public function auto_get_dabrikhaneh_user($id)
	{
		$query = '
			select b.`USR_UID`
			from `auto_dabirkhaneh` as a join `auto_dabirkhaneh_user` as b on(a.id = b.dabirkhaneh_id)
			where a.id = "'.$id.'" and b.deleted = 0;
		';
		return executeQuery($query);
	}

	public function auto_search_dabirkhaneh($inputData = [])
	{
		libXcrudLoad();
		$xcrud = Xcrud::get_instance();
		
		$xcrud->table('auto_dabirkhaneh');
		$xcrud->table_name('دبیرخانه ها');
		$xcrud->limit_list('10,25');
		
		$xcrud->columns('name,number_structure,number_start');
		$xcrud->label('name','نام');
		$xcrud->label('number_structure','ساختار شماره نامه');
		$xcrud->label('number_start','شروع شماره نامه از');
		
		$xcrud->where('deleted =', 0);
		
		$xcrud->button('javascript:edit_dabirkhaneh(\'{id}\', \'{name}\', \'{number_structure}\', \'{number_start}\', \'{letter_type}\');', 'ویرایش', 'glyphicon glyphicon-edit');
		$xcrud->button('javascript:delete_dabirkhaneh(\'{id}\', \'{name}\');', 'حذف', 'glyphicon glyphicon-remove');
		$xcrud->button('javascript:dabirkhaneh_user(\'{id}\');', 'کاربران', 'glyphicon glyphicon-user');
		
		$xcrud->search_columns('name','name');
		$xcrud->unset_excel(false);

		return $xcrud->render();
	}

	public function auto_add_dabirkhaneh($inputData = [])
	{
		$output = [];
		
		if(empty($inputData['dabirkhaneh_name'])){
			$output['error'] = 'نام دبیرخانه را مشخص کنید';
			return $output;
		}
		else if(empty($inputData['number_structure'])){
			$output['error'] = 'ساختار شماره نامه را مشخص کنید';
			return $output;
		}
		else if(empty($inputData['number_start'])){
			$output['error'] = 'شروع شماره نامه را مشخص کنید';
			return $output;
		}
        else if(empty($inputData['letter_type'])){
			$output['error'] = 'نوع نامه را مشخص کنید';
			return $output;
		}
        $inputData['letter_type'] = implode(',',$inputData['letter_type']);
		$query = '
			select *
			from `auto_dabirkhaneh`
			where `deleted` = 0 AND (`name` = "'.$inputData['dabirkhaneh_name'].'" OR `number_structure` = "'.$inputData['number_structure'].'");
		';
		$result = executeQuery($query);
		if(count($result) > 0){
			if($result[1]['name'] == $inputData['dabirkhaneh_name']){
				$output['error'] = 'دبیرخانه با این نام ثبت شده است';
				return $output;
			}
			if($result[1]['number_structure'] == $inputData['number_structure']){
				$output['error'] = 'دبیرخانه با این ساختار شماره نامه ثبت شده است';
				return $output;
			}
		}
		
		$query = '
			select id
			from `auto_dabirkhaneh`
			where `deleted` = 1 AND `number_structure` = "'.$inputData['number_structure'].'";
		';
		$result = executeQuery($query);
		if(count($result) > 0){
			$query = '
				update auto_dabirkhaneh
				set deleted = 0,
					`name` = "'.$inputData['dabirkhaneh_name'].'",
					`number_start` = "'.$inputData['number_start'].'"
				WHERE `id` = "'.$result[1]['id'].'";
			';
			executeQuery($query);
		}
		else{
			$query = '
				INSERT INTO `auto_dabirkhaneh`(
					`name`, `number_structure`, `number_start`,letter_type, `insert_user`, `insert_date`
				)
				VALUES (
					"'.$inputData['dabirkhaneh_name'].'", "'.$inputData['number_structure'].'", "'.$inputData['number_start'].'", "'.$inputData['letter_type'].'", "'.$_SESSION['USER_LOGGED'].'", NOW()
				);
			';
			$result = executeQuery($query);
		}
		
		$query = '
			select *
			from `auto_dabirkhaneh`
			where `deleted` = 0 AND `number_structure` = "'.$inputData['number_structure'].'";
		';
		$result = executeQuery($query);
		
		$output['data'] = $result[1];
		
		$message = 'دبیرخانه با موفقیت ثبت شد';
		$output['data']['message'] = $message;
		return $output;
	}

	public function auto_edit_dabirkhaneh($inputData = [])
	{
		$output = [];

		if(empty($inputData['dabirkhaneh_name'])){
			$output['error'] = 'نام دبیرخانه را مشخص کنید';
			return $output;
		}
		else if(empty($inputData['number_structure'])){
			$output['error'] = 'ساختار شماره نامه را مشخص کنید';
			return $output;
		}
		else if(empty($inputData['number_start'])){
			$output['error'] = 'شروع شماره نامه را مشخص کنید';
			return $output;
		}
        else if(empty($inputData['letter_type'])){
            $output['error'] = 'نوع نامه را مشخص کنید';
            return $output;
        }
		else if(empty($inputData['id'])){
			$output['error'] = 'شناسه ویرایش به درستی مشخص نشده است';
			return $output;
		}
        $inputData['letter_type'] = implode(',',$inputData['letter_type']);
		$query = '
			select *
			from `auto_dabirkhaneh`
			where `deleted` = 0 AND (`name` = "'.$inputData['dabirkhaneh_name'].'" OR `number_structure` = "'.$inputData['number_structure'].'") AND
				  `id` != "'.$inputData['id'].'";
		';
		$result = executeQuery($query);
		if(count($result) > 0){
			if($result[1]['name'] == $inputData['dabirkhaneh_name']){
				$output['error'] = 'دبیرخانه با این نام ثبت شده است';
				return $output;
			}
			if($result[1]['number_structure'] == $inputData['number_structure']){
				$output['error'] = 'دبیرخانه با این ساختار شماره نامه ثبت شده است';
				return $output;
			}
		}

		$query = '
			UPDATE `auto_dabirkhaneh`
			SET `name` = "'.$inputData['dabirkhaneh_name'].'",
				`number_structure` = "'.$inputData['number_structure'].'",
				`number_start` = "'.$inputData['number_start'].'",
				`letter_type` = "'.$inputData['letter_type'].'"
			WHERE `id` = "'.$inputData['id'].'";
		';
		executeQuery($query);

		$query = '
			select a.*
			from `auto_dabirkhaneh` as a
			where a.`deleted` = 0 AND a.`id` = "'.$inputData['id'].'";
		';
		$result = executeQuery($query);

		$this->empty_redis(['autoGetNumberStructure']);

		$output['data'] = $result[1];

		$message = 'دبیرخانه با موفقیت ویرایش شد';
		$output['data']['message'] = $message;
		return $output;
	}

	public function auto_delete_dabirkhaneh($inputData = [])
	{
		$output = [];
		
		if(empty($inputData['id'])){
			$output['error'] = 'شناسه حذف به درستی مشخص نشده است';
			return $output;
		}
		else if(empty($inputData['dabirkhaneh_name'])){
			$output['error'] = 'سازمان را مشخص کنید';
			return $output;
		}
		
		$query = '
			UPDATE `auto_dabirkhaneh`
			SET `deleted` = 1
			WHERE id = "'.$inputData['id'].'" AND `name` = "'.$inputData['dabirkhaneh_name'].'";
		';
		$result = executeQuery($query);
		
		$this->empty_redis(['autoSecretAccess', 'autoGetNumberStructure']);
		
		$output['data'] = [
			'message'=>'حذف با موفقیت انجام شد'
		];
		return $output;
	}

	public function auto_search_dabirkhaneh_user($id)
	{
		libXcrudLoad();
		$xcrud = Xcrud::get_instance();

		$xcrud->table('auto_dabirkhaneh_user');
		$xcrud->join2("USERS" , "b", "auto_dabirkhaneh_user.USR_UID = b.USR_UID");
		$xcrud->table_name('کاربران دبیرخانه');
		$xcrud->limit_list('10,25');

		$xcrud->columns(['fullname'=>'concat(b.USR_FIRSTNAME, " ", b.USR_LASTNAME)','supervisor','view_secret','view_secret_file']);
		$xcrud->label('fullname','کاربر');
		$xcrud->label('supervisor','سوپروایزر است');
		$xcrud->label('view_secret','مشاهده نامه های محرمانه');
		$xcrud->label('view_secret_file','مشاهده فایل های محرمانه');

		$xcrud->where('deleted =', 0);
		$xcrud->where('dabirkhaneh_id =', $id);

		$xcrud->change_type('supervisor','select','',array('values'=>array('0'=>'خیر','1'=>'بله')));
		$xcrud->change_type('view_secret','select','',array('values'=>array('0'=>'خیر','1'=>'بله')));
		$xcrud->change_type('view_secret_file','select','',array('values'=>array('0'=>'خیر','1'=>'بله')));

		$xcrud->button('javascript:edit_dabirkhaneh_user(\'{dabirkhaneh_id}\', \'{USR_UID}\', \'{supervisor}\', \'{view_secret}\', \'{view_secret_file}\', \'{fullname}\');', 'ویرایش', 'glyphicon glyphicon-edit');
		$xcrud->button('javascript:delete_dabirkhaneh_user(\'{dabirkhaneh_id}\', \'{USR_UID}\');', 'حذف', 'glyphicon glyphicon-remove');

		$xcrud->search_columns('fullname,supervisor,view_secret,view_secret_file','fullname');
		$xcrud->unset_excel(false);

		return $xcrud->render();
	}

	public function auto_add_dabirkhaneh_user($inputData = [])
	{
		$output = [];

		if(empty($inputData['dabirkhaneh'])){
			$output['error'] = 'دبیرخانه را مشخص کنید';
			return $output;
		}
		else if((empty($inputData['user']) && empty($inputData['group'])) || (!empty($inputData['user']) && !empty($inputData['group']))){
			$output['error'] = 'کاربر یا گروه کاربری را مشخص کنید';
			return $output;
		}

		if(!empty($inputData['user'])){
			$query = '
				select a.*
				from `auto_dabirkhaneh_user` as a
				where a.deleted = 0 and a.`dabirkhaneh_id` = "'.$inputData['dabirkhaneh'].'" AND a.USR_UID = "'.$inputData['user'].'";
			';
			$result = executeQuery($query);
			if(count($result) > 0){
				$output['error'] = 'این کاربر قبلا به دبیرخانه افزوده شده است';
				return $output;
			}

			$query = '
				INSERT INTO `auto_dabirkhaneh_user`(
					`dabirkhaneh_id`, `USR_UID`, `supervisor`, `view_secret`, `view_secret_file`, `insert_user`, `insert_date`
				)
				VALUES (
					"'.$inputData['dabirkhaneh'].'", "'.$inputData['user'].'", "'.$inputData['supervisor'].'", "'.$inputData['view_secret'].'", "'.$inputData['view_secret_file'].'",
					"'.$_SESSION['USER_LOGGED'].'", NOW()
				);
			';
			executeQuery($query);
		}
		else if(!empty($inputData['group'])){
			$not_insert = [];

			$query = '
				SELECT * FROM `GROUP_USER` 
				where `GRP_UID` = "'.$inputData['group'].'";
			';
			$result = executeQuery($query);
			if(count($result) > 0){
				foreach($result as $row){
					$query = '
						select a.*
						from `auto_dabirkhaneh_user` as a
						where a.deleted = 0 and a.`dabirkhaneh_id` = "'.$inputData['dabirkhaneh'].'" AND a.USR_UID = "'.$row['USR_UID'].'";
					';
					$result1 = executeQuery($query);
					if(count($result1) > 0){
						array_push($not_insert, $result1[1]['USR_UID']);
						continue;
					}

					$query = '
						INSERT INTO `auto_dabirkhaneh_user`(
							`dabirkhaneh_id`, `USR_UID`, `supervisor`, `view_secret`, `view_secret_file`, `insert_user`, `insert_date`
						)
						VALUES (
							"'.$inputData['dabirkhaneh'].'", "'.$row['USR_UID'].'", "'.$inputData['supervisor'].'", "'.$inputData['view_secret'].'", "'.$inputData['view_secret_file'].'",
							"'.$_SESSION['USER_LOGGED'].'", NOW()
						);
					';
					executeQuery($query);
				}
			}
		}

		$this->refresh_permissions();

		$result = [];
		if(!empty($inputData['user'])){
			$query = '
				SELECT a.*, concat(c.USR_FIRSTNAME, " ", c.USR_LASTNAME) as fullname
				FROM `auto_dabirkhaneh_user` as a join USERS as c on(a.USR_UID = c.USR_UID)
				where a.deleted = 0 and a.`dabirkhaneh_id` = "'.$inputData['dabirkhaneh'].'" AND a.USR_UID = "'.$inputData['user'].'";
			';
			$result = executeQuery($query);
		}
		else if(!empty($inputData['group'])){
			$query = '
				select a.*, concat(c.USR_FIRSTNAME, " ", c.USR_LASTNAME) as fullname
				from `auto_dabirkhaneh_user` as a join GROUP_USER as b on(a.USR_UID = b.USR_UID)
					 join USERS as c on(a.USR_UID = c.USR_UID)
				where a.deleted = 0 and a.`dabirkhaneh_id` = "'.$inputData['dabirkhaneh'].'" AND b.GRP_UID = "'.$inputData['group'].'"
					  '.(count($not_insert) > 0?' and a.USR_UID NOT IN ("'.implode('", "', $not_insert).'") ':'').';
			';
			$result = executeQuery($query);
		}

		$message = 'کاربران با موفقیت به دبیرخانه اضافه شدند';
		$output['data'] = [
			'result' => $result,
			'message' => $message
		];
		return $output;
	}

	public function auto_update_dabirkhaneh_user($inputData = [])
	{
		$output = [];

		if(empty($inputData['dabirkhaneh']) || empty($inputData['user'])){
			$output['error'] = 'شناسه ویرایش را به درستی مشخص کنید';
			return $output;
		}

		$query = '
			update `auto_dabirkhaneh_user`
			set supervisor = "'.$inputData['supervisor'].'",
				view_secret = "'.$inputData['view_secret'].'",
				view_secret_file = "'.$inputData['view_secret_file'].'",
				update_user = "'.$_SESSION['USER_LOGGED'].'",
				update_date = NOW()
			WHERE dabirkhaneh_id = "'.$inputData['dabirkhaneh'].'" AND `USR_UID` = "'.$inputData['user'].'";
		';
		executeQuery($query);

		$this->empty_redis(['autoSecretAccess']);

		$output['data'] = [
			'message'=>'ویرایش با موفقیت انجام شد'
		];
		return $output;
	}

	public function auto_delete_dabirkhaneh_user($inputData = [])
	{
		$output = [];

		if(empty($inputData['dabirkhaneh']) || empty($inputData['user'])){
			$output['error'] = 'شناسه حذف به درستی مشخص نشده است';
			return $output;
		}

		$query = '
			update `auto_dabirkhaneh_user`
			set deleted = 1,
				update_user = "'.$_SESSION['USER_LOGGED'].'",
				update_date = NOW()
			WHERE dabirkhaneh_id = "'.$inputData['dabirkhaneh'].'" AND `USR_UID` = "'.$inputData['user'].'";
		';
		executeQuery($query);

		$this->refresh_permissions();

		$this->empty_redis(['autoSecretAccess']);

		$output['data'] = [
			'message'=>'حذف با موفقیت انجام شد'
		];
		return $output;
	}

	/* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */

    public function auto_search_semat($inputData = [])
	{
        libXcrudLoad();
        $xcrud = Xcrud::get_instance();

        $xcrud->table('auto_semat');
        $xcrud->join2("USERS", "c","auto_semat.USR_UID = c.USR_UID and c.USR_STATUS <> 'CLOSED'");
        $xcrud->table_name('سمت ها');
        $xcrud->limit_list('10,25');
        $col = ['name'=>'concat(`c`.`USR_FIRSTNAME`, " ", `c`.`USR_LASTNAME`)','semat','in_active'];

        $xcrud->columns($col);
        $xcrud->label('name','نام و نام خانوادگی');
        $xcrud->label('semat','سمت');
        $xcrud->label('in_active','وضعیت');

        $xcrud->where('deleted =', 0);
        //$xcrud->where('in_active =', 0);
        $xcrud->order_by('id','desc');
		
		$xcrud->change_type('in_active','select','',array('values'=>array('1'=>'غیرفعال','0'=>'فعال')));

        $xcrud->button('javascript:edit_semat(\'{id}\', \'{USR_UID}\', \'{semat}\');', 'ویرایش', 'glyphicon glyphicon-edit', '', '', [], array('in_active','=',0));
        $xcrud->button('javascript:delete_semat(\'{id}\');', 'حذف', 'glyphicon glyphicon-remove', '', '', [], array('in_active','=',0));
        $xcrud->unset_excel(false);
        return $xcrud->render();
    }

    public function auto_add_semat($inputData = [])
	{
        $output = [];

        if(empty($inputData['USR_UID'])){
            $output['error'] = 'نام و نام خانوادگی را مشخص کنید';
            return $output;
        }
        else if(empty($inputData['semat'])){
            $output['error'] = 'سمت را مشخص کنید';
            return $output;
        }
        $query = '
			select *
			from `auto_semat`
			where `deleted` = 0 AND `USR_UID` = "'.$inputData['USR_UID'].'" AND `semat` = "'.$inputData['semat'].'";
		';
        $result = executeQuery($query);
        if(count($result) > 0){
           $output['error'] = 'سمت با این نام برای این کاربر ثبت شده است';
           return $output;
        }

        $query = '
            INSERT INTO `auto_semat`(
                `USR_UID`, `semat`, `insert_user`, `insert_date`
            )
            VALUES (
                "'.$inputData['USR_UID'].'", "'.$inputData['semat'].'", "'.$_SESSION['USER_LOGGED'].'", NOW()
            );
        ';
        $result = executeQuery($query);

        $message = 'سمت با موفقیت ثبت شد';
        $output['data']['message'] = $message;
        return $output;
    }

    public function auto_edit_semat($inputData = [])
	{
        $output = [];

        if(empty($inputData['USR_UID'])){
            $output['error'] = 'نام و نام خانوادگی را مشخص کنید';
            return $output;
        }
        else if(empty($inputData['semat'])){
            $output['error'] = 'سمت را مشخص کنید';
            return $output;
        }
        else if(empty($inputData['id'])){
            $output['error'] = 'شناسه ویرایش به درستی مشخص نشده است';
            return $output;
        }

        $query = '
			select *
			from `auto_semat`
			where `deleted` = 0 AND `USR_UID` = "'.$inputData['USR_UID'].'" AND `semat` = "'.$inputData['semat'].'" and id != "'.$inputData['id'].'";
		';
        $result = executeQuery($query);
        if(count($result) > 0){
            $output['error'] = 'سمت با این نام برای این کاربر ثبت شده است';
            return $output;
        }

        $query = '
			select id
			from `auto_letter_receiver`
			where `from_semat` = "'.$inputData['id'].'" OR `to_semat` = "'.$inputData['id'].'";
		';
        $result = executeQuery($query);
        if(count($result) > 0){
            $query = '
                UPDATE `auto_semat`
                SET `in_active` = "1",
					`update_user` = "'.$_SESSION['USER_LOGGED'].'",
					`update_date` = NOW()
                WHERE `id` = "' . $inputData['id'] . '";
                ';
            executeQuery($query);

			$query = '
                INSERT INTO `auto_semat`(
                    `USR_UID`, `semat`, `insert_user`, `insert_date`
                )
                VALUES (
                    "'.$inputData['USR_UID'].'", "'.$inputData['semat'].'", "'.$_SESSION['USER_LOGGED'].'", NOW()
                );
                ';
            executeQuery($query);
        }
        else{
            $query = '
                UPDATE `auto_semat`
                SET `USR_UID` = "' . $inputData['USR_UID'] . '",
                    `semat` = "' . $inputData['semat'] . '",
					`update_user` = "'.$_SESSION['USER_LOGGED'].'",
					`update_date` = NOW()
                WHERE `id` = "' . $inputData['id'] . '";
                ';
            executeQuery($query);
        }

        $message = 'سمت با موفقیت ویرایش شد';
        $output['data']['message'] = $message;
        return $output;
    }

    public function auto_delete_semat($inputData = [])
	{
        $output = [];
        if(empty($inputData['id'])){
            $output['error'] = 'شناسه حذف به درستی مشخص نشده است';
            return $output;
        }
        $query = '
			select id
			from `auto_letter_receiver`
			where `from_semat` = "'.$inputData['id'].'" OR `to_semat` = "'.$inputData['id'].'";
		';
        $result = executeQuery($query);
        if(count($result) > 0){
            $query = '
			UPDATE `auto_semat`
			SET `in_active` = 1
			WHERE id = "' . $inputData['id'] . '";
		    ';
        }
        else {
            $query = '
			UPDATE `auto_semat`
			SET `deleted` = 1
			WHERE id = "' . $inputData['id'] . '";
		    ';
        }
        executeQuery($query);

        $output['data'] = [
            'message'=>'حذف سمت با موفقیت انجام شد'
        ];
        return $output;
    }

    /* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */

	public function auto_get_variable_replace($text)
	{
		global $RedisObj;
		$memKey = 'autoGetVaiableReplace';
		if (!autoCheckRedis() || ($result = ($RedisObj->get($memKey)?json_decode($RedisObj->get($memKey), true):false)) === false) {
			$query = '
				select a.*
				from `auto_defined_variable` as a
				where 1;
			';
			$result = executeQuery($query);
			
			if(autoCheckRedis()){
				$RedisObj->set($memKey, json_encode($result));
				$RedisObj->expire($memKey, 8*60*60);
			}
		}

		if(count($result) > 0){
			$date_type = $this->auto_get_config('date_type');
			list($year, $month, $day) = ($date_type == 'm'?[date('Y'), date('m'), date('d')]:$this->persian_date_e2p(date('Y'), date('m'), date('d')));
			
			//$ld = explode('-', @$data['letter_date']);
			//list($year1, $month1, $day1) = ($date_type == 'm'?[$ld[0], $ld[1], $ld[2]]:$this->persian_date_e2p($ld[0], $ld[1], $ld[2]));
			
			foreach($result as $row){
				if(!stristr($text, $row['variable_key']))
					continue;
				if($row['variable_key'] == '{date}'){
					$text = str_replace($row['variable_key'], ($year.'/'.$month.'/'.$day), $text);
				}
				else if($row['variable_key'] == '{year}'){
					$text = str_replace($row['variable_key'], $year, $text);
				}
				else if($row['variable_key'] == '{min_year}'){
					$text = str_replace($row['variable_key'], substr($year, 2), $text);
				}
				else if($row['variable_key'] == '{month}'){
					$text = str_replace($row['variable_key'], $month, $text);
				}
				else if($row['variable_key'] == '{day}'){
					$text = str_replace($row['variable_key'], $day, $text);
				}
				/*else if($row['variable_key'] == '{letter_date}'){
					$text = str_replace($row['variable_key'], ($year1.'/'.$month1.'/'.$day1), $text);
				}
				else if($row['variable_key'] == '{letter_year}'){
					$text = str_replace($row['variable_key'], $year1, $text);
				}
				else if($row['variable_key'] == '{letter_month}'){
					$text = str_replace($row['variable_key'], $month1, $text);
				}
				else if($row['variable_key'] == '{letter_day}'){
					$text = str_replace($row['variable_key'], $day1, $text);
				}
				else if($row['variable_key'] == '{letter_time}'){
					$letter_date = explode(' ', $data['insert_date']);
					$text = str_replace($row['variable_key'], $letter_date[1], $text);
				}*/
				else if($row['type'] == 'variable' && !empty($row['variable_value'])){
					$text = str_replace($row['variable_key'], $row['variable_value'], $text);
				}
				else if($row['type'] == 'database'){
					try{
						$query = '
							select `'.$row['database_column'].'` as selected_column
							from `'.$row['database_name'].'`.`'.$row['database_table'].'`
							'.(!empty($row['database_where'])?'where '.$row['database_where']:'').'
						';
						$result1 = executeQuery($query);
						if(is_array($result1) && count($result1) > 0)
							$text = str_replace($row['variable_key'], $result1[1]['selected_column'], $text);
					} catch(Exception $e){
						error_log($e->getMessage(), 0);
					}
				}
			}
		}
		
		return $text;
	}

	public function auto_get_variables_data($inputData = [], $type = 'array')
	{
		global $RedisObj;
		$data = [];
		
		$memKey = 'autoGetVaiableData'.$inputData['template_id'];
		if (!autoCheckRedis() || ($result = ($RedisObj->get($memKey)?json_decode($RedisObj->get($memKey), true):false)) === false) {
			$query = '
				select b.*
				from `auto_template_variables` as a join `auto_defined_variable` as b on(a.variable_id = b.id)
				where `template_id` = "'.$inputData['template_id'].'";
			';
			$result = executeQuery($query);
			
			if(autoCheckRedis()){
				$RedisObj->set($memKey, json_encode($result));
				$RedisObj->expire($memKey, 8*60*60);
			}
		}

		if(count($result) > 0){
			$date_type = $this->auto_get_config('date_type');
			list($year, $month, $day) = ($date_type == 'm'?[date('Y'), date('m'), date('d')]:$this->persian_date_e2p(date('Y'), date('m'), date('d')));
			
			$ld = explode('-', @$inputData['letter_date']);
			list($year1, $month1, $day1) = ($date_type == 'm'?[$ld[0], $ld[1], $ld[2]]:$this->persian_date_e2p($ld[0], $ld[1], $ld[2]));
			
			foreach($result as $row){
				$key = str_replace('{', '', $row['variable_key']);
				$key = str_replace('}', '', $key);
				
				if($row['variable_key'] == '{date}'){
					$data[$key] = $year.'/'.$month.'/'.$day;
				}
				else if($row['variable_key'] == '{year}'){
					$data[$key] = $year;
				}
				else if($row['variable_key'] == '{min_year}'){
					$data[$key] = substr($year, 2);
				}
				else if($row['variable_key'] == '{month}'){
					$data[$key] = $month;
				}
				else if($row['variable_key'] == '{day}'){
					$data[$key] = $day;
				}
				else if($row['variable_key'] == '{letter_date}'){
					$data[$key] = $year1.'/'.$month1.'/'.$day1;
				}
				else if($row['variable_key'] == '{letter_year}'){
					$data[$key] = $year1;
				}
				else if($row['variable_key'] == '{letter_month}'){
					$data[$key] = $month1;
				}
				else if($row['variable_key'] == '{letter_day}'){
					$data[$key] = $day1;
				}
				else if($row['variable_key'] == '{letter_time}'){
					$insert_date = explode(' ', $inputData['insert_date']);
					$data[$key] = $insert_date[1];
				}
				else if($row['variable_key'] == '{peyvast}'){
					$peyvast = 'ندارد';
					$query = '
						select * from auto_letter_attachment
						where letter_id = "'.$inputData['letter_id'].'";
					';
					$result1 = executeQuery($query);
					if(is_array($result1) && count($result1) > 0)
						$peyvast = 'دارد';
					$data[$key] = $peyvast;
				}
				else if($row['type'] == 'variable' && !empty($row['variable_value'])){
					$data[$key] = $row['variable_value'];
				}
				else if($row['type'] == 'database'){
					$data[$key] = '';
					try{
						$query = '
							select `'.$row['database_column'].'` as selected_column
							from `'.$row['database_name'].'`.`'.$row['database_table'].'`
							'.(!empty($row['database_where'])?'where '.$row['database_where']:'').'
						';
						$result1 = executeQuery($query);
						if(is_array($result1) && count($result1) > 0)
							$data[$key] = $result1[1]['selected_column'];
					} catch(Exception $e){
						error_log($e->getMessage(), 0);
					}
				}
			}
		}
		
		if($type == 'json'){
			$data['temp'] = '';
			return json_encode($data, JSON_UNESCAPED_UNICODE);
		}
		return $data;
	}

	public function auto_search_variable($inputData = [])
	{
		libXcrudLoad();
		$xcrud = Xcrud::get_instance();

		$xcrud->table('auto_defined_variable');
		$xcrud->join2("auto_based_info" , "b", "auto_defined_variable.`type` = b.`value` and b.`type` = 'variable_type'");
		$xcrud->table_name('متغیرها');
		$xcrud->limit_list('10,25');

		$xcrud->columns(['name','variable_type_name'=>'b.name','variable_key','variable_value']);
		$xcrud->label('name','نام');
		$xcrud->label('variable_type_name','نوع');
		$xcrud->label('variable_key','کلمه کلیدی');
		$xcrud->label('variable_value','مقدار');

		$xcrud->where('deleted =', '0');

		$xcrud->button('javascript:edit_variable(\'{id}\', \'{name}\', \'{type}\', \'{variable_key}\', \'{variable_value}\', \'{database_name}\', \'{database_table}\', \'{database_column}\', \'{database_where}\');', 'ویرایش', 'glyphicon glyphicon-edit');
		$xcrud->button('javascript:delete_variable(\'{id}\', \'{variable_key}\');', 'حذف', 'glyphicon glyphicon-remove');

		$xcrud->search_columns('name,variable_type_name,variable_key,variable_value','name');
		$xcrud->unset_excel(false);

		return $xcrud->render();
	}

	public function auto_add_variable($inputData = [])
	{
		global $RedisObj;
		$output = [];
		
		if(empty($inputData['variable_name'])){
			$output['error'] = 'نام متغیر را مشخص کنید';
			return $output;
		}
		if(empty($inputData['variable_type'])){
			$output['error'] = 'نوع متغیر را مشخص کنید';
			return $output;
		}
		if(empty($inputData['variable_key'])){
			$output['error'] = 'کلمه کلیدی متغیر را مشخص کنید';
			return $output;
		}
		
		if($inputData['variable_type'] == 'database'){
			if(empty($inputData['database_name'])){
				$output['error'] = 'پایگاه داده را مشخص کنید';
				return $output;
			}
			if(empty($inputData['database_table'])){
				$output['error'] = 'جدول را مشخص کنید';
				return $output;
			}
			if(empty($inputData['database_column'])){
				$output['error'] = 'ستون را مشخص کنید';
				return $output;
			}
		}
		
		$query = '
			select a.*
			from `auto_defined_variable` as a
			where a.`deleted` = 0 AND a.`variable_key` = "'.$inputData['variable_key'].'";
		';
		$result = executeQuery($query);
		if(count($result) > 0){
			$output['error'] = 'این متغیر قبلا ثبت شده است';
			return $output;
		}
		
		$query = '
			INSERT INTO `auto_defined_variable`(
				`name`, `type`, `variable_key`, `variable_value`, `database_name`, `database_table`, `database_column`, `database_where`, `insert_user`, `insert_date`
			)
			VALUES (
				"'.$inputData['variable_name'].'", "'.$inputData['variable_type'].'", "'.$inputData['variable_key'].'", "'.$inputData['variable_value'].'",
				"'.$inputData['database_name'].'", "'.$inputData['database_table'].'", "'.$inputData['database_column'].'", "'.$inputData['database_where'].'", "'.$_SESSION['USER_LOGGED'].'", NOW()
			);
		';
		executeQuery($query);
		
		$query = '
			select a.*, b.`name` as variable_type_name
			from `auto_defined_variable` as a join `auto_based_info` as b on(a.`type` = b.`value` and b.`type` = "variable_type")
			where a.`deleted` = 0 AND a.`variable_key` = "'.$inputData['variable_key'].'";
		';
		$result = executeQuery($query);
		
		$output['data'] = $result[1];
		
		$this->empty_redis(['autoGetVaiableData']);
		
		$message = 'متغیر با موفقیت ثبت شد';
		$output['data']['message'] = $message;
		return $output;
	}

	public function auto_edit_variable($inputData = [])
	{
		global $RedisObj;
		$output = [];
		
		if(empty($inputData['variable_name'])){
			$output['error'] = 'نام متغیر را مشخص کنید';
			return $output;
		}
		if(empty($inputData['variable_type'])){
			$output['error'] = 'نوع متغیر را مشخص کنید';
			return $output;
		}
		if(empty($inputData['variable_key'])){
			$output['error'] = 'کلمه کلیدی متغیر را مشخص کنید';
			return $output;
		}
		if(empty($inputData['id'])){
			$output['error'] = 'شناسه ویرایش به درستی مشخص نشده است';
			return $output;
		}
		
		if($inputData['variable_type'] == 'database'){
			if(empty($inputData['database_name'])){
				$output['error'] = 'پایگاه داده را مشخص کنید';
				return $output;
			}
			if(empty($inputData['database_table'])){
				$output['error'] = 'جدول را مشخص کنید';
				return $output;
			}
			if(empty($inputData['database_column'])){
				$output['error'] = 'ستون را مشخص کنید';
				return $output;
			}
		}
		
		$query = '
			select a.*
			from `auto_defined_variable` as a
			where a.`deleted` = 0 AND a.`variable_key` = "'.$inputData['variable_key'].'" and a.`id` != "'.$inputData['id'].'";
		';
		$result = executeQuery($query);
		if(count($result) > 0){
			$output['error'] = 'این متغیر قبلا ثبت شده است';
			return $output;
		}
		
		$query = '
			UPDATE `auto_defined_variable`
			SET `name` = "'.$inputData['variable_name'].'",
				`type` = "'.$inputData['variable_type'].'",
				`variable_key` = "'.$inputData['variable_key'].'",
				`variable_value` = "'.$inputData['variable_value'].'",
				`database_name` = "'.$inputData['database_name'].'",
				`database_table` = "'.$inputData['database_table'].'", 
				`database_column` = "'.$inputData['database_column'].'",
				`database_where` = "'.$inputData['database_where'].'"
			WHERE `id` = "'.$inputData['id'].'";
		';
		executeQuery($query);
		
		$query = '
			select a.*, b.`name` as variable_type_name
			from `auto_defined_variable` as a join `auto_based_info` as b on(a.`type` = b.`value` and b.`type` = "variable_type")
			where a.`deleted` = 0 AND a.`id` = "'.$inputData['id'].'";
		';
		$result = executeQuery($query);
		
		$output['data'] = $result[1];
		
		$this->empty_redis(['autoGetVaiableData']);
		
		$message = 'متغیر با موفقیت ویرایش شد';
		$output['data']['message'] = $message;
		return $output;
	}

	public function auto_delete_variable($inputData = [])
	{
		global $RedisObj;
		$output = [];
		
		if(empty($inputData['id'])){
			$output['error'] = 'شناسه حذف به درستی مشخص نشده است';
			return $output;
		}
		else if(empty($inputData['variable_key'])){
			$output['error'] = 'متغیر را مشخص کنید';
			return $output;
		}
		
		$query = '
			UPDATE `auto_defined_variable`
			SET `deleted` = 1
			WHERE `type` != "variable" AND id = "'.$inputData['id'].'" AND `variable_key` = "'.$inputData['variable_key'].'";
		';
		$result = executeQuery($query);
		
		empty_redis($memKeys = ['autoGetVaiableData']);
		
		$output['data'] = [
			'message'=>'حذف با موفقیت انجام شد'
		];
		return $output;
	}

	/* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */

	public function auto_search_template($inputData = [])
	{
		$output = [];

		$where = 'a.`deleted` = 0';
		if(!empty($inputData['id']))
			$where .= ' and a.`id` = "'.$inputData['id'].'"';

		$query = '
			select a.*
			from `auto_template` as a
			where '.$where.'
			order by a.`name`;
		';
		$result = executeQuery($query);

		if(!empty($inputData['id']) && count($result) > 0){
			$result[1]['template_variables'] = [];
			
			$query = '
				select a.*, concat(b.`name`, " - ", b.variable_key) as `variable_name` 
				from `auto_template_variables` as a join auto_defined_variable as b on(a.variable_id = b.id and b.deleted = 0)
				where a.`template_id` = "'.$inputData['id'].'";
			';
			$result1 = executeQuery($query);
			
			$result[1]['template_variables'] = $result1;
		}

		return $result;
	}

	public function auto_add_template($inputData = [])
	{
		$output = [];
		
		if(empty($inputData['name'])){
			$output['error'] = 'نام قالب را مشخص کنید';
			return $output;
		}
		else if(empty($inputData['letter_type'])){
			$output['error'] = 'نوع نامه را مشخص کنید';
			return $output;
		}
        else if(empty($inputData['print_type'])){
			$output['error'] = 'نوع خروجی چاپ را مشخص کنید';
			return $output;
		}
		else if(empty($inputData['print_file_name'])){
			$output['error'] = 'فایل خروجی چاپ را مشخص کنید';
			return $output;
		}
        $inputData['letter_type'] = implode(',',$inputData['letter_type']);
		if(empty($inputData['id'])){
			$query = '
				select *
				from `auto_template`
				where deleted = 0 and `name` = "'.$inputData['name'].'";
			';
			$result = executeQuery($query);
			if(count($result) > 0){
				$output['error'] = 'این قالب قبلا تعریف شده است';
				return $output;
			}
		}
		else{
			$query = '
				select *
				from `auto_template`
				where deleted = 0 and `name` = "'.$inputData['name'].'" and id != "'.$inputData['id'].'";
			';
			$result = executeQuery($query);
			if(count($result) > 0){
				$output['error'] = 'نام قالب تکراری است';
				return $output;
			}
		}
		
		if($inputData['is_default'] == 1){
			$query = '
				UPDATE `auto_template`
				SET `is_default` = 0
				WHERE `deleted` = 0;
			';
			executeQuery($query);
		}
		if($inputData['is_default_export'] == 1){
			$query = '
				UPDATE `auto_template`
				SET `is_default_export` = 0
				WHERE `deleted` = 0;
			';
			executeQuery($query);
		}
		
		if(empty($inputData['id'])){
			$query = '
				INSERT INTO `auto_template`(
					`name`, `print_type`, `print_file_name`, `is_default`, `is_default_export`, `enable_editor`,`letter_type`, `insert_user`, `insert_date`
				)
				VALUES (
					"'.$inputData['name'].'", "'.$inputData['print_type'].'", "'.$inputData['print_file_name'].'", "'.$inputData['is_default'].'",
					"'.$inputData['is_default_export'].'", "'.$inputData['enable_editor'].'", "'.$inputData['letter_type'].'",
					"'.$_SESSION['USER_LOGGED'].'", NOW()
				);
			';
			executeQuery($query);
		}
		else{
			$query = '
				UPDATE `auto_template`
				SET `name` = "'.$inputData['name'].'",
					`letter_type` = "'.$inputData['letter_type'].'",
					`print_type` = "'.$inputData['print_type'].'",
					`print_file_name` = "'.$inputData['print_file_name'].'",
					`is_default` = "'.$inputData['is_default'].'",
					`is_default_export` = "'.$inputData['is_default_export'].'",
					`enable_editor` = "'.$inputData['enable_editor'].'",
					update_user = "'.$_SESSION['USER_LOGGED'].'",
					update_date = NOW()
				WHERE `deleted` = 0 AND `id` = "'.$inputData['id'].'";
			';
			executeQuery($query);
		}
		
		if(empty($inputData['id'])){
			$query = '
				select LAST_INSERT_ID() as last_insert_id;
			';
			$result = executeQuery($query);
			if(count($result) > 0){
				$template_id = $result[1]['last_insert_id'];
			}
		}
		else{
			$template_id = $inputData['id'];
			$query = '
				DELETE FROM `auto_template_variables`
				WHERE `template_id` = "'.$template_id.'";
			';
			executeQuery($query);
		}
		
		$template_variables = $inputData['template_variables'];
		if(!is_null($template_variables)){
			for($i=0;$i<count($template_variables);$i++){
				$query = '
					INSERT INTO `auto_template_variables`(
						`template_id`, `variable_id`
					)
					VALUES (
						"'.$template_id.'", "'.$template_variables[$i].'"
					);
				';
				executeQuery($query);
			}
		}
		
		if(empty($inputData['id']))
			$message = 'قالب با موفقیت ثبت شد';
		else
			$message = 'قالب با موفقیت ویرایش شد';
		$output['data']['message'] = $message;
		return $output;
	}

	public function auto_delete_template($inputData = [])
	{
		$output = [];
		
		if(empty($inputData['id']) || empty($inputData['name'])){
			$output['error'] = 'شناسه حذف به درستی مشخص نشده است';
			return $output;
		}
		
		$query = '
			UPDATE `auto_template`
			SET `deleted` = 1,
				update_user = "'.$_SESSION['USER_LOGGED'].'",
				update_date = NOW()
			WHERE `is_default` = 0 AND `is_default_export` = 0 AND `id` = "'.$inputData['id'].'" AND `name` = "'.$inputData['name'].'";
		';
		$result = executeQuery($query);
		if(!$result)
			$output['error'] = 'امکان حذف قالب پیش فرض نمی باشد.';
		else{
			$output['data'] = [
				'message'=>'حذف با موفقیت انجام شد'
			];
		}
		return $output;
	}

	/* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */

	public function auto_search_custom_group($inputData = [])
	{
		libXcrudLoad();
		$xcrud = Xcrud::get_instance();
		
		$xcrud->table('auto_custom_group');
		$xcrud->table_name('گروه ها');
		$xcrud->limit_list('10,25');
		
		$xcrud->columns('name');
		$xcrud->label('name','نام');
		
		$xcrud->where('deleted =', 0);
		$xcrud->where('USR_UID =', $_SESSION['USER_LOGGED']);
		
		$xcrud->button('javascript:edit_custom_group(\'{id}\', \'{name}\');', 'ویرایش', 'glyphicon glyphicon-edit');
		$xcrud->button('javascript:delete_custom_group(\'{id}\', \'{name}\');', 'حذف', 'glyphicon glyphicon-remove');
		$xcrud->button('javascript:custom_group_user(\'{id}\');', 'کاربران', 'glyphicon glyphicon-user');
		
		$xcrud->search_columns('name','name');
		$xcrud->unset_excel(false);

		return $xcrud->render();
	}

	public function auto_add_custom_group($inputData = [])
	{
		$output = [];
		
		if(empty($inputData['custom_group_name'])){
			$output['error'] = 'نام گروه را مشخص کنید';
			return $output;
		}
		
		$query = '
			select a.*
			from `auto_custom_group` as a
			where a.`deleted` = 0 AND a.`name` = "'.$inputData['custom_group_name'].'";
		';
		$result = executeQuery($query);
		if(count($result) > 0){
			$output['error'] = 'گروه با این نام قبلا ثبت شده است';
			return $output;
		}
		
		$query = '
			INSERT INTO `auto_custom_group`(
				`USR_UID`, `name`, `insert_user`, `insert_date`
			)
			VALUES (
				"'.$_SESSION['USER_LOGGED'].'", "'.$inputData['custom_group_name'].'", "'.$_SESSION['USER_LOGGED'].'", NOW()
			);
		';
		executeQuery($query);
		
		$query = '
			select a.*
			from `auto_custom_group` as a
			where a.`deleted` = 0 AND a.`name` = "'.$inputData['custom_group_name'].'";
		';
		$result = executeQuery($query);
		
		$output['data'] = $result[1];
		
		$message = 'گروه شخصی با موفقیت ثبت شد';
		$output['data']['message'] = $message;
		return $output;
	}

	public function auto_edit_custom_group($inputData = [])
	{
		$output = [];
		
		if(empty($inputData['custom_group_name'])){
			$output['error'] = 'نام گروه را مشخص کنید';
			return $output;
		}
		else if(empty($inputData['id'])){
			$output['error'] = 'شناسه ویرایش به درستی مشخص نشده است';
			return $output;
		}
		
		$query = '
			select a.*
			from `auto_custom_group` as a
			where a.`deleted` = 0 AND a.`name` = "'.$inputData['custom_group_name'].'" AND a.`id` != "'.$inputData['id'].'";
		';
		$result = executeQuery($query);
		if(count($result) > 0){
			$output['error'] = 'گروه با این نام قبلا ثبت شده است';
			return $output;
		}
		
		$query = '
			UPDATE `auto_custom_group`
			SET `name` = "'.$inputData['custom_group_name'].'"
			WHERE `id` = "'.$inputData['id'].'";
		';
		executeQuery($query);
		
		$query = '
			select a.*
			from `auto_custom_group` as a
			where a.`deleted` = 0 AND a.`name` = "'.$inputData['custom_group_name'].'";
		';
		$result = executeQuery($query);
		
		$output['data'] = $result[1];
		
		$message = 'گروه شخصی با موفقیت ویرایش شد';
		$output['data']['message'] = $message;
		return $output;
	}

	public function auto_delete_custom_group($inputData = [])
	{
		$output = [];
		
		if(empty($inputData['id'])){
			$output['error'] = 'شناسه حذف به درستی مشخص نشده است';
			return $output;
		}
		else if(empty($inputData['custom_group_name'])){
			$output['error'] = 'گروه را مشخص کنید';
			return $output;
		}
		
		$query = '
			UPDATE `auto_custom_group`
			SET `deleted` = 1
			WHERE id = "'.$inputData['id'].'" AND `name` = "'.$inputData['custom_group_name'].'";
		';
		$result = executeQuery($query);
		
		$output['data'] = [
			'message'=>'حذف با موفقیت انجام شد'
		];
		return $output;
	}

	public function auto_search_custom_group_user($inputData = [])
	{
		$query = '
			select a.*
			from `auto_custom_group_user` as a
			where a.`custom_group_id` = "'.$inputData['id'].'"
			order by a.`custom_group_id`, a.`USR_UID`;
		';
		return executeQuery($query);
	}

	public function auto_add_custom_group_user($inputData = [])
	{
		$output = [];
		
		if(empty($inputData['custom_group'])){
			$output['error'] = 'گروه را مشخص کنید';
			return $output;
		}
		else if(empty($inputData['user'])){
			$output['error'] = 'کاربر را مشخص کنید';
			return $output;
		}
		
		$query = '
			select a.*
			from `auto_custom_group_user` as a
			where a.`custom_group_id` = "'.$inputData['custom_group'].'" AND a.USR_UID = "'.$inputData['user'].'";
		';
		$result = executeQuery($query);
		if(count($result) > 0){
			$output['error'] = 'این کاربر قبلا به گروه افزوده شده است';
			return $output;
		}
		
		$query = '
			INSERT INTO `auto_custom_group_user`(
				`custom_group_id`, `USR_UID`, `insert_user`, `insert_date`
			)
			VALUES (
				"'.$inputData['custom_group'].'", "'.$inputData['user'].'", "'.$_SESSION['USER_LOGGED'].'", NOW()
			);
		';
		executeQuery($query);
		
		$query = '
			select a.*
			from `auto_custom_group_user` as a
			where a.`custom_group_id` = "'.$inputData['custom_group'].'" AND a.USR_UID = "'.$inputData['user'].'";
		';
		$result = executeQuery($query);
		
		$this->empty_redis(['autoShowUserFromGroup']);
		
		$output['data'] = $result[1];
		
		$message = 'کاربر با موفقیت به گروه افزوده شد';
		$output['data']['message'] = $message;
		return $output;
	}

	public function auto_delete_custom_group_user($inputData = [])
	{
		$output = [];
		
		if(empty($inputData['custom_group']) || empty($inputData['user'])){
			$output['error'] = 'شناسه حذف به درستی مشخص نشده است';
			return $output;
		}
		
		$query = '
			DELETE FROM `auto_custom_group_user`
			WHERE custom_group_id = "'.$inputData['custom_group'].'" AND `USR_UID` = "'.$inputData['user'].'";
		';
		$result = executeQuery($query);

		$this->empty_redis(['autoShowUserFromGroup']);

		$output['data'] = [
			'message'=>'حذف با موفقیت انجام شد'
		];
		return $output;
	}

	/* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */

	public function auto_insert_letter($inputData)
	{
		global $RedisObj;

		$number_structure = $this->auto_get_number_structure($inputData['dabirkhaneh_id']);
		if(empty($number_structure))
			return -1;

		//بررسی ارجاعات
		$receivers = 0;
		foreach($inputData['grid_receiver'] as $row){
			if(!empty($row['grid_suggest_user'])){
				if($inputData['security'] == 'secret' || $inputData['security'] == 'hard_secret'){
					$memKey = 'autoSecretAccess'.$row['grid_suggest_user'];
					if (!autoCheckRedis() || ($result = ($RedisObj->get($memKey)?json_decode($RedisObj->get($memKey), true):false)) === false) {
						$query = '
							select *
							from auto_dabirkhaneh_user
							where deleted = 0 and view_secret = 1 and USR_UID = "'.$row['grid_suggest_user'].'"
						';
						$result = executeQuery($query);

						if(autoCheckRedis()){
							$RedisObj->set($memKey, json_encode($result));
							$RedisObj->expire($memKey, 8*60*60);
						}
					}

					if(!(is_array($result) && count($result) > 0))
						continue;
				}

				$receivers++;
			}
		}
		if(!$receivers)
			return -2;

		require_once(PATH_PLUGINS . 'automation/classes/class.sequence.php');
		$sequence = new Sequence();
		$number = $sequence->sequenceNumber($number_structure, $inputData['dabirkhaneh_id']);
		/*$sequence->lockSequenceTable();
		$number = $sequence->getSequeceNumber($number_structure, $inputData['dabirkhaneh_id']);
		$sequence->changeSequence();
		$sequence->unlockSequenceTable();*/

		$number = $number_structure.$number;

		$template_id = 0;
		if($inputData['letter_type'] != 'import'){
			$template = explode( '_', $inputData['template_id'] );
			$template_id = $template[0];
		}

		if(!strstr($inputData['template_id'], '_Word_0')){
			$inputData['content'] = str_replace('&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD XHTML 1.0 Transitional//EN&quot; &quot;http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd&quot;&gt;', '', $inputData['content']);
			$inputData['content'] = str_replace('&lt;!DOCTYPE html&gt;', '', $inputData['content']);
			$inputData['content'] = str_replace('&lt;html&gt;', '', $inputData['content']);
			$inputData['content'] = str_replace('&lt;head&gt;', '', $inputData['content']);
			$inputData['content'] = str_replace('&lt;/head&gt;', '', $inputData['content']);
			$inputData['content'] = str_replace('&lt;body&gt;', '', $inputData['content']);
			$inputData['content'] = str_replace('&lt;/body&gt;', '', $inputData['content']);
			$inputData['content'] = str_replace('&lt;/html&gt;', '', $inputData['content']);
			$inputData['content'] = trim($inputData['content']);

			if(strstr($inputData['template_id'], '_Word_1')){
				$word = $this->auto_open_word_file(['template' => $inputData['template_id']]);
				$inputData['word_name'] = $word['data'];
			}
		}
		else
			$inputData['content'] = '';

		$inputData['organization_id'] = (empty($inputData['organization_id'])?0:$inputData['organization_id']);
		$inputData['organization_persons_id'] = (empty($inputData['organization_persons_id'])?0:$inputData['organization_persons_id']);

		$con = Propel::getConnection('workflow');
		$stmt = $con->createStatement();

		$query = '
			INSERT INTO `auto_letter`(
				`number`, `subject`, `content`, `letter_type`, `draft`, `dabirkhaneh_id`, `template_id`, `organization_id`, `organization_persons_id`,
				`security`, `priority`, `letter_title`, `letter_unit`, `letter_date`, `letter_tag`,
				`internal_letter_number`, `internal_letter_date`, `internal_letter_receive_type`, `word_name`,
				`insert_user`, `insert_date`
			)
			VALUES (
				"'.$number.'", "'.$inputData['subject'].'", "'.$inputData['content'].'", "'.$inputData['letter_type'].'", "'.$inputData['draft'].'",
				"'.$inputData['dabirkhaneh_id'].'", "'.$template_id.'", "'.$inputData['organization_id'].'", "'.$inputData['organization_persons_id'].'",
				"'.$inputData['security'].'", "'.$inputData['priority'].'", "'.$inputData['letter_title'].'", "'.$inputData['letter_unit'].'",
				"'.$inputData['letter_date'].'", "'.$inputData['letter_tag'].'",
				"'.$inputData['internal_letter_number'].'", "'.$inputData['internal_letter_date'].'", "'.$inputData['internal_letter_receive_type'].'",
				"'.$inputData['word_name'].'", "'.$_SESSION['USER_LOGGED'].'", NOW()
			);
		';
		$stmt->executeQuery($query);

		//if($result){
			$query = '
				select LAST_INSERT_ID() as last_insert_id;
			';
			$letterResult = $stmt->executeQuery($query, ResultSet::FETCHMODE_ASSOC);
			while ($letterResult->next()) {
				$row = $letterResult->getRow();
				$letter_id = $row['last_insert_id'];

				if($inputData['sign'] == 1){
					//درج امضا کننده
					$query = '
						INSERT INTO `auto_letter_sign`(
							`letter_id`, `USR_UID`, `insert_user`, `insert_date`
						)
						VALUES (
							"'.$letter_id.'", "'.$_SESSION['USER_LOGGED'].'", "'.$_SESSION['USER_LOGGED'].'", NOW()
						);
					';
					$stmt->executeQuery($query);
				}

				/*if($inputData['draft'] == 1){
					//درج پیش نویس
					foreach($inputData['grid_receiver_draft'] as $row){
						if(!empty($row['grid_draft_suggest_user'])){
							$grid_draft_datetime_due_date = ($this->validateDate($row['grid_draft_datetime_due_date'])?$row['grid_draft_datetime_due_date']:date("Y-m-d", strtotime('tomorrow')));
							$query = '
								INSERT INTO `auto_letter_receiver`(
									`letter_id`, `from_user`, `to_user`, `comment`, `receiver_type`, `from_APP_UID`, `to_APP_UID`, `due_date`,
									`insert_user`, `insert_date`
								)
								VALUES (
									"'.$letter_id.'", "'.$_SESSION['USER_LOGGED'].'", "'.$row['grid_draft_suggest_user'].'", "'.$row['grid_draft_textarea_comment'].'",
									"draft", "'.$inputData['application'].'", "'.$inputData['application'].'", "'.$grid_draft_datetime_due_date.'", "'.$_SESSION['USER_LOGGED'].'", NOW()
								);
							';
							$stmt->executeQuery($query);
						}
						break;
					}
				}*/

				//درج ارجاعات
				foreach($inputData['grid_receiver'] as $key=>$row){
					if(!empty($row['grid_suggest_user'])){
						if($inputData['security'] == 'secret' || $inputData['security'] == 'hard_secret'){
							$memKey = 'autoSecretAccess'.$row['grid_suggest_user'];
							if (!autoCheckRedis() || ($result = ($RedisObj->get($memKey)?json_decode($RedisObj->get($memKey), true):false)) === false) {
								$query = '
									select *
									from auto_dabirkhaneh_user
									where deleted = 0 and view_secret = 1 and USR_UID = "'.$row['grid_suggest_user'].'"
								';
								$result = executeQuery($query);

								if(autoCheckRedis()){
									$RedisObj->set($memKey, json_encode($result));
									$RedisObj->expire($memKey, 8*60*60);
								}
							}

							if(!(is_array($result) && count($result) > 0))
								continue;
						}

						$processData = [
							'letter_id' => $letter_id,
							'letter_type' => $inputData['letter_type'],
							'due_date' => $row['grid_datetime_due_date'],
							'letter_number' => $number,
							'letter_subject' => $inputData['subject'],
							'organization_name' => $inputData['organization_name'],
							'letter_comment' => $row['grid_textarea_comment'],
							'letter_content' => htmlspecialchars_decode($inputData['content'])
						];
						$new_app = $this->auto_create_new_case($row['grid_suggest_user'], $processData, 'TO_DO');
						$inputData['grid_receiver'][$key]['new_app'] = $new_app;

						$grid_datetime_due_date = ($this->validateDate($row['grid_datetime_due_date'])?$row['grid_datetime_due_date']:date("Y-m-d", strtotime('tomorrow')));
						$query = '
							INSERT INTO `auto_letter_receiver`(
								`letter_id`, `from_user`, `to_user`,`from_semat`, `to_semat`, `comment`, `receiver_type`, `receive_type`, `from_APP_UID`, `to_APP_UID`, `due_date`,
								`insert_user`, `insert_date`
							)
							VALUES (
								"'.$letter_id.'", "'.$_SESSION['USER_LOGGED'].'", "'.$row['grid_suggest_user'].'", "'.$inputData['from_semat'].'", "'.$row['semat_receive'].'", "'.$row['grid_textarea_comment'].'",
								"'.$row['grid_dropdown_receiver_type'].'", "'.$row['grid_receive_type'].'", "'.$inputData['application'].'", "'.$new_app.'", "'.$grid_datetime_due_date.'",
								"'.$_SESSION['USER_LOGGED'].'", NOW()
							);
						';
						$stmt->executeQuery($query);
					}
				}

				//درج پیوست ها
				$attachments = [];
				foreach($inputData['file_internal_letter'] as $row){
					$attach = $row['appDocUid'];
					$query = '
						INSERT INTO `auto_letter_attachment`(
							`letter_id`, `from_user`, `attach`, `comment`, `from_APP_UID`, `secret`, `attach_type`, `insert_user`, `insert_date`
						)
						VALUES (
							"'.$letter_id.'", "'.$_SESSION['USER_LOGGED'].'", "'.$attach.'", "فایل نامه ورودی",
							"'.$inputData['application'].'", "0", "letter", "'.$_SESSION['USER_LOGGED'].'", NOW()
						);
					';
					$stmt->executeQuery($query);

					$temp = [
						'attach' => $attach,
						'comment' => '',
						'name' => $row['name']
					];
					array_push($attachments, $temp);
				}
				foreach($inputData['grid_attachment'] as $row){
					if(!isset($row['grid_file_attach'][0]['appDocUid']))
						continue;
					$attach = $row['grid_file_attach'][0]['appDocUid'];
					$query = '
						INSERT INTO `auto_letter_attachment`(
							`letter_id`, `from_user`, `attach`, `comment`, `from_APP_UID`, `secret`, `insert_user`, `insert_date`
						)
						VALUES (
							"'.$letter_id.'", "'.$_SESSION['USER_LOGGED'].'", "'.$attach.'", "'.$row['grid_attach_textarea_comment'].'",
							"'.$inputData['application'].'", "'.$row['secret'].'", "'.$_SESSION['USER_LOGGED'].'", NOW()
						);
					';
					$stmt->executeQuery($query);

					$temp = [
						'attach' => $attach,
						'comment' => $row['grid_attach_textarea_comment'],
						'name' => $row['grid_file_attach'][0]['name']
					];
					array_push($attachments, $temp);
				}

				if($inputData['letter_type'] != 'import'){
					if(!strstr($inputData['template_id'], '_Word_'))
						$this->auto_set_content($letter_id);
					else if(function_exists('loadTemplateMaker')){
						$tm = loadTemplateMaker();
						$tm->tm_auto_move_template($inputData['word_name']);
						if(strstr($inputData['template_id'], '_Word_1'))
							$tm->tm_auto_set_html_data('html', $inputData['content'], $inputData['word_name']);
					}
				}

				if($inputData['letter_type'] == 'export'){
					//درج رونوشت به سازمان ها
					foreach($inputData['grid_organization_ronevesht'] as $row){
						if(!empty($row['export_organization_1'])){
							$query = '
								INSERT INTO `auto_letter_organization_ronevesht`(
									`letter_id`, `organization_id`, `organization_persons_id`, `insert_user`, `insert_date`
								)
								VALUES (
									"'.$letter_id.'", "'.$row['export_organization_1'].'", "'.($row["organization_person_1"] == ""?'0':$row["organization_person_1"]).'", "'.$_SESSION['USER_LOGGED'].'", NOW()
								);
							';
							$stmt->executeQuery($query);
						}
					}

					$memKey = 'autoWebServiceSettings';
					if (!autoCheckRedis() || ($params = ($RedisObj->get($memKey)?json_decode($RedisObj->get($memKey), true):false)) === false) {
						//ارسال از طریق وب سرویس
						$params = [
							'another_server_relation' => 0,
							'another_server_org_id' => 0,
							'another_server_address' => '',
							'another_server_key' => ''
						];

						$query = '
							select * from `auto_settings`
						';
						$result = executeQuery($query);
						if(count($result)){
							foreach($result as $row){
								if($row['config'] == 'another_server_relation')
									$params['another_server_relation'] = $row['config_value'];
								else if($row['config'] == 'another_server_org_id')
									$params['another_server_org_id'] = $row['config_value'];
								else if($row['config'] == 'another_server_address')
									$params['another_server_address'] = $row['config_value'];
								else if($row['config'] == 'another_server_key')
									$params['another_server_key'] = $row['config_value'];
							}
						}

						if(autoCheckRedis()){
							$RedisObj->set($memKey, json_encode($params));
							$RedisObj->expire($memKey, 8*60*60);
						}
					}

					foreach($params as $key=>$value)
						$$key = $value;

					if($another_server_relation == 1 && $another_server_org_id == $inputData['organization_id'] && !empty($another_server_address) && !empty($another_server_key)){
						$ch = curl_init();

						curl_setopt($ch, CURLOPT_URL, $another_server_address);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt(
							$ch, CURLOPT_POSTFIELDS, 
							"priority=".$inputData['priority']."&security=".$inputData['security']."&internal_letter_receive_type=webservice".
							"&internal_letter_number=".$number."&internal_letter_date=".date('Y-m-d')."&subject=".$inputData['subject']."&server_key=".$another_server_key.
							"&attachment=".serialize($attachments)
						);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

						$headers = [];
						$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:69.0) Gecko/20100101 Firefox/69.0';
						$headers[] = 'Accept: */*';
						$headers[] = 'Accept-Language: en-US,en;q=0.5';
						$headers[] = 'X-Requested-With: XMLHttpRequest';
						$headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
						$headers[] = 'Dnt: 1';
						$headers[] = 'Connection: keep-alive';
						$headers[] = 'Referer: '.$another_server_address;
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

						$result = curl_exec($ch);
						if (curl_errno($ch)) {
							echo 'Error:' . curl_error($ch);
						}
						curl_close($ch);
					}
				}

				if($inputData['letter_type'] != 'import')
					$this->auto_show_print(['letter_id' => $letter_id, 'from'=> 'direct']);
				foreach($inputData['grid_receiver'] as $row){
					if(!empty($row['grid_suggest_user'])){
						$processData = [
							'new_app' =>  $row['new_app'], 
							'letter_id' => $letter_id, 
							'letter_type' => $inputData['letter_type'], 
							'subject' => $inputData['subject'], 
							'letter_number' => $number, 
							'letter_comment' => $row['grid_textarea_comment'],
							'word_name' => $inputData['word_name'],
							'letter_content' => htmlspecialchars_decode($inputData['content'])
						];
						$this->auto_insert_notification($row['grid_suggest_user'], $processData);
					}
				}
			}
		//}

		return $number;
	}

	public function auto_send_letter($inputData)
	{
		if(!empty($inputData['letter_id']) && !empty($inputData['letter_type'])){
			$letter_id = $inputData['letter_id'];
			
			$number = 0;
			$content = '';
			$query = '
				select `number`, `content`, `word_name`
				from `auto_letter`
				where `id` = "'.$letter_id.'"
			';
			$result = executeQuery($query);
			if(count($result)){
				$number = $result[1]['number'];
				$content = $result[1]['content'];
			}
			else
				return 0;
			
			//درج ارجاعات
			foreach($inputData['grid_receiver'] as $row){
				if(!empty($row['grid_suggest_user'])){
					$processData = [
						'letter_id' => $letter_id, 
						'letter_type' => $inputData['letter_type'],
						'due_date' => $row['grid_datetime_due_date'],
						'letter_number' => $number,
						'letter_subject' => $inputData['subject'],
						'organization_name' => $inputData['organization_name'],
						'letter_comment' => $row['grid_textarea_comment'],
						'letter_content' => htmlspecialchars_decode($content)
					];
					$new_app = $this->auto_create_new_case($row['grid_suggest_user'], $processData, 'TO_DO');

					$processData = [
						'new_app' => $new_app, 
						'letter_id' => $letter_id, 
						'letter_type' => $inputData['letter_type'], 
						'subject' => $inputData['subject'], 
						'letter_number' => $number, 
						'word_name' => $row['word_name'],
						'letter_comment' => $row['grid_textarea_comment'],
						'letter_content' => htmlspecialchars_decode($content)
					];
					$this->auto_insert_notification($row['grid_suggest_user'], $processData);
					
					$grid_datetime_due_date = ($this->validateDate($row['grid_datetime_due_date'])?$row['grid_datetime_due_date']:date("Y-m-d", strtotime('tomorrow')));
					$query = '
						INSERT INTO `auto_letter_receiver`(
							`letter_id`, `from_user`, `to_user`,`from_semat`, `to_semat`, `comment`, `receiver_type`, `receive_type`, `from_APP_UID`, `to_APP_UID`, `due_date`,
							`insert_user`, `insert_date`
						)
						VALUES (
							"'.$letter_id.'", "'.$_SESSION['USER_LOGGED'].'", "'.$row['grid_suggest_user'].'", "'.$inputData['from_semat'].'", "'.$row['semat_receive'].'", "'.$row['grid_textarea_comment'].'",
							"'.$row['grid_dropdown_receiver_type'].'", "'.$row['grid_receive_type'].'", "'.$inputData['application'].'", "'.$new_app.'", "'.$grid_datetime_due_date.'",
							"'.$_SESSION['USER_LOGGED'].'", NOW()
						);
					';
					executeQuery($query);

					$query = '
						UPDATE `auto_letter`
						SET `status` = 0
						where id = "'.$inputData['letter_id'].'"
					';
					executeQuery($query);
				}
			}

			//درج پیوست ها
			foreach($inputData['grid_attachment'] as $row){
				if(!isset($row['grid_file_attach'][0]['appDocUid']))
					continue;
				$attach = $row['grid_file_attach'][0]['appDocUid'];
				$query = '
					INSERT INTO `auto_letter_attachment`(
						`letter_id`, `from_user`, `attach`, `comment`, `from_APP_UID`, `insert_user`, `insert_date`
					)
					VALUES (
						"'.$letter_id.'", "'.$_SESSION['USER_LOGGED'].'", "'.$attach.'", "'.$row['grid_attach_textarea_comment'].'",
						"'.$inputData['application'].'", "'.$_SESSION['USER_LOGGED'].'", NOW()
					);
				';
				executeQuery($query);
			}
			return 1;
		}
		return 0;
	}

	public function auto_edit_status($inputData)
	{
		$query = '
			UPDATE `auto_letter_receiver`
			SET `status` = 1
			where letter_id = "'.$inputData['letter_id'].'" and to_APP_UID = "'.$inputData['application'].'"
		';
		executeQuery($query);

		$query = '
			select letter_id
			from `auto_letter_receiver`
			where letter_id = "'.$inputData['letter_id'].'" and status = 0
		';
		$result = executeQuery($query);
		if(count($result) == 0){
			$query = '
				UPDATE `auto_letter`
				SET `status` = 1
				where id = "'.$inputData['letter_id'].'"
			';
			executeQuery($query);
		}
	}

	public function auto_end_letter($inputData)
	{
		if(!empty($inputData['letter_id']) && !empty($inputData['letter_type'])){
			//درج یادداشت اختتام
			if(!empty($inputData['textarea_end_note'])){
				$query = '
					select id
					from `auto_letter_receiver`
					where letter_id = "'.$inputData['letter_id'].'" and to_APP_UID = "'.$inputData['application'].'"
				';
				$result = executeQuery($query);

				if(is_array($result) && count($result) > 0){
					$query = '
						INSERT INTO `auto_letter_actions`(
							`letter_receiver_id`, `action`, `insert_user`, `insert_date`
						)
						VALUES (
							"'.$result[1]['id'].'", "'.$inputData['textarea_end_note'].'", "'.$_SESSION['USER_LOGGED'].'", NOW()
						);
					';
					executeQuery($query);
				}
			}

			//درج پیوست اختتام
			if(is_array($inputData['file_end_attach']) && count($inputData['file_end_attach'])>0){
				$attach = $inputData['file_end_attach'][0]['appDocUid'];

				$query = '
					INSERT INTO `auto_letter_attachment`(
						`letter_id`, `from_user`, `attach`, `comment`, `from_APP_UID`, `insert_user`, `insert_date`
					)
					VALUES (
						"'.$inputData['letter_id'].'", "'.$_SESSION['USER_LOGGED'].'", "'.$attach.'", "'.$inputData['textarea_comment_end_attach'].'",
						"'.$inputData['application'].'", "'.$_SESSION['USER_LOGGED'].'", NOW()
					);
				';
				executeQuery($query);
			}
		}
	}

	public function search_letter($inputData = [])
	{
		libXcrudLoad();
		$xcrud = Xcrud::get_instance();

		$xcrud->table('auto_letter');
		$xcrud->join2("auto_letter_receiver" , "c", "auto_letter.id = c.letter_id");
		$xcrud->join2("auto_based_info" , "d", "auto_letter.letter_type = d.value and d.deleted = 0");
		$xcrud->join2("auto_dabirkhaneh" , "e", "auto_letter.dabirkhaneh_id = e.id");
		//$xcrud->join2("auto_based_info" , "f", "auto_letter.priority = f.value and f.deleted = 0");
		//$xcrud->join2("auto_based_info" , "g", "auto_letter.security = g.value and g.deleted = 0");
		$xcrud->join2("USERS" , "h", "c.from_user = h.USR_UID");
		$xcrud->join2("USERS" , "i", "c.to_user = i.USR_UID");
		$xcrud->join2("auto_template" , "b", "auto_letter.template_id = b.`id`", "left");
		//$xcrud->join2("auto_letter_attachment" , "j", "auto_letter.id = j.letter_id", "left");
		//$xcrud->join2("auto_letter_actions" , "k", "c.id = k.letter_receiver_id and k.deleted = 0", "left");
		$xcrud->join2("auto_dabirkhaneh_user" , "l", "l.dabirkhaneh_id = auto_letter.dabirkhaneh_id and l.deleted = 0", "left");
		$xcrud->join2("auto_organization" , "m", "auto_letter.organization_id = m.`id`", "left");
		//$xcrud->join2("auto_organization_persons" , "n", "auto_letter.organization_persons_id = n.`id`", "left");
		$xcrud->table_name('آرشیو نامه ها');
		$xcrud->limit_list('10,25');

		$xcrud->columns(['letter_type_name'=>'d.name','dabirkhaneh_name'=>'e.name','j_letter_date'=>'n2_date(letter_date)','number','subject','from_user_name'=>'concat(h.USR_FIRSTNAME, " ", h.USR_LASTNAME)','to_user_name'=>'concat(i.USR_FIRSTNAME, " ", i.USR_LASTNAME)','letter_tag','organization_name'=>'m.name','iln'=>'if(auto_letter.letter_type="import",internal_letter_number,"")','ild'=>'if(auto_letter.letter_type="import",n2_date(internal_letter_date),"")','status']);
		$xcrud->label('letter_type_name','نوع نامه');
		$xcrud->label('dabirkhaneh_name','دبیرخانه');
		$xcrud->label('j_letter_date','تاریخ نامه');
		$xcrud->label('number','شماره نامه');
		$xcrud->label('subject','موضوع نامه');
		$xcrud->label('from_user_name','از');
		$xcrud->label('to_user_name','به');
		$xcrud->label('letter_tag','برچسب');
		$xcrud->label('organization_name','سازمان');
		$xcrud->label('iln','شماره نامه ورودی');
		$xcrud->label('ild','تاریخ نامه ورودی');
		$xcrud->label('status','وضعیت');
		$xcrud->change_type('status','select','',array('values'=>array('0'=>'جاری','1'=>'اتمام')));
		$xcrud->where('c.from_user = "'.$_SESSION['USER_LOGGED'].'" OR c.to_user = "'.$_SESSION['USER_LOGGED'].'" OR l.supervisor = 1');
		$xcrud->where('l.USR_UID = "'.$_SESSION['USER_LOGGED'].'" OR ISNULL(l.USR_UID)');

		if(isset($inputData['letter_title']) && !empty($inputData['letter_title']))
			$xcrud->where('letter_title like "%'.$inputData['letter_title'].'%"');
		if(isset($inputData['letter_type']) && !empty($inputData['letter_type']))
			$xcrud->where('letter_type =', $inputData['letter_type']);
		if(isset($inputData['dabirkhaneh']) && !empty($inputData['dabirkhaneh']))
			$xcrud->where('dabirkhaneh_id =', $inputData['dabirkhaneh']);
		if(isset($inputData['subject']) && !empty($inputData['subject']))
			$xcrud->where('subject like "%'.$inputData['subject'].'%"');
		if(isset($inputData['letter_date']) && !empty($inputData['letter_date']))
			$xcrud->where('letter_date =', $inputData['letter_date']);
		if(isset($inputData['letter_number']) && !empty($inputData['letter_number']))
			$xcrud->where('number like "%'.$inputData['letter_number'].'%"');
		if(isset($inputData['priority']) && !empty($inputData['priority']))
			$xcrud->where('priority =', $inputData['priority']);
		if(isset($inputData['security']) && !empty($inputData['security']))
			$xcrud->where('security =', $inputData['security']);
		if(isset($inputData['export_organization']) && !empty($inputData['export_organization']))
			$xcrud->where('organization_id =', $inputData['export_organization']);
		if(isset($inputData['import_organization']) && !empty($inputData['import_organization']))
			$xcrud->where('organization_id =', $inputData['import_organization']);
		if(isset($inputData['organization_person']) && !empty($inputData['organization_person']))
			$xcrud->where('organization_persons_id =', $inputData['organization_person']);
		if(isset($inputData['internal_letter_date']) && !empty($inputData['internal_letter_date']))
			$xcrud->where('internal_letter_date =', $inputData['internal_letter_date']);
		if(isset($inputData['internal_letter_number']) && !empty($inputData['internal_letter_number']))
			$xcrud->where('internal_letter_number like "%'.$inputData['internal_letter_number'].'%"');
		if(isset($inputData['sender']) && !empty($inputData['sender']))
			$xcrud->where('c.from_user =', $inputData['sender']);
		if(isset($inputData['receiver']) && !empty($inputData['receiver']))
			$xcrud->where('c.to_user =', $inputData['receiver']);
		if(isset($inputData['type_erja']) && !empty($inputData['type_erja']))
			$xcrud->where('c.receive_type in('.implode(',',$inputData['type_erja']).')');
		if(isset($inputData['vaziat']) && is_numeric($inputData['vaziat']))
			$xcrud->where('status =',$inputData['vaziat']);

		$xcrud->group_by('id');
		$xcrud->order_by('insert_date','desc');

		$xcrud->button('javascript:show_print_archive(\'{id}\', \'{letter_type}\', \'{b.print_type}\', \'{b.print_file_name}\');', 'چاپ', 'glyphicon glyphicon-print');
		$xcrud->button('javascript:show_receivers_archive(\'{id}\', \'{c.from_user}\', \'{c.from_APP_UID}\', \'{number}\');', 'ارجاعات', 'glyphicon glyphicon-user');
		$xcrud->button('javascript:show_attachment(\'{id}\', \'{number}\');', 'پیوست ها', 'glyphicon glyphicon-download-alt');
		$xcrud->button('javascript:show_actions(\'{id}\', \'{number}\');', 'اقدامات', 'glyphicon glyphicon-flash');
		$xcrud->button('javascript:show_print_word_archive(\'{id}\', \'{letter_type}\', \'{b.print_type}\', \'{b.print_file_name}\');', 'ورد', 'glyphicon glyphicon-list-alt');
		$xcrud->button('javascript:send_letter(\'{id}\', \'{number}\');', 'ارجاع', 'glyphicon glyphicon-share-alt');

		$xcrud->search_columns('letter_type_name,dabirkhaneh_name,number,subject,from_user_name,to_user_name,letter_tag,organization_name,iln');
		$xcrud->unset_excel(false);

		return $xcrud->render();
	}

	public function auto_search_letter($inputData = [])
	{
		$output = [];
		
		$where = [];
		array_push($where, '1');
		
		if(isset($inputData['letter_title']) && !empty($inputData['letter_title']))
			array_push($where, 'a.letter_title like "%'.$inputData['letter_title'].'%"');
		if(isset($inputData['letter_type']) && !empty($inputData['letter_type']))
			array_push($where, 'a.letter_type = "'.$inputData['letter_type'].'"');
		if(isset($inputData['dabirkhaneh']) && !empty($inputData['dabirkhaneh']))
			array_push($where, 'a.dabirkhaneh_id = "'.$inputData['dabirkhaneh'].'"');
		if(isset($inputData['subject']) && !empty($inputData['subject']))
			array_push($where, 'a.subject like "%'.$inputData['subject'].'%"');
		if(isset($inputData['letter_date']) && !empty($inputData['letter_date']))
			array_push($where, 'a.letter_date = "'.$inputData['letter_date'].'"');
		if(isset($inputData['letter_number']) && !empty($inputData['letter_number']))
			array_push($where, 'a.number = "'.$inputData['letter_number'].'"');
		if(isset($inputData['priority']) && !empty($inputData['priority']))
			array_push($where, 'a.priority = "'.$inputData['priority'].'"');
		if(isset($inputData['security']) && !empty($inputData['security']))
			array_push($where, 'a.security = "'.$inputData['security'].'"');
		if(isset($inputData['export_organization']) && !empty($inputData['export_organization']))
			array_push($where, 'a.organization_id = "'.$inputData['export_organization'].'"');
		if(isset($inputData['import_organization']) && !empty($inputData['import_organization']))
			array_push($where, 'a.organization_id = "'.$inputData['import_organization'].'"');
		if(isset($inputData['internal_letter_date']) && !empty($inputData['internal_letter_date']))
			array_push($where, 'a.internal_letter_date = "'.$inputData['internal_letter_date'].'"');
		if(isset($inputData['internal_letter_number']) && !empty($inputData['internal_letter_number']))
			array_push($where, 'a.internal_letter_number = "'.$inputData['internal_letter_number'].'"');
		
		if(isset($inputData['sender']) && !empty($inputData['sender']))
			array_push($where, 'c.from_user = "'.$inputData['sender'].'"');
		if(isset($inputData['receiver']) && !empty($inputData['receiver']))
			array_push($where, 'c.to_user = "'.$inputData['receiver'].'"');
		
		if(isset($inputData['search_all']) && !empty($inputData['search_all'])){
			array_push($where, '(a.letter_title like "%'.$inputData['search_all'].'%" OR a.subject like "%'.$inputData['search_all'].'%" OR
								 a.number like "%'.$inputData['search_all'].'%" OR a.internal_letter_number like "%'.$inputData['search_all'].'%" OR
								 m.`name` like "%'.$inputData['search_all'].'%" OR n.`name` like "%'.$inputData['search_all'].'%" OR
								 n.`post` like "%'.$inputData['search_all'].'%" OR a.letter_tag like "%'.$inputData['search_all'].'%")');
		}
		
		$result = [];
		if(count($where) > 0){
			array_push($where, '(c.from_user = "'.$_SESSION['USER_LOGGED'].'" OR c.to_user = "'.$_SESSION['USER_LOGGED'].'" OR l.supervisor = 1)');
			
			$order = 'order by ';
			if(isset($inputData['orderColumn']) && !empty($inputData['orderColumn'])){
				if($inputData['orderColumn'] == 'from_user_name')
					$order .= 'concat(h.USR_FIRSTNAME, " ", h.USR_LASTNAME) ';
				else if($inputData['orderColumn'] == 'to_user_name')
					$order .= 'concat(i.USR_FIRSTNAME, " ", i.USR_LASTNAME) ';
				else
					$order .= 'a.`'.$inputData['orderColumn'].'` ';
				if(isset($inputData['orderType']) && !empty($inputData['orderType']))
					$order .= $inputData['orderType'];
			}
			else
				$order .= 'a.`insert_date` desc';
			
			$where = implode(' and ', $where);
			$query = '
				select a.*, b.`print_type` as print_type, b.`print_file_name` as print_file_name, c.from_APP_UID, c.from_user, c.to_user,
					   d.name as letter_type_name, e.name as dabirkhaneh_name, f.name as priority_name, g.name as security_name,
					   concat(h.USR_FIRSTNAME, " ", h.USR_LASTNAME) as from_user_name, concat(i.USR_FIRSTNAME, " ", i.USR_LASTNAME) as to_user_name,
					   if(isnull(j.id),0,1) as have_download, if(isnull(k.id),0,1) as have_actions, l.supervisor,
					   m.`name` as organization_name, m.`phone` as organization_phone, n.`name` as organization_person, n.`post` as organization_post
				from auto_letter as a left join `auto_template` as b on(a.template_id = b.`id`)
					 join `auto_letter_receiver` as c on(a.id = c.letter_id)
					 join `auto_based_info` as d on(a.letter_type = d.value and d.deleted = 0)
					 join `auto_dabirkhaneh` as e on(a.dabirkhaneh_id = e.id)
					 join `auto_based_info` as f on(a.priority = f.value and f.deleted = 0)
					 join `auto_based_info` as g on(a.security = g.value and g.deleted = 0)
					 join `USERS` as h on(c.from_user = h.USR_UID)
					 join `USERS` as i on(c.to_user = i.USR_UID)
					 left join `auto_letter_attachment` as j on(a.id = j.letter_id)
					 left join `auto_letter_actions` as k on(c.id = k.letter_receiver_id and k.deleted = 0)
					 left join `auto_dabirkhaneh_user` as l on(l.dabirkhaneh_id = a.dabirkhaneh_id and l.USR_UID = "'.$_SESSION['USER_LOGGED'].'" and l.deleted = 0)
					 left join `auto_organization` as m on(a.organization_id = m.`id`)
					 left join `auto_organization_persons` as n on(a.organization_persons_id = n.`id`)
				where '.$where.'
				group by a.`id`
				'.$order.'
				limit 0, 100;
			';
			$result = executeQuery($query);
		}
		
		$output['data'] = $result;
		return $output;
	}

	public function auto_show_letter($letter_id)
	{
		$query = '
			select a.*, b.`name` as priority_name, c.`name` as security_name, d.`name` as dabirkhaneh_name, e.`print_type` as print_type,
				   e.`print_file_name` as print_file_name, f.`name` as organization_name,
				   i.`name` as organization_person, i.`post` as organization_post, g.`name` as letter_title_name, h.`name` as letter_unit_name
			from `auto_letter` as a join `auto_based_info` as b on(a.priority = b.`value` and b.`type` = "priority_type")
				 join `auto_based_info` as c on(a.security = c.`value` and c.`type` = "security_type")
				 join `auto_dabirkhaneh` as d on(a.dabirkhaneh_id = d.`id`)
				 left join `auto_template` as e on(a.template_id = e.`id`)
				 left join `auto_organization` as f on(a.organization_id = f.`id`)
				 left join `auto_organization_persons` as i on(a.organization_persons_id = i.`id`)
				 left join `auto_letter_title` as g on(a.letter_title = g.id and g.`type` = "title" and a.letter_type = "internal")
				 left join `auto_letter_title` as h on(a.letter_unit = h.id and h.`type` = "unit" and  a.letter_type = "internal")
			where a.id = "'. $letter_id .'";
		';
		return executeQuery($query);
	}

	public function auto_show_sender($letter_id)
	{
		$jFunc = ($this->auto_get_config('date_type') == 'm'?'':'n2_date');
		$query = '
			select a.*, d.`name` as receiver_type_name, CONCAT(b.`USR_FIRSTNAME`," ",b.USR_LASTNAME) as from_user_name,
				   CONCAT(c.`USR_FIRSTNAME`," ",c.USR_LASTNAME) as to_user_name, '.$jFunc.'(a.insert_date) as insert_date_j, e.dabirkhaneh_id,
				   '.$jFunc.'(a.due_date) as due_date_j, g.`name` as receive_type_name,
				   f.APP_STATUS, '.$jFunc.'(f.APP_UPDATE_DATE) as APP_UPDATE_DATE_J, '.$jFunc.'(f.APP_FINISH_DATE) as APP_FINISH_DATE_J, '.$jFunc.'(f.DEL_INIT_DATE) as DEL_INIT_DATE_J,
				   '.$jFunc.'(f.DEL_DELEGATE_DATE) as DEL_DELEGATE_DATE_J, '.$jFunc.'(f.DEL_TASK_DUE_DATE) as DEL_TASK_DUE_DATE_J
			from `auto_letter_receiver` as a join `USERS` as b on(a.from_user = b.USR_UID)
				 join `USERS` as c on(a.to_user = c.USR_UID)
				 join `auto_based_info` as d on(a.receiver_type = d.`value` and d.`type` = "receiver_type")
				 join `auto_letter` as e on(a.`letter_id` = e.`id`)
				 join `APP_CACHE_VIEW` as f on(a.`to_APP_UID` = f.`APP_UID`)
				 join `auto_based_info` as g on(a.receive_type = g.`value` and g.`type` = "receive_type")
			where a.letter_id = "'. $letter_id .'" and a.to_user = "'. $_SESSION['USER_LOGGED'] .'" and a.to_APP_UID = "'. $_SESSION['APPLICATION'] .'";
		';
		return executeQuery($query);
	}

	public function auto_update_app_cache_view($letter_author_id, $letter_author)
	{
		$con = Propel::getConnection('workflow');
		$stmt = $con->createStatement();
		$query = "
			UPDATE `APP_CACHE_VIEW`
			SET `PREVIOUS_USR_UID` = '". $letter_author_id ."',
				`APP_DEL_PREVIOUS_USER` = '". $letter_author ."'
			WHERE `APP_UID` = '". $_SESSION['APPLICATION'] ."';
		";
		$stmt->executeQuery($query);
	}

	public function auto_show_receivers($inputData,$type = '')
	{
		$output = [];
		$from_APP_UID = [];

		if(!isset($inputData['from_user']) && !isset($inputData['from_APP_UID'])){
			$query = '
				select DISTINCT(from_APP_UID) as from_APP_UID
				from auto_letter_receiver
				where `letter_id` = "'.$inputData['letter_id'].'" and
					  from_APP_UID not in (select to_APP_UID from auto_letter_receiver where letter_id = "'.$inputData['letter_id'].'")
				order by id
			';
			$result = executeQuery($query);
			if(count($result)){
				foreach($result as $row)
					$from_APP_UID[] = $row['from_APP_UID'];
			}
			else{
				$output['error'] = 'در فراخوانی ارجاعات خطایی رخ داده است، لطفا مجدد سعی نمایید!';
				return $output;
			}
		}

		$jFunc = ($this->auto_get_config('date_type') == 'm'?'':'n2_date');

		$query = '
			select a.*, d.`name` as receiver_type_name, CONCAT(b.`USR_FIRSTNAME`," ",b.USR_LASTNAME) as from_user_name,
				   CONCAT(c.`USR_FIRSTNAME`," ",c.USR_LASTNAME) as to_user_name, '.$jFunc.'(a.insert_date) as insert_date_j, e.dabirkhaneh_id,
				   '.$jFunc.'(a.due_date) as due_date_j, g.`name` as receive_type_name,
				   f.APP_STATUS, '.$jFunc.'(f.APP_UPDATE_DATE) as APP_UPDATE_DATE_J, '.$jFunc.'(f.DEL_FINISH_DATE) as APP_FINISH_DATE_J, '.$jFunc.'(f.DEL_INIT_DATE) as DEL_INIT_DATE_J,
				   '.$jFunc.'(f.DEL_DELEGATE_DATE) as DEL_DELEGATE_DATE_J, '.$jFunc.'(f.DEL_TASK_DUE_DATE) as DEL_TASK_DUE_DATE_J,
				   CONCAT(c.`USR_FIRSTNAME`," ",c.USR_LASTNAME) as `title`
			from `auto_letter_receiver` as a join `USERS` as b on(a.from_user = b.USR_UID)
				 join `USERS` as c on(a.to_user = c.USR_UID)
				 join `auto_based_info` as d on(a.receiver_type = d.`value` and d.`type` = "receiver_type")
				 join `auto_letter` as e on(a.`letter_id` = e.`id`)
				 join `APP_CACHE_VIEW` as f on(a.`to_APP_UID` = f.`APP_UID`)
				 join `auto_based_info` as g on(a.receive_type = g.`value` and g.`type` = "receive_type")
			where a.`letter_id` = "'.$inputData['letter_id'].'" and f.TAS_UID = "'.$this->task_view.'"
		';
		if(!isset($inputData['from_user']) && !isset($inputData['from_APP_UID']))
			$query .= ' and a.from_APP_UID in ("'.implode('","', $from_APP_UID).'")';
		else{
			$query .= ' and a.from_user = "'.$inputData['from_user'].'"';
			$query .= ' and a.from_APP_UID = "'.$inputData['from_APP_UID'].'"';
		}
		if($type != '')
			$query .= 'order by a.insert_date ASC';
		$result = executeQuery($query);

		foreach($result as $row){
			if($row['receiver_type'] == 'bcc' && !($row['to_user'] == $_SESSION['USER_LOGGED'] || $row['from_user'] == $_SESSION['USER_LOGGED'])){
				$query = '
					select *
					from auto_dabirkhaneh_user
					where deleted = 0 and dabirkhaneh_id = "'.$row['dabirkhaneh_id'].'" and USR_UID = "'.$_SESSION['USER_LOGGED'].'" and supervisor = 1
				';
				$result1 = executeQuery($query);
				if(count($result1) > 0){
					if(isset($inputData['fancytree']) && $inputData['fancytree'] == 1){
						$inputData['from_user'] = $row['to_user'];
						$inputData['from_APP_UID'] = $row['to_APP_UID'];
						$temp = $this->auto_show_receivers($inputData,'1');
						if(count($temp)){
							$row['expanded'] = true;
							$row['folder'] = true;
							$row['children'] = $temp;
						}
					}
					$output[] = $row;
				}
				else
					continue;
			}
			else{
				if(isset($inputData['fancytree']) && $inputData['fancytree'] == 1){
					$inputData['from_user'] = $row['to_user'];
					$inputData['from_APP_UID'] = $row['to_APP_UID'];
					$temp = $this->auto_show_receivers($inputData,'1');
					if(count($temp)){
						$row['expanded'] = true;
						$row['folder'] = true;
						$row['children'] = $temp;
					}
				}
				$output[] = $row;
			}
		}

		if(!isset($inputData['fancytree']) || $inputData['fancytree'] == 0){
			$result = [];
			$i = 1;
			foreach($output as $row){
				$result[$i] = $row;
				$i++;
			}
			$output['data'] = $row;
		}

		return $output;
	}

	public function auto_show_actions($letter_id)
	{
		$output = [];
		$jFunc = ($this->auto_get_config('date_type') == 'm'?'':'n2_date');

		$query = '
			select a.*, CONCAT(c.`USR_FIRSTNAME`," ",c.USR_LASTNAME) as user_name, '.$jFunc.'(a.insert_date) as insert_date_j, b.to_APP_UID, if(a.insert_user="'.$_SESSION['USER_LOGGED'].'",1,0) as deleteAllow
			from `auto_letter_actions` as a join `auto_letter_receiver` as b on(a.letter_receiver_id = b.id)
				 join `USERS` as c on(a.insert_user = c.USR_UID)
			where a.deleted = 0 and b.`letter_id` = "'.$letter_id.'";
		';
		$result = executeQuery($query);

		$output['data'] = $result;
		return $output;
	}

	private function check_file($file)
	{
        if (!empty($file)){
            if ($file["error"] > 0) {
                $output['error'] = $file["error"];
                return $output;
            }
            else {
                $fileName = $file["name"];
                $fileTmpName = $file["tmp_name"];
                $fileSize = $file["size"];
                $fileType = pathinfo($fileName)["extension"];
                $base_config = parse_ini_file(PATH_CONFIG . 'env.ini');
                $valid_file_size = 5;
                if(isset($this->config["file_types"]) && !empty($this->config["file_types"])){
                    $valid_file_types = explode(',',$this->config["file_types"]);
                }
                elseif(isset($base_config["files_white_list"]) && !empty($base_config["files_white_list"])){
                    $valid_file_types = explode(',',$base_config["files_white_list"]);
                }
                else{
                    $valid_file_types = ["jpg","jpeg","png","pdf","docx","doc","xlsx","xls","zip","rar"];
                }
                if(!in_array($fileType,$valid_file_types)){
                    $output['error'] = "فرمت فایل باید یکی از مقادیر '".implode(',',$valid_file_types)."' باشد";
                    return $output;
                }
                if(round($fileSize/(1024*1024),2)>$valid_file_size){
                    $output['error'] = "حجم فایل باید کمتر از ".$valid_file_size." باشد";
                    return $output;
                }
            }
        }
    }

	public function auto_download_file($inputData)
	{
        $filename = '';
		$id = $inputData['id'];
        $type = $inputData['type'];
        if(!isset($id)){
            header('Location: /errors/error404.php');
        }
        $query = '
				select *
				from auto_letter_actions
				where deleted = 0 and id = "'.$id.'" 
		';
		$result = executeQuery($query);
		if(count($result))
			$data = $result[1];
		else{
			header('Location: /errors/error404.php');
		}
		$filename = $data["file_name"];
        if(!empty($filename))
        {
            $fileType =pathinfo($filename)["extension"];
            $file = str_replace('\\', '/', PATH_DATA).'pishrobpms/automation/'.$type.'-files/' . $this->workspace.$id .'.'. $fileType;
            ob_clean();
            G::streamFile($file, true, $filename);
        }
        else{
            header('Location: /errors/error404.php');
        }
        die();
    }

	public function auto_add_action($inputData,$file)
	{
		$output = [];
		$output = $this->check_file($file);
		if (isset($output['error'])) {
            return $output;
        }
		$inputData['action_file_name'] = '';
		if (!empty($file)){
			$fileName = $file["name"];
			$fileTmpName = $file["tmp_name"];
			$fileType = pathinfo($fileName)["extension"];
			$inputData['action_file_name'] = $fileName;
		}
		if(!empty($inputData['letter_id']) && !empty($inputData['new_action'])){
			$query = '
				select id
				from `auto_letter_receiver`
				where letter_id = "'.$inputData['letter_id'].'"
			';
			if($this->task_view == $_SESSION['TASK'])
				$query .= ' and to_user = "'.$_SESSION['USER_LOGGED'].'" and to_APP_UID = "'.$_SESSION['APPLICATION'].'"';
			else
				$query .= ' and (to_user = "'.$_SESSION['USER_LOGGED'].'" or from_user = "'.$_SESSION['USER_LOGGED'].'")';
			$query .= ' order by id desc';
			$result = executeQuery($query);

			if(is_array($result) && count($result) > 0){
				$query = '
					INSERT INTO `auto_letter_actions`(
						`letter_receiver_id`, `action`, `file_name`, `insert_user`, `insert_date`
					)
					VALUES (
						"'.$result[1]['id'].'", "'.$inputData['new_action'].'", "'.$inputData['action_file_name'].'", "'.$_SESSION['USER_LOGGED'].'", NOW()
					);
				';
				executeQuery($query);

				$query = '
					select LAST_INSERT_ID() as last_insert_id;
				';
				$result = executeQuery($query);
				$action_id = $result[1]['last_insert_id'];
				if (!empty($file)){
					$permission = 0777;
					$path = PATH_DATA . 'pishrobpms';
					if(!is_dir($path))
						mkdir($path, $permission);

					$path = $path . PATH_SEP . 'automation';
					if(!is_dir($path))
						mkdir($path, $permission);

					$path = $path . PATH_SEP . 'action-files';
					if(!is_dir($path))
						mkdir($path, $permission);

					move_uploaded_file($fileTmpName, $path . PATH_SEP . $this->workspace.$action_id.'.'.$fileType);
				}

				$query = '
					select `number`, subject, CONCAT(USR_FIRSTNAME, " ", USR_LASTNAME) as fullname, c.`name` as letter_type_name
					from auto_letter as a join USERS as b on(a.insert_user = b.USR_UID)
						 join auto_based_info as c on(a.letter_type = c.`value` and c.deleted = 0)
					where a.id = "'.$inputData['letter_id'].'"
				';
				$result1 = executeQuery($query);
				if(is_array($result1) && count($result1) > 0){
					$row = $result1[1];
					$processData = [
						'new_app' => $_SESSION['APPLICATION'],
						'letter_type' => $row['letter_type_name'],
						'subject' => $row['subject'],
						'letter_number' => $row['number'],
						'letter_author' => $row['fullname'],
						'action_comment' => $inputData['action_comment']
					];

					$query = '
						select *
						from auto_letter_receiver
						where letter_id = "'.$inputData['letter_id'].'"
					';
					$result = executeQuery($query);
					$temp = [];
					$temp[] = $_SESSION['USER_LOGGED'];
					foreach($result as $row){
						if(!in_array($row['from_user'], $temp)){
							$temp[] = $row['from_user'];
							$this->auto_insert_notification($row['from_user'], $processData, 'action');
						}
						if(!in_array($row['to_user'], $temp)){
							$temp[] = $row['to_user'];
							$this->auto_insert_notification($row['to_user'], $processData, 'action');
						}
					}
				}

				$output['data']['message'] = 'اقدام با موفقیت ثبت شد';
				return $output;
			}
		}

		$output['error'] = 'در ثبت اقدام خطایی رخ داده است، مجدد سعی نمایید.';
		return $output;
	}

	public function auto_delete_action($inputData = [])
	{
		$output = [];

		if(empty($inputData['id'])){
			$output['error'] = 'شناسه حذف به درستی مشخص نشده است';
			return $output;
		}

		// or b.`to_APP_UID` = "'.$_SESSION['APPLICATION'].'"
		$query = '
			select a.id
			from `auto_letter_actions` as a join `auto_letter_receiver` as b on(a.letter_receiver_id = b.id)
			where a.deleted = 0 and a.`id` = "'.$inputData['id'].'" and (a.`insert_user` = "'.$_SESSION['USER_LOGGED'].'");
		';
		$result = executeQuery($query);
		if(!count($result)){
			$output['error'] = 'اجازه حذف این اقدام را ندارید!';
			return $output;
		}

		$query = '
			UPDATE `auto_letter_actions`
			SET `deleted` = 1,
				`update_user` = "'.$_SESSION['USER_LOGGED'].'",
				`update_date` = NOW()
			WHERE id = "'.$inputData['id'].'";
		';
		executeQuery($query);

		$output['success'] = 'حذف با موفقیت انجام شد';
		return $output;
	}

	public function auto_show_user_from_group($inputData = [])
	{
		$output = [];
		
		global $RedisObj;
		$memKey = 'autoShowUserFromGroup'.$_SESSION['USER_LOGGED'].$inputData['letter_type'].$inputData['custom_group_id'];
		if (!autoCheckRedis() || ($result = ($RedisObj->get($memKey)?json_decode($RedisObj->get($memKey), true):false)) === false) {
			$query = '
				select b.USR_UID as user_id, CONCAT(USR_FIRSTNAME, " ", USR_LASTNAME) as `name`, dabirkhaneh_id
				from `auto_permission` as a join `USERS` as b 
					 on(a.from_user = "'.$_SESSION['USER_LOGGED'].'" and b.USR_UID = a.to_user and a.letter_type = "'.$inputData['letter_type'].'")
					 join `auto_custom_group` as c on(c.USR_UID = "'.$_SESSION['USER_LOGGED'].'" and c.id = "'.$inputData['custom_group_id'].'")
					 join `auto_custom_group_user` as d on(d.USR_UID = b.USR_UID and d.custom_group_id = c.id)
					 join `auto_dabirkhaneh_user` as e on(e.USR_UID = b.USR_UID and e.deleted = 0)
					 join `auto_dabirkhaneh` as f on(e.dabirkhaneh_id = f.id and f.deleted = 0 and f.letter_type like "%'.$inputData['letter_type'].'%")
				where USR_STATUS = "ACTIVE" and USR_DUE_DATE > NOW()
				group by b.USR_UID
				order by `name`;
			';
			$result = executeQuery($query);
			
			if(autoCheckRedis()){
				$RedisObj->set($memKey, json_encode($result));
				$RedisObj->expire($memKey, 8*60*60);
			}
		}

		$output['data'] = $result;
		return $output;
	}

	public function auto_show_attachment($letter_id)
	{
		$output = [];
		
		$view_secret_file = 0;
		$query = '
			select *
			from auto_letter_receiver as a join auto_dabirkhaneh_user as b on(a.to_user = b.USR_UID OR a.from_user = b.USR_UID)
			where b.deleted = 0 and b.view_secret_file = 1 and b.USR_UID = "'.$_SESSION['USER_LOGGED'].'" and a.letter_id = "'.$letter_id.'";
		';
		$result = executeQuery($query);
		if(is_array($result) && count($result) > 0)
			$view_secret_file = 1;
		
		$jFunc = ($this->auto_get_config('date_type') == 'm'?'':'n2_date');
		$query = '
			select a.*, CONCAT(b.`USR_FIRSTNAME`," ",b.USR_LASTNAME) as from_user_name, '.$jFunc.'(left(a.insert_date, 10)) as insert_date_j, c.APP_DOC_FILENAME
			from `auto_letter_attachment` as a join `USERS` as b on(a.from_user = b.USR_UID)
				 join `APP_DOCUMENT` as c on(a.attach = c.APP_DOC_UID)
			where a.`letter_id` = "'.$letter_id.'";
		';
		$result = executeQuery($query);
		
		if(!$view_secret_file){
			$i = 1;
			$temp = [];
			foreach($result as $row){
				if($row['secret'] != 1){
					$temp[$i] = $row;
					$i++;
				}
			}
			$result = $temp;
		}
		
		$output['data'] = $result;
		return $output;
	}

	public function auto_show_content($letter_id)
	{
		$query = '
			select a.*, c.`name` as organization_name
			from `auto_letter` as a left join `auto_organization` as c on(a.organization_id = c.id)
			where a.`id` = "'.$letter_id.'";
		';
		$result = executeQuery($query);
		
		$data = [];
		$data['letter_type'] = $result[1]['letter_type'];
		$data['subject'] = $result[1]['subject'];
		$content = htmlspecialchars_decode($result[1]['content']);
		
		if($result[1]['letter_type'] == 'internal'){
			$query = '
				select a.*, CONCAT(b.`USR_FIRSTNAME`," ",b.USR_LASTNAME) as to_user_name, b.USR_POSITION as `to_user_position`
				from `auto_letter_receiver` as a join `USERS` as b on(a.to_user = b.USR_UID)
				where a.`letter_id` = "'.$letter_id.'" and a.`receiver_type` = "main";
			';
			$result = executeQuery($query);
			$data['to_user_name'] = $result[1]['to_user_name'];
			$data['to_user_position'] = $result[1]['to_user_position'];
		}
		else if($result[1]['letter_type'] == 'export'){
			$data['to_user_name'] = $result[1]['organization_name'];
			$data['to_user_position'] = '';
		}
		
		$textBody = '
			<table class="table table-responsive" style="background-color: #fff;">
				<tbody>
					<tr>
						<td><p dir="rtl" align="right"><b>'.$data['to_user_name'].' '.$data['to_user_position'].'<br/>'
							.$data['subject'].'</b></p>'
							.$content.
						'</td>
					</tr>
				</tbody>
			</table>
		';
		
		return $textBody;
	}

	private function auto_get_girandeh($row, $girandeh_structure, $pre = '')
	{
		$row['from_position'] = $this->auto_get_semat($row['from_semat'], $row[$pre.'position']);
		$data = [];
		for($i=0;$i<count($girandeh_structure);$i++)
			$data[] = $row[$pre.$girandeh_structure[$i]];
		return implode(' ', $data);
	}

    private function auto_get_semat($semat_id,$position)
	{
        if(is_null($semat_id) || $semat_id == '')
            return $position;
        $query = 'select semat from auto_semat where id = "'.$semat_id.'"';
        $result = executeQuery($query);
        if(count($result))
            return $result[1]['semat'];
        return $position;
    }

	private function mb_str_split($string, $split_length = 1, $encoding = 'UTF-8')
	{
		if ($split_length < 1) {
			throw new InvalidArgumentException('Split length must be greater than 0.');
		}

		$length = mb_strlen($string, $encoding);
		$result = [];

		for ($i = 0; $i < $length; $i += $split_length) {
			$result[] = mb_substr($string, $i, $split_length, $encoding);
		}

		return $result;
	}

	public function auto_show_print($inputData = [])
	{
		if(isset($inputData['from']) && $inputData['from'] == 'direct'){
			$config = parse_ini_file(PATH_CONFIG . 'env.ini');
			if(isset($config["disable_task_manager_routing_async"]) && $config["disable_task_manager_routing_async"] == "1"){
				return;
			}
		}

		$type = 'pdf';
		if(isset($inputData['type']) && $inputData['type'] == 'word')
			$type = 'word';

		$where = '1!=1';
		if(isset($inputData['letter_id']) && !empty($inputData['letter_id']))
			$where = 'a.id = "'.$inputData['letter_id'].'"';

		$query = '
			select a.*, b.`name` as priority_name, c.`name` as security_name, d.`name` as dabirkhaneh_name, e.`print_type` as print_type,
				   e.`print_file_name` as print_file_name, f.`name` as organization_name, f.`phone` as organization_phone,
				   i.`name` as organization_person, i.`post` as organization_post,
				   g.`name` as letter_title_name, h.`name` as letter_unit_name
			from `auto_letter` as a join `auto_based_info` as b on(a.priority = b.`value` and b.`type` = "priority_type")
				 join `auto_based_info` as c on(a.security = c.`value` and c.`type` = "security_type")
				 join `auto_dabirkhaneh` as d on(a.dabirkhaneh_id = d.`id`)
				 left join `auto_template` as e on(a.template_id = e.`id`)
				 left join `auto_organization` as f on(a.organization_id = f.`id`)
				 left join `auto_organization_persons` as i on(a.organization_persons_id = i.`id`)
				 left join `auto_letter_title` as g on(a.letter_title = g.`id` and g.`type` = "title")
				 left join `auto_letter_title` as h on(a.letter_title = h.`id` and h.`type` = "unit")
			where '.$where.';
		';
		$result = executeQuery($query);
		$data = [
			'pdf' => ''
		];
		if(is_array($result) && count($result) > 0){
			if(function_exists('loadTemplateMaker')){
				$row = $result[1];
				$letter_id = $row['id'];
				$tm = loadTemplateMaker();
				$pdf = $tm->tm_auto_get_print($row['word_name']);
				if($pdf && $type == 'pdf')
					$data['pdf'] = $pdf;
				else{
					$letter_date = explode(' ', $row['insert_date']);
					$letter_time = $letter_date[1];
					$letter_date = $row['letter_date'];
					$internal_letter_date = $row['internal_letter_date'];

                    $letter_date_g = $letter_date;
					$date_format = $this->auto_get_config('date_format');
					$date = new DateTime($letter_date_g);
					$letter_date_g = $date->format($date_format);

					if($this->auto_get_config('date_type') == 'j'){
                        $letter_date = $this->persian_date_e2p_na($letter_date);
						$internal_letter_date = $this->persian_date_e2p_na($internal_letter_date);
					}
                    else{
                        $letter_date = $letter_date_g;
                    }

					$peyvast = 'ندارد';
					$query = '
						select * from auto_letter_attachment
						where letter_id = "'.$letter_id.'";
					';
					$result1 = executeQuery($query);
					if(is_array($result1) && count($result1) > 0)
						$peyvast = 'دارد';

					$sender = '';
					$girandegan = [];
					$roneveshts = [];
					$query = '
						select a.from_semat, a.to_semat, a.comment, a.receiver_type, b.`USR_FIRSTNAME` as firstname, b.USR_LASTNAME as lastname, b.USR_POSITION as position, b.USR_ZIP_CODE as gender,
							   a.from_APP_UID, c.`USR_FIRSTNAME` as from_firstname, c.USR_LASTNAME as from_lastname, c.USR_POSITION as from_position, c.USR_ZIP_CODE as from_gender
						from `auto_letter_receiver` as a join `USERS` as b on(a.to_user = b.USR_UID)
							 join `USERS` as c on(a.from_user = c.USR_UID)
						where a.`letter_id` = "'.$letter_id.'" and a.`receiver_type` in ("main", "bc");
					';
					$result1 = executeQuery($query);
					if(is_array($result1) && count($result1) > 0){
						$girandeh_structure = explode(',', $this->auto_get_config('girandeh_structure'));
						$sender_structure = explode(',', $this->auto_get_config('sender_structure'));

                        $sender = $this->auto_get_girandeh($result1[1], $sender_structure, 'from_');
						$from_APP_UID = $result1[1]['from_APP_UID'];
						foreach($result1 as $row1){
							if($from_APP_UID != $row1['from_APP_UID'])
								break;
							if($row1['receiver_type'] == 'main'){
                                $girandegan[] = [
									'girandeh' => $this->auto_get_girandeh($row1, $girandeh_structure)
								];
							}
							else{
								$roneveshts[] = [
									'ronevesht' => $this->auto_get_girandeh($row1, $girandeh_structure),
									'comment' => $row1['comment']
								];
							}
						}
					}

					$signatures = [];
					$query = '
						select a.*, b.sign_file, c.APP_DOC_FILENAME
						from `auto_letter_sign` as a join `auto_signer` as b on(a.USR_UID = b.USR_UID)
							 join `APP_DOCUMENT` as c on(b.sign_file = c.APP_DOC_UID)
						where a.`letter_id` = "'.$letter_id.'" and b.`'.$row['letter_type'].'` = 1 and b.deleted = 0
						order by id desc;
					';
					$result1 = executeQuery($query);
					if(is_array($result1) && count($result1) > 0){
						foreach($result1 as $row1){
							$path = $this->auto_get_inputDocPath().$row1['sign_file'];
							$file_type = pathinfo($row1['APP_DOC_FILENAME'], PATHINFO_EXTENSION);
							$file_type = strtolower($file_type);
							if(G::is_https()){
								$arrContextOptions = [
									"ssl" => [
										"verify_peer" => false,
										"verify_peer_name" => false
									]
								];
								$image_data = file_get_contents($path, false, stream_context_create($arrContextOptions));
							}
							else
								$image_data = file_get_contents($path);
							if($image_data){
								$signature = 'data:image/' . $file_type . ';base64,' . base64_encode($image_data);
								$signatures[] = [
									'signature' => $signature
								];
							}
						}
					}

					$organization_roneveshts = [];
					$query = '
						select b.`name` as organization_name, b.`phone` as organization_phone, c.`name` as organization_person, c.`post` as organization_post
						from `auto_letter_organization_ronevesht` as a join `auto_organization` as b on(a.organization_id = b.`id`)
							 left join `auto_organization_persons` as c on(a.organization_persons_id = c.`id`)
						where a.deleted = 0 and a.`letter_id` = "'.$letter_id.'";
					';
					$result1 = executeQuery($query);
					if(is_array($result1) && count($result1) > 0){
						foreach($result1 as $row1){
							$organization_roneveshts[] = [
								'ronevesht' => $row1['organization_name'].(!empty($row1['organization_person'])?'/'.$row1['organization_person']:'').
																		  (!empty($row1['organization_post'])?'/'.$row1['organization_post']:'')
							];
						}
					}

					$data['templateName'] = $row['word_name'];
                    $letter_number = $this->mb_str_split($row['number'],1,'UTF-8');
                    $l_letter_number = '';
                    $m_letter_number = '';
                    $r_letter_number = '';
                    $stage = 0;
                    $is_persian = 0;
                    $persian_character = ['آ','ا','ب','پ','ت','ث','ج','چ','ح','خ','د','ذ','ر','ز','ژ','س','ش','ص','ض','ط','ظ','ع','غ','ف','ق','ک','گ','ل','م','ن','و','ه','ی','ي','ك'];
                    foreach($letter_number as $val){
                        if(is_numeric($val)){
                            if($stage == 1)
                                $r_letter_number.=$val;
                            else
                                $l_letter_number.=$val;
                        }
                        else{
                            if($is_persian == 0 && in_array($val,$persian_character))
                                $is_persian = 1;
                            $stage = 1;
                            $m_letter_number.=$val;
                        }
                    }
                    if($is_persian == 0){
                        $r_letter_number  = '';
                        $m_letter_number = $row['number'];
                        $l_letter_number = '';
                    }
					$data['result'] = [
						'l_l_n' => $l_letter_number,
						'm_l_n' => $m_letter_number,
						'r_l_n' => $r_letter_number,
						'letter_number' => $row['number'],
						'subject' => $row['subject'],
						'internal_letter_number' => $row['internal_letter_number'],
						'internal_letter_date' => $internal_letter_date,
						'priority_name' => $row['priority_name'],
						'security_name' => $row['security_name'],
						'dabirkhaneh_name' => $row['dabirkhaneh_name'],
						'organization_name' => $row['organization_name'],
						'organization_phone' => $row['organization_phone'],
						'organization_person' => $row['organization_person'],
						'organization_post' => $row['organization_post'],
						'letter_title_name' => $row['letter_title_name'],
						'letter_unit_name' => $row['letter_unit_name'],
						'letter_date' => $letter_date,
						'letter_date_g' => $letter_date_g,
						'letter_time' => $letter_time,
						'peyvast' => $peyvast,
						'sender' => $sender,
						'girandegan' => $girandegan,
						'roneveshts' => $roneveshts,
						'organization_roneveshts' => $organization_roneveshts,
						'signatures' => $signatures
					];

					$input = [
						'template_id' => $row['template_id'],
						'letter_date' => $row['letter_date'],
						'letter_id' => $letter_id
					];
					$variables = $this->auto_get_variables_data($input);
					foreach($variables as $key=>$value){
						if(!isset($data['result'][$key]))
							$data['result'][$key] = $value;
					}
					if(isset($inputData['from']) && $inputData['from'] == 'direct'){
						$tm->tm_auto_create_print($data['result'], $row['word_name'], $letter_id);
					}
				}
			}
		}

		$output['data'] = $data;
		return $output;
	}

	public function auto_open_word_file($inputData = [])
	{
		$output = [];
		
		if(!isset($inputData['template']) || empty($inputData['template'])){
			$output['error'] = 'قالب را مشخص کنید';
			return $output;
		}
		if(!strstr($inputData['template'], '_Word_')){
			$output['error'] = 'قالب را به درستی مشخص کنید';
			return $output;
		}
		
		$created_template = 0;
		if(function_exists('loadTemplateMaker')){
			$template = explode('_', $inputData['template']);
			$query = '
				select * from auto_template
				where id = "'.$template[0].'" and print_type = "Word" and deleted = 0;
			';
			$result = executeQuery($query);
			if(is_array($result) && count($result) > 0){
				$tm = loadTemplateMaker();
				$created_template = $tm->tm_auto_copy_template($_SESSION['APPLICATION'], $result[1]['print_file_name']);
			}
		}
		$output['data'] = $created_template;
		return $output;
	}

	public function auto_set_organization_template($inputData = [])
	{
		if(!isset($inputData['organization_id']) || empty($inputData['organization_id'])){
			$output['error'] = 'سازمان را مشخص کنید';
			return $output;
		}

		global $RedisObj;
		$memKey = 'autoSetOrganizationTemplate'.$inputData['organization_id'];
		if (!autoCheckRedis() || ($output = ($RedisObj->get($memKey)?json_decode($RedisObj->get($memKey), true):false)) === false) {
			$query = '
				SELECT CONCAT(a.id, \'_\', a.print_type, \'_\', a.enable_editor) as id
				FROM `auto_template` as a join auto_organization as b on(a.id = b.template_id and b.deleted = 0)
				where a.deleted = 0 and b.id = "'.$inputData['organization_id'].'" and letter_type LIKE "%export%"
			';
			$result = executeQuery($query);
			$output['data'] = '';
			if(is_array($result) && count($result) > 0)
				$output['data'] = $result[1]['id'];
			
			if(autoCheckRedis()){
				$RedisObj->set($memKey, json_encode($output));
				$RedisObj->expire($memKey, 8*60*60);
			}
		}

		return $output;
	}

	public function auto_delete_letters($inputData = [])
	{
		$output = [];
		
		if(empty($inputData['letters'])){
			$output['error'] = 'شماره نامه را مشخص کنید';
			return;
		}
		
		$id = 0;
		$cases = [];
		$receiver_id = [];
		$query = '
			select b.id, letter_id, from_APP_UID, to_APP_UID
			from auto_letter as a join auto_letter_receiver as b on(a.id = b.letter_id)
			where a.`number` = "'.$inputData['letters'].'"
		';
		$result = executeQuery($query);
		if(!count($result)){
			$output['error'] = 'شماره نامه را به درستی مشخص کنید!';
			return $output;
		}

		foreach($result as $row){
			$id = $row['letter_id'];
			$receiver_id[] = $row['id'];
			if(!in_array($row['from_APP_UID'], $cases))
				$cases[] = $row['from_APP_UID'];
			if(!in_array($row['to_APP_UID'], $cases))
				$cases[] = $row['to_APP_UID'];
		}

		if(count($cases) > 0){
			$case = new Cases();
			for($i=0;$i<count($cases);$i++){
				try {
					$case->removeCase($cases[$i]);
				} catch(Exception $e){
					error_log($e->getMessage(), 0);
				}
			}
		}

		if(!empty($id)){
			$query = '
				delete from auto_letter
				where id = "'.$id.'"
			';
			executeQuery($query);
			
			$query = '
				delete from auto_letter_receiver
				where letter_id = "'.$id.'"
			';
			executeQuery($query);
			
			$query = '
				delete from auto_letter_attachment
				where letter_id = "'.$id.'"
			';
			executeQuery($query);
			
			$query = '
				delete from auto_letter_sign
				where letter_id = "'.$id.'"
			';
			executeQuery($query);
			
			$query = '
				delete from auto_letter_organization_ronevesht
				where letter_id = "'.$id.'"
			';
			executeQuery($query);
			
			$query = '
				delete from auto_letter_actions
				where letter_receiver_id in ('.implode(',', $receiver_id).')
			';
			executeQuery($query);
		}
		
		$output['success'] = 'نامه ها با موفقیت حذف شدند';
		return $output;
	}
	
	public function auto_get_templates()
	{
		$result_js = [];
		$result_flash = [];
		$result_word = [];
		
		if(function_exists('pm_get_templates')){
			$result_js = pm_get_templates('JS');
			$result_flash = pm_get_templates('Flex');
		}
		if(function_exists('loadTemplateMaker')){
			$tm = loadTemplateMaker();
			$result_word = $tm->tm_get_templates('dropdown');
		}
		
		return [$result_js, $result_flash, $result_word];
	}
	
	public function auto_check_ekhtetam_permission($type = 'export')
	{
		$hidden_js = '';
		$group_id = '89126876660b0a61e502cd0003441869';
		if($type == 'import')
			$group_id = '927345098650873d0f23597018829333';
		$query = '
			select USR_UID
			from GROUP_USER
			where USR_UID = "'.$_SESSION['USER_LOGGED'].'" and GRP_UID = "'.$group_id.'"
		';
		$result = executeQuery($query);
		if(!(is_array($result) && count($result) > 0)){
			$hidden_js .= '
				$("#submit_end").hide();
			';
		}
		return $hidden_js;
	}

	public function get_send_form_data($inputData = [])
	{
		$output = [];
		$query = '
			select id, letter_type, dabirkhaneh_id
			from view_auto_letters
			where (from_user = "'.$_SESSION['USER_LOGGED'].'" OR to_user = "'.$_SESSION['USER_LOGGED'].'" OR supervisor = 1) and
				  (dabirkhaneh_USR_UID = "'.$_SESSION['USER_LOGGED'].'" OR ISNULL(dabirkhaneh_USR_UID)) and
				  id = "'.$inputData['letter_id'].'"
			group by id;
		';
		$result = executeQuery($query);
		if(!count($result)){
			$output['error'] = 'اجازه ارجاع نامه را ندارید!';
			return $output;
		}
		$output = [
			'user_logged' => $_SESSION['USER_LOGGED'],
			'letter_type' => $result[1]['letter_type'],
			'dabirkhaneh' => $result[1]['dabirkhaneh_id'],
			'id' => $result[1]['id']
		];
		return $output;
	}

	public function send_letter($inputData = [])
	{
		$output = [];
		$query = '
			select id, letter_type, `number`, `subject`, organization_name, word_name
			from view_auto_letters
			where (from_user = "'.$_SESSION['USER_LOGGED'].'" OR to_user = "'.$_SESSION['USER_LOGGED'].'" OR supervisor = 1) and
				  (dabirkhaneh_USR_UID = "'.$_SESSION['USER_LOGGED'].'" OR ISNULL(dabirkhaneh_USR_UID)) and
				  id = "'.$inputData['letter_id'].'"
			group by id;
		';
		$result = executeQuery($query);
		if(!count($result)){
			$output['error'] = 'اجازه ارجاع نامه را ندارید!';
			return $output;
		}

		$grid_receiver = [];
		foreach($inputData['grid_receiver'] as $row){
			$grid_receiver[] = [
				'grid_suggest_user' => $row[0],
				'semat_receive' => $row[1],
				'grid_dropdown_receiver_type' => $row[2],
				'grid_receive_type' => $row[3],
				'grid_datetime_due_date' => $row[4],
				'grid_textarea_comment' => $row[5],
				'receiver_user_logged' => $row[6],
				'receiver_letter_type' => $row[7]
			];
		}
		$grid_attachment = [];
		foreach($inputData['grid_attachment'] as $row){
			if(empty($row[3]))
				continue;
			$grid_attachment[] = [
				'grid_file_attach' => [0=>['appDocUid'=>$row[3]]],
				'grid_attach_textarea_comment' => $row[1],
				'secret' => $row[2],
				'grid_hidden_attach' => $row[3]
			];
		}

		$inputData = [
			'application' => $_SESSION['APPLICATION'],
			'letter_id' => $result[1]['id'],
			'word_name' => $result[1]['word_name'],
			'from_semat' => $inputData['from_semat'],
			'letter_type' => $result[1]['letter_type'],
			'grid_receiver' => $grid_receiver,
			'grid_attachment' => $grid_attachment,
			'letter_number' => $result[1]['number'],
			'subject' => $result[1]['subject'],
			'organization_name' => $result[1]['organization_name']
		];
		$result = $this->auto_send_letter($inputData);

		if($result)
			$output['success'] = ['نامه با موفقیت ارجاع یافت.'];
		else
			$output['error'] = 'در ارجاع نامه خطایی رخ داده است، مجددا سعی نمایید!';
		return $output;
	}
}
