<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */


function videos_u_menu(&$tab_menu,$page) {
	$tab_menu[] = array(
		'name'=>'Videos',
		'type'=>'tab',
		'path'=>'videos/liststories/page_'.$page['id']
		);
}

function videos_page_menu(&$tab_menu,$page) {
	$tab_menu[] = array(
		'name'=>'Videos',
		'type'=>'tab',
		'path'=>'videos/liststories/page_'.$page['id']
		);
}

function videos_group_menu(&$tab_menu,$page) {
	$tab_menu[] = array(
		'name'=>'Videos',
		'type'=>'tab',
		'path'=>'videos/liststories/page_'.$page['id']
		);
}

/* owner,connected,everyone */
function videos_quick_share() {
	return array(
		'u' => array('access'=>'owner','flag' => t('Video'),'weight'=>4),
		'page' => array('access'=>'connected','flag' => t('Video')),
		'group' => array('access'=>'connected','flag' => t('Video'))
		);
}