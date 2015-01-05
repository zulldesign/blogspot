<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */

function images_menu() {
	$items = array();
	$items['images'] = array(
		'name'=>'Images',
		'tab_name'=>'Community',
		'type'=>'app'
	);
	$items['images/following'] = array(
		'name'=>'Following',
		'type'=>'tab',
		'parent'=>'images'
	);
	return $items;
}

?>