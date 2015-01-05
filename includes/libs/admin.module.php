<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class admin {
	function admin() {
		do_auth(3);
		clear_as();
		clear_report();
		set_title('Admin Panel');
		global $nav, $config, $menuon;
		$menuon = 'admin';
		$config['hide_ad'] = 1;
		$nav[] = url('admin',t('Admin Panel'));

	}
	function modules() {
		global  $nav;
		$nav[] = url('admin/modules',t('Modules'));
		include "modules/admin/inc/modules.php";
	}
	function menu($step,$id) {
		global $nav;
		$nav[] = url('admin/menu',t('Menu'));
		include "modules/admin/inc/menu.php";
	}
	function blocks($step) {
		global $nav;
		$nav[] = url('admin/blocks',t('Blocks'));
		include "modules/admin/inc/blocks.php";
	}
	function update($step) {
		global $nav;
		include "modules/admin/inc/update.php";
	}
	function footer_pages($step,$id) {
		global $nav;
		include "modules/admin/inc/footer_pages.php";
	}

	function blacklist($step,$id) {
		global $nav;
		$nav[] = url('admin/blacklist',t('Blacklist'));
		include "modules/admin/inc/blacklist.php";
	}
	function memberqueue($step,$id) {
		global $nav;
		$nav[] = url('admin/memberqueue',t('Member Queue'));
		include "includes/libs/admin_memberqueue.module.php";
	}
	function pending_posts($step,$id) {
		global $nav;
		$nav[] = url('admin/pending_posts','Posts Queue');
		include "includes/libs/admin_pending_posts.module.php";
	}
	function cache($step,$id) {
		global $nav;
		$nav[] = url('admin/cache',t('Cache controller'));
		include "includes/libs/admin_cache.module.php";
	}
	function themes($step) {
		global $menuon, $tab_menu, $nav, $config;
		set_title('Theme');
		$nav[] = url('admin/themes','Themes');
		include "modules/admin/inc/themes.php";
	}
	function members_quick($step) {
		global $menuon, $tab_menu, $nav, $config;
		set_title('Member quick management');
		$nav[] = url('admin/members_quick','Member quick management');
		include "modules/admin/inc/members_quick.php";
	}
	function stream_monitor($step) {
		global $menuon, $tab_menu, $nav, $config;
		set_title('Stream monitor');
		$nav[] = url('admin/stream_monitor','Stream monitor');
		include "modules/admin/inc/stream_monitor.php";
	}
	function permissions($step) {
		global $menuon, $tab_menu, $nav, $config, $menu_items;
		set_title('Permissions');
		$nav[] = url('admin/permissions','Permissions');
		include "modules/admin/inc/permissions.php";
	}
 
	function index() {
		global $version, $client, $optional_apps, $config, $current_modules, $admin_menu;
		nav('Index');

		$s = str_replace('http://','',uhome());
		$res = sql_query("select count(*) as num from ".tb()."accounts");
		$member = sql_fetch_array($res);

		if (!check_license()) {
			$upgradeurl = ' (<strong><a href="http://www.jcow.net/download/">Upgrade</a></strong>)';
		}


		section_content('
			<IFRAME SRC="http://www.jcow.net/news.php?s='.urlencode($s).'&e='.urlencode($client['email']).'fn='.urlencode($client['fullname']).'&m='.$member['num'].'&v='.jversion().'&l='.check_license().'" TITLE="Jcow News" WIDTH="580" HEIGHT="180" scrolling="no">
<a href="http://www.jcow.net">Visit Jcow for News&Updates</a><br />
</IFRAME>
		');
		section_close('Jcow Updates & Security notes');

		$res = sql_query("select count(*) as num from ".tb()."accounts " );
		$row = sql_fetch_array($res);
		$members = $row['num'];
		$res = sql_query("select count(*) as num from ".tb()."accounts where disabled=0" );
		$row = sql_fetch_array($res);
		$verifed_members = $row['num'];
		$res = sql_query("select count(*) as total from `".tb()."reports`");
		$row = sql_fetch_array($res);
		$reports_all = $row['total'];
		$res = sql_query("select count(*) as total from `".tb()."reports` where hasread=0");
		$row = sql_fetch_array($res);
		$reports_unread = $row['total'];

		$res = sql_query("select count(*) as num from ".tb()."pending_review where ignored=0" );
		$row = sql_fetch_array($res);
		$postqueue = $row['num'];
		$res = sql_query("select count(*) as num from ".tb()."accounts where disabled=1 and forum_posts>0" );
		$row = sql_fetch_array($res);
		$memberqueue = $row['num'];
		
		
		section_content('
		<ul>
		<li>You have <a href="'.url('admin/users').'">'.$members.' Members</a>. (<strong>'.$verifed_members.'</strong> verified)</li>
		<li>
		<a href="'.url('admin/reports').'">Member Reports (<strong>'.$reports_unread.'</strong>/'.$reports_all.')</a>
		</li>

		<li>Your Jcow Version: <strong>'.jversion().'</strong>. '.$upgradeurl.'</li>
		</ul>');
		section_content('<div>

		<div class="ai_items">'
		.url('admin/config','<img src="'.uhome().'/files/icons/admin/config.gif" />').
		'<br />'.url('admin/config',t('Site configuration')).'</div>
		
		<div class="ai_items">'
		.url('admin/modules','<img src="'.uhome().'/files/icons/admin/modules.gif" />').
		'<br />'.url('admin/modules',t('Modules')).'</div>
		
		<div class="ai_items">'
		.url('admin/menu','<img src="'.uhome().'/files/icons/admin/menu.gif" />').
		'<br />'.url('admin/menu',t('Menu')).'</div>
				
		<div class="ai_items">'
		.url('admin/themes','<img src="'.uhome().'/files/icons/admin/themes.gif" />').
		'<br />'.url('admin/themes',t('Themes')).'</div>
		
		<div class="ai_items">'
		.url('admin/customfields','<img src="'.uhome().'/files/icons/admin/user_fields.gif" />').
		'<br />'.url('admin/customfields','Member Fields').'</div>
		
		<div class="ai_items">'
		.url('admin/users','<img src="'.uhome().'/files/icons/admin/users.gif" />').
		'<br />'.url('admin/users',t('Members')).'</div>


		<div class="ai_items">'
		.url('admin/userroles','<img src="'.uhome().'/files/icons/admin/roles.gif" />').
		'<br />'.url('admin/userroles',t('User Roles')).'</div>
		
	

		<div class="ai_items">'
		.url('admin/texts','<img src="'.uhome().'/files/icons/admin/texts.gif" />').
		'<br />'.url('admin/texts',t('Texts')).'</div>
		
		'.$ads_link.'

		<div class="ai_items">'
		.url('admin/translate','<img src="'.uhome().'/files/icons/admin/translate.gif" />').
		'<br />'.url('admin/translate',t('Translate')).'</div>

		<div class="ai_items">'
		.url('admin/permissions','<img src="'.uhome().'/files/icons/admin/permissions.gif" />').
		'<br />'.url('admin/permissions',t('User Permissions')).'</div>


		<div class="ai_items">'
		.url('admin/members_quick','<img src="'.uhome().'/modules/admin/member_quick_management.gif" />').
		'<br />'.url('admin/members_quick','Members<br />Quick Manage').'</div>
		<div class="ai_items">'
		.url('admin/stream_monitor','<img src="'.uhome().'/modules/admin/stream_monitor.gif" />').
		'<br />'.url('admin/stream_monitor','Stream Monitor').'</div>

		<div class="ai_items">'
		.url('admin/cache','<img src="'.uhome().'/modules/admin/cache.gif" />').
		'<br />'.url('admin/cache','Cache Controller').'</div>

		<div class="ai_items">'
		.url('admin/ad_blocks','<img src="'.uhome().'/files/icons/admin/ad_blocks.gif" />').
		'<br />'.url('admin/ad_blocks','Ad Blocks').'</div>
		
		
		<style>
		.ai_items {
			width:100px;
			height: 100px;
			overflow: hidden;
			float: left;
			text-align: center;
	}
		.ai_items img {		
			border: #ccc 1px solid;
	}
		</style>');
		
		

		section_close('Management Tools');
		
		c('<ul class="simple_list">');
		c('
		<li>'.url('admin/footer_pages', 'Footer Pages').' - Edit pages like "about us"</li>');
		if (is_array($admin_menu)) {
			foreach ($admin_menu as $item) {
				$arr = explode('/',$item['path']);
				$img = '';
				$icon = 'modules/'.$arr[0].'/icon.png';
				if (file_exists($icon)) {
					$img = '<img src="'.uhome().'/'.$icon.'" />';
				}
				section_content('<li>'.$img.' '.url($item['path'],$item['name']).'</li>');
			}
		}
		c('
		</ul>');
		section_close('Addon Tools');
	}

	function jsql() {
		nav(url('admin/jsql','Execute SQL Query') );
		if ($_POST['step'] == 2) {
			if (!$_POST['query']) {
				sys_notice('Empty query');
			}
			else {
				$query = stripslashes($_POST['query']);
				$query = remove_remarks($query);
				$pieces = split_sql_file($query, ";");
				if (is_array($pieces)) {
					foreach ($pieces as $piece) {
						$sql = trim($piece);
						if(!empty($sql) and $sql[0] != "#") {
							sql_query($sql);
						}
					}
				}
				sys_notice('Query executed!');
			}
		}
		c('
		<p>Be <strong>Very</strong> careful when using this tool!<br />
		Do not execute queries from unknown source.
		</p>
		<form method="post" action="'.url('admin/jsql').'">
		<textarea name="query" rows="10" cols="50"></textarea><br />
		<input type="hidden" name="step" value="2" />
		<input type="submit" value="Execute!" />
		</form>');
	}

	function app_disable($key) {
		set_gvar('app_'.$key, 0);
		redirect('admin',1);
	}
	function app_enable($key) {
		set_gvar('app_'.$key, 1);
		redirect('admin',1);
	}
	
	function reports() {
		nav(url('admin/reports',t('Reports') ));
		global $current_sub_menu, $apps, $story_apps,$offset, $ubase, $num_per_page, $page, $content; 
		set_title('Reports');
		sql_query("update ".tb()."reports set hasread=1");
		$res = sql_query("SELECT r.*,u.username FROM ".tb()."reports as r left join `".tb()."accounts` as u on u.id=r.uid ORDER by r.id DESC LIMIT $offset,$num_per_page ");
		c('<table class="stories" cellspacing="1">');
		section_content('<tr class="table_line1">
			<td>Reports</td>
			</tr>');
			while ($row = sql_fetch_array($res)) {
			c('<tr class="row1">
			<td>'.get_date($row['created']).', url: '.url($row['url'],$row['url']).'<br /> reported by '.url('u/'.$row['username'],$row['username']).': <span class="sub">'.h($row['message']).'</span></td>
			</tr>');
		}
		c('</table>');

		// pager
		$res = sql_query("select count(*) as total from `".tb()."reports`");
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = $ubase.'admin/reports';
		$pagebar  = $pb->whole_num_bar();
		c($pagebar);
	}

	
	
	function config() {
		nav(url('admin/config',t('Site configuration')) );
		global $current_sub_menu, $config, $locations, $blocks, $client;
		$current_sub_menu['href'] = url('admin/config');
		section_content('<form action="'.url('admin/configpost').'" method="post">
		<fieldset>
		<legend>'.t('General settings').'</legend>
		<p>
		'.label(t('Site name')).'
		<input type="text" name="site_name" value="'.get_gvar('site_name').'" />
		</p>
		
		<p>
		'.label(t('Site Slogan')).'
		<input type="text" size="70" name="site_slogan" value="'.get_gvar('site_slogan').'" />
		</p>

		<p>
		'.label(t('Site Keywords')).'
		<input type="text" size="70" name="site_keywords" value="'.get_gvar('site_keywords').'" /><br />
		<span class="sub">will be inserted to meta tag</span>
		</p>
		');

		section_content('<p> '.label(t('Webmaster email')).'<input type="text" 
		name="site_email" value="'.get_gvar('site_email').'" />
		<span>Important message will be sent to this address</span></p>');

		section_content('<p> '.label('Footer Message').'
		<textarea name="footermsg">'.h(get_text('footermsg')).'</textarea><br />
		<span>Displayed in the footer of your pages.</span></p>


		
		</fieldset>');
		if (get_gvar('friend_admin')) {
			$friend_admin = 'checked';
		}
		if (get_gvar('reg_confirm')) {
			$reg_confirm = 'checked';
		}
		if (get_gvar('private_network')) {
			$story_access_member = 'selected';
		}
		else {
			$story_access_all = 'selected';
		}
		if (get_gvar('hide_right_sidebar')) {
			$hide_right_sidebar = 'checked';
		}
		
		section_content('
		<fieldset>
		<legend>Privacy&Security</legend>
		<p>'.label('Network visiting').'
		<select name="private_network">
		<option value="0" '.$story_access_all.' >Public - everyone can visite this site</option>
		<option value="1" '.$story_access_member.' >Private - only members can visite this site</option>
		</select>
		</p>
		');
		if (get_gvar('signup_closed')) {
			$signup_closed = 'selected';
		}
		else {
			$signup_closed_no = 'selected';
		}
		if (get_gvar('acc_verify') == 2) {
			$acc_verify_2 = 'selected';
		}
		elseif (get_gvar('acc_verify') == 1) {
			$acc_verify_1 = 'selected';
		}
		elseif (get_gvar('acc_verify') == 3) {
			$acc_verify_3 = 'selected';
		}
		else {
			$acc_verify_0 = 'selected';
		}
		if (!$pending_post_limit = get_gvar('pending_post_limit'))
			$pending_post_limit = 10;
		if (!$reg_limit_ip = get_gvar('reg_limit_ip'))
			$reg_limit_ip = 2;
		if (get_gvar('email_c')) {
			$email_c = 'checked';
		}
		section_content('
		<p>
		'.label('Signing Up').'
		<select name="signup_closed">
		<option value="0" '.$signup_closed_no.' >Open - Accepting new members</option>
		<option value="1" '.$signup_closed.' >Close - Not accepting new members</option>
		</select>
		</p>
		<p>
		'.label('Limit').'
		Limit <input type="text" size="2" name="reg_limit_ip" value="'.$reg_limit_ip.'" /> registrations per IP address.
		</p>
		<p>
		'.label('Account verification').'
		<select name="acc_verify">
		<option value="0" '.$acc_verify_0.' >Testing/Dev mod - automatically verified</option>
		<option value="1" '.$acc_verify_1.' >Production mod1 - verify by facebook account</option>
		<option value="2" '.$acc_verify_2.' >Production mod2 - verify by verified facebook account(recommended)</option>
		<option value="3" '.$acc_verify_3.' >Production mod3 - verify by Admin(you)</option>
		</select><br />
		<span>"<strong>Verified facebook account</strong>" means the user has been verified via mobile,SMS,or credit card on facebook. <br />
		To avoid SPAM, we strongly recommend you to turn on the "<strong>Production mod2</strong>" for live site.
		</span>
		</p>

		</fieldset>');


		section_content('<fieldset>
		<legend>Offline</legend>');
		if (get_gvar('offline')) {
			$offline = 'checked';
		}
		section_content('
		<p>
		'.label('Website Offline').'
		<input type="checkbox" value="1" name="offline" '.$offline.' /> '.t('Offline').'
		<span>
		'.t('Reason').':<br />
		<textarea name="offline_reason" style="width:500px" rows="3">'.get_gvar('offline_reason').'</textarea><br />
		'.t('Even if offline, you can still access admin area').'
		</span>
		</p>
		</fieldset>');

		section_content('
		<fieldset>
		<legend>Location Options</legend>
		<p>
		<textarea name="locations" style="width:280px;height:200px">'.get_text('locations').'</textarea>
		</p>
		</fieldset>');
		$miniblog_maximum = get_gvar('miniblog_maximum');
		if (!$miniblog_maximum) {
			$miniblog_maximum = 140;
		}
		if (!get_gvar('disable_recaptcha_reg')) {
			$enable_recaptcha_reg_checked = 'checked';
		}
		if (!get_gvar('disable_recaptcha_login')) {
			$enable_recaptcha_login_checked = 'checked';
		}
		if (!get_gvar('disable_recaptcha_pm')) {
			$enable_recaptcha_pm_checked = 'checked';
		}
		if (!get_gvar('disable_recaptcha_req')) {
			$enable_recaptcha_req_checked = 'checked';
		}
		section_content('
		<fieldset>
		<legend>Others</legend>
		<p>
		Maximum length of mini-blog: <input type="text" size="5" name="miniblog_maximum" value="'.$miniblog_maximum.'" /> characters
		</p>
		<p>
		<input type="checkbox" value="1" name="enable_recaptcha_reg" '.$enable_recaptcha_reg_checked.' />Enable reCaptcha in Signup form.
		</p>
		<p>
		<input type="checkbox" value="1" name="enable_recaptcha_login" '.$enable_recaptcha_login_checked.'  />Enable reCaptcha in Login form.
		</p>

		<p>
		<input type="checkbox" value="1" name="disable_recaptcha_pm" '.$enable_recaptcha_pm_checked.' />Enable reCaptcha when a stranger is sending PM.
		</p>
		<p>
		<input type="checkbox" value="1" name="disable_recaptcha_req" '.$enable_recaptcha_req_checked.'  />Enable reCaptcha when a stranger is sending Friend Request.
		</p>
		</fieldset>');
		
		
		
		section_content('<p>
		<input type="submit" class="button" value="'.t('Save changes').'" />
		</p>
		</form>
		');
	}
	function setlicensepost() {
		set_gvar('license_key', $_POST['license_key']);
		redirect(url('admin'));
	}
	function configpost() {
		$config = array( 
					'site_name' => $_POST['site_name'],
					'email' => $_POST['email'],
					'offline' => $_POST['offline'],
					'offline_reason' => $_POST['offline_reason']
					);
		set_gvar('private_network', $_POST['private_network']);
		set_gvar('botauth', $_POST['botauth']);
		set_gvar('site_name', $_POST['site_name']);
		set_gvar('site_slogan', $_POST['site_slogan']);
		set_gvar('site_keywords', $_POST['site_keywords']);
		set_gvar('site_email', $_POST['site_email']);
		set_gvar('chat', $_POST['chat']);
		set_gvar('acc_verify', $_POST['acc_verify']);
		$s = serialize($config);
		set_gvar('friend_admin',$_POST['friend_admin']);
		set_gvar('reg_confirm',$_POST['reg_confirm']);
		set_gvar('offline',$_POST['offline']);
		set_gvar('reg_limit_ip',$_POST['reg_limit_ip']);
		set_gvar('offline_reason',$_POST['offline_reason']);
		set_gvar('only_invited',0);
		set_gvar('signup_closed',$_POST['signup_closed']);
		set_text('locations',$_POST['locations']);
		set_text('footermsg',$_POST['footermsg']);
		set_gvar('pending_post_limit',$_POST['pending_post_limit']);

		set_gvar('miniblog_maximum',$_POST['miniblog_maximum']);
		$disable_recaptcha_reg = $_POST['enable_recaptcha_reg'] ? 0:1;
		$disable_recaptcha_login = $_POST['enable_recaptcha_login'] ? 0:1;
		$disable_recaptcha_pm = $_POST['disable_recaptcha_pm'] ? 0:1;
		$disable_recaptcha_req = $_POST['disable_recaptcha_req'] ? 0:1;
		set_gvar('disable_recaptcha_reg',$disable_recaptcha_reg);
		set_gvar('disable_recaptcha_login',$disable_recaptcha_login);
		set_gvar('disable_recaptcha_pm',$disable_recaptcha_pm);
		set_gvar('disable_recaptcha_req',$disable_recaptcha_req);
		redirect(url('admin/config'),1);
	}
	
	function users($filter = '') {
		nav(url('admin/users','Members'));
		global $current_sub_menu,$num_per_page,$offset,$page;
		section_content('
		<script>
		function sfilter() {
			var uname = document.getElementById("fusername").value;
			window.location = "'.url('admin/users').'/"+uname;
			}
		</script>
		');
		c(t('Username').': 
		<input type="text" name="lastname" id="fusername" /> <input type="button" value="'.t('Search').'" onclick="javascript:sfilter();" />');
		
		section_content('<ul>');
		if (strlen($filter) && !preg_match("/^page/i",$filter)) {
			$pageb = "/$filter";
			$filter = " and username like '%$filter%' ";
		}
		else {
			$filter = '';
		}
		$res = sql_query("select * from `".tb()."accounts` "." where 1 $filter order by id DESC limit $offset,$num_per_page");
		while ($member = sql_fetch_array($res)) {
			if ($member['disabled'] == 1)
				$status = '(un-verified)';
			if ($member['disabled'] == 2)
				$status = '(suspended)';
			if ($member['disabled'] == 3)
				$status = '(spammer)';
			section_content('<li><span>'.url('admin/useredit/'.$member['id'],$member['username'].' '.$member['lastname']). '<span class="sub">'.$status.'</span></li>');
		}
		section_content('</ul>');
		
		$res = sql_query("select count(*) as total from `".tb()."accounts` "." where 1 $filter ");
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = url('admin/users'.$pageb);
		$pagebar  = $pb->whole_num_bar();
		c($pagebar);
		$current_sub_menu['href'] = url('admin/users');
	}
	
	function useredit($uid = 0) {
		global $nav;
		$nav[] = url('admin/users',t('Users'));
		$res = sql_query("select * from `".tb()."accounts` where id='$uid' ");
		$user = sql_fetch_array($res);
		$user['roles'] = explode('|',$user['roles']);
		if (!$user['id']) {
			die('wrong uid');
		}
		if ($user['featured']) $featured = 'checked';
		section_content('
		<p>
		'.t('Username').': 
		<strong>'.$user['username'].'</strong>
		 ('.url('u/'.$user['username'],t('View profile')).')<br />
		Email: '.$user['email'].'<br />
		Location: '.h($user['location']).'
		</p>
		<p>
		<label>User IP</label>
		<strong>'.$user['ipaddress'].'</strong> (<a href="http://www.google.ca/search?q='.$user['ipaddress'].'" target="_blank">Whois</a>)
		</p>
		<fieldset>
		<form action="'.url('admin/usereditpost').'" method="post">
		<p>
		'.label('User Roles'));
		$res = sql_query("select * from ".tb()."roles where (id=3 or id>9) order by id");
		while ($role = sql_fetch_array($res)) {
			$checked = '';
			if (in_array($role['id'],$user['roles']))	$checked = ' checked ';
			section_content('<input type="checkbox" name="set_roles[]" value="'.$role['id'].'" '.$checked.' />'.h($role['name']).' ');
		}
		section_content('
		</p>
		
					<p>
					'.label(t('Status')).'
					<input type="radio" name="disabled" value=0 '.admin_check_status($user,0).' /> Verified 
					<input type="radio" name="disabled" value=1 '.admin_check_status($user,1).' /> Un-verified 
					<input type="radio" name="disabled" value=2 '.admin_check_status($user,2).' /> Suspended 
					<input type="radio" name="disabled" value=3 '.admin_check_status($user,3).' /> Spammer<br />
					<span>
					<strong>Un-verified</strong> - can not post.<br />
					<strong>Suspended</strong> - can not login.<br />
					<strong>Spammer</strong> - can not post and old posts will be hidden.</span>
					</p>
					<p>
					'.label(t('Featured')).'
					<input type="checkbox" name="set_featured" value=1 '.
					$featured.' /> Featured<br />
					<span class="sub">Featured members have more chance to be displayed.</span>
					</p>
		
		<p>
		<input type="hidden" name="uid" value="'.$user['id'].'" />
		<input type="submit" value="'.t('Save changes').'" class="button" />
		</p>
		</form>
		</fieldset>');
	}
	
	function usereditpost() {
		if ($_POST['delete']) {
			redirect('admin/users',1);
		}
		else {
			if (is_array($_POST['set_roles']))
			$roles = implode('|',$_POST['set_roles']);
			$featured = $_POST['set_featured'];
			$res = sql_query("select disabled,email from ".tb()."accounts where id='{$_POST['uid']}'");
			$user = sql_fetch_array($res);
			sql_query("update `".tb()."accounts` set disabled='{$_POST['disabled']}',roles='{$roles}',featured='{$featured}' $newpass where id={$_POST['uid']} ");
			if (!$_POST['disabled'] && $user['disabled'] == 1) {
				@jcow_mail($user['email'], 'Your account on '.get_gvar('site_name').' approved!', 'Congratulations! Your account on '.get_gvar('site_name').' has been approved! You can start posting now');
			}

			redirect('admin/useredit/'.$_POST['uid'],1);
		}
	}

	function userdelete() {
		$res = sql_query("select * from `".tb()."accounts` where id='{$_POST['uid']}' ");
		$user = sql_fetch_array($res);
		if (!$user['id']) {
			sys_back('wrong uid');
		}
		$uid = $user['id'];
		/*delete forum posts*/
		sql_query("delete from ".tb()."accounts where id='$uid'");
		sql_query("delete from ".tb()."forum_threads where userid='$uid'");
		sql_query("delete from ".tb()."forum_posts where uid='$uid'");
		/*delete comments*/
		sql_query("delete from ".tb()."comments where uid='$uid'");
		/*delete follower*/
		sql_query("delete from ".tb()."followers where uid='$uid' or fid='$uid'");
		/*others*/
		sql_query("delete from ".tb()."friends where uid='$uid' or fid='$uid'");
		sql_query("delete from ".tb()."groups where creatorid='$uid'");
		sql_query("delete from ".tb()."group_members where uid='$uid'");
		sql_query("delete from ".tb()."group_members_pending where uid='$uid'");
		sql_query("delete from ".tb()."group_posts where uid='$uid'");
		sql_query("delete from ".tb()."group_topics where uid='$uid'");
		sql_query("delete from ".tb()."messages where from_id='$uid' or to_id='$uid'");
		sql_query("delete from ".tb()."profiles where id='$uid'");
		sql_query("delete from ".tb()."profile_comments where uid='$uid'");
		$res = sql_query("select id from ".tb()."stories where uid='$uid'");
		while ($story = sql_fetch_array($res)) {
			$res2 = sql_query("select uri from ".tb()."story_photos where sid='{$story['id']}'");
			while($photo = sql_fetch_array($res2)) {
				@unlink($photo['uri']);
			}
		}
		sql_query("delete from ".tb()."stories where uid='$uid'");
		sql_query("delete from ".tb()."streams where uid='$uid'");
		sql_query("delete from ".tb()."liked where uid='$uid'");
		redirect('admin/users',1);
		
	}
	
	function userroles() {
		global $nav;
		$nav[] = url('admin/userroles','User roles');
		$res = sql_query("select * from ".tb()."roles "." order by id");
		section_content('<p>"User Roles" is mainly used for grouping your members so that you can '.url('adminpermissions','give different permissions to different members').'.</p>');
		section_content('<ul>');
		while ($role = sql_fetch_array($res)) {
			section_content('<li>'.h($role['name']));
			if ($role['id'] > 9) section_content(' '.url('admin/userroleedit/'.$role['id'],'Edit').' | '.url('admin/userroledelete/'.$role['id'],'Delete'));
			section_content('</li>');
		}
		section_content('</ul>');
		section_close('Current Roles');
		section_content('
		<form method="post" action="'.url('admin/userroleadd').'">
		<p><label>Role Name:</label><input type="text" name="name" />
		<input type="submit" value="Add" />
		</p>
		</form>');
		section_close('Add a Role');
	}
	
	function userroledelete($rid) {
		if ($rid < 10) die('you must not delete roles that ID under 10');
		sql_query("delete from ".tb()."roles where id='$rid' ");
		redirect('admin/userroles',1);
	}
	
	function userroleedit($rid) {
		$res = sql_query("select * from ".tb()."roles where id='$rid' ");
		$role = sql_fetch_array($res);
		section_content('
		<form method="post" action="'.url('admin/userroleeditpost').'">
		<p><label>Role Name:</label><input type="text" name="name" value="'.h($role['name']).'" />
		<input type="hidden" name="rid" value="'.$role['id'].'" />
		<input type="submit" value="Add" />
		</p>
		</form>');
		section_close('Edit Role');
	}
	
	function userroleeditpost() {
		sql_query("update ".tb()."roles set name='{$_POST['name']}' where id='{$_POST['rid']}' ");
		redirect('admin/userroles',1);
	}
	
	function userroleadd() {
		if (!$_POST['name']) sys_back('Please input a valid role name');
		$res = sql_query("select max(id) as maxid from ".tb()."roles " );
		$row = sql_fetch_array($res);
		if ($row['maxid'] < 11) $id = 11;
		else $id = $row['maxid'] + 1;
		sql_query("insert into ".tb()."roles(id,name) values($id,'{$_POST['name']}')");
		redirect('admin/userroles',1);
	}
	
	function apps() {
		nav(t('Applications'));
		global $current_sub_menu, $all_apps;
		$current_sub_menu['href'] = url('admin/apps');
		$res = sql_query("select * from ".tb()."apps "." order by weight");
		section_content('<table class="stories" cellspacing="1">');
		section_content('<form action="'.url('admin/appspost').'" method="post" >');
		section_content('<tr class="table_line1"><td width="10">Actived</td><td>Application</td><td>Display</td><td>Weight</td></tr>');
		while($app = sql_fetch_array($res)) {
			$checked = $app['status'] ? 'checked':'';
			$readonly = $app['status'] ? '':'readonly';
			$app['dname'] = $app['name'];
			if ($app['dname'] == 'members') $app['dname'] = 'member listings';
			section_content('<tr class="row1"><td><input type="checkbox" name="'.$app['name'].'active" '.$checked.' value="1" /></td>
			<td>'.$app['dname'].'</td>
			<td><input type="text" name="'.$app['name'].'flag" value="'.h($app['flag']).'" '.$readonly.' /></td>
			<td><input type="text" name="'.$app['name'].'weight" size="2" value="'.$app['weight'].'" /></td>
			</tr>');
		}
		section_content('<tr class="row2"><td colspan="4"><input type="submit" value="'.t('Save changes').'" /></td></tr>
		</form>
		</table>');
	}
	
	function appspost() {
		$res = sql_query("select * from ".tb()."apps ");
		while($app = sql_fetch_array($res)) {
			$active = $app['name'].'active';
			$active = $_POST[$active];
			$flag = $app['name'].'flag';
			$flag = $_POST[$flag];
			$weight = $app['name'].'weight';
			$weight = $_POST[$weight];
			sql_query("update ".tb()."apps set status='$active',flag='$flag',weight='$weight' where id='{$app['id']}'");
		}
		redirect('admin/apps',1);
	}
	
	function plugins() {
		nav(t('Plugins'));
		global $current_sub_menu, $apps;
		$path='plugins/';
		if ($handle = opendir($path)) {
		    c("Directory handle: $handle\n");
		    c("Files:\n");
		    while (false !== ($file = readdir($handle))) {
		        $tmppath=rawurlencode($file);
		        c("<a href=$path"."$tmppath>$file</a>\n");
		    		c("<br>");
		    }
		    while ($file = readdir($handle)) {
		        c("$file\n");
		    }
		    closedir($handle);
		}
	}
	
	function pluginspost() {
		redirect('admin/apps',1);
	}
	
	function texts() {
		nav(t('System Texts'));
		global $current_sub_menu, $config, $locations,  $blocks;
		$current_sub_menu['href'] = url('admin/texts');
		$res = sql_query("select * from `".tb()."texts`");
		while ($row = sql_fetch_array($res)) {
			$texts[$row['tkey']] = htmlspecialchars($row['tvalue']);
		}
		section_content('
			<form action="'.url('admin/textspost').'" method="post">
			<p>
			'.label(t('Rules & Conditions')).'
			<textarea name="rules_conditions" style="width:500px" rows="7">'.$texts['rules_conditions'].'</textarea><br />
			<span>Used on user registration</span>
			</p>			
			
			<p>
			'.label('Welcome PM').'
			<textarea name="welcome_pm"  style="width:500px" rows="7">'.$texts['welcome_pm'].'</textarea><br />
			<span>Special word: %username%</span>
			</p>
			
			<p>
			'.label('Welcome Email').'
			<textarea name="welcome_email"  style="width:500px" rows="7">'.$texts['welcome_email'].'</textarea><br />
			<span>Special words: %username%, %email%, %sitelink%</span>
			</p>

			<p>
			'.label('Words filter').'
			<textarea name="words_filter"  style="width:500px" rows="7">'.$texts['words_filter'].'</textarea><br />
			<span>Words in this box will be replaced by **. separated by commas.</span>
			</p>
			
			<p>
			<input type="submit" class="button" value="'.t('Save changes').'" />
			</p>
			</form>
				');
	}

	function textspost() {
		foreach ($_POST as $key=>$val) {
			set_text($key,$val);
		}
		redirect('admin/texts',1);
	}

	function ad_blocks() {
		nav(url('admin/ad_blocks','Ad blocks'));
		global $current_sub_menu, $config, $locations;
		$current_sub_menu['href'] = url('admin/ad_blocks');

		section_content('<form action="'.url('admin/ad_blockspost').'" method="post">');

		section_content('<fieldset><legend>Ad Options</legend>
		<p><label>Hide Ad to</label>');
		$hide_ad_roles = explode('|',get_gvar('hide_ad_roles'));
		$res = sql_query("select * from ".tb()."roles "." where id>1 order by id");
		while ($role = sql_fetch_array($res)) {
			$checked = '';
			if (in_array($role['id'],$hide_ad_roles))	$checked = ' checked ';
			section_content('<input type="checkbox" name="hide_ad_roles[]" value="'.$role['id'].'" '.$checked.' />'.h($role['name']).' ');
		}
		section_content('</p></fieldset>');
		
		section_content('<fieldset>
		<legend>Ad Content</legend>
		<p>
		<ul>
		<li>Maximum width: <strong>200px</strong></li>
		<li>You can insert HTML tags</li>
		<li>To improve User Experience, Ads is automatically hidden on some pages like "Sign Up", "Login", "Edit Profile", "Admin Panel", ...</li>
		</ul>');
		if (!check_license()) {
			c('
		<textarea name="appad_sidebar2" style="width:500px;height:600px" disabled>'.h(get_gvar('appad_sidebar')).'</textarea><br />
		Available parameters:<br />
		<strong>jcow_user_id</strong>: Returns user id for online member. Returns "0" for guests.<br />
		<strong>jcow_user_username</strong>: Returns username for online member. Returns blank for guests.<br />
		<strong>jcow_user_fullname</strong>: Returns fullname for online member. Returns blank for guests.<br />
		<input type="hidden" name="appad_sidebar" value="" />
		');
		}
		else {
			c('
		<textarea name="appad_sidebar" style="width:500px;height:600px">'.h(get_gvar('appad_sidebar')).'</textarea><br />
		Available parameters:<br />
		<strong>jcow_user_id</strong>: Returns user id for online member. Returns "0" for guests.<br />
		<strong>jcow_user_username</strong>: Returns username for online member. Returns blank for guests.<br />
		<strong>jcow_user_fullname</strong>: Returns fullname for online member. Returns blank for guests.<br />');
		}
			
			c('</p>
			');
		if (!check_license()) {
			section_content('<input value="'.t('Save changes').'" disabled />
			<div style="background:#FFFF99">This function is not available in Free version</div>');
		}
		else {
			section_content('<input type="submit" class="button" value="'.t('Save changes').'" />');
		}
		section_content('</p>
		</form>
		');
	}
	function ad_blockspost() {
		if (is_array($_POST['hide_ad_roles'])) {
			set_gvar('hide_ad_roles', implode('|',$_POST['hide_ad_roles']));
		}
		else {
			set_gvar('hide_ad_roles', '');
		}
		set_gvar('appad_sidebar', $_POST['appad_sidebar']);
		redirect(url('admin/ad_blocks'),1);
	}


	function customfields() {
		nav(url('admin/customfields','Custom fields'));
		global $current_sub_menu, $apps;
		$current_sub_menu['href'] = url('admin/customfields');
		section_content('
		<p>This setting is optional. You can ask your members some questions when they signing up or changing profile. Their answers will be displayed on their profile.</p>
		<table border="1" width="100%">
		<tr>
			<td><input type="text" size="20"  value="Smoke or not" /></td>
			<td>
			<select>
			<option value="text" >Text</option>
			<option value="select_box" selected >Select Box</option>
			<option value="disabled">Disabled</option>
			</select>
			</td>
			<td>
			<textarea  rows="2" style="width:360px">Yes
No</textarea>
			</td>
		</tr>
		</table>');
		section_close('Example');
		section_content('
		<form action="'.url('admin/customfields_post').'" method="post">
		<table border="1" width="100%" cellpadding="5">
		<tr>
		<td>Question</td>
		<td>Default Value(s)<br />
		Only required when the Field Type is <strong>Select Box</strong>.Separate options with "Enter"</td>
		</tr>');
		for($i=1;$i<=7;$i++) {
			$key = 'cf_var'.$i;
			$key2 = 'cf_var_value'.$i;
			$key3 = 'cf_var_des'.$i;
			$key4 = 'cf_var_label'.$i;
			$key5 = 'cf_var_required'.$i;
			$type = get_gvar($key);
			$value = get_gvar($key2);
			$des = get_gvar($key3);
			$label = get_gvar($key4);
			$required_field = get_gvar($key5);
			$text_selected = $select_box_selected = $disabled_selected = '';
			if ($type == 'text') {
				$text_selected = 'selected';
			}
			elseif ($type == 'select_box') {
				$select_box_selected = 'selected';
			}
			elseif ($type == 'textarea') {
				$textarea_selected = 'selected';
			}
			else {
				$disabled_selected = 'selected';
			}
			if ($required_field) {
				$required_checked = 'checked';
			}
			else {
				$required_checked = '';
			}
			section_content('<tr>
			<td>
			Field Name:<input type="text" size="20" name="'.$key4.'" value="'.$label.'" /><br />
			Field type:
			<select name="'.$key.'">
			<option value="text" '.$text_selected.'>Single-Line Text</option>
			<option value="textarea" '.$textarea_selected.'>Multi-Line Text</option>
			<option value="select_box"  '.$select_box_selected.'>Select Box</option>
			<option value="disabled"  '.$disabled_selected.'>Disabled</option>
			</select><br />
			<input type="checkbox" name="'.$key5.'" value=1 '.$required_checked.' />Member must fill in this field
			</td>
			<td>Options for Select Box:<br />
			<textarea name="'.$key2.'" rows="3" style="width:360px">'.$value.'</textarea><br />
			Description:<br />
			<input type="text" name="'.$key3.'" value="'.$des.'" style="width:360px" />
			</td>
			</tr>');
		}

		section_content('
		</table>
		<p><input type="submit" value="'.t('Save changes').'" /></p>
		</fieldset>
		');
		section_close('Setup');
	}
	
	function customfields_post() {
		for($i=1;$i<=7;$i++) {
			$key = 'cf_var'.$i;
			$key2 = 'cf_var_value'.$i;
			if ($_POST[$key] == 'select_box') {
				if (!strlen($_POST[$key2])) {
					sys_back(t('You must set options for select_box'));
				}
			}
		}
		
		for($i=1;$i<=7;$i++) {
			$key = 'cf_var'.$i;
			$key2 = 'cf_var_value'.$i;
			$key3 = 'cf_var_des'.$i;
			$key4 = 'cf_var_label'.$i;
			$key5 = 'cf_var_required'.$i;
			if ($_POST[$key] != 'disabled') {
				$newarr = array();
				set_gvar($key,$_POST[$key]);
				$tarr = explode(',',$_POST[$key2]);
				foreach ($tarr as $val) {
						$newarr[] = trim($val);
				}
				$newkey2 = implode(',',$newarr);
				set_gvar($key2,$newkey2);
				set_gvar($key3,$_POST[$key3]);
				set_gvar($key4,$_POST[$key4]);
				set_gvar($key5,$_POST[$key5]);
			}
			else {
				set_gvar($key,'disabled');
				set_gvar($key2);
				set_gvar($key3);
				set_gvar($key4);
				set_gvar($key5);
			}
		}
		redirect('admin/customfields',1);
	}
	function translatedel($delkey) {
		global $langs_enabled;
		$newlangs = array();
		foreach ($langs_enabled as $val) {
			if ($val != $delkey) {
				$newlangs[] = $val;
			}
		}
		$newlang = implode(',',$newlangs);
		set_gvar('langs_enabled',$newlang);
		redirect('admin/translate',1);
	}

	function translateinout() {
		global $langs;
		if ($_POST['step'] == 'export') {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.$_POST['lang'].'.txt');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			$res = sql_query("select * from ".tb()."langs where lang='{$_POST['lang']}'");
			while ($row = sql_fetch_array($res)) {
				echo $row['lang'].' :transfrom: '.$row['lang_from'].' :transto: '.$row['lang_to']."\r\n";
			}
			exit;
		}
		if ($_POST['step'] == 'import') {
			if ($src = $_FILES['lang_src']['tmp_name']) {
				$data = fread(fopen($src, 'r'), filesize($src));
				if (!preg_match("/transto/i",$data)) {
					sys_notice('Please select a correct language file');
				}
				else {
					$lines = explode("\r\n",$data);
					foreach ($lines as $line) {
						if (preg_match("/transfrom/i",$line)) {
							$trans = explode(':transto:',$line);
							$lang_to = addslashes(trim($trans[1]));
							$lang_s = explode(':transfrom:',$trans[0]);
							$lang_code = trim($lang_s[0]);
							$lang_from = addslashes(trim(  $lang_s[1]         ));
							if(strlen($lang_code) == 2) {
								$res = sql_query("select * from ".tb()."langs where lang_from='{$lang_from}' and lang='{$lang_code}'");
								if (sql_counts($res)) {
									sql_query("update ".tb()."langs set lang_to='{$lang_to}' where lang_from='{$lang_from}' and lang='{$lang_code}'");
								}
								else {
									sql_query("insert into ".tb()."langs (lang,lang_from,lang_to) values('{$lang_code}','$lang_from','$lang_to')");
								}
							}
						}
					}
					redirect('admin/translate',1);
				}
			}
			else {
				sys_notice('Please select a language file');
			}
		}
	}

	function translate() {
		nav(url('admin/translate','Translate'));
		global $current_sub_menu, $client, $langs_enabled, $page, $lang_options, $langs;
		if (is_array($langs_enabled) && count($langs_enabled)) {
			c('<p>Language Options:<ul>');
			foreach ($langs_enabled as $val) {
				c('<li>'.$langs[$val].' <span class="sub">| '.url('admin/translatedel/'.$val,'Delete').'</li>');
			}
			c('</ul></p>');
		}
		c('<p><form method="post" action="'.url('admin/translateadd').'">Add a language: <select name="new_lang">');
		foreach ($langs as $key=>$lang) {
			c('<option value="'.$key.'">'.$lang.'</option>');
		}
	c('</select>
	<input type="submit" value="Add" />
	</form>
	</p>');
		$offset = ($page-1)*50;
		$current_sub_menu['href'] = url('admin/translate');
		$res = sql_query("select * from `".tb()."langs` where lang='{$client['lang']}' "." order by lang_from limit $offset, 50");
		$key = $client['lang'];
	if (count($langs_enabled) > 0) {
		section_content('<hr />
		<table width="90%" border="1">
		<tr><td width="50%">Export Language<br />
		<form method="post" action="'.url('admin/translateinout').'">
		<select name="lang">');
		foreach ($langs_enabled as $val) {
			c('<option value="'.$val.'">'.$langs[$val].'</option>');
		}
		c('</select>
		<input type="hidden" name="step" value="export" />
		<input type="submit" value="Export" />
		</form>
		</td>
		<td>Import Language<br />
		<form method="post" action="'.url('admin/translateinout').'" enctype="multipart/form-data">
		<input type="file" name="lang_src" />
		<input type="hidden" name="step" value="import" />
		<input type="submit" value="Import" />
		</form>
		</td>
		</tr>
		</table>
		<p>You are editing <strong>'.$lang_options[$key].'</strong>');
		if (count($lang_options) > 1) {
				foreach ($lang_options as $key=>$lang) { 
					if ($client['lang'] == $key) { 
						$lang_pres[] = $lang; 
					} 
					else { 
						$lang_pres[] = url('language/post/'.$key,$lang); 
					}
				} 
				$lang_select = implode(' | ', $lang_pres);
				c(' (Switch to '.$lang_select.')<br />');
		}
		section_content('</p>');

		section_content('
		<script>
				$(document).ready( function(){
						$(".tsubmit").click(function() {
							if ($(this).prev()[0].value != "") {
								var cbox = $(this).next();
								var mbox = $(this).prev();
								var tbox = $(this).prev().prev();
								cbox.html("<img src=\"'.uhome().'/files/loading.gif\" /> Submitting");
								$.post("'.uhome().'/index.php?p=jquery/translate",
								{tto:mbox[0].value,tfrom:tbox[0].value},
								  function(data){
									cbox.html("saved");
									},"html"
								);
								return false;
							}
						});
				});
				</script>');

		while ($row = sql_fetch_array($res)) {
			if(!strlen($row['lang_to'])) {
				$row['lang_to'] = $row['lang_from'];
			}

			section_content('<form class="translate_form">
			<span class="sub">('.htmlspecialchars($row['lang_from']).')</span><br />
			<input type="hidden" name="lang_from" value="'.htmlspecialchars($row['lang_from']).'" />
			<textarea name="lang_to">'.htmlspecialchars($row['lang_to']).'</textarea>
			<input class="tsubmit" type="submit" value="'.t('Save').'" /> <span class="tstatus"></span>
			</form><div class="hr"></div>');
		}

		$res = sql_query("select count(*) as total from `".tb()."langs` where lang='{$client['lang']}' ");
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, 50, $page);
		$pb->paras = url('admin/translate');
		$pagebar  = $pb->whole_num_bar();
		c($pagebar);
	}

	}

	function translateadd() {
		global $langs,$langs_enabled;
		$key = $_POST['new_lang'];
		if (!in_array($key,$langs_enabled)) {
			$langs_enabled[] = $key;
			$le = implode(',',$langs_enabled);
			set_gvar('langs_enabled',$le);
		}
		redirect('admin/translateadded',1);
	}

	function translateadded() {
		c('Language added. <strong>'.url('admin/translate','Back to Translate'));
	}
	

	
	


	
}


function admin_check_status($user, $val) {
	if ($user['disabled'] == $val) {
		return 'checked';
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



function remove_remarks($sql)
{
	$lines = explode("\n", $sql);
	
	// try to keep mem. use down
	$sql = "";
	
	$linecount = count($lines);
	$output = "";

	for ($i = 0; $i < $linecount; $i++)
	{
		if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0))
		{
			if ($lines[$i][0] != "#")
			{
				$output .= $lines[$i] . "\n";
			}
			else
			{
				$output .= "\n";
			}
			// Trading a bit of speed for lower mem. use here.
			$lines[$i] = "";
		}
	}
	
	return $output;
	
}

//

//
function split_sql_file($sql, $delimiter)
{
	$tokens = explode($delimiter, $sql);

	$sql = "";
	$output = array();
	
	$matches = array();
	
	$token_count = count($tokens);
	for ($i = 0; $i < $token_count; $i++)
	{
		if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0)))
		{
			$total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
			// Counts single quotes that are preceded by an odd number of backslashes, 
			// which means they're escaped quotes.
			$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
			
			$unescaped_quotes = $total_quotes - $escaped_quotes;
			
			if (($unescaped_quotes % 2) == 0)
			{
				// It's a complete sql statement.
				$output[] = $tokens[$i];
				// save memory.
				$tokens[$i] = "";
			}
			else
			{
				// incomplete sql statement. keep adding tokens until we have a complete one.
				// $temp will hold what we have so far.
				$temp = $tokens[$i] . $delimiter;
				// save memory..
				$tokens[$i] = "";
				
				// Do we have a complete statement yet? 
				$complete_stmt = false;
				
				for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++)
				{
					// This is the total number of single quotes in the token.
					$total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
					// Counts single quotes that are preceded by an odd number of backslashes, 
					// which means they're escaped quotes.
					$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
			
					$unescaped_quotes = $total_quotes - $escaped_quotes;
					
					if (($unescaped_quotes % 2) == 1)
					{
						$output[] = $temp . $tokens[$j];

						$tokens[$j] = "";
						$temp = "";
						
						$complete_stmt = true;
						$i = $j;
					}
					else
					{
						$temp .= $tokens[$j] . $delimiter;
						$tokens[$j] = "";
					}
					
				}
			} 
		}
	}

	return $output;
}