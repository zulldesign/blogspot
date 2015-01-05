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
global $page,$client,$num_per_page,$offset;
if ($step == 'modify') {
}

elseif($step == 'changestatus') {
	if (is_array($_POST['ids'])) {
		foreach ($_POST['ids'] as $id) {
			sql_query("update ".tb()."accounts set disabled='{$_POST['status']}' where id='$id'");
		}
	}
	redirect(url('admin/members_quick','','',array('page'=>$_POST['page'])),1);
}

		c('<form method="post" action="'.url('admin/members_quick').'">
		Username or Email address: 
		<input type="text" name="username" /> <input type="submit" value="'.t('Search').'" />
		</form><br />');
		if ($_POST['username']) {
			$res = sql_query("select * from `".tb()."accounts` "." where username like '%{$_POST['username']}%' or email like '%{$_POST['username']}%' order by id DESC limit 12");
		}
		else {
			$res = sql_query("select count(*) as total from `".tb()."accounts` "." where 1 $filter ");
			$row = sql_fetch_array($res);
			$total = $row['total'];
			$pb       = new PageBar($total, $num_per_page, $page);
			$pb->paras = url('admin/members_quick'.$pageb);
			$pagebar  = $pb->whole_num_bar();

			$res = sql_query("select * from `".tb()."accounts` "." order by id DESC limit $offset,$num_per_page");
		}
		c('<table class="stories">
		<form method="post" action="'.url('admin/members_quick/changestatus').'">
		<tr class="table_line1">
		<td></td><td>Member</td><td>Details</td><td>Recent acts</td></tr>');
		while ($member = sql_fetch_array($res)) {
			$res2 = sql_query("select s.*,u.username,u.avatar,p.uid as wall_uid from ".tb()."streams as s left join ".tb()."accounts as u on u.id=s.uid left join ".tb()."pages as p on p.id=s.wall_id where u.id='{$member['id']}' order by s.id desc limit 5");
			$acts = '';
			while($row2 = sql_fetch_array($res2)) {
				$attachment = unserialize($row2['attachment']);
				$att = '';
				if (count($attachment) > 1) {
					if (strlen($attachment['name'])) {
						if (strlen($attachment['uri'])) {
							$att = url($attachment['uri'],h($attachment['name']));
						}
						else {
							$att = h($attachment['name']);
						}
					}
					if (strlen($attachment['title'])) {
						$att = url($attachment['uri'],h($attachment['title']) );
					}
				}
				$acts .= '<li>'.$row2['message'].' '.$att.'</li>';
			}
			if ($member['disabled'] == 1) {
			$status = 'Un-verified';
			}
			elseif ($member['disabled'] == 2) {
				$status = '<font color="red">Suspended</font>';
			}
			elseif ($member['disabled'] == 3) {
				$status = '<font color="red">Spammer</font>';
			}
			else {
				$status = '<font color="green">Verified</font>';
			}
			$gender = $member['gender'] ? 'Male':'Femail';
			section_content('
			<tr class="row1">
			<td><input type="checkbox" name="ids[]" value="'.$member['id'].'" /></td>
			<td valign="top" width="60">'.avatar($member).'<br />'.$member['username'].'<br />'.h($member['fullname']). '<br />'.$status.'<br />'.url('admin/useredit/'.$member['id'],'Edit').'<br />
			</td>
			<td>Gender: '.$gender.'<br />
			Location: '.h($member['location']).'<br />
			Email: '.h($member['email']).'<br />
			Birth: '.$member['birthmonth'].'/'.$member['birthday'].'/'.$member['birthyear'].'<br />
			Custom filed 1: '.h($member['var1']).'<br />
			Custom field 2: '.h($member['var2']).'<br />
			Custom field 3: '.h($member['var3']).'
			</td>
			<td><ul>'.$acts.'</ul></td>
			</tr>');
		}
		section_content('
		<tr class="row2"><td colspan="4">
		Change status to: <select name="status">
	<option value="0">Select..</option>
	<option value="0">Verified</option>
	<option value="2">Suspend</option>
	<option value="3">Spammer</option>
	<option value="1">Un-verified</option>
	</select> <input type="submit" value="Save" />
		<div class="sub">
					<strong>Un-verified</strong> - can not post.<br />
					<strong>Suspended</strong> - can not login.<br />
					<strong>Spammer</strong> - can not post and old posts will be hidden.</div>
		<input type="hidden" name="page" value="'.$page.'" />
		</td></tr>
		</form>
		</table>');
		
		c($pagebar);
