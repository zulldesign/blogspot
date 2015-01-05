<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */
// boot
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
 
//
$time_start = microtime_float();

require_once './includes/boot.inc.php';

// load application
$app = new $parr[0]();
$app->name = $parr[0];
if (is_numeric($parr[1])) {
	$app->index($parr[1],$parr[2]);
}
else {
	if (!$parr[1]) {
		$app->index();
	}
	else {
		$app->$parr[1]($parr[2],$parr[3]);
	}
}

// end
stop_here();
?>