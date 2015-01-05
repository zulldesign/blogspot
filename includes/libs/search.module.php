<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class search{
	
	function index() {
		c('
		<form action="'.url('search/listing').'" method="post">
		<p>
		<label>title</label> <input type="text" name="title" />
		</p>
		<p>
		<input type="submit" class="button" value="'.t('Submit').'" />
		</p>
		</form>
		');
	}
	
	function listing() {
		global $offset, $num_per_page, $page;
		$title = trim($_POST['title']);
		$display_title = stripslashes(trim($_POST['title']));
		c('Searching <strong>'.h($title).'</strong><br />');
		if (strlen($title) < 3) {
			sys_back(t('Keyword is too short'));
		}
		else {
			$hash = substr(md5($title),0,12);
			if ($c = get_cache($hash)) {
				header("location:".url('search/result/'.$hash));
				exit;
			}
			else {
				$c = '<h1>'.h($display_title).'</h1>';
				$res = sql_query("select * from ".tb()."accounts where username like '%{$title}%'  order by lastlogin DESC limit 10");
				while ($user = sql_fetch_array($res)) {
					$users .= '<li>'.url('u/'.$user['username'],$user['username']).'<br />'.avatar($user).'<br />'.h($user['username']).'</li>';
				}
				if (strlen($users)) {
					$c .= '<h2>'.t('Members').'</h2>';
					$c .= '<ul class="small_avatars">'.$users.'</ul>';
				}
				$c .= '<h2>'.t('Stories').'</h2>
					<p>Searching for <strong>"'.h($title).'"</strong></p>';
				$res = sql_query("select s.*,u.username from `".tb()."stories` as s left join ".tb()."accounts as u on u.id=s.uid where s.title LIKE '%$title%' and u.disabled!=3 ORDER BY s.id DESC LIMIT 20");
				if (!sql_counts($res)) {
					$c .= '<p>no story matched</p>';
				}
				else {
					$c .= 'Stories:<br /><ul class="post">';
					while($story = sql_fetch_array($res)) {
						$c .= '<li>
						<a href="'.url($story['app'].'/viewstory/'.$story['id']).'">'.str_replace($title,'<strong>'.h($title).'</strong>',htmlspecialchars($story['title'])).'</a><br />'.get_date($story['created']).', by '.url('u/'.$story['username'],$story['username']).'
						</li>';
					}
					$c .= '</ul>';
				}
				set_cache($hash, $c, 48);
				header("location:".url('search/result/'.$hash));
				exit;
			}
		}
	}

	function result($hash) {
		set_title('Search result');
		c(get_cache($hash).get_gvar('ad_block_search'));
	}
	
}
