<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

 
class follow {
	function follow() {
		global $client;
		if (!$client['id']) {
			die('please login');
		}
		set_title('Follow');
	}
	
	function index() {
	}

	function add($uid = 0) {
		global $client;
		if (!$user = valid_user($uid) ) {
			die('wrong uid');
		}
		if ($user['id'] == $client['id']) {
			sys_back(t('You can not follow yourself'));
		}
		$res = sql_query("select * from ".tb()."followers where uid='{$client['id']}' and fid='$uid' limit 1");
		if (sql_counts($res)) {
			sys_back(t('You have followed this user before'));
		}
		$follow['uid'] = $client['id'];
		$follow['fid'] = $uid;
		sql_insert($follow, tb().'followers');
		sql_query("update ".tb()."accounts set followers=followers+1 where id='$uid'");
		send_note($user['id'],url('follow/imfollowing',t('You got a new follower')));
		redirect(url('follow/imfollowing'),1);
	}


	function remove($uid = 0) {
		global $client;
		if (!$user = valid_user($uid) ) {
			die('wrong uid');
		}
		$res = sql_query("select * from ".tb()."followers where uid='{$client['id']}' and fid='$uid' limit 1");
		if (sql_counts($res)) {
			sql_query("delete from ".tb()."followers where uid='{$client['id']}' and fid='$uid'");
			sql_query("update ".tb()."accounts set followers=followers-1 where id='$uid'");
		}
		redirect(url('follow/imfollowing'),1);
	}

	function myfollowers() {
		global $client, $apps, $uhome,$ubase, $offset, $num_per_page, $page;
		nav(t('My Followers'));
		c('
		<ul class="small_avatars">');
		$res = sql_query("select u.id,u.username,u.avatar from ".tb()."followers as f left join ".tb()."accounts as u on u.id=f.uid where f.fid='{$client['id']}' order by u.lastlogin DESC limit $offset, $num_per_page");
		$output = '';
		while ($row = sql_fetch_array($res)) {
			$f = 1;
			c('<li><span>'.url('u/'.$row['username'],$row['username']).'</span><br />
			'.avatar($row).'</li>');
		}
		c('</ul>');
		// pager
		$res = sql_query("select count(*) as total from `".tb()."followers` where fid='{$client['id']}' ".dbhold() );
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = $ubase.'follow/myfollowers';
		$pagebar  = $pb->whole_num_bar();
		$output .= $pagebar;
		c($output);
		if (!$total) {
			c('<p>'.t('You have no follower.').'</p>');
		}
		section_close(t('My Followers'));
	}

	function imfollowing() {
		global $client, $apps, $uhome,$ubase, $offset, $num_per_page, $page;
		nav(t("Im Following"));
		c('
		<ul class="small_avatars">');
		$res = sql_query("select u.id,u.username,u.avatar from ".tb()."followers as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$client['id']}' order by u.lastlogin DESC limit $offset, $num_per_page");
		$output = '';
		while ($row = sql_fetch_array($res)) {
			$f = 1;
			c('<li><span>'.url('u/'.$row['username'],$row['username']).'</span><br />
			'.avatar($row).'<br />
			'.url('follow/remove/'.$row['id'],t('Unfollow')).'</li>');
		}
		c('</ul>');
		// pager
		$res = sql_query("select count(*) as total from `".tb()."followers` where uid='{$client['id']}' ".dbhold() );
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = $ubase.'follow/myfollowers';
		$pagebar  = $pb->whole_num_bar();
		$output .= $pagebar;
		c($output);
		if (!$total) {
			c('<p>'.t('You are not following anyone.').'</p>');
		}
		section_close(t('Im Following'));
	}
}