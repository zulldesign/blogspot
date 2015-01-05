<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

$version = '7.0.1';

$langs = array(
	'am' => 'Amharic',
	'ar' => 'Arabic',
	'be' => 'Belarusian',
	'bg' => 'Bulgarian',
	'br' => 'Breton',
	'ca' => 'Catalan',
	'ch' => 'Chamorro',
	'zh' => 'Chinese',
	'cs' => 'Czech',
	'cy' => 'Welsh',
	'da' => 'Danish',
	'de' => 'German',
	'el' => 'Greek',
	'en' => 'English',
	'eo' => 'Esperanto',
	'es' => 'Spanish',
	'et' => 'Estonian',
	'eu' => 'Basque',
	'fa' => 'Farsi',
	'fi' => 'Finnish',
	'fr' => 'French',
	'ga' => 'Irish',
	'gl' => 'Galician',
	'gu' => 'Gujarati',
	'he' => 'Hebrew',
	'hi' => 'Hindi',
	'hr' => 'Croatian',
	'hu' => 'Hungarian',
	'ia' => 'Interlingua',
	'id' => 'Indonesian',
	'it' => 'Italian',
	'ja' => 'Japanese',
	'ka' => 'Georgian',
	'ko' => 'Korean',
	'kw' => 'Cornish',
	'la' => 'Latin',
	'lt' => 'Lithuanian',
	'mg' => 'Malagasy',
	'ne' => 'Nepali',
	'nl' => 'Dutch',
	'no' => 'Norwegian',
	'ps' => 'Pashto',
	'pl' => 'Polish',
	'pt' => 'Portuguese',
	'ro' => 'Romanian',
	'ru' => 'Russian',
	'sa' => 'Sanskrit',
	'sk' => 'Slovak',
	'sl' => 'Slovenian',
	'so' => 'Somali',
	'sq' => 'Albanian',
	'sr' => 'Serbian',
	'sv' => 'Swedish',
	'ta' => 'Tamil',
	'tr' => 'Turkish',
	'uk' => 'Ukrainian',
	'vi' => 'Vietnamese',
	'wa' => 'Walloon'
	);
if (is_array($lang_options)) {
	foreach ($lang_options as $key=>$val) {
		$langs[$key] = $val;
	}
}
function get_r($arr) {
    foreach ($arr as $val) {
		GLOBAL $$val;
		if (isset( $_REQUEST[$val])) {
			if (!is_array($_REQUEST[$val])) {
				if (get_magic_quotes_gpc())
					$$val = trim($_REQUEST[$val]);
				else
					$$val = addslashes(trim($_REQUEST[$val]));
			}
			else
				$$val = $_REQUEST[$val];
		}
	}
}

function utf8_substr($str, $len = 20, $start=0) {
	$olen = strlen($str);
	for($i=0;$i<$len;$i++)
	{
		$temp_str=substr($str,0,1);
		if(ord($temp_str) > 127)
			{
			$i++;
			if($i<$len)
				{
				$new_str[]=substr($str,0,3);
				$str=substr($str,3);
				}
			}
		else
			{
			$new_str[]=substr($str,0,1);
			$str=substr($str,1);
			}
	}
	$new_str = join($new_str);
	if (strlen($new_str) < $olen) {
		$new_str = $new_str.'..';
	}
	return $new_str;
}
// get table name
function table($name) {
	GLOBAL $db_info;
	return $db_info.$name;
}

function sys_break($msg) {
	echo $msg;
	die();
}

function sys_back($msg) {
	global $alerts, $app;
	c('<div class="notice">'.$msg.'<br /><br />
	&gt;&gt; <a href="javascript:history.go(-1);void(0);">'.t('Click here to go back').'</a>
	</div>');
	set_title('Redirecting');
	nav('Redirecting');
	clear_as();
	stop_here();
}

function set_title($val) {
	global $title;
	$title  = $val;
}
function set_page_title($val) {
	global $page_title;
	$page_title  = $val;
}


function clear_as() {
	global $clear_as;
	$clear_as = 1;
}

function jcow_access($role) {
	GLOBAL $client;
	if ($role == 2) {
		if ($client['id'] != 1) {
			return false;
		}
	}
}

function do_auth($roleids, $force_uid = 0, $msg = '') {
	global $client;
	if (!allow_access($roleids, $force_uid)) {
		if ($force_uid) {
			sys_back('Sorry, you have no permission to do this.');
		}
		if ($roleids == 2 || jcow_in_array(2,$roleids)) {
			redirect('member/login/1');
		}
		if (!strlen($msg)) {
			$msg = t('Sorry, you have no permission to do this.');
		}
		if (!is_array($roleids)) {
			if (!$roleids) {
				$need_roles = '<br />'.t('The following roles have the permission').':<ul>
				<li>'.t('Super admin').'</li></ul>';
			}
		}
		else {
			if (!$roleids[0]) {
				$need_roles = '<br />'.t('The following roles have the permission').':<ul>
				<li>'.t('Super admin').'</li></ul>';
			}
			else {
				if (is_array($roleids)) {
					$where = ' where id in ('.implode(',',$roleids).')';
				}
				else {
					$where = ' where id='.$roleids;
				}
				$res = sql_query("select name from ".tb()."roles $where order by id");
				$need_roles = '<br />'.t('The following roles have the permission').':<ul>';
				while ($role = sql_fetch_array($res)) {
					$need_roles .= '<li>'.h($role['name']).'</li>';
				}
				$need_roles .= '</ul>';
			}
		}
		$res = sql_query("select name from ".tb()."roles where id in(".implode(',',$client['roles']).") order by id");
		$your_roles = '<br /><br />'.t('Your current roles').':<ul>';
		while ($role = sql_fetch_array($res)) {
			$your_roles .= '<li>'.h($role['name']).'</li>';
		}
		$your_roles .= '</ul>';
		sys_back($msg.'<br /><br />'.$need_roles.$your_roles);
	}
}

function url($link,$name = '',$target='',$gets=array()) {
	GLOBAL $ubase, $uhome;
	if (preg_match("/^http/i",$link)) {
		$url = $link;
	}
	else {
		if (preg_match("/^account/i",$link) || preg_match("/^admin/i",$link) || preg_match("/^login/i",$link) || $link == 'logout') {
			$url = $uhome.'/index.php?p='.$link;
		}
		else {
			$url = $ubase.$link;
		}
	}
	if (count($gets) > 0) {
		foreach ($gets as $key=>$val) {
			$pars[] = $key.'='.urlencode($val);
		}
		$getss = implode('&',$pars);
		if (preg_match("/\?/",$url)) {
			$url = $url.'&'.$getss;
		}
		else {
			$url = $url.'?'.$getss;
		}
	}
	if ($name == 'ohno' || $name == '') {
		return $url;
	}
	if (preg_match("/delete/i",$link)) {
		$cfm = cfm();
	}
	if ($target)
		$target = ' target="'.$target.'"';
	return '<a href="'.$url.'"'.$target.$cfm.'>'.$name.'</a>';
}

function name2profile($name) {
	return url('u/'.$name,$name);
}
function gurl($link,$name='',$igroup = '') {
	if (!$igroup) {
		global $group;
	}
	else {
		$group = $igroup;
	}
	if ($name == '') $name = 'ohno';
	return url('group/'.$group['uri'].'/'.$link,$name);
}

function button($link, $name, $cfm = '') {
	global $ubase, $buttons;
	if (preg_match("/delete/i",$link) || $cfm) {
		$cfm = cfm($cfm);
	}
	$buttons[] = '<a class="button" href="'.$ubase.$link.'" '.$cfm.'>'.$name.'</a>';
}

function get_date($timeline, $type = 'time') {
	GLOBAL $settings, $client;
	$timeline = $timeline + $client['timezone']*3600;
	$current = time() + $client['timezone']*3600;
	$it_s = intval($current - $timeline);
	$it_m = intval($it_s/60);
	$it_h = intval($it_m/60);
	$it_d = intval($it_h/24);
	$it_y = intval($it_d/365);
	if ($type == 'date'){
		return gmdate($settings['date_format'],$timeline);
	}
	else {
		if(gmdate("j",$timeline) == gmdate("j",$current)) {
			return $settings['date_today'].', '.gmdate($settings['time_format'],$timeline);
		}
		elseif(gmdate("j",$timeline) == gmdate("j",($current-3600*24) ) ) {
			return $settings['date_yesterday'].', '.gmdate($settings['time_format'],$timeline);
		}
		return gmdate($settings['date_format'].', '.$settings['time_format'],$timeline);
	}
}

function redirect($url, $message = 0) {
	global $ubase;
	
	clear_as();
	if (!preg_match("/^http/i",$url)) {
		$url = $ubase.$url;
	}
	if (!$message) {
		header("Location:$url");
		exit;
	}
	elseif ($message == 1) {
		if (preg_match("/index\.php/i",$url)) {
			$url = $url.'&succ=1';
		}
		else {
			$url = $url.'?succ=1';
		}
		header("Location:$url");
		exit;
		redirecting($url, t('Operation success'),'auto');
	}
	else {
		redirecting($url, $message);
	}
}



function redirecting($url, $message, $option = '') {
	global $alerts, $uhome, $auto_redirect, $config;
	$config['hide_ad'] = 1;
	if ($option == 'auto') {
		$auto_redirect = '<meta http-equiv="Refresh" content="1; url='.url($url).'" />';
		c('<div class="message"><p>'.$message.'</p>
		<img src="'.$uhome.'/files/loading.gif" /> Now redirecting to: '.url($url,$url).
			'</div>');
	}
	else {
		c('<div class="message"><p>'.$message.'</p>
		&gt;&gt; '.url($url,t('Click here to go on')).
			'</div>');
	}
	stop_here();
}

function gen_nav() {
	global $nav;
	return implode(' <span class="sub">&gt;</span> ', $nav);
}

function valid_user($val, $type = 'id') {
	global $db;
	if ($type == 'id') {
		$res = sql_query("select * from `".tb()."accounts` where id='$val' ".dbhold() );
	}
	else {
		$res = sql_query("select * from `".tb()."accounts` where username='$val' ".dbhold() );
	}
	if (sql_counts($res)) {
		return sql_fetch_array($res);
	}
	else {
		return false;
	}
}



function ip() { 
    if (isset($_SERVER)) {

        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        
        elseif (isset($_SERVER["HTTP_CLIENT_IP"]))
            $ip = $_SERVER["HTTP_CLIENT_IP"];
		else
			$ip = $_SERVER["REMOTE_ADDR"];
    }

    elseif (getenv('HTTP_X_FORWARDED_FOR'))
        $ip = getenv('HTTP_X_FORWARDED_FOR');

    elseif (getenv('HTTP_CLIENT_IP'))
        $ip = getenv('HTTP_CLIENT_IP');

    else
		$ip = getenv('REMOTE_ADDR');
	return $ip;
} 



function get_rand($length, $possible = "0123456789abcdefghijklmnopqrstuvwxyz") { 
	srand((double)microtime()*1000000);
    $str = ""; 
    while(strlen($str) < $length) { 
        $str .= substr($possible, rand(0,50), 1); 
        } 
    return($str); 
} 


function t($str, $att1 = '', $att2 = '', $att3 = '',$godb = 0) {
	global $client, $lang_options, $config;
	if (!$config['disable_language']) {
		$dbstr = addslashes($str);
		$res = sql_query("select * from `".tb()."langs` where lang_from='$dbstr' and lang='{$client['lang']}' LIMIT 1");
		if (!sql_counts($res)) {
			sql_query("insert into `".tb()."langs` (lang_from,lang_to,lang) values('$dbstr','','{$client['lang']}')");
		}
		else {
			$row = sql_fetch_array($res);
			if (strlen($row['lang_to'])) {
				$str = $row['lang_to'];
			}
			else {
				$str = $row['lang_from'];
			}
		}
	}
	if ($godb) {
		$str = addslashes($str);
	}
	return str_replace(array('{1}','{2}','{3}'),array($att1,$att2,$att3), $str);
}


// 
function save_img($src, $ext, $target = '') {
	$dir = date("Ym",time());
	$s_folder = uploads.'/userfiles/'.$dir;
	if (!is_dir($s_folder))
		mkdir($s_folder, 0777);

	// check photo
	if ($target) {
		$photo_name = $target;
	}
	else {
		$photo_name = $s_folder.'/'.date("H_i",time()).'_'.get_rand(5).'.'.$ext;
	}
	if (preg_match("/^http/i",$src)) {
		if ($get_file = @file_get_contents($src)) {
			$fp = @fopen($photo_name,"w");
			@fwrite($fp,$get_file);
			@fclose($fp);
			return $photo_name;
		}
		else {
			return false;
		}
	}
	else {
		if (copy($src, $photo_name)) {
			return $photo_name;
		}
		else {
			return false;
		}
	}
}


// 上传文件
function save_file($file, $allowed_ext = array('jpg','png','gif')) {
	$ext = substr($file['name'],-3,3);
	$ext = strtolower($ext);
	if (preg_match("/\./i",$ext)) $ext = substr($ext,-2,2);
	if (!in_array($ext, $allowed_ext)) {
		die('Sorry, the file type is uncorrect:'.$file['name']);
	}
	$fname = date("H_i",time()).'_'.get_rand(5);
	$dir = date("Ym",time());
	$folder = uploads.'/userfiles/'.$dir;
	$uri = $folder.'/'.$fname.'.'.$ext;
	if (!is_dir($folder))
		mkdir($folder, 0777);
	if (copy($file['tmp_name'],$uri))
		return $uri;
	else {
		return false;
	}
}

function save_thumbnail($file, $width = 100, $height = 100) {
	include_once('includes/libs/resizeimage.inc.php');
	$ext = substr($file['name'],-3,3);
	$ext = strtolower($ext);
	$allowed_ext = array('jpg','png','gif','png');
	if (!in_array($ext, $allowed_ext)) {
		die('Sorry, the pic type is uncorrect:'.$ext);
	}
	$fname = date("H_i",time()).'_'.get_rand(5);
	$dir = date("Ym",time());
	$folder = uploads.'/userfiles/'.$dir;
	$uri = $folder.'/'.$fname.'.'.$ext;
	if (!is_dir($folder))
		mkdir($folder, 0777);
	if ($width == '100') {
		$fill = 'white';
	}
	$resizeimage = new resizeimage($file['tmp_name'], $file['type'], $folder.'/'.$fname, $width, $height, 0, 80,$fill);
	return $folder.'/'.$fname.".".$resizeimage->type;
}

function jcow_cache_alert($keys = array(),$buffer = 0,$cache = '') {
	if (!$buffer) $buffer = get_gvar('jcow_cache_buffer');
	if (!$buffer) $buffer = 60;
	$timeline = time() + $buffer;
	if (!count($keys)) return false;
	$keys = implode("','",$keys);
	sql_query("update ".tb()."cache set expired='$timeline' where ckey in ('$keys') and expired>$timeline");
}



function cfm($msg = '') {
	if (!$msg)
		$msg = t('Are you sure to delete?');
	return ' onclick="return confirm(\''.$msg.'\');" ';
}

class PageBar
{
        public $total;        
        public $onepage;
        public $num;                        
        public $pagecount;
        public $total_page;
        public $offset;        
        public $linkhead;
		public $type_id;
		public $first = '';
		public $paras = "";
		public $prefix = "322_";

		public $next_page = 'Next';
		public $last_page = 'Prev';
		public $first_page = 'First';
		public $end_page = 'End';
    
        function PageBar($total, $onepage, $pagecount)
        {
                $this->total      = $total;
                $this->onepage    = $onepage;
                $this->total_page = ceil($total/$onepage);
                if (empty($pagecount))
                {
                        $this->pagecount = 1;
                        $this->offset         = 0;        
                }
                else
                {
                        $this->pagecount = $pagecount;
                        $this->offset    = ($pagecount-1)*$onepage;
                }

                $linkarr = explode("pagecount=", $_SERVER['QUERY_STRING']);
                $linkft  = $linkarr[0];

                if (empty($linkft))
                {
                        $this->linkhead = $_SERVER['PHP_SELF']."?".$formlink;
                }
                else
                {
                        $linkft    = (substr($linkft, -1)=="&")?$linkft:$linkft."&";
                        $this->linkhead = $_SERVER['PHP_SELF']."?".$linkft.$formlink;
                }

        }
        function offset()
        {
                return $this->offset;
        }

        function pre_page($char='')
        {
                $linkhead  = $this->linkhead;
                $pagecount = $this->pagecount;
                if (empty($char))
                {
                        $char = $this->last_page;
                }

                if ($pagecount>1)
                {
                        $pre_page = $pagecount - 1;
						if ($pre_page == 1) {
							return " <a href=\"".$this->paras."\">$char</a> ";
						}
						else {
							return " <a href=\"".$this->paras."page=".$pre_page."\">$char</a> ";
						}
                }
                else
                {
                        return '';
                }

        }

        function next_page($char='')
        {
                $linkhead   = $this->linkhead;
                $total_page = $this->total_page;
                $pagecount  = $this->pagecount;
                if (empty($char))
                {
                        $char = $this->next_page;
                }
                if ($pagecount<$total_page)
                {
                        $next_page = $pagecount + 1;
                        return " <a href=\"".$this->paras."page=".$next_page."\">$char</a> ";
                }
                else
                {
                        return '';
                }
        }

        function num_bar($num='', $color='', $left='', $right='')
        {
                $num       = (empty($num))?10:$num;
                $this->num = $num;
                $mid       = floor($num/2);
                $last      = $num - 1; 
                $pagecount = $this->pagecount;
                $totalpage = $this->total_page;
                $linkhead  = $this->linkhead;
                $color     = (empty($color))?"#ff0000":$color;
                $minpage   = (($pagecount-$mid)<1)?1:($pagecount-$mid);
                $maxpage   = $minpage + $last;
                if ($maxpage>$totalpage)
                {
                        $maxpage = $totalpage;
                        $minpage = $maxpage - $last;
                        $minpage = ($minpage<1)?1:$minpage;
                }

                for ($i=$minpage; $i<=$maxpage; $i++)
                {
                        $char = $left.$i.$right;
                        if ($i==$pagecount)
                        {
                        $linkchar = "<strong>$char</strong>";
                        }
						elseif ($i == 1) {
							$linkchar = " <a href=\"".$this->paras."\">".$char."</a> ";
						}
						else{
                        //$linkchar = " <a href='".$this->prefix.$i.".htm'>".$char."</a> ";
                        $linkchar = " <a href=\"".$this->paras."page=$i\">".$char."</a> ";
						}
                        $linkbar  = $linkbar.$linkchar;
                }

                return $linkbar;
        }

        function pre_group($char='')
        {
                $pagecount   = $this->pagecount;
				if ($pagecount > 2)
					if ($this->first) {
						$content = " <a href=\"".$this->first."\">".$this->first_page."</a> ";
					}
					else {
						$content = " <a href=\"".$this->paras."\">".$this->first_page."</a> ";
					}
				else
					$content = "";
                return "";
        }

        function next_group($char='')
        {
                $pagecount = $this->pagecount;
                $linkhead  = $this->linkhead;
                $totalpage = $this->total_page;
				if ($pagecount < ($totalpage - 1))
					$content = " <a href=\"".$this->paras."page=".$totalpage."\">".$this->end_page."</a> ";
				else
					$content = "";
                return "";
        }

    function whole_num_bar($num='', $color='')
    {
		if ($this->total <= $this->onepage) {
			return '';
		}
		if (preg_match("/\?/i",$this->paras)) {
			$this->paras = $this->paras.'&';
		}
		else {
			$this->paras = $this->paras.'?';
		}

        $num_bar    = $this->num_bar($num, $color);
        $pre_group  = $this->pre_group();
        $pre_page   = $this->pre_page();
        $next_page  = $this->next_page();
        $next_group = $this->next_group();

            $pagebar =  $pre_group.$pre_page.$num_bar.$next_page.$next_group;
			if ($pagebar == '<strong>1</strong>')
				return "";
			else
				return '<div id="pager">'.$pagebar.'</div>';
    }

}

function hide_menubar() {
	global $config;
	$config['hide_menubar'] = 1;
}

function nav($foo) {
	global $nav;
	$nav[] = $foo;
}

function frd_request() {
	global $client;
	if (!$client['id']) {
		return false;
	}
	else {
		$res = sql_query("select count(*) as num from `".tb()."friend_reqs` where fid='{$client['id']}'");
		$row = sql_fetch_array($res);
		$frd_new = $row['num'] ? '('.$row['num'].')' : '';
		if ($row['num']) {
			$link = url('friends/requests');
		}
		else {
			$link = url('friends');
		}
		return '<a href="'.$link.'" id="jcow_frd_link">Friends <span id="jcow_frd_new">'.$frd_new.'</span></a>';
	}
}


function msg_unread() {
	global $client;
	if ($client['id']) {
		$res = sql_query("select count(*) as num from `".tb()."messages` where to_id='{$client['id']}' and from_id>0 and !hasread");
		$row = sql_fetch_array($res);
		$num = $row['num'];
		if ($num) {
			$num =  '('.$num.')';
		}
		else {
			$num = '';
		}
		return ' <span id="jcow_msg_new">'.$num.'</span>';
	}
	else {
		return false;
	}
}

function note_unread() {
	global $client;
	if ($client['id']) {
		$res = sql_query("select count(*) as num from `".tb()."messages` where to_id='{$client['id']}' and from_id=0 and !hasread");
		$row = sql_fetch_array($res);
		$num = $row['num'];
		if ($num) {
			$num =  '('.$num.')';
		}
		else {
			$num = '';
		}
		return ' <span id="jcow_note_new">'.$num.'</span>';
	}
	else {
		return false;
	}
}

function send_note($id, $msg) {
	global $client;
	if ($client['id'] == $id)
		return false;
	$note['message'] = addslashes($msg);
	$note['created'] = time();
	$note['to_id'] = $id;
	sql_insert($note,tb().'messages');
}

function set_text($key, $value) {
	$res = sql_query("select * from `".tb()."texts` where tkey='$key'");
	if (sql_counts($res)) {
		sql_query("update `".tb()."texts` SET tvalue='$value' WHERE tkey='$key'");
	}
	else {
		sql_query("insert into `".tb()."texts` (tkey,tvalue) values('$key','$value')");
	}
}

function get_text($key) {
	$res = sql_query("select * from `".tb()."texts` where tkey='$key' ");
	$row = sql_fetch_array($res);
	return $row['tvalue'];
}

function delete_text($key) {
	sql_query("delete from `".tb()."texts` where tkey='$key' ");
}

function avatar($row, $type = 'small') {
	global $uhome, $ubase;
	if ($row['uid']) {
		$row['id'] = $row['uid'];
	}
	if (!$row['avatar']) {
		$row['avatar'] = 'undefined.jpg';
	}
	
	if ($type == 'small') {
		$row['avatar'] = 's_'.$row['avatar'];
	}
	
	if (is_numeric($type)) {
		$row['avatar'] = 's_'.$row['avatar'];
		$csize = ' width="'.$type.'" height="'.$type.'" ';
	}
	if ($type != 'small' && !is_numeric($type)) {
		$csize = ' width="160" height="160" ';// add from 7.0
	}
	return '<a href="'.$ubase.'u/'.$row['username'].'">
	<img '.$csize.' src="'.$uhome.'/'.uploads.'/avatars/'.$row['avatar'].'" class="avatar" /></a>';
}

function page_logo($page, $type = 'small') {
	global $uhome, $ubase;
	if (!$page['logo']) {
		$page['logo'] = 'logo.jpg';
	}
	
	if ($type == 'small') {
		$page['logo'] = 's_'.$page['logo'];
	}
	
	if (is_numeric($type)) {
		$page['logo'] = 's_'.$page['logo'];
		$csize = ' width="'.$type.'" height="'.$type.'" ';
	}
	if ($type != 'small' && !is_numeric($type)) {
		$csize = ' width="160" height="160" ';
	}
	return '<a href="'.$ubase.$page['type'].'/'.$page['uri'].'">
	<img '.$csize.' src="'.$uhome.'/'.uploads.'/avatars/'.$page['logo'].'" class="avatar" /></a>';
}


function ubase() {
	global $ubase;
	return $ubase;
}
function uhome() {
	global $uhome;
	return $uhome;
}
function theme_folder() {
	global $theme_folder;
	return $theme_folder;
}

function get_friends($uid = 0) {
	global $client;
	if (!$uid)
		$uid = $client['id'];
	if (!$uid)
		return false;
	$res = sql_query("select fid from `".tb()."friends` where uid='$uid' LIMIT 10");
	while ($row = sql_fetch_array($res)) {
		$ids[] = $row['fid'];
	}
	return $ids;
}

// active sidebars
function ass($arr, $status = '') {
	block($arr, $status);
}

function block($arr, $status = '') {
	global $blocks;
	if ($arr['box']) {
		$arr['content'] = '<div class="block_box">'.$arr['box'].'</div>'.$arr['content'];
	}
	if ($status == 'highlight') {
		$arr['highlight'] = 1;
	}
	$blocks[] = $arr;
}

function section($arr) {
	global $sections;
	$sections[] = $arr;
}


function section_content($content = '') {
	global $section_content;
	$section_content .= $content;
}

function section_close($title = '') {
	global $section_content;
	section(array('title'=>$title,'content'=>$section_content));
	$section_content = '';
}

function sys_notice($notice) {
	global $notices;
	$notices[] = $notice;
}

function user_page_id($user) {
	if (!$user['id']) return false;
	if ($user['page_id']) return $user['page_id'];
	else {
		$res = sql_query("select id from ".tb()."pages where uid='{$user['id']}' and type='u'");
		$row = sql_fetch_array($res);
		return $row['id'];
	}
}

function client($key='') {
	global $client;
	if (!$key)
		return $client;
	else
		return $client[$key];
}

function jcow_mail($to,$subject,$message,$reply = '') {
	if (function_exists('jcow_user_mail')) {
		return jcow_user_mail($to,$subject,$message,$reply);
	}
	else {
		if (!$reply)
			$reply = get_gvar('site_name').'<noreply@'.$_SERVER['HTTP_HOST'].'>';
		$headers = "From: $reply\r\n" .
				"Message-ID: <".time()."-".$reply.">\r\n".
				'X-Mailer: PHP/' . phpversion() . "\r\n" .
				"MIME-Version: 1.0\r\n" .
				"Content-Type: text/html; charset=utf-8 \r\n" .
				"Content-Transfer-Encoding: 8bit\r\n\r\n";
		// Send
		$message = str_replace("\r\n",'<br />',$message);
		$mail_sent = @mail($to, $subject, $message, $headers);
		return $mail_sent;
	}
}


function save_u_settings($arr) {
	global $client;
	if (!$client['id']) return false;
	foreach ($arr as $key=>$value) {
		$client['settings'][$key] = $value;
	}
	sql_query("update ".tb()."accounts set settings='".addslashes(serialize($client['settings']))."' where id='{$client['id']}'");
	return true;
}

function get_gvar($key) {
	global $gvars;
	return $gvars[$key];
}

function record_this_posting($message) {
	return true;
	// this function was disabled from 5.2.1
	/*
	if (get_gvar('autoban')) {
		global $client;
		if (!$autoban_acts = get_gvar('autoban_acts')) {
			$autoban_acts = 3;
		}
		if (!$autoban_trusted = get_gvar('autoban_trusted')) {
			$autoban_trusted = 30;
		}
		if (allow_access(3)) {
			return true;
		}
		if ((time() - $client['created']) > 3600*24*$autoban_trusted) {
			return true;
		}
		$hash = substr(md5(trim($message)),0,5);
		sql_query("insert into ".tb()."user_crafts (uid,hash,created) values('{$client['id']}','$hash',".time().")");
		$timeline = time() - 3600;
		$res = sql_query("select count(*) as num from ".tb()."user_crafts where uid='{$client['id']}' and hash='$hash' and created>$timeline");
		$row = sql_fetch_array($res);
		if ($row['num'] > $autoban_acts) {
			jcow_ban($client['ip'],$client['username'],time()+3600*24);
			sql_query("delete from ".tb()."user_crafts where uid='{$client['id']}'");
		}
	}
	*/
}

function jcow_ban($ip,$username,$expired,$operator='') {
	$ips = explode('.',$ip);
	sql_query("insert into ".tb()."banned (ip1,ip2,ip3,ip4,username,expired,created,operator)
	values('{$ips[0]}','{$ips[1]}','{$ips[2]}','{$ips[3]}','$username',$expired,".time().",'$operator')");
}

function set_gvar($key, $value = '') {
	global $gvars;
	if ($value == '') {
		sql_query("delete from `".tb()."gvars` WHERE gkey='$key'");
	}
	else {
		if (!isset($gvars[$key])) {
			sql_query("insert into `".tb()."gvars` (gkey,gvalue) values ('$key', '$value')");
		}
		else {
			sql_query("update `".tb()."gvars` set gvalue='$value' where gkey='$key'");
		}
	}
}

function set_tmp($key, $value = 'deleteit', $live = 1) {
	if ($value == 'deleteit') {
		sql_query("delete from `".tb()."tmp` where tkey='$key'");
	}
	else {
		$res = sql_query("select tkey from ".tb()."tmp where tkey='$key'  limit 1");
		if (sql_counts($res)) {
			sql_query("update ".tb()."tmp set tcontent='$value' where tkey='$key'");
		}
		else {
			sql_query("insert into `".tb()."tmp` (tkey,tcontent) values('$key','$value')");
		}
	}
}
function get_tmp($key, $opt = '') {
	$res = sql_query("select * from `".tb()."tmp` where tkey='$key'");
	$row = sql_fetch_array($res);
	if ($opt == 'delete') {
		sql_query("delete from `".tb()."tmp` where tkey='$key'");
	}
	return $row['tcontent'];
}


function set_cache($key, $value = '', $live = 0) {
	if (!$live) {
		$live = 1;
	}
	$expired = time() + 3600*$live;
	$res = sql_query("select content from `".tb()."cache` where ckey='$key'");
	if (!sql_counts($res)) {
		sql_query("insert into ".tb()."cache (ckey,content,expired) values('$key','".addslashes($value)."',$expired)");
	}
	else {
		sql_query("update ".tb()."cache set content='".addslashes($value)."',expired=$expired where ckey='$key'");
	}
}
function get_cache($key) {
	$res = sql_query("select content from `".tb()."cache` where ckey='$key' and expired>".time());
	if (!sql_counts($res))
		return false;
	else {
		$row = sql_fetch_array($res);
		return $row['content'];
	}
}


function convert_blocks($content) {
	global $client;
	if ($client['id']) {
		$content = preg_replace("({guest}(.+){\/guest})",'',$content);
		$content = str_replace('{member}','',$content);
		$content = str_replace('{/member}','',$content);
	}
	else {
		$content = preg_replace("({member}(.+){\/member})",'',$content);
		$content = str_replace('{guest}','',$content);
		$content = str_replace('{/guest}','',$content);
	}
	return $content;
}

function user_post($row, $convert = 1, $simple = 0, $hide_avatar = 0) {
	// $convert: convert HTML or not
	global $user_post_i;
	$i = $user_post_i%2 + 1;
	$user_post_i++;
	if ($row['vote'] != 0) {
		if ($row['vote'] > 0) $row['vote'] = '+'.$row['vote'];
		$row['vote_msg'] = '('.$row['vote'].')';
	}
	if ($convert) {
		$row['content'] = nl2br(decode_bb(htmlspecialchars($row['content'])));
	}
	/*
	if ($row['sid'] && $row['app']) {
		$row['content'] = url($row['app'].'/viewstory/'.$row['sid'],'#'.h($row['stitle'])).'<br />'.$row['content'];
	}
	*/
	if (!$hide_avatar) $avatar = '<td class="user_post_left" width="42" valign="top">'.avatar($row,25).'</td>';
	if ($simple) {
		return '
		<div class="user_post_'.$i.'">
			<table>
			<tr>
			'.$avatar.'
			<td class="user_post_right" valign="top">'.url('u/'.$row['username'],$row['username']).
			' '.$row['vote_msg'].' <span class="sub">'.get_date($row['created']).'</span><br />'.$row['content'].' </td>
			</tr>
			</table>
		</div>
			';
	}
	else {
		if ($row['num']) {
			$row_num = '<span class="sub">#'.$row['num'].'</span> ';
		}
		return '
		<div class="user_post_'.$i.'">
			<table width="100%">
			<tr>
			<td class="user_post_left" width="60" valign="top">'.avatar($row).'</td>
			<td class="user_post_right" valign="top"><div class="user_post_head">'.$row_num.url('u/'.$row['username'],$row['username']).
			' '.$row['vote_msg'].' <span class="sub">'.get_date($row['created']).'</span></div>'.$row['content'].'</td>
			</tr>
			</table>
		</div>
			';
	}
}


function group_post($row, $type = 'summary') {
	// $convert: convert HTML or not
	global $client, $group;
	if (!$row['uri']) $row['uri'] = $group['uri'];
	$row['rname_label'] = $row['rname'];
	$row['username_label'] = $row['username'];
	$row['message'] = nl2br(decode_bb(htmlspecialchars($row['message'])));
	if ($type == 'summary') {
		$row['message'] .= '..<br />'.url('group/'.$row['uri'].'/viewpost/'.$row['id'],t('See more'));
		$row['message'] .= ' | '.url('group/'.$row['uri'].'/viewpost/'.$row['id'],t('Reply({1})',$row['replies']));
	}
	else {
		$row['message'] .= '<br />'.url('group/'.$row['uri'].'/viewpost/'.$row['id'],'Reply('.$row['replies'].')');
	}
	if ($row['tid']) {
		$topic = '<br />'.url('group/'.$row['uri'].'/viewtopic/'.$row['tid'],'#'.h($row['topic']) );
	}
	if ($row['rtid']) {
		$reply = url('group/'.$row['uri'].'/viewpost/'.$row['rtid'],'@'.h($row['rname_label']) );
	}
	if (!$hide_avatar) $avatar = '<td class="user_post_left" width="42" valign="top">'.avatar($row,50).'</td>';

		if ($row['num']) {
			$row_num = '<span class="sub">#'.$row['num'].'</span> ';
		}
		return '
		<div class="user_post_1">
			<table width="100%">
			<tr>
			<td class="user_post_left" width="62" valign="top">'.avatar($row).'</td>
			<td class="user_post_right" valign="top"><div class="user_post_head">'.$row_num.url('u/'.$row['username'],$row['username']).
			' '.$reply.' <span class="sub">'.get_date($row['created']).$topic.'</span></div>'
			.$row['message'].'
			</td>
			</tr>
			</table>
		</div>
			';

}

function convert_html($content) {
	global $config;
	return preg_replace(
		'/<a /i','<a rel="nofollow external" ',
		strip_tags($content,$config['allowed_html_tags'])
		);
}

function tweet($status) {
	$username = get_gvar('twitter_username');
	$password = get_gvar('twitter_password');
	if (!strlen($username) || !strlen($password)) return false;
	if ($status) {
		$tweetUrl = 'http://www.twitter.com/statuses/update.xml';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "$tweetUrl");
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, "status=$status");
		curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
		$result = curl_exec($curl);
		$resultArray = curl_getinfo($curl);
		if ($resultArray['http_code'] == 200)
			return true;
		else
			return false;
		curl_close($curl);
	}
}


function var_cache($key, $value = 'none') {
	global $var_cache_live;
	$timeline = time() - $var_cache_live;
	if ($value == 'none') { // get
		$res = sql_query("select content from `".tb()."var_cache` where created>$timeline and name='$key' ORDER BY created DESC LIMIT 1");
		if (!sql_counts($res)) {
			return false;
		}
		else {
			$row = sql_fetch_array($res);
			return $row['content'];
		}
	}
	else { // set
		sql_query("insert into `".tb()."var_cache` (name,content,created) values ('$key', '$value','".time()."')");
	}
}

function h($str) {
	return htmlspecialchars($str);
}

function tb() {
	global $table_prefix;
	return $table_prefix;
}

function p2l($p) {
	if($p >= get_gvar('user_lv8'))
		return 8;
	if($p >= get_gvar('user_lv7'))
		return 7;
	if($p >= get_gvar('user_lv6'))
		return 6;
	if($p >= get_gvar('user_lv5'))
		return 5;
	if($p >= get_gvar('user_lv4'))
		return 4;
	if($p >= get_gvar('user_lv3'))
		return 3;
	if($p >= get_gvar('user_lv2'))
		return 2;
	else
		return 1;
}

function parseurl($msg) {
        $search_array = array(
            "/([^\]]+)(http:\/\/.+)([\r\n\s]+)/isU"
		);
		$replace_array = array(
            "\\1[url]\\2[/url]\\3"
		);
		return preg_replace($search_array, $replace_array, $msg.' ');
}


function set_subtitle($title) {
	global $sub_title;
	$sub_title = $title;
}

function get_filesize($size) {
	if ($size < 1000) {
		return $size.' bytes';
	}
	elseif ($size < 1024*1024) {
		return ceil($size/1024).' kb';
	}
	elseif ($size < 1024*1024*1024) {
		return number_format($size/(1024*1024),2).' mb';
	}
	else {
		return number_format($size/(1024*1024*1024),2).' gb';
	}
}

function need_login() {
	global $client;
	if (!$client['id']) {
		redirect('member/login/1');
	}
}

function jcow_in_array($str, $arr) {
	if (!is_array($arr))
		return false;
	if (in_array($str, $arr))
		return true;
	else
		return false;
}

function show_ad($key) {
	if ($croles = get_gvar('hide_ad_roles')) {
		$croles = explode('|',$croles);
		global $client;
		foreach ($client['roles'] as $role) {
			if (in_array($role, $croles)) {
				return '';
			}
		}
	}
	return get_gvar($key);
}

function nid() {
	global $network;
	return $network['id'];
}
function network($key) {
	global $network;
	return $network[$key];
}

function label($val) {
	return '<span class="form_label">'.$val.'</span>';
}

function gender($val) {
	if ($val == 1) {
		return t('Male');
	}
	elseif ($val == 0) {
		return t('Female');
	}
	else {
		return '<i>'.t('Hidden').'</i>';
	}
}

function get_age($birthyear, $hidden = 0,$birthmonth=1,$birthday=1) {
	if ($hidden)
		return '<i>'.t('Hidden').'</i>';
	$age = date("Y",time()) - $birthyear;
	if ($birthmonth > date("m",time()) || 
				($birthmonth == date("m",time())&& $birthday>date("d",time()) )
			){
				$age = $age-1;
			}
	return $age;
}


function check_menu_on($menu_item) {
	global $current_menu_path,$menu_items,$parr;
	if (strlen($menu_item) && ($menu_item == $current_menu_path || $menu_item.'/index' == $current_menu_path || $menu_item == $menu_items[$current_menu_path]['parent'])) {
		return ' class="menuon" ';
	}
	elseif (strlen($parr[1])) {
		$key = $parr[0].'/'.$parr[1];
		if (!$menu_items[$key] && $parr[0] == $menu_item) {
			return ' class="menuon" ';
		}
		else {
			return ' class="menugen" ';
		}
	}
	else {
		return ' class="menugen" ';
	}
}

function check_tabmenu_on($menu_item) {
	global $current_menu_path,$real_path,$parr,$defined_current_tab;
	if ($defined_current_tab) {
		$tab = $defined_current_tab;
	}
	elseif (strlen($real_path)) {
		$tab = $real_path;
	}
	else {
		$tab = $current_menu_path;
	}
	if (strlen($menu_item) && $menu_item == $tab) {
		return ' class="on" ';
	}
	else {
		return ' class="ge" ';
	}
}
function tabmenu_begin() {
	global $menu_items,$top_menu_path;
	if (strlen($menu_items[$top_menu_path]['tab_name'])) {
			return '<li '.check_tabmenu_on($menu_items[$top_menu_path]['path']).'>'.url($menu_items[$top_menu_path]['path'],t($menu_items[$top_menu_path]['tab_name'])).'</li>';
		}
	else {
		return '';
	}
}
function set_menu_path($path) {
	global $menuon;
	$menuon = $path;
}
function set_return($path) {
	setcookie('j_return_url', $path, time()+3600*24*365,"/");
}

function add_links($menu) {
	if ($name = get_gvar('cmi1_name')) {
		$menu[] = array('link'=>'<a href="'.get_gvar('cmi1_link').'"><div style="padding:3px 0 3px 23px;background:url('.uhome().'/files/appicons/links.png) 0 1px no-repeat">'.h($name).'</div></a>','name'=>h($name),'href'=>get_gvar('cmi1_link'));
	}
	if ($name = get_gvar('cmi2_name')) {
		$menu[] = array('link'=>'<a href="'.get_gvar('cmi2_link').'"><div style="padding:3px 0 3px 23px;background:url('.uhome().'/files/appicons/links.png) 0 1px no-repeat">'.h($name).'</div></a>','name'=>h($name),'href'=>get_gvar('cmi2_link'));
	}
	if ($name = get_gvar('cmi3_name')) {
		$menu[] = array('link'=>'<a href="'.get_gvar('cmi3_link').'"><div style="padding:3px 0 3px 23px;background:url('.uhome().'/files/appicons/links.png) 0 1px no-repeat">'.h($name).'</div></a>','name'=>h($name),'href'=>get_gvar('cmi3_link'));
	}
	if ($name = get_gvar('cmi4_name')) {
		$menu[] = array('link'=>'<a href="'.get_gvar('cmi4_link').'"><div style="padding:3px 0 3px 23px;background:url('.uhome().'/files/appicons/links.png) 0 1px no-repeat">'.h($name).'</div></a>','name'=>h($name),'href'=>get_gvar('cmi4_link'));
	}
	if ($name = get_gvar('cmi5_name')) {
		$menu[] = array('link'=>'<a href="'.get_gvar('cmi5_link').'"><div style="padding:3px 0 3px 23px;background:url('.uhome().'/files/appicons/links.png) 0 1px no-repeat">'.h($name).'</div></a>','name'=>h($name),'href'=>get_gvar('cmi5_link'));
	}
	return $menu;
}


function jversion() {
	global $version;
	return $version;
}

function show_rss($rss) {
	foreach ($rss['items'] as $item) {
		$items .= '
			<item>
			<title>'.$item['title'].'</title>
			<link>'.$item['link'].'</link>
			<pubDate>'.get_date($item['created']).'</pubDate>
			</item>
			';
	}

	return '<?xml version="1.0" encoding="UTF-8"?>
	<rss version="2.0"
		xmlns:content="http://purl.org/rss/1.0/modules/content/"
		xmlns:wfw="http://wellformedweb.org/CommentAPI/"
		xmlns:dc="http://purl.org/dc/elements/1.1/"
		xmlns:atom="http://www.w3.org/2005/Atom"
		xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
		xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
		xmlns:georss="http://www.georss.org/georss" xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" xmlns:media="http://search.yahoo.com/mrss/"
		>

	<channel>
		<title>'.$rss['title'].'</title>
		<atom:link href="'.$rss['link'].'/feed" rel="self" type="application/rss+xml" />
		<link>'.$rss['link'].'</link>
		'.$items.'
	</channel>
	</rss>
	';

}

function limit_posting($onform = 0,$ajax = 0) {
	global $client;
	if ($client['disabled']) {
		if ($ajax) {
			echo '<div style="color:red">'.t('Your account is not verified yet.');
			if (strlen(get_gvar('fb_id')))
				echo ' '.url('fblogin',t('Verify by facebook'));
			echo '</div>';
			exit;
		}
		else {
			$output = t('Your account is not verified yet.');
			if (strlen(get_gvar('fb_id')))
				$output .= ' '.url('fblogin',t('Verify by facebook'));
			sys_back($output);
		}
	}
	if (allow_access(3)) {
		return true;
	}
	if (!check_limit_posting()) {
		$error = t('You have exceeded the posting volume limit, please wait for a few hours, thanks');
	}
	elseif(!$onform){
		sql_query("insert into ".tb()."limit_posting(uid,created) values('{$client['id']}',".time().") ");
	}
	if (strlen($error)) {
		if ($ajax) {
			die($error);
		}
		else {
			sys_back($error);
		}
	}
}

function check_limit_posting() {
	global $client;
	if (!$client['limit_posting_exceed']) {
		return true;
	}
	else {
		return false;
	}
}


function clear_report() {
	global $config;
	$config['clear_report'] = 1;
}

function enreport() {
	global $config;
	$config['enreport'] = 1;
}

function is_redirecting() {
	global $auto_redirect;
	if (strlen($auto_redirect)) {
		return true;
	}
	else {
		return false;
	}
}

function timeselector($default = 0) {
	GLOBAL $settings, $client;
	if (!$default) {
		$default = time();
	}
	$default = $default + $client['timezone']*3600;
	$default = gmdate('g:i a',$default);
	$arr = explode(':',$default);
	$arr2 = explode(' ',$arr[1]);
	$hour = $arr[0];
	$ap =$arr2[1];
	$default = $hour.':30 '.$ap;
		
	$timearr = array('12:00 am','12:30 am','1:00 am','1:30 am','2:00 am','2:30 am','3:00 am','3:30 am','4:00 am','4:30 am','5:00 am','5:30 am','6:00 am','6:30 am','7:00 am','7:30 am','8:00 am','8:30 am','9:00 am','9:30 am','10:00 am','10:30 am','11:00 am','11:30 am','12:00 pm','12:30 pm','1:00 pm','1:30 pm','2:00 pm','2:30 pm','3:00 pm','3:30 pm','4:00 pm','4:30 pm','5:00 pm','5:30 pm','6:00 pm','6:30 pm','7:00 pm','7:30 pm','8:00 pm','8:30 pm','9:00 pm','9:30 pm','10:00 pm','10:30 pm','11:00 pm','11:30 pm');
	$output = '<select name="time">';
	foreach ($timearr as $val) {
		if ($default == $val) {
			$output .= '<option value="'.$val.'" selected>'.$val.'</option>';
		}
		else {
			$output .= '<option value="'.$val.'">'.$val.'</option>';
		}
	}
	$output .= '</select>';
	return $output;
}

function app_header($content) {
	global $app_header;
	$app_header = $content;
}



function stream_form($preset='',$page = array()) {
	global $config ,$client,$miniblog_maximum;
	if (!$client['id']) return false;
	if (!count($page)) {
		$page = array('id'=>$client['page']['id'],'type'=>'u','uid'=>$client['id']);
	}
	
	$photo_style = $video_style = 'style="display:none"';
	if ($preset == 'photo') {
		$photo_style = '';
		$attachment_type = 'photo';
		$photo_att_button_on = ' att_button_on';
		$default_msg = 'Photo description...';
	}
	elseif ($preset == 'video') {
		$video_style = '';
		$attachment_type = 'video';
		$video_att_button_on = ' att_button_on';
		$default_msg = 'Video description...';
	}
	else {
		$status_att_button_on = ' att_button_on';
		if ($page['uid'] == $client['id'] && ($page['type'] == 'u' || $page['type'] == 'page')) {
			$default_msg = t('Share with your followers...');
		}
		else {
			$default_msg = t('Post something...');
		}
	}
	$quick_apps = check_hooks('quick_share');
	$miniposttag = t('Status');
	if ($client['id']) {
		$res = sql_query("select pid from ".tb()."page_users where pid='{$page['id']}' and uid='{$client['id']}'");
		if(sql_counts($res)) {
			$connected = 1;
		}
		if ($page['type'] == 'u' && $page['uid'] != $client['id']) {
			$res = sql_query("select username from ".tb()."accounts where id='{$page['uid']}'");
			$owner = sql_fetch_array($res);
			$commentat = $default_msg = '@'.$owner['username'].' ';
			$target_page_id = $client['page']['id'];
			$oncomment = 1;
			$miniposttag = t('Comment');
		}
		else {
			$oncomment = 0;
			$commentat = '';
			$target_page_id = $page['id'];
		}
	}
	if (is_array($quick_apps) and count($quick_apps) > 0) {
		foreach ($quick_apps as $key=>$app) {
			$hook_func = $app.'_quick_share';
			$res = $hook_func();
			$page_type = $page['type'];
			$quick_share = $res[$page_type];
			if (is_array($quick_share)) {
				if ($quick_share['access'] == 'everyone' || 
				($quick_share['access'] == 'connected' && $connected) ||
				($page['uid'] == $client['id'])
				) {
					if (!is_numeric($quick_share['weight']))
						$quick_share['weight'] = $key;
					$quick_share['app'] = $app;
					$quick_shares[] = $quick_share;
				}
			}
		}
		$quick_shares = array_sort($quick_shares, 'weight', SORT_ASC);
		if (!$client['disabled']) {
			foreach ($quick_shares as $quick_share) {
				$page_type = $page['type'];
				$app = $quick_share['app'];
				$flag = $app;
				if ($quick_share['flag'])
					$flag = $quick_share['flag'];
				$a_button .= '
				<a href="javascript:void();" class="att_button" id="stream_att_'.$app.'_radio"><img src="'.uhome().'/modules/'.$app.'/icon.png" align="absmiddle" />'.$flag.'</a> 
				';
				$a_js .= '
				$("#stream_att_'.$app.'_radio").click(function() {
						$("#form_message").hide();
						$("span#spanstatus").html("<img src=\"'.uhome().'/files/loading.gif\" /> Loading");
						$("#apps_box").load("'.url($app.'/ajax_form/'.$page_type.'/'.$page['id']).'", function() {
							$("span#spanstatus").html("");
							$("#form_submit").removeAttr("disabled");
							$("#form_submit").addClass("att_submit_active");
						});
						$(".att_button").removeClass("att_button_on");
						$(this).addClass("att_button_on");
						$("#attachment").attr("value","'.$app.'");
						$("#charsRemaining").html("");
					});
					';
			
			}
		}
	}
	$output =  
		'
		<script>
		$(document).ready( function(){
				var options = {beforeSubmit:showRequest,success:showStream};
				$("#stream_form").ajaxForm(options);
				
				function showRequest() {
					if ($("#form_message")[0].value == "" && $("#attachment").val() == "status") {
						alert("please input something..");
						return false;
					}
					$("span#spanstatus").html("<img src=\"'.uhome().'/files/loading.gif\" /> '.addslashes(h(t('Submitting'))).'");
					$("#charsRemaining").html("");
					$("#post_form").slideToggle("slow");
				}
				function showStream(responseText, statusText)  {
					$("span#spanstatus").html("");
					if ($("#attachment").val() == "status") {
						$("#posts_head").after(responseText);
						$("#form_message").attr("value","'.$commentat.'");
						$("#form_title").attr("value","");
						$("#form_submit").attr("disabled",true);
						$("#form_submit").removeClass("att_submit_active");

						$("#apps_box").html("");
						$(".stream_atts").css("display","none");
						$(".att_button").removeClass("att_button_on");
						$("#stream_att_status_radio").addClass("att_button_on");
						$("#attachment").attr("value","status");
						$("#form_message").attr("rows",3);
						$("#form_message").show();

						$("#post_form").slideToggle("slow");
					}
					else {
						$("#apps_box").html(responseText);
						$(".stream_atts").css("display","none");
						$("#post_form").slideToggle("slow");
					}
				}

				$("#form_message").click(function() {
					if ($("#form_message").attr("rows") == 2) {
							$("#form_message").attr("value","'.$commentat.'");
							$("#form_message").attr("rows",3);
							$("#stream_att").css("display","block");
						}
				});
				$("#form_message").bind("change keyup",function() {
					$("#form_submit").removeAttr("disabled");
					$("#form_submit").addClass("att_submit_active");
				});

				$("#form_message").keyup(function() {
					if ($("#attachment").val() == "status") {
						var max = parseInt($("#form_message").attr("maxlength"));
						if($(this).val().length > max){
							$(this).val($(this).val().substr(0, $(this).attr("maxlength")));
						}
						$("#charsRemaining").html("You have <strong>" + (max - $(this).val().length) + "</strong> characters remaining");
					}
				});

				$("#stream_att_status_radio").click(function() {
					$("#apps_box").html("");
					$(".att_button").removeClass("att_button_on");
					$(this).addClass("att_button_on");
					$("#attachment").attr("value","status");
					$("#form_message").show();
				});
				';
				$output .= $a_js;
				 
		
		if ($client['disabled'] == 1) {
			$default_msg = h(t('Your account is not verified yet.'));
			$status_form = '<textarea name="message" rows="2" id="form_message" maxlength="'.$miniblog_maximum.'" class="fpost"  style="width:95%" disabled >'.$default_msg.'</textarea>';
		}
		else {
			$status_form = '<textarea name="message" rows="2" id="form_message" maxlength="'.$miniblog_maximum.'" class="fpost"  style="width:95%" >'.$default_msg.'</textarea>';
		}
		
		$output .= '

		});
		</script>

		<div id="error_box"></div>
		<div id="post_form">
			<div id="stream_att">
				<a href="javascript:void();" class="att_button'.$status_att_button_on.'" id="stream_att_status_radio"><img src="'.uhome().'/files/appicons/status.png" align="absmiddle" />'.$miniposttag.'</a>
				'.$a_button.'
			</div>
			<div style="width:100%;clear:both"></div>

			<div id="stream_box">
				<form id="stream_form" action="'.url('streampublish').'" method="post" enctype="multipart/form-data">
				<input type="hidden" name="page_id" value="'.$target_page_id.'" />
				<input type="hidden" name="oncomment" value="'.$oncomment.'" />
				<input type="hidden" name="page_type" value="'.$page['type'].'" />
				'.$status_form.'
				<div id="apps_box"></div>
				<div id="charsRemaining"></div>

				<div class="stream_atts" id="stream_att_video" '.$video_style.'>
					http://www.youtube.com/watch?v=<input type="text" size="15" name="youtubeid" />
				</div>
				<table border="0" width="95%">
				<tr>
				<td>
				</td>
				<td align="right">
				<input type="hidden" id="attachment" name="attachment" value="status" />
				<input type="submit" value=" '.t('Post').' " class="fbutton" id="form_submit" disabled />
				</td>
				</tr>
				</table>
				</form>
			</div>

		</div>
		<span id="spanstatus"></span>
		
		<div id="posts_head"></div>';
		return $output;
		}


function reply_form($stream_id, $replyto = '') {
	global $config, $client;
	if (!$replyto) return '';
	if (!check_limit_posting()) {
		return '';
	}
	if (!$client['id']) {
		return '';
	}
	$likeflag = t('Like');
	$res = sql_query("select * from ".tb()."streams where id='$stream_id'");
	$stream = sql_fetch_array($res);
	if (!$stream['id']) return '';
	$flag = t('Reply');
	$likeit = '<span> <a href="#" class="dolike">'.$likeflag.'</a></span>';
	
	if ($flag == 'none') {
		$comment_link = '<span></span>';
	}
	else {
		if ($no_like) {
			$comment_link = '<a href="#" class="quick_comment">+'.$flag.'</a>';
		}
		else {
			$comment_link = '<a href="#" class="quick_comment">+'.$flag.'</a>';
		}
	}

		return '
		<div>
		'.$comment_link.' <span></span>
			<div class="quick_comment_form" style="display:none;">
				<table border="0"><tr><td valign="top">
				<img src="'.uhome().'/'.uploads.'/avatars/s_'.$client['avatar'].'" width="25" height="25" />
				</td><td>
				<input name="message" rows="2" maxlength="140" class="fpost commentmessage""  style="width:350px;" value="@'.h($replyto).'" />
				<input type="button" value=" '.$flag.' " class="fbutton commentsubmit" />
				</td></tr>
				</table>
			</div>
			<input type="hidden" name="wall_id" value="'.$stream_id.'" />
			<div></div>
		</div>';
}

function comment_form($stream_id, $flag = '', $likeflag = '') {
	global $config, $client;
	if (!check_limit_posting()) {
		return '';
	}
	if (!$client['id']) {
		return '<div>&gt;&gt;'.url('member/login',t('Login to comment') ).'</div>';
	}
	if (strlen($likeflag)<2) {
		$likeflag = t('Like');
	}
	$res = sql_query("select s.*,u.username from ".tb()."streams as s left join ".tb()."accounts as u on u.id=s.uid where s.id='$stream_id'");
	$stream = sql_fetch_array($res);
	if (!$stream['id']) return '';
	if (!$flag) $flag = t('Comment');
	$likeit = '<span> <a href="#" class="dolike">'.$likeflag.'</a></span>';
	$res = sql_query("select * from ".tb()."liked where stream_id='$stream_id' and uid='{$client['id']}' limit 1");
	if (sql_counts($res)) {
		$likeit = '<span><a href="#" class="dolike">'.t('Unlike').'</a></span>';
	}
	if ($flag == 'none') {
		$comment_link = '<span></span>';
	}
	else {
		if ($no_like) {
			$comment_link = '<a href="#" class="quick_comment">+'.$flag.'</a>';
		}
		else {
			$comment_link = '<a href="#" class="quick_comment">+'.$flag.'</a> | ';
		}
	}

		return '
		<div>
		'.$comment_link.' '.$likeit.'
			<div class="quick_comment_form" style="display:none;">
				<table border="0"><tr><td valign="top">
				<img src="'.uhome().'/'.uploads.'/avatars/s_'.$client['avatar'].'" width="25" height="25" />
				</td><td>
				<input name="message" rows="2" value="@'.$stream['username'].'" maxlength="140" class="fpost commentmessage""  style="width:350px;" />
				<input type="button" value=" '.$flag.' " class="fbutton commentsubmit" />
				</td></tr>
				</table>
			</div>
			<input type="hidden" name="wall_id" value="'.$stream_id.'" />
			<div></div>
		</div>';
}

function profile_comment_form($target_id) {
	global $config ,$client;
	if (!$client['id']) return false;
	return 
		'
		<script>
		$(document).ready( function(){
				var coptions = {beforeSubmit:showcRequest,success:showComment};
				$("#pc_form").ajaxForm(coptions);
				
				function showcRequest() {
					if ($("#pc_message")[0].value == "") {
						alert("please input something..");
						return false;
					}
					$("span#pc_status").html("<img src=\"'.uhome().'/files/loading.gif\" /> '.addslashes(h(t('Submitting'))).'");
					$("#pc_form_box").toggle("slow");
				}
				function showComment(responseText, statusText)  { 
					$("#pc_head").after(responseText);
					$("span#pc_status").html("");
					$("#pc_message").attr("value","");
					$("#pc_submit").attr("disabled",true);
					$("#pc_form_box").toggle("slow");
				}
				$("#pc_message").click(function() {
					if ($("#pc_message").attr("rows") == 2) {
							$("#pc_message").attr("value","");
							$("#whatsbb").css("display","block");
							$("#pc_message").attr("rows",7);
						}
				});
				$("#pc_message").bind("change keyup",function() {
					$("#pc_submit").removeAttr("disabled");
					$("#pc_submit").addClass("att_submit_active");
					});
		});
		</script>

		<span id="pc_status"></span>
		<div id="pc_form_box">
		<form id="pc_form" action="'.url('jquery/profile_comment_publish').'" method="post">
		
		<textarea name="message" rows="2" id="pc_message" class="fpost"  style="width:95%" >'.t('Write Comment..').'</textarea>
		
		<input type="hidden" name="target_id" value="'.$target_id.'" />
		<input type="submit" value=" '.t('Comment').' " class="fbutton" id="pc_submit" disabled />
		
		</form>
		</div>
		
		<div id="pc_head"></div>';
		}

function my_jcow_home() {
	global $client;
	if ($client['id']) {
		return url('feed');
	}
	else {
		return uhome();
	}
}

function mail_notice($type,$username,$title,$message) {
	global $client;
	$key = 'dismail_'.$type;
	$res = sql_query("select id,email,fullname,settings from ".tb()."accounts where username='$username'");
	$user = sql_fetch_array($res);
	if (!$user['id']) return false;
	if ($client['id'] == $user['id']) return false;
	$user_settings = unserialize($user['settings']);
	if (!$user_settings[$key]) {
		@jcow_mail(
			$user['email'],
			$title,
			"Dear ".$user['fullname'].",\r\n ".$message."\r\n".url('home',get_gvar('site_name'))
			);
	}
}

function check_hooks($act) {
	global $current_modules;
	foreach ($current_modules as $module) {
		$func = $module['name'].'_'.$act;
		if ($module['actived'] && function_exists($func)) {
			$hooks[] = $module['name'];
		}
	}
	if (is_array($hooks))
		return $hooks;
	else
		return false;
}

function member_only() {
	global $client;
}


function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function get_footer_pages() {
	$footer_pages = array();
	$res = sql_query("select id,link_name from `".tb()."footer_pages` order by weight");
	while ($row = sql_fetch_array($res)) {
		$footer_pages[] = url('footer_page/'.$row['id'],h($row['link_name']));
	}
	return $footer_pages;
}

function pending_review($post_id,$content,$uri='',$stream_id=0) {
	return 'verified';
	/*
	//disabled from v7.0
	*/
}

