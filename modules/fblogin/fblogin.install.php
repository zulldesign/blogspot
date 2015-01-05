<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */

function fblogin_enabled() {
	set_gvar('public_app_fblogin',1);
}

function fblogin_menu() {
	$items = array();
	$items['fblogin/admin'] = array(
		'name'=>'Facebook connection settings',
		'type'=>'admin',
		'protected'=>1
	);
	return $items;
}