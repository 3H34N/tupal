<?php
require_once(PHP168_PATH."inc/waterimage.php");

$string_yzimg=yzImgNumRand(4);
set_cookie("yzImgNum","$string_yzimg",600);

yzImg($string_yzimg);

function yzImgNumRand($lenth){
	mt_srand((double)microtime() * 1000000);
	for($i=0;$i<$lenth;$i++){
		$randval.= mt_rand(1,9);
	}
	return $randval;
}
