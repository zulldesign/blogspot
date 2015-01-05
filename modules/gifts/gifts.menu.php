<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */

function gifts_menu() {
	$items = array();
	$items['gifts/public1'] = array(
		'name'=>'Gifts Pmenu',
		'tab_name'=>'Primary Tab',
		'type'=>'public'
	);

	$items['gifts/public2'] = array(
		'name'=>'Test Tmenu1',
		'parent'=>'gifts',
		'type'=>'tab',
	);
	$items['gifts/public3'] = array(
		'name'=>'Test Tmenu2',
		'parent'=>'gifts',
		'type'=>'tab',
	);

	$items['gifts/test1'] = array(
		'name'=>'My test1',
		'protected'=>1,
		'type'=>'private',
		'icon'=>'modules/vip/icon.png'
	);
	$items['gifts/test2'] = array(
		'name'=>'My test2',
		'type'=>'private',
		'icon'=>'modules/vip/icon.png'
	);
	return $items;
}

?>