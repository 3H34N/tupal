<?php
error_reporting(E_ALL);
define('PHP168_PATH', __FILE__ ? dirname(__FILE__).'/' : './');
if(file_exists(PHP168_PATH . 'cache/install.php')) header("Location: ./");
@set_time_limit(0);
set_magic_quotes_runtime(0);
if(!get_magic_quotes_gpc()){
	Add_S($_POST);
	Add_S($_GET);
	Add_S($_COOKIE);
}
if(!ini_get('register_globals')){	
	@extract($_COOKIE,EXTR_SKIP);
	@extract($_FILES,EXTR_SKIP);
}
foreach($_POST as $_key=>$_value){
	!ereg("^\_",$_key) && $$_key=$_POST[$_key];
}
foreach($_GET as $_key=>$_value){
	!ereg("^\_",$_key) && $$_key=$_GET[$_key];
}
function Add_S(&$array){
	foreach($array as $key=>$value){
		if(!is_array($value)){
			$array[$key]=addslashes($value);
		}else{
			Add_S($array[$key]);
		}
	}
}

$default_admin='admin';
$default_weburl='http://v6.com/';
$dbcharset = 'utf8';

if($_SERVER['HTTP_CLIENT_IP']){ $onlineip=$_SERVER['HTTP_CLIENT_IP']; }
elseif($_SERVER['HTTP_X_FORWARDED_FOR']){ $onlineip=$_SERVER['HTTP_X_FORWARDED_FOR']; }
else{ $onlineip=$_SERVER['REMOTE_ADDR']; }

$onlineip = preg_replace("/^([\d\.]+).*/", "\\1", $onlineip);

if($action=="initdb")
{
	if( !$db168 || !$dbname || !$dbuser || !$dbhost )
	{
		showmsg("数据库主机,用户名,数据库,数据表前缀不能同时为空");
	}
	if(!ereg("^([a-z0-9_]+)$",$db168))
	{
		showmsg("数据表区分符必须是a-z0-9_");
	}
	if( !@mysql_connect($dbhost, $dbuser, $dbpw) )
	{
		showmsg("数据库连接失败，请确认<br><br>数据库帐号:<font color=red>{$dbuser}</font><br><br>数据库密码:<font color=red>{$dbpw}</font><br><br>是否正确,如有问题请向空间商咨询<br><br>");
	}
	if( !@mysql_select_db($dbname) )
	{
		if( !@mysql_query("CREATE DATABASE `$dbname`") )
		{
			showmsg("数据库虽然已连接成功，但数据库名<font color=red>{$dbname}</font>不对，连接不上去，请检查一下，是否正确,如有问题请向空间商咨询<br>");
		}
	}
	if( mysql_get_server_info() < '4.1' )
	{
		$dbcharset='';
	}
	$dbcharset && mysql_query("SET NAMES '$dbcharset'");

	if( mysql_get_server_info() > '5.0' )
	{
		mysql_query("SET sql_mode=''");
	}

	if(	!is_writable(PHP168_PATH."php168/mysql_config.php")	)
	{
		$msg[]=("php168/mysql_config.php 文件不可写，请改属性为0777");
	}
	if(	!is_writable(PHP168_PATH."upload_files")	)
	{
		$msg[]=("upload_files/ 目录不可写，请改属性为0777,其目录下的所有文件也要改为0777");
	}
	
	if(	!is_writable(PHP168_PATH."php168")	)
	{
		$msg[]=("php168/ 目录不可写，请改属性为0777,其目录下的所有文件也要改为0777");
	}
	
	if(	!is_writable(PHP168_PATH."php168/config.php")	)
	{
		$msg[]=("php168/config.php 文件不可写，请改属性为0777");
	}

	if(	!is_writable(PHP168_PATH."php168/mysql_config.php")	)
	{
		$msg[]=("php168/mysql_config.php 文件不可写，请改属性为0777");
	}

	if(	!is_writable(PHP168_PATH."cache")	)
	{
		$msg[]=("/cache/ 目录不可写，请改属性为0777,其目录下的所有文件也要改为0777");
	}

	if( is_array($msg) )
	{
		foreach($msg AS $value){
			$show.="|-----$value<br>";
		}
		showmsg("以下目录或文件不可写,请在ftp里修改其属性为0777,然后再刷新本页面,再进行下一步安装:<br><br><br>$show");
	}
	
	$writefile="

/**
* 以下变量需根据您的服务器说明档修改
*/
\$dbhost = '$dbhost';		// 数据库服务器(一般不必改)
\$dbuser = '$dbuser';			// 数据库用户名
\$dbpw = '$dbpw';					// 数据库密码
\$dbname = '$dbname';				// 数据库名
\$pre='$db168';				// 网站表区分符 

\$database = 'mysql';		// 数据库类型(一般不必改)
\$pconnect = 0;				// 数据库是否持久连接(一般不必改)
\$dbcharset = '$dbcharset';		// 数据库编码,如果出现网页乱码,你可以尝试改为gbk或latin1或utf8或big5,即可解决

	";
	writeover(PHP168_PATH . "php168/mysql_config.php",'<?php'.$writefile.'?>');

	//导入数据
	if(!$error){
		into_sql('');
		$step = 'adduser';
	}
}


elseif($action=="adduser")
{
	mysql_connect($dbhost,$dbuser,$dbpw);
	mysql_select_db($dbname);
	if( mysql_get_server_info() < '4.1' )
	{
		$dbcharset='';
	}
	$dbcharset && mysql_query("SET NAMES '$dbcharset'");

	if( mysql_get_server_info() > '5.0' )
	{
		mysql_query("SET sql_mode=''");
	}

	if(!$admin_name)
	{
		$show="<CENTER>帐号不能为空,<a href='#' onClick='javascript:history.go(-1);'>点击返回修改</a></CENTER>";
		require_once(PHP168_PATH.'install/make.htm');
		exit;
	}

	if(!$admin_pwd)
	{
		$show=("<CENTER>密码不能为空,<a href='#' onClick='javascript:history.go(-1);'>点击返回修改</a></CENTER>");
		require_once(PHP168_PATH.'install/make.htm');
		exit;
	}

	if($admin_pwd!=$admin_pwd2)
	{
		$show="<CENTER>两次输入密码不相同,<a href='#' onClick='javascript:history.go(-1);'>点击返回修改</a></CENTER>";
		require_once(PHP168_PATH.'install/make.htm');
		exit;
	}

	include(PHP168_PATH."php168/config.php");


	$timestamp=time();
	
	$admin_pwd=md5($admin_pwd);
	$webdb['passport_type']='';
	mysql_query("TRUNCATE TABLE {$db168}members");
	mysql_query("TRUNCATE TABLE {$db168}memberdata");
	mysql_query("TRUNCATE TABLE {$db168}memberdata_1");
	mysql_query("INSERT INTO {$db168}members (uid,username, password) VALUES ('1','$admin_name', '$admin_pwd')");
	mysql_query("INSERT INTO {$db168}memberdata (uid,username, groupid,money,regip,regdate, yz,lastvist,totalspace) VALUES ('1','$admin_name', '3','9999','$onlineip','$timestamp',1,'$timestamp','999999')");
	$rs[uid]=1;

	writeover(PHP168_PATH."php168/admin.php",'<?php	 '."\$admin_name='$admin_name';".' ?>');
	
	mysql_query("TRUNCATE TABLE {$db168}config");

	$PHP_SELF_TEMP=$_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	$PHP_SELF=$_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:$PHP_SELF_TEMP;
	$HTTP_HOST=$_SERVER['HTTP_HOST']?$_SERVER['HTTP_HOST']:$HTTP_SERVER_VARS['HTTP_HOST'];
	$WEBURL='http://'.$HTTP_HOST.$PHP_SELF;
	$webdb['www_url']=preg_replace("/(.*)\/([^\/]*)/is","\\1",$WEBURL);

	$webdb[passport_url]="$webdb[www_url]/$passportPath";
	
	$webdb['webmail']="$admin_email";

	$webdb[mymd5] = rand(100000, 99999999);
	
	$writefile="<?php
	";
	$SQL='';
	foreach( $webdb AS $key=>$value ){
		$value=addslashes($value);
		$SQL.="('$key', '$value', ''),";

		$writefile.="\$webdb['$key']='$value';\r\n";
	}
	$SQL=$SQL.";";
	$SQL=str_Replace("'),;","')",$SQL);
	mysql_query(" INSERT INTO `{$db168}config` VALUES  $SQL ");

	writeover(PHP168_PATH."php168/config.php",$writefile);

	
	@unlink(PHP168_PATH."cache/MysqlTime.txt");
	@unlink(PHP168_PATH."cache/admin_logs.php");
	@unlink(PHP168_PATH."cache/adminlogin_logs.php");
	@unlink(PHP168_PATH."cache/gather_list.begin_preg.php");
	@unlink(PHP168_PATH."cache/gather_morepage.php");
	@unlink(PHP168_PATH."cache/gather_show.begin_preg.php");
	@unlink(PHP168_PATH."cache/gather_show.endfile_preg.php");
	@unlink(PHP168_PATH."cache/gather_title.php");
	@unlink(PHP168_PATH."cache/copysina.php");
	
	$cache=readover(PHP168_PATH."php168/ad_cache.php");
	$cache=str_replace($default_weburl,"$webdb[www_url]/",$cache);
	writeover(PHP168_PATH."php168/ad_cache.php",$cache);

	$cache=readover(PHP168_PATH."php168/friendlink.php");
	$cache=str_replace($default_weburl,"$webdb[www_url]/",$cache);
	writeover(PHP168_PATH."php168/friendlink.php",$cache);
	
	$job='succee';
	
	if(strlen($db168)!=3){
		$query=@mysql_query("SELECT * FROM {$db168}label WHERE typesystem=1 ");
		while($rs=@mysql_fetch_array($query)){
			$rs[code]=preg_replace("/s:([\d]+):\"([^\"]+)\"/eis","strlen_lable('\\1','\\2')",$rs[code]);
			$rs[code]=addslashes($rs[code]);
			@mysql_query("UPDATE {$db168}label SET code='$rs[code]' WHERE lid='$rs[lid]' ");
		}
	}
	
	if($delete_all){
		mysql_query("TRUNCATE TABLE {$db168}article");
		mysql_query("TRUNCATE TABLE {$db168}article_content_100");
		mysql_query("TRUNCATE TABLE {$db168}article_content_101");
		mysql_query("TRUNCATE TABLE {$db168}article_content_102");
		mysql_query("TRUNCATE TABLE {$db168}article_content_103");
		mysql_query("TRUNCATE TABLE {$db168}article_content_104");
		mysql_query("TRUNCATE TABLE {$db168}article_content_105");
		mysql_query("TRUNCATE TABLE {$db168}reply");
		mysql_query("TRUNCATE TABLE {$db168}keyword");
		mysql_query("TRUNCATE TABLE {$db168}keywordid");
		mysql_query("TRUNCATE TABLE {$db168}special");
		mysql_query("TRUNCATE TABLE {$db168}spsort");
		mysql_query("TRUNCATE TABLE {$db168}special_comment");
		mysql_query("TRUNCATE TABLE {$db168}comment");
		mysql_query("TRUNCATE TABLE {$db168}guestbook");
		mysql_query("TRUNCATE TABLE {$db168}pm");
		mysql_query("TRUNCATE TABLE {$db168}sellad_user");
		mysql_query("TRUNCATE TABLE {$db168}upfile");
		mysql_query("TRUNCATE TABLE {$db168}vote_comment");
		mysql_query("TRUNCATE TABLE {$db168}shoporderuser");
		mysql_query("TRUNCATE TABLE {$db168}shoporderproduct");
		mysql_query("TRUNCATE TABLE {$db168}shopolpay");
		mysql_query("TRUNCATE TABLE {$db168}report");
		mysql_query("TRUNCATE TABLE {$db168}propagandize");
		mysql_query("TRUNCATE TABLE {$db168}olpay");
		mysql_query("TRUNCATE TABLE {$db168}moneycard");
		mysql_query("TRUNCATE TABLE {$db168}memberdata_1");
		mysql_query("TRUNCATE TABLE {$db168}count_stat");
		mysql_query("TRUNCATE TABLE {$db168}count_user");
		//deldir(PHP168_PATH."upload_files/article");
		//deldir(PHP168_PATH."upload_files/special");
	}

	if(is_writable("install")&&is_writable("install.php")){
		require_once(PHP168_PATH.'install/make.htm');
		//deldir("install");
		//deldir("install.php");
		//deldir(PHP168_PATH."bbs/data.sql");
	}else{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
		echo "<CENTER>整站安装成功,请手工删除安装文件install.php与目录install/才能进入主页</CENTER>";
	}
	exit;

}

require_once(PHP168_PATH.'install/make.htm');

function readover($filename,$method="rb"){
	if($handle=@fopen($filename,$method)){
		flock($handle,LOCK_SH);
		$filedata=fread($handle,filesize($filename));
		fclose($handle);
	}
	return $filedata;
}

function writeover($filename,$data,$method="rb+",$iflock=1){
	touch($filename);
	$handle=fopen($filename,$method);
	if($iflock){
		flock($handle,LOCK_EX);
	}
	$show=fputs($handle,$data);
	if($method=="rb+") ftruncate($handle,strlen($data));
	fclose($handle);
	return $show;
}

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

function into_sql($file){
	global $dbhost,$dbuser,$dbpw,$dbname, $db168, $dbcharset, $tbl_prefix;
	mysql_connect($dbhost, $dbuser, $dbpw);
	mysql_select_db($dbname);
	if( mysql_get_server_info() < '4.1' ){
		$dbcharset='';
	}
	$dbcharset && mysql_query("SET NAMES '$dbcharset'");
	if( mysql_get_server_info() > '5.0' ){
		mysql_query("SET sql_mode=''");
	}
	$tbl_prefix = $db168;
	$file2=readover($file);
	$file2 = str_replace("p8_", "$db168", $file2);

	$file2=explode("\n", $file2);
	$c1=count($file2);
	for($j=0;$j<$c1;$j++){
		$ck=substr($file2[$j],0,4);
		if( ereg("#",$ck)||ereg("--",$ck) ){
			continue;
		}
		$arr[]=$file2[$j];
	}

	$read=implode("\n",$arr); 
	$sql=str_replace("\r",'',$read);
	$detail=explode(";\n",$sql);
	$count=count($detail);
	for($i=0;$i<$count;$i++){
		$sql=str_replace("\r",'',$detail[$i]);
		$sql=str_replace("\n",'',$sql);
		$sql=trim($sql);
		if($sql){
			if(eregi("CREATE TABLE",$sql)){
				$sql=preg_replace("/DEFAULT CHARSET=([a-z0-9]+)/is","",$sql);
				$sql=preg_replace("/TYPE=MyISAM/is","ENGINE=MyISAM",$sql);
				if($dbcharset){
					$sql=str_replace("ENGINE=MyISAM"," ENGINE=MyISAM DEFAULT CHARSET=$dbcharset ",$sql);
				}
				if(mysql_get_server_info()<'4.1'){
					$sql=preg_replace("/ENGINE=MyISAM/is","TYPE=MyISAM",$sql);
				}
			}
			
		}

	}

	if( mysql_get_server_info() < '4.1' ) $tbl_setting = " TYPE=MyISAM ";
	else $tbl_setting = " ENGINE=MyISAM DEFAULT CHARSET=utf8 ";
	require_once(PHP168_PATH . 'install/sqldata.php');
	mysql_query($sql_data);
	
}

function deldir($path){
	if (file_exists($path)){
		if(is_file($path)){
			@unlink($path);
		} else{
			$handle = opendir($path);
			while ($file = readdir($handle)) {
				if (($file!=".") && ($file!="..") && ($file!="")){
					if (is_dir("$path/$file")){
						deldir("$path/$file");
					} else{
						@unlink("$path/$file");
					}
				}
			}
			closedir($handle);
			@rmdir($path);
		}
	}
}


function showmsg($msg){
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
	echo "错误提示:$msg<br><br>";
	echo '<input type="button" name="Submit" value="点击返回" id="post_bt" onclick="history.back(-1)">';
	exit;
}

function strlen_lable($num,$sring){
	global $db168;
	if(strstr($sring," $db168")&&eregi('SELECT ',$sring)){
		$num=strlen($sring);
	}	
	return "s:$num:\"$sring\"";
}
