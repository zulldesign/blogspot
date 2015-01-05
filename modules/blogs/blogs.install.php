<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */

function blogs_menu() {
	$items = array();
	$items['blogs'] = array(
		'name'=>'Blogs',
		'tab_name'=>'Community',
		'type'=>'app'
	);
	$items['blogs/following'] = array(
		'name'=>'Following',
		'type'=>'tab',
		'parent'=>'blogs'
	);
	return $items;
}


?>