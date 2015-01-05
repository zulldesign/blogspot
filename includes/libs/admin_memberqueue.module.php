<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

if ($step == 'post') {
	if (is_array($_POST['ids'])) {
		foreach ($_POST['ids'] as $id) {
			if ($_POST['opt'] == 1) {
				sql_query("update ".tb()."accounts set disabled=0 where id='{$id}'");
				sql_query("delete from ".tb()."pending_review where uid='$id' and ignored!=1");
			}
			elseif ($_POST['opt'] == 2) {
				sql_query("update ".tb()."accounts set disabled=2 where id='{$id}'");
			}
		}
	}
	redirect('admin/memberqueue',1);

}

else {
	$num_per_page = 20;
	global $parr;
	if (!$parr[2]) $page=1;
	else $page = $parr[2];
	$offset = ($page-1)*$num_per_page;
	$more = $num_per_page+1;
	c('
	<script>
	$(document).ready( function(){
		$("#checkallids").click(function() {
			$(".checkids").attr("checked",true);
		});
	});
	</script>
	<form method="post" action="'.url('admin/memberqueue/post').'">
	Make sure do not make bot/spammer verified.  Only verify those who are posting on-topic messages.
<table class="stories">
	
	<tr class="table_line1">
	<td width="5"></td><td>Member</td><td>Posts</td></tr>');
	$res = sql_query("select * from ".tb()."accounts where forum_posts>1 and disabled=1 order by lastlogin DESC limit $offset,$more");
	$i=1;
	while ($row = sql_fetch_array($res)) {
		if ($i < $more) {
			c('<tr class="row1">
			<td><input type="checkbox" name="ids[]" class="checkids" value="'.$row['id'].'" /></td>
			<td>'.url('u/'.$row['username'],$row['username']).'<br />
			Fullname: '.h($row['fullname']).'<br />
			Register: '.get_date($row['created']).'<br />
			Posts: '.$row['forum_posts'].'<br />
			IP: '.$row['ipaddress'].'</td><td>
			<div style="height:90px;width:470px;overflow-x:auto;overflow-y:scroll">
			<ul>');
			$res2 = sql_query("select * from ".tb()."streams where uid='{$row['id']}' order by id DESC limit 10");
			while ($row2 = sql_fetch_array($res2)) {
				$attachment = unserialize($row2['attachment']);
				if (count($attachment)>0) {
					if (strlen($attachment['name'])) {
						if (strlen($attachment['uri'])) {
							$row2['att'] = '<div class="att_name">'.url($attachment['uri'],h($attachment['name'])).'</div>';
						}
						else {
							$row2['att'] = '<div class="att_name">'.h($attachment['name']).'</div>';
						}
					}
				}
				c('<li><span class="sub">'.$row2['message'].' '.$row2['att']);
				c(' | '.get_date($row2['created']).'</span></li>');
			}
			c('</ul>
			</div></td></tr>');
		}
		$i++;
	}
	c('<tr class="row2">
	<td colspan="3">
	<input type="checkbox" id="checkallids" />Select All | 
	What to do? <select name="opt">
	<option value="1" selected>Verify</option>
	<option value="2">Ban</option>
	</select> 
	<input type="hidden" name="act" value="havestreams" />
	</td></tr>
	</table><br />
	<input type="submit" value=" Save Changes" style="font-size:15px" />
	</form>
	');
	if ($i>$num_per_page) {
		$page = $page+1;
		c('<div style="font-size:15px;padding:5px;">'.url('admin/memberqueue/'.$page,'More..').'</div>');
	}
	section_close();
}