<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class apps{
	function apps() {
		do_auth(2);

	}

	function index() {
		global $content, $client,$all_apps,$my_apps,$new_apps;
		set_title('apps');

		c('<meta charset="utf-8">
	<style>
	#jcow_sortable1, #jcow_sortable2 { list-style-type: none; margin: 0; padding: 0; float: left; margin-right: 10px; }
	#jcow_sortable1 li, #jcow_sortable2 li { background:white;margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; width: 120px; }
	#jcow_sortable1 {padding:0 0 100px 20px;width:150px;background:#eeeeee}
	#jcow_sortable2 {padding:0 0 100px 20px;width:150px;background:#FFFFCC}
	</style>
	<script>
	$(function() {
		$( ".jcow_sortableapps" ).sortable(
		{connectWith: ".jcow_sortableapps"}, { update: function(event, ui) {
						if ($(this).attr("id") == "jcow_sortable1") {
							var out = $("#jcow_sortable1").sortable("serialize");
							$.ajax({
							   type: "POST",
							   url: "'.url('apps/update_apps').'",
							   data: out
							 });
						}
					}
				}
		).disableSelection();
	});
	</script>
<div style="padding:5px;margin:3px;border:#eeeeee 1px solid;background:white;color:#666666;font-weight:bold;">
<img src="'.uhome().'/modules/apps/help.png" /> '.t('You can drag/drop the items').'</div>
<div class="my_app_box">

<ul id="jcow_sortable1" class="jcow_sortableapps">'.t('Displayed'));
if (is_array($my_apps)) {
			foreach ($my_apps as $item) {
				if ($item['path'] != 'account') {
					$icon = uhome().'/modules/'.$item['app'].'/icon.png';
					if ($item['icon']) $icon = $item['icon'];
					c( '<li class="ui-state-highlight" id="'.$item['path'].'_1">'.url($item['path'],
					'<div style="padding:3px 0 3px 23px;background:url('.$icon.') 0 1px no-repeat">'.t($item['name']).'</div>'
					).'</li>');
				}
			}
		}
if (is_array($new_apps)) {
			foreach ($new_apps as $item) {
				if ($item['path'] != 'account') {
					$icon = uhome().'/modules/'.$item['app'].'/icon.png';
					if ($item['icon']) $icon = $item['icon'];
					c( '<li  class="ui-state-highlight" id="'.$item['path'].'_1">'.url($item['path'],
					'<div style="padding:3px 0 3px 23px;background:url('.$icon.') 0 1px no-repeat">'.t($item['name']).'</div>'
					).'</li>');
				}
			}
		}
c('
</ul>
<ul id="jcow_sortable2" class="jcow_sortableapps">
'.t('Hidden'));

foreach ($all_apps as $app_key=>$item) {
	if (!is_array($my_apps[$app_key]) && !is_array($new_apps[$app_key]) && $item['path'] != 'account') {
		$icon = uhome().'/modules/'.$item['app'].'/icon.png';
		if ($item['icon']) $icon = $item['icon'];
		c( '<li  class="ui-state-highlight" id="'.$item['path'].'_1">'.url($item['path'],
		'<div style="padding:3px 0 3px 23px;background:url('.$icon.') 0 1px no-repeat">'.t($item['name']).'</div>'
		).'</li>');
	}
}

c('
</ul>

</div>

');
		section_close(t('My apps'));
	}


	function update_apps() {
		global $all_apps;
		echo time().'| ';
		print_r($_POST);
		echo '<br />';
		$my_apps = $hide_apps = array();
		foreach ($_POST as $key=>$value) {
			if (is_array($all_apps[$key])) {
				$my_apps[] = $key;
			}
		}
		foreach ($all_apps as $app_key=>$app) {
			if (!in_array($app_key,$my_apps)) {
				$hide_apps[] = $app_key;
			}
		}
		$arr = array('my_jcow_apps'=>$my_apps,'hidden_jcow_apps'=>$hide_apps);
		save_u_settings($arr);
		exit;
	}
}