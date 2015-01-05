<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class account {
	var $mail_noti_arr = array();
	function account() {
		global $nav, $config, $client, $styles, $custom_css,$menuon;
		$this->mail_noti_arr = array(
			'dismail_message'=>t('Private message'),
			'dismail_friend_request' => t('Friend request'),
			'dismail_friend_request_c' => t('Friend request confirmed'),
			'dismail_wall_comment' => t('Wall post'),
			'dismail_stream_comment' => t('Stream comment'),
			'dismail_group_reply' => t('Group reply'),
		);
		$menuon = 'myprofile';
		$config['hide_ad'] = 1;
		if (!$client['id']) {
			redirect(url('member/login/1'));
		}
		clear_as();
		set_title('Account settings');
		
		$nav[] = url('account',t('account'));
	}
	
	
	function index($onreg = 0) {
		global $content, $db, $nav, $client, $sub_menu, $locations, $all_apps;
		$errors = array();
		if ($_POST['onpost']) {
			if ($_POST['birthyear'] < 1900 || $_POST['birthyear'] > date("Y",time()) || !$_POST['birthmonth'] || !$_POST['birthday']) {
				$errors[] = ('Please fill the Birth info');
			}
			if (!strlen($_POST['location'])) {
				$errors[] = t('Please fill the Location');
			}
			elseif (!strlen($_POST['fullname'])) {
				$errors[] = t('Please fill the Full Name');
			}
			elseif (preg_match("/[<>]/",$_POST['fullname'])) {
				$errors[] = 'Please remove the "&gt;" or "&lt;" from your Fullname';
			}
			else {
				for($i=1;$i<=7;$i++) {
					$col = 'var'.$i;
					$key = 'cf_var'.$i;
					$key2 = 'cf_var_value'.$i;
					$key3 = 'cf_var_des'.$i;
					$key4 = 'cf_var_label'.$i;
					$key5 = 'cf_var_required'.$i;
					$ctype = get_gvar($key);
					if ($ctype != 'disabled' && get_gvar($key5)) {
						if (!strlen($_POST[$col])) {
							$errors[] = t('Please fill in all the required blanks');
						}
					}
				}
			}
			if (!count($errors)) {
				$res = sql_query("select * from `".tb()."accounts` where id='{$client['id']}'");
				$profile = sql_fetch_array($res);
				
				$newacc['location'] = h($_POST['location']);
				$newacc['intr'] = $_POST['intr'];
				$newacc['about_me'] = $_POST['about_me'];
				$newacc['fullname'] = $_POST['fullname'];
				$newacc['gender'] = $_POST['gender'];
				$newacc['birthyear'] = $_POST['birthyear'];
				$newacc['birthmonth'] = $_POST['birthmonth'];
				$newacc['birthday'] = $_POST['birthday'];
				$newacc['id'] = $client['id'];
				$newacc['hide_age'] = $_POST['hide_age'];
				if (sql_update($newacc,tb().'accounts')) {
					sql_query("update `".tb()."accounts` set var1='{$_POST['var1']}',var2='{$_POST['var2']}',var3='{$_POST['var3']}',
					var4='{$_POST['var4']}',var5='{$_POST['var5']}',var6='{$_POST['var6']}',var7='{$_POST['var7']}' where id='{$client['id']}' ");
					if (strlen($_COOKIE['j_return_url'])) {
						setcookie('j_return_url', '', time()+3600*24*365,"/");
						redirect(url($_COOKIE['j_return_url']));
					}
					else {
						if (preg_match("/undefined/i",$client['avatar'])) {
							redirect(url('account/avatar'),1);
						}
						else {
							redirect(my_jcow_home());
						}
					}
				}
			}
		}
		if (count($errors)) {
			foreach ($errors as $error) {
				sys_notice($error);
			}
		}
		if ($onreg) {
			sys_notice(t('Please complete the profile information'));
		}
		if ($client['id']) {
			$res = sql_query("select * from `".tb()."accounts` where id='{$client['id']}' ");
			$row = sql_fetch_array($res);
			if (!$row['id']) {
				die('wrong uid');
			}
		}
		if ($client['profile_permission'] == 1) {
			$profile_permission_all = 'selected';
		}
		else {
			$profile_permission_friends = 'selected';
		}
		$res = sql_query("SELECT * FROM `".tb()."accounts` where id='{$client['id']}' ");
		$profile = sql_fetch_array($res);
		if ($_POST['fullname']) {
			$set_fullname = $_POST['fullname'];
		}
		else {
			$set_fullname = $client['fullname'];
		}
		if ($_POST['location']) {
			$set_location = $_POST['location'];
		}
		else {
			$set_location = $client['location'];
		}
		section_content('
					<form method="post" name="form1" action="'.url('account/index').'"  enctype="multipart/form-data">
					
					<fieldset>
					<legend>'.t('Private info').'</legend>
					'.t('Only your friends can see your Private info').'
					<p>
					'.label('*'.t('Full Name')).'
					<input type="text" size="20" name="fullname" value="'.h($set_fullname).'"  class="fpost" style="width:180px" />
					</p>

					<p>
					'.label(t('Gender')).'');
					if ($row['gender'] == 1) {
						$male_checked = 'checked';
					}
					elseif ($row['gender'] == 0) {
						$female_checked = 'checked';
					}
					else {
						$gender_hide = 'checked';
					}
					section_content('
					<input type="radio" name="gender" value="1" '.$male_checked.' />'.t('Male').' 
					<input type="radio" name="gender" value="0" '.$female_checked.' />'.t('Female').'
					<input type="radio" name="gender" value="2" '.$gender_hide.' />'.t('Hide').'
					</p>
					
					<p>
					'.label('*'.t('Birth')).'
					<select name="birthyear" class="fpost">
					<option value="0">Select..</option>
					');
					if ($_POST['birthyear']) {
						$row['birthyear'] = $_POST['birthyear'];
					}
					if ($_POST['birthmonth']) {
						$client['birthmonth'] = $_POST['birthmonth'];
					}
					if ($_POST['birthday']) {
						$client['birthday'] = $_POST['birthday'];
					}
					$year_from = date("Y",time()) - 8;
					$year_to = date("Y",time()) - 100;
					for ($i=$year_from;$i>$year_to;$i--) {
						$selected = '';
						if ($row['birthyear'] == $i)
							$selected = 'selected';
						section_content('<option value="'.$i.'" '.$selected.'>'.$i.'</option>');
					}
					if ($row['hide_age']) $hide_age = 'checked';
					section_content('
					</select> 
					<select name="birthmonth" class="fpost">
					<option value="0"> </option>');
					for ($i=1;$i<13;$i++) {
						if ($i<10)$j='0'.$i;else $j=$i;$iss='';
						if ($client['birthmonth'] == $j) $iss='selected';
						section_content('<option value="'.$j.'" '.$iss.'>'.$j.'</option>');
					}
					section_content('</select>
					<select name="birthday" class="fpost">
					<option value="0"> </option>');
					for ($i=1;$i<=31;$i++) {
						if ($i<10)$j='0'.$i;else $j=$i;$iss='';
						if ($client['birthday'] == $j) $iss='selected';
						section_content('<option value="'.$j.'" '.$iss.'>'.$j.'</option>');
					}
					section_content('</select><br />
					<input type="checkbox" name="hide_age" value="1" '.$hide_age.' />'.t('Hide my age').'
					</p>
					</fieldset>
					<fieldset>
					<legend>'.t('Public info').'</legend>
					<p>
					'.label('*'.t('Come from')).'
					<select name="location" class="inputText">');
					$locations = explode("\r\n",get_text('locations'));
					foreach($locations as $location) {
						if ($set_location == trim($location)) {
							$selected = 'selected';
							$gotlselected = 1;
						}
						else {
							$selected = '';
						}
						$loptions .= '<option value="'.$location.'" '.$selected.' >'.$location.'</option>';
					}
					if (!$gotlselected) {
						$loptions = '<option value="" selected>Select..</option>'.$loptions;
					}
					section_content($loptions.'</select>
					</p>
					
					<p>
					'.label(t('About me')).'
					<textarea rows="5" name="about_me">'.htmlspecialchars($client['about_me']).'</textarea>
					</p>');
					
					// custom fields 
					for($i=1;$i<=7;$i++) {
						$col = 'var'.$i;
						$key = 'cf_var'.$i;
						$key2 = 'cf_var_value'.$i;
						$key3 = 'cf_var_des'.$i;
						$key4 = 'cf_var_label'.$i;
						$key5 = 'cf_var_required'.$i;
						$ctype = get_gvar($key);
						$value = get_gvar($key2);
						$des = get_gvar($key3);
						$label = get_gvar($key4);
						$required = get_gvar($key5);
						if ($ctype != 'disabled') {
							if ($required) $required = '*';
							if ($ctype == 'text') {
								if (strlen($profile[$col])) {
									$value = htmlspecialchars($profile[$col]);
								}
								section_content('<p>
								'.label($required.$label).'<input type="text" name="'.$col.'" value="'.$value.'" />
								<span>'.$des.'</span></p>');
							}
							elseif ($ctype == 'textarea') {
								if (strlen($profile[$col])) {
									$value = htmlspecialchars($profile[$col]);
								}
								section_content('<p>
								'.label($required.$label).'<textarea rows="3" name="'.$col.'" />'.$value.'</textarea><br />
								<span>'.$des.'</span></p>');
							}
							elseif ($ctype == 'select_box') {
								$tarr = explode("\r\n",$value);
								section_content('<p>
								'.label($required.$label).'
								<select name="'.$col.'">
								');
								foreach ($tarr as $val) {
									if ($val == $profile[$col]) {
										$selected = 'selected';
									}
									else {
										$selected = '';
									}
									section_content('<option value="'.$val.'" '.$selected.'>'.$val.'</option>');
								}
								section_content('</select><span>'.$des.'</span></p>');
							}
						}
					}
					
					section_content('</fieldset>
					<p>');
					if ($onreg) {
						section_content('<input type="hidden" name="onreg" value="'.$onreg.'" />
						
									<input class="button" type="submit" value="'.t('Submit').'" />');
					}
					else {
						section_content('<input class="button" type="submit" value="'.t('Save').'" />');
					}
					section_content('
					<input type="hidden" name="onpost" value="1" /></p>
					</form>
					');
		section_close();
	}

	function notifications() {
		global $content, $db, $nav, $client, $sub_menu, $locations, $current_sub_menu;
		$current_sub_menu['href'] = 'account/notifications';
		section_content('<form method="post" name="form1" action="'.url('account/notificationspost').'" >');
		foreach ($this->mail_noti_arr as $key=>$flag) {
			$checked = $client['settings'][$key] ? '':'checked';
			section_content('<p>
			<input type="checkbox" value="1" '.$checked.' name="'.$key.'" />'.$flag.'
			</p>');
		}
		section_content('
					<p>
					<input class="button" type="submit" value="'.t('Save').'" />
					</p>
					</form>
					');
		section_close(t('Receive emails for these kinds of notifications'));
	}

	function notificationspost() {
		global $client, $ss, $ubase;
		foreach ($this->mail_noti_arr as $key=>$flag) {
			$value = $_POST[$key] ? 0 : 1;
			$client['settings'][$key] = $value;
		}
		$newacc['settings'] = serialize($client['settings']);
		$newacc['id'] = $client['id'];
		sql_update($newacc,tb().'accounts');
		redirect(url('account/notifications'),1);
	}

	function privacy() {
		global $content, $db, $nav, $client, $sub_menu, $locations, $current_sub_menu;
		$current_sub_menu['href'] = 'account/privacy';
		if ($client['id']) {
			$res = sql_query("select * from `".tb()."accounts` where id='{$client['id']}' ");
			$row = sql_fetch_array($res);
			if (!$row['id']) {
				die('wrong uid');
			}
		}
		if ($client['profile_permission'] == 0) {
			$profile_permission_0 = 'selected';
		}
		elseif ($client['profile_permission'] == 1) {
			$profile_permission_1 = 'selected';
		}
		else {
			$profile_permission_2 = 'selected';
		}
		$hide_me = $row['hide_me'] ? 'checked':'';
		section_content('
					<form method="post" name="form1" action="'.url('account/privacypost').'" >
					
					'.label(t('Profile privacy')) );
					section_content('
					<input type="hidden" name="profile_permission" value="1" />
					
					<p>
					'.label('Community browse').'
					<input type="checkbox" name="hide_me" value="1" '.$hide_me.' />'.t('Hide me from member browse').'
					</p>
					<p>
					<input class="button" type="submit" value="'.t('Save').'" />
					</p>
					</form>
					');
	}

	function privacypost() {
		global $client, $ss, $ubase;
		$res = sql_query("select * from `".tb()."accounts` where id='{$client['id']}' ");
		$profile = sql_fetch_array($res);
		$newacc['hide_me'] = $_POST['hide_me'];
		$newacc['profile_permission'] = $_POST['profile_permission'];
		$newacc['id'] = $client['id'];
		sql_update($newacc,tb().'accounts');
		redirect(url('account/privacy'),1);
	}
	
	
	function avatar() {
		global $content, $db, $nav, $client, $sub_menu, $locations, $current_sub_menu;
		$current_sub_menu['href'] = 'account/avatar';
		if ($client['id']) {
			$res = sql_query("select * from `".tb()."accounts` where id='{$client['id']}' ");
			$row = sql_fetch_array($res);
			if (!$row['id']) {
				die('wrong uid');
			}
		}
		if ($client['profile_permission'] == 1) {
			$profile_permission_all = 'selected';
		}
		else {
			$profile_permission_friends = 'selected';
		}
		$redirect = my_jcow_home();
		section_content('
					<form method="post" name="form1" action="'.url('account/avatarpost').'" enctype="multipart/form-data">
					
					<fieldset>
					<legend>'.t('Avatar picture').'</legend>
					<p>
					<img src="'.uhome().'/'.uploads.'/avatars/'.$client['avatar'].'" class="avatar" />
					</p>
					<p>
					'.label(t('Upload')).'
					<input name="avatar" type="file" id="avatar" />
					</p>
					</fieldset>

					<p>
					'.$redirect_field.'
					<input class="button" type="submit" value="'.t('Save').'" /> 
					<a href="'.$redirect.'">Skip</a>
					</p>
					</form>
					');
		section_close();
	}

	function avatarpost() {
		global $db, $client, $ss, $ubase;
		if (!$client['id']) {
			die('only members');
		}
		$res = sql_query("select * from `".tb()."accounts` where id='{$client['id']}' ");
		$profile = sql_fetch_array($res);
		
		// avatar
		if (strlen($_FILES['avatar']['tmp_name'])>0 && $_FILES['avatar']['tmp_name'] != "none") {
			include_once('includes/libs/resizeimage.inc.php');
			$dir = date("Ym",time());
			$folder = uploads.'/avatars/'.$dir;
			if (!is_dir($folder))
				mkdir($folder, 0777);
			$s_folder = uploads.'/avatars/s_'.$dir;
			if (!is_dir($s_folder))
				mkdir($s_folder, 0777);
			$name = date("H_i",time()).'_'.get_rand(5);
			//small
			$resizeimage = new resizeimage($_FILES['avatar']['tmp_name'], $_FILES['avatar']['type'], $s_folder.'/'.$name, 50,50, 0,100,'white');
			//big
			$resizeimage = new resizeimage($_FILES['avatar']['tmp_name'], $_FILES['avatar']['type'], $folder.'/'.$name, 160,160, 0, 100,'white');
			$reset_avatar = "avatar='".$dir.'/'.$client['id'].".".$resizeimage->type."' ";
			$newacc['avatar'] = $dir.'/'.$name.".".$resizeimage->type;
			$newacc['id'] = $client['id'];
			sql_update($newacc,tb().'accounts');
			if ($profile['avatar']) {
				@unlink(uploads.'/avatars/'.$profile['avatar']);
				@unlink(uploads.'/avatars/s_'.$profile['avatar']);
			}
		}
		redirect(my_jcow_home(),1);
	}
	
	function backgrounddel() {
		global $nav, $client, $uhome;
		if (!$client['id']) {
			die('backgrounddel');
		}
		$res = sql_query("SELECT * FROM `".tb()."profiles` where id='{$client['id']}'");
		$profile = sql_fetch_array($res);
		$arr = unserialize($profile['custom_css']);
		unlink($arr['wallpaper_bg_image']);
		$arr['wallpaper_bg_image'] = '';
		$custom_css = serialize($arr);
		sql_query("update `".tb()."profiles` set custom_css='$custom_css' where id='{$client['id']}'");
		redirect(url('account/customtheme'),1);

	}

	function cpassword() {
		c('
		<form method="post" name="form1" action="'.url('account/cpasswordpost').'" >
					
					
					<p>
					'.label(t('Current Password')).'
					<input type="password" name="password" />
					</p>
					<p>
					'.label(t('New password')).'
					<input type="password" name="password1" />
					</p>
					<p>
					'.label(t('Re-type new password')).'
					<input type="password" name="password2" />
					</p>

					<p>
					<input class="button" type="submit" value="'.t('Save').'" />
					</p>

					</form>');
	}

	function cpasswordpost() {
		global $client;
		$opassword = md5($_POST['password'].'jcow');
		$res = sql_query("select * from ".tb()."accounts where id='{$client['id']}' and password='{$opassword}'");
		$row = sql_fetch_array($res);
		if (!$row['id']) {
			sys_back(t('Wrong password'));
		}
		if (!strlen($_POST['password1'])) {
			sys_back('Please type a new password');
		}
		if ($_POST['password1'] != $_POST['password2']) {
			sys_back('Please re-type new password');
		}
		$password = md5($_POST['password1'].'jcow');
		sql_query("update ".tb()."accounts set password='$password' where id='{$client['id']}'");
		redirect('account/cpassword',1);
	}



}

function get_style_list($dirname) {
	if ($handle = opendir($dirname)) {
		while (false !== ($file = readdir($handle))) {
			if (is_dir($dirname . '/' .$file) && $file != '.' && $file != '..' && $file != '.svn' ) {
				$dirs[] = $file;
			}
		}
		closedir($handle);
		
		if (is_array($dirs)) {
			asort($dirs);
			return $dirs;
		}
		else {
			return 0;
		}
	}
}