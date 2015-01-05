<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

function browse_menu() {
	$items = array();
	$items['browse'] = array(
		'name'=>'Browse',
		'type'=>'community',
		'protected'=>1
	);

	return $items;
}

?>