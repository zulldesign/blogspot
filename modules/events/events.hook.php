<?php
/* ############################################################ *\
 ----------------------------------------------------------------
GUN GPL
 ----------------------------------------------------------------
\* ############################################################ */

function events_u_menu(&$tab_menu,$page) {
	$tab_menu[] = array(
		'name'=>'Events',
		'type'=>'tab',
		'path'=>'events/liststories/page_'.$page['id']
		);
}

function events_group_menu(&$tab_menu,$page) {
	$tab_menu[] = array(
		'name'=>'Events',
		'type'=>'tab',
		'path'=>'events/liststories/page_'.$page['id']
		);
}

/* owner,connected,everyone */
function events_quick_share() {
	return array(
		'u' => array('access'=>'owner','flag' => t('Events'),'weight'=>10),
		'group' => array('access'=>'connected','flag' => t('Events'),'weight'=>10)
		);
}