<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class rss{
	
	function notifications($uid,$pass) {
		global $content, $db, $client, $offset, $num_per_page, $page, $ubase, $nav;
		if (!$user = valid_user($uid)) {
			die('wrong uid');
		}
		$rsspass = md5(get_gvar('secure_key').$user['id']);
		if ($pass != $rsspass) {
			die('access denied');
		} 
		header("Content-Type: application/rss+xml");
		echo '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
<channel>
<title>'.t('Notifications from {1}',get_gvar('site_name')).'</title>
<link>'.url('rss/notifications/'.$user['id'].'/'.$rsspass).'</link>

';
		$res = sql_query("SELECT m.*,u.username FROM `".tb()."messages` as m left join `".tb()."accounts` as u on u.id=m.from_id where m.to_id='{$user['id']}' and m.from_id=0 ORDER by m.id DESC LIMIT 30");
		$rsspass = md5(get_gvar('secure_key').$client['id']);
		c('<tr class="table_line1">
			<td>Notifications</td>
			</tr>');
			while ($row = sql_fetch_array($res)) {
			echo '
<item>
<title>'.get_date($row['created']).'</title>
<description><![CDATA['.$row['message'].']]></description>
</item>
';
		}
		echo '
</channel>
</rss>
';
		exit;
	}

}