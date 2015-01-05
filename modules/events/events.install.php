<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */


function events_menu() {
	$items = array();
	$items['events'] = array(
		'name'=>'Events',
		'tab_name'=>'Community',
		'type'=>'app'
	);
	$items['events/following'] = array(
		'name'=>'Following',
		'type'=>'tab',
		'parent'=>'events'
	);
	return $items;
}
?>