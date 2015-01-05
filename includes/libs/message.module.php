<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class message{
	
	function message() {
		global $client, $menuon, $tab_menu;
		$menuon = 'message';
		if (!$client['id']) {
			redirect('member/login/1');
		}
		$tab_menu[] = array('path'=>'message/inbox', 'name'=>t('Inbox'));
		$tab_menu[] = array('path'=>'message/outbox', 'name'=>t('Outbox'));
	}
	
	function index() {
		global $ubase;
		redirect($ubase.'message/inbox');
	}

	function inbox() {
		global $content, $db, $client, $offset, $num_per_page, $page, $ubase, $nav, $current_sub_menu;
		$current_sub_menu['href'] = 'message/inbox';
		set_title(t('Message'));
		$res = sql_query("SELECT m.*,u.username,u.avatar FROM `".tb()."messages` as m left join `".tb()."accounts` as u on u.id=m.from_id where m.to_id='{$client['id']}' and m.from_id>0 ORDER by m.id DESC LIMIT $offset,$num_per_page ");
		c('<table class="stories" cellspacing="1"><form action="'.url('message/delete').'" method="post">');
		c('<tr class="table_line1">
			<td width="10"></td>
			<td width="50"></td>
			<td>Title</td>
			<td width="50">Time</td>
			</tr>');
		while ($row = sql_fetch_array($res)) {
			if (!strlen($row['subject'])) {
				$row['subject'] = strip_tags(utf8_substr($row['message'],40));
			}
			if (!$row['hasread']) {
				c('<tr class="row1 stress">');
			}
			else {
				c('<tr class="row1">');	
			}
			if (!$row['from_id']) {
				$from_user = 'System';
			}
			else {
				$from_user = htmlspecialchars($row['username']);
			}
			c('<td width="10"><input type="checkbox" name="ids[]" value="'.$row['id'].'" /></td>');
			c('<td width="50" nowrap>'.avatar($row,25).'</td>');
			c('<td>'.$from_user.':<br />'.url('message/view/'.$row['id'], htmlspecialchars($row['subject'])).'</td>');
			c('<td width="150">'.get_date($row['created']).'</td>');
			c('</tr>');
		}
		c('<tr class="row2"><td colspan="4">
		<input type="checkbox" name="allbox" id="check_uncheck" onclick="js_check_all(this.form)" /><label for="check_uncheck">'.t('Check/ Uncheck all').'</label>
		<input type="submit" value="'.t('Delete').'" '.cfm().'/></td></tr>');
		c('</form></table>');

		// pager
		$res = sql_query("select count(*) as total from `".tb()."messages` where to_id='{$client['id']}' and from_id>0");
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = $ubase.$this->name.'/inbox';
		$pagebar  = $pb->whole_num_bar();
		c($pagebar);
	}

	function view($mid) {
		global $content, $db, $client, $ubase, $nav, $current_sub_menu;
		$current_sub_menu['href'] = 'message/inbox';
		$res = sql_query("SELECT m.*,u.username,u.avatar,u.lastlogin from `".tb()."messages` as m left join `".tb()."accounts` as u on u.id=m.from_id where m.id='$mid' and m.to_id='{$client['id']}'");
		$row = sql_fetch_array($res);
		if ($row['from_id']) {
			button('message/compose/'.$mid, t('Reply'));
		}
		button('message/delete/'.$mid, t('Delete'));
		if ($row['id'] && $row['username']) {
			if ($row['lastlogin'] > (time()-300))
				$row['user_online'] = '<img src="'.uhome().'/files/icons/online.gif" />';
			else
				$row['user_online'] = '<img src="'.uhome().'/files/icons/offline.gif" />';
			$nav[] = htmlspecialchars($row['subject']);
			set_title(htmlspecialchars($row['subject']));
			c('<table class="stories"><tr class="table_line1"><td>'.get_date($row['created']).'</td></tr></table>
			');
			c('<table class="stories"><tr class="row1">
			<td valign="top" width="150">'.$row['username'].' '.$row['user_online'].'<br />'.avatar($row).'</td>
			<td valign="top"><strong>'.h($row['subject']).'</strong>
			<p>'.nl2br(decode_bb(htmlspecialchars($row['message']))).'</p>
			</td></tr>
			</table>');
			sql_query("UPDATE `".tb()."messages` set hasread=1 where id='$mid'");
		}
		else {
			die('wrong mid');
		}
	}

	function viewsent($mid) {
		global $content, $db, $client, $ubase, $nav, $current_sub_menu;
		$current_sub_menu['href'] = 'message/outbox';
		$res = sql_query("SELECT m.*,u.username from `".tb()."messages_sent` as m left join `".tb()."accounts` as u on u.id=m.to_id where m.id='$mid' ");
		$row = sql_fetch_array($res);
		button('message/deletesent/'.$mid, t('Delete'));
		if ($row['id']) {
			$nav[] = htmlspecialchars($row['subject']);
			set_title(htmlspecialchars($row['subject']));
			c('<h1>'.h($row['subject']).'</h1>');
			$fromu = url('u/'.$row['username'],$row['username']);
			c('<span class="sub">To: '.$fromu.', '.get_date($row['created']).'</span>');
			c('<p>'.nl2br(decode_bb(htmlspecialchars($row['message']))).'</p>');
		}
		else {
			die('wrong mid');
		}
	}

	function outbox() {
		global $content, $db, $client, $offset, $num_per_page, $page, $ubase, $nav, $current_sub_menu;
		$current_sub_menu['href'] = 'message/outbox';
		set_title(t('Message'));
		$res = sql_query("SELECT m.*,u.username FROM `".tb()."messages_sent` as m left join `".tb()."accounts` as u on u.id=m.to_id where m.from_id='{$client['id']}' ORDER by m.id DESC LIMIT $offset,$num_per_page ");
		c('<table class="stories" cellspacing="1"><form action="'.url('message/deletesent').'" method="post">');
		c('<tr class="table_line1">
			<td width="10"></td>
			<td width="50">To</td>
			<td>Title</td>
			<td width="50">Time</td>
			</tr>');
		while ($row = sql_fetch_array($res)) {
			if (!strlen($row['subject'])) {
				$row['subject'] = strip_tags(utf8_substr($row['message'],40));
			}
			c('<tr class="row1">');
			$to_user = url('u/'.$row['username'],htmlspecialchars($row['username']));
			c('<td width="10"><input type="checkbox" name="ids[]" value="'.$row['id'].'" /></td>');
			c('<td width="90" nowrap>'.$to_user.'</td>');
			c('<td>'.url('message/viewsent/'.$row['id'], htmlspecialchars($row['subject'])).'</td>');
			c('<td width="150">'.get_date($row['created']).'</td>');
			c('</tr>');
		}
		c('<tr class="row2"><td colspan="4">
		<input type="checkbox" name="allbox" id="check_uncheck" onclick="js_check_all(this.form)" /><label for="check_uncheck">'.t('Check/ Uncheck all').'</label>
		<input type="submit" value="'.t('Delete').'" '.cfm().'/></td></tr>');
		c('</form></table>');

		// pager
		$res = sql_query("select count(*) as total from `".tb()."messages_sent` where from_id='{$client['id']}'");
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = $ubase.$this->name.'/outbox';
		$pagebar  = $pb->whole_num_bar();
		c($pagebar);
	}

	function compose($mid=0) {
		global $content, $db, $nav, $client, $captcha;
		limit_posting();
		set_title('Compose message');
		clear_as();
		$nav[] = url('message','Message');
		$nav[] = t('Compose a message');
		if ($_POST['step'] == 'post') {
			$error = '';
			limit_posting();
			if (!$_POST['uid'] || !$_POST['message']) {
				$error = t('Please fill all the required blank');
			}
			if(!$user = valid_user($_POST['uid'])) {
				$error = t('Invalid username');
			}
			if ($this->load_recaptcha($user['id'])) {
				$resp = recaptcha_check_answer ($captcha['privatekey'],
												$_SERVER["REMOTE_ADDR"],
												$_POST["recaptcha_challenge_field"],
												$_POST["recaptcha_response_field"]);

				if (!$resp->is_valid) {
						c('<script language="javascript" >
				$(document).ready( function(){
									$("#recaptcha_response_field").focus();
			});
									</script>');
						$captchaerror = $resp->error;
						$error = 'Incorrect reCaptcha';
				}
			}
			if (!strlen($error)) {
				$timeline = time();
				if ($res = sql_query("insert into `".tb()."messages` (from_id,to_id,subject,message,created) values('{$client['id']}','{$user['id']}','".$_POST['subject']."','".$_POST['message']."',$timeline)")) {
					sql_query("insert into `".tb()."messages_sent` (from_id,to_id,subject,message,created) values('{$client['id']}','{$user['id']}','".$_POST['subject']."','".$_POST['message']."',$timeline)");
					$mid = mysql_insert_id();
					mail_notice('message',$user['username'],t('You have a new PM from {1}',$client['username']),t('You have a new PM from {1}',$client['fullname']) );
					record_this_posting($_POST['message']);
				}
				redirect('message/outbox',1);
			}
			else {
				sys_notice(h($error));
			}
		}
		if (is_numeric($mid)) {
			$res = sql_query("SELECT m.subject,m.message,u.username,u.id as uid from `".tb()."messages` as m LEFT JOIN `".tb()."accounts` as u on u.id=m.from_id where m.id='$mid' and m.to_id='{$client['id']}' ");
			$message = sql_fetch_array($res);
			if (!preg_match("/^Re/",$message['subject'])) {
				if (!strlen($message['subject'])) {
					$message['subject'] = strip_tags(utf8_substr($message['message'],40));
				}
				$message['subject'] = 'Re:'.htmlspecialchars($message['subject']);
			}
			$msg = "\r\n\r\n ---".$message['username']." wrote --- \r\n".h($message['message']);
			$uid = $message['uid'];
		}
		elseif (preg_match("/^u/i",$mid)) {
			$uid = str_replace('u','',$mid);
			if (is_numeric($uid)) {
				$res = sql_query("select username from `".tb()."accounts` where id=$uid");
				$message = sql_fetch_array($res);
			}
		}
		else {
			die('no act');
		}
		if (strlen($_POST['message'])) {
			$msg = h($_POST['message']);
		}
		if (strlen($_POST['subject'])) {
			$message['subject'] = h($_POST['subject']);
		}
		$res = sql_query("select * from `".tb()."blacks` where bid={$client['id']} and uid={$uid} ");
		if (sql_counts($res)) {
			c(t('This user has blocked you'));
		}
		else {
			c('<form method="post" action="'.url('message/compose/u'.$uid).'" >
					<p>
					'.label(t('Send to')).'
					<input type="text" value="'.htmlspecialchars($message['username']).' '.htmlspecialchars($message['lastname']).'" disabled />
					</p>
					<p>
					'.label(t('Subject').' ('.t('Optional').')').'
					<input type="text" name="subject" size="55" value="'.$message['subject'].'"/>
					</p>
					<p>
					'.label(t('Message')).'
					<textarea name="message" style="width:680px" rows="15">'.$msg.'</textarea>
					</p>');
			if ($this->load_recaptcha($uid)) {
				c('<p>'. recaptcha_get_html($captcha['publickey'],$captchaerror).'</p>');
			}
					c('
					<p>
					<input type="hidden" name="step" value="post" />
					<input type="hidden" name="uid" value="'.$uid.'" />
					<input class="button" type="submit" value="'.t('Send').'" />
					</p>
					</form>');
		}
	}

	function load_recaptcha($uid=0) {
		global $client;
		if (!get_gvar('disable_recaptcha_pm')) {
			$res = sql_query("select * from ".tb()."messages_sent where from_id='$uid' and to_id='{$client['id']}'");
			if (sql_counts($res)) {
				return false;
			}
			$res = sql_query("select * from ".tb()."friends where uid='$uid' and fid='{$client['id']}'");
			if (sql_counts($res)) {
				return false;
			}
			return true;
		}
		else {
			return false;
		}
	}

	function delete($mid) {
		global $db, $client;
		// ids
		if (is_array($_REQUEST['ids'])) {
			foreach ($_REQUEST['ids'] as $id) {
				sql_query("delete from `".tb()."messages` where id='{$id}' and to_id='{$client['id']}' ");
			}
		}
		else {
			sql_query("delete from `".tb()."messages` where id='{$mid}' and to_id='{$client['id']}' ");
		}
		redirect(url('message/inbox'),1);
	}

	function deletesent($mid) {
		global $db, $client;
		// ids
		if (is_array($_REQUEST['ids'])) {
			foreach ($_REQUEST['ids'] as $id) {
				sql_query("delete from `".tb()."messages_sent` where id='{$id}' and from_id='{$client['id']}' ");
			}
		}
		else {
			sql_query("delete from `".tb()."messages_sent` where id='{$mid}' and from_id='{$client['id']}' ");
		}
		redirect(url('message/outbox'),1);
	}
}