<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class friends{
	
	function friends() {
		global $tab_menu, $client, $menuon;
		$menuon = 'friends';
		if (!$client['id']) {
			redirect('member/login/1');
		}
		set_title('Friends');
		$tab_menu = array();
		$tab_menu[] = array('path'=>'friends', 'name'=>t('Friends'));
		$tab_menu[] = array('path'=>'friends/requests', 'name'=>t('Friend requests'));
	}
	
	function my() {
		global $client;
		if ($client['id']) {
			redirect('friends/listing/'.$client['id']);
		}
		else {
			redirect('home');
		}
	}
	
	function index() {
		global $db, $client, $offset, $num_per_page, $page, $ubase, $nav, $current_sub_menu;
		$uid = $client['id'];
		//ass(friends_box());
		$current_sub_menu['href'] = 'friends';
		$nav[] = t('My friends');

		$res = sql_query("SELECT u.* FROM `".tb()."friends` as f left join `".tb()."accounts` as u on u.id=f.fid where f.uid={$uid}  ORDER BY f.created DESC LIMIT $offset,$num_per_page");
		c('<ul class="gallery">');
		while ($row = sql_fetch_array($res)) {
			c('<li>');
			c('<span>'.url('u/'.$row['username'], $row['username']).'</span> '.avatar($row));
			c('<br />'.url('friends/delete/'.$row['id'],t('Remove')));
			c('</li>');
		}
		c('</ul>');

		// pager
		$res = sql_query("select count(*) as total from `".tb()."friends` where uid='{$uid}'");
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = url('friends/index');
		$pagebar  = $pb->whole_num_bar();
		c($pagebar);
	}
	
	function delete($fid) {
		global $client, $ubase;
		sql_query("delete from `".tb()."friends` where uid={$client['id']} and fid='$fid' ");
		sql_query("delete from `".tb()."friends` where uid='$fid' and fid={$client['id']} ");
		redirect($ubase.'friends');
	}
	
	function add($uid) {
		global $db, $client, $offset, $num_per_page, $page, $ubase, $captcha;
		nav('Add a friend');
		if(!$user = valid_user($uid)) {
			sys_back('wrong uid');
		}
		
		if ($user['id'] == $client['id']) {
			sys_back('you can not add yourself');
		}
		if ($_POST['step'] == 'post') {
			$error = '';
			if ($user['id'] == $client['id']) {
				$error = 'you cannot add yourself';
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
			$res = sql_query("select * from `".tb()."friends` where uid={$client['id']} and fid={$user['id']} ");
			if (sql_counts($res)) {
				$error = 'This user is already in your friend listings';
			}
			if (!strlen($error)) {
				// do add
				$res = sql_query("select * from `".tb()."friend_reqs` where uid={$client['id']} and fid={$user['id']} ");
				if (!sql_counts($res)) {
					sql_query("insert into `".tb()."friend_reqs` (uid,fid,created,msg) values ({$client['id']},{$user['id']},".time().",'{$_POST['msg']}')");
				}
				else {
					sql_query("update `".tb()."friend_reqs` set created=".time().",msg='{$_POST['msg']}' where uid={$client['id']} and fid={$user['id']} ");
				}
				mail_notice('friend_request',$user['username'],t('{1} wants to be friends with you',$client['fullname']),t('{1} wants to be friends with you',$client['fullname']) );
				redirect(url('friends/requests'),t('Your request has been sent successfully'));
			}
			else {
				sys_notice(h($error));
			}
		}
		$res = sql_query("select * from `".tb()."friends` where uid={$client['id']} and fid={$user['id']} ");
		if (sql_counts($res)) {
			sys_back('This user is already in your friend listings');
		}
		c('<p>'.t('Adding {1} {2} as friend',url('u/'.$user['username'],$user['username'])).'</p>');
		$res = sql_query("select * from `".tb()."blacks` where bid={$client['id']} and uid={$user['id']} ");
		if (sql_counts($res)) {
			c(t('This user has blocked you'));
		}
		else {
			c('
					<form method="post" name="form1" action="'.url('friends/add/'.$uid).'"  enctype="multipart/form-data">
					<p>
					'.label(t('Request message')).'
					<input type="text" name="msg" size="78" />
					<br /><span class="sub">'.t('Why add friend?').'</span>
					</p>');
			if ($this->load_recaptcha($uid)) {
				c('<p>'. recaptcha_get_html($captcha['publickey'],$captchaerror).'</p>');
			}
			c('
					<p>
					<input type="hidden" name="step" value="post" />
					<input type="hidden" name="uid" value="'.$uid.'" />
					<input class="button" type="submit" value="'.t('Send request').'" />
					</p>
					</form>
					');
		}
	}

	function load_recaptcha($uid=0) {
		global $client;
		if (!get_gvar('disable_recaptcha_req')) {
			$res = sql_query("select * from ".tb()."messages_sent where from_id='$uid' and to_id='{$client['id']}'");
			if (sql_counts($res)) {
				return false;
			}
			return true;
		}
		else {
			return false;
		}
	}
	
	
	function requests() {
		global $client, $content, $title, $current_sub_menu;
		//ass(friends_box());
		$current_sub_menu['href'] = 'friends/requests';
		nav('Requests');
		$title = 'Requests';
		$res = sql_query("select count(*) as num from ".tb()."friend_reqs where uid='{$client['id']}' ");
		$row = sql_fetch_array($res);
		c(t('You have {1} pending requests','<strong>'.$row['num'].'</strong>'));
		section_close(t('To others'));


		$res = sql_query("select u.*,r.created as timeline,r.msg from `".tb()."friend_reqs` as r 
									left join `".tb()."accounts` as u on u.id=r.uid
									where r.fid={$client['id']} limit 100");
		if (sql_counts($res)) {
			c( '<ul>');
			while($row = sql_fetch_array($res)) {
				if (strlen($row['username'])) {
					c('
					<div class="post">
				<div class="post_author">'.avatar($row).'<br />
				'.url('u/'.$row['username'],$row['username']).'</div>
				<div class="post_content">
				'.nl2br(htmlspecialchars($row['msg'])).'
				<div class="tab_things">
				<div class="tab_thing">'.url('friends/approve/'.$row['id'],t('Approve')).'</div>
				<div class="tab_thing">'.url('friends/deletes/'.$row['id'],t('Reject')).'</div>
				</div>
				</div>
				</div>');
				}
			}
			c('</ul>');
		}
		else {
			c('Currently no friend requests');
		}
		section_close(t('To you'));
	}
	
	function approve($uid) {
		global $client, $ubase;
		if (!$user = valid_user($uid)) die('wrong uid');
		// 
		$res = sql_query("select * from `".tb()."friend_reqs` where uid='$uid' and fid={$client['id']} ");
		if (sql_counts($res)) {
			$res = sql_query("select * from `".tb()."friends` where uid='$uid' and fid={$client['id']} ");
			// 
			if (!sql_counts($res)) {
				sql_query("insert into `".tb()."friends` (uid,fid,created) values ($uid,{$client['id']},".time().")");
				sql_query("insert into `".tb()."friends` (uid,fid,created) values ({$client['id']},$uid,".time().")");
			}
			// 
			sql_query("delete from `".tb()."friend_reqs` where uid=$uid and fid={$client['id']} ");
			sql_query("delete from `".tb()."friend_reqs` where uid={$client['id']} and fid=$uid ");
			// 
			stream_publish(
				t(
					'became a friend of {1}',
					url('u/'.$user['username'],$user['username'])
				)
			);
			mail_notice('dismail_friend_request_c',$user['username'],t('{1} confirmed your friend request',$client['fullname']),t('{1} confirmed your friend request',$client['fullname']) );
			redirect($ubase.'friends', 1);
		}
	}
	
	function deletes($uid) {
		global $client, $ubase;
		// 确保有这个请求
		$res = sql_query("select * from `".tb()."friend_reqs` where uid='$uid' and fid={$client['id']} ");
		if (sql_counts($res)) {
			// 删除请求
			sql_query("delete from `".tb()."friend_reqs` where uid=$uid and fid={$client['id']} ");
			sql_query("delete from `".tb()."friend_reqs` where uid={$client['id']} and fid=$uid ");
		}
		// 成功
		redirect($ubase.'friends', t('Opration success'));
	}

}

	function friends_box() {
		global $client;
		$res = sql_query("select count(*) as num from `".tb()."friends` where uid='{$client['id']}' ");
		$row = sql_fetch_array($res);
		$content = t('You have {1} friends','<strong>'.url('friends',$row['num']).'</strong>');
		$content .= '<table border="0">
				<tr><td><img src="'.uhome().'/files/icons/invite.gif" /></td><td>'.url('invite',t('Invite friends to join our community')).'</td></tr>
				<tr><td><img src="'.uhome().'/files/icons/browse.gif" /></td><td>'.url('members/listing',t('Browse people')).' </td></tr>
				</table>';
		return array('title'=>t('Friends').' '.url('friends',t('View')), 'content' => $content);
	}