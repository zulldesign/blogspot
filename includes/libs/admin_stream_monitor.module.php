<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

if (basename($_SERVER["SCRIPT_NAME"]) != 'index.php') die(basename($_SERVER["SCRIPT_NAME"]));
global $client;
$snum = 50;
if ($step == 'modify') {
}

else {
	$filter = $_POST['filter'];
	$mpage = $_POST['mpage'];
	if (!$mpage) $mpage = 1;
	$offset = $snum*($mpage-1);
	if (!$filter) $filter = 'week';
	if ($filter == 'week') {
		$timeline = time() - 3600*24*7;
		$where = "where u.created>$timeline";
		$weekchecked = 'checked';
	}
	elseif ($filter == 'month') {
		$timeline = time() - 3600*24*30;
		$where = "where u.created>$timeline";
		$monthchecked = 'checked';
	}
	else {
		$where = '';
		$allchecked = 'checked';
	}
	c('View streams from:<br />
	<form action="'.url('admin/stream_monitor').'" method="post" />
	<input type="radio" name="filter" value="week" '.$weekchecked.' />New members(registered less than <strong>1 week</strong>)<br />
	<input type="radio" name="filter" value="month" '.$monthchecked.' />New members(registered less than <strong>1 month</strong>)<br />
	<input type="radio" name="filter" value="all" '.$allchecked.' />All members<br />
	<input type="submit" value="Update" />
	</form>
	');
	c('<ul>');
	$res = sql_query("select s.*,u.username,u.avatar,p.uid as wall_uid from ".tb()."streams as s left join ".tb()."accounts as u on u.id=s.uid left join ".tb()."pages as p on p.id=s.wall_id $where order by s.id desc limit $offset,$snum");
	$acts = '';
	$i=0;
	while($row = sql_fetch_array($res)) {
		$i++;
		$attachment = unserialize($row['attachment']);
		$att = '';
		if (count($attachment) > 1) {
			if (strlen($attachment['name'])) {
				if (strlen($attachment['uri'])) {
					$att = url($attachment['uri'],h($attachment['name']));
				}
				else {
					$att = h($attachment['name']);
				}
			}
			if (strlen($attachment['title'])) {
				$att = url($attachment['uri'],h($attachment['title']) );
			}
		}
		c('<li>'.url('u/'.$row['username'],$row['username']).': '.$row['message'].' '.$att.'</li>');
	}
	c('</ul>');
	if ($i == $snum) {
		$next = $mpage+1;
		c('
		<form action="'.url('admin/stream_monitor').'" method="post" />
		<input type="hidden" name="filter" value="'.$filter.'" />
		<input type="hidden" name="mpage" value="'.$next.'" />
		<input type="submit" value="More.." />
		</form>
		');
	}
}