<?php
require_once("global.php");

if(!$id){
	$id=$aid;
}elseif(!$aid){
	$aid=$id;
}

$erp=get_id_table($id);
$rsdb=$db->get_one("SELECT A.*,S.* FROM {$pre}article$erp A LEFT JOIN {$pre}sort S ON A.fid=S.fid WHERE A.aid='$id'");

$fid=$rsdb[fid];
if(!$rsdb)
{
	die("地址有误,请检查之");
}
get_guide($fid);	//栏目导航
$GuideFid[$fid]=str_replace("'list.php?","'$webdb[www_url]$webdb[path]/list.php?",$GuideFid[$fid]);

require(PHP168_PATH."inc/head.php");
require(html("comment"));
require(PHP168_PATH."inc/foot.php");
?>