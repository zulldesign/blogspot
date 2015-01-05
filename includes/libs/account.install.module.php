<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

function account_menu() {
	$items = array();
	$items['account'] = array(
		'name'=>'My account',
		'tab_name'=>'My information',
		'type'=>'personal'
	);

	$items['account/avatar'] = array(
		'name'=>'Avatar',
		'type'=>'tab',
		'parent'=>'account'
	);


	$items['account/privacy'] = array(
		'name'=>'Privacy',
		'type'=>'tab',
		'parent'=>'account'
	);
	$items['account/cpassword'] = array(
		'name'=>'Password',
		'type'=>'tab',
		'parent'=>'account'
	);

	return $items;
}

?>