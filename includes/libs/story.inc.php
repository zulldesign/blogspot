<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */
class story{
	var $name = '';
	var $flag = '';
	var $header = '';
	var $footer = '';
	var $cid = 0;
	var $write_story = '';
	var $submit = '';
	var $savechanges = '';
	var $social_bookmarks = 1;
	var $top_stories = 0;
	var $stories_from_author = 0;
	var $stories_from_cat = 0;
	var $allow_vote = 0;
	var $who_voted = 1;
	var $tags = 0;
	var $default_thumb = '';
	var $about_the_author = 0;
	var $writepost = 'writestorypost';
	var $photos = 0;
	var $disable_category = 0;

	// list
	var $list_type = 'ul';
	var $list_elements = array('title','created','username');
	var $list_atts = array('Title','Created','User');

	// view detail
	var $view_elements = array('title','created','username','content');
	var $view_atts = array('Title','Created','Name','Content');

	// comments
	var $comment_type = 'ul';
	var $comment_elements = array('title','created','username','content');

	// insert/edit form
	var $story_form_elements = array('title','content');
	var $stories = 'Stories';

	// access
	var $story_write = 2;
	var $story_edit = 2;
	var $story_delete = 2;
	var $comment_write = 2;
	var $comment_delete = 2;
	
	// labels
	var $label_content = '';
	var $label_title = '';
	var $label_comment = '';
	var $label_entry = 'entries';
	// redirect
	var $redirect_writestorypost = '';

	// hooks
	var $hook;
	
	// acts
	var $act_write = '';
	
	function story() {
		GLOBAL $content, $parr, $sub_menu, $sub_menu_title, $current_app, $client, $ss, $menuon;
		if (!$this->write_story) {
			$this->write_story = t('New Post');
		}
		if (!$this->label_content) {
			$this->label_content = t('Content');
		}
		if (!$this->label_title) {
			$this->label_title = t('Title');
		}
		if (!$this->label_comment) {
			$this->label_comment = t('Comments');
		}
		if (!$this->submit) {
			$this->submit = t('Submit');
		}
		if (!$this->savechanges) {
			$this->savechanges = t('Save changes');
		}
		if ($this->allow_vote) {
			$this->vote_options['rating'] = t('Rating');
		}
		$this->name = $parr[0];
		$menuon = $this->name;
		$this->flag = $current_app['flag'];
		$sub_menu_title = t('Categories');
		/*
		$res = sql_query("select c.* from `".tb()."story_categories` as c where  app='{$this->name}' order by weight");
		if (!$this->index) {
			$sub_menu[] = array('href'=>$this->name.'/liststories/all', 'name'=>t('All').' '.$current_app['flag']);
		}
		while ($row = sql_fetch_array($res)) {
			$sub_menu[] = array('href'=>$this->name.'/liststories/'.$row['id'],'name'=>$row['name'],'description'=>$row['description']);
		}
		if (count($sub_menu) < 2) {
			$sub_menu = array();
			$this->disable_category = 1;
		}
		*/
		$sub_menu = array();
		$this->disable_category = 1;
	}


	function hooks($array) {
		foreach ($array as $val) {
			$this->hook[$val] = 1;
		}
	}

	function set_current_sub_menu($cid) {
		global $current_sub_menu;
		$current_sub_menu['href'] = $this->name.'/liststories/'.$cid;
	}
	function mine() {
		$this->liststories('mine');
	}
	function index() {
		$this->liststories('all');
	}
	function friends() {
		$this->liststories('friends');
	}
	function following() {
		$this->liststories('following');
	}
	function all() {
		$this->liststories('all');
	}

	// 文章列表
	function liststories($uri) {
		GLOBAL $db,$num_per_page,$page,$ubase,$offset,$content,$client,$title,$sub_menu, $cat_id, $uhome, $current_sub_menu,$parr,$from_url;
		$num_per_page = $num_per_page+1;
		if (preg_match("/^page_/i",$uri)) {
			$arr = explode('_',$uri);
			$page_id = $arr[1];
			if (!preg_match("/^[0-9]+$/i",$page_id)) die('bad uri');
			$res = sql_query("select * from ".tb()."pages where id='{$page_id}'");
			$jcow_page = sql_fetch_array($res);
			if (!$jcow_page['id']) die('wrong page id');
			if ($jcow_page['uid'] == $client['id']) {
				button($this->name.'/writestory/'.$jcow_page['id'],$this->write_story);
			}
			if ($jcow_page['type'] == 'u') {
				include_once('modules/u/u.php');
				if (!$user = u::settabmenu($jcow_page['uri'],1,'u')) die('wrong uname');
				$title = t("{1}'s {2}",$user['username'],$this->label_entry);
				$nav[] = url($this->name.'/liststories/page_'.$user['username'],$user['username']);
				$res = sql_query("SELECT s.*,u.username FROM `".tb()."stories` as s left join `".tb()."accounts` as u on u.id=s.uid where s.app='{$this->name}' and s.page_id='{$jcow_page['id']}' ORDER by s.id DESC LIMIT $offset,$num_per_page ");
				$pwhere = " and uid='{$user['id']}' ";
			}
			elseif ($jcow_page['type'] == 'page') {
				include_once('modules/page/page.php');
				if (!$user = page::settabmenu($jcow_page['uri'],1,$jcow_page['type'])) die('owner not found');
				$title = h($jcow_page['name'].' - '.$this->label_entry);
				$nav[] = url($this->name.'/liststories/page_'.$uri,$jcow_page['name']);
				$res = sql_query("SELECT s.*,u.username FROM `".tb()."stories` as s left join `".tb()."accounts` as u on u.id=s.uid where s.app='{$this->name}' and s.page_id='{$jcow_page['id']}' ORDER by s.id DESC LIMIT $offset,$num_per_page ");
				$pwhere = " and page_id='{$jcow_page['id']}' ";
			}
			elseif ($jcow_page['type'] == 'group') {
				include_once('modules/group/group.php');
				if (!$user = group::settabmenu($jcow_page['uri'],1,$jcow_page['type'])) die('owner not found');
				$title = h($jcow_page['name'].' - '.$this->label_entry);
				$nav[] = url($this->name.'/liststories/page_'.$uri,$jcow_page['name']);
				$res = sql_query("SELECT s.*,u.username FROM `".tb()."stories` as s left join `".tb()."accounts` as u on u.id=s.uid where s.app='{$this->name}' and s.page_id='{$jcow_page['id']}' ORDER by s.id DESC LIMIT $offset,$num_per_page ");
				$pwhere = " and page_id='{$jcow_page['id']}' ";
			}
			$uri = 'liststories/'.$uri;
		}
		else {
			if ($client['id']) {
				button($this->name.'/writestory',$this->write_story);
			}
			if ($uri == 'following') {
				if (!$client['id']) redirect('member/login/1');
				$res2 = sql_query("select f.fid from ".tb()."followers as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$client['id']}' and u.disabled<2 order by u.lastlogin desc limit 50");
				while ($row = sql_fetch_array($res2)) {
					$uids .= $uids ? ','.$row['fid'] : $row['fid'];
				}
				if ($uids) {
					$res = sql_query("SELECT s.*,u.username FROM `".tb()."stories` as s left join `".tb()."accounts` as u on u.id=s.uid where s.app='{$this->name}' and s.uid in ('$uids') ORDER by s.id DESC LIMIT $offset,$num_per_page ");
					$pwhere = ' and uid in ('.$uids.') ';
				}
			}
			elseif ($uri == 'connections') {
				if (!$client['id']) {
					if (preg_match("/".$this->name."/",$from_url)) {
						redirect('member/login/1');
					}
					else {
						redirect($this->name.'/all');
					}
				}
				else {
					$uids[] = $client['id'];
					$num = 10;
					$res2 = sql_query("select f.fid from ".tb()."friends as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$client['id']}' and u.disabled<2 order by u.lastlogin desc limit 10");
					while ($row = sql_fetch_array($res2)) {
						$uids[] = $row['fid'];
					}
					$res2 = sql_query("select f.fid from ".tb()."followers as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$client['id']}' and u.disabled<2 order by u.lastlogin desc limit 10");
					while ($row = sql_fetch_array($res2)) {
						$uids[] = $row['fid'];
					}
					$uids = implode(',',$uids);
					$res = sql_query("SELECT s.*,u.username FROM `".tb()."stories` as s left join `".tb()."accounts` as u on u.id=s.uid where s.app='{$this->name}' and s.uid in ('$uids') and s.page_type='u' ORDER by s.id DESC LIMIT $offset,$num_per_page ");
					$pwhere = ' and uid in ('.$uids.') ';
				}
			}
			elseif ($uri == 'friends') {
				if (!$client['id']) redirect('member/login/1');
				$res2 = sql_query("select f.fid from ".tb()."friends as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$client['id']}' order by u.lastlogin desc limit 50");
				while ($row = sql_fetch_array($res2)) {
					$uids .= $uids ? ','.$row['fid'] : $row['fid'];
				}
				if ($uids) {
					$res = sql_query("SELECT s.*,u.username FROM `".tb()."stories` as s left join `".tb()."accounts` as u on u.id=s.uid where s.app='{$this->name}' and s.uid in ('$uids') ORDER by s.id DESC LIMIT $offset,$num_per_page ");
					$pwhere = ' and uid in ('.$uids.') ';
				}
			}
			elseif ($uri == 'mine') {
				if (!$client['id']) redirect('member/login/1');
				$res = sql_query("SELECT s.*,u.username FROM `".tb()."stories` as s left join `".tb()."accounts` as u on u.id=s.uid where s.app='{$this->name}' and s.uid='{$client['id']}' ORDER by s.id DESC LIMIT $offset,$num_per_page ");
				$pwhere = " and uid='{$client['id']}' ";
			}
			elseif (is_numeric($uri)) {
				if($cat = valid_category($uri, $this->name)) {
					$cat_id = $cid = $cat['id'];
					$title = htmlspecialchars($cat['name']);
					nav(h($cat['name']));
					$pwhere = " and cid='$cid' ";
					$res = sql_query("SELECT s.*,u.fullname,u.username FROM `".tb()."stories` as s left join `".tb()."accounts` as u on u.id=s.uid where s.app='{$this->name}' and s.cid='$cid' and u.disabled<2 ORDER by s.id DESC LIMIT $offset,$num_per_page ");
				}
				else {
					die('wrong cid');
				}
			}
			else {
				$uri = 'all';
				$res = sql_query("SELECT s.*,u.username FROM `".tb()."stories` as s left join `".tb()."accounts` as u on u.id=s.uid where s.app='{$this->name}' and s.page_type='u' and u.disabled<2 ORDER by s.id DESC LIMIT $offset,$num_per_page ");
			}
			if (method_exists($this,'hook_liststories')) {
				$this->hook_liststories($uri);
			}
			if ($this->tags && $uri == 'all') {
				//$this->tag_cloud(30);
			}
		}

		if ($res) {
			$i=1;
			if ($this->list_type == 'ul') {
				while ($row = sql_fetch_array($res)) {
					if ($i<$num_per_page) {
						section_content($this->theme_list_lines($row));
					}
					else {
						$got_next = 1;
					}
					$i++;
				}
			}
			elseif ($this->list_type == 'gallery') {
				section_content('<ul class="gallery">');
				while ($row = sql_fetch_array($res)) {
					if ($i<$num_per_page) {
						section_content($this->theme_list_gallery($row));
						}
					else {
						$got_next = 1;
					}
					$i++;
				}
				section_content('</ul>');
			}
			else {
				c('<table class="stories" cellspacing="1">
					<tr>
					<td class="table_line1" width="20"></td> 
					<td class="table_line1">'.t('Title').'</td> 
					<td class="table_line1" width="150">'.t('Last post').'</td>
					<td class="table_line1" width="60">'.t('Views').'</td>
					<td class="table_line1" width="60">'.t('Replies').'</td>
					</tr>');
				while ($row = sql_fetch_array($res)) {
					if ($i<$num_per_page) {
						if (!$row['lastreply']) {
							$row['lastreply'] = $row['created'];
							$row['lastreplyuid'] = $row['uid'];
							$row['lastreplyuname'] = $row['username'];
						}
						$icon = 'topic_1.gif';
						if ($row['comments'] >= 5 or $row['views'] >= 100) {
							$icon = 'topic_2.gif';
						}
						c('<tr class="row1">
						<td><img src="'.$uhome.'/files/'.$icon.'" /></td>
						<td><span class="forum_title">'.$this->list_title($row).'</span><br />'.$this->list_username($row).', '.$this->list_created($row).'</td>
						<td>'.get_date($row['lastreply']).'<br />'.$row['lastreplyuname'].'</td>
						<td>'.$row['views'].'</td>
						<td>'.$row['comments'].'</td>
						</tr>');
					}
					else {
						$got_next = 1;
					}
					$i++;
				}
				section_content('</table>');
			}

			// pager
			if (!$parr[1]) {
				$paras = $ubase.$this->name;
			}
			else {
				$paras = $ubase.$this->name.'/'.$uri;
			}
			if ($offset>0) {
				if ($offset == 1) {
					$page_prev = '<a href="'.$paras.'">'.t('Prev').'</a>';
				}
				elseif (preg_match("/\?/",$paras)) {
					$page_prev = '<a href="'.$paras.'&page='.($page-1).'">&lt;'.t('Prev').'</a>';
				}
				else {
					$page_prev = '<a href="'.$paras.'?page='.($page-1).'">&lt;'.t('Prev').'</a>';
				}
			}
			if ($got_next) {
				if (preg_match("/\?/",$paras)) {
					$page_next = '<a href="'.$paras.'&page='.($page+1).'">'.t('Next').' &gt;</a>';
				}
				else {
					$page_next = '<a href="'.$paras.'?page='.($page+1).'">'.t('Next').' &gt;</a>';
				}
			}
			if ($offset>0 || $got_next) {
				c('<div style="padding:5px;font-size:15px">'.$page_prev.' '.$page_next.'</div>');
			}
			section_close();
		}
		else {
			c(t('No entry'));
		}
		//
		//$smarty->assign('content',$content);
	}
	
	function theme_list_lines($row) {
		global $client;
		$output = '';
		$output .= '<div class="story">';
		if (!$row['thumbnail'] && $this->default_thumb) {
			$row['thumbnail'] = $this->default_thumb;
		}
		if ($row['thumbnail']) {
			$output .= '<div class="thumb">'.url($this->name.'/viewstory/'.$row['id'],$this->list_thumbnail($row)).'</div>';
		}
		$output .='<div class="header">'.$this->list_title($row).'</div>';
		$output .='<div class="content">'.$this->list_summary($row).'</div>';
		$output .='<div class="tab_things">';
		$output .= $this->theme_story_footer($row);
		if (method_exists($this,'hook_list_lines')) {
			$output .= $this->hook_list_lines($row);
		}
		if ($client['id'] == $row['uid'] || allow_access(3)) {
			$output .= '<div class="tab_thing">'.url($this->name.'/editstory/'.$row['id'], t('Edit')).'</div>';
			$output .= '<div class="tab_thing">'.url($this->name.'/deletestory/'.$row['id'], t('Delete')).'</div>';
		}
		$output .= '</div>';
		$output .= '</div>';
		return $output;
	}

	function theme_list_gallery($row) {
		global $client;
		section_content('<li class="test">');
		if (!$row['thumbnail'] && $this->default_thumb) {
			$row['thumbnail'] = $this->default_thumb;
		}
		section_content(url($this->name.'/viewstory/'.$row['id'],$this->list_thumbnail($row)) );
		section_content('<br />'.$this->list_title($row));
		if ($row['photos'] > 1) {
			section_content('('.$row['photos'].')');
		}
		section_content('<br /><i>by '.url('u/'.$row['username'],$row['username']).'</i>');
		section_content('</li>');
		return $content;
	}
	function theme_story_footer($row) {
		$output .= '<div class="tab_thing">'.$this->list_created($row).'</div>';
		$output .= '<div class="tab_thing">'.$this->list_username($row).'</div>';
		$output .= '<div class="tab_thing">'.$this->list_views($row).'</div>';
		return $output;
	}
	
	
	function story_line($row) {
			$line[] = $this->list_title($row);
			$line[] = $this->list_thumbnail($row);
			$line[] = $this->list_created($row);
			$line[] = $this->list_username($row);
			$line[] = url($this->name.'/deletestory/'.$row['id'],'Delete');
			$line[] = url($this->name.'/editstory/'.$row['id'],'Edit');
			return $line;
	}

	// TAGs
	function tag($tid) {
		GLOBAL $nav,$num_per_page,$page,$ubase,$offset,$content,$title,$sub_menu, $cat_id, $uhome;
		$res = sql_query("select * from `".tb()."tags` where id='$tid'");
		if ($otag = sql_fetch_array($res)) {
			set_title($this->flag.' Tag: '.htmlspecialchars($otag['name']));
			$nav[] = 'Tag: <strong>'.url($this->name.'/tag/'.$otag['id'],h($otag['name'])).'</strong>';
			set_page_title($this->flag.' Tag: '.htmlspecialchars($otag['name']));
			$res = sql_query("select s.id,s.uid,s.created,s.title,u.avatar,u.username from `".tb()."tag_ids` as t left join `".tb()."stories` as s on s.id=t.sid left join `".tb()."accounts` as u on u.id=s.uid where t.tid='{$otag['id']}' ".dbhold('t')." order by t.sid DESC limit 30");
			while ($row = sql_fetch_array($res)) {
				if ($row['uid'] != $client['id']) {
					$res2 = sql_query("select * from ".tb()."pending_review where post_id='story".$row['id']."'");
					if (sql_counts($res2)) {
						continue;
					}
				}
				$row['content'] = url($this->name.'/viewstory/'.$row['id'],htmlspecialchars($row['title']));
				c(user_post($row,0));
			}
		}
		else {
			die('tag not found');
		}
	}
	
	// 阅读文章
	function viewstory($sid) {
		GLOBAL $db,$ubase,$uhome,$nav,$content, $title, $page_title, $page, $client,$cat_id, $num_per_page, $offset, $config;
		enreport();

		$res = sql_query("select s.*,u.birthyear,u.gender,u.location,u.avatar,u.username,u.disabled from `".tb()."stories` as s left join `".tb()."accounts` as u on u.id=s.uid where s.id='$sid' ");
		$row = sql_fetch_array($res);
		if ($row['disabled'] == 3) die('marked as Spam');
		if (!$row['id']) die('wrong sid');
		$res = sql_query("select * from ".tb()."pending_review where post_id='story".$row['id']."'");
		if (sql_counts($res)) {
			if ($row['uid'] == $client['id'] || allow_access(3)) {
				sys_notice('Pending review');
			}
			else {
				c('Pending review');
				stop_here();
			}
		}
		$res = sql_query("select type from ".tb()."pages where id='{$row['page_id']}'");
		$jcow_page = sql_fetch_array($res);
		if (!$jcow_page['type']) die('unknown page id');
		if ($jcow_page['type'] == 'u') {
			include_once('modules/u/u.php');
			u::settabmenu($row['page_id'],1);
		}
		elseif ($jcow_page['type'] == 'page') {
			include_once('modules/page/page.php');
			page::settabmenu($row['page_id'],1);
		}
		elseif ($jcow_page['type'] == 'group') {
			include_once('modules/group/group.php');
			group::settabmenu($row['page_id'],1);
		}
		/*
		if (allow_access($this->story_write)) {
			button($this->name.'/writestory/'.$row['id'],$this->write_story);
		}
		*/
		$title = $this->title_prefix.$row['title'];
		if (!$row['id']) {
			die(':)');
		}
		if ($row['cid']) {
			$cat = valid_category($row['cid']);
			$cat_id = $row['cid'];
			$closed = $row['closed'];
			$this->set_current_sub_menu($row['id']);
		}
		if ($row['var5'] == 2) {
			if (!privacy_access(2,$row['uid'])) {
				$accessdenied = 1;
			}
			$privacy_flag = t('Friends only');
		}
		elseif ($row['var5'] == 1) {
			if (!privacy_access(1,$row['uid'])) {
				$accessdenied = 1;
			}
			$privacy_flag = t('Friends of friends');
		}
		if ($accessdenied) {
			$accessdenied = 1;
				c('<table>
			<tr><td align="right">
			'.avatar($row).'</td>
			<td align="left">
			<h1>'.url($this->name.'/viewstory/'.$row['id'],$this->title_prefix.h($row['title'])).'</h1>
			View: <a href="'.url('u/'.$row['username']).'">'.t("{1}'s Profile",$row['username']).'</a> | 
			<a href="'.url($this->name.'/liststories/page_'.$row['username']).'">'.t("{1}'s {2}",$row['username'],$this->label_entry).'</a>
			</td></tr>
			</table>');
				c(t('Sorry, this content is open to {1}','<strong>'.$privacy_flag.'</strong>'));
		}
		else {
			// 更新文章阅读数目
			sql_query("update `".tb()."stories` set views=views+1 where id='$sid'");
			/*
			if (!$this->disable_category) {
				$nav[] = url($this->name.'/liststories/'.$cat['id'],$cat['name']);
			}
			*/
			$nav[] = $title = $page_title = htmlspecialchars($row['title']);

			
			// table view
			$story .= $this->story_content($row);
			if ($this->tags && $row['tags']) {
				$story .= '<p><i>'.t('Tags').': </i><br />';
				$tags = explode(',',$row['tags']);
				foreach ($tags as $tag) {
					$res = sql_query("select * from `".tb()."tags` where  name='".addslashes($tag)."' and app='{$this->name}'");
					if ($tag = sql_fetch_array($res)) {
						$tagstr .= $tagstr ? ', '.url($this->name.'/tag/'.$tag['id'],'<img src="'.uhome().'/files/icons/tags.gif" /> '.$tag['name']).'<span class="sub">('.$tag['num'].')</span>' : url($this->name.'/tag/'.$tag['id'],'<img src="'.uhome().'/files/icons/tags.gif" /> '.$tag['name']).'<span class="sub">('.$tag['num'].')</span>';
					}
				}
				$story .= $tagstr;
				$stroy .= '</p>';
			}

			$story .= '<div id="sp_block_content_bottom">'.$config['sp_content_bottom'].show_ad('sp_block_content_bottom').'</div>';


			if (is_array($this->story_opts)) {
				$story .= '<script>
					$(document).ready( function(){
						$("#add_to_favorite").click(function() {
							$("#add_to_favorite").replaceWith("<img id=\'add_to_favorite\' src=\''.$uhome.'/files/loading.gif\' width=16 height=16 />");
							$.post("'.$uhome.'/index.php?p=jquery/favoriteadd",{sid:$("#story_id").val()},function(data) {
								$("#add_to_favorite").replaceWith(data);
							},\'html\');
						});
				});
				</script>
				<table border="0"><tr>';
				$story .= '</tr></table>';
			}

			if ($this->social_bookmarks && !get_gvar('private_network')) {
				$encoded_url = urlencode(url($this->name.'/viewstory/'.$sid));
				$encoded_title = urlencode($row['title']);
				$story .= '<div style="overflow:hidden;margin:10px 0;"><i>'.t('Bookmark & Share').':</i><div style="font-size:1.5em">';
				$story .= '<a href="http://twitter.com/home?status='.$encoded_url.'" target="_blank">Twitter</a>, ';
				$story .= '<a href="http://www.facebook.com/sharer.php?u='.$encoded_url.'&t='.$encoded_title.'"  target="_blank">Facebook</a>, ';
				$story .= '<a href="http://digg.com/submit?phase=2&url='.$encoded_url.'&title='.$encoded_title.'"  target="_blank">Digg</a>, ';
				$story .= '<a href="http://channelor.com/write?url='.$encoded_url.'&title='.$encoded_title.'"  target="_blank">Channelor</a>';
				
				$story .= '</div></div>';
			}
			
			c('
			'
			.$story.
				'<div class="hr"></div>'.
				comment_form($row['stream_id']).likes_get($row['stream_id']).comment_get($row['stream_id'],100)
				);
		}
	}
	
	function comment_author($row) {
		$output = '<div class="post_author">';
		$output .= avatar($row).'<br />'.url('u/'.$row['username'],$row['username']);
		$output .= '</div>';
		return $output;
	}

	// 产生文章内容
	function story_content($row) {
		global $client, $nav, $defined_current_tab;
		$res = sql_query("select id,type from ".tb()."pages where id='{$row['page_id']}'");
		$page = sql_fetch_array($res);
		$defined_current_tab = $this->name.'/liststories/page_'.$page['id'];

			$story = '<div class="tab_things">'.$this->theme_story_footer($row);
			if ($client['id'] == $row['uid'] || allow_access(3)) {
				if ($this->name == 'images') {
					$story .= '<div class="tab_thing"><strong>'.url($this->name.'/managephotos/'.$row['id'], t('upload')).'</strong></div>';
				}
				$story .= '<div class="tab_thing">'.url($this->name.'/editstory/'.$row['id'], t('Edit')).'</div>';
				$story .= '<div class="tab_thing">'.url($this->name.'/deletestory/'.$row['id'], t('Delete')).'</div>';
			}
			if (allow_access(3)) {
				if ($row['featured']) {
					$story .= '<div class="tab_thing">'.t('Featured').' ['.url($this->name.'/unfeature_story/'.$row['id'], t('Unfeature')).']</div>';
				}
				else {
					$story .= '<div class="tab_thing">'.url($this->name.'/feature_story/'.$row['id'], t('Feature this')).'</div>';
				}
			}

			$story .= '</div>';
			$story .= '<input type="hidden" value="'.$row['id'].'" name="story_id" id="story_id" />';
			$story = '<div class="user_post_2">
			<table>
		<tr><td align="right">
		'.avatar($row).'</td>
		<td align="left">
		<h1>'.url($this->name.'/viewstory/'.$row['id'],$this->title_prefix.h($row['title'])).'</h1>
'.$current_menu_path.'
		</td></tr>
		</table>
			'.$story.'
			</div>';
			$story .= '<div id="ad_block_content_top">'.show_ad('ad_block_content_top').'</div>';
			if (method_exists($this,'hook_viewstory')) {
				$story .= $this->hook_viewstory($row);
			}
			// photos
			if ($row['photos'] && $this->name == 'images') {
				if ($row['photos'] == 1) {
					$res = sql_query("select * from `".tb()."story_photos` where sid='{$row['id']}' ORDER by id DESC");
					$photo = sql_fetch_array($res);
					$story .= '<img src="'.uhome().'/'.$photo['uri'].'" /><br />'.h($photo['des']);
				}
				else {
					$res = sql_query("select * from `".tb()."story_photos` where sid='{$row['id']}' ORDER by id DESC");
					$story .= '<div><ul class="gallery">';
					while ($photo = sql_fetch_array($res)) {
						$story .= '<li><a title="'.htmlspecialchars($photo['des']).'" href="'.uhome().'/'.$photo['uri'].'" rel="lightbox" ><img src="'.uhome().'/'.$photo['thumb'].'" /></a></li>';
					}
					$story .= '</ul></div>';
				}
			}
			$story .= $this->view_content($row);
			if (method_exists($this,'hook_viewstorybottom')) {
				$story .= $this->hook_viewstorybottom($row);
			}
			return $story;
	}
	
	
	// 评论列表里的 单个项目
	function comment_line($row) {
			$line[] = $this->comment_created($row);
			$line[] = $this->comment_username($row);
			if ($client['id'] == 1) {
				$line[] = url($this->name.'/deletecomment/'.$row['id'],'Delete');			
			}
			$line[] = $this->comment_content($row);
			return $line;
	}

	function writestory($page_id=0) {
		do_auth(explode('|',get_gvar('permission_add')));
		clear_as();
		limit_posting(1);
		GLOBAL $ubase,$nav, $title, $client, $sub_menu, $ass,$current_app,$cat_id;
		if ($page_id) {
			$page = $this->check_page_access($page_id);
			$page_id = $page['id'];
			$this->tags = 0;
		}
		else {
			$page_id = $client['page']['id'];
			$page = $this->check_page_access($page_id);
		}
		$cat_id = $cid;
		$sub_menu = $ass = '';
		$nav[] = $this->write_story;
		$this->set_current_sub_menu($cid);
		$title = $this->write_story;
		section_content('<div class="form"><form action="'.$ubase.$this->name.'/'.$this->writepost.'" method="post"  enctype="multipart/form-data">');
		if (!$this->disable_category) {
			c($this->story_form_cat($cid));
		}
		section_content($this->writestory_form_elements($row));

		if (method_exists($this,'hook_writestory')) {
			c( $this->hook_writestory($cid));
		}
		if ($page['type'] == 'u' && !$this->disable_privacy) {
			section_content(privacy_form($row));
		}
		/*
		if ($this->photos && $this->name != 'photos') {
			section_content('<p>'.label(t('Photos')).'<input type="checkbox" name="photos" value="1" />'.t('Upload photos on next step').'</p>';
		}
		*/
		section_content('<p><input class="button" type="submit" value="'.$this->submit.'" /></p>');
		section_content('<input type="hidden" name="page_id" value="'.$page_id.'" /></form></div>');
	}
	
	// 处理评论表单
	function writecommentpost() {
		do_auth($this->comment_write);
		GLOBAL $db,$client,$ubase;
		limit_posting(1);
		$timeline = time();
		//get_r(array('sid','content'));
		if (!$story = valid_story($_POST['sid'])) {
			sys_back('wrong sid');
		}
		if ($story['closed']) {
			sys_back('topic closed');
		}
		if (strlen($_POST['form_content']) < 10) {
			sys_back(t('Your message is too short'));
		}
		if ($res = sql_query("insert into `".tb()."story_comments` (sid,content,uid,cid,created,app,target_uid,vote) VALUES ('".$_POST['sid']."','".$_POST['form_content']."','{$client['id']}','{$story['cid']}',$timeline,'{$this->name}','{$story['uid']}','{$_POST['vote']}')")) {
			if ($_POST['vote'] > 0) {
				$vote = ",digg=digg+".$_POST['vote'];
			}
			elseif ($_POST['vote'] < 0) {
				$vote = $_POST['vote']*(-1);
				$vote = ",dugg=dugg+".$vote;
			}
			sql_query("update `".tb()."stories` set comments=comments+1,lastreply=".time().",lastreplyuname='{$client['uname']}',lastreplyuid={$client['id']}".$vote." where id='".$_POST['sid']."'");
		}
		redirect($ubase.$this->name.'/viewstory/'.$_POST['sid'],1);
	}
	
	
	// 文章表单
	

	function check_page_access($page_id) {
		global $client;
		$res = sql_query("select * from ".tb()."pages where id='{$page_id}'");
		$page = sql_fetch_array($res);
		if (!$page['id']) die('wrong page id');
		if ($page['type'] == 'u') {
			if ($page['uid'] != $client['id']) {
				die('access denied');
			}
		}
		elseif($page['type'] == 'page') {
			if ($page['uid'] != $client['id']) {
				die('only page owner can post');
			}
		}
		return $page;
	}
	
	function story_form_cat($cid) {
		global $current_app;
		$output = '<p>'.label('Category').'<select name="cid" class="inputText">';
		if ($current_app['cat_group']) {
			$res = sql_query("select g.* from `".tb()."story_cat_groups` as g where  app='{$this->name}' order by weight");
			while ($group = sql_fetch_array($res)) {
				$output .= '<optgroup label="'.$group['name'].'">';
				$res2 = sql_query("select c.* from `".tb()."story_categories` as c where  gid={$group['id']} order by weight");
				while($row = sql_fetch_array($res2)) {
					if(is_numeric($cid) && $cid == $row['id']) {
						$row['selected'] = 'selected';
					}
					$output .='<option value="'.$row['id'].'" '.$row['selected'].' >'.$row['name'].'</option>';
					if(is_numeric($cid) && $cid == $row['id']) {
						$row['on'] = 1;
					}
				}
				$output .='</optgroup>';
			}
		}
		else {
			$res = sql_query("select c.* from `".tb()."story_categories` as c where  app='{$this->name}' order by weight DESC");
			while($row = sql_fetch_array($res)) {
				if(is_numeric($cid) && $cid == $row['id']) {
					$row['selected'] = 'selected';
				}
				$output .='<option value="'.$row['id'].'" '.$row['selected'].' >'.$row['name'].'</option>';
				if(is_numeric($cid) && $cid == $row['id']) {
					$row['on'] = 1;
				}
			}
		}
		$output .='</select></p>';
		return $output;
	}

	function writestory_form_elements($row) {
		$foo = $this->story_form_title($row);
		$foo .= $this->story_form_content($row);
		if ($this->tags) {
			$foo .= $this->story_form_tags($row);
		}
		return $foo;
	}
	
	// 编辑文章
	function editstory($sid) {
		GLOBAL $ubase,$content,$sub_menu, $ass, $client;
		if ($client['disabled']) {
			c(t('You can not edit post before being verified'));
			stop_here();
		}
		clear_as();
		$sub_menu = $ass = '';
		if (!$story = valid_story($sid)) {
			 ('Wrong sid');
		}
		if ($story['page_type'] != 'u') {
			$this->tags = 0;
		}
		do_auth($this->story_edit, $story['uid']);
		$this->set_current_sub_menu($story['cid']);
		section_content('<div class="form">
		<form action="'.$ubase.$this->name.'/editstorypost" method="post">');
		if (!$this->disable_category) {
			section_content($this->story_form_cat($story['cid']));
		}
		c($this->writestory_form_elements($story));
		if (method_exists($this,'hook_editstory')) {
			section_content($this->hook_editstory($story));
		}
		if ($story['page_type'] == 'u' && !$this->disable_privacy) {
			section_content(privacy_form($story));
		}
		section_content('<input type="hidden" name="sid" value="'.$story['id'].'" />');
		section_content('<p><input class="button" type="submit" value="'.$this->savechanges.'" /></p>');
		section_content('</form></div>');
		if ($this->photos && allow_access( explode('|',get_gvar('permission_upload')) )) {
			section_content('<div style="padding-left:150px"> '.url($this->name.'/managephotos/'.$sid,'<img src="'.uhome().'/files/icons/photos.png" /> '.t('Manage photos')).'</div>');
		}
	}
	
	function writestorypost() {
		do_auth($this->story_write);
		GLOBAL $db,$client,$ubase;
		if ($client['disabled']) {
			c(t('You can not edit post before being verified'));
			stop_here();
		}
		//get_r(array('title','content','cid'));
		$timeline = time();
		//if (!valid_category($_POST['cid'])) {
		//	sys_back('wrong cid');
		//}
		if (!$_POST['title'] || !$_POST['form_content']) {
			sys_back(t('Please fill all requried blanks'));
		}
		if (!is_numeric($_POST['page_id'])) die("need a page id");
		$page = $this->check_page_access($_POST['page_id']);

		$story = array(
			'cid' => $_POST['cid'],
			'page_id'=>$_POST['page_id'],
			'page_type'=>$page['type'],
			'title' => $_POST['title'],
			'var5' => $_POST['privacy'],
			'content' => $this->convert_content_before_insert($_POST['form_content']),
			'uid' => $client['id'],
			'created' => time(),
			'closed' => $_POST['closed'],
			'app' => $this->name
			);
		if ($this->tags && $_POST['tags']) {
			$stags = array();
			$tags = explode(',',$_POST['tags']);
			if (is_array($tags)) {
				foreach ($tags as $tag) {
					if (count($stags) > 5) {
						continue;
					}
					$tag = strtolower(trim($tag));
					if (strlen($tag) > 0 && strlen($tag) < 50) {
						$stags[] = $tag;
					}
				}
			}
			if ($num = count($stags)) {
				$story['tags'] = implode(',',$stags);
			}
		}

		if (method_exists($this,'hook_writestorypost') ) {
			section_content($this->hook_writestorypost($story));
		}

		if ($this->allow_vote) {
			foreach ($this->vote_options as $key=>$vla) {
				$ratings[$key] = array('score'=>0,'users'=>0);
			}
			$story['rating'] = serialize($ratings);
		}
		limit_posting();

		if (sql_insert($story, tb().'stories')) {
			$sid = $story['id'] = mysql_insert_id();
			if (method_exists($this,'hook_writestorypostdone')) {
				section_content($this->hook_writestorypostdone($story));
			}
			// twitter
			if (get_gvar('twitter_target') == 'all') {
				tweet(url($this->name.'/viewstory/'.$sid.' :'.$_POST['title']));
			}
			// write act
			if (strlen($this->act_write)) {
				$attachment = array(
					'cwall_id' => $this->name.$sid,
					'uri' => $this->name.'/viewstory/'.$sid,
					'name' => $_POST['title']
					);
				$app = array('name'=>$this->name,'id'=>$sid);
				$args = array(
					'message'=>addslashes($this->act_write),
					'link' => $this->name.'/viewstory/'.$sid,
					'name' => $_POST['title'],
					'app' => $this->name,
					);
				if ($this->hide_feed == 1) {
					$hide = 1;
				}
				else {
					$hide = 0;
				}
				$stream_id = jcow_page_feed($_POST['page_id'],$args,$client['id'],$hide);
				$set_story['id'] = $sid;
				$set_story['stream_id'] = $stream_id;
				sql_update($set_story,tb()."stories");
			}
			if (!$this->redirect_writestorypost) {
				if ($this->tags) {
					save_tags($stags,$sid,$this->name);
				}
				if ($_POST['photos']) {
					redirect($this->name.'/managephotos/'.$sid);
				}
				else {
					redirect(url($this->name.'/viewstory/'.$sid));
				}
			}
			else {
				redirect($this->redirect_writestorypost);
			}
		}
	}
	
	// 处理文章编辑
	function editstorypost() {
		GLOBAL $db,$client,$ubase;
		//get_r(array('title','content','sid'));
		$timeline = time();
		//get_r(array('sid','title','content'));
		if (!$story = valid_story($_POST['sid'])) {
			sys_back('wrong sid');
		}
		do_auth($this->story_edit, $story['uid']);
		$story = array(
			'title' => $_POST['title'],
			'cid' => $_POST['cid'],
			'var5' => $_POST['privacy'],
			'content' => $this->convert_content_before_insert($_POST['form_content']),
			'updated' => time(),
			'closed' => $_POST['closed']
			);
		if ($this->tags && $_POST['tags']) {
			$stags = array();
			$tags = explode(',',$_POST['tags']);
			if (is_array($tags)) {
				foreach ($tags as $tag) {
					if (count($stags) > 5) {
						continue;
					}
					$tag = strtolower(trim($tag));
					if (strlen($tag) > 0 && strlen($tag) < 50) {
						$stags[] = $tag;
					}
				}
			}
			if ($num = count($stags)) {
				$story['tags'] = implode(',',$stags);
			}
		}
		if (method_exists($this,'hook_editstorypost')) {
			$this->hook_editstorypost($story);
		}
		if ($this->tags) {
			save_tags($stags,$_POST['sid'],$this->name);
		}
		sql_update($story, tb().'stories', array('id'=>$_POST['sid']));
		redirect($ubase.$this->name.'/viewstory/'.$_POST['sid']);
	}

	//
	function managephotos($sid) {
		GLOBAL $ubase,$content,$sub_menu, $ass;
		clear_as();
		do_auth( explode('|',get_gvar('permission_upload')) );
		$sub_menu = $ass = '';
		if (!$story = valid_story($sid)) {
			 ('Wrong sid');
		}

		c('<form action="'.$ubase.$this->name.'/managephotos_upload" method="post" enctype="multipart/form-data">
						<script  type="text/javascript">
						$(document).ready( function(){
								$("#add_another_photo").click(function() {
									$("#add_another_photo_box").before("<tr><td><input type=\"file\" name=\"photos[]\" /></td><td><input type=\"text\" size=\"35\" name=\"descriptions[]\" /></td></tr>");
								});
						});
	</script>		
		 				<fieldset>
						<legend>'.t('Upload image').'</legend>

						<table border="0">
						<tr><td>'.t('Photo').'</td><td>'.t('Description').'</td></tr>
		<tr><td><input type="file" name="photos[]" /></td><td><input type="text" size="35" name="descriptions[]" /></td></tr>
		
		<tr id="add_another_photo_box"><td colspan="2"><a href="javascript:void();" id="add_another_photo">'.t('Add another photo').'</a></td></tr>
		</table>
						<input type="submit" class="button" value="'.t('Upload').'" />
						</fieldset>
						<input type="hidden" name="sid" value="'.$story['id'].'" />
						[ '.url($this->name.'/viewstory/'.$story['id'],t('Finished')).' ] 
				</form>');
		c('<h1>'.url($this->name.'/viewstory/'.$story['id'],h($story['title'])).'</h1>');
		do_auth($this->story_edit, $story['uid']);
		set_page_title(t('Photos of {1}','"'.url($this->name.'/viewstory/'.$sid,htmlspecialchars($story['title'])).'"'));
		$this->set_current_sub_menu($story['cid']);
		

		// current pics
		$res = sql_query("select * from `".tb()."story_photos` where sid='{$story['id']}' "." order by id DESC");
		while ($row = sql_fetch_array($res)) {
			$photos[] = $row;
		}
		if (is_array($photos)) {
			c('<fieldset><legend>'.t('Uploaded images').'</legend><ul class="gallery">');
			$images = unserialize($story['text1']);
			foreach ($photos as $photo) {
				c('<li><img src="'.uhome().'/'.$photo['thumb'].'" /><br />'.url($this->name.'/managephotos_delete/'.$photo['id'],t('Delete')).'</li>');
			}
			c('</ul></fieldset>');
		}

	}

	function managephotos_upload() {
		GLOBAL $db,$client,$ubase;
		$timeline = time();
		if (!$story = valid_story($_POST['sid'])) {
			sys_back('wrong sid');
		}
		do_auth($this->story_edit, $story['uid']);
		// up pic
		foreach ($_FILES['photos']['tmp_name'] as $key=>$file_tmp_name) {
			if ($file_tmp_name) {
				$uploaded = 1;
				$photo = array('name'=>$_FILES['photos']['name'][$key],
				'tmp_name'=>$file_tmp_name,
				'type'=>$_FILES['photos']['type'][$key],
				'size'=>$_FILES['photos']['size'][$key]);
				list($width, $height) = getimagesize($photo['tmp_name']);
				if ($width <= 740) {
					$uri = save_file($photo);
				}
				else {
					$height = floor(740*$height/$width);
					$uri = save_thumbnail($photo, 740,0);
				}
				$story['photos']++;
				$thumb = save_thumbnail($photo, 100, 100);
				$size = $photo['size'];
				sql_query("insert into `".tb()."story_photos` (sid,uri,des,thumb,size) values( {$story['id']},'$uri','".$_POST['descriptions'][$key]."','$thumb','$size')");
			}
		}
		if ($uploaded) {
			$set_story['thumbnail'] = $thumb;
			$set_story['id'] = $story['id'];
			$set_story['photos'] = $story['photos'];
			sql_update($set_story, tb().'stories');
			
			if (strlen($this->act_write)) {
				$res = sql_query("select thumb from ".tb()."story_photos where sid='{$story['id']}' order by id DESC limit 3");
				while ($photo = sql_fetch_array($res)) {
					$thumbs[] = $photo['thumb'];
					$pics = ' ('.$set_story['photos'].')';
				}
				$attachment = array(
						'cwall_id' => $this->name.$story['id'],
						'uri' => $this->name.'/viewstory/'.$story['id'],
						'name' => addslashes($story['title']),
						'thumb' => $thumbs
						);
				$app = array('name'=>$this->name,'id'=>$story['id']);
				$stream_id = stream_update(addslashes($this->act_write.$pics),$attachment,$app, $story['stream_id']);
			}

			redirect($ubase.$this->name.'/managephotos/'.$_POST['sid'], 1);
		}
		else {
			die('sorry,failed to upload pic');
		}
	}
	function managephotos_delete($pid) {
		GLOBAL $client,$ubase;
		$res = sql_query("select * from `".tb()."story_photos` where id='$pid' ");
		$photo = sql_fetch_array($res);
		if (!$photo['id']) {
			die('no this photo');
		}
		if (!$story = valid_story($photo['sid'])) {
			sys_back('wrong sid');
		}
		do_auth($this->story_edit, $story['uid']);
		//delete
		sql_query("delete from `".tb()."story_photos` where id='$pid' ");
		unlink($photo['uri']);
		unlink($photo['thumb']);
		//story thumb
		if ($story['thumbnail'] == $photo['thumb']) {
			$res = sql_query("select * from `".tb()."story_photos` where sid='{$story['id']}' "." limit 1");
			$ophoto = sql_fetch_array($res);
			if ($ophoto['id']) {
				sql_query("update `".tb()."stories` set thumbnail='{$ophoto['thumb']}' where id={$story['id']} ");
			}
			else {
				sql_query("update `".tb()."stories` set thumbnail='' where id={$story['id']} ");
			}
		}
		sql_query("update `".tb()."stories` set photos=photos-1 where id={$story['id']} ");
		redirect($ubase.$this->name.'/managephotos/'.$story['id'], 1);
	}

	// 删除文章
	function deletestory($sid) {
		GLOBAL $db,$client,$ubase;
		if (!$story = valid_story($sid)) {
			sys_back('wrong sid');
		}
		do_auth($this->story_delete, $story['uid']);
		$res = sql_query("DELETE from `".tb()."stories` where id='$sid'");
		if ($res) {
			sql_query("delete from ".tb()."streams where id='{$story['stream_id']}'");
			//delete photos
			$res2 = sql_query("select * from ".tb()."story_photos where sid='$sid'");
			while ($photo = sql_fetch_array($res2)) {
				unlink($photo['uri']);
				unlink($photo['thumb']);
				sql_query("delete from ".tb()."story_photos where id='{$photo['id']}'");
			}
		}

		$cat = valid_category($story['cid']);
		redirect($ubase.$this->name.'/liststories/mine');
	}

	// 推荐
	function feature_story($sid) {
		GLOBAL $db,$client,$ubase;
		if (!$story = valid_story($sid)) {
			sys_back('wrong sid');
		}
		do_auth(9);
		$res = sql_query("UPDATE `".tb()."stories` set featured=1 where id='$sid' ");
		if ($res) {
			if(get_gvar('twitter_target') == 'featured') {
				tweet(url($this->name.'/viewstory/'.$story['id']).' :'.$story['title']);
			}
		}
		redirect($this->name.'/viewstory/'.$story['id']);
	}
	function unfeature_story($sid) {
		GLOBAL $db,$client,$ubase;
		if (!$story = valid_story($sid)) {
			sys_back('wrong sid');
		}
		do_auth(9);
		$res = sql_query("UPDATE `".tb()."stories` set featured=0 where id='$sid' ");
		redirect($this->name.'/viewstory/'.$story['id']);
	}

	function deletecomment($cid) {
		do_auth($this->comment_write);
		GLOBAL $smarty,$db,$client,$ubase;
		if (!$comment = valid_comment($cid)) {
			sys_back('wrong cid');
		}
		$res = sql_query("DELETE from `".tb()."story_comments` where id='$cid' ");
		redirect($ubase.$this->name.'/viewstory/'.$comment['sid']);
	}


	// story form
	function story_form_title($row = array()) {
		return '<p>'.label($this->label_title).'<input type="text" class="inputText required" style="width:400px" name="title" value="'.htmlspecialchars($row['title']).'" /></p>';
	}
	function story_form_tags($row = array()) {
		return '<p>'.label(t('Tags')).'
		<input type="text" class="inputText" name="tags" style="width:300px" value="'.htmlspecialchars($row['tags']).'" />
		<span>'.t('Multiple tags should be Separated with commas(,)').'</span>
		</p>';
	}
	function story_form_content($row = array()) {
		global $uhome;
		if (file_exists('js/tiny_mce/jquery.tinymce.js')) {
			return '<p>'.label($this->label_content).
				$this->tinymce_form().'
	<textarea name="form_content" rows="18" style="width:580px" class="rich" >'.htmlspecialchars($row['content']).'</textarea>
	</p>';
		}
		else {
			return '<p>'.label($this->label_content).'
	<textarea name="form_content" rows="18" style="width:580px" class="rich" >'.htmlspecialchars($row['content']).'</textarea>
	</p>';
		}
	}

	function tinymce_form() {
		return '
		<script type="text/javascript" src="'.uhome().'/js/tiny_mce/jquery.tinymce.js"></script>
			<script language="javascript" type="text/javascript" 
			src="'.uhome().'/js/tiny_mce/tiny_mce.js"></script>
	<script language="javascript" type="text/javascript">
		tinyMCE.init({
		theme : "advanced",
		mode : "exact",
		elements : "form_content",
		plugins : "emotions,inlinepopups",
		language : "en",
		theme_advanced_buttons1 : "code,bold,italic,underline,bullist,undo,redo,link,unlink,forecolor,removeformat,removeformat,cleanup",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_styles : "Code=codeStyle;Quote=quoteStyle",
		content_css : "'.uhome().'/js/tiny_mce/content.css",
		entity_encoding : "raw",
		add_unload_trigger : false,
		remove_linebreaks : false,

		force_br_newlines  :  true,
		  force_p_newlines   :  false,
		  remove_linebreaks  : false,
		  relative_urls  :  false,
		  plaintext_create_paragraphs : false,
		  paste_create_paragraphs : false,
			
		  theme_advanced_buttons1_add : "emotions"

		});
	</script>
	';
	}

	// settings
	function get_header() {
		return '';
	}

	function get_footer() {
		return '';
	}

	// views
	function view_title($row) {
		return '<h1>'.$row['title'].'</h1>';
	}
	function view_created($row) {
		return '<p>'.get_date($row['created']).'</p>';
	}
	function view_username($row) {
		return '<p>'.url('user/'.$row['uid'],htmlspecialchars($row['username'])).'</p>';
	}
	function view_content($row) {
		$output = '<div class="story_content">'.$this->show_content($row['content']);
		if ($row['photos'] && $this->name != 'images') {
				$res = sql_query("select * from `".tb()."story_photos` where sid='{$row['id']}' "." ORDER by id DESC");
				$output .= '
							<script type="text/javascript">
							jQuery(document).ready(function($) {
							  $(\'a[rel*=lightbox]\').lightbox() ;
							})
						  </script>';
				$output .= '<div style="width:210px;float:right"><ul class="gallery">';
				while ($photo = sql_fetch_array($res)) {
					$output .= '<li><a href="'.uhome().'/'.$photo['uri'].'" rel="lightbox"><img src="'.uhome().'/'.$photo['thumb'].'" /></a><br />'.htmlspecialchars($photo['des']).'</li>';
				}
				$output .= '</ul></div>';
			}
		$output .= '</div>';
		return $output;
	}

	function show_content($content) {
		return $content;
	}

	// comments
	function comment_title($row) {
		return '<h3>'.$row['title'].'</h3>';
	}
	
	function comment_created($row) {
		return get_date($row['created']);
	}
	
	function comment_signature($row) {
		return htmlspecialchars($row['signature']);
	}

	function comment_username($row) {
		return url('user/'.$row['uid'],htmlspecialchars($row['username']));

	}

	function comment_content($row) {
		/* block signature
		return '
		<div class="post_content">
		'.nl2br(htmlspecialchars($row['content'])).'
		<div class="tab_things">'.get_date($row['created']).'</div>
		<div class="signature">'.$this->comment_signature($row).'</div>
		</div>';
		*/
		return '<i>'.get_date($row['created']).' '.t('Wrote').':</i><br />
		'.nl2br(htmlspecialchars($row['content'])).'
		';

	}


	// listings
	function list_title($row) {
		if ($row['sticky'])
			$sticky = t('Sticky').': ';
		return $sticky.url($this->name.'/viewstory/'.$row['id'],htmlspecialchars($row['title']));
	}
	
	function list_thumbnail($row) {
		global $uhome;
		if ($row['thumbnail']) {
			return '<img src="'.$uhome.'/'.$row['thumbnail'].'" />';
		}
		else {
			return false;
		}
	}
	
	function list_views($row) {
		return $row['views'].' '.t('Views');
	}

	function list_comments($row) {
		//return $row['comments'].' '.t('Comments');
		return '';
	}

	function list_votes($row) {
		$row['votes'] = $row['digg'] + $row['dugg'];
		if ($row['digg']) {
			return t('Rating').': '.ceil(($row['digg']*100)/$row['votes']).'%';
		}
		else {
			return false;
		}
	}
	
	function list_summary($row) {
		$row['content'] = preg_replace("/\[\w+](.*)\[\/\w+]/isU","\\1",$row['content']);
		return strip_tags(utf8_substr($row['content'],150),'<img>');
	}
	
	function list_created($row) {
		return get_date($row['created']);
	}

	function list_username($row) {
		return t('Posted by {1}',url('u/'.$row['username'],htmlspecialchars($row['username'])) );

	}
	
	// init
	function init() {
		global $nav, $current_app;
		$nav[] = $current_app['flag'];
	}
	
	
	// stories from the author
	function stories_from_author($story) {
		global $current_app,$uhome;
		$res = sql_query("select * from `".tb()."stories` where uid='{$story['uid']}' and app='{$current_app['name']}' "." order by id DESC LIMIT 5");
		while ($row = sql_fetch_array($res)) {
			if (!$row['thumbnail'] && $this->default_thumb) {
				$row['thumbnail'] = $this->default_thumb;
			}
			if ($row['thumbnail']) {
				$output .= '<table><tr><td width="25"><a href="'.url($current_app['name'].'/viewstory/'.$row['id']).'"><img src="'.$uhome.'/'.$row['thumbnail'].'" width="50" height="50" /></a></td>
				<td>'.get_date($row['created']).'<br />'.url($current_app['name'].'/viewstory/'.$row['id'],$row['title']).'</td></tr></table>';
			}
			else {
				$output .= '<table><tr><td>
				'.url($current_app['name'].'/viewstory/'.$row['id'],$row['title']).'
				<br />'.get_date($row['created']).'</td></tr></table>';
			}
		}
		ass(array('title'=>$this->stories_from_author,'content'=>$output));
	}
	
	// stories from the author
	function stories_from_cat($cid, $title) {
		global $current_app,$uhome;
		if ($cid > 0) {
			$where = " and cid='$cid' ";
		}
		$res = sql_query("select * from `".tb()."stories` where  app='{$current_app['name']}' $where order by id DESC LIMIT 10");
		while ($row = sql_fetch_array($res)) {
			if (!$row['thumbnail'] && $this->default_thumb) {
				$row['thumbnail'] = $this->default_thumb;
			}
			if ($row['thumbnail']) {
				$output .= '<table><tr><td width="25"><a href="'.url($current_app['name'].'/viewstory/'.$row['id']).'"><img src="'.$uhome.'/'.$row['thumbnail'].'" width="50" height="50" /></a></td>
				<td>'.url($current_app['name'].'/viewstory/'.$row['id'],$row['title']).'</td></tr></table>';
			}
			else {
				$output .= '<ul class="simple_list"><li>
				'.url($current_app['name'].'/viewstory/'.$row['id'],$row['title']).'</li></ul>';
			}
		}
		ass(array('title'=>$title,'content'=>$output));
	}
	
	// who voted
	/*
	function who_voted($sid) {
		global $current_app;
		$res = sql_query("select v.*,u.firstname from `".tb()."votes` as v LEFT JOIN `".tb()."accounts` AS u ON u.id=v.uid where v.sid='$sid' ORDER BY v.created DESC LIMIT 30");
		$output = '<ul class="float_left">';
		while ($row = sql_fetch_array($res)) {
			$output .= '<li>'.url('u/'.$row['uid'],htmlspecialchars($row['username'])).'</li>';
		}
		$output .= '</ul>';
		ass(array('title'=>t('People who voted'),'content'=>$output));
	}
	*/
	

	// tag cloud
	function tag_cloud($num) {
		$res = sql_query("select * from `".tb()."tags` where  app='{$this->name}' and num>0 order by num DESC LIMIT $num");
		if (sql_counts($res)) {
			while ($row = sql_fetch_array($res)) {
				$tag_cloud .= ' '.url($this->name.'/tag/'.$row['id'],htmlspecialchars($row['name'])).' <span class="sub">('.$row['num'].')</span> ';
			}
		}
		if (strlen($tag_cloud)) {
			ass(
				array('title' => t('Tags'), 'content'=>$tag_cloud)
				);
		}
	}

	function vote_form() {
		return '
			<p>
						'.label(t('Rate')).'
						<select name="vote">
						<option value="0">'.t('Not Rate').'</option>
						<option value="+3">+3('.t('Excellent').')</option>
						<option value="+2">+2('.t('Very good').')</option>
						<option value="+1" selected>+1('.t('Good').')</option>
						<option value="-1">-1('.t('Poor').')</option>
						<option value="-2">-2('.t('Terrible').')</option>
						</select>';
	}

	function convert_content_before_insert($content) {
		if (!file_exists('js/tiny_mce/jquery.tinymce.js')) {
			return nl2br(convert_html($content));
		}
		else {
			return convert_html($content);
		}
	}

}


function theme_list_inul($arr) {
	if (!$arr)
		$arr = array();
	$data = '<ul>';
	foreach ($arr as $item) {
		$data .= '<li>';
		foreach ($item as $key=>$val) {
			$data .= $val.' ';
		}
		$data .= '</li>';
	}
	$data .= '</ul>';
	return $data;
}

function theme_list_intable($arr, $atts) {
	if (!$arr)
		$arr = array();
	$data = '<table class="stories"><tr>';
	foreach ($atts as $att) {
		$data .= '<th>'.$att.'</th>';
	}
	$data .= '</tr>';
	foreach ($arr as $item) {
		$data .= '<tr>';
		foreach ($item as $key=>$val) {
			$data .= '<td>'.$val.'</td>';
		}
		$data .= '</tr>';
	}
	$data .= '</table>';
	return $data;
}


function theme_comments_inul($arr) {
	if (!$arr)
		$arr = array();
	$data = '<ul>';
	foreach ($arr as $item) {
		$data .= '<li>';
		foreach ($item as $key=>$val) {
			$data .= $val.' ';
		}
		$data .= '</li>';
	}
	$data .= '</ul>';
	return $data;
}

// 检测cid
function valid_category($cid,$app=0) {
	GLOBAL $db;
	if (!$app || $app == 'groups')
		$res = sql_query("select * from `".tb()."story_categories` where id='$cid' ");
	else
		$res = sql_query("select * from `".tb()."story_categories` where id='$cid' and app='$app' ");
	if(sql_counts($res)) {
		return sql_fetch_array($res);
	}
	else {
		return false;
	}
}

// 检测sid
function valid_story($sid) {
	GLOBAL $db;
	$res = sql_query("select * from `".tb()."stories` where id='$sid' ");
	if(sql_counts($res)) {
		return sql_fetch_array($res);
	}
	else {
		return false;
	}
}

// check comment
function valid_comment($cid) {
	GLOBAL $db;
	$res = sql_query("select * from `".tb()."story_comments` where id='$cid' ");
	if(sql_counts($res)) {
		return sql_fetch_array($res);
	}
	else {
		return false;
	}
}

// save tags
function save_tags($tags,$sid,$app) {
	if (is_array($tags)) {
		$res = sql_query("select * from `".tb()."tag_ids` where sid='$sid' ");
		while ($row = sql_fetch_array($res)) {
			sql_query("update `".tb()."tags` set num=num-1 where id='{$row['tid']}' ");
		}
		sql_query("delete from `".tb()."tag_ids` where sid='$sid' ");
		foreach ($tags as $tag) {
			$res = sql_query("select * from `".tb()."tags` where name='$tag' and app='$app' ");
			if ($row = sql_fetch_array($res)) {
				sql_query("insert into `".tb()."tag_ids` (tid,sid) values ('{$row['id']}','$sid')");
				sql_query("update `".tb()."tags` set num=num+1 where id='{$row['id']}' ");
			}
			else {
				sql_query("insert into `".tb()."tags` (name,app,num) values ('$tag','$app',1)");
				$tid = mysql_insert_id();
				sql_query("insert into `".tb()."tag_ids` (tid,sid) values('$tid','$sid')");
			}
		}
	}
}