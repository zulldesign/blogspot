<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

if (basename($_SERVER["SCRIPT_NAME"]) != 'index.php') die(basename($_SERVER["SCRIPT_NAME"]));

$theme_tpl = get_gvar('theme_tpl');
if (file_exists('themes/'.$theme_tpl.'/settings.php')) {
	include('themes/'.$theme_tpl.'/settings.php');
}
if (is_array($theme_blocks)) {
	if ($step == 'post') {
		foreach ($theme_blocks as $key=>$block) {
			$key = 'theme_block_'.$key;
			set_gvar($key,$_POST[$key]);
		}
		redirect('admin/blocks',1);
	}

	section_content('<h2>Blocks in your current template ('.$theme_tpl.')</h2>
	<form method="post" action="'.url('admin/blocks/post').'">');
	foreach ($theme_blocks as $key=>$block) {
		$key = 'theme_block_'.$key;
		section_content('<fieldset><legend>'.$block['name'].'</legend>
		<p>'.$block['description'].'<br /><textarea name="'.$key.'" rows="5">'.h(get_gvar($key)).'</textarea>
		</p></fieldset>');
	}
	section_content('<p>
		<div style="background:#FFFF99">Blocks in theme is disabled, please use "Ad blocks" instead.</div></p>');

	c('
	</form>');
}

else {
	section_content('No block was defined in your current template');
}