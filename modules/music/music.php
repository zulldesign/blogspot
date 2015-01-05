<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */
// var1: song location
// var2: musician name
// var3: picture
class music extends story{
	
	function music() {
		$this->hooks(array('writestory','writestorypost','viewstory'));
		$this->write_story = t('Upload song');
		set_title(t('Music'));
		$this->stories_from_author = 1;
		$this->stories_from_cat = 1;
		$this->top_stories = 1;
		$this->tags = 1;
		$this->allow_vote = 1;
		$this->label_content = t('Description');
		$this->act_write = t('added a song');
		$this->label_title = t('Name of the song');
		$this->default_thumb = uploads.'/userfiles/undefined_song.gif';
		parent::story();
		
	}
	
	function hook_writestory($row) {
		return '
			<p><label>'.t('Upload').'</label><input type="file" name="song" />
			<span>'.t('Only mp3 is accepted').'</span></p>
			<p><label>'.t('Music source').'</label>
			<input type="radio" name="musicsource" value="own" checked="1" />'.t('My own').' 
			<input type="radio" name="musicsource" value="others" />'.t('From another musician').'
			</p>
			<div id="ms_others" style="display:none">
			<p><label>'.t('Musician name').'</label><input name="musician" /></p>
			</div>
			<div id="ms_own">
			<p></p>
			</div>
			<p><label>'.t('Picture').' ('.t('Optional').')</label><input type="file" name="picture" />
			<span>'.t('A picture about the song or the musician').'</span></p>
			<script type="text/javascript">
	    jQuery(document).ready(function($) {
	    	$("input[value=\'own\']").click(
	    		function() {
	    		$("#ms_others").hide();
	    		$("#ms_own").fadeIn();
	    		}
	    		);
	      $("input[value=\'others\']").click(
	    		function() {
	    		$("#ms_own").hide();
	    		$("#ms_others").fadeIn();
	    		}
	    		);
	    })
	  </script>';
	}

	function hook_writestorypost(&$story) {
		if ($_POST['musicsource'] == 'others') {
			if (!strlen($_POST['musician'])) {
				sys_back('If the song is not created by your own, you must enter the musician name');
			}
			$story['var2'] = $_POST['musician'];
		}
		if (!preg_match("/\.mp3$/i",$_FILES['song']['name'])) {
				sys_back('Unavaible mp3 format');
		}
		if ($_FILES['picture']['tmp_name']) {
			$thumb = save_thumbnail($_FILES['picture']);
			list($width, $height) = getimagesize($_FILES['picture']['tmp_name']);
			if ($width <= 580) {
				$picture = save_file($_FILES['picture']);
			}
			else {
				$height = floor(580*$height/$width);
				$picture = save_thumbnail($_FILES['picture'], 580, $height);
			}
			$story['thumbnail'] = $thumb;
			$story['var3'] = $picture;
		}
		
		// upload song
		$uri = save_file($_FILES['song'],array('mp3'));
		$story['var1'] = $uri;
	}
	
	function hook_viewstory($row) {
		global $uhome;
		if (strlen($row['var1'])) {
			if (!$row['var2']) {
				$row['var2'] = $row['username'];
			}
			if ($row['var3']) {
				$image = '<img src="'.$uhome.'/'.$row['var3'].'" />';
			}
			$encoded_uhome = urlencode($uhome.'/');
			$output = '
			<p id="audioplayer_1">Mp3 Player</p>
				<script type="text/javascript" src="'.uhome().'/js/player/audio-player.js"></script>  
		   
			<script type="text/javascript"> 
	$(document).ready(function(){
		AudioPlayer.setup("'.uhome().'/js/player/player.swf", {  
			width: 500  
		});  
		AudioPlayer.embed("audioplayer_1", {  
		soundFile: "'.$row['var1'].'",  
		titles: "'.h(addslashes($row['title'])).'",  
		artists: "'.h(addslashes($row['var2'])).'",  
		autostart: "yes",
		loop: "yes"
		}); 
			});
			</script>
			';
			return $output;
		}
		else {
			return '<p>'.$row['text1'].'</p>';
		}
	}
	
	function list_views($row) {
		return $row['views'].' '.t('Listens');
	}
}