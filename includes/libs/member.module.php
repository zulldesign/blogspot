<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class member{
	function member() {
		clear_as();
	}
	function login($need_login = 0, $iid = 0) {
		global $db, $nav, $client, $config,$captcha;
		set_title('Log In');
		if ($client['id']) redirect(my_jcow_home());
		if ($need_login) {
			sys_notice(t('You need to login to do this'));
		}
		$nav[] = url('member/login','Login');
		section_content('
<script language="javascript" >
			$(document).ready( function(){
								$("#recaptcha_response_field").attr("tabindex",4);
		});
								</script>
								<table border="0" width="80%">
								<tr><td>

<form method="post" name="form1" id="form1" action="'.url('member/loginpost').'" >
								<table>
								<tr>
								<td align="right" valign="top">
								'.t('Username or Email').':</td><td>
								<input type="text" size="10" name="username" style="width:120px" value="'.h($_POST['username']).'" tabindex=1 /><div class="sub">'.url('member/signup',t('Register an account')).'</div></td>
								</tr>
								<tr>
								<td align="right" valign="top">
								'.t('Password').':</td><td>
								<input type="password" size="10" name="password" style="width:120px"  value="'.h($_POST['password']).'" tabindex=2 />
								<div class="sub">'.url('member/chpass',t('Forgot password?')).'</div></td>
								</tr>
								</table>							
								');
			
			c('
								<p>
								( <input type="checkbox" name="remember_me" value="1" '.$remember_check.' tabindex=4 /> '.t('Remember me').' ) 
								<input type="submit" value="'.t('Login').'" tabindex=5 /> 
								'.$bakg.'
								<br />
								</p>
								</form>

								</td>

							<td>');
			if (get_gvar('fb_id')) {
				c('<p>'.url('fblogin','<img src="'.uhome().'/modules/fblogin/button.png" />').'</p>');
			}
			c('
							<span style="font-size:20px"> '.url('member/signup',t('SignUp Now!')).'</span></td>
							</tr>
							</table>
								<script language="javascript" >
					document.form1.username.focus();
					</script>
			');
		section_close(t('Log in'));

	}

	function loginpost() {
		global $db, $client, $ss, $ubase,$sid, $captcha;
		if ($client['id']) redirect(my_jcow_home());
		if (!strlen($_POST['username'])) {
			redirect(url('member/login'));
		}
		$togkey = md5($_POST['username']);
		$atempts = get_tmp($togkey);
		if (!is_numeric($atempts)) $atempts = 0;
		
		if (!get_gvar('disable_recaptcha_login')) {
			if ($atempts > 2) {
				if ($_POST["recaptcha_challenge_field"]) {
					$resp = recaptcha_check_answer ($captcha['privatekey'],
													$_SERVER["REMOTE_ADDR"],
													$_POST["recaptcha_challenge_field"],
													$_POST["recaptcha_response_field"]);
					if (!$resp->is_valid) {
							c('<script language="javascript" >
					$(document).ready( function(){
										$("#recaptcha_response_field").focus();
				});
										</script>');
							$captchaerror = $resp->error;
							$hold = 1;
					}
				}
				else {
					c('<script language="javascript" >
					$(document).ready( function(){
										$("#recaptcha_response_field").focus();
				});
										</script>');
					$hold = 1;
				}
			}
		}
	
		if (!$hold) {
			$password = md5($_POST['password'].'jcow');
			$res = sql_query("select * from `".tb()."accounts` where  (email='".$_POST['username']."' or username='".$_POST['username']."') and password='$password'  limit 1");
			if (sql_counts($res)) {
				$newss = get_rand(12);
				$row = sql_fetch_array($res);
				if ($_POST['remember_me']) {
					sql_query("UPDATE `".tb()."accounts` SET jcowsess='$newss',ipaddress='".addslashes($client['ip'])."' WHERE id='{$row['id']}' ");
					setcookie('jcowss', $newss, time()+3600*24*365,"/");
					setcookie('jcowuid', $row['id'], time()+3600*24*365,"/");
				}
				$_SESSION['uid'] = $row['id'];
				set_tmp($togkey,'deleteit');
				if (strlen($_COOKIE['j_return_url'])) {
					setcookie('j_return_url', '', time()+3600*24*365,"/");
					redirect(url($_COOKIE['j_return_url']));
				}
				else {
					redirect(my_jcow_home());
				}
				exit;
			}
			else {
				$atempts++;
				set_tmp($togkey,$atempts);
				sys_notice(t('Wrong account or password'));
			}
		}
		else {
			$atempts++;
			set_tmp($togkey,$atempts);
			sys_notice(t('Wrong account or password'));
		}
			set_title('Login');
			if ($_POST['remember_me']) {
				$remember_check = 'checked';
			}
			section_content('
			<script language="javascript" >
			$(document).ready( function(){
								$("#recaptcha_response_field").attr("tabindex",3);
		});
								</script>
								<table border="0" width="80%">
								<tr><td>
			<form method="post" name="form1" id="form1" action="'.url('member/loginpost').'" >
								<table>
								<tr>
								<td align="right" valign="top">
								'.t('Username or Email').':</td><td>
								<input type="text" size="10" name="username" style="width:120px" value="'.h($_POST['username']).'" tabindex=1 /><div class="sub">'.url('member/signup',t('Register an account')).'</div></td>
								</tr>
								<tr>
								<td align="right" valign="top">
								'.t('Password').':</td><td>
								<input type="password" size="10" name="password" style="width:120px"  value="'.h($_POST['password']).'" tabindex=2 />
								<div class="sub">'.url('member/chpass',t('Forgot password?')).'</div></td>
								</tr>
								</table>							
								');
			if (!get_gvar('disable_recaptcha_login')) {
				if ($atempts > 2) {
					c( recaptcha_get_html($captcha['publickey'],$captchaerror));
				}
			}
			
			c('
								<p>
								( <input type="checkbox" name="remember_me" value="1" '.$remember_check.' tabindex=4 /> '.t('Remember me').' ) 
								<input type="submit" value="'.t('Login').'" tabindex=5 /> 
								'.$bakg.'
								<br />
								</p>
								</form>
							</td>

							<td> <span style="font-size:20px"> '.url('member/signup',t('SignUp Now!')).'</span></td>
							</tr>
							</table>
						');
					section_close(t('Log in'));
		
	}

	function logout() {
		global $content, $db, $nav, $sid, $client,$ss,$ubase, $config;
		if ($client['id']) {
			$hooks = check_hooks('logout');
			if ($hooks) {
				foreach ($hooks as $hook) {
					$hook_func = $hook.'_logout';
					$hook_func();
				}
			}
			 $_SESSION['uid'] = 0;
			 setcookie('jcowuid', '', time()+3600*24*365,"/");
			 setcookie('jcowss', '', time()+3600*24*365,"/");
			 redirect(uhome());
		}
		redirect(uhome());
	}

	function chpass() {
		global $client;
		if ($client['id']) {
			die('already logged in');
		}
		c('<h2>'.t('Get back my password').'</h2>
		<form method="post" name="form1" action="'.url('member/chpasspost').'" >
					<p>
					'.label(t('Email')).'
					<input type="text" name="email" />
					<div class="sub">'.t('The email address you registered with').'</div>
					</p>
					<p>
					<input class="button" type="submit" value="'.t('Submit').'" />
					</p>
					</form>
					<script language="javascript" >
					document.form1.email.focus();
					</script>');
	}
	
	function chpasspost() {
		if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $_POST['email'])) {
			sys_back(t('Unavailable email address'));
		}
		$res = sql_query("select * from `".tb()."accounts` where email='{$_POST['email']}'");
		$row = sql_fetch_array($res);
		if (!$row['id']) {
			sys_back(t('No user registered with this email'));
		}
		$code = get_rand(10);
		sql_query("update `".tb()."accounts` set chpass='$code' where id='{$row['id']}'");
		$link = url('member/chpassdo/'.$code,t('Click Here'));
		jcow_mail($_POST['email'],t('Get your password'),$link);
		c(t('A verification email has been sent to you, please check your email inbox'));
	}

	function chpassdo($code = '') {
		if (!preg_match("/^[0-9a-z]+$/i",$code)) die('not a valid code');
		$res = sql_query("select * from `".tb()."accounts` where chpass='{$code}'");
		$user = sql_fetch_array($res);
		if (!sql_counts($res)) die('wrong code');
		c('<form method="post" name="form1" action="'.url('member/chpassdopost').'" >
					<p>
					'.label(t('Pick a new password')).'
					<input type="password" name="password" />
					</p>
					<p>
					<input type="hidden" name="passcode" value="'.$code.'" />
					<input type="hidden" name="id" value="'.$user['id'].'" />
					<input class="button" type="submit" value="'.t('Submit').'" />
					</p>
					</form>');
	}

	function chpassdopost() {
		$code = $_POST['passcode'];
		if (!preg_match("/^[0-9a-z]+$/i",$code)) die('not a valid code');
		$res = sql_query("select * from `".tb()."accounts` where chpass='{$code}'");
		if (!sql_counts($res)) die('wrong code');
		$newpass = md5($_POST['password'].'jcow');
		sql_query("update ".tb()."accounts set chpass='',password='$newpass' where id='{$_POST['id']}' and chpass='{$code}'");
		redirect('member/login',t('Your password has been changed'));
	}


	function signup() {
		global $db, $client, $uhome, $config, $captcha;
		$reg_limit_ip = get_gvar('reg_limit_ip');
		if (get_gvar('signup_closed')) {
			c(t('Sorry, currently we are not accepting new members'));
			stop_here();
		}
		if (is_numeric($reg_limit_ip)) {
			$res = sql_query("select count(*) as num from ".tb()."accounts where ipaddress='{$client['ip']}'");
			$row = sql_fetch_array($res);
			if ($row['num'] >= $reg_limit_ip) {
				c(t('Sorry, only {1} registrations allowed per IP','<strong>'.$reg_limit_ip.'</strong>'));
				stop_here();
			}
		}
		if (get_gvar('only_invited')) {
			$hold = 1;
		}
		$email = $_GET['email'];
		if (isset($_POST['email'])) {
			$email = $_POST['email'];
		}
		$iid = $_GET['iid'];
		if (isset($_POST['iid'])) {
			$iid = $_POST['iid'];
		}
		if (strlen($email)) {
			$res = sql_query("select * from ".tb()."invites where id='$iid' and email='{$email}'");
			$invite = sql_fetch_array($res);
			if ($invite['id']) {
				$hold = 0;
				$iid_field = '<input type="hidden" name="iid" value="'.$iid.'" />';
			}
		}

		if ($_POST['onpost']) {
			if ($hold) {
				c('only invited');
				stop_here();
			}
			if (!get_gvar('disable_recaptcha_reg')) {
				$resp = recaptcha_check_answer ($captcha['privatekey'],
											$_SERVER["REMOTE_ADDR"],
											$_POST["recaptcha_challenge_field"],
											$_POST["recaptcha_response_field"]);

				if (!$resp->is_valid) {
						$captchaerror = $resp->error;
						$errors[] = t('Wrong Verification code');
				}
			}
			if (!$_POST['agree_rules']) {
				$errors[] = t('You must agree to our rules for signing up');
			}
			
			//get_r(array('username','password','password2','email','agree','confirm_code','location'));
			if (strtolower($_COOKIE['cfm']) != strtolower($_POST['confirm_code'])) {
				$errors[] = t('The string you entered for the code verification did not match what was displayed');
			}
			$_POST['username'] = strtolower($_POST['username']);
			if (strlen($_POST['username']) < 4 || strlen($_POST['username']) > 18 || !preg_match("/^[0-9a-z]+$/i",$_POST['username'])) {
				$errors[] = t('Username').': '.t('from 4 to 18 characters, only 0-9,a-z');
			}
			if (preg_match("/</",$_POST['fullname'])) {
				$errors[] = 'Unavailable Full name format';
			}

			if (!$_POST['email'] || !$_POST['username'] || !$_POST['password']) {
				$errors[] = t('Please fill in all the required blanks');
			}
			/*
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
			*/
			if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $_POST['email'])) {
				$errors[] = t('Unavailable email address');
			}

			$password = md5($_POST['password'].'jcow');
			$timeline = time();
			$res = sql_query("select * from `".tb()."accounts` where email='{$_POST['email']}'");
			if (sql_counts($res)) {
				$errors[] = t('You have registered with this email address before.');
			}
			$res = sql_query("select * from `".tb()."accounts` where username='{$_POST['username']}'");
			if (sql_counts($res)) {
				$errors[] = t('The Username has already been used');
			}

			if (!is_array($errors)) {
				$reg_code = '';
				$verify_note = '';
			
				// member
				if ($_POST['hide_age']) {
					$hide_age = 1;
				}
				else {
					$hide_age = 0;
				}
				$newss = get_rand(12);
				if (get_gvar('pm_enabled') || get_gvar('acc_verify')) {
					$member_disabled = 1;
				}
				else {
					$member_disabled = 0;
				}
				sql_query("insert into `".tb()."accounts` (about_me,disabled,gender,location,birthyear,birthmonth,birthday,hide_age,password,email,username,fullname,created,lastlogin,ipaddress,var1,var2,var3,var4,var5,var6,var7,reg_code) values('{$_POST['about_me']}',$member_disabled,'{$_POST['gender']}','{$_POST['location']}','{$_POST['birthyear']}','{$_POST['birthmonth']}','{$_POST['birthday']}','{$hide_age}','$password','".$_POST['email']."','{$_POST['username']}','{$_POST['fullname']}',$timeline,$timeline,'{$client['ip']}','{$_POST['var1']}','{$_POST['var2']}','{$_POST['var3']}','{$_POST['var4']}','{$_POST['var5']}','{$_POST['var6']}','{$_POST['var7']}','{$reg_code}')");
				$uid = insert_id();
				if ($uid == 1) {
					sql_query("update ".tb()."accounts set roles='3' where id='$uid'");
				}
				sql_query("insert into `".tb()."pages` (uid,uri,type) values($uid,'{$_POST['username']}','u')");
				$page_id = insert_id();
				if ($invite['id']>0) {
					sql_query("update ".tb()."invites set status=1 where id='{$invite['id']}'");
					sql_query("insert into `".tb()."friends` (uid,fid,created) values ($uid,{$invite['uid']},".time().")");
					sql_query("insert into `".tb()."friends` (uid,fid,created) values ({$invite['uid']},$uid,".time().")");
				}

				stream_publish(t('Signed Up','','','',1),'','',$uid,$page_id);

				// welcome email
				$welcome_email = nl2br(get_text('welcome_email'));
				$welcome_email = str_replace('%username%',$_POST['username'],$welcome_email);
				$welcome_email = str_replace('%email%',$_POST['email'],$welcome_email);
				$welcome_email = str_replace('%password%',$_POST['password'],$welcome_email);
				$welcome_email = str_replace('%sitelink%',url(uhome(),h(get_gvar('site_name')) ),$welcome_email);
				@jcow_mail($_POST['email'], 'Welcome to "'.h(get_gvar('site_name')).'"!', $verify_note.$welcome_email);
				$_SESSION['login_cd'] = 3;
				//login
				$_SESSION['uid'] = $uid;
				redirect('account/index/1');
				exit;
				//redirect(url('member/login'),t('Congratulations! You have successfully signed up. You can now login with your account'));
			}
			else {
				foreach ($errors as $error) {
					$error_msg .= '<li>'.$error.'</li>';
				}
				sys_notice(t('Errors').':<ul>'.$error_msg.'</ul>');
			}
		}

		
		if ($hold) {
			c(t('Sorry, only invited people can sign up'));
			stop_here();
		}

		set_title('Signup');
			if (get_gvar('pm_enabled')) {
				c('<strong>'.t('Join Us').'</strong><br />
				'.t('Membership pricing').':<ul>');
				if ($pm_1m = get_gvar('pm_1m')) {
					c('<li>'.$pm_1m.' '.get_gvar('pm_currency').' '.t('Per month').'</li>');
				}
				if ($pm_3m = get_gvar('pm_3m')) {
					c('<li>'.$pm_3m.' '.get_gvar('pm_currency').' '.t('Per Annua').'</li>');
				}
				if ($pm_12m = get_gvar('pm_12m')) {
					c('<li>'.$pm_12m.' '.get_gvar('pm_currency').' '.t('Per Yeal').'</li>');
				}
				c('</ul>');
				section_close(t('Paid membership'));
			}
					c('
<script>
$(document).ready( function(){
	objrow = $("tr.row1 td::first-child");
	objrow.attr("valign","top");
	objrow.attr("align","right");
	});
</script>');
if (get_gvar('fb_id')) {
				c('<p>'.url('fblogin','<img src="'.uhome().'/modules/fblogin/button.png" />').'</p>');
			}
			c('
		<form method="post" action="'.url('member/signup').'" >
<table class="stories">
<tr class="table_line1">
<td colspan="2">'.t('Passport').'</td>
</tr>
<tr class="row1">
<td>*'.t('Email Address').'</td>
<td>
					<input type="text" size="20" name="email" value="'.h($_REQUEST['email']).'" class="fpost" style="width:180px" />
					<br /><span class="sub">('.$invite_msg.t("We won't display your Email Address.").')</span>
</tr>
<tr class="row1">
<td>*'.t('Username').'/'.t('Nickname').'</td><td>
					<input type="text" size="18" class="fpost" name="username" value="'.h($_REQUEST['username']).'" style="width:180px" /><br />
					<span class="sub">('.t('4 to 18 characters, made up of 0-9,a-z').')</span>
</tr>
<tr class="row1">
<td>*'.t('Password').'</td><td>
					<input type="password" name="password"  class="fpost" value="'.h($_REQUEST['password']).'" style="width:180px" />
</tr>
');
/*
c('
<tr class="table_line1">
<td colspan="2">'.t('Personal info').'</td>
</tr>
<tr class="row1">
<td>*'.t('Full Name').'</td><td>
					<input type="text" size="20" name="fullname" value="'.h($_REQUEST['fullname']).'"  class="fpost" style="width:180px" />
</td>
</tr>
<tr class="row1">
<td>*'.t('Birth').'</td><td>
					<select name="birthyear" class="fpost">
					');
					$year_from = date("Y",time()) - 8;
					$year_to = date("Y",time()) - 100;
					if ($_REQUEST['birthyear'])
						$yearkey = $_REQUEST['birthyear'];
					else
						$yearkey = $year_from - 12;
					for ($i=$year_from;$i>$year_to;$i--) {
						$selected = '';
						if ($yearkey == $i)
							$selected = 'selected';
						c('<option value="'.$i.'" '.$selected.'>'.$i.'</option>');
					}
					if ($row['hide_age']) $hide_age = 'checked';
					c('
					</select>
					<select name="birthmonth" class="fpost">');
					for ($i=1;$i<13;$i++) {
						if ($i<10)$j='0'.$i;else $j=$i;$iss='';
						if ($_REQUEST['birthmonth'] == $j) $iss='selected';
						c('<option value="'.$j.'" '.$iss.' >'.$j.'</option>');
					}
					c('</select>
					<select name="birthday" class="fpost">');
					for ($i=1;$i<=31;$i++) {
						if ($i<10)$j='0'.$i;else $j=$i;$iss='';
						if ($_REQUEST['birthday'] == $j) $iss='selected';
						c('<option value="'.$j.'" '.$iss.'>'.$j.'</option>');
					}
					c('</select><br />
					 <input type="checkbox" name="hide_age" value="1" '.$hide_age.' />'.t('Hide my age').'
					</td></tr>');
					if ($_REQUEST['gender'] == 1) {
						$gender1 = 'checked';
					}
					elseif ($_REQUEST['gender'] == 2) {
						$gender2 = 'checked';
					}
					else {
						$gender0 = 'checked';
					}
					c('
					<tr class="row1"><td>*'.t('Gender').'</td><td>
					<input type="radio" name="gender" value="1" '.$gender1.' />'.t('Male').' 
					<input type="radio" name="gender" value="0" '.$gender0.' />'.t('Female').'
					<input type="radio" name="gender" value="2" '.$gender2.' />'.t('Hide').'
					</td></tr>');
					
					c('<tr class="row1"><td>*'.t('Come from').'</td><td>
					<select name="location" class="inputText">');
					$locations = explode("\r\n",get_text('locations'));
					$_REQUEST['location'] = trim($_REQUEST['location']);
					foreach($locations as $location) {
						if ($_REQUEST['location'] == trim($location)) {
							$selected = 'selected';
						}
						else {
							$selected = '';
						}
						c('<option value="'.$location.'" '.$selected.' >'.$location.'</option>');
					}
					c('</select>
</td>
</tr>
<tr class="row1">
<td>'.t('About me').'</td><td>
					<textarea rows="5" name="about_me">'.htmlspecialchars($client['about_me']).'</textarea>
</td>
</tr>
			'); 
			
			
					// custom fields 
					$profile = array();
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
						if ($required) $required = '*';
						if ($ctype != 'disabled') {
							if ($ctype == 'text') {
								if (strlen($profile[$col])) {
									$value = htmlspecialchars($profile[$col]);
								}
								if (strlen($_POST[$col])) {
									$value = h($_POST[$col]);
								}
								c('<tr class="row1"><td>
								'.$required.$label.'</td><td><input type="text" name="'.$col.'" value="'.$value.'" />
								<br /><span class="sub">'.$des.'</span></td></tr>');
							}
							elseif ($ctype == 'textarea') {
								if (strlen($profile[$col])) {
									$value = htmlspecialchars($profile[$col]);
								}
								if (strlen($_POST[$col])) {
									$value = h($_POST[$col]);
								}
								c('<tr class="row1"><td>'.$required.$label.'</td><td>
								<textarea rows="3" name="'.$col.'" />'.$value.'</textarea><br />
								<br /><span class="sub">'.$des.'</span></td></tr>');
							}
							elseif ($ctype == 'select_box') {
								$tarr = explode("\r\n",$value);
								c('<tr class="row1">
								<td>'.$label.'</td><td>
								<select name="'.$col.'">
								');
								if (strlen($_POST[$col])) {
									$value = h($_POST[$col]);
								}
								foreach ($tarr as $val) {
									if ($val == $value) {
										$selected = 'selected';
									}
									else {
										$selected = '';
									}
									c('<option value="'.$val.'" '.$selected.'>'.$val.'</option>');
								}
								c('</select><br /><span class="sub">'.$des.'</span></td></tr>');
							}
						}
					}
			
*/			
	c($iid_field);
			if (!get_gvar('disable_recaptcha_reg')) {
				c('<tr class="row1">
				<td>'.t('Image verification').'</td><td>
				'.recaptcha_get_html($captcha['publickey'],$captchaerror).'
				</td>
				</tr>');
			}
			
			c('
			<tr class="row2">
			<td colspan="2"><strong>'.t('Rules & Conditions').'</strong>
			<div style="width:700px;height:100px;overflow:scroll;border:white 2px solid;padding:5px;">
			'.nl2br(h(get_text('rules_conditions'))).'
			</div></td>
			</tr>
			<tr class="row2">
			<td colspan="2" align="center">
			<input type="hidden" name="g" value="'.h($_REQUEST['g']).'" />
			<input type="hidden" name="onpost" value="1" />
			<input type="checkbox" name="agree_rules" value="1" checked /> '.t('I have read, and agree to abide by the Rules & Conditions.').' 
						<input type="submit" style="background:#5BA239;color:white;font-size:1.5em;font-weight:bold" value="'.t('Signup Now').'" />
						</td></tr>

						
			</table>
		</form>	
			');
	}

	function need_verify() {
		global $client;
		if (!$client['id']) die('plz login');
		set_title('Not verified');
		c('Your account is pending');
	}
}