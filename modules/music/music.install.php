<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */


function music_menu() {
	$items = array();
	$items['music'] = array(
		'name'=>'Music',
		'tab_name'=>'Community',
		'type'=>'app'
	);
	$items['music/following'] = array(
		'name'=>'Following',
		'type'=>'tab',
		'parent'=>'music'
	);
	return $items;
}


?>