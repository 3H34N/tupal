<?php
!function_exists('html') && exit('ERR');
require_once(PHP168_PATH."inc/artic_function.php");



if($action=="delete"&&$Apower[fu_artic_power])
{
	foreach( $listdb AS $key=>$value){
		list($aid,$fid)=explode("-",$value);
		$db->query("DELETE FROM {$pre}fu_article WHERE fid='$fid' AND aid='$aid'");
	}
	
	jump("删除成功","$FROMURL",0);
}
/**
*列出所有文章
**/
elseif($job=="listartic"&&$Apower[fu_artic_power])
{
	$SQL=" 1 ";
	if(is_numeric($fid)){
		$SQL.=" AND A.fid=$fid ";
	}
	$rows=50;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$order="A.aid";
	$desc="DESC";
	$showpage=getpage("{$pre}fu_article A","WHERE $SQL","index.php?lfj=$lfj&job=listartic&fid=$fid&type=$type&keyword=$keyword&only=$only&mid=$mid",$rows,"");
	$sort_fid=$Guidedb->Select("{$pre}fu_sort","fid",$fid,"index.php?lfj=$lfj&job=listartic");
	$query=$db->query("SELECT A.*,S.name AS fname FROM {$pre}fu_article A LEFT JOIN {$pre}fu_sort S ON A.fid=S.fid WHERE $SQL ORDER BY A.aid DESC LIMIT $min,$rows");
	while($rs=$db->fetch_array($query))
	{
		$erp=get_id_table($rs[aid]);
		$rss=$db->get_one("SELECT posttime,title,hits,comments,username,uid,yz,levels,pages,fid AS fid2,fname AS fname2 FROM {$pre}article$erp WHERE aid='$rs[aid]' ");
		$rss && $rs=$rs+$rss;
		$rs[ischeck]=$rs[yz]?"<A HREF='?lfj=$lfj&action=work&jobs=unyz&aid=$rs[aid]&only=$only&mid=$mid' title='已经通过审核,点击可取消审核'><img src='../member/images/check_yes.gif' border=0></A>":"<A HREF='?lfj=$lfj&action=work&jobs=yz&aid=$rs[aid]&only=$only&mid=$mid' style='color:blue;' title='还没有通过审核,点击可通过审核'><img src='../member/images/check_no.gif' border=0></A>";
		$rs[iscom]=$rs[levels]?"<A HREF='?lfj=$lfj&action=work&jobs=uncom&aid=$rs[aid]&levels=0&only=$only&mid=$mid' style='color:red;' title='已推荐,点击可取消推荐'><img src='../images/default/good_ico.gif' border=0></A>":"<A HREF='?lfj=$lfj&action=work&jobs=com&aid=$rs[aid]&levels=1&only=$only&mid=$mid' title='未推荐,点击可设置为推荐'><img src='../member/images/nogood_ico.gif' border=0></A>";
		$rs[title2]=urlencode($rs[title]);
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$rs[pages]<1 && $rs[pages]=1;
		$rs[yz]==2 && $rs[fname]="<A HREF='?lfj=$lfj&action=work&jobs=return&listdb[]=$rs[aid]&only=$only&mid=$mid' style='color:blue;' onclick=\"return confirm('你确认要从回收站取回它吗?')\">回收站</A>";
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/fu_artic/article_menu.htm");
	require(dirname(__FILE__)."/"."template/fu_artic/listartic.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

?>