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
	set_gvar('pending_review_actived',$_POST['pending_review_actived']);
	set_gvar('pending_review_limit',$_POST['pending_review_limit']);
	redirect('admin/pending_posts',1);
}
elseif ($step == 'approve') {
	if (is_array($_POST['ids'])) {
		foreach ($_POST['ids'] as $id) {
			if ($_POST['opt'] == 1) {
				$res = sql_query("select * from ".tb()."pending_review where id='$id'");
				$post = sql_fetch_array($res);
				$str = preg_replace("/[0-9]/",'',$post['post_id']);
				$sid = preg_replace("/[^0-9]/",'',$post['post_id']);
				if (!preg_match("/^forums/",$post['uri'])) {
					sql_query("update ".tb()."accounts set forum_posts=forum_posts+1 where id='{$post['uid']}'");
				}
				if (strlen($str) && $sid>0) {
					$func = $str.'_approved';
					if (function_exists($func)) {
						$func($sid);
					}
				}
				sql_query("delete from ".tb()."pending_review where id='$id'");
			}
			elseif ($_POST['opt'] == 2) {
				sql_query("update ".tb()."pending_review set ignored=1 where id='$id'");
			}
			elseif ($_POST['opt'] == 3) {
				$res = sql_query("select * from ".tb()."pending_review where id='$id'");
				$post = sql_fetch_array($res);
				sql_query("update ".tb()."accounts set disabled=2 where id='{$post['uid']}'");
				sql_query("update ".tb()."pending_review set ignored=1 where id='$id'");
			}
		}
	}
	redirect('admin/pending_posts',1);

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
	<form method="post" action="'.url('admin/pending_posts/approve').'">
	Pending posts from Newcomers:
<table class="stories">
	
	<tr class="table_line1">
	<td width="5"></td><td>Posts</td><td>Info</td></tr>');
	$res = sql_query("select p.*,u.username from ".tb()."pending_review as p left join ".tb()."accounts as u on u.id=p.uid where p.ignored!=1 order by id DESC limit $offset,$more");
	$i=1;
	while ($row = sql_fetch_array($res)) {
		if ($i < $more) {
			c('<tr class="row1">
			<td><input type="checkbox" name="ids[]" class="checkids" value="'.$row['id'].'" /></td>
			<td>'.h($row['content']));
			if (strlen($row['uri'])) {
				c('<br />
				URL:<a href="'.url($row['uri']).'">'.url($row['uri']));
			}
			c('</a></td><td>
			'.get_date($row['created']).'<br />
			By '.url('u/'.$row['username'],$row['username']).'<br />
			'.url('admin/useredit/'.$row['id'],'Manage this user').'<br /></td></tr>');
		}
		$i++;
	}
	c('<tr class="row2">
	<td colspan="3">
	<input type="checkbox" id="checkallids" />Select All | 
	What to do? <select name="opt">
	<option value="1" selected>Approve</option>
	<option value="2">Ignore</option>
	<option value="3">Ignore and ban the authors</option>
	</select> 
	<input type="hidden" name="act" value="havestreams" />
	</td></tr>
	</table><br />
	<input type="submit" value=" Save Changes" style="font-size:15px" />
	</form>
	');
	if ($i>$num_per_page) {
		$page = $page+1;
		c('<div style="font-size:15px;padding:5px;">'.url('admin/pending_posts/'.$page,'More..').'</div>');
	}
	section_close();
}