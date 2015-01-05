<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class u extends jcow_pages {
	function u() {
		$this->type = 'u';
	}

	function tab_menu($owner,$page=array()) {
		return array(
			array('path'=>'u/'.$owner['username'], 'name'=>t('Wall')),
			array('path'=>'u/'.$owner['username'].'/liked', 'name'=>t('Liked'))
			);
	}

	function show_sidebar($page,$owner) {
		global $client;
		if ($client['id']) {
			$res = sql_query("select * from `".tb()."blacks` where uid={$client['id']} and bid={$owner['id']} ");
			if (sql_counts($res)) {
				$ublock = url('blacklist/remove/'.$owner['id'],t('Unblock'));
			}
			else {
				$ublock = url('blacklist/add/'.$owner['id'],t('Block'));
			}

			$res = sql_query("select * from ".tb()."followers where uid='{$client['id']}' and fid='{$owner['id']}' limit 1");
			if (!sql_counts($res)) {
				$follow_url = url('follow/add/'.$owner['id'],t('Follow'));
			}
			else {
				$follow_url = url('follow/remove/'.$owner['id'],t('Unfollow'));
			}
		}
		else {
			$follow_url = url('member/login/1',t('Follow') );
		}
		if ($owner['online']) {
			$onoff = '<img src="'.uhome().'/files/icons/online.gif" /><br />';
		}
		else {
			$onoff = '<img src="'.uhome().'/files/icons/offline.gif" /><br />';
		}
		if (!$owner['birthyear']) {
			$age = ' - ';
		}
		else {
			$age = (date("Y",time()) - $owner['birthyear']);
			if ($owner['birthmonth'] > date("m",time()) || 
				($owner['birthmonth'] == date("m",time())&& $owner['birthday']>date("d",time()) )
			){
				$age = $age-1;
			}
		}
		if (!$owner['location']) $owner['location'] = ' - ';
		$output = 
		'
		<center>
		<div>'.
			avatar($owner,'normal').'<br />'.t('Last login').':'.get_date($owner['lastlogin']).'
		</div>
		<img src="'.uhome().'/files/icons/favorite.png" /> <strong>'.$owner['followers'].'</strong> '.url('u/'.$owner['username'].'/followers',t('Followers')).'
		</center>
		<ul class="sidebar_buttons">
		<li>'.$follow_url.'</li>
		<li>'.url('message/compose/u'.$owner['id'],t('Message')).'</li>
		<li>'.$ublock.'</li>
		<li><a href="'.url('friends/add/'.$owner['id']).'">'.t('Add friend').'</a></li>';
		if ($client['id'] == $owner['id']) {
			$output .= '<li>'.url('account',t('Edit profile')).'</li>';
		}
		if (allow_access(3)) {
			$output .= '<li>'.url('admin/useredit/'.$owner['id'],'Manage this User').'</li>';
		}
		$output .= '</ul>';
		ass(array('content'=>$output));

		
		// following
		$res = sql_query("select u.id,u.username,u.avatar from ".tb()."followers as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$owner['id']}' order by u.lastlogin DESC limit 20");
		$output = '';
		while ($row = sql_fetch_array($res)) {
			$f = 1;
			$output .= avatar($row,25);
		}
		ass(array('title'=>t('Following'), 'content' => '<div class="toolbar">'.url('u/'.$owner['username'] .'/following',t('See all')).'</div>'.$output));


		ass($this->details($owner));
	}



	function friends($url = 0) {
		global $client, $apps, $uhome,$ubase, $current_sub_menu, $offset, $num_per_page, $page;
		$profile = $this->settabmenu($url, 1,'u');
		$current_sub_menu['href'] = 'u/'.$url.'/friends';
		
		// friends
		$output = '<ul class="small_avatars">';
		$res = sql_query("SELECT u.* FROM `".tb()."friends` as f left join `".tb()."accounts` as u on u.id=f.fid where f.uid={$profile['id']}  ORDER BY f.created DESC LIMIT $offset, $num_per_page");
		while ($row = sql_fetch_array($res)) {
			$output .= '<li>';
			$output .= '<span>'.url('u/'.$row['username'], $row['username']).'</span> '.avatar($row);
			$output .= '</li>';
		}
		$output .= '</ul>';

		// pager
		$res = sql_query("select count(*) as total from `".tb()."friends` where uid='{$profile['id']}' ".dbhold() );
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = $ubase.'u/'.$profile['username'].'/friends';
		$pagebar  = $pb->whole_num_bar();
		$output .= $pagebar;

		section(
			array('title'=>'Friends',
			'content'=>$output)
			);
	}


	function liked($url = 0) {
		global $client, $content, $nav, $apps, $uhome,  $ubase, $offset, $num_per_page, $page,$config, $menuon;
		$profile = $this->settabmenu($url, 1,'u');
		$res = sql_query("select stream_id from ".tb()."liked where uid='{$profile['id']}' order by id DESC limit 10");
		while ($row = sql_fetch_array($res)) {
			$res2 = sql_query("select s.*,u.username,u.avatar from ".tb()."streams as s left join ".tb()."accounts as u on u.id=s.uid left join ".tb()."pages as p on p.id=s.wall_id where s.id='{$row['stream_id']}' and p.type!='group'");
			$stream = sql_fetch_array($res2);
			$stream['attachment'] = unserialize($stream['attachment']);
			$output .= stream_display($stream);
		}
		if (substr_count($output,'user_post_left') > 9) {
			$output .= '
			<div id="morestream_box"></div>
			<div>
			<script>
			$(document).ready(function(){
				$("#morestream_button").click(function() {
					$(this).hide();
					$("#morestream_box").html("<img src=\"'.uhome().'/files/loading.gif\" /> Loading");
					$.post("'.uhome().'/index.php?p=jquery/morelikestream",
								{offset:$("#stream_offset").val(),uid:'.$profile['id'].'},
								  function(data){
									var currentVal = parseInt( $("#stream_offset").val() );
									$("#stream_offset").val(currentVal + 10);
									$("#morestream_box").before(data);
									if (data) {
										$("#morestream_button").show();
									}
									$("#morestream_box").html("");
									},"html"
								);
					return false;
				});
			});
			</script>

			<input type="hidden" id="stream_offset" value="10" />
			<a href="#" id="morestream_button"><strong>'.t('See More').'</strong></a>
			</div>';
		}

		$current_sub_menu['href'] = 'u/'.$profile['username'].'/liked';
		section(array('title'=>t('Liked'),'content'=>$output)
			);

	}





	function following($url) {
		global $client, $apps, $uhome,$ubase, $offset, $num_per_page, $page;
		if (!preg_match("/[0-9a-z]+/i",$url)) {
			die('wrong uid');
		}
		$profile = $this->settabmenu($url, 1,'u');
		if (!$profile['id']) die('bad uid');
		$res = sql_query("select u.id,u.username,u.avatar from ".tb()."followers as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$profile['id']}' order by u.lastlogin DESC limit $offset, $num_per_page");
		$output = '';
		while ($row = sql_fetch_array($res)) {
			$f = 1;
			$output .= avatar($row);
		}
		// pager
		$res = sql_query("select count(*) as total from `".tb()."followers` where uid='{$profile['id']}' ".dbhold() );
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = $ubase.'u/'.$profile['username'].'/following';
		$pagebar  = $pb->whole_num_bar();
		$output .= $pagebar;
		section(array('title'=>'Following','content'=>$output));
	}

	function followers($url) {
		global $client, $apps, $uhome,$ubase, $offset, $num_per_page, $page;
		if (!preg_match("/[0-9a-z]+/i",$url)) {
			die('wrong uid');
		}
		$profile = $this->settabmenu($url, 1,'u');
		if (!$profile['id']) die('bad uid');
		$res = sql_query("select u.id,u.username,u.avatar from ".tb()."followers as f left join ".tb()."accounts as u on u.id=f.uid where f.fid='{$profile['id']}' order by u.lastlogin DESC limit $offset, $num_per_page");
		$output = '';
		while ($row = sql_fetch_array($res)) {
			$f = 1;
			$output .= avatar($row);
		}
		// pager
		$res = sql_query("select count(*) as total from `".tb()."followers` where fid='{$profile['id']}' ".dbhold() );
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = $ubase.'u/'.$profile['username'].'/followers';
		$pagebar  = $pb->whole_num_bar();
		$output .= $pagebar;
		section(array('title'=>'Followers','content'=>$output));
	}

}
