<?php

/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */


function music_u_menu(&$tab_menu,$profile) {
	$tab_menu[] = array(
		'name'=>'Music',
		'type'=>'tab',
		'path'=>'music/liststories/user_'.$profile['username']
		);
}
