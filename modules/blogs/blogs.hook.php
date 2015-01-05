<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */

function blogs_u_menu(&$tab_menu,$page) {
	$tab_menu[] = array(
		'name'=>'Blogs',
		'type'=>'tab',
		'path'=>'blogs/liststories/page_'.$page['id']
		);
}

function blogs_page_menu(&$tab_menu,$page) {
	$tab_menu[] = array(
		'name'=>'Blogs',
		'type'=>'tab',
		'path'=>'blogs/liststories/page_'.$page['id']
		);
}

function blogs_group_menu(&$tab_menu,$page) {
	$tab_menu[] = array(
		'name'=>'Blogs',
		'type'=>'tab',
		'path'=>'blogs/liststories/page_'.$page['id']
		);
}

/* owner,connected,everyone */
function blogs_quick_share() {
	return array(
		'u' => array('access'=>'owner','flag' => t('Blog'),'weight'=>1),
		'page' => array('access'=>'connected','flag' => t('Text'),'weight'=>1),
		'group' => array('access'=>'connected','flag' => t('Text'),'weight'=>1),
		);
}
