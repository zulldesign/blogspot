<?php
if (basename($_SERVER["SCRIPT_NAME"]) != 'index.php') die(basename($_SERVER["SCRIPT_NAME"]));
// check

if (!is_writable('includes/libs/ss.inc.php')) {
	c('You need to make the folder "includes/libs" and all files under this folder writable.');
}
else {
	if (!$key = get_gvar('jcow_securekey')) {
		$key = get_rand(32);
		set_gvar('jcow_securekey',$key);
	}
	$url = urlencode(str_replace('http://','',uhome()));
	$url = 'http://sp.jcow.net/update.php?url='.$url.'&v='.jversion().'&securekey='.$key;
	//verify
	$handle = fopen($url.'&act=verify', "rb");
	$contents = '';
	while (!feof($handle)) {
	  $contents .= fread($handle, 8192);
	}
	fclose($handle);
	if ($contents == 'verified') {
		c('<IFRAME SRC="'.$url.'&act=info" TITLE="Jcow Update" WIDTH="650" HEIGHT="380" scrolling="no">
		<a href="http://www.jcow.net">Update</a><br />
		</IFRAME>');
	}
	elseif ($contents == 'developing') {
		c('Jcow is moving the file server, please wait for a few days. Thanks.');
	}

	else {
		c('Failed to verify you Network.<br />
		<strong>Possible reasons:</strong>
		<ol><li>Your network is on Localhost.(Localhost can not get Updates) </li>
		<li>Jcow server is temporarily unreachable.</li>
		</ol>');
	}
	
	section_close('Online Update');
}
