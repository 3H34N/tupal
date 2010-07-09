<?php
$sql_drop_tbls = array(
	"sort",	"article_db",	"article",	"reply",	"article_content_100",
	"article_content_101",	"article_content_102",	"article_content_103",	"article_content_104",	"article_content_105",
	"article_module",	"members",	"memberdata",	"memberdata_1",	"group",
	"menu",	"admin_menu",	"module",	"fu_article",	"fu_sort",
	"special",	"special_comment",	"spsort",	"alonepage",	"channel",
	"collection",	"comment",	"config",	"copyfrom",	"hack",
	"label",	"form_content",	"form_content_1",	"form_content_2",	"form_content_3",
	"form_content_4",	"form_content_5",	"form_content_6",	"form_content_7",	"form_content_8",
	"form_module",	"form_reply",	"friendlink",	"friendlink_sort",	"gather_rule",
	"gather_sort",	"pm",	"guestbook",	"keyword",	"keywordid",
	"limitword",	"ad",	"ad_user",	"sellad",	"sellad_user",
	"shoporderproduct",	"shoporderuser",	"upfile",	"vote",	"vote_comment",
	"vote_config",	"area",	"jfabout",	"jfsort",	"moneycard",
	"olpay",	"propagandize",	"report",	"template",	"template_bak",
	"count_site",	"count_stat",	"count_user",	"exam_form",	"exam_form_element",
	"exam_sort",	"exam_student",	"exam_student_title",	"exam_title"
);

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}sort` (
  fid int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fup` int(10) unsigned NOT NULL default '0',
  `fmid` int(10) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `class` smallint(4) NOT NULL default '0',
  `sons` smallint(4) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `admin` varchar(100) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  `listorder` tinyint(2) NOT NULL default '0',
  `passwd` varchar(32) NOT NULL default '',
  `logo` varchar(150) NOT NULL default '',
  `descrip` text NOT NULL,
  `style` varchar(50) NOT NULL default '',
  `template` text NOT NULL,
  `jumpurl` varchar(150) NOT NULL default '',
  `maxperpage` tinyint(3) NOT NULL default '0',
  `metakeywords` varchar(255) NOT NULL default '',
  `metadescription` varchar(255) NOT NULL default '',
  `allowcomment` tinyint(1) NOT NULL default '0',
  `allowpost` varchar(150) NOT NULL default '',
  `allowviewtitle` varchar(150) NOT NULL default '',
  `allowviewcontent` varchar(150) NOT NULL default '',
  `allowdownload` varchar(150) NOT NULL default '',
  `forbidshow` tinyint(1) NOT NULL default '0',
  `config` text NOT NULL,
  `list_html` varchar(255) NOT NULL default '',
  `bencandy_html` varchar(255) NOT NULL default '',
  `domain` varchar(150) NOT NULL default '',
  `domain_dir` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`fid`),
  KEY `fup` (`fup`),
  KEY `fmid` (`fmid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}article_db` (
  `aid` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY  (`aid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}article` (
  `aid` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL default '',
  `smalltitle` varchar(100) NOT NULL default '',
  `fid` mediumint(7) unsigned NOT NULL default '0',
  `mid` mediumint(5) NOT NULL default '0',
  `fname` varchar(50) NOT NULL default '',
  `special_id` mediumint(7) NOT NULL default '0',
  `bak_id` mediumint(7) NOT NULL default '0',
  `info` tinyint(2) NOT NULL default '0',
  `hits` mediumint(7) NOT NULL default '0',
  `pages` smallint(4) NOT NULL default '0',
  `comments` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `list` int(10) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `author` varchar(30) NOT NULL default '',
  `copyfrom` varchar(100) NOT NULL default '',
  `copyfromurl` varchar(150) NOT NULL default '',
  `titlecolor` varchar(15) NOT NULL default '',
  `fonttype` tinyint(1) NOT NULL default '0',
  `titleicon` smallint(3) NOT NULL default '0',
  `picurl` varchar(150) NOT NULL default '0',
  `ispic` tinyint(1) NOT NULL default '0',
  `yz` tinyint(1) NOT NULL default '0',
  `yzer` varchar(30) NOT NULL default '',
  `yztime` int(10) NOT NULL default '0',
  `levels` tinyint(2) NOT NULL default '0',
  `levelstime` int(10) NOT NULL default '0',
  `keywords` varchar(100) NOT NULL default '',
  `jumpurl` varchar(150) NOT NULL default '',
  `iframeurl` varchar(150) NOT NULL default '',
  `style` varchar(15) NOT NULL default '',
  `template` varchar(255) NOT NULL default '',
  `target` tinyint(1) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `lastfid` mediumint(7) NOT NULL default '0',
  `money` mediumint(7) NOT NULL default '0',
  `buyuser` text NOT NULL,
  `passwd` varchar(32) NOT NULL default '',
  `allowdown` varchar(150) NOT NULL default '',
  `allowview` varchar(150) NOT NULL default '',
  `editer` varchar(30) NOT NULL default '',
  `edittime` int(10) NOT NULL default '0',
  `begintime` int(10) NOT NULL default '0',
  `endtime` int(10) NOT NULL default '0',
  `description` text NOT NULL,
  `lastview` int(10) NOT NULL default '0',
  `digg_num` mediumint(7) NOT NULL default '0',
  `digg_time` int(10) NOT NULL default '0',
  `forbidcomment` tinyint(1) NOT NULL default '0',
  `ifvote` tinyint(1) NOT NULL default '0',
  `heart` varchar(255) NOT NULL default '',
  `htmlname` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`aid`),
  KEY `fid` (`fid`),
  KEY `hits` (`hits`,`yz`,`fid`,`ispic`),
  KEY `lastview` (`yz`,`lastview`,`fid`,`ispic`),
  KEY `list` (`list`,`yz`,`fid`,`ispic`),
  KEY `ispic` (`ispic`),
  KEY `uid` (`uid`),
  KEY `levels` (`levels`),
  KEY `digg_num` (`digg_num`),
  KEY `digg_time` (`digg_time`),
  KEY `mid` (`mid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}reply` (
  `rid` mediumint(7) NOT NULL AUTO_INCREMENT,
  `subhead` varchar(150) NOT NULL default '',
  `postdate` int(10) NOT NULL default '0',
  `aid` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `topic` tinyint(1) NOT NULL default '0',
  `ishtml` tinyint(1) NOT NULL default '1',
  `download` text NOT NULL,
  `content` mediumtext NOT NULL,
  `orderid` mediumint(7) NOT NULL default '0',
  PRIMARY KEY  (`rid`),
  KEY `aid` (`aid`),
  KEY `topic` (`topic`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}article_content_100` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `aid` mediumint(7) NOT NULL default '0',
  `rid` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `photourl` mediumtext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `uid` (`uid`),
  KEY `aid` (`aid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}article_content_101` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `aid` mediumint(7) NOT NULL default '0',
  `rid` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `day_hits` mediumint(7) NOT NULL default '0',
  `week_hits` mediumint(7) NOT NULL default '0',
  `month_hits` mediumint(7) NOT NULL default '0',
  `total_hits` mediumint(7) NOT NULL default '0',
  `hits_time` int(10) NOT NULL default '0',
  `hits_user` text NOT NULL,
  `my_author` varchar(30) NOT NULL default '',
  `my_copyfromurl` varchar(150) NOT NULL default '',
  `my_demo` varchar(150) NOT NULL default '',
  `operatingsystem` varchar(150) NOT NULL default '',
  `softlanguage` varchar(30) NOT NULL default '',
  `copyright` varchar(30) NOT NULL default '',
  `softsize` varchar(20) NOT NULL default '',
  `softurl` mediumtext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `uid` (`uid`),
  KEY `aid` (`aid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}article_content_102` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `aid` mediumint(7) NOT NULL default '0',
  `rid` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `day_hits` mediumint(7) NOT NULL default '0',
  `week_hits` mediumint(7) NOT NULL default '0',
  `month_hits` mediumint(7) NOT NULL default '0',
  `total_hits` mediumint(7) NOT NULL default '0',
  `hits_time` int(10) NOT NULL default '0',
  `hits_user` text NOT NULL,
  `mvurl` mediumtext NOT NULL,
  `my_role` varchar(100) NOT NULL default '',
  `my_lang` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `uid` (`uid`),
  KEY `aid` (`aid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}article_content_103` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `aid` mediumint(7) NOT NULL default '0',
  `rid` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `shoptype` varchar(50) NOT NULL default '',
  `martprice` varchar(15) NOT NULL default '',
  `nowprice` varchar(20) NOT NULL default '',
  `shop_id` varchar(30) NOT NULL default '',
  `shopmoney` int(7) NOT NULL default '0',
  `shopnum` varchar(5) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `uid` (`uid`),
  KEY `aid` (`aid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}article_content_104` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `aid` mediumint(7) NOT NULL default '0',
  `rid` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `flashurl` varchar(150) NOT NULL default '',
  `flashauthor` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `uid` (`uid`),
  KEY `aid` (`aid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}article_content_105` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `aid` mediumint(7) NOT NULL default '0',
  `rid` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `my_type` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `uid` (`uid`),
  KEY `aid` (`aid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}article_module` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL default '',
  `alias` varchar(30) NOT NULL default '',
  `list` smallint(4) NOT NULL default '0',
  `allowpost` varchar(255) NOT NULL default '',
  `style` varchar(30) NOT NULL default '',
  `template` varchar(255) NOT NULL default '',
  `config` mediumtext NOT NULL,
  `keywords` varchar(30) NOT NULL default '',
  `ifclose` tinyint(1) NOT NULL default '0',
  `iftable` mediumint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}members` (
  `uid` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`uid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}memberdata` (
  `uid` mediumint(7) unsigned NOT NULL default '0',
  `username` varchar(50) NOT NULL default '',
  `question` varchar(32) NOT NULL default '',
  `groupid` smallint(4) NOT NULL default '0',
  `grouptype` tinyint(1) NOT NULL default '0',
  `groups` varchar(255) NOT NULL default '',
  `yz` tinyint(1) NOT NULL default '0',
  `newpm` tinyint(1) NOT NULL default '0',
  `medals` varchar(255) NOT NULL default '',
  `money` mediumint(7) unsigned NOT NULL default '0',
  `totalspace` bigint(13) NOT NULL default '0',
  `usespace` bigint(13) NOT NULL default '0',
  `oltime` int(10) NOT NULL default '0',
  `lastvist` int(10) NOT NULL default '0',
  `lastip` varchar(15) NOT NULL default '',
  `regdate` int(10) NOT NULL default '0',
  `regip` varchar(15) NOT NULL default '',
  `sex` tinyint(1) NOT NULL default '0',
  `bday` date NOT NULL default '0000-00-00',
  `icon` varchar(150) NOT NULL default '',
  `introduce` text NOT NULL,
  `hits` int(7) NOT NULL default '0',
  `lastview` int(10) NOT NULL default '0',
  `oicq` varchar(11) NOT NULL default '',
  `msn` varchar(50) NOT NULL default '',
  `homepage` varchar(150) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `provinceid` mediumint(6) NOT NULL default '0',
  `cityid` mediumint(7) NOT NULL default '0',
  `address` varchar(255) NOT NULL default '',
  `postalcode` varchar(6) NOT NULL default '',
  `mobphone` varchar(12) NOT NULL default '',
  `telephone` varchar(25) NOT NULL default '',
  `idcard` varchar(20) NOT NULL default '',
  `truename` varchar(20) NOT NULL default '',
  `config` text NOT NULL,
  `moneycard` mediumint(7) unsigned NOT NULL default '0',
  `email_yz` tinyint(1) NOT NULL default '0',
  `mob_yz` tinyint(1) NOT NULL default '0',
  `idcard_yz` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`uid`),
  KEY `groups` (`groups`),
  KEY `sex` (`sex`,`bday`,`cityid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}memberdata_1` (
  `uid` mediumint(7) NOT NULL default '0',
  `cpname` varchar(50) NOT NULL default '',
  `cplogo` varchar(150) NOT NULL default '',
  `cptype` varchar(40) NOT NULL default '0',
  `cptrade` varchar(40) NOT NULL default '',
  `cpproduct` varchar(255) NOT NULL default '',
  `cpcity` mediumint(7) NOT NULL default '0',
  `cpfoundtime` varchar(20) NOT NULL default '',
  `cpfounder` varchar(20) NOT NULL default '',
  `cpmannum` varchar(20) NOT NULL default '',
  `cpmoney` varchar(20) NOT NULL default '',
  `cpcode` varchar(30) NOT NULL default '',
  `cppermit` varchar(150) NOT NULL default '',
  `cpweb` varchar(150) NOT NULL default '',
  `cppostcode` varchar(6) NOT NULL default '0',
  `cptelephone` varchar(30) NOT NULL default '',
  `cpfax` varchar(30) NOT NULL default '',
  `cpaddress` varchar(150) NOT NULL default '',
  `cplinkman` varchar(20) NOT NULL default '',
  `cpmobphone` varchar(11) NOT NULL default '',
  `cpqq` varchar(11) NOT NULL default '',
  `cpmsn` varchar(50) NOT NULL default '',
  UNIQUE KEY `uid` (`uid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}group` (
  `gid` smallint(4) NOT NULL AUTO_INCREMENT,
  `gptype` tinyint(1) NOT NULL default '0',
  `grouptitle` varchar(50) NOT NULL default '',
  `levelnum` mediumint(7) NOT NULL default '0',
  `totalspace` int(10) NOT NULL default '0',
  `allowsearch` tinyint(1) NOT NULL default '0',
  `powerdb` text NOT NULL,
  `allowadmin` tinyint(1) NOT NULL default '0',
  `allowadmindb` text,
  PRIMARY KEY  (`gid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}menu` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `fid` mediumint(5) NOT NULL default '0',
  `name` varchar(80) NOT NULL default '',
  `linkurl` varchar(150) NOT NULL default '',
  `color` varchar(15) NOT NULL default '',
  `target` tinyint(1) NOT NULL default '0',
  `moduleid` tinyint(2) NOT NULL default '0',
  `type` tinyint(2) NOT NULL default '0',
  `hide` tinyint(1) NOT NULL default '0',
  `list` smallint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}admin_menu` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `fid` mediumint(5) NOT NULL default '0',
  `name` text NOT NULL,
  `linkurl` varchar(150) NOT NULL default '',
  `color` varchar(15) NOT NULL default '',
  `target` tinyint(1) NOT NULL default '0',
  `list` smallint(4) NOT NULL default '0',
  `groupid` mediumint(5) NOT NULL default '0',
  `iftier` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}module` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `pre` varchar(20) NOT NULL default '',
  `dirname` varchar(30) NOT NULL default '',
  `domain` varchar(100) NOT NULL default '',
  `admindir` varchar(20) NOT NULL default '',
  `unite_admin` tinyint(1) NOT NULL default '0',
  `config` text NOT NULL,
  `list` mediumint(5) NOT NULL default '0',
  `admingroup` varchar(150) NOT NULL default '',
  `adminmember` text NOT NULL,
  `unite_member` tinyint(1) NOT NULL default '1',
  `unite_table` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}fu_article` (
  `fid` int(7) NOT NULL default '0',
  `aid` int(10) NOT NULL default '0',
  KEY `fid` (`fid`),
  KEY `aid` (`aid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}fu_sort` (
  `fid` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `fup` mediumint(7) unsigned NOT NULL default '0',
  `fmid` mediumint(5) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `class` smallint(4) NOT NULL default '0',
  `sons` smallint(4) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `admin` varchar(100) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  `listorder` tinyint(2) NOT NULL default '0',
  `passwd` varchar(32) NOT NULL default '',
  `logo` varchar(150) NOT NULL default '',
  `descrip` text NOT NULL,
  `style` varchar(50) NOT NULL default '',
  `template` text NOT NULL,
  `jumpurl` varchar(150) NOT NULL default '',
  `maxperpage` tinyint(3) NOT NULL default '0',
  `metakeywords` varchar(255) NOT NULL default '',
  `metadescription` varchar(255) NOT NULL default '',
  `allowcomment` tinyint(1) NOT NULL default '0',
  `allowpost` varchar(150) NOT NULL default '',
  `allowviewtitle` varchar(150) NOT NULL default '',
  `allowviewcontent` varchar(150) NOT NULL default '',
  `allowdownload` varchar(150) NOT NULL default '',
  `forbidshow` tinyint(1) NOT NULL default '0',
  `config` text NOT NULL,
  `list_html` varchar(255) NOT NULL default '',
  `bencandy_html` varchar(255) NOT NULL default '',
  `domain` varchar(150) NOT NULL default '',
  `domain_dir` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`fid`),
  KEY `fup` (`fup`),
  KEY `fmid` (`fmid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}special` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `fid` mediumint(7) NOT NULL default '0',
  `title` varchar(150) NOT NULL default '',
  `titlecolor` varchar(15) NOT NULL default '',
  `keywords` varchar(100) NOT NULL default '',
  `style` varchar(25) NOT NULL default '',
  `template` varchar(255) NOT NULL default '',
  `picurl` varchar(150) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `aids` text NOT NULL,
  `tids` text NOT NULL,
  `jumpurl` varchar(150) NOT NULL default '',
  `target` tinyint(1) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(50) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `list` int(10) NOT NULL default '0',
  `hits` mediumint(7) NOT NULL default '0',
  `lastview` int(10) NOT NULL default '0',
  `levels` tinyint(1) NOT NULL default '0',
  `levelstime` int(10) NOT NULL default '0',
  `htmlfile` varchar(50) NOT NULL default '',
  `banner` varchar(150) NOT NULL default '',
  `allowpost` varchar(255) NOT NULL default '',
  `ifbase` tinyint(1) NOT NULL default '0',
  `htmlname` varchar(80) NOT NULL default '',
  `yz` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `ifbase` (`ifbase`),
  KEY `yz` (`yz`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}special_comment` (
  `id` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `cid` mediumint(7) unsigned NOT NULL default '0',
  `vid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL default '',
  `icon` tinyint(3) NOT NULL default '0',
  `yz` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `aid` (`cid`),
  KEY `uid` (`uid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}spsort` (
  `fid` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `fup` mediumint(7) unsigned NOT NULL default '0',
  `name` varchar(200) NOT NULL default '',
  `class` smallint(4) NOT NULL default '0',
  `sons` smallint(4) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `admin` varchar(100) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  `listorder` tinyint(2) NOT NULL default '0',
  `passwd` varchar(32) NOT NULL default '',
  `logo` varchar(150) NOT NULL default '',
  `descrip` text NOT NULL,
  `style` varchar(50) NOT NULL default '',
  `template` text NOT NULL,
  `jumpurl` varchar(150) NOT NULL default '',
  `maxperpage` tinyint(3) NOT NULL default '0',
  `metakeywords` varchar(255) NOT NULL default '',
  `metadescription` varchar(255) NOT NULL default '',
  `allowcomment` tinyint(1) NOT NULL default '0',
  `allowpost` varchar(150) NOT NULL default '',
  `allowviewtitle` varchar(150) NOT NULL default '',
  `allowviewcontent` varchar(150) NOT NULL default '',
  `allowdownload` varchar(150) NOT NULL default '',
  `forbidshow` tinyint(1) NOT NULL default '0',
  `config` text NOT NULL,
  `list_html` varchar(255) NOT NULL default '',
  `bencandy_html` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`fid`),
  KEY `fup` (`fup`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}alonepage` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `fid` mediumint(5) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `style` varchar(15) NOT NULL default '',
  `tpl_head` varchar(50) NOT NULL default '',
  `tpl_main` varchar(50) NOT NULL default '',
  `tpl_foot` varchar(50) NOT NULL default '',
  `filename` varchar(100) default NULL,
  `filepath` varchar(30) NOT NULL default '',
  `descrip` text NOT NULL,
  `keywords` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `hits` int(7) NOT NULL default '0',
  `ishtml` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}channel` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) NOT NULL default '0',
  `sort` smallint(4) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `path` varchar(30) NOT NULL default '',
  `phpname` varchar(255) NOT NULL default '',
  `htmlname` varchar(255) NOT NULL default '',
  `fids` varchar(255) NOT NULL default '',
  `showfid` varchar(150) NOT NULL default '',
  `style` varchar(15) NOT NULL default '',
  `head_tpl` varchar(255) NOT NULL default '',
  `main_tpl` varchar(255) NOT NULL default '',
  `foot_tpl` varchar(255) NOT NULL default '',
  `url` varchar(150) NOT NULL default '',
  `logo` varchar(150) NOT NULL default '',
  `descrip` text NOT NULL,
  `admin` varchar(150) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  `config` text NOT NULL,
  PRIMARY KEY  (`id`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}collection` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `aid` int(10) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}comment` (
  `cid` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned NOT NULL default '0',
  `fid` mediumint(7) unsigned NOT NULL default '0',
  `authorid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL default '',
  `icon` tinyint(3) NOT NULL default '0',
  `yz` tinyint(1) NOT NULL default '0',
  `ifcom` tinyint(1) NOT NULL default '0',
  `agree` mediumint(5) NOT NULL default '0',
  `disagree` mediumint(5) NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `aid` (`aid`),
  KEY `fid` (`fid`),
  KEY `uid` (`uid`),
  KEY `ifcom` (`ifcom`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}config` (
  `c_key` varchar(50) NOT NULL default '',
  `c_value` text NOT NULL,
  `c_descrip` text NOT NULL,
  PRIMARY KEY  (`c_key`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}copyfrom` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `keywords` (`name`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}hack` (
  `keywords` varchar(30) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `isclose` tinyint(1) NOT NULL default '0',
  `author` varchar(30) NOT NULL default '',
  `config` text NOT NULL,
  `htmlcode` text NOT NULL,
  `hackfile` text NOT NULL,
  `hacksqltable` text NOT NULL,
  `adminurl` varchar(150) NOT NULL default '',
  `about` text NOT NULL,
  `class1` varchar(30) NOT NULL default '',
  `class2` varchar(30) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  `linkname` text NOT NULL,
  `isbiz` tinyint(1) NOT NULL default '0',
  UNIQUE KEY `keywords` (`keywords`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}label` (
  `lid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL default '',
  `ch` smallint(4) NOT NULL default '0',
  `chtype` tinyint(2) NOT NULL default '0',
  `tag` varchar(50) NOT NULL default '',
  `type` varchar(30) NOT NULL default '',
  `typesystem` tinyint(1) NOT NULL default '0',
  `code` text NOT NULL,
  `divcode` text,
  `hide` tinyint(1) NOT NULL default '0',
  `js_time` int(10) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `pagetype` tinyint(3) NOT NULL default '0',
  `module` mediumint(6) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `if_js` tinyint(1) NOT NULL default '0',
  `style` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`lid`),
  KEY `ch` (`ch`,`pagetype`,`module`,`fid`,`chtype`),
  KEY `tag` (`tag`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}form_content` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL default '',
  `mid` smallint(4) NOT NULL default '0',
  `hits` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `list` varchar(10) NOT NULL default '',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `titlecolor` varchar(15) NOT NULL default '',
  `yz` tinyint(1) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `hits` (`hits`,`yz`),
  KEY `list` (`list`,`yz`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}form_content_1` (
  `id` mediumint(7) unsigned NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `truename` varchar(20) NOT NULL default '',
  `sex` int(1) NOT NULL default '0',
  `oicq` varchar(10) NOT NULL default '',
  `mobphone` varchar(11) NOT NULL default '',
  `interest` mediumtext NOT NULL,
  `introduce` mediumtext NOT NULL,
  `sortname` varchar(40) NOT NULL default '',
  `webtime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}form_content_2` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(7) NOT NULL default '0',
  `workplace` varchar(100) NOT NULL default '',
  `nums` varchar(10) NOT NULL default '',
  `jobrequire` mediumtext NOT NULL,
  `workwhere` varchar(50) NOT NULL default '',
  `wage` varchar(30) NOT NULL default '',
  `asksex` int(1) NOT NULL default '0',
  `schoo_age` varchar(20) NOT NULL default '',
  `wageyear` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}form_content_3` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(7) NOT NULL default '0',
  `advicetype` varchar(30) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `truename` varchar(15) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `mobphone` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}form_content_4` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(7) NOT NULL default '0',
  `truename` varchar(15) NOT NULL default '',
  `sex` int(1) NOT NULL default '0',
  `age` int(2) NOT NULL default '0',
  `mobphone` varchar(25) NOT NULL default '',
  `metier` varchar(30) NOT NULL default '',
  `my_song` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}form_content_5` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(7) NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `bday` varchar(25) NOT NULL default '',
  `school_age` varchar(20) NOT NULL default '',
  `native` varchar(30) NOT NULL default '',
  `specialty` varchar(40) NOT NULL default '',
  `skill` varchar(50) NOT NULL default '',
  `sport` varchar(80) NOT NULL default '',
  `height` int(3) NOT NULL default '0',
  `truename` varchar(15) NOT NULL default '',
  `oicq` varchar(10) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `mobphone` varchar(11) NOT NULL default '',
  `address` varchar(150) NOT NULL default '',
  `telephone` varchar(15) NOT NULL default '',
  `idcard` varchar(18) NOT NULL default '',
  `cp_title` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}form_content_6` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(7) NOT NULL default '0',
  `workposition` varchar(50) NOT NULL default '',
  `experience` mediumtext NOT NULL,
  `workyear` int(2) NOT NULL default '0',
  `truename` varchar(15) NOT NULL default '',
  `schoo_age` varchar(15) NOT NULL default '',
  `myage` int(2) NOT NULL default '0',
  `graduateschool` varchar(40) NOT NULL default '',
  `specialty` varchar(50) NOT NULL default '',
  `skill` varchar(50) NOT NULL default '',
  `sex` int(1) NOT NULL default '0',
  `telephone` varchar(25) NOT NULL default '',
  `wage` varchar(20) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `oicq` varchar(11) NOT NULL default '',
  `worktime` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}form_content_7` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(7) NOT NULL default '0',
  `product` varchar(50) NOT NULL default '',
  `paymoney` varchar(15) NOT NULL default '',
  `paytime` varchar(15) NOT NULL default '',
  `paytype` varchar(25) NOT NULL default '',
  `sendbank` varchar(30) NOT NULL default '',
  `receivebank` varchar(30) NOT NULL default '',
  `truename` varchar(15) NOT NULL default '',
  `oicq` varchar(11) NOT NULL default '',
  `telephone` varchar(30) NOT NULL default '',
  `mobphone` varchar(11) NOT NULL default '',
  `address` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}form_content_8` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(7) NOT NULL default '0',
  `roomtype` varchar(30) NOT NULL default '',
  `roomnum` int(3) NOT NULL default '0',
  `numday` int(3) NOT NULL default '0',
  `intotime` varchar(30) NOT NULL default '',
  `truename` varchar(30) NOT NULL default '',
  `telephone` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) {$tbl_setting};
EOF;

$sql_tbl[] = <<<EOF
CREATE TABLE `{$tbl_prefix}form_module` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL default '',
  `list` smallint(4) NOT NULL default '0',
  `style` varchar(50) NOT NULL default '',
  `config` mediumtext NOT NULL,
  `allowpost` varchar(255) NOT NULL default '',
  `endtime` int(10) NOT NULL default '0',
  `about` text NOT NULL,
  `usetitle` tinyint(1) NOT NULL default '0',
  `repeatpost` tinyint(1) NOT NULL default '0',
  `statename` varchar(30) NOT NULL default '',
  `allowview` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) {$tbl_setting};
EOF;

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}form_reply` (
  `rid` int(10) NOT NULL AUTO_INCREMENT,
  `id` int(10) NOT NULL default '0',
  `mid` int(10) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`rid`)
) {$tbl_setting};";


$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}friendlink` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `fid` int(7) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `url` varchar(150) NOT NULL default '',
  `logo` varchar(150) NOT NULL default '',
  `descrip` varchar(255) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  `ifhide` tinyint(1) NOT NULL default '0',
  `iswordlink` tinyint(1) default NULL,
  `hits` tinyint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `uid` int(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `yz` tinyint(1) NOT NULL default '1',
  `endtime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `yz` (`yz`,`endtime`,`ifhide`)
) {$tbl_setting};";


$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}friendlink_sort` (
  `fid` mediumint(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  PRIMARY KEY  (`fid`)
) {$tbl_setting};";


$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}gather_rule` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `fid` mediumint(7) NOT NULL default '0',
  `type` varchar(15) NOT NULL default '0',
  `fixsystem` varchar(30) NOT NULL default '',
  `filetype` varchar(50) NOT NULL default '',
  `webname` varchar(150) NOT NULL default '',
  `listurl` varchar(150) NOT NULL default '',
  `firstpage` varchar(150) NOT NULL default '',
  `page_begin` int(10) NOT NULL default '0',
  `page_end` int(10) NOT NULL default '0',
  `page_step` int(10) NOT NULL default '0',
  `title_minleng` smallint(5) NOT NULL default '0',
  `listmoreurl` text NOT NULL,
  `link_include_word` text NOT NULL,
  `link_noinclude_word` text NOT NULL,
  `link_replace_word` text NOT NULL,
  `title_replace_word` text NOT NULL,
  `list_begin_code` text NOT NULL,
  `list_end_code` text NOT NULL,
  `list_begin_preg` text NOT NULL,
  `list_end_preg` text NOT NULL,
  `gatherthesame` tinyint(1) NOT NULL default '0',
  `show_begin_preg` text NOT NULL,
  `show_end_preg` text NOT NULL,
  `show_endfile_preg` text NOT NULL,
  `show_begin_code` text NOT NULL,
  `show_end_code` text NOT NULL,
  `show_replace_word` text NOT NULL,
  `show_morepage` varchar(100) NOT NULL default '',
  `show_firstpage` varchar(100) NOT NULL default '',
  `show_spe2page` tinyint(1) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `list` int(10) NOT NULL default '0',
  `copypic` tinyint(1) NOT NULL default '0',
  `sort` smallint(4) NOT NULL default '0',
  `file_type` varchar(50) NOT NULL default '',
  `file_minleng` mediumint(6) NOT NULL default '0',
  `file_minsize` int(9) NOT NULL default '0',
  `file_includeword` text NOT NULL,
  `file_noincludeword` text NOT NULL,
  `file_explode` text NOT NULL,
  `file_picwidth` int(8) NOT NULL default '0',
  `file_star_string` varchar(150) NOT NULL default '',
  `title_rule` text NOT NULL,
  `content_rule` text NOT NULL,
  `title_morepage_rull` text NOT NULL,
  `content_morepage_rull` text NOT NULL,
  `charset_type` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) {$tbl_setting};";


$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}gather_sort` (
  `fid` mediumint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL default '',
  `fup` mediumint(6) NOT NULL default '0',
  `class` smallint(4) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `list` mediumint(5) NOT NULL default '0',
  `allowpost` varchar(255) NOT NULL default '',
  `sons` smallint(4) NOT NULL default '0',
  PRIMARY KEY  (`fid`)
) {$tbl_setting};";


$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}pm` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `touid` mediumint(8) unsigned NOT NULL default '0',
  `togroups` varchar(80) NOT NULL default '',
  `fromuid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(15) NOT NULL default '',
  `type` enum('rebox','sebox','public') NOT NULL default 'rebox',
  `ifnew` tinyint(1) NOT NULL default '0',
  `title` varchar(130) NOT NULL default '',
  `mdate` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`mid`),
  KEY `touid` (`touid`),
  KEY `fromuid` (`fromuid`),
  KEY `type` (`type`)
) {$tbl_setting};";


$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}guestbook` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `fid` mediumint(7) NOT NULL default '0',
  `ico` tinyint(2) NOT NULL default '0',
  `email` varchar(50) NOT NULL default '',
  `oicq` varchar(11) default NULL,
  `weburl` varchar(150) NOT NULL default '',
  `blogurl` varchar(150) NOT NULL default '',
  `uid` int(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  `content` text NOT NULL,
  `yz` tinyint(1) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `list` int(10) NOT NULL default '0',
  `reply` text NOT NULL,
  PRIMARY KEY  (`id`)
) {$tbl_setting};";


$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}keyword` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `keywords` varchar(30) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `ifhide` tinyint(1) NOT NULL default '0',
  `url` varchar(150) NOT NULL default '',
  `num` smallint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `keywords` (`keywords`),
  KEY `num` (`num`)
) {$tbl_setting};";


$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}keywordid` (
  `id` mediumint(7) NOT NULL default '0',
  `aid` mediumint(7) NOT NULL default '0',
  KEY `id` (`id`),
  KEY `aid` (`aid`)
) {$tbl_setting};";


$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}limitword` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `oldword` varchar(50) NOT NULL default '',
  `newword` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) {$tbl_setting};";


$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}ad` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `keywords` varchar(50) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `type` varchar(30) NOT NULL default '0',
  `isclose` tinyint(1) NOT NULL default '0',
  `begintime` int(10) NOT NULL default '0',
  `endtime` int(10) NOT NULL default '0',
  `adcode` text NOT NULL,
  `posttime` int(10) NOT NULL default '0',
  `list` int(10) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `hits` mediumint(7) NOT NULL default '0',
  `money` mediumint(6) NOT NULL default '0',
  `moneycard` mediumint(6) NOT NULL default '0',
  `ifsale` tinyint(1) NOT NULL default '0',
  `autoyz` tinyint(1) NOT NULL default '0',
  `demourl` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`id`)
) {$tbl_setting};";


$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}ad_user` (
  `u_id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `id` mediumint(7) NOT NULL default '0',
  `u_uid` mediumint(7) NOT NULL default '0',
  `u_username` varchar(30) NOT NULL default '',
  `u_day` smallint(4) NOT NULL default '0',
  `u_begintime` int(10) NOT NULL default '0',
  `u_endtime` int(10) NOT NULL default '0',
  `u_hits` mediumint(7) NOT NULL default '0',
  `u_yz` tinyint(1) NOT NULL default '0',
  `u_code` text NOT NULL,
  `u_money` mediumint(7) NOT NULL default '0',
  `u_moneycard` mediumint(7) NOT NULL default '0',
  `u_posttime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`u_id`),
  KEY `u_endtime` (`u_endtime`)
) {$tbl_setting};";


$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}sellad` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL default '',
  `isclose` tinyint(1) NOT NULL default '0',
  `list` int(10) NOT NULL default '0',
  `price` mediumint(5) NOT NULL default '0',
  `day` mediumint(4) NOT NULL default '0',
  `adnum` smallint(3) NOT NULL default '0',
  `wordnum` smallint(3) NOT NULL default '0',
  `autoyz` tinyint(1) NOT NULL default '1',
  `demourl` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`id`)
) {$tbl_setting};";


$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}sellad_user` (
  `ad_id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `begintime` int(10) NOT NULL default '0',
  `endtime` int(10) NOT NULL default '0',
  `money` mediumint(6) NOT NULL default '0',
  `id` mediumint(7) NOT NULL default '0',
  `yz` tinyint(1) NOT NULL default '1',
  `adlink` varchar(200) NOT NULL default '',
  `adword` varchar(255) NOT NULL default '',
  `hits` mediumint(7) NOT NULL default '0',
  `color` varchar(20) NOT NULL default '',
  `fonttype` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ad_id`),
  KEY `id` (`id`,`endtime`,`money`,`yz`)
) {$tbl_setting};";


$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}shoporderproduct` (
  `pid` int(7) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL default '',
  `orderid` mediumint(7) default NULL,
  `shopid` int(10) NOT NULL default '0',
  `shopuid` mediumint(7) NOT NULL default '0',
  `ifsend` tinyint(1) NOT NULL default '0',
  `amount` mediumint(7) NOT NULL default '0',
  PRIMARY KEY  (`pid`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}shoporderuser` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `truename` varchar(30) NOT NULL default '',
  `sex` varchar(10) NOT NULL default '',
  `telphone` varchar(20) NOT NULL default '',
  `mobphone` varchar(12) NOT NULL default '',
  `email` varchar(30) NOT NULL default '',
  `oicq` varchar(11) NOT NULL default '',
  `postalcode` varchar(6) NOT NULL default '',
  `sendtype` varchar(50) NOT NULL default '',
  `paytype` varchar(50) NOT NULL default '',
  `olpaytype` varchar(25) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `othersay` text NOT NULL,
  `posttime` int(10) NOT NULL default '0',
  `ifsend` tinyint(1) NOT NULL default '0',
  `totalmoney` varchar(15) NOT NULL default '',
  `ifpay` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}upfile` (
  `up_id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `module_id` smallint(4) NOT NULL default '0',
  `ids` varchar(255) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `url` varchar(150) NOT NULL default '',
  `filename` varchar(100) NOT NULL default '',
  `num` smallint(5) NOT NULL default '0',
  `if_tmp` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`up_id`),
  KEY `filename` (`filename`),
  KEY `if_tmp` (`if_tmp`),
  KEY `posttime` (`posttime`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}vote` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `cid` int(7) NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `votenum` int(7) NOT NULL default '0',
  `list` int(10) NOT NULL default '0',
  `img` varchar(100) NOT NULL default '',
  `describes` varchar(255) NOT NULL default '',
  `url` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}vote_comment` (
  `id` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `cid` mediumint(7) unsigned NOT NULL default '0',
  `vid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL default '',
  `icon` tinyint(3) NOT NULL default '0',
  `yz` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `aid` (`cid`),
  KEY `uid` (`uid`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}vote_config` (
  `cid` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL default '',
  `about` text NOT NULL,
  `type` tinyint(4) NOT NULL default '0',
  `limittime` int(10) NOT NULL default '0',
  `limitip` tinyint(1) NOT NULL default '0',
  `ip` text NOT NULL,
  `posttime` int(10) NOT NULL default '0',
  `user` text NOT NULL,
  `begintime` int(10) NOT NULL default '0',
  `endtime` int(10) NOT NULL default '0',
  `forbidguestvote` tinyint(1) NOT NULL default '0',
  `ifcomment` tinyint(1) NOT NULL default '0',
  `tplcode` text NOT NULL,
  `votetype` tinyint(2) NOT NULL default '0',
  `aid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  PRIMARY KEY  (`cid`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}area` (
  `fid` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `fup` mediumint(7) unsigned NOT NULL default '0',
  `name` varchar(200) NOT NULL default '',
  `class` smallint(4) NOT NULL default '0',
  `sons` smallint(4) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `admin` varchar(100) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  `listorder` tinyint(2) NOT NULL default '0',
  `passwd` varchar(32) NOT NULL default '',
  `logo` varchar(150) NOT NULL default '',
  `descrip` text NOT NULL,
  `style` varchar(50) NOT NULL default '',
  `template` text NOT NULL,
  `jumpurl` varchar(150) NOT NULL default '',
  `maxperpage` tinyint(3) NOT NULL default '0',
  `metakeywords` varchar(255) NOT NULL default '',
  `metadescription` varchar(255) NOT NULL default '',
  `allowcomment` tinyint(1) NOT NULL default '0',
  `allowpost` varchar(150) NOT NULL default '',
  `allowviewtitle` varchar(150) NOT NULL default '',
  `allowviewcontent` varchar(150) NOT NULL default '',
  `allowdownload` varchar(150) NOT NULL default '',
  `forbidshow` tinyint(1) NOT NULL default '0',
  `config` text NOT NULL,
  PRIMARY KEY  (`fid`),
  KEY `fup` (`fup`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}jfabout` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `fid` mediumint(5) NOT NULL default '0',
  `title` varchar(150) NOT NULL default '',
  `content` text NOT NULL,
  `list` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}jfsort` (
  `fid` mediumint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  PRIMARY KEY  (`fid`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}moneycard` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `passwd` varchar(32) NOT NULL default '',
  `moneyrmb` int(7) NOT NULL default '0',
  `moneycard` int(7) NOT NULL default '0',
  `ifsell` tinyint(1) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(32) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}olpay` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `orderid` int(10) NOT NULL default '0',
  `numcode` varchar(32) NOT NULL default '',
  `money` varchar(15) NOT NULL default '0',
  `ifpay` tinyint(1) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(32) NOT NULL default '',
  `paytype` tinyint(3) NOT NULL default '0',
  `moduleid` mediumint(5) NOT NULL default '0',
  `formid` mediumint(5) NOT NULL default '0',
  `banktype` varchar(15) NOT NULL default '',
  `articleid` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `numcode` (`numcode`),
  KEY `paytype` (`paytype`),
  KEY `formid` (`formid`),
  KEY `articleid` (`articleid`),
  KEY `moduleid` (`moduleid`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}propagandize` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(7) NOT NULL default '0',
  `ip` bigint(11) NOT NULL default '0',
  `day` smallint(3) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `fromurl` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `day` (`day`,`uid`,`ip`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}report` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `aid` int(10) NOT NULL default '0',
  `type` varchar(50) NOT NULL default '',
  `uid` mediumint(7) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `content` text NOT NULL,
  `posttime` int(10) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `yz` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}template` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL default '',
  `type` smallint(4) NOT NULL default '0',
  `filepath` varchar(100) NOT NULL default '',
  `descrip` text NOT NULL,
  `list` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}template_bak` (
  `bid` int(7) NOT NULL AUTO_INCREMENT,
  `id` int(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `code` text NOT NULL,
  PRIMARY KEY  (`bid`),
  KEY `id` (`id`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}count_site` (
  `fid` mediumint(7) NOT NULL AUTO_INCREMENT,
  `fup` mediumint(7) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `list` mediumint(5) NOT NULL default '0',
  `ifclose` tinyint(1) NOT NULL default '0',
  `count_num` mediumint(7) NOT NULL default '0',
  PRIMARY KEY  (`fid`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}count_stat` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `fid` mediumint(7) NOT NULL default '0',
  `year` mediumint(4) NOT NULL default '0',
  `month` tinyint(2) NOT NULL default '0',
  `week` tinyint(2) NOT NULL default '0',
  `day` smallint(3) NOT NULL default '0',
  `hour` tinyint(2) NOT NULL default '0',
  `pv` mediumint(7) NOT NULL default '0',
  `uv` mediumint(7) NOT NULL default '0',
  `ip` mediumint(7) NOT NULL default '0',
  `windows_type` text NOT NULL,
  `ie_type` text NOT NULL,
  `windows_lang` text NOT NULL,
  `screen_size` text NOT NULL,
  `from_domain` text NOT NULL,
  PRIMARY KEY  (`id`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}count_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fid` mediumint(7) NOT NULL default '0',
  `time_day` tinyint(2) NOT NULL default '0',
  `username` varchar(32) NOT NULL default '',
  `lasttime` int(10) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `ip_address` varchar(50) NOT NULL default '',
  `fromurl` varchar(255) NOT NULL default '',
  `weburl` varchar(150) NOT NULL default '',
  `lasturl` varchar(150) NOT NULL default '',
  `windows_type` varchar(30) NOT NULL default '',
  `ie_type` varchar(30) NOT NULL default '',
  `windows_lang` varchar(30) NOT NULL default '',
  `screen_size` varchar(30) NOT NULL default '',
  `hits` mediumint(7) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `username` (`username`),
  KEY `time_day` (`time_day`,`ip`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}exam_form` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL default '0',
  `fid` mediumint(6) NOT NULL default '0',
  `name` varchar(150) NOT NULL default '',
  `config` text NOT NULL,
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ifshare` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}exam_form_element` (
  `element_id` int(7) NOT NULL AUTO_INCREMENT,
  `form_id` mediumint(7) NOT NULL default '0',
  `title_id` mediumint(7) NOT NULL default '0',
  `list` int(10) NOT NULL default '0',
  PRIMARY KEY  (`element_id`),
  KEY `form_id` (`form_id`),
  KEY `title_id` (`title_id`),
  KEY `list` (`list`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}exam_sort` (
  `fid` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `fup` mediumint(7) unsigned NOT NULL default '0',
  `name` varchar(200) NOT NULL default '',
  `class` smallint(4) NOT NULL default '0',
  `sons` smallint(4) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `admin` varchar(100) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  `listorder` tinyint(2) NOT NULL default '0',
  `passwd` varchar(32) NOT NULL default '',
  `logo` varchar(150) NOT NULL default '',
  `descrip` text NOT NULL,
  `style` varchar(50) NOT NULL default '',
  `template` text NOT NULL,
  `jumpurl` varchar(150) NOT NULL default '',
  `maxperpage` tinyint(3) NOT NULL default '0',
  `metakeywords` varchar(255) NOT NULL default '',
  `metadescription` varchar(255) NOT NULL default '',
  `allowcomment` tinyint(1) NOT NULL default '0',
  `allowpost` varchar(150) NOT NULL default '',
  `allowviewtitle` varchar(150) NOT NULL default '',
  `allowviewcontent` varchar(150) NOT NULL default '',
  `allowdownload` varchar(150) NOT NULL default '',
  `forbidshow` tinyint(1) NOT NULL default '0',
  `config` text NOT NULL,
  `list_html` varchar(255) NOT NULL default '',
  `bencandy_html` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`fid`),
  KEY `fup` (`fup`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}exam_student` (
  `student_id` int(7) NOT NULL AUTO_INCREMENT,
  `student_uid` mediumint(7) NOT NULL default '0',
  `student_name` varchar(30) NOT NULL default '',
  `form_id` mediumint(7) NOT NULL default '0',
  `total_fen` smallint(4) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`student_id`),
  KEY `form_id` (`form_id`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}exam_student_title` (
  `st_id` int(7) NOT NULL AUTO_INCREMENT,
  `title_id` mediumint(7) NOT NULL default '0',
  `student_id` mediumint(7) NOT NULL default '0',
  `form_id` mediumint(7) NOT NULL default '0',
  `answer` text NOT NULL,
  `fen` smallint(3) NOT NULL default '0',
  `comment` text NOT NULL,
  PRIMARY KEY  (`st_id`)
) {$tbl_setting};";

$sql_tbl[] = "CREATE TABLE `{$tbl_prefix}exam_title` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `fid` smallint(4) NOT NULL default '0',
  `type` tinyint(2) NOT NULL default '0',
  `question` text NOT NULL,
  `config` text NOT NULL,
  `answer` text NOT NULL,
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ifshare` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) {$tbl_setting};";

