<?php
$base_menuName=array('base'=>'系统设置','article'=>'内容管理','member'=>'会员管理','other'=>'功能中心');


@include(PHP168_PATH."php168/hack.php");

if($ForceEnter||$GLOBALS[ForceEnter]){
	foreach( $menu_partDB AS $key1=>$value1){
		if($key1=='base'){
			continue;
		}
		foreach( $value1 AS $key2=>$value2){
			$menu_partDB['base'][]=$value2;
		}
	}
}

?>