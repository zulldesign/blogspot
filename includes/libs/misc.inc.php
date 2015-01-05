<?php

$captcha['publickey'] = $captcha['publickey']?$captcha['publickey']:'6Ld7BbwSAAAAANqYkhR9oAEsUCuYoWQgoYtNYeiH';
$captcha['privatekey'] = $captcha['privatekey']?$captcha['privatekey']:'6Ld7BbwSAAAAAAdMlleVlFTGqiJqKafjFZsYWDmB';

if (!$config['max_upload']) $config['max_upload'] = 100;

require_once './includes/libs/bbcode.php';
@require_once './my/license.php';
$from_url = getenv(HTTP_REFERER);

$conn=sql_connect($db_info['host'], $db_info['user'], $db_info['pass'], $db_info['dbname']);
mysql_query("SET NAMES UTF8");

$lang_options = array();
// gvars
$res = sql_query("select * from `".tb()."gvars`");
while ($row = sql_fetch_array($res)) {
	$gvars[$row['gkey']] = $row['gvalue'];
}
$langs_enabled = array();
if ($le = get_gvar('langs_enabled')) {
	$langs_enabled = explode(',',$le);
}
foreach ($langs_enabled as $key) {
	$lang_options[$key] = $langs[$key];
}
if (!count($lang_options)) $lang_options = array('en'=>'English');
// modules
$current_modules = array();
$res = sql_query("select * from ".tb()."modules");
while ($row = sql_fetch_array($res)) {
	$key = $row['name'];
	$current_modules[$key] = $row;
	if ($row['actived'] && $row['hooking'] && file_exists('modules/'.$row['name'].'/'.$row['name'].'.hook.php')) {
		include_once 'modules/'.$row['name'].'/'.$row['name'].'.hook.php';
	}
}

$_REQUEST['p'] = str_replace('-','',$_REQUEST['p']);
if (!strlen($_REQUEST['p'])) {
	$parr[0] = 'home';
	$parr[1] = 'index';
}
elseif (!preg_match("/^[0-9a-z_\/.\|]+$/i",$_REQUEST['p'])) {
	sys_break('Wrong path:'.htmlspecialchars($_REQUEST['p']));
}
else {
	$parr = explode('/',$_REQUEST['p']);
	if ($parr[1]) {
		$act =  $parr[1];
	}
	else {
		$act = 'index';
	}
}
foreach ($current_modules as $arr) {
	if ($arr['name'] == $parr[0]) {
		if (!$arr['actived']) die('this app is disabled');
	}
}
$application = $parr[0];
include './includes/libs/ss.inc.php';


if ($parr[0] != 'member' && $parr[0] != 'home' && $parr[0] != 'jcow' && $parr[0] != 'paidmember' && $parr[0] != 'upgrade' && $parr[0] != 'rss' && $parr[0] != 'language' && $parr[0] != 'signup' && get_gvar('private_network') && !$client['id'] && !preg_match("/google/i",$_SERVER['HTTP_USER_AGENT']) ) {
	$key = 'public_app_'.$parr[0];
	if (get_gvar($key)) {
	}
	else {
		redirect('member/login/1');
	}
}
// page
if (!$_GET['page']) {
	$page = 1;
}
else {
	$page = $_GET['page'];
}

// app cache
if (get_gvar('jcow_cache_enabled') ) {
	$hooks = check_hooks('page_cache');
	if ($hooks) {
		foreach ($hooks as $hook) {
			$hook_func = $hook.'_page_cache';
			if($page_cache = $hook_func($parr,$page,$client)) {
				$enable_page_cache = true;
				if ($page_content = get_cache($page_cache['key'])) {
					if (!$config('disable_execute_info')) {
						$execute_time = microtime_float() - $time_start;
						$execute_info = '<br /><span class="sub">Executed in '.substr($execute_time,0,7).' seconds</span>';
						echo str_replace('<!-- jcow_execute_info -->',$execute_info,$page_content);
					}
					else {
						echo str_replace('<!-- jcow_execute_info -->',$execute_info,$page_content);
					}
					exit();
				}
			}
		}
	}
}


// menu
if (!strlen($parr[1]))
	$current_menu_path = $parr[0];
else
	$current_menu_path = $parr[0].'/'.$parr[1];
$menu_items = array();
$res = sql_query("select * from ".tb()."menu where app!='' order by weight ASC");
if (!is_array($my_apps)) {
	$my_apps = array();
}
if (!is_array($all_apps)) {
	$all_apps = array();
}

if (!is_array($new_apps)) {
	$new_apps = array();
}
while ($row = sql_fetch_array($res)) {
	$path = $row['path'];
	$row['allowed_roles'] = explode(',',$row['allowed_roles']);
	$menu_items[$row['path']] = $row;
	if ($row['protected'] && !allow_access($row['allowed_roles'])) {
		continue;
	}
	if ($row['type'] == 'tab') {
		$all_tab_menu[] = $row;
	}
	if ($row['type'] == 'community' || $row['type'] == 'personal' || $row['type'] == 'app') {
		$all_apps[$path] = $row;
		if (!is_array($client['settings']['my_jcow_apps']) && $row['actived']) {
			$new_apps[$path] = $row;
		}
	}
	elseif ($row['type'] == 'admin' && $row['actived']) {
		$admin_menu[] = $row;
	}
}
if (allow_access(3)) {
	$admin_app = array(
		'name'=>'Admin CP',
		'path'=>'admin',
		'app'=>'admin',
		'actived'=>1,
		'type'=>'personal',
		'icon'=>'modules/admin/icon.png'
	);
	if (!is_array($client['settings']['my_jcow_apps'])) {
		$new_apps['admin'] = $admin_app;
	}
	else {
		$all_apps['admin'] = $admin_app;
	}
}
if (is_array($client['settings']['my_jcow_apps']) && is_array($client['settings']['hidden_jcow_apps'])) {
	foreach ($all_apps as $path=>$row) {
		if (!is_array($new_apps[$path]) 
			&& !in_array($path,$client['settings']['my_jcow_apps'])
			&& !in_array($path,$client['settings']['hidden_jcow_apps'])
			) {
			$new_apps[$path] = $row;
		}
	}
}

if (!$default_jcow_homeapp) {
	$default_jcow_homeapp = 'feed';
}
if ($client['id'] && $parr[0] == 'home') {
	if ($homeapp_key = $client['settings']['my_jcow_homeapp']) {
		if (is_array($all_apps[$homeapp_key])) {
			$myhome_app = $homeapp_key;
		}
	}
	if ($myhome_app && $myhome_app != 'home') {
		header("location:".url($myhome_app));
	}
	else {
		header("location:".url($default_jcow_homeapp));
	}
}


if (is_array($client['settings']['my_jcow_apps'])) {
	foreach ($client['settings']['my_jcow_apps'] as $app_key) {
		if (is_array($all_apps[$app_key])) {
			$my_apps[$app_key] = $all_apps[$app_key];
		}
	}
	foreach ($all_apps as $app_key=>$tmpapp) {
		if (!in_array($app_key,$client['settings']['my_jcow_apps']) && !in_array($app_key,$client['settings']['hidden_jcow_apps']) && $tmpapp['actived'] ) {
			$new_apps[$app_key] = $tmpapp;
		}
	}
}
if (strlen($menu_items[$current_menu_path]['parent'])) {
	$top_menu_path = $menu_items[$current_menu_path]['parent'];
}
else {
	$top_menu_path = $current_menu_path;
}
if (strlen($menu_items[$current_menu_path]['name'])) {
	set_title(t($menu_items[$current_menu_path]['name']));
}
if (strlen($menu_items[$top_menu_path]['name'])) {
	$top_title = t($menu_items[$top_menu_path]['name']);
}
if (is_array($all_tab_menu)) {
	foreach ($all_tab_menu as $arr) {
		if ($arr['parent'] == $top_menu_path) {
			$tab_menu[] = $arr;
		}
	}
}



$hide_ad_roles = explode('|',get_gvar('hide_ad_roles'));
if (is_array($hide_ad_roles)) {
	foreach ($hide_ad_roles as $role) {
		if (in_array($role, $client['roles'])) {
			$config['hide_ad'] = 1;
		}
	}
}



$nav[] = url('home','Home');