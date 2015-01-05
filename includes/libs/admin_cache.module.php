<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

if ($step == 'post') {
	set_gvar('jcow_cache_buffer',$_POST['jcow_cache_buffer']);
	set_gvar('jcow_cache_enabled',$_POST['jcow_cache_enabled']);
	redirect('admin/cache',1);
}
else {

	if (!$jcow_cache_buffer = get_gvar('jcow_cache_buffer')) {
		$jcow_cache_buffer = 60;
	}
	if (get_gvar('jcow_cache_enabled')) {
		$checked = 'checked';
	}
	c('
	<form method="post" action="'.url('admin/cache/post').'">
	<p>
	<input type="checkbox" name="jcow_cache_enabled" value="1" '.$checked.' /> Enable Cache
	<div class="sub">If you are debugging, you should Disable cache.</div>
	</p>
	<p>
	Default Jcow Cache Buffer: <input type="text" name="jcow_cache_buffer" size="2" value="'.$jcow_cache_buffer.'" /> seconds.
	<div class="sub">
	Leave as default if you don\'t know what it is.</div>
	</p>
	<p>
	<input type="submit" value=" Save Changes " />
	</p>
	</form>
	');
}