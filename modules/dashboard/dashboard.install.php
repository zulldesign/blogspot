<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */

function dashboard_menu() {
	$items = array();
	$items['dashboard'] = array(
		'name'=>'Dashboard',
		'type'=>'personal'
	);

	return $items;
}

?>