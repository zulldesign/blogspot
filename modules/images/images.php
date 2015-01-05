<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */
/*
text1: picids
*/
class images extends story{
	function images() {
		global $nav,$ubase;
		$nav[] = url('images',t('Images'));
		set_title(t('Images'));
		$this->top_stories = 1;
		$this->disable_category = 0;
		$this->images = 1;
		$this->allow_vote = 0;
		$this->list_type = 'gallery';
		$this->default_thumb = uploads.'/userfiles/undefined.jpg';
		$this->write_story = t('Upload');
		parent::story();
		$this->act_write = t('Uploaded image');
		$this->label_title = t('Image Name');
		$this->submit = t('Next step');
		$this->label_entry = t('Images');
	}

	function story_form_content($row = array()) {
		global $uhome;
		if (file_exists('js/tiny_mce/jquery.tinymce.js')) {
			return '<p>'.label(t('Album Description')).
				$this->tinymce_form().'
	<textarea name="form_content" rows="5" style="width:580px" class="rich" >'.htmlspecialchars($row['content']).'</textarea>
	<input type="hidden" name="images" value="1" />
	</p>';
		}
		else {
			return '<p>'.label(t('Album Description')).'
	<textarea name="form_content" rows="5" style="width:580px" class="rich" >'.htmlspecialchars($row['content']).'</textarea>
	<input type="hidden" name="images" value="1" />
	</p>';
		}
	}


		// 文章表单
	function writestory($page_id=0) {
		do_auth($this->story_write);
		clear_as();
		GLOBAL $ubase,$content,$nav, $client, $title, $sub_menu, $ass,$current_app,$cat_id;
		if ($page_id) {
			$page = $this->check_page_access($page_id);
			$page_id = $page['id'];
		}
		else {
			$page_id = $client['page']['id'];
		}

		// choose album
		$res = sql_query("SELECT * FROM `".tb()."stories` WHERE uid='{$client['id']}' and app='images' and page_id='{$page_id}' order by id DESC");
		if (sql_counts($res)) {
			c('<ul>');
			while ($row = sql_fetch_array($res)) {
				c('<li>'.url('images/managephotos/'.$row['id'],htmlspecialchars($row['title'])).' ('.get_date($row['created']).')</li>');
			}
			c('</ul>');
		}
		else {
			c('<p>'.t('You have no photo album').'</p>');
		}
		section_close(t('Choose an album to continue'));


		$cat_id = $cid;
		$sub_menu = $ass = '';
		$nav[] = $this->write_story;
		$this->set_current_sub_menu($cid);
		$title = $this->write_story;
		c('<div class="form"><form action="'.$ubase.$this->name.'/'.$this->writepost.'" method="post"  enctype="multipart/form-data">');
		if (!$this->disable_category) {
			c($this->story_form_cat($cid));
		}
		c($this->writestory_form_elements($row));
		if ($this->hook['writestory']) {
			c($this->hook_writestory($row));
		}
		c('<input type="hidden" name="images" value="1" />');
		c('<p><input type="hidden" name="page_id" value="'.$page_id.'" /><input class="button" type="submit" value="'.$this->submit.'" /></p>');
		c('</form></div>');
		section_close(t('Create a New album'));
	}



	function viewstory($sid) {
		GLOBAL $db,$client,$ubase,$uhome,$nav,$content, $title, $page_title, $page, $client,$cat_id, $num_per_page, $offset, $config;
		clear_as();
		$res = sql_query("select s.*,u.birthyear,u.gender,u.location,u.avatar,u.username from `".tb()."stories` as s left join `".tb()."accounts` as u on u.id=s.uid where s.id='$sid' ");
		$row = sql_fetch_array($res);
		if (!$row['id']) die('wrong sid');

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
		<a href="'.url($this->name.'/liststories/user_'.$row['username']).'">'.t("{1}'s {2}",$row['username'],$this->label_entry).'</a>
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
					$res = sql_query("select * from `".tb()."tags` where  name='".addslashes($tag)."' and app='images'");
					if ($tag = sql_fetch_array($res)) {
						$tagstr .= $tagstr ? ', '.url($this->name.'/tag/'.$tag['id'],'<img src="'.uhome().'/files/icons/tags.gif" /> '.$tag['name']).'<span class="sub">('.$tag['num'].')</span>' : url($this->name.'/tag/'.$tag['id'],'<img src="'.uhome().'/files/icons/tags.gif" /> '.$tag['name']).'<span class="sub">('.$tag['num'].')</span>';
					}
				}
				$story .= $tagstr;
				$stroy .= '</p>';
			}
			$story .= '<div id="sp_block_content_bottom">'.$config['sp_content_bottom'].show_ad('sp_block_content_bottom').'</div>';

			if ($this->allow_vote && strlen($row['rating']) > 10) {
				if ($client['id']) {
					$res = sql_query("select * from `".tb()."votes` where uid='{$client['id']}' and sid='{$sid}' limit 1");
					if (sql_counts($res)) {
						$vote_button = '';
						$vote_disable = '$("input").rating("readOnly",true);';
					}
					else {
						$vote_button = '<input type="button" style="font-size:10px" value="'.t('Submit').'" id="sendrate" />';
						$vote_disable = '';
					}
				}
				else {
					$vote_button = '';
					$vote_disable = '$("input").rating("readOnly",true);';
				}
				$story .= '<div id="rating_box">
				<script src="'.uhome().'/js/starrating/jquery.MetaData.js" type="text/javascript" language="javascript"></script>
					<script src="'.uhome().'/js/starrating/jquery.rating.js" type="text/javascript" language="javascript"></script>
					 <link href="'.uhome().'/js/starrating/jquery.rating.css" type="text/css" rel="stylesheet"/>
					 <form id="starrate">
					 <table border="0">
					 <td>';
				$ratings = unserialize($row['rating']);
				if (!is_array($ratings)) $ratings = array();
				foreach ($ratings as $key=>$rating) {
					$ratecheck = array();
					if ($rating['users']) {
						$rate = ceil($rating['score']/$rating['users']);
					}
					else {
						$rate = 0;
					}
					$ratecheck[$rate] = 'checked';
					$story .= ' 
					 <div style="width:100%;clear:both">'.$this->vote_options[$key].'</div>
					 <div style="width:100%;clear:both">
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="1" '.$ratecheck['1'].' />
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="2" '.$ratecheck['2'].' />
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="3" '.$ratecheck['3'].' />
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="4" '.$ratecheck['4'].' />
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="5" '.$ratecheck['5'].' />
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="6" '.$ratecheck['6'].' />
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="7"/ '.$ratecheck['7'].' >
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="8"/ '.$ratecheck['8'].' >
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="9"/ '.$ratecheck['9'].' >
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="10"/ '.$ratecheck['10'].' >
					  </div>
					  ';
				}
				$story .= '	  
					  </td>
					  <td>
					  <span id="sendbox">'.$vote_button.'</span>
					  <span id="votesnum" class="sub">'.$row['dugg'].'</span> <span class="sub">vote(s)</span>
					  </td>
					  </table>
					  <script>
					  $(document).ready( function(){
						  '.$vote_disable.'
						  $("#sendrate").click(function() {
							  $("#sendbox").html("<img src=\''.$uhome.'/files/loading.gif\' width=16 height=16 />");
							  $("input").rating("readOnly",true);
								$.post("'.$uhome.'/index.php?p=jquery/ratestory",{
									rate:$("form#starrate").serialize(),
									sid:$("#story_id").val()},
									function(data) {
												$("#sendbox").html("sent");
												$("#votesnum").html(data);
											},\'html\');
								return false;

							});
						});
						</script>
				';
				$story .= '</div>';
			}
			
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
				comment_form($row['stream_id']).comment_get($row['stream_id'],100)
				);
		}
			
		
	}

	function ajax_form($page_type='',$page_id=0) {
		global $client;
		if (!$client) die('login');
		if (!$page_type) $page_type = 'u';
		echo images::upload_form($page_type);
		exit;
	}

	function ajax_post() {
		global $client;
		if (!$_FILES['images']['tmp_name'][0]) images::ajax_error('No photo selected');
		if (is_numeric($_POST['gallery_id'])) {
			$res = sql_query("select * from ".tb()."stories where id='{$_POST['gallery_id']}' and uid='{$client['id']}' and app='images'");
			$story = sql_fetch_array($res);
			if (!$story['id']) die('wrong gallery id');
		}
		else {
			if ($_POST['gallery_name']) {
				$gallery_name = $_POST['gallery_name'];
			}
			elseif ($_POST['descriptions'][0]) {
				$gallery_name = $_POST['descriptions'][0];
			}
			else {
				if (count($_FILES['images']['tmp_name'])>1) {
					$gallery_name = 'gallery';
				}
				else {
					$gallery_name = 'image';
				}
			}
			$page = story::check_page_access($_POST['page_id']);
			$story = array(
				'cid' => 0,
				'page_id' => $_POST['page_id'],
				'page_type'=>$page['type'],
				'title' => $gallery_name,
				'content' => '',
				'uid' => $client['id'],
				'created' => time(),
				'photos'=>0,
				'var5' => $_POST['privacy'],
				'app' => 'images'
				);
			sql_insert($story, tb().'stories');
			$story['id'] = mysql_insert_id();
			// write act
			$attachment = array(
				'cwall_id' => 'images'.$story['id'],
				'uri' => 'images/viewstory/'.$story['id'],
				'name' => $_POST['album_name']
				);
			$args = array(
				'message'=>addslashes(t('uploaded image')),
				'link' => 'images/viewstory/'.$story['id'],
				'name' =>  $gallery_name,
				'app' => 'images',
				);
			$stream_id = jcow_page_feed($_POST['page_id'],$args);
			$set_story['stream_id'] = $story['stream_id'] = $stream_id;
		}
		$sid = $album_id = $story['id'];
		$set_story['id'] = $story['id'];
		
		$photos = $story['photos'];
		foreach ($_FILES['images']['tmp_name'] as $key=>$file_tmp_name) {
			if ($file_tmp_name && $key<3) {
				$photo = array('name'=>$_FILES['images']['name'][$key],
				'tmp_name'=>$file_tmp_name,
				'type'=>$_FILES['images']['type'][$key],
				'size'=>$_FILES['images']['size'][$key]);

				list($width, $height) = getimagesize($file_tmp_name);
				if ($width <= 740) {
					$uri = save_file($photo);
				}
				else {
					$height = floor(740*$height/$width);
					$uri = save_thumbnail($photo, 740, 0);
				}
				$photos++;
				$thumb = save_thumbnail($photo, 100, 100);
				$size = $photo['size'];
				sql_query("insert into `".tb()."story_photos` (sid,uri,des,thumb,size) values( {$sid},'$uri','".$_POST['descriptions'][$key]."','$thumb','$size')");
				
			}
		}
		$set_story['thumbnail'] = $thumb;
		$set_story['photos'] = $photos ;
		sql_update($set_story,tb()."stories");

		// update stream
		$res = sql_query("select thumb from ".tb()."story_photos where sid='{$story['id']}' order by id DESC limit 3");
		while ($photo = sql_fetch_array($res)) {
			$thumbs[] = $photo['thumb'];
			$pics = ' ('.$photos.')';
		}
		$attachment = array(
				'cwall_id' => 'images'.$story['id'],
				'uri' => 'images'.'/viewstory/'.$story['id'],
				'name' => addslashes($story['title']),
				'thumb' => $thumbs
				);
		$app = array('name'=>'images','id'=>$story['id']);
		$stream_id = stream_update(addslashes(t('Uploaded image').$pics),$attachment,$app, $story['stream_id']);

		echo '<span style="background:yellow;color:black">'.t('Upload success!').'</span> <a href="'.url('images/viewstory/'.$album_id).'"><strong>'.t('View').'</strong></a>';
		if ($photos > 1) {
			echo images::gallery_form($_POST['page_type'],$sid);
		}
		else {
			echo images::ajax_form($_POST['page_type'],$_POST['page_id']);
		}
		
		exit;
	}

	function ajax_create_gallery($page_type='') {
		echo images::gallery_form($page_type);
		exit;
	}

	function gallery_form($page_type='',$gallery_id=0) {
		if ($page_type == 'u' || $_REQUEST['page_type'] == 'u') {
			$privacy_form = privacy_form();
		}
		$gallery_name = '<tr><td colspan="2">'.t('Overall Caption').': <input type="text" name="gallery_name" size="50" /></td></tr>';
		if ($gallery_id>0) {
			$res = sql_query("select id,title,photos from ".tb()."stories where id='$gallery_id'");
			$story = sql_fetch_array($res);
			if (strlen($story['title'])) {
				$gallery_name = '<tr><td colspan="2">'.t('Upload more images to {1}',url('images/viewstory/'.$story['id'],h($story['title']))).' (<strong>'.$story['photos'].'</strong> uploaded)
				<input type="hidden" name="gallery_id" value="'.$story['id'].'" /></td></tr>';
			}
		}
		$output = '
		<script>
				function fresh_apps_box(freshurl) {
					$("#apps_box").html("");
					$("span#spanstatus").html("<img src=\"'.uhome().'/files/loading.gif\" /> Loading");
					$("#apps_box").load(freshurl, function() {
						$("span#spanstatus").html("");
					});
				}
				$("#add_another_photo").click(function() {
					$("#add_another_photo_box").before("<tr><td><input type=\"file\" name=\"images[]\" /></td><td><input type=\"text\" size=\"35\" name=\"descriptions[]\" /></td></tr>");
				});
	</script><table border="0">
	'.$gallery_name.'
		<tr><td><input type="file" name="images[]" /></td><td><input type="text" size="35" name="descriptions[]" /></td></tr>
		<tr><td><input type="file" name="images[]" /></td><td><input type="text" size="35" name="descriptions[]" /></td></tr>
		<tr><td><input type="file" name="images[]" /></td><td><input type="text" size="35" name="descriptions[]" /></td></tr>
		</table>
		<div style="padding-right:25px;text-align:right">
		'.$privacy_form.'</div>
		';
		return $output;
	}

	function upload_form($page_type='') {
		if ($page_type == 'u' || $_REQUEST['page_type'] == 'u') {
			$privacy_form = privacy_form();
		}
		$output = '
		<script>
				function fresh_apps_box(freshurl) {
					$("#apps_box").html("");
					$("span#spanstatus").html("<img src=\"'.uhome().'/files/loading.gif\" /> Loading");
					$("#apps_box").load(freshurl, function() {
						$("span#spanstatus").html("");
					});
				}
				$("#add_another_photo").click(function() {
					$("#add_another_photo_box").before("<tr><td><input type=\"file\" name=\"images[]\" /></td><td><input type=\"text\" size=\"35\" name=\"descriptions[]\" /></td></tr>");
				});
	</script><table border="0">
	<tr><td>'.t('Upload').$_REQUEST['gallery_name'].'</td><td>'.t('Caption').'</td></tr>
		<tr><td><input type="file" name="images[]" /></td><td><input type="text" size="35" name="descriptions[]" /></td></tr>
		<tr id="add_another_photo_box"><td colspan="2">'.t('or').' <a href="javascript:void();" onclick="javascript:fresh_apps_box(\''.url('images/ajax_create_gallery/'.$page_type.'').'\')">'.t('Upload multiple images').'</a></td></tr></table>
		';
		return $output;
	}

	function ajax_error($msg) {
		echo '<div style="color:red">'.$msg.'</div>';
		echo images::ajax_form();
		exit;
	}


}