<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */


class language{
	function index() {
		die();
	}

	function post($lang) {
		global $client, $lang_options,$from_url, $sid;
		if ($lang_options[$lang]) {
			$client['lang'] = $lang;
			setcookie($sid.'lang', $lang, time()+3600*24*365,'/');
			redirect($from_url,'Language changed');
		}
	}
}