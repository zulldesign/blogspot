<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */


function videos_menu() {
	$items = array();
	$items['videos'] = array(
		'name'=>'Videos',
		'tab_name'=>'Community',
		'type'=>'app'
	);
	$items['videos/following'] = array(
		'name'=>'Following',
		'type'=>'tab',
		'parent'=>'videos'
	);
	return $items;
}


?>