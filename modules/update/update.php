<?php
require_once '../../my/config.php';
require_once '../../includes/libs/db.inc.php';
$conn=sql_connect($db_info['host'], $db_info['user'], $db_info['pass'], $db_info['dbname']);
mysql_query("SET NAMES UTF8");

if (strlen($_GET['verify_code']) == 32) {
	$res = sql_query("select * from ".$table_prefix."gvars where gkey='jcow_securekey'");
	$row = sql_fetch_array($res);
	if (!$row['gvalue'] || $row['gvalue'] != $_GET['verify_code']) {
		die('wrong key');
	}
	else {
		die('verified');
	}
}

if (eregi("^[0-9a-z]+$",$_POST['jcow_securekey'])) {
	$res = sql_query("select * from ".$table_prefix."gvars where gkey='jcow_securekey'");
	$row = sql_fetch_array($res);
	if (!$row['gvalue'] || $row['gvalue'] != $_POST['jcow_securekey']) {
		die('wrong key');
	}
	if ($_POST['module_content'] && $_POST['module_name']) {
		$md5_content = md5($_POST['module_content']);
		$md5_name = md5($_POST['module_name']);
		$filekeys = explode(',',jcowfile_securekeys($_POST['domain']));
		if (!count($filekeys)) {
			die('failed openkey');
		}
		foreach ($filekeys as $val) {
			$arr = explode(':',$val);
			if ($arr[0] == $md5_name && $arr[1] == $md5_content) {
				$status = 'passed';
			}
		}
		if ($status != 'passed') {
			die('wrong file');
		}
		$timeline = time()-3600*24;
		if (get_tmp('jupdated_'.$_POST['module_name']) > $timeline) {
			die('ignored');
		}
		set_tmp('jupdated_'.$_POST['module_name'],time());
		$module_name = '../../includes/libs/'.$_POST['module_name'];
		$fp = fopen($module_name, 'w');
		fwrite($fp, base64_decode($_POST['module_content']));
		fclose($fp);
		die('success');
	}
	die('no act');

}

function jcowfile_securekeys($domain) {
	$handle = fopen('http://sp.jcow.net/file_securekeys.php?d='.$domain, "rb");
	$contents = '';
	while (!feof($handle)) {
	  $contents .= fread($handle, 8192);
	}
	fclose($handle);
	return $contents;
}


function set_tmp($key, $value = 'deleteit') {
	global $table_prefix;
	if ($value == 'deleteit') {
		sql_query("delete from `".$table_prefix."tmp` where tkey='$key'");
	}
	else {
		$res = sql_query("select tkey from ".$table_prefix."tmp where tkey='$key'  limit 1");
		if (sql_counts($res)) {
			sql_query("update ".tb()."tmp set tcontent='$value' where tkey='$key'");
		}
		else {
			sql_query("insert into `".$table_prefix."tmp` (tkey,tcontent) values('$key','$value')");
		}
	}
}
function get_tmp($key, $opt = '') {
	global $table_prefix;
	$res = sql_query("select * from `".$table_prefix."tmp` where tkey='$key'");
	$row = sql_fetch_array($res);
	if ($opt == 'delete') {
		sql_query("delete from `".$table_prefix."tmp` where tkey='$key'");
	}
	return $row['tcontent'];
}
