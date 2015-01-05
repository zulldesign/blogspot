<?php
header("Cache-Control: no-cache");

// db
require_once './../../my/config.php';
require_once './../../includes/libs/common.inc.php';
require_once './../../includes/libs/db.inc.php';
$conn=sql_connect($db_info['host'], $db_info['user'], $db_info['pass'], $db_info['dbname']);

sql_query("
CREATE TABLE IF NOT EXISTS `".tb()."pending_review` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `content` text NOT NULL,
  `uri` varchar(255) NOT NULL,
  `post_id` varchar(50) NOT NULL,
  `created` int(11) NOT NULL,
  `ignored` tinyint(4) NOT NULL,
  `stream_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `post_id` (`post_id`),
  KEY `stream_id` (`stream_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8
");

sql_query("
CREATE TABLE IF NOT EXISTS `".tb()."limit_posting` (
  `uid` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `act` varchar(50) NOT NULL,
  KEY `uid` (`uid`,`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
");


sql_query("
CREATE TABLE IF NOT EXISTS `".tb()."mentions` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  `wall_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8
");

sql_query("update ".tb()."stories set app='images' where app='photos'");

$result = sql_query("SHOW COLUMNS FROM ".tb()."streams");
while ($row = sql_fetch_array($result)) {
	if ($row['Field'] == 'stuff_id') {
		$stuff_id = 'exists';
	}
}
if ($stuff_id != 'exists') {
	sql_query(" 
	ALTER TABLE ".tb()."streams ADD `stuff_id` INT NOT NULL ;
	");
	sql_query(" 
	ALTER TABLE ".tb()."streams ADD INDEX ( `stuff_id` ) ;
	");
}


echo 'Upgrading finished';

