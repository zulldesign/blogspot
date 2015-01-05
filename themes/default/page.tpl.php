<?php
/* ############################################################ *\
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
------------------------------------------------------------------------
The contents of this file are subject to the Common Public Attribution
License Version 1.0. (the "License"); you may not use this file except in
compliance with the License. You may obtain a copy of the License at
http://www.jcow.net/celicense. The License is based on the Mozilla Public
License Version 1.1, but Sections 14 and 15 have been added to cover use of
software over a computer network and provide for limited attribution for the
Original Developer. In addition, Exhibit A has been modified to be consistent
with Exhibit B.

Software distributed under the License is distributed on an "AS IS" basis,
 WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
the specific language governing rights and limitations under the License.
------------------------------------------------------------------------
The Original Code is Jcow.

The Original Developer is the Initial Developer.  The Initial Developer of the
Original Code is jcow.net.

\* ############################################################ */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">	

<head>
<base href="<?php echo uhome()?>/" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="Generator" content="Powered by Jcow" />
<?php echo $auto_redirect?>
<style type="text/css" media="all">@import "<?php echo $uhome;?>/files/common_css/style.css";</style>
<style type="text/css" media="all">@import "<?php echo $uhome;?>/themes/<?php echo get_gvar('theme_tpl')?>/page.css";</style>
<link rel="shortcut icon" href="<?php echo $uhome;?>/themes/default/ico.gif" type="image/x-icon" />
<?php echo $tpl_vars['javascripts'];?>
<title><?php echo $title?> - <?php echo get_gvar('site_name')?></title>
<?php echo $tpl_vars['custom_profile_css']?>
<?php
if (strlen(get_gvar('site_keywords'))) {
	echo '<meta name="keywords" content="'.get_gvar('site_keywords').'" />';
}
?>
<?php echo $header?>
<script type="text/javascript">
 var RecaptchaOptions = {
    theme : 'white'
 };
</script>
</head>

<body>

<div id="topbar_box">
<div id="topbar">
<table width="100%" height="50" cellpadding="0" cellspacing="0"><tr>
<td width="150">
<?php

echo '<a href="'.my_jcow_home().'"><img src="'.uhome().'/themes/default/logo.png" /></a>';
?>
</td>
<td valign="middle">
	<table  cellpadding="0" cellspacing="0">
	<form action="<?php echo url('search/listing')?>" method="post" name="search_form">
	<tr>
	<td valign="top">
	<input type="text" id="search_box" name="title" value="" name="search_box"  />
	</td>
	<td>
	<input type="submit" value="find" id="search_button" style="display:none" />
	</td>
	</tr>
	</form>
	</table>
</td>
<?php
if (!$client['id']) {
	echo '
	<td align="right">
	'.url('member/login',t('Login')).' | '.url('member/signup',t('Sign up')).'
	</td>';
}
else {
	echo '
	<td align="right"><a href="'.my_jcow_home().'">'.t('Home').'</a> | '.url('u/'.$client['uname'],t('Profile')).' | '.$friendslink.' | '.url('message',t('Inbox').msg_unread() ).' | '.url('notifications',t('Notifications').note_unread()).' | '.url('account',t('Settings')).' | <span class="sub">'.url('member/logout',t('Logout') ).'</span>
	</td>';
}
?>
</tr>
</table>


<div class="topnav_box menu">
<ul class="topnav">
<?php
$top_length = 85;
$top_apps = $more_apps = array();
if (is_array($my_apps)) {
	foreach ($my_apps as $item) {
		if ($item['path'] != 'account') {
			$total_length += strlen(t($item['name']));
			if ($total_length > $top_length) {
				$more_apps[] = $item;
			}
			else {
				$top_apps[] = $item;
			}
		}
	}
}

if (is_array($new_apps)) {
	foreach ($new_apps as $item) {
		if ($item['path'] != 'account') {
			$total_length += strlen(t($item['name']));
			if ($total_length > $top_length) {
				$more_apps[] = $item;
			}
			else {
				$top_apps[] = $item;
			}
		}
	}
}
foreach ($top_apps as $item) {
	echo '<li '.check_menu_on($item['path']).'>'.url($item['path'],t($item['name'])).'</li>';
}

if(count($more_apps)>0) {
	echo '<li>
	<ul class="subnav">';
	foreach ($more_apps as $item) {
			$icon = uhome().'/modules/'.$item['app'].'/icon.png';
			if ($item['icon']) $icon = $item['icon'];
			echo '<li>'.url($item['path'],
			'<div style="padding:3px 0 3px 23px;background:url('.$icon.') 0 1px no-repeat">'.t($item['name']).'</div>'
			).'</li>';
	}
	echo '<li>'.url('apps',
					'<div style="padding:3px 0 3px 23px;background:url('.uhome().'/modules/apps/icon.png) 0 1px no-repeat">'.t('Apps').'</div>'
					).'</li>';
	echo '</ul>';
	echo '</li>';
}
else {
	echo '<li '.check_menu_on('apps').' >'.url('apps',t('Apps')).'</li>';
}
?>
</ul>
</div>


</div>
</div>
<script>
$(document).ready(function(){

	$("ul.subnav").parent().append("<span></span>"); //Only shows drop down trigger when js is enabled (Adds empty span tag after ul.subnav*)

	$("ul.topnav li span").click(function() { //When trigger is clicked...

		//Following events are applied to the subnav itself (moving subnav up and down)
		$(this).parent().find("ul.subnav").slideDown('fast').show(); //Drop down the subnav on click

		$(this).parent().hover(function() {
		}, function(){
			$(this).parent().find("ul.subnav").slideUp('slow'); //When the mouse hovers out of the subnav, move it back up
		});

		//Following events are applied to the trigger (Hover events for the trigger)
		}).hover(function() {
			$(this).addClass("subhover"); //On hover over, add class "subhover"
		}, function(){	//On Hover Out
			$(this).removeClass("subhover"); //On hover out, remove class "subhover"
	});

});
</script>

<!-- #################### structure ################## -->

<div id="wallpaper" style="width:100%;height:100%;overflow:hidden">
<div id="jcow_main_box">
<div id="jcow_main">
<table id="appframe" cellspacing="0"><tr>
<?php

echo '<td valign="top">';
if (count($nav) > 2) {
	echo '<div id="nav">'.gen_nav($nav).'</div>';
}
if (is_array($notices)) {
	foreach ($notices as $notice) {
		echo '<div class="notice">'.$notice.'</div>';
	}
}
if ($top_title) {
	echo '<div style="padding:0 0 10px 30px;background:url('.uhome().'/modules/'.$application.'/icon.png) 9px 5px no-repeat;font-size:1.5em">'.$top_title.'</div>';
}

echo $app_header;
if (is_array($tab_menu)) {
	echo '<div id="tabmenu">';
	echo '<ul>';
	echo '<li class="tm_begin"></li>';
	echo tabmenu_begin();
	foreach ($tab_menu as $item) {
		echo '<li '.check_tabmenu_on($item['path']).'>'.url($item['path'],t($item['name'])).'</li>';
	}
	echo '<li class="tm_end"> </li>';
	echo '</ul>
	</div>';
}

if (is_array($buttons)) {
		echo '<div style="padding-left:10px;"><ul class="buttons">';
		foreach ($buttons as $val) {
			echo '<li>'.$val.'</li>';
		}
		echo '</ul></div>';
	}

/* 
The "display_application_content" is the output of applications. 
The default Width is 780px. 
You may not change the Width, otherwise some applications can not be displayed correctly 
*/
echo '<table border="0" width="100%">
<tr><td valign="top">';
if ($application != 'home') {
	display_application_content();
}
else {
	include 'themes/default/home.tpl.php';
}
echo '</td>';
if (!$is_cover) {
	echo '<td valign="top"><div style="width:170px;float:right;">
	'.get_gvar('theme_block_adsbar').'</div></td>';
}

echo '
</tr></table>

</td>';


?>


</table>


</div><!-- end jcow_application -->
</div><!-- end jcow_application_box -->
</div><!-- end wallpaper -->

<div id="footer">
<div>
<?php
// footer pages
$footer_pages = get_footer_pages();
if (count($footer_pages)) {
	echo '<div id="footer_pages">'.implode(' | ',$footer_pages).'</div>';
}
?>


<?php echo $tpl_vars['language_selection']?>
</div>
<?php echo $tpl_vars['footer']?>
<br /><br />

<!-- YOU ARE NOT ALLOWED TO REMOVE jcow_attribution() WITHOUT A BRANDING REMOVAL LICENSE -->
<?php echo jcow_attribution();?>

</div>

<?php echo $footer;?>
</body>
</html>