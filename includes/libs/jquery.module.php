<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class jquery {
	function jquery() {

	}
	
	function index() {
		exit;
	}

	function ratestory() {
		global $client;
		if (!$client['id']) {
			die('need login');
		}
		$storyid = $_POST['sid'];
		$rate = $_POST['rate'];
		$rate = explode('&',$rate);
		foreach ($rate as $val) {
			$rate2 = explode('=',$val);
			if (is_numeric($rate2[1]) && $rate2[1] > 0) {
				$key = $rate2[0];
				$rvote[$key] = $rate2[1];
			}
		}
		if (!is_numeric($storyid)) {
			die('wrong sid');
		}
		$res = sql_query("select id,digg,dugg,rating from `".tb()."stories` where id='$storyid' ");
		$story = sql_fetch_array($res);
		if (!$story['id']) die('wrong sid');
		$ratings = unserialize($story['rating']);
		if (!is_array($ratings)) die('not array');
		$digg = $i = 0;
		foreach($ratings as $key=>$rating) {
			if ($rvote[$key] > 0) {
				$ratings[$key]['score'] = $ratings[$key]['score'] + $rvote[$key];
				$ratings[$key]['users'] = $ratings[$key]['users'] + 1;
				$digg = $digg + $rvote[$key];
				$i++;
			}
		}
		$ratings = serialize($ratings);
		if ($digg) {
			$story['digg'] = $story['digg'] + ceil($digg/$i);
			$story['dugg'] = $story['dugg'] + 1;
		}
		
		// 是否已经投过票
		$res = sql_query("select * from `".tb()."votes` where uid='{$client['id']}' and sid='{$storyid}' limit 1");
		if (!sql_counts($res)) {
			sql_query("insert into `".tb()."votes` (sid,uid,rate,created) values ('{$storyid}','{$client['id']}','$rate',".time().")");
			sql_query("update `".tb()."stories` set digg='{$story['digg']}',dugg='{$story['dugg']}',rating='$ratings' where id='{$storyid}'");
		}
		else {
			die('already voted');
		}
		echo $story['dugg'];
		ss_update();
		exit;
	}
	
	function update_status() {
		global $client;
		if (!$client['id']) {
			die('need login');
		}
		$q = "INSERT INTO `".tb()."acts` (uid,act,created,title) VALUES({$client['id']},'status',".time().",'{$_POST['statusinput']}')";
		if(sql_query($q)) {
			echo '{"status":"'.$_POST['statusinput'].'"}';
		}
		ss_update();
	}

	function comments() {
		global $num_per_page;
		$storyid = $_POST['sid'];
		if (!is_numeric($storyid)) die('bad sid');
		$res = sql_query("select * from ".tb()."stories where id='$storyid' ");
		$story = sql_fetch_array($res);
		if (!$story['id']) die('wrong sid');

		$i = 0;
		$total = $story['comments'];
		$comments_head = '<div id="comments_head"></div>';
		$res = sql_query("select c.*,u.avatar,u.signature,u.username from `".tb()."story_comments` as c left join `".tb()."accounts` as u on u.id=c.uid where c.sid='$storyid' ORDER BY c.id DESC LIMIT 0,$num_per_page");
		while ($row = sql_fetch_array($res)) {
			// list in ul
			//$row['num'] = $offset + $i;
			$comments .= user_post($row);
			$i++;
		}
		if ($i == 0) {
			$comments .= '<i>None</i><br /><br />';
		}
		else {
			$num = '1 ~ '.$i;
		}
		$comments .= '<div id="comments_foot"></div>';
		echo $comments_head.$num.$comments;
		ss_update();
		exit;
	}

	function comments_more() {
		global $num_per_page;
		$page = $_POST['apage'];
		$offset = ($page-1)*$num_per_page;
		$storyid = $_POST['sid'];
		if (!is_numeric($storyid)) die('bad sid');
		$res = sql_query("select * from ".tb()."stories where id='$storyid' ");
		$story = sql_fetch_array($res);
		if (!$story['id']) die('wrong sid');

		$i = $offset;
		$total = $story['comments'];
		$comments = '<div id="comments_head"></div>';
		$res = sql_query("select c.*,u.avatar,u.signature,u.username from `".tb()."story_comments` as c left join `".tb()."accounts` as u on u.id=c.uid where c.sid='$storyid' ORDER BY c.id DESC LIMIT $offset,$num_per_page");
		while ($row = sql_fetch_array($res)) {
			// list in ul
			//$row['num'] = $offset + $i;
			$comments .= user_post($row);
			$i++;
		}
		echo ($offset+1).' ~ '.$i.$comments;
		exit;
	}

	function comment_add() {
		GLOBAL $db,$client,$ubase;
		if (!$client['id']) {
			die('need login');
		}
		$timeline = time();
		$res = sql_query("select * from ".tb()."stories where id='{$_POST['sid']}' ");
		$story = sql_fetch_array($res);
		if (!$story['id']) {
			die('wrong sid');
		}
		if ($story['closed']) {
			die('topic closed');
		}
		if (strlen($_POST['form_content']) < 2) {
			die(t('Your message is too short'));
		}
		if ($res = sql_query("insert into `".tb()."story_comments` (sid,content,uid,cid,created,app,target_uid,vote) VALUES ('".$_POST['sid']."','".$_POST['form_content']."','{$client['id']}','{$story['cid']}',$timeline,'{$story['app']}','{$story['uid']}','{$_POST['vote']}')")) {
			sql_query("update `".tb()."stories` set comments=comments+1,lastreply=".time().",lastreplyuname='{$client['uname']}',lastreplyuid={$client['id']} where id='".$_POST['sid']."'");
		}
		$row = array(
			'avatar'=>$client['avatar'],
			'content' => nl2br(htmlspecialchars($_POST['form_content'])),
			'username' => $client['username'],
			'created' => time()
		);
		echo user_post($row);
		exit;
	}

	function favoriteadd() {
		global $client;
		if (!$client['id']) {
			die('need login');
		}
		$storyid = $_POST['sid'];
		$res = sql_query("select * from `".tb()."stories` where id='$storyid' ");
		$story = sql_fetch_array($res);
		if (!$story['id']) die('wrong sid');
		$res = sql_query("select * from ".tb()."favorites where fsid='$storyid' and uid='{$client['id']}'");
		$row = sql_fetch_array($res);
		if ($row['id']) die('Found in listings');
		$favorite = array(
			'uid' => $client['id'],
			'fuid' => $story['uid'],
			'fapp' => $story['app'],
			'fsid' => $storyid,
			'title' => addslashes($story['title']),
			'created' => time()
			);
		sql_insert($favorite, tb().'favorites');
		echo 'Saved';
		exit;
	}

	function stream_publish() {
		global $client, $config;
		if (!$client['id']) die('please login first');
		limit_posting(0,1);
			if (strlen($_POST['message'])<4) die('failed! message too short');
			$_POST['message'] = utf8_substr($_POST['message'],200);
			$_POST['message'] = parseurl($_POST['message']);
			$url_search = array(            
				"/\[url]www.([^'\"]*)\[\/url]/iU",
				"/\[url]([^'\"]*)\[\/url]/iU",
				"/\[url=www.([^'\"\s]*)](.*)\[\/url]/iU",
				"/\[url=([^'\"\s]*)](.*)\[\/url]/iU",
			);
			$url_replace = array(
				"<a href=\"http://www.\\1\" target=\"_blank\" rel=\"nofollow\">www.\\1</a>",
				"<a href=\"\\1\" target=\"_blank\" rel=\"nofollow\">\\1</a>",
				"<a href=\"http://www.\\1\" target=\"_blank\" rel=\"nofollow\">\\2</a>",
				"<a href=\"\\1\" target=\"_blank\" rel=\"nofollow\">\\2</a>"
				);
			$stream_id = stream_publish(preg_replace($url_search,$url_replace, h($_POST['message']) ),$attachment,$app,$client['id'],$_POST['page_id']);
			$arr = array(
				'id'=>$stream_id,'fullname'=>$client['fullname'],'avatar'=>$client['avatar'],'message'=>decode_bb(h(stripslashes($_POST['message']))),'attachment'=>$attachment,'username'=>$client['uname'],'created'=>time()
				);
			echo stream_display($arr,'',1);
			ss_update();
		
		exit;
	}

	function stream_delete($storyid) {
		global $client, $config;
		if (!$client['id']) die('please login first');
		$res = sql_query("select * from ".tb()."streams where id='$storyid'");
		$stream = sql_fetch_array($res);
		if (!$stream['id']) die('wrong sid');
		if ($stream['uid'] == $client['id'] || in_array('3',$client['roles']) ) {
			sql_query("update ".tb()."streams set hide=1 where id='{$stream['id']}'");
			echo 'ok';
		}
		else {
			echo 'access denied';
		}
		exit;
	}

	function comment_publish() {
		global $client, $config;
		if (!$client['id']) die('<div class="ferror">please login first</div>');
		if ( !preg_match("/^[0-9a-z]+$/i",$_POST['target_id']) ) die('no target id');
		if (strlen($_POST['message'])<4) die('<div class="ferror">failed! message too short</div>');
		limit_posting(0,1);

		$_POST['message'] = utf8_substr($_POST['message'],140);
		$_POST['message'] = parseurl($_POST['message']);
		$url_search = array(            
			"/\[url]www.([^'\"]*)\[\/url]/iU",
			"/\[url]([^'\"]*)\[\/url]/iU",
			"/\[url=www.([^'\"\s]*)](.*)\[\/url]/iU",
			"/\[url=([^'\"\s]*)](.*)\[\/url]/iU",
		);
		$url_replace = array(
			"<a href=\"http://www.\\1\" target=\"_blank\" rel=\"nofollow\">www.\\1</a>",
			"<a href=\"\\1\" target=\"_blank\" rel=\"nofollow\">\\1</a>",
			"<a href=\"http://www.\\1\" target=\"_blank\" rel=\"nofollow\">\\2</a>",
			"<a href=\"\\1\" target=\"_blank\" rel=\"nofollow\">\\2</a>"
			);
		$message = preg_replace($url_search,$url_replace, h($_POST['message']));
		if ($cid = comment_publish($_POST['target_id'],$message
			)) {
			$arr = array(
				'avatar'=>$client['avatar'],'message'=>stripslashes($message),'username'=>$client['uname'],'created'=>time()
				);
			echo comment_display($arr);
		}
		exit;
	}

	function dolike() {
		global $client, $config;
		if (!$client['id']) die('<div class="ferror">please login first</div>');
		if ( !preg_match("/^[0-9a-z]+$/i",$_POST['target_id']) ) die('no target id');
		if (!is_numeric($_POST['target_id'])) {
			die('wrong target id');
		}
		limit_posting(0,1);
		$res = sql_query("select * from ".tb()."liked where stream_id='{$_POST['target_id']}' and uid='{$client['id']}' limit 1");
		if (sql_counts($res)) {
			sql_query("delete from ".tb()."liked where stream_id='{$_POST['target_id']}' and uid='{$client['id']}'");
			sql_query("update ".tb()."streams set likes=likes-1 where id='{$_POST['target_id']}'");
			echo t('Unliked').'<br />';
		}
		else {
			$like = array('uid'=>$client['id'],'stream_id'=>$_POST['target_id']);
			sql_insert($like,tb().'liked');
			sql_query("update ".tb()."streams set likes=likes+1 where id='{$_POST['target_id']}'");
			echo t('Liked').'<br />';
		}
		exit;
	}

	function dodislike() {
		global $client, $config;
		if (!$client['id']) die('<div class="ferror">please login first</div>');
		if ( !preg_match("/^[0-9a-z]+$/i",$_POST['target_id']) ) die('no target id');
		if (!is_numeric($_POST['target_id'])) {
			die('wrong target id');
		}
		limit_posting(0,1);
		$res = sql_query("select * from ".tb()."disliked where stream_id='{$_POST['target_id']}' and uid='{$client['id']}' limit 1");
		if (sql_counts($res)) {
			sql_query("delete from ".tb()."disliked where stream_id='{$_POST['target_id']}' and uid='{$client['id']}'");
			sql_query("update ".tb()."streams set dislikes=dislikes-1 where id='{$_POST['target_id']}'");
			echo t('Un-disliked').'<br />';
		}
		else {
			$like = array('uid'=>$client['id'],'stream_id'=>$_POST['target_id']);
			sql_insert($like,tb().'disliked');
			sql_query("update ".tb()."streams set dislikes=dislikes+1 where id='{$_POST['target_id']}'");
			echo t('Disliked').'<br />';
		}

		exit;
	}

	function wholike($stream_id=0,$offset=0) {
		if (!is_numeric($stream_id)) die();
		if (!$offset) $offset = 0;
		$num = 8;
		$res = sql_query("select u.* from ".tb()."liked as l left join ".tb()."accounts as u on u.id=l.uid where stream_id='$stream_id' limit $offset,".($num+1));
		$i=1;
		while ($user = sql_fetch_array($res)) {
			if ($i>$num) {
				$got_more = 1;
			}
			else {
				$users[] = $user;
			}
			$i++;
		}
		echo $this->display_users($users);
		if ($got_more) {
			$offset = $offset + $num;
			echo '<hr />
			<a href="#" onclick="jQuery.facebox({ ajax: \''.url('jquery/wholike/'.$stream_id.'/'.$offset).'\' });return false;" >'.t('More..').'</a>';
		}
		exit;
	}
	function whodislike($stream_id=0,$offset=0) {
		if (!is_numeric($stream_id)) die();
		if (!$offset) $offset = 0;
		$num = 8;
		$res = sql_query("select u.* from ".tb()."disliked as l left join ".tb()."accounts as u on u.id=l.uid where stream_id='$stream_id' limit $offset,".($num+1));
		$i=1;
		while ($user = sql_fetch_array($res)) {
			if ($i>$num) {
				$got_more = 1;
			}
			else {
				$users[] = $user;
			}
			$i++;
		}
		echo $this->display_users($users);
		if ($got_more) {
			$offset = $offset + $num;
			echo '<hr />
			<a href="#" onclick="jQuery.facebox({ ajax: \''.url('jquery/whodislike/'.$stream_id.'/'.$offset).'\' });return false;" rel="facebox">'.t('More..').'</a>';
		}
		exit;
	}

	function allcomments($stream_id=0,$offset=0) {
		if (!is_numeric($stream_id)) die();
		if (!$offset) $offset = 0;
		$num = 12;
		$more = $num+1;
		$res = sql_query("select c.*,u.username,u.avatar from ".tb()."comments as c left join ".tb()."accounts as u on u.id=c.uid where c.stream_id='{$stream_id}' order by id desc limit $offset,".$more);
		$i=1;
		while($row = sql_fetch_array($res)) {
			if ($i <=$num) {
				$comments .= comment_display($row);
			}
			$i++;
		}
		echo $comments;
		if ($i>$num) {
			$offset = $offset + $num;
			echo '<hr />
			<a href="#" onclick="jQuery.facebox({ ajax: \''.url('jquery/allcomments/'.$stream_id.'/'.$offset).'\' });return false;" >'.t('More..').'</a>';
		}
		exit;
	}

	private function display_users($users) {
		if (is_array($users)) {
			foreach ($users as $user) {
				if (!strlen($user['fullname'])) $user['fullname'] = $user['username'];
				$output .= '<li>'.avatar($user).'<br />'.url('u/'.$user['username'],h($user['username'])).'</li>';
			}
		}
		return '
		<ul class="small_avatars">'.$output.'</ul>';
	}

	function profile_comment_publish() {
		global $client, $config;
		if (!$client['id']) die('please login first');
		limit_posting(0,1);
		if (!is_numeric($_POST['target_id'])) die('no target id');
		$user = valid_user($_POST['target_id']);
		if (!$user['id']) die('bad uid');
		if (strlen($_POST['message'])<4) die('failed! message too short');
		$id = profile_comment_publish($_POST['target_id'],$_POST['message']);
		$msg = 'Hi<br /><strong>'.$client['uname'].'</strong> left a comment to your profile.<br />
		Click '.url('u/'.$user['username'],'HERE').' to view the comment.';
		//jcow_mail($user['email'],$client['uname'].' left a comment to your profile',$msg);
		send_note($user['id'],t('{1} left a comment to {2}',name2profile($client['uname']),url('u/'.$user['username'],t('your profile'))) );
		mail_notice('wall_post',$user['username'],t('{1} left a comment to your profile',$client['username']),t('{1} left a comment to your profile',$client['username']) );
		// write act
		$attachment = array(
			'url' => url('u/'.$user['username']),
			'des' => utf8_substr($_POST['message'],180)
			);
		$app = array('name'=>'pcomment','id'=>$id);
		$act = t('{1} left a comment to {2}','',name2profile($user['username']) );
		$stream_id = stream_publish($act,$attachment,$app);
		sql_query("update ".tb()."profile_comments set stream_id='$stream_id' where id='$id'");

		$arr = array(
			'id'=>$id,'avatar'=>$client['avatar'],'message'=>stripslashes($_POST['message']),'username'=>$client['uname'],'created'=>time(),'stream_id'=>$stream_id
			);
		echo profile_comment_display($arr,1);
		ss_update();
		exit;
	}

	function morestream() {
		echo stream_get($_POST['page_id'],7,$_POST['offset'],$_POST['target_id']);
		exit;
	}
	function moreactivities() {
		if (preg_match("/_/",$_POST['uid'])) {
			$uid = explode('_',$_POST['uid']);
		}
		else
			$uid = $_POST['uid'];
		echo activity_get($uid,7,$_POST['offset'],$_POST['target_id']);
		echo '<script>jcow_ajax_loaded();</script>';
		exit;
	}

	function morelikestream() {
		echo stream_get($_POST['uid'],10,$_POST['offset']);
		exit;
	}

	function moreprofilecomment() {
		echo profile_comment_get($_POST['uid'],5,$_POST['offset']);
		exit;
	}
	

	function translate() {
		global $client;
		if (!allow_access(3)) die('access denied');
		sql_query("update `".tb()."langs` set lang_to='{$_POST['tto']}' where lang_from='{$_POST['tfrom']}' and lang='{$client['lang']}' ");
		ss_update();
		echo 'saved';
	}

	function jcow_update_new() {
		global $client;
		if (!$client['id']) die();
		$res = sql_query("select count(*) as num from `".tb()."messages` where to_id='{$client['id']}' and from_id>0 and !hasread");
		$row = sql_fetch_array($res);
		$msg_new = $row['num'] ? '('.$row['num'].')' : '';

		$res = sql_query("select count(*) as num from `".tb()."messages` where to_id='{$client['id']}' and from_id=0 and !hasread");
		$row = sql_fetch_array($res);
		$note_new = $row['num'] ? '('.$row['num'].')' : '';

		$res = sql_query("select count(*) as num from `".tb()."friend_reqs` where fid='{$client['id']}'");
		$row = sql_fetch_array($res);
		$frd_new = $row['num'] ? '('.$row['num'].')' : '';
		if ($row['num']) {
			$frd_link = url('friends/requests');
		}
		else {
			$frd_link = url('friends');
		}

		echo '{
		  "msg_new": "'.$msg_new.'",
		  "note_new": "'.$note_new.'",
		  "frd_new": "'.$frd_new.'",
		  "frd_link": "'.$frd_link.'"
		}
		';
		exit;
	}

}

function valid_youtube_id($id) {
	if (!$data = @file_get_contents("http://gdata.youtube.com/feeds/api/videos/".$id)) {
		return false;
	}
	else {
		if (!preg_match("/xml/i",$data)) {
			return false;
		}
	}
	return true;
}