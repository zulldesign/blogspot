-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2010 年 03 月 05 日 21:52
-- 服务器版本: 5.0.51
-- PHP 版本: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `osn`
--

-- --------------------------------------------------------

--
--  `jcow_accounts`
--

CREATE TABLE IF NOT EXISTS `jcow_accounts` (
  `id` int(11) NOT NULL auto_increment,
  `fbid` bigint(20) NOT NULL,
  `email` varchar(120) NOT NULL default '',
  `lastact` int(11) NOT NULL default '0',
  `created` int(11) NOT NULL default '0',
  `username` varchar(25) NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `level` tinyint(4) NOT NULL default '0',
  `points` int(11) NOT NULL,
  `avatar` varchar(50) NOT NULL,
  `signature` tinytext NOT NULL,
  `blurbs` text NOT NULL,
  `profile_permission` tinyint(4) NOT NULL default '0',
  `location` varchar(100) NOT NULL,
  `lastlogin` int(11) NOT NULL,
  `ipaddress` varchar(30) NOT NULL,
  `chpass` varchar(10) NOT NULL,
  `disabled` tinyint(4) NOT NULL,
  `intr` text NOT NULL,
  `gender` tinyint(4) NOT NULL,
  `about_me` text NOT NULL,
  `birthyear` int(4) NOT NULL,
  `birthmonth` tinyint(2) NOT NULL,
  `birthday` tinyint(2) NOT NULL,
  `hide_age` tinyint(1) NOT NULL,
  `reg_code` varchar(8) NOT NULL,
  `forum_posts` int(11) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `roles` varchar(255) NOT NULL,
  `country` varchar(50) NOT NULL,
  `locale` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `jcowsess` char(12) NOT NULL,
  `token` varchar(32) NOT NULL,
  `wall_id` int(11) NOT NULL,
  `followers` int(11) NOT NULL,
  `settings` text NOT NULL,
  `var1` varchar(255) NOT NULL,
  `var2` varchar(255) NOT NULL,
  `var3` varchar(255) NOT NULL,
  `var4` varchar(255) NOT NULL,
  `var5` varchar(255) NOT NULL,
  `var6` varchar(255) NOT NULL,
  `var7` varchar(255) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `hide_me` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `username` (`username`),
  KEY `lastlogin` (`lastlogin`),
  KEY `email` (`email`),
  KEY `fbid` (`fbid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
--  `jcow_blacks`
--

CREATE TABLE IF NOT EXISTS `jcow_blacks` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `bid` int(11) NOT NULL default '0',
  `bname` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`,`bid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
--  `jcow_cache`
--

CREATE TABLE IF NOT EXISTS `jcow_cache` (
  `ckey` varchar(50) collate utf8_unicode_ci NOT NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `expired` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
--  `jcow_comments`
--

CREATE TABLE IF NOT EXISTS `jcow_comments` (
  `id` int(11) NOT NULL auto_increment,
  `target_id` varchar(20) NOT NULL,
  `uid` int(11) NOT NULL,
  `message` text NOT NULL,
  `created` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `target_id` (`target_id`),
  KEY `stream_id` (`stream_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
--  `jcow_favorites`
--

CREATE TABLE IF NOT EXISTS `jcow_favorites` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `fuid` int(11) NOT NULL,
  `fapp` varchar(100) character set utf8 NOT NULL,
  `fsid` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `title` varchar(100) character set utf8 NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`,`fuid`,`fsid`,`created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
--  `jcow_followers`
--

CREATE TABLE IF NOT EXISTS `jcow_followers` (
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  KEY `uid` (`uid`,`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `jcow_forums`
--

CREATE TABLE IF NOT EXISTS `jcow_forums` (
  `id` int(11) NOT NULL auto_increment,
  `weight` int(11) NOT NULL default '0',
  `parent_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `type_pic` varchar(255) NOT NULL default '',
  `description` tinytext NOT NULL,
  `rules` text NOT NULL,
  `forum_type` varchar(50) NOT NULL default '0',
  `threads` int(11) NOT NULL default '0',
  `posts` int(11) NOT NULL default '0',
  `lastpostname` varchar(32) NOT NULL,
  `lastposttopicid` int(11) NOT NULL default '0',
  `lastposttopic` varchar(70) NOT NULL,
  `lastpostcreated` int(11) NOT NULL default '0',
  `moderator` varchar(255) NOT NULL default '',
  `settings` text NOT NULL,
  `fmembers` int(11) NOT NULL default '0',
  `image` varchar(250) NOT NULL,
  `read_roles` varchar(255) NOT NULL,
  `upload_roles` varchar(255) NOT NULL,
  `thread_roles` varchar(255) NOT NULL,
  `reply_roles` varchar(255) NOT NULL,
  `moderators` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `belong_id` (`parent_id`),
  KEY `order_num` (`weight`),
  KEY `type_class` (`forum_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 导出表中的数据 `jcow_forums`
--

INSERT INTO `jcow_forums` (`id`, `weight`, `parent_id`, `name`, `type_pic`, `description`, `rules`, `forum_type`, `threads`, `posts`, `lastpostname`, `lastposttopicid`, `lastposttopic`, `lastpostcreated`, `moderator`, `settings`, `fmembers`, `image`, `read_roles`, `upload_roles`, `thread_roles`, `reply_roles`, `moderators`) VALUES
(7, 1, 0, 'General Category', '', '', '', 'category', 0, 0, '', 0, '', 0, '', '', 0, '', '', '', '', '', ''),
(8, 1, 7, 'General Forum', '', 'This is a general forum', '', 'forum', 0, 0, '', 0, '', 0, '', '', 0, '', '1|2', '2', '2', '2', '');

-- --------------------------------------------------------

--
--  `jcow_forum_attachments`
--

CREATE TABLE IF NOT EXISTS `jcow_forum_attachments` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `uri` varchar(100) NOT NULL,
  `des` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `orginal_name` varchar(255) NOT NULL,
  `downloads` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
--  `jcow_forum_polls`
--

CREATE TABLE IF NOT EXISTS `jcow_forum_polls` (
  `id` int(11) NOT NULL auto_increment,
  `tid` int(11) NOT NULL default '0',
  `question` varchar(100) NOT NULL default '',
  `created` int(11) NOT NULL default '0',
  `options` text NOT NULL,
  `timeout` int(11) NOT NULL default '0',
  `options_per_user` tinyint(4) NOT NULL default '0',
  `voters` text NOT NULL,
  `total` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
--  `jcow_forum_posts`
--

CREATE TABLE IF NOT EXISTS `jcow_forum_posts` (
  `id` int(11) NOT NULL auto_increment,
  `tid` bigint(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created` int(10) NOT NULL default '0',
  `ip` varchar(30) NOT NULL default '',
  `is_first` tinyint(4) NOT NULL default '0',
  `attach` int(11) NOT NULL default '0',
  `bbcode_off` tinyint(4) NOT NULL default '0',
  `emote_off` tinyint(4) NOT NULL default '0',
  `got_attach` tinyint(4) NOT NULL,
  `stream_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `tid` (`tid`),
  KEY `author_id` (`uid`),
  KEY `stream_id` (`stream_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------

--
--  `jcow_forum_threads`
--

CREATE TABLE IF NOT EXISTS `jcow_forum_threads` (
  `id` int(11) NOT NULL auto_increment,
  `fid` int(11) NOT NULL default '0',
  `old_fid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `userid` int(11) NOT NULL default '0',
  `username` varchar(50) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `views` int(11) NOT NULL default '0',
  `posts` int(11) NOT NULL default '0',
  `closed` smallint(1) NOT NULL default '0',
  `created` int(11) NOT NULL default '0',
  `lastpostusername` varchar(255) NOT NULL default '0',
  `lastpostcreated` int(10) NOT NULL default '0',
  `icon` tinyint(4) NOT NULL default '0',
  `thread_type` tinyint(1) NOT NULL default '0',
  `thread_lock` tinyint(1) NOT NULL default '0',
  `got_poll` tinyint(11) NOT NULL default '0',
  `got_attach` tinyint(4) NOT NULL,
  `stressed` tinyint(4) NOT NULL default '0',
  `digg` int(11) NOT NULL default '0',
  `dugg` int(11) NOT NULL default '0',
  `votes` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `thread_type` (`thread_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
--  `jcow_friends`
--

CREATE TABLE IF NOT EXISTS `jcow_friends` (
  `uid` int(11) NOT NULL default '0',
  `fid` int(11) NOT NULL default '0',
  `created` int(11) NOT NULL default '0',
  KEY `uid` (`uid`,`fid`),
  KEY `fid` (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `jcow_friend_reqs`
--

CREATE TABLE IF NOT EXISTS `jcow_friend_reqs` (
  `uid` int(11) NOT NULL default '0',
  `fid` int(11) NOT NULL default '0',
  `created` int(11) NOT NULL default '0',
  `msg` varchar(200)  NOT NULL,
  KEY `uid` (`uid`,`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `jcow_groups`
--

CREATE TABLE IF NOT EXISTS `jcow_groups` (
  `id` int(11) NOT NULL auto_increment,
  `uri` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slogan` varchar(200) NOT NULL,
  `creatorid` int(11) NOT NULL,
  `creator` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `members` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `private` tinyint(4) NOT NULL,
  `needapproval` tinyint(4) NOT NULL,
  `posts` int(11) NOT NULL,
  `topics` int(11) NOT NULL,
  `lastptime` int(11) NOT NULL,
  `lastpname` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `custom_css` text NOT NULL,
  `style_ids` varchar(50) NOT NULL,
  `category` char(2) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `creatorid` (`creatorid`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
--  `jcow_group_categories`
--

CREATE TABLE IF NOT EXISTS `jcow_group_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `groups` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
--  `jcow_group_members`
--

CREATE TABLE IF NOT EXISTS `jcow_group_members` (
  `gid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `about_me` text NOT NULL,
  `hide_profile` tinyint(1) NOT NULL,
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `jcow_group_members_pending`
--

CREATE TABLE IF NOT EXISTS `jcow_group_members_pending` (
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `ignored` tinyint(4) NOT NULL,
  KEY `uid` (`uid`,`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `jcow_group_polls`
--

CREATE TABLE IF NOT EXISTS `jcow_group_polls` (
  `id` int(11) NOT NULL auto_increment,
  `tid` int(11) NOT NULL default '0',
  `question` varchar(100) NOT NULL default '',
  `created` int(11) NOT NULL default '0',
  `options` text NOT NULL,
  `timeout` int(11) NOT NULL default '0',
  `options_per_user` tinyint(4) NOT NULL default '0',
  `voters` text NOT NULL,
  `total` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
--  `jcow_group_postcats`
--

CREATE TABLE IF NOT EXISTS `jcow_group_postcats` (
  `id` int(11) NOT NULL auto_increment,
  `gid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
--  `jcow_group_posts`
--

CREATE TABLE IF NOT EXISTS `jcow_group_posts` (
  `id` int(11) NOT NULL auto_increment,
  `gid` int(11) NOT NULL,
  `tid` bigint(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `username` varchar(50) NOT NULL,
  `rtid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `rname` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created` int(10) NOT NULL default '0',
  `ip` varchar(30) NOT NULL default '',
  `attach` int(11) NOT NULL default '0',
  `bbcode_off` tinyint(4) NOT NULL default '0',
  `emote_off` tinyint(4) NOT NULL default '0',
  `got_attach` tinyint(4) NOT NULL,
  `topic` varchar(100) NOT NULL,
  `is_first` tinyint(4) NOT NULL,
  `replies` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `tid` (`tid`),
  KEY `uid` (`uid`),
  KEY `gid` (`gid`),
  KEY `rtid` (`rtid`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

--
--  `jcow_group_topics`
--

CREATE TABLE IF NOT EXISTS `jcow_group_topics` (
  `id` int(11) NOT NULL auto_increment,
  `gid` int(11) NOT NULL default '0',
  `old_fid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL default '0',
  `username` varchar(50) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `views` int(11) NOT NULL default '0',
  `posts` int(11) NOT NULL default '0',
  `closed` smallint(1) NOT NULL default '0',
  `created` int(11) NOT NULL default '0',
  `lastpostusername` varchar(255) NOT NULL default '0',
  `lastpostcreated` int(11) NOT NULL default '0',
  `icon` tinyint(4) NOT NULL default '0',
  `thread_type` tinyint(1) NOT NULL default '0',
  `thread_lock` tinyint(1) NOT NULL default '0',
  `got_poll` tinyint(11) NOT NULL default '0',
  `got_attach` tinyint(4) NOT NULL,
  `stressed` tinyint(4) NOT NULL default '0',
  `digg` int(11) NOT NULL default '0',
  `dugg` int(11) NOT NULL default '0',
  `votes` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
--  `jcow_gvars`
--

CREATE TABLE IF NOT EXISTS `jcow_gvars` (
  `gkey` varchar(50) NOT NULL,
  `gvalue` text NOT NULL,
  KEY `gkey` (`gkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 导出表中的数据 `jcow_gvars`
--

INSERT INTO `jcow_gvars` (`gkey`, `gvalue`) VALUES
('theme_folder', 'themes/default'),
('cf_var1', 'disabled'),
('cf_var2', 'disabled'),
('cf_var3', 'disabled'),
('cf_var4', 'disabled'),
('cf_var5', 'disabled'),
('cf_var6', 'disabled'),
('cf_var7', 'disabled'),
('jcow_version', '4.0'),
('app_music_disable', '0'),
('story_access', 'all'),
('profile_access', 'all'),
('site_slogan', 'This is a Social Network'),
('ad_block_content_top', ''),
('ad_block_content_bottom', ''),
('site_name', 'My Jcow Network'),
('site_email', 'name@domain.com'),
('block_top', ''),
('block_bottom', ''),
('only_invited', '0'),
('session_lived', '1267784005'),
('permission_etheme', '2'),
('permission_atheme', '2|11'),
('private_network', '0'),
('theme_tpl', 'default'),
('theme_css', '1.css'),
('hide_ad_roles', '3'),
('acc_verify', '0'),
('permission_upload', '2'),
('permission_comment', '2'),
('permission_add', '2'),
('permission_browse', '1|2'),
('permission_feed', '1|2'),
('theme_block_adsbar', 'Go to "Admin CP" - "Themes" - "Manage Blocks" to edit this message.'),
('limit_posting_num', '5'),
('app_music', '0');

-- --------------------------------------------------------

--
--  `jcow_invites`
--

CREATE TABLE IF NOT EXISTS `jcow_invites` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
--  `jcow_langs`
--

CREATE TABLE IF NOT EXISTS `jcow_langs` (
  `lang_from` varchar(255) NOT NULL default '',
  `lang_to` text NOT NULL,
  `lang` varchar(20) NOT NULL default '',
  KEY `lang_from` (`lang_from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `jcow_messages`
--

CREATE TABLE IF NOT EXISTS `jcow_messages` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(100) NOT NULL default '',
  `message` text NOT NULL,
  `from_id` int(11) NOT NULL default '0',
  `to_id` int(11) NOT NULL default '0',
  `created` int(11) NOT NULL default '0',
  `hasread` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `from_id` (`from_id`,`to_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;


-- --------------------------------------------------------

--
--  `jcow_profiles`
--

CREATE TABLE IF NOT EXISTS `jcow_profiles` (
  `id` int(11) NOT NULL,
  `style_ids` varchar(255) NOT NULL,
  `custom_css` text NOT NULL,
  `background` varchar(100) NOT NULL,
  `videoid` int(11) NOT NULL,
  `favorites` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `jcow_profile_comments`
--

CREATE TABLE IF NOT EXISTS `jcow_profile_comments` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `stream_id` (`stream_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
--  `jcow_roles`
--
CREATE TABLE IF NOT EXISTS `jcow_roles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) character set utf8 NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 导出表中的数据 `jocow_roles`
--

INSERT INTO `jcow_roles` (`id`, `name`) VALUES
(1, 'Guest'),
(2, 'General member'),
(3, 'Administrator');


-- --------------------------------------------------------

--
--  `jcow_stories`
--

CREATE TABLE IF NOT EXISTS `jcow_stories` (
  `id` int(11) NOT NULL auto_increment,
  `cid` int(11) NOT NULL default '0',
  `sticky` tinyint(4) NOT NULL,
  `closed` tinyint(4) NOT NULL,
  `title` varchar(120) NOT NULL default '',
  `thumbnail` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `uid` int(11) NOT NULL default '0',
  `created` int(11) NOT NULL default '0',
  `lastreply` int(11) NOT NULL default '0',
  `lastreplyuname` varchar(50) NOT NULL,
  `lastreplyuid` int(11) NOT NULL,
  `updated` int(11) NOT NULL default '0',
  `views` int(11) NOT NULL,
  `comments` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  `app` varchar(50) NOT NULL default '',
  `digg` int(11) NOT NULL,
  `dugg` int(11) NOT NULL,
  `photos` int(11) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `featured` tinyint(4) NOT NULL,
  `var1` varchar(255) NOT NULL default '',
  `var2` varchar(255) NOT NULL default '',
  `var3` varchar(255) NOT NULL default '',
  `var4` varchar(255) NOT NULL default '',
  `var5` varchar(255) NOT NULL default '',
  `text1` text NOT NULL,
  `text2` text NOT NULL,
  `blob1` blob NOT NULL,
  `rating` text NOT NULL,
  `page_id` int(11) NOT NULL,
  `page_type` VARCHAR( 25 ) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `app` (`app`),
  KEY `uid` (`uid`),
  KEY `page_id` (`page_id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=79 ;

-- --------------------------------------------------------

--
--  `jcow_story_categories`
--

CREATE TABLE IF NOT EXISTS `jcow_story_categories` (
  `id` int(11) NOT NULL auto_increment,
  `gid` int(11) NOT NULL,
  `name` varchar(150) NOT NULL default '',
  `description` text NOT NULL,
  `weight` int(11) NOT NULL default '0',
  `app` varchar(50) NOT NULL default '',
  `var1` varchar(255) NOT NULL,
  `var2` varchar(255) NOT NULL,
  `var3` varchar(255) NOT NULL,
  `var4` varchar(255) NOT NULL,
  `var5` varchar(255) NOT NULL,
  `uri` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `app` (`app`),
  KEY `weight` (`weight`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
--  `jcow_story_cat_groups`
--

CREATE TABLE IF NOT EXISTS `jcow_story_cat_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `app` varchar(50) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
--  `jcow_story_photos`
--

CREATE TABLE IF NOT EXISTS `jcow_story_photos` (
  `id` int(11) NOT NULL auto_increment,
  `sid` int(11) NOT NULL,
  `uri` varchar(100) NOT NULL,
  `des` varchar(255) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

-- --------------------------------------------------------

--
--  `jcow_streams`
--

CREATE TABLE IF NOT EXISTS `jcow_streams` (
  `id` int(11) NOT NULL auto_increment,
  `message` text NOT NULL,
  `wall_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `attachment` text NOT NULL,
  `created` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `app` varchar(20) NOT NULL,
  `aid` int(11) NOT NULL,
  `hide` tinyint(1) NOT NULL,
  `likes` int(11) NOT NULL,
  `dislikes` int(11) NOT NULL,
  `stuff_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `app` (`app`),
  KEY `aid` (`aid`),
  KEY `stuff_id` (`stuff_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `jcow_disliked` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`,`stream_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
--  `jcow_tags`
--

CREATE TABLE IF NOT EXISTS `jcow_tags` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `app` varchar(25) NOT NULL,
  `num` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
--  `jcow_tag_ids`
--

CREATE TABLE IF NOT EXISTS `jcow_tag_ids` (
  `tid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  KEY `tid` (`tid`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `jcow_texts`
--


CREATE TABLE IF NOT EXISTS `jcow_texts` (
  `tkey` varchar(50) NOT NULL,
  `tvalue` text NOT NULL,
  KEY `tkey` (`tkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 导出表中的数据 `jcow_texts`
--

INSERT INTO `jcow_texts` (`tkey`, `tvalue`) VALUES
('welcome_pm', 'Hello %username%!\r\nThank you for your registeration!\r\nPlease invite your friends to join our community.'),
('welcome_email', 'Dear %username%,\r\nWelcome to %sitelink%!\r\nYour login email is: %email%\r\nOur URL is:\r\n%sitelink%'),
('welcome_msg', 'Welcome to our Community!'),
('rules_conditions', 'none'),
('footermsg', 'Your footer here..'),
('locations', 'Afghanistan  \r\nAlbania  \r\nAlgeria  \r\nAmerican Samoa  \r\nAndorra  \r\nAngola  \r\nAnguilla  \r\nAntarctica  \r\nAntigua and Barbuda  \r\nArgentina  \r\nArmenia  \r\nAruba  \r\nAustralia  \r\nAustria  \r\nAzerbaidjan  \r\nBahamas  \r\nBahrain  \r\nBangladesh  \r\nBarbados  \r\nBelarus  \r\nBelgium  \r\nBelize  \r\nBenin  \r\nBermuda  \r\nBhutan  \r\nBolivia  \r\nBosnia-Herzegovina  \r\nBotswana  \r\nBouvet Island  \r\nBrazil  \r\nBrunei Darussalam  \r\nBulgaria  \r\nBurkina Faso  \r\nBurundi  \r\nCambodia  \r\nCameroon  \r\nCanada  \r\nCape Verde  \r\nCayman Islands  \r\nCentral African Republic  \r\nChad  \r\nChile  \r\nChina  \r\nChristmas Island  \r\nCocos Islands  \r\nColombia  \r\nComoros  \r\nCongo  \r\nCook Islands  \r\nCosta Rica  \r\nCroatia  \r\nCuba  \r\nCyprus  \r\nCzech Republic  \r\nDenmark  \r\nDjibouti  \r\nDominica  \r\nDominican Republic  \r\nEast Timor  \r\nEcuador  \r\nEgypt  \r\nEl Salvador  \r\nEquatorial Guinea  \r\nEstonia  \r\nEthiopia  \r\nFalkland Islands  \r\nFaroe Islands  \r\nFiji  \r\nFinland  \r\nFormer Czechoslovakia  \r\nFrance  \r\nFrench Guyana  \r\nGabon  \r\nGambia  \r\nGeorgia  \r\nGermany  \r\nGhana  \r\nGibraltar  \r\nGreat Britain  \r\nGreece  \r\nGreenland  \r\nGrenada  \r\nGuadeloupe  \r\nGuam  \r\nGuatemala  \r\nGuinea  \r\nGuinea Bissau  \r\nGuyana  \r\nHaiti  \r\nHonduras  \r\nHong Kong  \r\nHungary  \r\nIceland  \r\nIndia  \r\nIndonesia  \r\nIran  \r\nIraq  \r\nIreland  \r\nIsrael  \r\nItaly  \r\nIvory Coast  \r\nJamaica  \r\nJapan  \r\nJordan  \r\nKazakhstan  \r\nKenya  \r\nKiribati  \r\nKuwait  \r\nKyrgyzstan  \r\nLaos  \r\nLatvia  \r\nLebanon  \r\nLesotho  \r\nLiberia  \r\nLibya  \r\nLiechtenstein  \r\nLithuania  \r\nLuxembourg  \r\nMacau  \r\nMacedonia  \r\nMadagascar  \r\nMalawi  \r\nMalaysia  \r\nMaldives  \r\nMali  \r\nMalta  \r\nMarshall Islands  \r\nMartinique  \r\nMauritania  \r\nMauritius  \r\nMayotte  \r\nMexico  \r\nMicronesia  \r\nMoldavia  \r\nMonaco  \r\nMongolia  \r\nMontserrat  \r\nMorocco  \r\nMozambique  \r\nMyanmar  \r\nNamibia  \r\nNauru  \r\nNepal  \r\nNetherlands  \r\nNetherlands Antilles  \r\nNeutral Zone  \r\nNew Caledonia  \r\nNew Zealand  \r\nNicaragua  \r\nNiger  \r\nNigeria  \r\nNiue  \r\nNorfolk Island  \r\nNorth Korea  \r\nNorway  \r\nOman  \r\nPakistan  \r\nPalau  \r\nPanama  \r\nPapua New Guinea  \r\nParaguay  \r\nPeru  \r\nPhilippines  \r\nPitcairn Island  \r\nPoland  \r\nPolynesia  \r\nPortugal  \r\nPuerto Rico  \r\nQatar  \r\nReunion  \r\nRomania  \r\nRussian Federation  \r\nRwanda  \r\nSaint Helena  \r\nSaint Lucia  \r\nSaint Vincent and Grenadines  \r\nSamoa  \r\nSan Marino  \r\nSaudi Arabia  \r\nSenegal  \r\nSeychelles  \r\nSierra Leone  \r\nSingapore  \r\nSlovak Republic  \r\nSlovenia  \r\nSolomon Islands  \r\nSomalia  \r\nSouth Africa  \r\nSouth Korea  \r\nSpain  \r\nSri Lanka  \r\nSudan  \r\nSuriname  \r\nSwaziland  \r\nSweden  \r\nSwitzerland  \r\nSyria  \r\nTadjikistan  \r\nTaiwan  \r\nTanzania  \r\nThailand  \r\nTogo  \r\nTokelau  \r\nTonga  \r\nTrinidad and Tobago  \r\nTunisia  \r\nTurkey  \r\nTurkmenistan  \r\nTuvalu  \r\nUganda  \r\nUkraine  \r\nUnited Arab Emirates  \r\nUnited Kingdom  \r\nUnited States  \r\nUruguay  \r\nUzbekistan  \r\nVanuatu  \r\nVatican City State  \r\nVenezuela  \r\nVietnam  \r\nVirgin Islands (British)  \r\nVirgin Islands (USA)  \r\nWallis and Futuna Islands  \r\nWestern Sahara  \r\nYemen  \r\nYugoslavia  \r\nZaire  \r\nZambia  \r\nZimbabwe');

-- --------------------------------------------------------

--
--  `jcow_tmp`
--

CREATE TABLE IF NOT EXISTS `jcow_tmp` (
  `tkey` varchar(70) NOT NULL,
  `tcontent` text NOT NULL,
  KEY `tkey` (`tkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `jcow_var_cache`
--

CREATE TABLE IF NOT EXISTS `jcow_var_cache` (
  `name` varchar(60) NOT NULL,
  `content` varchar(255) NOT NULL,
  `created` int(11) NOT NULL,
  KEY `name` (`name`,`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `jcow_votes`
--

CREATE TABLE IF NOT EXISTS `jcow_votes` (
  `sid` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  KEY `sid` (`sid`,`uid`),
  KEY `created` (`created`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `jcow_forum_subscribes` (
  `uid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  KEY `uid` (`uid`,`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jcow_chatrooms` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `content` text character set utf8 NOT NULL,
  `updated` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`,`fid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `jcow_reports` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `hasread` tinyint(1) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `jcow_reports` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `hasread` tinyint(1) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `jcow_messages_sent` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(100) NOT NULL default '',
  `message` text NOT NULL,
  `from_id` int(11) NOT NULL default '0',
  `to_id` int(11) NOT NULL default '0',
  `created` int(11) NOT NULL default '0',
  `hasread` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `from_id` (`from_id`,`to_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `jcow_liked` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `stream_id` (`stream_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `jcow_subscr` (
  `id` varchar(32) NOT NULL,
  `item_number` varchar(32) NOT NULL,
  `status` varchar(25) NOT NULL,
  `uid` int(11) NOT NULL,
  `timeline` int(11) NOT NULL,
  KEY `id` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2010 年 07 月 16 日 19:55
-- 服务器版本: 5.0.51
-- PHP 版本: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `jcow3`
--

-- --------------------------------------------------------

--
--  `jcow_menu`
--

CREATE TABLE IF NOT EXISTS `jcow_menu` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `tab_name` varchar(50) NOT NULL,
  `weight` int(11) NOT NULL default '0',
  `path` varchar(255) NOT NULL default '',
  `app` varchar(50) NOT NULL default '',
  `actived` tinyint(1) NOT NULL default '0',
  `type` varchar(25) NOT NULL,
  `protected` tinyint(1) NOT NULL,
  `allowed_roles` text NOT NULL,
  `icon` varchar(255) NOT NULL,
  `parent` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

--
-- 导出表中的数据 `jcow_menu`
--

INSERT INTO `jcow_menu` (`id`, `name`, `tab_name`, `weight`, `path`, `app`, `actived`, `type`, `protected`, `allowed_roles`, `icon`, `parent`) VALUES
(1, 'Blogs', 'Community', 4, 'blogs', 'blogs', 1, 'app', 0, '', '', ''),
(110, '@Mentions', '', 48, 'feed/mentions', 'feed', 1, 'tab', 0, '', '', 'feed'),
(111, 'Likes', '', 49, 'feed/likes', 'feed', 1, 'tab', 0, '', '', 'feed'),
(13, 'Browse', '', 1, 'browse', 'browse', 1, 'community', 1, '1', '', ''),
(14, 'Home', 'Following', 0, 'feed', 'feed', 1, 'app', 0, '1', '', ''),
(113, 'Following', '', 51, 'images/following', 'images', 1, 'tab', 0, '', '', 'images'),
(18, 'My account', 'My information', 20, 'account', 'account', 1, 'personal', 0, '', '', ''),
(19, 'Avatar', '', 34, 'account/avatar', 'account', 1, 'tab', 0, '', '', 'account'),
(114, 'Following', '', 52, 'videos/following', 'videos', 1, 'tab', 0, '', '', 'videos'),
(21, 'Privacy', '', 36, 'account/privacy', 'account', 1, 'tab', 0, '', '', 'account'),
(22, 'Password', '', 37, 'account/cpassword', 'account', 1, 'tab', 0, '', '', 'account'),
(23, 'Invite', 'Invite', 20, 'invite', 'invite', 1, 'personal', 0, '', '', ''),
(24, 'Histories', 'Following', 40, 'invite/histories', 'invite', 1, 'tab', 0, '', '', 'invite'),
(106, 'Images', 'Community', 44, 'images', 'images', 1, 'app', 0, '', '', ''),
(108, 'Videos', 'Community', 46, 'videos', 'videos', 1, 'app', 0, '', '', ''),
(112, 'Following', '', 50, 'blogs/following', 'blogs', 1, 'tab', 0, '', '', 'blogs'),
(115, 'Events', 'Community', 53, 'events', 'events', 1, 'app', 0, '', '', ''),
(116, 'Following', '', 54, 'events/following', 'events', 1, 'tab', 0, '', '', 'events'),
(130, 'Facebook connection settings', '', 68, 'fblogin/admin', 'fblogin', 1, 'admin', 1, '', '', ''),
(131, 'Community', '', 69, 'feed/all', 'feed', 1, 'tab', 0, '', '', 'feed');



CREATE TABLE IF NOT EXISTS `jcow_modules` (
  `name` varchar(50) NOT NULL default '',
  `actived` tinyint(1) NOT NULL default '0',
  `hooking` tinyint(4) NOT NULL default '0',
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
--

INSERT INTO `jcow_modules` (`name`, `actived`, `hooking`) VALUES
('blogs', 1, 1),
('browse', 1, 0),
('feed', 1, 0),
('dashboard', 0, 1),
('account', 1, 0),
('admin', 1, 1),
('u', 1, 0),
('member', 1, 0),
('follow', 1, 0),
('friends', 1, 0),
('jquery', 1, 0),
('language', 1, 0),
('message', 1, 0),
('notifications', 1, 0),
('preference', 1, 0),
('report', 1, 0),
('rss', 1, 0),
('search', 1, 0),
('invite', 1, 0),
('apps', 1, 0),
('blacklist', 1, 0),
('images', 1, 1),
('update', 1, 0),
('videos', 1, 1),
('cache', 0, 1),
('events', 1, 1),
('questions', 1, 1),
('fblogin', 1, 0);




CREATE TABLE IF NOT EXISTS `jcow_banned` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL,
  `ip1` varchar(3) NOT NULL,
  `ip2` varchar(3) NOT NULL,
  `ip3` varchar(3) NOT NULL,
  `ip4` varchar(3) NOT NULL,
  `created` int(11) NOT NULL,
  `expired` int(11) NOT NULL,
  `operator` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
--

CREATE TABLE IF NOT EXISTS `jcow_pages` (
  `id` int(11) NOT NULL auto_increment,
  `uri` varchar(30) NOT NULL,
  `uid` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `style_ids` text NOT NULL,
  `custom_css` text NOT NULL,
  `background` varchar(100) NOT NULL,
  `type` varchar(25) NOT NULL,
  `description` text NOT NULL,
  `users` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
--

CREATE TABLE IF NOT EXISTS `jcow_page_users` (
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  KEY `pid` (`pid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--

CREATE TABLE IF NOT EXISTS `jcow_user_crafts` (
  `uid` int(11) NOT NULL,
  `hash` varchar(5) NOT NULL,
  `created` int(11) NOT NULL,
  KEY `uid` (`uid`,`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `jcow_disliked` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`,`stream_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `jcow_footer_pages` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `link_name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


INSERT INTO `jcow_footer_pages` (`id`, `name`, `link_name`, `content`, `weight`) VALUES
(1, 'About Us', 'About Us', 'You(admin) can edit this page from "Admin CP"-"Footer Pages".', 1),
(2, 'Contact Us', 'Contact Us', 'You(admin) can edit this page from "Admin CP"-"Footer Pages".', 2);


CREATE TABLE IF NOT EXISTS `jcow_pending_review` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `jcow_limit_posting` (
  `uid` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `act` varchar(50) NOT NULL,
  KEY `uid` (`uid`,`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jcow_mentions` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  `wall_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;