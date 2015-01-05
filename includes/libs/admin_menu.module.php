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


		global $menu_items;

	if ($step == 'post') {
		global $menu_items;
			foreach ($menu_items as $item) {
				$id = $item['id'];
				$path = $_POST['path_'.$id];
				$name = $_POST['name_'.$id];
				$app = $_POST['app_'.$id];
				$weight = $_POST['weight_'.$id];
				$actived = $_POST['active_'.$id] ? 1:0;
				if (strlen($name)) {
					sql_query("update ".tb()."menu set actived=$actived,weight='$weight',name='$name' where id='$id'");
				}
			}
		redirect('admin/menu',1);
	}
		section_content('
		<p>DO NOT MAKE TRANSLATE THE MENU ITEMS. YOU SHOULD MAKE TRANSLATION '.URL('admin/translate','HERE').'</p><table width="100%" border="0" class="stories">
		<form method="post" action="'.url('admin/menu/post').'">
		<tr class="table_line1"><td width="50">Active</td>
		<td width="80">Weight</td>
		<td>Name</td>
		<td>Path/URL</td>
		</tr>');
		section_content('<tr class="table_line2"><td colspan="4">App Menu</td></tr>');
		foreach ($menu_items as $item) {
			if ($item['type'] == 'personal' || $item['type'] == 'community' || $item['type'] == 'app') {
				$checked = $item['actived'] ? 'checked':'';
				if (!$item['app']) {
					$path = '<input type="text" name="path_'.$item['id'].'" value="'.$item['path'].'" size="40" />';
					$delete = url('admin/menu/delete/'.$item['id'],t('Delete'));
				}
				else {
					$path = $item['path'];
					$delete = '';
				}
				section_content('<tr class="row1"><td><input type="checkbox" name="active_'.$item['id'].'" value="1" '.$checked.' /></td>
				<td><input type="text" name="weight_'.$item['id'].'" value="'.$item['weight'].'" size="3" /></td>
				<td><input type="text" name="name_'.$item['id'].'" value="'.$item['name'].'" /><input type="hidden" name="app_'.$item['id'].'" value="'.$item['app'].'" /></td>
				<td>'.$path.' '.$delete.'</td>
				</tr>');
			}
		}
		section_content('
		<tr><td colspan="4">
		<input type="submit" value="Save changes" />
		</td></tr>
		</form></table>');
		
		

	
