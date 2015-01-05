<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */
if ($parr[3]) {
	$real_path = $parr[0].'/'.$parr[1].'/'.$parr[2].'/'.$parr[3];
}
elseif ($parr[2]) {
	$real_path = $parr[0].'/'.$parr[1].'/'.$parr[2];
}
elseif ($parr[1]) {
	$real_path = $parr[0].'/'.$parr[1];
}
else {
	$real_path = $parr[0];
}
if ($parr[0] == 'u' || $parr[0] == 'page') {

	if ($parr[2]) {
		$tmp = $parr[1];
		$parr[1] = $parr[2];
		$parr[2] = $tmp;
	}
	else {
		$parr[3] = $parr[2];
		$parr[2] = $parr[1];
		$parr[1] = 'index';
	}

}

elseif ($parr[0] == 'group') {
	if ($parr[2]) {
		$tmp = $parr[1];
		$parr[1] = $parr[2];
		$parr[2] = $tmp;
	}
	else {
		$parr[3] = $parr[2];
		$parr[2] = $parr[1];
		$parr[1] = 'index';
	}
}

elseif ($parr[0] == 'footer_page') {
	$res = sql_query("select * from `".tb()."footer_pages` where id='{$parr[1]}'");
	$page = sql_fetch_array($res);
	if (!$page['id']) {
		c('page not found');
	}
	else {
		if (strlen($page['content'])<200) {
			$tmpc = strip_tags(trim($page['content']));
			if 	(preg_match("/^http:\/\//",$tmpc) || preg_match("/^https:\/\//",$tmpc)) {
				redirect($tmpc);
				exit;
			}
		}
		set_title(h($page['name']));
		c('<h2>'.h($page['name']).'</h2>');
		c($page['content']);
	}
	stop_here();
}

$path = 'modules/'.$parr[0].'/'.$parr[0].'.php';
$my_app = 'my/';
if (!file_exists($path)) {
	$my_app = '';
	$path = 'modules/home/home.php';
	$parr[0] = 'home';
	$parr[1] = 'index';
}


//

$offset = $num_per_page*($page-1);

if (!$current_app) {
	$current_app = $all_apps[$parr[0]];
}

// do app


if ($current_app['force'] == 'guest' && $client['id']) {
	header("Location:".uhome());
}

if ($menu_items[$current_menu_path]['type'] == 'private' || $menu_items[$top_menu_path]['type'] == 'private') {
	if (!$client['id']) {
		redirect('member/login/1');
	}
}

//
$key = $parr[0];


// access

if ($menu_items[$current_menu_path]['protected']) {
	do_auth($menu_items[$current_menu_path]['allowed_roles']);
}

// app cache
if (get_gvar('jcow_cache_enabled') ) {
	$hooks = check_hooks('app_cache');
	if ($hooks) {
		foreach ($hooks as $hook) {
			$hook_func = $hook.'_app_cache';
			if($cache_app = $hook_func($parr,$page,$client)) {
				$enable_app_cache = true;
			}
		}
	}
}


if ($enable_app_cache) {
	$app_content = get_cache($cache_app['key']);
}

if (!strlen($app_content)) {
	include_once($path);
	$farr = array($parr[2],$parr[3],$parr[4]);
}
else {
	load_tpl();
}

// functions

function app_name($id) {
	global $apps;
	return $apps[$id]['flag'];
}