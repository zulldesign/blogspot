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
var1: youtube id
var2: type

*/
$num_per_page = 15;
$offset = $num_per_page*($page-1);
class videos extends story{
	public $list_type = 'ul';

	function videos() {
		global $nav,$ubase,$sub_menu;
		$nav[] = url('videos',t('Videos'));
		set_title(t('Videos'));
		$this->write_story = t('Add Video');
		$this->top_stories = 1;
		$this->tags = 1;
		$this->list_type = 'gallery';
		$this->allow_vote = 1;
		$this->default_thumb = uploads.'/userfiles/undefined.jpg';
		$this->submit = t('Next step');
		$this->story_opts = array('tofavorite'=>1,'toprofile'=>1);
		$this->allow_favorite = 1;
		parent::story();
		$this->act_write = t('added a video');
		$this->label_entry = t('videos');
	}

	function hook_writestory($row) {
			return '
			<p>
			'.label('Youtube Video URL').'
			<input type="text" name="youtube_url" size="54" value="http://www.youtube.com/watch?v=" />
			<input type="hidden" name="videosource" value="youtube" />
			</p>
			';
	}

	function story_form_content($row = array()) {
		global $uhome;
		if (file_exists('js/tiny_mce/jquery.tinymce.js')) {
			return '<p>'.label(t('Video Description')).
				$this->tinymce_form().'
	<textarea name="form_content" rows="5" style="width:580px" class="rich" >'.htmlspecialchars($row['content']).'</textarea>
	</p>';
		}
		else {
			return '<p>'.label(t('Video Description')).'
	<textarea name="form_content" rows="5" style="width:580px" class="rich" >'.htmlspecialchars($row['content']).'</textarea>
	</p>';
		}
	}
	
	function hook_writestorypost(&$story) {
		if (!$_POST['title'] || !$_POST['form_content']) {
			sys_back('pls fill in all required blanks');
		}
		if ($_POST['videosource'] == 'local') {
			c(youtube_upload_form());
		}
		else {
			// valid youtube
			$youtube_id = str_replace('http://www.youtube.com/watch?v=','',$_POST['youtube_url']);
			$youtube_id = explode('&',$youtube_id);
			$youtube_id = $youtube_id[0];
			if (!valid_youtube_id($youtube_id)) {
				sys_back('Invalid Youtube video URL:'.$_POST['youtube_url']);
			}
			$res = sql_query("select id from `".tb()."stories` WHERE var1='{$youtube_id}'");
			$row = sql_fetch_array($res);
			if ($row['id']) {
				sys_back('Sorry, the video has already been existing in our site:'.url('videos/viewstory/'.$row['id'],'Here'));
			}
		}
		$story['var1'] = $youtube_id;
		$file = array('name' => 'default.jpg',
			'tmp_name' => 'http://i4.ytimg.com/vi/'.$youtube_id.'/default.jpg',
			'type' => 'jpg');
		$thumbnail = save_thumbnail($file);
		$story['thumbnail'] = $thumbnail;
	}

	function hook_viewstorybottom($story) {
		global $sub_menu;
		$sub_menu = array();

		if (!$story['thumbnail']) {
			if ((time() - $row['created']) > 1000) {
				if (valid_youtube_id($story['var1'])) {
					$file = array('name' => 'default.jpg',
						'tmp_name' => 'http://i4.ytimg.com/vi/'.$story['var1'].'/default.jpg',
						'type' => 'jpg');
					$thumbnail = save_thumbnail($file);
					sql_query("update `".tb()."stories` set thumbnail='$thumbnail' where id='{$row['id']}'");
				}
			}
		}
			return '
			<object width="480" height="295"><param name="movie" value="http://www.youtube.com/v/'.$story['var1'].'&hl=en&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$story['var1'].'&hl=en&fs=1" type="application/x-shockwave-flash" width="560" height="340" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
	}

	function ajax_form() {
		echo '
		<table><tr><td>'.t('Video Title').':</td><td><input type="text" size="45" name="video_title" /></td></tr>
		<tr><td>'.t('Description').':</td><td>
		<textarea rows="2" style="width:300px" name="description"></textarea>
		<br />
		'.t('Tags').': <input type="text" size="25" name="tags" /> <span class="sub">('.t('Separated with commas').')</span></td></tr>
		<tr><td>'.t('Video source').':</td><td>
		<input type="text" name="youtube_url" size="45" value="http://www.youtube.com/watch?v=" /></td></tr>
		</table>
		<div style="padding-right:25px;text-align:right">
		'.privacy_form().'</div>';
		exit;
	}

	function ajax_post() {
		global $client;
		if (!$_POST['youtube_url']) videos::ajax_error('please insert a video URL');
		$youtubeid = str_replace('http://www.youtube.com/watch?v=','',$_POST['youtube_url']);
		$youtubeid = explode('&',$youtubeid);
		$youtubeid = $youtubeid[0];
		if (strlen($youtubeid) < 6 || strlen($youtubeid) > 20)
			videos::ajax_error('wrong youtubeid');
		if (!$_POST['video_title']) videos::ajax_error(t('Please input a Title'));
		if (!$data = @file_get_contents("http://gdata.youtube.com/feeds/api/videos/".$youtubeid)) {
			videos::ajax_error('Invalid Youtube video URL:http://gdata.youtube.com/feeds/api/videos/'.$youtubeid);
		}
		else {
			if (!preg_match("/xml/i",$data)) {
				videos::ajax_error('Invalid Youtube video ID:'.$youtubeid);
			}
		}
		$vote_options['rating'] = t('Rating');
		foreach ($vote_options as $key=>$vla) {
			$ratings[$key] = array('score'=>0,'users'=>0);
		}
		$page = story::check_page_access($_POST['page_id']);
		$file = 'http://i4.ytimg.com/vi/'.$youtubeid.'/default.jpg';
		$thumbnail = save_img($file,'jpg');
		$story = array(
			'cid' => 0,
			'page_id' => $_POST['page_id'],
			'page_type'=>$page['type'],
			'title' => $_POST['video_title'],
			'content' => $_POST['description'],
			'uid' => $client['id'],
			'created' => time(),
			'var1' => $youtubeid,
			'var5' => $_POST['privacy'],
			'thumbnail' => $thumbnail,
			'app' => 'videos',
			'rating' => serialize($ratings)
			);
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
		if (sql_insert($story, tb().'stories')) {
			$sid = $story['id'] = mysql_insert_id();
			save_tags($stags,$sid,'videos');
			// write act
			$attachment = array(
				'cwall_id' => 'videos'.$sid,
				'uri' => 'videos/viewstory/'.$sid,
				'name' => $_POST['video_title'],
				'thumb' => $thumbnail
				);
			$app = array('name'=>'videos','id'=>$sid);
			$stream_id = stream_publish(t('added a video'),$attachment,$app);
			$set_story['id'] = $sid;
			$set_story['stream_id'] = $stream_id;
			sql_update($set_story,tb()."stories");
			echo '<span style="background:yellow;color:black">'.t('Video Added!').'</span> <a href="'.url('videos/viewstory/'.$sid).'"><strong>'.t('View').'</strong></a>';
			echo videos::ajax_form();
		}
		else {
			videos::ajax_error('failed to add video');
		}
	}
	function ajax_error($msg) {
		echo '<div style="color:red">'.$msg.'</div>';
		echo videos::ajax_form();
		exit;
	}

}

function get_video_thumbnail($embed) {
	if (preg_match("/youtube\.com/i",$embed)) { // youtube
		preg_match_all("|youtube.com\/v\/([0-9a-zA-Z_]+)|", $embed,$tmp);
		return 'http://img.youtube.com/vi/'.$tmp[1][0].'/default.jpg';
	}
	else {
		return false;
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

function check_video_status() {
	
	try { 
		$control = $videoEntry->getControl(); 
		} 
	catch (Zend_Gdata_App_Exception $e) { 
		echo $e->getMessage();
	} 
	if ($control instanceof Zend_Gdata_App_Extension_Control) { 
		if ($control->getDraft() != null && $control->getDraft()->getText() == 'yes') { 
			$state = $videoEntry->getVideoState(); 
			if ($state instanceof Zend_Gdata_YouTube_Extension_State) { 
				print 'Upload status: '. $state->getName() .' '. $state-

getText();

} else { print "Not able to retrieve the video status information yet. Please try again shortly.\n"; } } } 
}