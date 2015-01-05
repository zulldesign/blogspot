<?php

class events extends story{
	public $list_type = 'ul';
	function events() {
		global $nav,$ubase;
		$nav[] = url('events',t('Events'));
		parent::story();
		$this->photos = 1;
		$this->act_write = t('Created an event');
		$this->write_story = t('Create event');
		set_menu_path('events');
	}
	
	function convert_content_before_insert($content) {
		return nl2br(convert_html($content));
		
	}

	function hook_writestory($row) {
		$output = '
		<script>
		$(document).ready( function(){
			$("#datepicker").datepicker({
				onSelect: function(date, instance) {
						$("#event_date").val(date);
				}
			});

		});

		</script>
		<div style="padding-left:20px">
			<table border="0"><tr><td>
			<div type="text" id="datepicker"></div>
			<input type="hidden" name="date" id="event_date" value="'.date('m/d/Y').'" />
			</td>
			<td valign="top">
							<p>'.label(t('Time')).timeselector().'
							</p>
							<p>
								'.label(t('Location')).'
								<input type="text" name="location" size="40" />
								</p>
			</td>
			</tr>
			</table>
		</div>
		
';
		return $output;
	}
	
	function hook_writestorypost(&$story) {
		global $client;
		
		$timeline = strtotime($_POST['date'].' '.$_POST['time']);
		$story['var1'] = $_POST['location'];
		$story['var2'] = $timeline;
		$uids[] = $client['id'];
		$text1 = serialize($uids);
		$story['text1'] = $text1;
	}
	
	function hook_viewstory($row) {
		global $client;
		$output .='<p><strong>'.t('Joined members').':</strong><br />';
		$uids = unserialize($row['text1']);

		if (is_array($uids)) {
			if (in_array($client['id'],$uids)) {
				$joined = 1;
			}
			foreach ($uids as $uid) {
				$res = sql_query("select username from `".tb()."accounts` where id='$uid'");
				$u = sql_fetch_array($res);
				$output .= url('u/'.$u['username'],$u['username']).' ';
			}
		}
		if (!$joined) {
			button('events/joinit/'.$row['id'],t('I want join'));
		}
		else {
			button('events/leaveit/'.$row['id'],t('Leave this event'));
		}
		$output .= '</p>';
		$output .= '
				<p>
				<strong>'.t('Location').':</strong> '.$row['var1'].'
				</p>
				<p>
				<strong>'.t('Time').':</strong> '.get_date($row['var2']).'
				</p>
				';
		return $output;
	}
	
	function joinit($sid) {
		global $client;
		$story = valid_story($sid);
		$uids = unserialize($story['text1']);
		if (is_array($uids)) {
			if (in_array($client['id'],$uids)) {
				sys_back(t('You have joined this event'));
			}
		}
		$uids[] = $client['id'];
		$text1 = serialize($uids);
		sql_query("update `".tb()."stories` set text1='$text1' where id={$story['id']}");
		redirect('events/viewstory/'.$story['id'],t('Opration success'));
	}
	function leaveit($sid) {
		global $client;
		$story = valid_story($sid);
		$uids = unserialize($story['text1']);
		foreach ($uids as $uid) {
			if ($uid != $client['id']) {
				$nuids[] = $uid;
			}
		}
		$text1 = serialize($nuids);
		sql_query("update `".tb()."stories` set text1='$text1' where id={$story['id']}");
		redirect('events/viewstory/'.$story['id'],t('Opration success'));
	}
	
	function story_form_content($row = array()) {
		return '<p>'.label(t('Description')).'<textarea name="form_content" rows="3" >'.htmlspecialchars($row['content']).'</textarea></p>';
	}

	function ajax_form($page_type='') {
		global $client;
		if (!$client) die('login');
		if ($page_type == 'u' || $_REQUEST['page_type'] == 'u') {
			$privacy_form = privacy_form();
		}
		echo '
				
		<script>
		$(document).ready( function(){
			$("#datepicker").datepicker({
				onSelect: function(date, instance) {
						$("#event_date").val(date);
				}
			});

		});

		</script>
		<div style="padding-left:20px">
			<table border="0"><tr><td width="235">
			<div type="text" id="datepicker"></div>
			<input type="hidden" name="date" id="event_date" value="'.date('m/d/Y').'" />
			</td>
			<td valign="top">
			<p>'.label(t('Event title')).'<input type="text" name="event_title" size="20" /></p>
			<p>'.label(t('Description')).'<textarea name="description" style="width:200px;height:50px"></textarea></p>
							<p>'.label(t('Time')).timeselector().'
							</p>
							<p>
								'.label(t('Location')).'
								<input type="text" name="location" size="20" />
								</p>
			</td>
			</tr>
			</table>
		</div>
		<div style="padding-right:25px;text-align:right">
		'.$privacy_form.'</div>';
		exit;
	}

	function ajax_post() {
		global $client;
		if (!$client) die('login');
		if (!$_POST['event_title']) events::ajax_error(t('Please input a Title'));
		$vote_options['rating'] = t('Rating');
		foreach ($vote_options as $key=>$vla) {
			$ratings[$key] = array('score'=>0,'users'=>0);
		}
		$page = story::check_page_access($_POST['page_id']);
		$story = array(
			'cid' => 0,
			'page_id' => $_POST['page_id'],
			'page_type'=>$page['type'],
			'title' => $_POST['event_title'],
			'content' => $_POST['description'],
			'uid' => $client['id'],
			'created' => time(),
			'app' => 'events',
			'var5' => $_POST['privacy'],
			'rating' => serialize($ratings)
			);
		$timeline = strtotime($_POST['date'].' '.$_POST['time']);
		$story['var1'] = $_POST['location'];
		$story['var2'] = $timeline;
		$uids[] = $client['id'];
		$text1 = serialize($uids);
		$story['text1'] = $text1;
		if (sql_insert($story, tb().'stories')) {
			$sid = $story['id'] = mysql_insert_id();
			save_tags($stags,$sid,'events');
			// write act
			$attachment = array(
				'cwall_id' => 'events'.$sid,
				'uri' => 'events/viewstory/'.$sid,
				'name' => $_POST['event_title']
				);
			$app = array('name'=>'events','id'=>$sid);
			$stream_id = stream_publish(t('started an event'),$attachment,$app,$client['id'],$_POST['page_id']);
			$set_story['id'] = $sid;
			$set_story['stream_id'] = $stream_id;
			sql_update($set_story,tb()."stories");
			echo '<span style="background:yellow;color:black">'.t('Event Added!').'</span> 
			<a href="'.url('events/viewstory/'.$sid).'"><strong>'.t('View').'</strong></a>';
		}
		else {
			events::ajax_error('failed to add event');
			
		}
		echo events::ajax_form();
		exit;
	}

	function ajax_error($msg) {
		echo '<div style="color:red">'.$msg.'</div>';
		echo events::ajax_form();
		exit;
	}
}
