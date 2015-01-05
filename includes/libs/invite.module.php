<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class invite{

	function invite() {
		global $client;
		clear_as();
		if (!$client['id']) {
			redirect('member/login/1');
		}
		set_title('Invite');
		nav(url('invite',t('Invite')));
	}

	function index() {
		global $content, $db, $nav, $client, $uhome, $locations, $current_sub_menu;
		$current_sub_menu['href'] = 'invite';
		if ($_POST['import'] == 'msn') {
			$gm = new msnlistgrab($_POST['msn_user'],$_POST['msn_pass']);
			$gm->GetRecords();
			if (is_array($gm->res)) {
				foreach ($gm->res as $val)
				{
				    $contacts .= $contacts ? ', '.$val : $val;
				}
			}
			else {
				sys_back(t('Sorry, we failed to import contacts, please check you input info'));
			}
		}

		$encoded_url = urlencode(uhome());
			c('
				Share this website with your facebook friends and twitter follers:<a href="http://twitter.com/home?status='.$encoded_url.'" target="_blank"><img src="'.uhome().'/files/social_bookmarks/twitter.png" /> Twitter</a> | 
		<a href="http://www.facebook.com/sharer.php?u='.$encoded_url.'" target="_blank" onclick="window.open(this.href, \'\',
\'height=300,width=600\');return false;"><img src="'.uhome().'/files/social_bookmarks/facebook.png" /> Facebook</a>');
		section_close(t('Share sharing'));


		c('

		<form method="post" action="'.url('invite/post').'" >
		<!--
		<p>
		<img src="'.uhome().'/files/icons/msn.gif" />'.url('invite/import_contacts',t('Click here to import your msn contacts')).'
		</p>
		-->
					<p>
					'.label(t('To').' (Email address)').'
					<input name="emails" size="80" value="'.$contacts.'" /><br />
					<span>'.t('Multiple email addresses should be Separated with commas').'(,)</span>
					</p>
					
					<p>
					'.label(t('Message').' (Optional)').'
					<textarea name="message" rows="5"></textarea></p>
	
		<p>
					<input type="submit" class="button" value="'.t('Send invitations').'" />
					</p>
					</form>
					');
		section_close(t('Send invitation'));
		
		
	}
	
	function import_contacts() {
		global $current_sub_menu;
		$current_sub_menu['href'] = 'invite';

		c('
			<form method="post" action="'.url('invite/index').'">
			<fieldset style="background:url(\''.uhome().'/files/images/msn.jpg\') right top no-repeat;">
			<legend>'.t('Invite your MSN friends').'</legend>
			<p>
			'.label(t('MSN username')).'
			<input type="text" name="msn_user" size="15" />@hotmail.com
			</p>
			<p>
			'.label(t('MSN password')).'
			<input type="password" name="msn_pass" size="15" />
			</p>
			<p>
			<input type="hidden" name="import" value="msn" />
			<input type="submit" class="button" value="Import" />
			</p>
			</fieldset>
			</form>');
	}

	function post() {
		global $db, $client, $uhome, $ubase, $config;
		if (!$_POST['emails']) {
			sys_back(t('Please fill all the required blanks'));
		}
		
		$emails = explode(',',$_POST['emails']);
		$i = 0;
		$sents = $ignores = 0;
		foreach ($emails as $email) {
			//check
			$res = sql_query("select * from `".tb()."invites` where email='$email' ");
			$res2 = sql_query("select * from `".tb()."accounts` where email='$email' ");
			if (!sql_counts($res) && !sql_counts($res2)) {
				$sents++;
				sql_query("insert into `".tb()."invites` (uid,email,created) values('{$client['id']}','$email',".time().")");
				$iid = insert_id();
				$url = url('member/signup');
				if (preg_match("/\?/",$url)) {
					$url = $url.'&email='.urlencode($email).'&iid='.$iid;
				}
				else {
					$url = $url.'?email='.urlencode($email).'&iid='.$iid;
				}
				$message = t('Join me on').' "'.get_gvar('site_name').'" - '.get_gvar('site_slogan').'<br />
			<strong><a href="'.$url.'">Click Here to Join!</a></strong><br />';
				$email = trim($email);
				$email = str_replace("\r\n","",$email);
				if ($i > 50) {
					redirect(url('invite'),t('Invitations have been sent!'));
				}
				$message .= $_POST['message'];
				$message .= '<br /><br />invited by '.url('u/'.$client['uname'],$client['uname']);
				@jcow_mail($email,t('you are invited to join {1}',get_gvar('site_name')),$message);
				$i++;
			}
			else {
				$ignores++;
			}
		}
		$total = $ignores+$sents;
		redirect(url('invite/histories'),t('{1} submitted',$total).' ('.$sents.' '.t('sents').', '.$ignores.' '.t('ignores').')');
	}

	function histories() {
		global $current_sub_menu, $client;
		$current_sub_menu['href'] = 'invite/histories';
		$res = sql_query("select * from ".tb()."invites where uid='{$client['id']}' "." order by id DESC LIMIT 100");
		c('<table class="stories">
		<tr class="table_line1"><td>Email address</td><td>Time</td><td>Status</td></tr>');
		while ($invite = sql_fetch_array($res) ) {
			$invite['status'] = $invite['status'] ? '<font color="green">Joined</font>' : 'Sent';
			c('<tr><td class="row1">'.$invite['email'].'</td><td>'.get_date($invite['created']).'</td><td>'.$invite['status'].'</td></tr>');
		}
		c('</table>');

	}
	
}