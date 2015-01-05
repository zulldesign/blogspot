<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class home{
	function home() {
		global $content, $db, $apps, $client, $settings, $tab_menu, $current_sub_menu, $menuon;
		$menuon = 'home';
		set_menu_path('home');
		$slogan = get_gvar('site_slogan');
		set_title($slogan);

	}

	function index($need_login = 0) {
		global $content, $db, $apps, $client, $settings, $config;
		if ($need_login)
			sys_notice(t('You need to login to do this'));

		c('app not found');
	}
}