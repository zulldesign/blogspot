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

echo '
<style>
				#appmain {
					width: 755px;
					float: right;
				}
				#appside {
					width: 210px;
					float: left;
				}
</style>
<div id="jcow_app_container">
<div id="appmain">';


if (!$client['id']) {
	echo '<div><a href="'.url('member/signup').'"><img src="'.uhome().'/themes/default/welcome.jpg" alt="welcome" /></a></div>';
}
echo '
<div class="block">
<div class="block_title">'.t('Community activities').'</div>
<div class="block_content">
<script type="text/javascript" src="'.uhome().'/js/jquery.vtricker.js"></script>
<script>
jQuery(document).ready(function($) {
	$("#recent_activites").fadeIn();
	$(\'#recent_activites\').vTicker({
	   speed: 800,
	   pause: 5000,
	   showItems: 4,
	   animation: \'fade\',
	   mousePause: false,
	   height: 350,
	   direction: \'down\'
	});
			});
</script>
<style>
#recent_activites li{
	margin:0;
	padding:0;
	}
</style>
<div id="recent_activites" style="display:none">
<ul>
';
$res = sql_query("SELECT s.*,u.username,u.avatar from `".tb()."streams` as s left join ".tb()."accounts as u on u.id=s.uid where s.hide!=1  order by s.id desc limit 20");
while ($stream = sql_fetch_array($res)) {
	$stream['attachment'] = unserialize($stream['attachment']);
	echo '<li>'.stream_display($stream,'simple').'</li>';
}
echo '</ul>
<div style="position:absolute;left:0;bottom:0px;height:20px;width:100%;background:url('.uhome().'/files/common_css/fade.png) repeat-x"> </div></div>

</div></div>';

echo '</div>';// end appmain


// sidebar
echo '<div id="appside">';
if (!$client['id']) {
echo '
				<div class="block">
				<div class="block_title">'.t('Login').'</div>
				<div class="block_content">
<form method="post" name="loginform" id="form1" action="'.url('member/loginpost').'" >
			'.t('Username or Email').':<br />
			<input type="text" size="10" name="username" style="width:120px" /><br />
							'.t('Password').':<br />
			<input type="password" size="10" name="password" style="width:120px" /><br />
			<div class="sub">( '.url('member/chpass',t('Forgot password?')).' )</div>
			<input type="checkbox" name="remember_me" value="1" /> '.t('Remember me').'<br />
			<input type="submit" value=" Login " />
			</form>
			<script language="javascript">document.loginform.username.focus();</script>';
			if (get_gvar('fb_id')) {
				echo '<div>'.url('fblogin','<img src="'.uhome().'/modules/fblogin/button.png" />').'</div>';
			}

			echo '
			<div class="hr"></div>
			'.t('New to our Network?').'<br />
			<a href="'.url('member/signup').'" style="display:block;font-size:2em;">
			'.t('Join Now!').'
			</a>
				</div>
				</div>
				';
}
echo '
				<div class="block">
				<div class="block_title">'.t('New members').'</div>
				<div class="block_content">';

$res = sql_query("SELECT * from `".tb()."accounts` order by id desc limit 18");
while($row = sql_fetch_array($res)) {
	echo avatar($row,25);
}
echo '</div></div>';


echo '
				<div class="block">
				<div class="block_title">'.t('Network Statistics').'</div>
				<div class="block_content">';

//## display network statistics.
$res = sql_query("SELECT count(*) as num from `".tb()."accounts`");
$row = sql_fetch_array($res);
$stats['members'] = $row['num'];
$res = sql_query("SELECT count(*) as num from `".tb()."friends`");
$row = sql_fetch_array($res);
$stats['friendships'] = $row['num']/2;
$res = sql_query("SELECT count(*) as num from `".tb()."comments`");
$row = sql_fetch_array($res);
$stats['comments'] = $row['num'];
$res = sql_query("SELECT count(*) as num from `".tb()."streams`");
$row = sql_fetch_array($res);
$stats['activities'] = $row['num'];
echo '
<strong>'.$stats['activities'].'</strong> '.t('Activities').'<br />
<strong>'.$stats['members'].'</strong> '.t('Members').'<br />
<strong>'.$stats['friendships'].'</strong> '.t('Friendships').'<br />
<strong>'.$stats['comments'].'</strong> '.t('Comments').'
';
echo '</div></div>';


echo get_gvar('theme_block_sidebar');
echo '</div>';// sidebar
echo '</div>';//jcow_app_container