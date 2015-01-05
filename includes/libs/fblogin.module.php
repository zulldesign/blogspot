<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */
$arr = explode('.',jversion());
if ($arr[0] < 7) {
	die('');
}
class fblogin{
	function index() {
		global $client;
		$fb_id = get_gvar('fb_id');
		$fb_secret = get_gvar('fb_secret');
		$back_url = urlencode(url('fblogin/verify'));
		$auto_url = 'https://graph.facebook.com/oauth/authorize?client_id='.$fb_id.'&redirect_uri='.$back_url.'&scope=email';
		header("Location:".$auto_url);
		exit;
	} 
	function verify() {
		global $client;
		$fb_id = get_gvar('fb_id');
		$fb_secret = get_gvar('fb_secret');
		$back_url = urlencode(url('fblogin/verify'));
		if (strlen($_GET['code'])) {
			$url2 = 
			'https://graph.facebook.com/oauth/access_token?client_id='.$fb_id.'&redirect_uri='.$back_url.'&client_secret='.$fb_secret.'&code='.$_GET['code'];
			$fb_token = @file_get_contents($url2);
			if (!strlen($fb_token)) die('bad fb code');
			$me = @file_get_contents('https://graph.facebook.com/me?'.$fb_token);
			$fbuser = json_decode($me,true);
			if (!$fbuser['id']) die('bad fb token');
			$_SESSION['fb_logged'] = 1;
			$_SESSION['fb_id'] = $fbuser['id'];
			$res = sql_query("select * from ".tb()."accounts where fbid='{$fbuser['id']}'");
			$user = sql_fetch_array($res);
			if ($user['id']) {//已绑定
				if ($client['id']) { //登录状态
					if ($user['id'] != $client['id']) {
						c(t('Your facebook ID has already bind to {1}','<strong>'.$user['email'].'</strong>'));
						stop_here();
					}
					elseif (!$fbuser['verified']) {
						c(t('Your facebook account is not verified. Please verify your facebook account first and turn to this page. Here is a guide to verify your facebook account: {1}','<a href="http://www.facebook.com/help/verify" target="_blank">www.facebook.com/help/verify</a>'));
						stop_here();
					}
				}
				$_SESSION['uid'] = $user['id'];
				if ($user['disabled'] == 1) {
					if (get_gvar('acc_verify') == 1) {
						$user['disabled'] = 0;
					}
					elseif (get_gvar('acc_verify') == 2) {
						if ($_SESSION['fb_verified']) {
							$user['disabled'] = 0;
						}
					}
					sql_query("update ".tb()."accounts set disabled={$user['disabled']} where id='{$user['id']}'");
				}
				redirect(url('feed'));
			}
			else {//未绑定
				$res = sql_query("select * from ".tb()."accounts where email='{$fbuser['email']}'");
				$user = sql_fetch_array($res);
				if ($user['id'] && !$user['fbid']) {
					$got_email_match = 1;
				}
				if ($client['id']) { //已登录用户，自动绑定
					sql_query("update ".tb()."accounts set fbid='{$fbuser['id']}' where id='{$client['id']}'");
					if ($client['disabled'] == 1) {// verify pending user
						if (get_gvar('acc_verify') == 1) {
							$uv = 'yes';
						}
						elseif (get_gvar('acc_verify') == 2) {
							if ($_SESSION['fb_verified']) {
								$uv = 'yes';
							}
						}
						if ($uv == 'yes') {
							sql_query("update ".tb()."accounts set disabled=0 where id='{$client['id']}'");
						}
					}
					redirect('feed');
				}
				elseif($got_email_match) { //email match,自动绑定
					sql_query("update ".tb()."accounts set fbid='{$fbuser['id']}' where id='{$user['id']}'");
					if ($user['disabled'] == 1) {// verify pending user
						if (get_gvar('acc_verify') == 1) {
							$uv = 'yes';
						}
						elseif (get_gvar('acc_verify') == 2) {
							if ($_SESSION['fb_verified']) {
								$uv = 'yes';
							}
						}
						if ($uv == 'yes') {
							sql_query("update ".tb()."accounts set disabled=0 where id='{$user['id']}'");
						}
					}
					$_SESSION['uid'] = $user['id'];
					redirect(url('feed'));
				}
				else {// 提示注册新帐户
					if (get_gvar('signup_closed')) {
						c(t('Sorry, currently we are not accepting new members'));
						stop_here();
					}
					$_SESSION['fb_verified'] = $fbuser['verified'];
					$_SESSION['fb_email'] = $fbuser['email'];
					$_SESSION['fb_token'] = $fb_token;
					$_SESSION['fb_fullname'] = trim($fbuser['first_name'].' '.$fbuser['last_name']);
					if ($fbuser['gender'] == 'male') $_SESSION['fb_gender'] = 1;else $_SESSION['fb_gender'] = 0;
					if (strlen($fbuser['username'])) $username = $fbuser['username'];
					else {
						$tmpname = strtolower(str_replace(' ','',$fbuser['first_name'].$fbuser['last_name']));
						if (preg_match("/^[0-9a-z]+$/i",$tmpname)) {
							$username = $tmpname;
						}
					}
					if (preg_match("/^[0-9a-z_]+$/i",$username)) {
						redirect('fblogin/signup/'.$username);
					}
					else {
						redirect('fblogin/signup');
					}
				}
			}
		}
	}
	function signup($username='') {
		set_title(t('Sign up'));
		global $client;
		if ($_POST['username']) {
			$username = $_POST['username'];
		}
		if (!$_SESSION['fb_id']) {
			die('no fb id');
		}
		else {
			$res = sql_query("select * from ".tb()."accounts where fbid='{$_SESSION['fb_id']}'");
			$user = sql_fetch_array($res);
			if ($user['id']) { // already joined
				redirect('feed');
			}
			if ($_POST['step'] == 2) {
				if (!$_POST['username']) {
					$errs[] = 'empty username';
				}
				elseif (strlen($_POST['username']) < 4 || strlen($_POST['username']) > 18 || !preg_match("/^[0-9a-z]+$/i",$_POST['username'])) {
					$errs[] = t('Username').': '.t('from 4 to 18 characters, only 0-9,a-z');
				}
				else {
					$_POST['username'] = strtolower($_POST['username']);
					$res = sql_query("select id from ".tb()."accounts where username='{$_POST['username']}'");
					if (sql_counts($res)) {
						$errs[] = t('The Username has already been used');
					}
					$res = sql_query("select id from ".tb()."accounts where email='{$_POST['email']}'");
					if (sql_counts($res)) {
						$errs[] = t('You have already signed up using the Email address, if you want to bind facebook ID to that account, please login first and then click "verify by facebook"');
					}
				}
				if (!$errs) {
					$password = get_rand(10);
					$md5_password = md5($password.'jcow');
					$timeline = time();

					$acc = array(
						'email'=>$_SESSION['fb_email'],
						'gender'=>$_SESSION['fb_gender'],
						'fullname'=>addslashes($_SESSION['fb_fullname']),
						'created'=>$timeline,
						'username'=>$_POST['username'],
						'password'=>$md5_password,
						'fbid'=>$_SESSION['fb_id'],
						'lastlogin'=>$timeline,
						'ipaddress'=>addslashes($client['ip'])
						);
					if (!get_gvar('acc_verify') || get_gvar('acc_verify') == 1) {
						$acc['disabled'] = 0;
					}
					elseif (get_gvar('acc_verify') == 2) {
						if ($_SESSION['fb_verified']) {
							$acc['disabled'] = 0;
						}
						else {
							$acc['disabled'] = 1;
						}
					}
					else {
						$acc['disabled'] = 1;
					}
					//pic
					$big_pic = 'https://graph.facebook.com/me/picture?type=normal&'.$_SESSION['fb_token'];
					$small_pic = 'https://graph.facebook.com/me/picture?type=square&'.$_SESSION['fb_token'];
					$dir = date("Ym",$timeline);
					$folder = uploads.'/avatars/'.$dir;
					if (!is_dir($folder))
						mkdir($folder, 0777);
					$s_folder = uploads.'/avatars/s_'.$dir;
					if (!is_dir($s_folder))
						mkdir($s_folder, 0777);
					$avatar_hash = get_rand(7);
					if ($content = @file_get_contents($big_pic)) {
						@file_put_contents($folder.'/'.$avatar_hash.'.jpg',$content);
						$acc['avatar'] = $dir.'/'.$avatar_hash.'.jpg';
					}
					if ($content = @file_get_contents($small_pic)) {
						@file_put_contents($s_folder.'/'.$avatar_hash.'.jpg',$content);
					}

					sql_insert($acc,tb().'accounts');
					$uid = insert_id();
					sql_query("insert into `".tb()."pages` (uid,uri,type) values($uid,'{$_POST['username']}','u')");
					$page_id = insert_id();

					stream_publish(t('Signed Up','','','',1),'','',$uid,$page_id);
				

					// welcome email
					$welcome_email = nl2br(get_text('welcome_email'));
					$welcome_email = str_replace('%username%',$_POST['username'],$welcome_email);
					$welcome_email = str_replace('%email%',$_SESSION['fb_email'],$welcome_email);
					$welcome_email = str_replace('%password%',$password,$welcome_email);
					$welcome_email = str_replace('%sitelink%',url(uhome(),h(get_gvar('site_name')) ),$welcome_email);

					@jcow_mail($_SESSION['fb_email'], 'Welcome to "'.h(get_gvar('site_name')).'"!', $welcome_email);
					$_SESSION['login_cd'] = 3;
					$_SESSION['uid'] = $uid;
					redirect(url('feed'));
				}
				elseif (is_array($errs)) {
					sys_notice($errs[0]);
				}
			}
			$email = $_POST['email'];
			if (!strlen($email))
				$email = $_SESSION['fb_email'];
			c('<form method="post" action="'.url('fblogin/signup').'">
			<p>
			'.label(t('Email address')).'<input type="text" name="email" value="'.h($email).'" />
			</p>
			<p>
			'.label(t('Username')).'<input type="text" name="username" value="'.h($username).'" />
			</p>
			<p>
			<input type="submit" class="fpost" value="'.t('Join').'" />
			</p>
			
			<input type="hidden" value="2" name="step" />
			</form>');
		}
	}

	function admin() {
		if ($_POST['step'] == 'post') {
			set_gvar('fb_id',$_POST['fb_id']);
			set_gvar('fb_secret',$_POST['fb_secret']);
			redirect('fblogin/admin',1);
		}
		if (!$mobile_ids = get_text('mobile_ids')) {
			$mobile_ids = 'iphone,nokia,BlackBerry,HTC,Motorola,Nokia,Samsung';
		}
		c('<form action="'.url('fblogin/admin').'" method="post">
		<p>'.label('Facebook app ID').'
		<input type="text" name="fb_id" size="50" value="'.get_gvar('fb_id').'" />
		</p>
		<p>'.label('Facebook app Secret').'
		<input type="text" name="fb_secret" size="50" value="'.get_gvar('fb_secret').'" />
		</p>
		<p><input type="submit" value="Save" />
		<input type="hidden" name="step" value="post" /></p>
		</form>');
	}
}
