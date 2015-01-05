<?php

function gifts_menu() {
	$items = array();
	$items['gifts'] = array(
		'name'=>'Gifts',
		'type'=>'community'
	);
	$items['gifts/'] = array(
		'name'=>'Send Gifts',
		'parent'=>'gifts',
		'type'=>'tab',
	);
	$items['gifts/mygifts'] = array(
		'name'=>'My Gifts',
		'parent'=>'gifts',
		'type'=>'tab',
	);
	$items['gifts/admin'] = array(
		'name'=>'Manage Gifts',
		'type'=>'admin',
		'protected'=>1

	);
	return $items;
}

function gifts_enabled(){
sql_query("

CREATE TABLE IF NOT EXISTS `jcow_gifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gift_image` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `gift_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM");

sql_query("

CREATE TABLE IF NOT EXISTS `jcow_sent_gifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `giftid` int(11) NOT NULL,
  `giftto` int(11) NOT NULL,
  `giftfrom` int(11) NOT NULL,
  `recieved` int(11) NOT NULL DEFAULT '0',
  `giftmsg` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM");

$sql = mysql_query("SELECT * FROM jcow_gifts");
if (mysql_num_rows($sql) == 0){
mysql_query("
INSERT INTO `jcow_gifts` (`id`, `gift_image`, `gift_name`) VALUES
(1, 'backpack.png', 'Backpack'),
(2, 'beatle.png', 'Beatle'),
(3, 'beerglass.png', 'Beer Glass'),
(4, 'blackburry.png', 'Blackburry'),
(5, 'blackheart.png', 'Black Heart'),
(6, 'bomb.png', 'Bomb'),
(7, 'bread.png', 'Bread'),
(8, 'breaddoll.png', 'Bread Doll'),
(9, 'butterfly.png', 'Butterfly'),
(10, 'candy.png', 'Candy'),
(11, 'catdoll.png', 'Cat Doll'),
(12, 'chocolatcake.png', 'Chocolate Cake'),
(13, 'crown.png', 'Crown'),
(14, 'cupcake.png', 'Cupcake'),
(15, 'donut.png', 'Donut'),
(16, 'flower.png', 'Flower'),
(17, 'footwear.png', 'Footwear'),
(18, 'giftbox4.png', 'Gift Box'),
(19, 'guitar.png', 'Guitar'),
(20, 'hamberger.png', 'Hamburger'),
(21, 'holywater.png', 'Holy Water'),
(22, 'japandoll.png', 'Japan Doll'),
(23, 'kokeshi.png', 'Kokeshi'),
(24, 'koreadoll.png', 'Korea Doll'),
(25, 'lcdtv.png', 'LCD TV'),
(26, 'lovesign.png', 'Love'),
(27, 'magichat.png', 'Magic Hat'),
(28, 'monster.png', 'Monster'),
(29, 'painheart.png', 'Heart in Pain'),
(30, 'panda.png', 'Panda'),
(31, 'paperfan.png', 'Paper Fan'),
(32, 'penguin.png', 'Penguin'),
(33, 'pokercard.png', 'Poker Cards'),
(34, 'rat.png', 'Rat'),
(35, 'rose.png', 'Rose'),
(36, 'ruby.png', 'Ruby'),
(37, 'sunny.png', 'Sunshine'),
(38, 'tent.png', 'Tent'),
(39, 'volkswagen.png', 'VW')");
}
$sql2 = mysql_query("SELECT giftmsg FROM jcow_sent_gifts");
if (!$sql2){
mysql_query("ALTER TABLE jcow_sent_gifts ADD giftmsg varchar(225) NOT NULL AFTER recieved");
}
}