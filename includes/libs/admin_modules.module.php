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
global $current_modules,$menu_items;


if ($handle = opendir('modules')) {
	while (false !== ($file = readdir($handle))) {
		$module = array();
		$ignores = array('.svn','home');
		if (is_dir('modules/' .$file) && $file != '.' && $file != '..' && !in_array($file,$ignores)) {
			$module['name'] = $file;
			if (file_exists('modules/'.$file.'/'.$file.'.hook.php')) {
				$module['hooking'] = 1;
			}
			else {
				$module['hooking'] = 0;
			}
			if (file_exists('modules/'.$file.'/'.$file.'.info')) {
				$filename = 'modules/'.$file.'/'.$file.'.info';
				$handle2 = fopen($filename, "r");
				$info = fread($handle2, filesize($filename));
				fclose($handle2);
				$arr = explode("\r\n",$info);
				foreach ($arr as $item) {
					$info = explode('=',$item);
					$key = trim($info[0]);
					if ($key == 'name') $key = 'flag';
					$value = trim($info[1]);
					$module[$key] = $value;
				}
			}
			else {
				$module['flag'] = $file;
				$module['version'] = 'unknown';
				$module['description'] = '';
				$module['type'] = '';
				$module['manage_path'] = '';
			}
			$this_modules[$file] = $module;
		}
	}
	closedir($handle);
}


if ($_POST['step'] == 'post') {
	foreach ($this_modules as $key=>$module) {
		$module_key = $key.'_actived';
		if ($_POST[$module_key] || $module['type'] == 'core') {
			if (is_array($current_modules[$key])) {
				sql_query("update ".tb()."modules set actived=1 where name='$key'");
			}
			else {
				sql_query("insert into ".tb()."modules(name,actived) values('{$key}',1)");
			}
		}
		else {
			sql_query("update ".tb()."modules set actived=0 where name='$key'");
		}
	}
	sql_query("update ".tb()."modules set actived=0 where name='dashboard'");
	// update modules
	$current_modules = array();
	$res = sql_query("select * from ".tb()."modules");
	while ($row = sql_fetch_array($res)) {
		$key = $row['name'];
		$current_modules[$key] = $row;
	}

	foreach ($current_modules as $module) {
		if ($module['actived'] && file_exists('modules/'.$module['name'].'/'.$module['name'].'.install.php')) {
			include_once('modules/'.$module['name'].'/'.$module['name'].'.install.php');
		}
	}
	// hook_enabled
	$hooks = check_hooks('enabled');
	$menu = array();
	if ($hooks) {
		foreach ($hooks as $hook) {
			$hook_func = $hook.'_enabled';
			$hook_func();
		}
	}

	// hook_menus
	$hooks = check_hooks('menu');
	$menu = array();
	if ($hooks) {
		foreach ($hooks as $hook) {
			$hook_func = $hook.'_menu';
			$arr = $hook_func();
			foreach($arr as $key=>$item) {
				$item['app'] = $hook;
				$menu[$key] = $item;
			}
		}
	}

	foreach($menu_items as $item) {
		$key = $item['path'];
		if (strlen($item['app']) && !is_array($menu[$key])) {
			sql_query("delete from ".tb()."menu where id='{$item['id']}'");
		}
	}
	foreach($menu as $key=>$item) {
		if (is_array($menu_items[$key])) {
			sql_query("update ".tb()."menu set type='{$item['type']}',name='{$item['name']}',protected='{$item['protected']}',icon='{$item['icon']}',parent='{$item['parent']}',tab_name='{$item['tab_name']}' where path='$key'");
		}
		else {
			$res = sql_query("select max(weight) as weight from ".tb()."menu");
			$row = sql_fetch_array($res);
			$weight = $row['weight'] + 1;
			sql_query("insert into ".tb()."menu(type,name,path,app,actived,weight,protected,icon,parent,tab_name) values('{$item['type']}','{$item['name']}','{$key}','{$item['app']}',1,'$weight','{$item['protected']}','{$item['icon']}','{$item['parent']}','{$item['tab_name']}')");
		}
	}
	foreach ($this_modules as $key=>$module) {
			if (is_array($current_modules[$key])) {
				sql_query("update ".tb()."modules set hooking='{$module['hooking']}' where name='$key'");
			}
			else {
				sql_query("insert into ".tb()."modules(hooking,name) values('{$module['hooking']}','$key')");
			}
	}
	if (is_array($current_modules)) {
		foreach ($current_modules as $key=>$item) {
			if (!is_array($this_modules[$key])) {
				sql_query("delete from ".tb()."modules where name='$key'");
			}
		}
	}
	redirect('admin/modules',1);
}
		section_content('
		<p>IF YOU MODIFIED SOME MODULE FILES, YOU NEED TO CLICK THE "Update modules" TO MAKE THEM EFFECTIVE.</p>
		<table width="100%" border="0" class="stories">
		<form method="post" action="'.url('admin/modules').'">
		<tr class="table_line1">
		<td width="50">Active</td>
		<td>Module</td>
		<td width="50">Type</td>
		<td width="80">Version</td></tr>');
		if (is_array($this_modules)) {
			foreach ($this_modules as $module) {
				if ($module['type'] != 'core') {
					$key = $module['name'];
					$type = $module['type'];
					$menu_item = $module['menu_item'] ? 'Yes':'';
					$checked = $current_modules[$key]['actived'] ? 'checked':'';
					$manage_path = $module['manage_path']?'- <strong>'.url($module['manage_path'],t('Manage')).'</strong>':'';
					if (!$current_modules[$key]['actived']) $manage_path = '';
					$module_key = $module['name'].'_actived';
					if ($module['type'] == 'core') {
						$checkbox = '<input type="checkbox" name="'.$module_key.'" value="1" checked DISABLED />';
					}
					else {
						$checkbox = '<input type="checkbox" name="'.$module_key.'" value="1" '.$checked.' />';
					}
					section_content('<tr class="row1"><td>'.$checkbox.'</td>
					<td>'.$module['flag'].' '.$manage_path.'<div class="sub">'.$module['description'].'</div></td>
					<td>'.$type.'</td>
					<td>'.$module['version'].'</tr>');
				}
			}

			c('<tr class="row1"><td><input type="checkbox" value="1" checked DISABLED /></td>
					<td>Jcow</td>
					<td>Core</td>
					<td>'.jversion());
			foreach ($this_modules as $module) {
				if ($module['type'] == 'core') {
					$key = $module['name'];
					$type = $module['type'];
					$menu_item = $module['menu_item'] ? 'Yes':'';
					$checked = $current_modules[$key]['actived'] ? 'checked':'';
					$manage_path = $module['manage_path']?'- <strong>'.url($module['manage_path'],t('Manage')).'</strong>':'';
					if (!$current_modules[$key]['actived']) $manage_path = '';
					$module_key = $module['name'].'_actived';
					if ($module['type'] == 'core') {
						c('<input type="hidden" name="'.$module_key.'" value="1" />');
					}
				}
			}


		}
		section_content('
		</td></tr>
		<tr><td colspan="4">
		<input type="hidden" name="step" value="post" />
		<input type="submit" value="Update modules" /> 
		<a href="http://community.jcow.net/extensions" target="_blank">+Browse more Modules..</a>
		</td></tr></table> ');
	
