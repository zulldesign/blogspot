<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class report{
	
	function report() {
		global $client, $menuon;
		clear_report();
		if (!$client['id']) {
			redirect('member/login/1');
		}
		clear_as();
	}
	
	function index() {
		global $db, $nav, $client;
		set_title('Report a page to Admin');
		clear_as();
		$nav[] = t('Report');
		$report_url = getenv(HTTP_REFERER);
		if (strlen($_GET['url'])) {
			$report_url = $_GET['url'];
		}
		if (!preg_match('/'.str_replace('/','\/',uhome()).'/',$report_url) || !$report_url) die('unable to find the url');
		c('<h1>'.t('Report SPAM or Abuse').'</h1>
		<form method="post" action="'.url('report/post').'" >
					<p>
					URL:<br />
					<a href="'.$report_url.'">'.$report_url.'</a>
					</p>
					<p>
					'.label(t('Message to Admin')).'
					<textarea name="message" style="width:500px" rows="3"></textarea>
					</p>
					<p>
					<input type="hidden" name="report_url" value="'.h($report_url).'" />
					<input class="button" type="submit" value="'.t('Send to Admin').'" />
					</p>
					</form>');
	}

	function post() {
		global $db, $client, $config;
		sql_query("insert into ".tb()."reports (uid,message,url,created) values('{$client['id']}','{$_POST['message']}','{$_POST['report_url']}',".time().")");
		set_title(t('Message sent, thank you!'));
		c(t('Message sent, thank you!'));
	}

	function delete($mid) {
		global $db, $client;
		// ids
		if (is_array($_REQUEST['ids'])) {
			foreach ($_REQUEST['ids'] as $id) {
				sql_query("delete from `".tb()."messages` where id='{$id}' and to_id='{$client['id']}' ");
			}
		}
		else {
			sql_query("delete from `".tb()."messages` where id='{$mid}' and to_id='{$client['id']}' ");
		}
		redirect(url('message/inbox'),1);
	}
}