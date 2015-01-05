<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */



function images_u_menu(&$tab_menu,$page) {
	$tab_menu[] = array(
		'name'=>'Images',
		'type'=>'tab',
		'path'=>'images/liststories/page_'.$page['id']
		);
}

function images_page_menu(&$tab_menu,$page) {
	$tab_menu[] = array(
		'name'=>'Images',
		'type'=>'tab',
		'path'=>'images/liststories/page_'.$page['id']
		);
}

function images_group_menu(&$tab_menu,$page) {
	$tab_menu[] = array(
		'name'=>'Images',
		'type'=>'tab',
		'path'=>'images/liststories/page_'.$page['id']
		);
}

/* owner,connected,everyone */
function images_quick_share() {
	return array(
		'u' => array('access'=>'owner','flag' => t('Image'),'weight'=>3),
		'page' => array('access'=>'connected','flag' => t('Image'),'weight'=>10),
		'group' => array('access'=>'connected','flag' => t('Image'),'weight'=>10)
		);
}
