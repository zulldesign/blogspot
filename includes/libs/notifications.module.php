<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class notifications{
	
	function notifications() {
		global $client, $menuon;
		$menuon = 'message';
		if (!$client['id']) {
			redirect('member/login/1');
		}
	}
	
	function index() {
		global $content, $db, $client, $offset, $num_per_page, $page, $ubase, $nav;
		set_title(t('Notifications'));
		$nav[] = t('Notifications');
		sql_query("update ".tb()."messages set hasread=1 where to_id='{$client['id']}' and from_id=0");
		$res = sql_query("SELECT m.*,u.username FROM `".tb()."messages` as m left join `".tb()."accounts` as u on u.id=m.from_id where m.to_id='{$client['id']}' and m.from_id=0 ORDER by m.id DESC LIMIT $offset,$num_per_page ");
		$rsspass = md5(get_gvar('secure_key').$client['id']);
		c('
		<div style="text-align:right">
		<a href="'.url('rss/notifications/'.$client['id'].'/'.$rsspass).'"><img src="'.uhome().'/files/icons/rss.gif" />Rss</a>
		</div>
		<table class="stories" cellspacing="1">');
		c('<tr class="table_line1">
			<td>Notifications</td>
			</tr>');
			while ($row = sql_fetch_array($res)) {
			c('<tr class="row1">
			<td><span class="sub">'.get_date($row['created']).': </span>'.$row['message'].'</td>
			</tr>');
		}
		c('</table>');

		// pager
		$res = sql_query("select count(*) as total from `".tb()."messages` where to_id='{$client['id']}' and from_id=0");
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = $ubase.$this->name.'/index';
		$pagebar  = $pb->whole_num_bar();
		c($pagebar);
	}

}