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
global $page,$client;

if ($step == 'modify') {
	$res = sql_query("select * from ".tb()."banned where id='$id'");
	$row = sql_fetch_array($res);
	if ($row['expired']) {
		$revive = ceil(($row['expired']-time())/3600);
	}
	else {
		$revive = 0;
	}
	c('
	<form method="post" action="'.url('admin/blacklist/modifypost').'">
	Ip address: <input type="text" name="ip" value="'.$row['ip1'].'.'.$row['ip2'].'.'.$row['ip3'].'.'.$row['ip4'].'" /><br />
	Revive in <input type="text" size="5" value="'.$revive.'" name="revive" /> hours (<strong>0</strong> means never revive)<br />
	<input type="hidden" name="id" value="'.$id.'" />
	<input type="submit" value="Save" />
	</form>');
}

elseif($step == 'modifypost') {
	$ips = explode('.',$_POST['ip']);
	if ($_POST['revive']) {
		$expired = $_POST['revive']*3600+time();
	}
	else {
		$expired = 0;
	}
	sql_query("update ".tb()."banned set 
	ip1='{$ips[0]}',ip2='{$ips[1]}',ip3='{$ips[2]}',ip4='{$ips[3]}',expired='$expired'
	where id='{$_POST['id']}'");
	redirect('admin/blacklist/modify/'.$_POST['id'],1);
}
elseif($step == 'addpost') {
	if ($_POST['revive']) {
		$expired = $_POST['revive']*3600+time();
	}
	else {
		$expired = 0;
	}
	jcow_ban($_POST['ip'],'',$expired,$client['username']);
	redirect('admin/blacklist',1);
}
elseif($step == 'delete') {
	sql_query("delete from ".tb()."banned where id='$id'");
	redirect('admin/blacklist',1);
}
elseif($step == 'autoban') {
	set_gvar('autoban',$_POST['autoban']);
	set_gvar('autoban_acts',$_POST['autoban_acts']);
	set_gvar('autoban_trusted',$_POST['autoban_trusted']);
	redirect('admin/blacklist',1);
}
else {

	$num = 15;
	$offset = ($page-1)*$num;
	c('<table class="stories"><tr class="table_line1">
	<td>Target IP</td><td>Related user</td><td>Manager</td><td>Revive</td><td>OPT</td></tr>');
	$res = sql_query("select * from ".tb()."banned order by id desc limit $offset,$num");
	while ($row = sql_fetch_array($res)) {
		if ($row['expired']) {
			if ($row['expired'] < time()) {
				$reviving = 'Revived';
			}
			else {
				$reviving = 'in '.ceil(($row['expired']-time())/3600).' hours';
			}
		}
		else {
			$reviving = 'banned for ever';
		}
		if ($row['operator']) {
			$operator = url('u/'.$row['operator'],$row['operator']);
		}
		else {
			$operator = '<i>System</i>';
		}
		if ($row['username']) {
			$related = url('u/'.$row['username'],$row['username']);
		}
		else {
			$related = ' - ';
		}
		c('<tr class="row1"><td>'.$row['ip1'].'.'.$row['ip2'].'.'.$row['ip3'].'.'.$row['ip4'].'</td>
		<td>'.$related.'</td>
		<td>'.$operator.'</td>
		<td>'.$reviving.'</td>
		<td>'.url('admin/blacklist/modify/'.$row['id'],'Modify').' | '.url('admin/blacklist/delete/'.$row['id'],'Delete').'</td></tr>');
	}
	c('</table>');
	$res = sql_query("select count(*) as num from ".tb()."banned");
	$row = sql_fetch_array($res);
	$pb       = new PageBar($row['num'], $num, $page);
	$pb->paras = url('admin/blacklist');
	$pagebar  = $pb->whole_num_bar();
	c($pagebar);
	section_close('Current entries');
	c('
	<form method="post" action="'.url('admin/blacklist/addpost').'">
	Ip address: <input type="text" name="ip" value="'.$_POST['ip'].'" /><br />
	Revive in <input type="text" size="5" name="revive" value="0" /> hours (<strong>0</strong> means never revive)<br />
	<input type="submit" value="Add" />
	</form>');
	section_close('New entry');
	
	if (get_gvar('autoban')) {
		$autoban_check = 'checked';
	}
	if (!$autoban_acts = get_gvar('autoban_acts')) {
		$autoban_acts = 3;
	}
	if (!$autoban_trusted = get_gvar('autoban_trusted')) {
		$autoban_trusted = 30;
	}
	c('
	<form method="post" action="'.url('admin/blacklist/autoban').'">
	<p><input type="checkbox" name="autoban" value="1" '.$autoban_check.' /> Enable auto-banning</p>
	<p>Allow <input type="text" size="5" name="autoban_acts" value="'.$autoban_acts.'" /> suspicious acts in a short time before being banned.</p>
	<p>Members that have signed up more than <input type="text" size="5" name="autoban_trusted" value="'.$autoban_trusted.'" /> day(s) are trusted anyway.</p>
	<input type="submit" value="Save" />
	</form>');
	section_close('auto-banning settings');
}