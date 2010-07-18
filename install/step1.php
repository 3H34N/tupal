<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>网站程序安装</title>
<style type="text/css">
<!--
body {margin: 0px;background-color: #E6E6E6;}
td{
	font-size: 9pt;
}
.style1 {
	color: #FFFFFF;
	font-size: 9pt;
	line-height: 16px;
}
.style5 {font-size: 18px; font-family: "黑体";}
.style9 {
	font-size: 9pt;
	color: #666666;
	line-height: 16px;
}
.style11 {
	color: #990000;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
}
.style13 {font-family: Arial, Helvetica, sans-serif}
.style17 {font-size: 18px; font-family: "黑体"; color: #990000; }
.style18 {color: #000000}
-->
</style>
</head>
<body>
<pre>
<?php if($errormsg) print_r($errormsg); ?>
</pre>

<table width="100%" height="100%" border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#E6E6E6" class="style9">
  <tr>
    <td> 
      <font color="#0000FF">注：MYSQL数据库帐号、数据库密码、所使用的数据库请向空间商咨询或购买。 </font> 
      <form method="post" action="?action=initdb"> 
        <table width="98%" cellspacing="0" cellpadding="4">
          <tr> 
            <td width="15%">数据库主机:</td>
            <td width="84%"> 
              <input type="text" name="dbhost" value="<?php echo $dbhost;?>">
              <font color="#0000FF" size="2">(一般不必修改)</font> </td>
          </tr>
          <tr> 
            <td width="15%">数据库帐号:</td>
            <td width="84%"> 
              <input type="text" name="dbuser" value="<?php echo $dbuser;?>">
            </td>
          </tr>
          <tr> 
            <td width="15%">数据库密码:</td>
            <td width="84%"> 
              <input type="text" name="dbpw" value="<?php echo $dbpw;?>">
            </td>
          </tr>
          <tr> 
            <td width="15%">数据库名称:</td>
            <td width="84%"> 
              <input type="text" name="dbname" value="<?php echo $dbname;?>">
            </td>
          </tr>
          <tr> 
            <td width="15%">数据表前缀:</td>
            <td width="84%"> 
              <input type="text" name="db168" value="p8_">
              <font color="#0000FF" size="2">建议不要修改,方便以后升级维护，并减少出错的机会</font>
			  </td>
          </tr>
          <tr> 
            <td width="15%">
			    <input type="hidden" name="dbcharset" value="utf8"/>
            </td>
            <td width="84%"> 
              <input type="submit" name="Submit" value="点击下一步">
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>
</body>
</html>
