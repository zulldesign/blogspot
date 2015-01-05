<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class dashboard{
	function dashboard() {
		global $content, $db, $apps, $client, $settings, $tab_menu, $current_sub_menu, $menuon;
		do_auth(2);
		clear_as();
		$menuon = 'dashboard';
		set_menu_path('dashboard');

	}

	function index() {
		global $content, $db, $apps, $client, $settings, $config;
		if ($client['id']) {
			if ($slogan = get_gvar('site_slogan')) {
				set_title($slogan);
			}
			else {
				set_title(t('Home'));
			}
			if (count($client['settings']['actived_widgets'])) {
				$hide_mw = 'style="display:none"';
			}

			c(
				'
			<script>
			$(document).ready( function(){
				$("#toggle_jcow_mw").click(function() {
					$("#jcow_mw").toggle("fast");
					return false;
				});
			});
			</script>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr><td>
			</td><td valign="bottom" width="220">
			<div style="font-size:15px;padding:5px;background:#ECF4FD;border:#C1DCF9 1px solid;border-width:1px 1px 0 1px">
			<a href="#" id="toggle_jcow_mw">+ '.t('Add/Remove Widgets').'</a>
			</div>
			</td></tr>
			</table>'
				);
			
			c('<div style="border:#C1DCF9 1px solid;margin-bottom:5px;">
			<style>
			#widgets_gallery td {
				 padding:2px;
				 border-bottom:#eeeeee 1px solid;
		}
		</style>
		<div id="jcow_mw" '.$hide_mw.'>
		<form action="'.url('dashboard/save_widgets').'" method="post">
			<table border="0" id="widgets_gallery" width="100%">
			<tr><td align="right" width="50">Active</td><td width="150">Name</td><td>'.t('Description').'</td></tr>
			');
			$hooks = check_hooks('widget');
			$widgets = array();
			if (is_array($hooks)) {
				foreach ($hooks as $hook) {
					$hook_func = $hook.'_widget';
					$hook_func($widgets);
				}
			}
			foreach($widgets as $key=>$widget) {
				$checked = isset($client['settings']['actived_widgets'][$key]) ? 'checked' : '';
				c('<tr><td align="right">
				<input type="checkbox" name="widgets[]" id="jcow_w_'.$key.'" value="'.$key.'" '.$checked.' /></td>
				<td>
				<label for="jcow_w_'.$key.'"><span class="widget_name">'.h($widget['name']).'</span></td>
				<td>
				<span class="sub">'.h($widget['description']).'</span></td></tr>');
			}
			c('</table>
			<input type="submit" value="'.t('Save changes').'" />
			</form>
			</div>
		
		</div>
');

	c('
				<style>
	.widget_name {CURSOR:pointer;font-weight:bold}
	.portlet { margin: 0 1em 1em 0; }
	.portlet-header { margin: 0.3em; padding-bottom: 4px; padding-left: 0.2em;CURSOR:move }
	.portlet-header .ui-icon { float: right; }
	.portlet-content { padding: 0.4em; }
	.ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 50px !important; }
	.ui-sortable-placeholder * { visibility: hidden; }
	</style>
	');
if (is_array($client['settings']['actived_widgets'])) {
	c('
	<script>
	$(function() {

		$( ".jcow_widgets_column" ).sortable(
			  { connectWith: ".jcow_widgets_column" }, { update: function(event, ui) {
						var out = "";
						out += $("#jcow_w_l").sortable("serialize");
						out += \'&r=1&\'+$("#jcow_w_r").sortable("serialize");
						if ($(this).attr("id") == "jcow_w_r") {
							$.ajax({
							   type: "POST",
							   url: "'.url('dashboard/update_widgets').'",
							   data: out
							 });
						}
					}
				}

		);

		$( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
			.find( ".portlet-header" )
				.addClass( "ui-widget-header ui-corner-all" )
				.prepend( "<span class=\'ui-icon ui-icon-minusthick\'></span>")
				.end()
			.find( ".portlet-content" );

		$( ".portlet-header .ui-icon" ).click(function() {
			$( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
			$( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
		});

		$( ".jcow_widgets_column" ).sortable({ handle: ".portlet-header" });

		$( ".jcow_widgets_column" ).disableSelection();



	});
	</script>

	<div class="jcow_widgets">
	<div class="jcow_widgets_column" id="jcow_w_l">
	');
	foreach ($client['settings']['actived_widgets'] as $key=>$actived_widget) {
		if (is_array($widgets[$key])) {
			if ($actived_widget['position'] == 'l') {
				$widget = $widgets[$key];
				$callback = $widget['callback'];
				$return = $callback();
				c('
				<div class="portlet" id="'.$key.'_'.$actived_widget['weight'].'">
					<div class="portlet-header">'.h($widget['name']).'</div>
					<div class="portlet-content">'.$return.'</div>
				</div>');
			}
		}
	}
	c('

	</div>

	<div class="jcow_widgets_column" id="jcow_w_r">
	');

	foreach ($client['settings']['actived_widgets'] as $key=>$actived_widget) {
		if (is_array($widgets[$key])) {
			if ($actived_widget['position'] == 'r') {
				$widget = $widgets[$key];
				$callback = $widget['callback'];
				$return = $callback();
				c('
				<div class="portlet" id="'.$key.'_'.$actived_widget['weight'].'">
					<div class="portlet-header">'.h($widget['name']).'</div>
					<div class="portlet-content">'.$return.'</div>
				</div>');
			}
		}
	}
		c('
	</div>
	</div>
	<div style="width:100%;clear:both"></div>
	<div style="padding:5px;margin:3px;border:#eeeeee 1px solid;background:white;color:#666666;font-weight:bold;">
<img src="'.uhome().'/modules/apps/help.png" /> '.t('You can drag/drop the items').'</div>

				');
			}
		}
	}

	private function newsfeed($num=5) {
		global $client;
		$uids[] = $client['id'];
		$res = sql_query("select f.fid from ".tb()."friends as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$client['id']}' order by u.lastlogin desc limit 5");
		while ($row = sql_fetch_array($res)) {
			$uids[] = $row['fid'];
		}
		$res = sql_query("select f.fid from ".tb()."followers as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$client['id']}' order by u.lastlogin desc limit 5");
		while ($row = sql_fetch_array($res)) {
			$uids[] = $row['fid'];
		}
		if (is_array($uids)) {
			$output .= activity_get($uids,$num,0,0,1);
		}
		else $output = t('No people');
		return array('title'=>t('News feed'),'content'=>$output);
	}

	function save_widgets() {
		global $client;
		if (is_array($_POST['widgets'])) {
			$position = 'l';
			foreach ($_POST['widgets'] as $widget) {
				if (!isset($client['settings']['actived_widgets'][$widget])) {
					$actived_widgets[$widget] =$position;
					$position = ($position == 'l') ? 'r':'l';
				}
			}
			if (is_array($client['settings']['actived_widgets'])) {
				foreach ($client['settings']['actived_widgets'] as $key=>$position) {
					if (in_array($key,$_POST['widgets'])) {
						$actived_widgets[$key] = $position;
					}
				}
			}
		}
		else {
			$actived_widgets = array();
		}
		$arr = array('actived_widgets'=>$actived_widgets);
		save_u_settings($arr);
		redirect('dashboard',1);

	}

	function update_widgets() {
		global $client;
		$p = 'l';
		$actived_widgets = array();
		if (is_array($_POST)) {
			foreach ($_POST as $key=>$value) {
				if ($key == 'r') {
					$p = 'r';
				}
				if (isset($client['settings']['actived_widgets'][$key])) {
					$actived_widgets[$key] = $p;
				}
			}
		}
		$arr = array('actived_widgets'=>$actived_widgets);
		save_u_settings($arr);
		print_r($_POST);
		exit;
	}
	
}



function my_account() {
		global $client, $apps;
		if (!$client['id']) return false;
		$res = sql_query("select * from `".tb()."pages` where uid='{$client['id']}' and type='u'");
		$row = sql_fetch_array($res);
		$profile_views = $row['views'];
		$res = sql_query("select count(*) as num from ".tb()."friends where uid='{$client['id']}'");
		$row = sql_fetch_array($res);
		$friends = $row['num'];
		$res = sql_query("select count(*) as num from ".tb()."followers where fid='{$client['id']}'");
		$row = sql_fetch_array($res);
		$followers = $row['num'];
		$content = 
			t('Your profile was viewed {1} times.','<strong>'.$profile_views.'</strong>').'
		<div class="hr"></div>'.
			t('You have {1} friends and {2} followers.','<strong>'.$friends.'</strong>','<strong>'.$followers.'</strong>');

		
		$content .= '<div class="hr"></div>';
		$content .= '
		<ul>
		<li>'.url('u/'.$client['username'],t('My Profile')).'</li>
		<li>'.url('follow/myfollowers',t('My Followers').'('.$followers.')' ).'</li>
		<li>'.url('follow/imfollowing',t('My Following') ).'</li>
		<li>'.url('preference',t('Preference')).'</li>
		</ul>';
		
		return array('title'=>t('Account'), 'content' => $content);
	}

function friends_birthday() {
	global $client;
	$m = date('n');
	$d = date('j');
	$next = $m+1;
	if ($m<10) $m = '0'.$m;
	if ($next > 12) $next = '01';
	if ($d > 20) {
		$nextm = " or (f.uid='{$client['id']}' and birthmonth='$next' and birthday<$d) ";
	}
	$res = sql_query("select u.* from ".tb()."friends as f left join ".tb()."accounts as u on u.id=f.fid where (f.uid='{$client['id']}' and u.birthmonth='$m' and u.birthday>$d) $nextm  order by u.lastlogin desc limit 15");
	$content = '<ul>';
	while ($user = sql_fetch_array($res)) {
		$total++;
		if ($user['birthmonth'] < 10) $user['birthmonth'] = '0'.$user['birthmonth'];
		if ($user['birthday'] < 10) $user['birthday'] = '0'.$user['birthday'];
		$content .= '<li>'.url('u/'.$user['username'],$user['username']).' - <strong>'.$user['birthmonth'].'/'.$user['birthday'].'</strong></li>';
	}
	$content .= '</ul>';
	if (!$total) $content = 'none';
	return array('title'=>t('Friends birthday coming up'), 'content' => $content);
}
