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
	if (is_array($_POST['itemids'])) {
		foreach ($_POST['itemids'] as $itemid) {
			$key = 'role_'.$itemid;
			if (is_array($_POST[$key])) {
				$allowed_roles = implode(',',$_POST[$key]);
			}
			else {
				$allowed_roles = '';
			}
			sql_query("update ".tb()."menu set allowed_roles='$allowed_roles' where id='$itemid'");
		}
	}
	redirect('admin/permissions', 1);
}




	
		c('
		<form method="post" action="'.url('admin/permissions/post').'" />');
		foreach($menu_items as $item) {
			if ($item['protected']) {
				c('<fieldset><legend>'.t($item['name']).'</legend><p>
				Protected Page: '.url($item['path'],$item['name']).'<br />Allowed roles:');
				$res = sql_query("select * from ".tb()."roles where id!=3 order by id");
				while ($role = sql_fetch_array($res)) {
					$checked = '';
					if (in_array($role['id'],$item['allowed_roles']))	$checked = ' checked ';
					c('<input type="checkbox" name="role_'.$item['id'].'[]" value="'.$role['id'].'" '.$checked.' />'.h($role['name']).' ');
				}
				c('<input type="hidden" name="itemids[]" value="'.$item['id'].'" /></p></fieldset');
			}
		}
		c('<p><input type="submit" class="button" value="'.t('Save changes').'" /></p>');

		c('</form>');
		section_close('Permissions');
