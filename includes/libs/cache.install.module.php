<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */


function cache_menu() {
	$items = array();
	$items['cache'] = array(
		'name'=>'Cache Controller',
		'type'=>'admin',
		'protected'=>1
	);
	return $items;
}

?>