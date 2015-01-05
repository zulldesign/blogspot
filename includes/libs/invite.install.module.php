<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */


function invite_menu() {
	$items = array();
	$items['invite'] = array(
		'name'=>'Invite',
		'tab_name'=>'Invite',
		'type'=>'personal'
	);

	$items['invite/histories'] = array(
		'name'=>'Histories',
		'tab_name'=>"Following",
		'parent'=>'invite',
		'type'=>'tab'
	);
	return $items;
}

?>