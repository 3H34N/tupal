<?php
/**
*读文件函数
**/
function read_file($filename,$method="rb"){
	if($handle=@fopen($filename,$method)){
		@flock($handle,LOCK_SH);
		$filedata=@fread($handle,@filesize($filename));
		@fclose($handle);
	}
	return $filedata;
}

/**
*写文件函数
**/
function write_file($filename,$data,$method="rb+",$iflock=1){
	@touch($filename);
	$handle=@fopen($filename,$method);
	if($iflock){
		@flock($handle,LOCK_EX);
	}
	@fputs($handle,$data);
	if($method=="rb+") @ftruncate($handle,strlen($data));
	@fclose($handle);
	@chmod($filename,0777);	
	if( is_writable($filename) ){
		return 1;
	}else{
		return 0;
	}
}

/**
*图像处理函数
**/
function gdpic($srcFile,$dstFile,$width,$height,$type=''){
	require_once(PHP168_PATH."inc/waterimage.php");
	if(is_array($type)){
		//截取一部分,以满足匹配尺寸
		cutimg($srcFile,$dstFile,$x=$type[x]?$type[x]:0,$y=$type[y]?$type[y]:0,$width,$height,$x2=$type[x2]?$type[x2]:0,$y2=$type[y2]?$type[y2]:0,$scale=$type[s]?$type[s]:100,$fix=$type[fix]?$type[fix]:'');
	}elseif($type==1){
		//成比例的缩放
		ResizeImage($srcFile,$dstFile,$width,$height);
	}else{
		//与尺寸不匹配时.用色彩填充
		gdfillcolor($srcFile,$dstFile,$width,$height);
	}
}

/**
*删除文件,值不为空，则返回不能删除的文件名
**/
function del_file($path){
	if (file_exists($path)){
		if(is_file($path)){
			if(	!@unlink($path)	){
				$show.="$path,";
			}
		} else{
			$handle = opendir($path);
			while (($file = readdir($handle))!='') {
				if (($file!=".") && ($file!="..") && ($file!="")){
					if (is_dir("$path/$file")){
						$show.=del_file("$path/$file");
					} else{
						if( !@unlink("$path/$file") ){
							$show.="$path/$file,";
						}
					}
				}
			}
			closedir($handle);
			if(!@rmdir($path)){
				$show.="$path,";
			}
		}
	}
	return $show;
}

function Tblank($string,$msg="内容不能全为空格"){
	$string=str_replace("&nbsp;","",$string);
	$string=str_replace(" ","",$string);
	$string=str_replace("　","",$string);
	$string=str_replace("\r","",$string);
	$string=str_replace("\n","",$string);
	$string=str_replace("\t","",$string);
	if(!$string){
		showerr($msg);
	}
}

/**
*数据表字段信息处理函数
**/
function table_field($table,$field=''){
	global $db;
	$query=$db->query(" SELECT * FROM $table limit 1");
	$num=mysql_num_fields($query);
	for($i=0;$i<$num;$i++){
		$f_db=mysql_fetch_field($query,$i);
		$showdb[]=$f_db->name;
	}
	if($field){
		if(in_array($field,$showdb) ){
			return 1;
		}else{
			return 0;
		}
	}else{
		return $showdb;
	}
}
/**
*判断数据表是否存在
**/
function is_table($table){
	global $db;
	$query=$db->query("SHOW TABLE STATUS");
	while( $array=$db->fetch_array($query) ){
		if($table==$array[Name]){
			return 1;
		}
	}
}

/**
*上传文件
**/
function upfile($upfile,$array){
	global $db,$lfjuid,$pre,$webdb,$groupdb,$lfjdb,$timestamp;
	$FY=strtolower(strrchr(basename($upfile),"."));if($FY&&$FY!='.tmp'){die("<SCRIPT>alert('上传文件有误');</SCRIPT>");}
	$filename=$array[name];

	$path=makepath(PHP168_PATH.$array[path]);

	if($path=='false')
	{
		showerr("不能创建目录$array[path]，上传失败",1);
	}
	elseif(!is_writable($path))
	{
		showerr("目录不可写".$path,1);
	}

	$size=abs($array[size]);

	$filetype=strtolower(strrchr($filename,"."));

	if(!$upfile)
	{
		showerr("文件不存在，上传失败",1);
	}
	elseif(!$filetype)
	{
		showerr("文件不存在，或文件无后缀名,上传失败",1);
	}
	else
	{
		if($filetype=='.php'||$filetype=='.asp'||$filetype=='.aspx'||$filetype=='.jsp'||$filetype=='.cgi'){
			showerr("系统不允许上传可执行文件,上传失败",1);
		}

		if( $groupdb[upfileType] && !in_array($filetype,explode(" ",$groupdb[upfileType])) )
		{
			showerr("你所上传的文件格式为:$filetype,而你所在用户组仅允许上传的文件格式为:$groupdb[upfileType]",1);
		}
		elseif( !in_array($filetype,explode(" ",$webdb[upfileType])) )
		{
			showerr("你所上传的文件格式为:$filetype,而系统仅允许上传的文件格式为:$webdb[upfileType]",1);
		}

		if( $groupdb[upfileMaxSize] && ($groupdb[upfileMaxSize]*1024)<$size )
		{
			showerr("你所上传的文件大小为:".($size/1024)."K,而你所在用户组仅允许上传的文件大小为:{$groupdb[upfileMaxSize]}K",1);
		}
		if( !$groupdb[upfileMaxSize] && $webdb[upfileMaxSize] && ($webdb[upfileMaxSize]*1024)<$size )
		{
			showerr("你所上传的文件大小为:".($size/1024)."K,而系统仅允许上传的文件大小为:{$webdb[upfileMaxSize]}K",1);
		}
	}
	$oldname=preg_replace("/(.*)\.([^.]*)/is","\\1",$filename);
	if(eregi("(.jpg|.png|.gif)$",$filetype)){
		$tempname="{$lfjuid}_".date("YmdHms_",time()).rands(5).$filetype;
	}else{
		$tempname="{$lfjuid}_".date("YmdHms_",time()).base64_encode(urlencode($oldname)).$filetype;
	}
	$newfile="$path/$tempname";

	if(@move_uploaded_file($upfile,$newfile))
	{
		@chmod($newfile, 0777);
		$ck=2;
	}
    if(!$ck)
	{
		if(@copy($upfile,$newfile))
		{
			@chmod($newfile, 0777);
			$ck=2;
		}
	}
	if($ck)
	{	

		if(($array[size]+$lfjdb[usespace])>($webdb[totalSpace]*1048576+$groupdb[totalspace]*1048576+$lfjdb[totalspace])){
			//有的用户组不限制空间大小,$array[updateTable]
			if(!$groupdb[AllowUploadMax]){
				unlink($newfile);
				showerr("你的空间不足,上传失败,你可以联系管理员帮你增大空间!",1);
			}
		}
		$db->query("UPDATE {$pre}memberdata SET usespace=usespace+'$size' WHERE uid='$lfjuid' ");

		//对附件做处理,删除冗余的附件.对附件做个记录
		$url=str_replace("$webdb[updir]/","",$array[path]);
		$db->query("INSERT INTO `{$pre}upfile` ( `uid` , `posttime` , `url` , `filename` , `num`, `if_tmp` ) VALUES ('$lfjuid','$timestamp','$url','tmp-$tempname','1','1')");
		setcookie("IF_upfile",$timestamp);

		return $tempname;
	}
	else
	{
		showerr("请检查空间问题,上传失败",1);
	}
}

/**
*生成目录
**/
function makepath($path){
	//这个\没考虑
	$path=str_replace("\\","/",$path);
	$PHP168_PATH=str_replace("\\","/",PHP168_PATH);
	$detail=explode("/",$path);
	foreach($detail AS $key=>$value){
		if($value==''&&$key!=0){
			//continue;
		}
		$newpath.="$value/";
		if((eregi("^\/",$newpath)||eregi(":",$newpath))&&!strstr($newpath,$PHP168_PATH)){continue;}
		if( !is_dir($newpath) ){
			if(substr($newpath,-1)=='\\'||substr($newpath,-1)=='/')
			{
				$_newpath=substr($newpath,0,-1);
			}
			else
			{
				$_newpath=$newpath;
			}
			if(!is_dir($_newpath)&&!mkdir($_newpath)&&ereg("^\/",PHP168_PATH)){
				return 'false';
			}
			@chmod($newpath,0777);
		}
	}
	return $path;
}

/**
*取得真实目录
**/
function tempdir($file,$type=''){
	global $webdb;
	if($type=='pwbbs'){
		global $db_attachname;
		if(is_file(PHP168_PATH."$webdb[passport_path]/$db_attachname/thumb/$file")){
			$file="$webdb[passport_url]/$db_attachname/thumb/$file";
		}else{
			$file="$webdb[passport_url]/$db_attachname/$file";
		}
		return $file;
	}elseif($type=='dzbbs'){
		global $_DCACHE;
		$file="$webdb[passport_url]/{$_DCACHE[settings][attachurl]}/$file";
		return $file;
	}elseif( ereg("://",$file) ){
		return $file;
	}elseif($webdb[mirror]&&!file_exists(PHP168_PATH."$webdb[updir]/$file")){	//FTP镜像点
		return $webdb[mirror]."/".$file;
	}else{
		return $webdb[www_url]."/".$webdb[updir]."/".$file;
	}
}

/**
*截取字符
**/
function get_word($string, $length = 80,$more=1 , $etc = '..')
{
	$strcut = '';
	$strLength = 0;
	$width  = 0;
	if(strlen($string) > $length) {
		//��$length�����ʵ��UTF8��ʽ�������ַ����ĳ���
		for($i = 0; $i < $length; $i++) {
			if ( $strLength >= strlen($string) ){
				break;
			}
			if ( $width>=$length){
				break;
			}
			//����⵽һ�������ַ�ʱ
			if( ord($string[$strLength]) > 127 ){
				$strLength += 3;
				$width     += 2;              //��Ű�һ�����ֿ����൱������Ӣ���ַ��Ŀ���
			}else{
				$strLength += 1;
				$width     += 1;
			}
		}
		return substr($string, 0, $strLength).$etc;
	} else {
		return $string;
	}
}



/**
*过滤安全字符
**/
function filtrate($msg){
	//$msg = str_replace('&','&amp;',$msg);
	//$msg = str_replace(' ','&nbsp;',$msg);
	$msg = str_replace('"','&quot;',$msg);
	$msg = str_replace("'",'&#39;',$msg);
	$msg = str_replace("<","&lt;",$msg);
	$msg = str_replace(">","&gt;",$msg);
	$msg = str_replace("\t","   &nbsp;  &nbsp;",$msg);
	//$msg = str_replace("\r","",$msg);
	$msg = str_replace("   "," &nbsp; ",$msg);
	return $msg;
}

/*过滤不健康的字*/
function replace_bad_word($str){
	global $Limitword;
	@include_once(PHP168_PATH."php168/limitword.php");
	foreach( $Limitword AS $old=>$new){
		$str=str_replace($old,"^$new",$str);
	}
	return $str;
}


/**
*取固定图片大小
**/
function pic_size($pic,$w,$h,$url){
	global $updir,$webdb,$N_path;
	$rand=rands(5);
	$show="<script>
			function resizeimage_$rand(obj) {
				var imageObject;
				var MaxW = $w;
				var MaxH = $h;
				imageObject = obj;
				var oldImage = new Image();
				oldImage.src = imageObject.src;
				var dW = oldImage.width;
				originalw=dW;
				var dH = oldImage.height;
				originalh=dH;
				if (dW>MaxW || dH>MaxH) {
					a = dW/MaxW;
					b = dH/MaxH;
					if (b>a) {
						a = b;
					}
					dW = dW/a;
					dH = dH/a;
				}
				if (dW>0 && dH>0) {
					imageObject.width = dW;
					imageObject.height = dH;
				}
			}
			</script>";
	return "$show<a href='$url' target='_blank'><img onload='resizeimage_$rand(this)' src='$pic' border=0 width='$w' height='$h'></a>";
}

/**
*模板相关函数
**/
function html($html,$tpl=''){
	global $STYLE;
	if($tpl&&strstr($tpl,PHP168_PATH)&&file_exists($tpl))
	{
		return $tpl;
	}
	elseif($tpl&&file_exists(PHP168_PATH.$tpl))
	{
		return PHP168_PATH.$tpl;
	}
	elseif(file_exists(PHP168_PATH."template/".$STYLE."/".$html.".htm"))
	{
		return PHP168_PATH."template/".$STYLE."/".$html.".htm";
	}
	elseif(file_exists(PHP168_PATH."template/default/".$html.".htm"))
	{
		return PHP168_PATH."template/default/".$html.".htm";
	}
}

/**
*分页
**/
function getpage($table,$choose,$url,$rows=20,$total=''){
	global $page,$db;
	if(!$page){
		$page=1;
	}
	//当存在$total的时候.就不用再读数据库
	if(!$total && $table){
		$query=$db->get_one("SELECT COUNT(*) AS num  FROM $table $choose");
		$total=$query['num'];
	}
	$totalpage=@ceil($total/$rows);
	$nextpage=$page+1;
	$uppage=$page-1;
	if($nextpage>$totalpage){
		$nextpage=$totalpage;
	}
	if($uppage<1){
		$uppage=1;
	}
	$s=$page-3;
	if($s<1){
		$s=1;
	}
	$b=$s;
	for($ii=0;$ii<6;$ii++){
		$b++;
	}
	if($b>$totalpage){
		$b=$totalpage;
	}
	for($j=$s;$j<=$b;$j++){
		if($j==$page){
			$show.=" <a href='#'><font color=red>$j</font></a>";
		}else{
			$show.=" <a href=\"$url&page=$j\" title=\"第{$j}页\">$j</a>";
		}
	}
	$showpage="<a href=\"$url&page=1\" title=\"首页\">首页</A> <a href=\"$url&page=$uppage\" title=\"上一页\">上一页</A>  {$show}  <a href=\"$url&page=$nextpage\" title=\"下一页\">下一页</A> <a href=\"$url&page=$totalpage\" title=\"尾页\">尾页</A> <a href='#'><font color=red>$page</font>/$totalpage/$total</a>";
    if($totalpage>1){
		return $showpage;
	}
}

/**
*页面跳转函数
**/
function refreshto($url,$msg,$time=1){
	global $webdb;
	if($time==0){
		header("location:$url");
	}else{
		require(PHP168_PATH."template/default/refreshto.htm");
		$content=ob_get_contents();
		ob_end_clean();
		ob_start();
		if($webdb[www_url]=='/.'){
			$content=str_replace('/./','/',$content);
		}
		echo $content;
	}
	exit;
}


/**
*警告页面函数
**/
function showerr($showerrMsg,$type=''){
	require_once(PHP168_PATH."php168/level.php");
	if($type==1){
		$showerrMsg=str_replace("'","\'",$showerrMsg);
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		alert('$showerrMsg');
		if(document.referrer==''&&window.self==window.top){
			window.self.close();
		}else{
			history.back(-1);
		}		
		//-->
		</SCRIPT>";
	}else{
		extract($GLOBALS);
		require(PHP168_PATH."template/default/showerr.htm");
		$content=ob_get_contents();
		ob_end_clean();
		ob_start();
		if($webdb[www_url]=='/.'){
			$content=str_replace('/./','/',$content);
		}
		echo $content;
	}
	exit;
}

 
/**
*取得随机字符
**/
function rands($length,$strtolower=1) {
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	if($strtolower==1){
		$hash=strtolower($hash);
	}
	return $hash;
}

/**
*简体中文转UTF8编码
**/
function gbk2utf8($text) {
	$fp = fopen(PHP168_PATH."inc/gbkcode/gbk2utf8.table","r");
	while(! feof($fp)) {
		list($gb,$utf8) = fgetcsv($fp,10);
		$charset[$gb] = $utf8;
	}
	fclose($fp);		//以上读取对照表到数组备用wl__hd_sg2_02.gif

	//提取文本中的成分，汉字为一个元素，连续的非汉字为一个元素
	preg_match_all("/(?:[\x80-\xff].)|[\x01-\x7f]+/",$text,$tmp);
	$tmp = $tmp[0];
	//分离出汉字
	$ar = array_intersect($tmp, array_keys($charset));
	//替换汉字编码
	foreach($ar as $k=>$v)
    $tmp[$k] = $charset[$v];
	//返回换码后的串
	return join('',$tmp);
}

/**
*各模块的评论
**/
function list_comments($SQL,$which='*',$leng=400){
	global $db,$pre;
	$query=$db->query("SELECT $which FROM `{$pre}comments` $SQL");
	while( $rs=$db->fetch_array($query) ){
		if(!$rs[username]){
			$detail=explode(".",$rs[ip]);
			$rs[username]="$detail[0].$detail[1].$detail[2].*";
		}
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		$rs[content]=get_word($rs[full_content]=$rs[content],$leng);
		$rs[content]=str_replace("\n","<br>",$rs[content]);
		$listdb[]=$rs;
	}
	return $listdb;
}

/*取得表的类型,新版不再使用,兼容旧版BLOG*/
function get_table($type){
	global $pre;
	if($type=="0"||$type=="article")
	{
		$array=array("id"=>"0","sort"=>"{$pre}sort","c"=>"{$pre}article","key"=>"article","name"=>"文章");
	}
	elseif($type=="1"||$type=="log")
	{
		$array=array("id"=>"1","sort"=>"{$pre}log_sort","c"=>"{$pre}log_article","key"=>"log","name"=>"日志");
	}
	elseif($type=="2"||$type=="down"||$type=="download")
	{
		$array=array("id"=>"2","sort"=>"{$pre}down_sort","c"=>"{$pre}down_software","key"=>"down","name"=>"下载");
	}
	elseif($type=="3"||$type=="photo")
	{
		$array=array("id"=>"3","sort"=>"{$pre}photo_sort","c"=>"{$pre}photo_pic","key"=>"photo","name"=>"相片");
	}
	elseif($type=="4"||$type=="mv"||$type=="video")
	{
		$array=array("id"=>"4","sort"=>"{$pre}mv_sort","c"=>"{$pre}mv_video","key"=>"mv","name"=>"视频");
	}
	elseif($type=="5"||$type=="shop")
	{
		$array=array("id"=>"5","sort"=>"{$pre}shop_sort","c"=>"{$pre}shop_product","key"=>"shop","name"=>"商城");
	}
	elseif($type=="6"||$type=="music"||$type=="song")
	{
		$array=array("id"=>"6","sort"=>"{$pre}music_sort","c"=>"{$pre}music_song","key"=>"music","name"=>"音乐");
	}
	elseif($type=="7"||$type=="flash")
	{
		$array=array("id"=>"7","sort"=>"{$pre}flash_sort","c"=>"{$pre}flash_swf","key"=>"flash","name"=>"FLASH");
	}
	return $array;
}


/**
*加密与解密函数
**/
function mymd5($string,$action="EN",$rand=''){ //字符串加密和解密 
	global $webdb;
    $secret_string = $webdb[mymd5].$rand.'5*j,.^&;?.%#@!'; //绝密字符串,可以任意设定 

    if($string=="") return ""; 
    if($action=="EN") $md5code=substr(md5($string),8,10); 
    else{ 
        $md5code=substr($string,-10); 
        $string=substr($string,0,strlen($string)-10); 
    } 
    //$key = md5($md5code.$_SERVER["HTTP_USER_AGENT"].$secret_string);
	$key = md5($md5code.$secret_string); 
    $string = ($action=="EN"?$string:base64_decode($string)); 
    $len = strlen($key); 
    $code = ""; 
    for($i=0; $i<strlen($string); $i++){ 
        $k = $i%$len; 
        $code .= $string[$i]^$key[$k]; 
    } 
    $code = ($action == "DE" ? (substr(md5($code),8,10)==$md5code?$code:NULL) : base64_encode($code)."$md5code"); 
    return $code; 
}

function pwd_md5($code){
	return md5($code);
}


function set_cookie($name,$value,$cktime=0){
	global $webdb;
	if($cktime!=0){
		$cktime=time()+$cktime;
	}
	if($value==''){
		$cktime=time()-31536000;
	}
	$S = $_SERVER['SERVER_PORT'] == '443' ? 1:0;
	if($webdb[cookiePath]){
		$path=$webdb[cookiePath];
	}else{
		$path="/";
	}
	$domain=$webdb[cookieDomain];
	setCookie("$webdb[cookiePre]$name",$value,$cktime,$path,$domain,$S);
}

function get_cookie($name){
	global $webdb,$_COOKIE;
    return $_COOKIE["$webdb[cookiePre]$name"];
}

function add_user($uid,$money){
	global $db,$pre,$timestamp,$webdb,$pre;
	//$db->query(" UPDATE {$pre}memberdata SET money=money+'$webdb[postArticleMoney]' WHERE uid='$uid' ");
	plus_money($uid,$money,$moneytype='');
}


//sock方式打开远程文件
function sockOpenUrl($url,$method='GET',$postValue=''){
	$method = strtoupper($method);
	if(!$url){
		return '';
	}elseif(!ereg("://",$url)){
		$url="http://$url";
	}
	$urldb=parse_url($url);
	$port=$urldb[port]?$urldb[port]:80;
	$host=$urldb[host];
	$query='?'.$urldb[query];
	$path=$urldb[path]?$urldb[path]:'/';
	$method=$method=='GET'?"GET":'POST';

	$fp = fsockopen($host, 80, $errno, $errstr, 30);
	if(!$fp)
	{
		echo "$errstr ($errno)<br />\n";
	}
	else
	{
		$out = "$method $path$query HTTP/1.1\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Cookie: c=1;c2=2\r\n";
		$out .= "Referer: $url\r\n";
		$out .= "Accept: */*\r\n";
		$out .= "Connection: Close\r\n";
		if ( $method == "POST" ) {
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$length = strlen($postValue);
			$out .= "Content-Length: $length\r\n";
			$out .= "\r\n";
			$out .= $postValue;
		}else{
			$out .= "\r\n";
		}
		fwrite($fp, $out);
		while (!feof($fp)) {
			$file.= fgets($fp, 256);
		}
		fclose($fp);
		if(!$file){
			return '';
		}
		$ck=0;
		$string='';
		$detail=explode("\r\n",$file);
		foreach( $detail AS $key=>$value){
			if($value==''){
				$ck++;
				if($ck==1){
					continue;
				}
			}
			if($ck){
				$stringdb[]=$value;
			}
		}
		$string=implode("\r\n",$stringdb);
		//$string=preg_replace("/([\d]+)(.*)0/is","\\2",$string);
		return $string;
	}
}


/*统计附件*/
function get_content_attachment($str){
	global $webdb;
	$rule=str_replace( array(".","/") , array("\.","\/") , $webdb[www_url] );
	preg_match_all("/$rule\/([a-z_\.0-9A-Z]+)\/([a-z_\.\/0-9A-Z=]+)/is",$str,$array);
	$filedb=$array[2];
	if($webdb[mirror]){
		$rule=str_replace( array(".","/") , array("\.","\/") , $webdb[mirror] );
		preg_match_all("/$rule\/([a-z_\.\/0-9A-Z=]+)/is",$str,$array2);
		if( is_array($filedb) ){
			$filedb+=$array2[1];
		}else{
			$filedb=$array2[1];
		}
	}
	return $filedb;
}

/*删除附件*/
function delete_attachment($uid,$str){
	global $webdb,$db,$pre;
	if(!$str||!$uid){
		return ;
	}
	//真实地址还原
	$str=En_TruePath($str,0);

	$filedb=get_content_attachment($str);
	foreach( $filedb AS $key=>$value){
		$name=basename($value);
		$detail=explode("_",$name);
		//获取文件的UID与用户的UID一样时.才删除.不要乱删除
		
		if($detail[0]&&$detail[0]==$uid){
			$turepath=PHP168_PATH.$webdb[updir]."/".$value;
			
			if($rs=$db->get_one("SELECT * FROM {$pre}upfile WHERE filename='$name'")){
				if($rs[num]>1){
					$db->query("UPDATE `{$pre}upfile` SET `num`=`num`-1 WHERE filename='$name'");
					continue;
				}
				$db->query("DELETE FROM `{$pre}upfile` WHERE filename='$name'");
			}
			$size=@filesize($turepath);
			$size && @unlink($turepath);
			//删除FTP上的资源
			if(!$size&&$webdb[ArticleDownloadUseFtp]){
				$value && $size=ftp_delfile($value);
			}
			$db->query(" UPDATE {$pre}memberdata SET usespace=usespace-'$size' WHERE uid='$uid' ");
		}
	}
}

/*移动附件*/
function move_attachment($uid,$str,$newdir,$type=''){
	global $webdb,$db,$pre,$id,$aid,$fid,$timestamp,$webdb,$Fid_db;
	if(!$str||!$uid||!$newdir){
		return $str;
	}
	$_id=$id?$id:$aid;
	//目前仅对文章作处理,新发文章时,设法获取ID
	if(!$webdb[module_id]&&!$_id){
		$erp=$Fid_db[iftable][$fid];
		$rs=$db->get_one("SHOW TABLE STATUS LIKE '{$pre}article$erp'");
		$_id=$rs[Auto_increment];
	}
	$filedb=get_content_attachment($str);
	foreach( $filedb AS $key=>$value){
		$name=basename($value);
		if($rs=$db->get_one("SELECT * FROM {$pre}upfile WHERE filename='$name'")){
			if($_id&&!in_array($_id,explode(",",$rs[ids]))){
				$db->query("UPDATE `{$pre}upfile` SET `num`=`num`+1,ids='$rs[ids],$_id' WHERE filename='$name'");
			}			
			continue;
		}
		$detail=explode("_",$name);
		//获取文件的UID与用户的UID一样时.才删除.不要乱删除
		if($detail[0]&&$detail[0]==$uid){
			$turepath=PHP168_PATH.$webdb[updir]."/".$value;
			if(!is_dir(PHP168_PATH.$webdb[updir]."/".$newdir))
			{
				makepath(PHP168_PATH.$webdb[updir]."/".$newdir);
			}
			//自动缩小太大张的图片
			if($webdb[ArticlePicWidth]&&$webdb[ArticlePicHeight]&&(eregi(".gif$",$turepath)||eregi(".jpg$",$turepath))){
				$img_array=getimagesize($turepath);
				if($img_array[0]>$webdb[ArticlePicWidth]||$img_array[1]>$webdb[ArticlePicHeight]){
					gdpic($turepath,$turepath,$webdb[ArticlePicWidth],$webdb[ArticlePicHeight],1);
				}
			}
			//图片加水印
			if($type!='small'&&$webdb[is_waterimg]&&(eregi(".gif$",$turepath)||eregi(".jpg$",$turepath)))
			{
				include_once(PHP168_PATH."inc/waterimage.php");
				imageWaterMark($turepath,$webdb[waterpos],PHP168_PATH.$webdb[waterimg]);
			}
			if( @rename($turepath,PHP168_PATH.$webdb[updir]."/$newdir/$name") )
			{
				$str=str_replace("$value","$newdir/$name",$str);
				$db->query("INSERT INTO `{$pre}upfile` ( `module_id` , `ids` , `fid` , `uid` , `posttime` , `url` , `filename` , `num` ) VALUES ('$webdb[module_id]','$_id','$fid','$uid','$timestamp','$newdir/$name','$name','1')");
			}
		}
	}
	return $str;
}

//对真实地址做处理
function En_TruePath($content,$type=1,$ifgetplayer=0){
	global $webdb;
	if($type==1)
	{
		//使用了远程附件镜像,要做特别处理,不局限于使用FTP
		if($webdb[mirror]){
			$content=str_replace("$webdb[mirror]","http://www_php168_com/Tmp_updir",$content);
		}
		$content=str_replace("$webdb[www_url]/$webdb[updir]","http://www_php168_com/Tmp_updir",$content);		
		$content=str_replace("$webdb[www_url]","http://www_php168_com",$content);
	}
	else
	{
		//使用了远程附件镜像,要做特别处理,不局限于使用FTP
		if($webdb[mirror]){
			$content=preg_replace("/http:\/\/www_php168_com\/Tmp_updir\/([-_=\/\.A-Za-z0-9]+)/eis","tempdir('\\1')",$content);
		}else{
			$content=str_replace("http://www_php168_com/Tmp_updir","$webdb[www_url]/$webdb[updir]",$content);
		}		
		$content=str_replace("http://www_php168_com","$webdb[www_url]",$content);
		if($ifgetplayer){
			$content=preg_replace("/\[(mp3|flv|wmv|flash|rmvb),([\d]+),([\d]+)\]([^\[]+)\[\/(mp3|flv|wmv|flash|rmvb)\]/eis","player('\\4','\\2','\\3','true','\\1')",$content);
		}
		//自动补全一些不对称的TABLE,TD,DIV标签
		//$content=check_html_format($content);
	}
	return $content;
}

function Get_SonFid($table,$fid=0){
	global $db;
	$query = $db->query("SELECT fid,sons FROM $table WHERE fup=$fid");
	while($rs = $db->fetch_array($query)){
		if($rs[sons]){
			$array2=Get_SonFid($table,$rs[fid]);
			if($array2){
				foreach( $array2 AS $key=>$value){
					$array[]=$value;
				}
			}
		}
		$array[]=$rs[fid];
	}
	return $array;
}

//静态网页处理
function Explain_HtmlUrl(){
	global $fid,$aid,$id,$page,$WEBURL;
	$detail=explode("fid-",$WEBURL);
	$detail2=explode(".",$detail[1]);
	$rs=explode("-",$detail2[0]);
	$fid=$rs[0];					//LIST页的fid,bencandy页的fid
	$rs[1] && $$rs[1]=$rs[2];		//可能是LIST页的PAGE,也可能是bencandy页的id
	$rs[3] && $$rs[3]=$rs[4];		//bencandy页的page
}


//获取用户积分
function get_money($uid,$moneytype=''){
	global $db,$pre,$_pre,$webdb,$TB_pre,$lfjdb;
	
	if($moneytype=='')
	{
		$moneytype='money';
	}

	if( $webdb[UseMoneyType]=='bbs'&&$webdb[passport_type] )
	{
		if( eregi("^pwbbs",$webdb[passport_type]) )
		{
			$rs=$db->get_one("SELECT * FROM {$TB_pre}memberdata WHERE uid='$uid'");
			return $rs[$moneytype];
		}
		elseif( eregi("^dzbbs",$webdb[passport_type]) )
		{
			$rs=$db->get_one("SELECT * FROM {$TB_pre}members WHERE uid='$uid'");
			return $rs[extcredits2];
		}
	}
	else
	{
		if($lfjdb[uid]==$uid)
		{
			return $lfjdb[money];
		}
		else
		{
			$rs=$db->get_one("SELECT * FROM {$pre}memberdata WHERE uid='$uid'");
			return $rs[money];
		}
	}
}

//增减用户积分
function plus_money($uid,$money,$moneytype=''){
	global $db,$pre,$_pre,$webdb,$TB_pre,$lfjdb;

	if($moneytype=='')
	{
		$moneytype='money';
	}

	if( $webdb[UseMoneyType]=='bbs' )
	{
		if( eregi("^pwbbs",$webdb[passport_type]) )
		{
			$db->query("UPDATE {$TB_pre}memberdata SET $moneytype=$moneytype+'$money' WHERE uid='$uid'");
		}
		elseif( eregi("^dzbbs",$webdb[passport_type]) )
		{
			$db->query("UPDATE {$TB_pre}members SET extcredits2=extcredits2+'$money' WHERE uid='$uid'");
		}
	}
	else
	{
		$db->query("UPDATE {$pre}memberdata SET money=money+'$money' WHERE uid='$uid'");
	}
}

/*页面显示,强制过滤关键字*/
function kill_badword($content){
	global $webdb,$Limitword;
	if($webdb[kill_badword])
	{
		if(!$content)
		{
			$content=@ob_get_contents();
			$ck++;
		}
		
		@include_once(PHP168_PATH."php168/limitword.php");

		foreach( $Limitword AS $key=>$value){
			$content=str_replace($key,$value,$content);
		}
		if($ck)
		{
			ob_end_clean();
			ob_start();
			echo $content;
		}
		else
		{
			return $content;
		}
	}
	else
	{
		return $content;
	}
}

//发站内消息
function pm_msgbox($array){
	global $db,$pre,$timestamp,$webdb,$TB_pre,$TB,$userDB;
	if( ereg("^pwbbs",$webdb[passport_type]) )
	{
		if(strlen($array[title])>130){
			showerr("标题不能大于65个汉字");
		}
		if(is_table("{$TB_pre}msgc")){
			$db->query("INSERT INTO {$TB_pre}msg (`touid`,`fromuid`, `username`, `type`, `ifnew`, `mdate`) VALUES ('$array[touid]','$array[fromuid]', '$array[fromer]', 'rebox', '1', '$timestamp')");
			$mid=$db->insert_id();
			$db->query("INSERT INTO {$TB_pre}msgc (`mid`, `title`, `content`) VALUES ('$mid','$array[title]','$array[content]')");
		}else{
			$db->query("INSERT INTO {$TB_pre}msg (`touid`,`fromuid`, `username`, `type`, `ifnew`, `title`, `mdate`, `content`) VALUES ('$array[touid]','$array[fromuid]', '$array[fromer]', 'rebox', '1', '$array[title]', '$timestamp', '$array[content]')");
		}
		$array=array(
				'uid'=>$array[touid],
				'newpm'=>1
			);
		$userDB->edit_pw_member($array);
	}
	elseif(defined("UC_CONNECT"))
	{
		if(strlen($array[title])>75){
			showerr("标题不能大于32个汉字");
		}
		uc_pm_send('$array[fromuid]','$array[touid]','$array[title]','$array[content]',1,0,1);
	}
	else
	{
		if(strlen($array[title])>130){
			showerr("标题不能大于65个汉字");
		}
		$db->query("INSERT INTO `{$pre}pm` (`touid`,`fromuid`, `username`, `type`, `ifnew`, `title`, `mdate`, `content`) VALUES ('$array[touid]','$array[fromuid]', '$array[fromer]', 'rebox', '1', '$array[title]', '$timestamp', '$array[content]')");
	}
}

//删除文章的函数
function delete_article($aid,$rid,$forcedel=0){
	global $db,$pre,$webdb;
	if(!$aid){
		showerr("id不存在");
	}
	$erp=get_id_table($aid);
	if($rid){
		$rsdb=$db->get_one("SELECT R.*,A.* FROM {$pre}article$erp A LEFT JOIN {$pre}reply$erp R ON A.aid=R.aid WHERE R.rid='$rid'");
	}elseif($aid){
		$rsdb=$db->get_one("SELECT R.*,A.* FROM {$pre}article$erp A LEFT JOIN {$pre}reply$erp R ON A.aid=R.aid WHERE A.aid='$aid' ORDER BY R.rid ASC LIMIT 1");
		if(!$rsdb[rid]){
			$db->query("DELETE FROM {$pre}article$erp WHERE aid='$aid'");
			$db->query("DELETE FROM {$pre}article_db WHERE aid='$aid'");
			$db->query("DELETE FROM {$pre}fu_article WHERE aid='$aid'");
		}
	}
	if(!$rsdb){
		return ;
	}
	if($rsdb[topic]){
		if($forcedel||$webdb[ForceDel]){
			if($rsdb[picurl]){
				delete_attachment( $rsdb[uid],tempdir($rsdb[picurl]) );
				delete_attachment( $rsdb[uid],tempdir("$rsdb[picurl].jpg") );
				delete_attachment( $rsdb[uid],tempdir("$rsdb[picurl].jpg.jpg") );
			}
			$query = $db->query("SELECT * FROM {$pre}reply$erp WHERE aid='$rsdb[aid]'");
			while($rs = $db->fetch_array($query)){
				delete_attachment($rs[uid],$rs[content]);
			}
			if($rsdb[mid]){
				$r2=$db->get_one("SELECT * FROM {$pre}article_content_$rsdb[mid] WHERE aid='$rsdb[aid]'");
				//删除附件
				if($rsdb[mid]==100||$rsdb[mid]==101||$rsdb[mid]==102){	//删除图片,软件,视频
					if($rsdb[mid]==100){
						$string=$r2[photourl];
					}elseif($rsdb[mid]==101){
						$string=$r2[softurl];
					}elseif($rsdb[mid]==102){
						$string=$r2[mvurl];
					}
					$string=str_replace("\r","",$string);
					$detail=explode("\n",$string);
					foreach($detail AS $value){
						$d=explode("@@@",$value);
						delete_attachment($rsdb[uid],tempdir($d[0]));
					}
				}elseif($rsdb[mid]==104){	//删除FLASH
					$d=explode("@@@",$r2[flashurl]);
					delete_attachment($rsdb[uid],tempdir($d[0]));
				}
				$db->query("DELETE FROM {$pre}article_content_$rsdb[mid] WHERE aid='$rsdb[aid]'");
			}
			$db->query("DELETE FROM `{$pre}collection` WHERE aid='$rsdb[aid]' ");
			$db->query("DELETE FROM `{$pre}article$erp` WHERE aid='$rsdb[aid]' ");
			$db->query("DELETE FROM `{$pre}article_db` WHERE aid='$rsdb[aid]' ");
			$db->query("DELETE FROM `{$pre}reply$erp` WHERE aid='$rsdb[aid]' ");
			$db->query("DELETE FROM `{$pre}comment` WHERE aid='$rsdb[aid]' ");
			$db->query("DELETE FROM `{$pre}report` WHERE aid='$rsdb[aid]' ");
			$db->query("DELETE FROM `{$pre}fu_article` WHERE aid='$rsdb[aid]'");
			//财富处理
			Give_article_money($rsdb[uid],'del');
			if($rsdb[levels]){
				Give_article_money($rsdb[uid],'uncom');
			}
			//删除关键字
			keyword_del($rsdb[aid],$rsdb[keywords]);
		}else{
			$db->query("UPDATE {$pre}article$erp SET yz=2 WHERE aid='$rsdb[aid]'");
		}
	}else{
		$db->query("DELETE FROM {$pre}reply$erp WHERE rid='$rsdb[rid]'");
		delete_attachment($rsdb[uid],$rsdb[content]);
		if($rsdb[mid]){
			$db->query("DELETE FROM {$pre}article_content_$rsdb[mid] WHERE rid='$rsdb[rid]'");
		}
		$db->query("UPDATE {$pre}article$erp SET pages=pages-1 WHERE aid='$rsdb[aid]'");
	}
	//删除缓存文件
	delete_cache_file($rsdb[fid],$rsdb[aid]);
}

//主要是给发表文章或修改文章时调用
function get_html_url(){
	global $rsdb,$aid,$fidDB,$webdb,$fid,$page,$showHtml_Type,$Html_Type;
	$id=$aid;
	if($page<1){
		$page=1;
	}
	$postdb[posttime]=$rsdb[posttime];
	
	if($showHtml_Type[bencandy][$id]){
		$filename_b=$showHtml_Type[bencandy][$id];
	}elseif($fidDB[bencandy_html]){
		$filename_b=$fidDB[bencandy_html];
	}else{
		$filename_b=$webdb[bencandy_filename];
	}
	//对于内容页的首页把$page去除
	if($page==1){
		$filename_b=preg_replace("/(.*)(-{\\\$page}|_{\\\$page})(.*)/is","\\1\\3",$filename_b);
	}
	$dirid=floor($aid/1000);
	//对于内容页的栏目小于1000篇文章时,把DIR分目录去除
	if($dirid==0){
		$filename_b=preg_replace("/(.*)(-{\\\$dirid}|_{\\\$dirid})(.*)/is","\\1\\3",$filename_b);
	}
	if(strstr($filename_b,'$time_')){
		$time_Y=date("Y",$postdb[posttime]);
		$time_y=date("y",$postdb[posttime]);
		$time_m=date("m",$postdb[posttime]);
		$time_d=date("d",$postdb[posttime]);
		$time_W=date("W",$postdb[posttime]);
		$time_H=date("H",$postdb[posttime]);
		$time_i=date("i",$postdb[posttime]);
		$time_s=date("s",$postdb[posttime]);
	}
	if($fidDB[list_html]){
		$filename_l=$fidDB[list_html];
	}else{
		$filename_l=$webdb[list_filename];
	}	
	if($page==1){
		if($webdb[DefaultIndexHtml]==1){
			$filename_l=preg_replace("/(.*)\/([^\/]+)/is","\\1/index.html",$filename_l);
		}else{
			$filename_l=preg_replace("/(.*)\/([^\/]+)/is","\\1/index.htm",$filename_l);
		}
	}
	eval("\$array[_showurl]=\"$filename_b\";");
	eval("\$array[_listurl]=\"$filename_l\";");
	//自定义了栏目域名
	if($Html_Type[domain][$fid]&&$Html_Type[domain_dir][$fid]){
		$rule=str_replace("/","\/",$Html_Type[domain_dir][$fid]);
		$filename_b=preg_replace("/^$rule/is","{$Html_Type[domain][$fid]}/",$filename_b);
		$filename_l=preg_replace("/^$rule/is","{$Html_Type[domain][$fid]}/",$filename_l);
		//特别处理一下些自定义内容页文件名的情况.
		if(!eregi("^http:\/\/",$filename_b)){
			$filename_b="$webdb[www_url]/$filename_b";
		}
	}else{
		$filename_b="$webdb[www_url]/$filename_b";
		$filename_l="$webdb[www_url]/$filename_l";
	}

	eval("\$array[showurl]=\"$filename_b\";");
	eval("\$array[listurl]=\"$filename_l\";");
	return $array;
}

//获取专题页的静态URL地址
function get_SPhtml_url($fidDB,$id='',$posttime=''){
	global $webdb,$showHtml_Type,$Html_Type;
	$page=1;
	$fid=$fidDB[fid];
	$postdb[posttime]=$posttime;
	
	if($showHtml_Type[SPbencandy][$id]){
		$filename_b=$showHtml_Type[SPbencandy][$id];
	}elseif($fidDB[bencandy_html]){
		$filename_b=$fidDB[bencandy_html];
	}else{
		$filename_b=$webdb[SPbencandy_filename];
	}
	if(strstr($filename_b,'$time_')){
		$time_Y=date("Y",$postdb[posttime]);
		$time_y=date("y",$postdb[posttime]);
		$time_m=date("m",$postdb[posttime]);
		$time_d=date("d",$postdb[posttime]);
		$time_W=date("W",$postdb[posttime]);
		$time_H=date("H",$postdb[posttime]);
		$time_i=date("i",$postdb[posttime]);
		$time_s=date("s",$postdb[posttime]);
	}
	if($fidDB[list_html]){
		$filename_l=$fidDB[list_html];
	}else{
		$filename_l=$webdb[SPlist_filename];
	}
	$filename_b="$webdb[www_url]/$filename_b";
	$filename_l="$webdb[www_url]/$filename_l";
	eval("\$array[showurl]=\"$filename_b\";");
	eval("\$array[listurl]=\"$filename_l\";");
	return $array;
}

function Remind_msg($MSG){
	global $rsdb;
	$rsdb[content].= "<SCRIPT LANGUAGE='JavaScript'>
	<!--
	alert('$MSG');
	//-->
	</SCRIPT>";
}

function make_module_cache(){
	global $db,$pre;
	$show="<?php\r\n";
	$query = $db->query("SELECT * FROM {$pre}module ORDER BY list DESC");
	while($rs = $db->fetch_array($query))
	{
		$rs[name]=addslashes($rs[name]);
		
		$rs[config]=str_replace("'","\'",$rs[config]);
		$rs[name]=str_replace("'","\'",$rs[name]);

		$show.="
			\$ModuleDB['{$rs[pre]}']=array('name'=>'$rs[name]',
				'dirname'=>'$rs[dirname]',
				'domain'=>'$rs[domain]',
				'admindir'=>'$rs[admindir]',
				'type'=>'$rs[type]',
				'config'=>'$rs[config]',
				'adminmember'=>'$rs[adminmember]',
				'unite_member'=>'$rs[unite_member]',
				'id'=>'$rs[id]'
			);
			";
	}
	write_file(PHP168_PATH."php168/module.php",$show);
}

//获取浏览器类型
function browseinfo() {
	$browser="";
	$browserver="";
	$Browsers =array("Lynx","MOSAIC","AOL","Opera","JAVA","MacWeb","WebExplorer","OmniWeb");
	$Agent = $_SERVER["HTTP_USER_AGENT"]?$_SERVER["HTTP_USER_AGENT"]:$HTTP_SERVER_VARS["HTTP_USER_AGENT"];
	for ($i=0; $i<=7; $i++) {
		if (strpos($Agent,$Browsers[$i])) {
			$browser = $Browsers[$i];
			$browserver ="";
		}
	}
	if (ereg("Mozilla",$Agent) && !ereg("MSIE",$Agent)) {
		$temp =explode("(", $Agent); $Part=$temp[0];
		$temp =explode("/", $Part); $browserver=$temp[1];
		$temp =explode(" ",$browserver); $browserver=$temp[0];
		$browserver =preg_replace("/([\d\.]+)/","\\1",$browserver);
		$browserver = " $browserver";
		$browser = "Netscape Navigator";
	}
	if (ereg("Mozilla",$Agent) && ereg("Opera",$Agent)) {
		$temp =explode("(", $Agent); $Part=$temp[1];
		$temp =explode(")", $Part); $browserver=$temp[1];
		$temp =explode(" ",$browserver);$browserver=$temp[2];
		$browserver =preg_replace("/([\d\.]+)/","\\1",$browserver);
		$browserver = " $browserver";
		$browser = "Opera";
	}
	if (ereg("Mozilla",$Agent) && ereg("MSIE",$Agent)) {
		$temp = explode("(", $Agent); $Part=$temp[1];
		$temp = explode(";",$Part); $Part=$temp[1];
		$temp = explode(" ",$Part);$browserver=$temp[2];
		$browserver =preg_replace("/([\d\.]+)/","\\1",$browserver);
		$browserver = " $browserver";
		$browser = "IE";
	}
	if ($browser!="") {
		$browseinfo = "$browser$browserver";
	}else {
		$browseinfo = "未知的浏览器";
	}
	return $browseinfo;
}

//获取操作系统类型
function osinfo() {
	$os="";
	$Agent =$_SERVER["HTTP_USER_AGENT"]?$_SERVER["HTTP_USER_AGENT"]:$HTTP_SERVER_VARS["HTTP_USER_AGENT"];
	if (eregi('win',$Agent) && strpos($Agent, '95')) {
		$os="Windows 95";
	}elseif (eregi('win 9x',$Agent) && strpos($Agent, '4.90')) {
		$os="Windows ME";
	}elseif (eregi('win',$Agent) && ereg('98',$Agent)) {
		$os="Windows 98";
	}elseif (eregi('win',$Agent) && eregi('nt 5\.0',$Agent)) {
		$os="Windows 2000";
	}elseif (eregi('win',$Agent) && eregi('nt 5\.1',$Agent)) { 
		$os="Windows XP"; 
	}elseif (eregi('win',$Agent) && eregi('nt',$Agent)) {
		$os="Windows NT";
	}elseif (eregi('win',$Agent) && ereg('32',$Agent)) {
		$os="Windows 32";
	}elseif (eregi('linux',$Agent)) {
		$os="Linux";
	}elseif (eregi('unix',$Agent)) {
		$os="Unix";
	}elseif (eregi('sun',$Agent) && eregi('os',$Agent)) {
		$os="SunOS";
	}elseif (eregi('ibm',$Agent) && eregi('os',$Agent)) {
		$os="IBM OS/2";
	}elseif (eregi('Mac',$Agent) && eregi('PC',$Agent)) {
		$os="Macintosh";
	}elseif (eregi('PowerPC',$Agent)) {
		$os="PowerPC";
	}elseif (eregi('AIX',$Agent)) {
		$os="AIX";
	}elseif (eregi('HPUX',$Agent)) {
		$os="HPUX";
	}elseif (eregi('NetBSD',$Agent)) {
		$os="NetBSD";
	}elseif (eregi('BSD',$Agent)) {
		$os="BSD";
	}elseif (ereg('OSF1',$Agent)) {
		$os="OSF1";
	}elseif (ereg('IRIX',$Agent)) {
		$os="IRIX";
	}elseif (eregi('FreeBSD',$Agent)) {
		$os="FreeBSD";
	}
	if ($os=='') $os = "Unknown";
	return $os;
}

function ipfrom($ip) {
	if(!preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
		return '';
	}
	if( !is_file(PHP168_PATH.'inc/ip.dat') ){
		return '<a title><A HREF="http://down2.php168.com/ip.rar" title="点击下载后,解压放到整站/inc/目录即可">IP库不存在,请点击下载一个!</A></a>';
	}
	if($fd = @fopen(PHP168_PATH.'inc/ip.dat', 'rb')) {

		$ip = explode('.', $ip);
		$ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];

		$DataBegin = fread($fd, 4);
		$DataEnd = fread($fd, 4);
		$ipbegin = implode('', unpack('L', $DataBegin));
		if($ipbegin < 0) $ipbegin += pow(2, 32);
		$ipend = implode('', unpack('L', $DataEnd));
		if($ipend < 0) $ipend += pow(2, 32);
		$ipAllNum = ($ipend - $ipbegin) / 7 + 1;

		$BeginNum = 0;
		$EndNum = $ipAllNum;

		while($ip1num > $ipNum || $ip2num < $ipNum) {
			$Middle= intval(($EndNum + $BeginNum) / 2);

			fseek($fd, $ipbegin + 7 * $Middle);
			$ipData1 = fread($fd, 4);
			if(strlen($ipData1) < 4) {
				fclose($fd);
				return '- System Error';
			}
			$ip1num = implode('', unpack('L', $ipData1));
			if($ip1num < 0) $ip1num += pow(2, 32);

			if($ip1num > $ipNum) {
				$EndNum = $Middle;
				continue;
			}

			$DataSeek = fread($fd, 3);
			if(strlen($DataSeek) < 3) {
				fclose($fd);
				return '- System Error';
			}
			$DataSeek = implode('', unpack('L', $DataSeek.chr(0)));
			fseek($fd, $DataSeek);
			$ipData2 = fread($fd, 4);
			if(strlen($ipData2) < 4) {
				fclose($fd);
				return '- System Error';
			}
			$ip2num = implode('', unpack('L', $ipData2));
			if($ip2num < 0) $ip2num += pow(2, 32);

			if($ip2num < $ipNum) {
				if($Middle == $BeginNum) {
					fclose($fd);
					return '- Unknown';
				}
				$BeginNum = $Middle;
			}
		}

		$ipFlag = fread($fd, 1);
		if($ipFlag == chr(1)) {
			$ipSeek = fread($fd, 3);
			if(strlen($ipSeek) < 3) {
				fclose($fd);
				return '- System Error';
			}
			$ipSeek = implode('', unpack('L', $ipSeek.chr(0)));
			fseek($fd, $ipSeek);
			$ipFlag = fread($fd, 1);
		}

		if($ipFlag == chr(2)) {
			$AddrSeek = fread($fd, 3);
			if(strlen($AddrSeek) < 3) {
				fclose($fd);
				return '- System Error';
			}
			$ipFlag = fread($fd, 1);
			if($ipFlag == chr(2)) {
				$AddrSeek2 = fread($fd, 3);
				if(strlen($AddrSeek2) < 3) {
					fclose($fd);
					return '- System Error';
				}
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
				fseek($fd, $AddrSeek2);
			} else {
				fseek($fd, -1, SEEK_CUR);
			}

			while(($char = fread($fd, 1)) != chr(0))
				$ipAddr2 .= $char;

			$AddrSeek = implode('', unpack('L', $AddrSeek.chr(0)));
			fseek($fd, $AddrSeek);

			while(($char = fread($fd, 1)) != chr(0))
				$ipAddr1 .= $char;
		} else {
			fseek($fd, -1, SEEK_CUR);
			while(($char = fread($fd, 1)) != chr(0))
				$ipAddr1 .= $char;

			$ipFlag = fread($fd, 1);
			if($ipFlag == chr(2)) {
				$AddrSeek2 = fread($fd, 3);
				if(strlen($AddrSeek2) < 3) {
					fclose($fd);
					return '- System Error';
				}
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
				fseek($fd, $AddrSeek2);
			} else {
				fseek($fd, -1, SEEK_CUR);
			}
			while(($char = fread($fd, 1)) != chr(0))
				$ipAddr2 .= $char;
		}
		fclose($fd);

		if(preg_match('/http/i', $ipAddr2)) {
			$ipAddr2 = '';
		}
		$ipaddr = "$ipAddr1 $ipAddr2";
		$ipaddr = preg_replace('/CZ88\.NET/is', '', $ipaddr);
		$ipaddr = preg_replace('/^\s*/is', '', $ipaddr);
		$ipaddr = preg_replace('/\s*$/is', '', $ipaddr);
		if(preg_match('/http/i', $ipaddr) || $ipaddr == '') {
			$ipaddr = '- Unknown';
		}
		return ''.$ipaddr;
	}
}

function ftp_upfile($source,$file,$ifdel=1){
	global $webdb;
	if(!$webdb[FtpHost]||!$webdb[FtpName]||!$webdb[FtpPwd]||!$webdb[FtpPort]||!$webdb[FtpDir]){
		return ;
	}
	require_once(PHP168_PATH."inc/ftp.php");
	$ftp = new FTP($webdb[FtpHost],$webdb[FtpPort],$webdb[FtpName],$webdb[FtpPwd],$webdb[FtpDir]);
	$path=dirname($file);
	$detail=explode("/",$path);
	//$pathname=$webdb[FtpDir];
	foreach( $detail AS $key=>$value){
		$pathname.="$value/";
		if(!$ftp->dir_exists($pathname)){
			$ftp->mkd($pathname);
		}
	}
	$ifput=$ftp->upload($source,$file);
	$ftp->close();
	if($ifput){
		$ifdel && unlink($source);
		return "$webdb[mirror]/$file";
	}else{
		return "$webdb[www_url]/$webdb[updir]/$file";
	}
}

function ftp_delfile($file){
	global $webdb;
	if(!$webdb[FtpHost]||!$webdb[FtpName]||!$webdb[FtpPwd]||!$webdb[FtpPort]||!$webdb[FtpDir]){
		return ;
	}
	require_once(PHP168_PATH."inc/ftp.php");
	$ftp = new FTP($webdb[FtpHost],$webdb[FtpPort],$webdb[FtpName],$webdb[FtpPwd],$webdb[FtpDir]);
	$size = $ftp->size($file,0);
	$ftp->delete($file);
	$ftp->close();
	return $size;
}

function sms_send($mob,$content){
	global $webdb;
	if($webdb[sms_type]=='eshang8'){
		$url="http://sms.eshang8.cn/api/?esname=$webdb[sms_es_name]&key=$webdb[sms_es_key]&msg=$content&phone=$mob&smskind=1";
		if( !$msg=sockOpenUrl($url) ){
			//$msg=file_get_contents($url);
		}
		if($msg===''){
			return 0;
		}elseif($msg==='0'){
			return 1;			//发送成功
		}else{
			return $msg;
		}
	}elseif($webdb[sms_type]=='winic'){
		$url="http://service.winic.org/sys_port/gateway/?id=$webdb[sms_wi_id]&pwd=$webdb[sms_wi_pwd]&to=$mob&content=$content&time=";
		if( !$msg=sockOpenUrl($url) ){
			//$msg=file_get_contents($url);
		}
		if($msg===''){
			return 0;
		}
		$detail=explode("/",$msg);
		if($detail[0]==='000'){
			return 1;			//发送成功
		}else{
			return $detail[0];
		}
	}else{
		showerr("系统没有选择短信接口平台!");
	}
}


/**
自定义模型当中,获取这三个select,radio,checkbox表单中类似“
1|中国
2|美国
”真实值
**/
function SRC_true_value($rs,$rsdb_v){
	if($rs[form_type]=='radio'||$rs[form_type]=='select'){
		$detail=explode("\r\n",$rs[form_set]);
		foreach( $detail AS $_key=>$value){
			list($v1,$v2)=explode("|",$value);
			if($v1==$rsdb_v&&$v2){
				$rsdb_v=$v2;
				break;
			}
		}
	}elseif($rs[form_type]=='checkbox'){
		$detail2=explode("/",$rsdb_v);
		$detail=explode("\r\n",$rs[form_set]);
		foreach( $detail AS $_key=>$value){
			list($v1,$v2)=explode("|",$value);
			if(in_array($v1,$detail2)&&$v2){
				foreach( $detail2 AS $key2=>$value2){
					if($value2==$v1){
						$detail2[$key2]=$v2;
						break;
					}
				}
			}
		}
		$rsdb_v=implode(" , ",$detail2);
	}
	return $rsdb_v;
}

//自定义内容页文件名缓存生成
function get_showhtmltype(){
	global $db,$pre,$Fid_db;
	$query = $db->query("SELECT aid,htmlname FROM {$pre}article WHERE htmlname!=''");
	while($rs = $db->fetch_array($query)){
		$show.="\$showHtml_Type[bencandy][{$rs[aid]}]='$rs[htmlname]';\r\n";
	}
	foreach( $Fid_db[iftable] AS $key=>$erp){
		$query = $db->query("SELECT aid,htmlname FROM {$pre}article$erp WHERE htmlname!=''");
		while($rs = $db->fetch_array($query)){
			$show.="\$showHtml_Type[bencandy][{$rs[aid]}]='$rs[htmlname]';\r\n";
		}
	}
	write_file(PHP168_PATH."php168/showhtmltype.php","<?php\r\n".$show.'?>');
}

//用户登录
function user_login($username,$password,$cookietime){
	global $userDB;
	$rs = $userDB->login($username,$password,$cookietime);
	return $rs;
}

//获取UNIX时间,主要是特别处理变成整数.不加引号08与8会不一样的结果,加引号是正常的
function mk_time($h,$i, $s, $m, $d, $y){
	$time=@mktime(intval($h),intval($i),intval($s),intval($m),intval($d),intval($y));
	return $time;
}


//检测某个关键字是否包含在数组里边
function ifin_array($array,$filename,$ISin=''){
	foreach($array as $key=>$value){
		if(!is_array($value)){
			if(strstr($value,$filename)){
				$ISin=1;
				break;
			}
		}elseif(!$ISin){
			$ISin=ifin_array($array[$key],$filename,$ISin);
		}
	}
	return $ISin;
}


/*讯雷联盟*/
function Thunder_Encode($url) 
{
	$thunderPrefix="AA";
	$thunderPosix="ZZ";
	$thunderTitle="thunder://";
	$thunderUrl=$thunderTitle.base64_encode($thunderPrefix.$url.$thunderPosix);
	return $thunderUrl;
}


/*快车联盟*/
function Flashget_Encode($t_url,$uid) 
{
	$prefix= "Flashget://";
	$FlashgetURL=$prefix.base64_encode("[FLASHGET]".$t_url."[FLASHGET]")."&".$uid;
	return $FlashgetURL;
}

//播放器
function player($url,$width=400,$height=300,$autostart='false',$force=''){
	global $webdb;
	//$urlstring=mymd5($url);
	$urlstring=urlencode($url);
	$string="
<SCRIPT LANGUAGE='JavaScript' src='$webdb[www_url]/do/job.php?job=playurl&urlstring=$urlstring'></SCRIPT>
<SCRIPT LANGUAGE=\"JavaScript\">
p8_player(playurl,'$width','$height','$force','$autostart');
</SCRIPT>
";
	return $string;
}


//自动补全一些不对称的TABLE,TD,DIV标签
function check_html_format($string){
	preg_match_all("/<div([^>]*)>/",$string,$array0);
	preg_match_all("/<\/div>/",$string,$array1);
	$num0=count($array0[0]);
	$num1=count($array1[0]);
	$divNUM=abs($num0-$num1);
	for($i=0;$i<$divNUM;$i++){
		if($num0>$num1){
			$string.="</div>";
		}else{
			$string="<div>$string";
		}
		break;
	}
	preg_match_all("/<td([^>]*)>/",$string,$array0);
	preg_match_all("/<\/td>/",$string,$array1);
	$num0=count($array0[0]);
	$num1=count($array1[0]);
	$tdNUM=abs($num0-$num1);
	for($i=0;$i<$tdNUM;$i++){
		if($num0>$num1){
			$string.="</td>";
		}else{
			$string="<td>$string";
		}
		break;
	}
	preg_match_all("/<table([^>]*)>/",$string,$array0);
	preg_match_all("/<\/table>/",$string,$array1);
	$num0=count($array0[0]);
	$num1=count($array1[0]);
	$tableNUM=abs($num0-$num1);
	for($i=0;$i<$tableNUM;$i++){
		if($num0>$num1){
			$string.="</table>";
		}else{
			$string="<table>$string";
		}
		break;
	}
	if($tdNUM>1||$tdNUM>1||$tableNUM>1){
		$string=check_html_format($string);
	}
	return $string;
}

function get_id_table($id){
	global $Fid_db;
	if(strlen($id)<9){
		return ;
	}
	if(!$Fid_db){
		@include(PHP168_PATH."php168/all_fid.php");
	}	
	$tableid=preg_replace("/([0-9]{3})([0-9]{6})/is","\\1",$id);
	$tableid=intval($tableid);
	if(in_array($tableid,$Fid_db[iftable])){
		return $tableid;
	}	
}

function get_one_article($id){
	global $db,$pre;
	$erp=get_id_table($id);
	$rs=$db->get_one("SELECT * FROM {$pre}article$erp WHERE aid='$id'");
	return $rs;
}

function delete_cache_file($fid,$id){
	del_file(PHP168_PATH."cache/jsarticle_cache");
	del_file(PHP168_PATH."cache/label_cache");
	del_file(PHP168_PATH."cache/list_cache");
	del_file(PHP168_PATH."cache/bencandy_cache");
	del_file(PHP168_PATH."cache/showsp_cache");
}
?>