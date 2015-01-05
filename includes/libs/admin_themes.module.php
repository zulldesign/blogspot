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
		set_gvar('theme_tpl',$_POST['theme_tpl']);
		if (file_exists('themes/'.$_POST['theme_tpl'].'/settings.php')) {
			include('themes/'.$_POST['theme_tpl'].'/settings.php');
		}
		if (is_array($theme_blocks)) {
			foreach ($theme_blocks as $key=>$block) {
				$key = 'theme_block_'.$key;
				if (!get_text($key)) {
					set_text($key,addslashes($block['default_value']));
				}
			}
		}
		redirect('admin/themes',1);
}
		if ($handle = opendir('themes')) {
			while (false !== ($file = readdir($handle))) {
				if (is_dir('themes/' .$file) && $file != '.' && $file != '..' && $file != '.svn' ) {
					$themes[] = $file;
				}
			}
			closedir($handle);
		}
		section_content('
		<style>
		.theme_preview {
			height:160px;
			width:150px;
			float:left;
			border: #eee 1px solid;
			margin: 5px;
			text-align:center;
		}
		.theme_preview img {
			border: #eee 1px solid;
		}
		</style>
		<form method="post" action="'.url('admin/themes/post').'">');
		if (is_array($themes)) {
			foreach ($themes as $theme) {
				$selected = '';
				if ($theme == get_gvar('theme_tpl')) {
					$selected = 'checked';
					$actived = '<strong>Actived</strong><br />'.url('admin/blocks','Manage Blocks');
				}
				else {
					$actived = 'Active';
				}
				section_content('<div class="theme_preview">
				<label for="theme'.$theme.'">
				<img src="'.uhome().'/themes/'.$theme.'/preview.gif" /><br />
				'.$theme.'<br />
				<input type="radio" name="theme_tpl" value="'.$theme.'" id="theme'.$theme.'" '.$selected.' />'.$actived.'
				</label>
				</div>');
			}
		}
		section_content('
		<div class="br"></div>
		<input type="submit" value="Save Change" />
		</form>');
		section_close('Themes');


