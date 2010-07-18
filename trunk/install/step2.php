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

<table width="100%" height="100%" border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#E6E6E6" class="style9">
  <tr>
    <td> 
	  <form name="form3" method="post" action="?action=adduser">
      <table width="100%" cellspacing="0" cellpadding="0">
          <tr> 
            <td colspan="2"> 
              <div align="center"><b><font color="#FF0000" size="6">请设置管理员帐号密码</font></b></div>
            </td>
          </tr>
          <tr> 
            <td height="4" align="right"><font color="#FF0000">超级管理员帐号</font>：</td>
            <td height="4"> 
              <input type="text" name="admin_name" value="<?php echo $default_admin; ?>" <?php echo $readonlymsg;?>>
            </td>
          </tr>
          <tr> 
            <td height="4"> 
              <div align="right"><font color="#0000FF">超级管理员密码</font>：</div>
            </td>
            <td height="4"> 
              <input type="password" name="admin_pwd">
            </td>
          </tr>
          <tr> 
            <td height="2"> 
              <div align="right"><font color="#0000FF">重复输入管理密码</font>：</div>
            </td>
            <td height="2"> 
              <input type="password" name="admin_pwd2">
            </td>
          </tr>
          <tr> 
            <td height="0"> 
              <div align="right">电子邮箱：</div>
            </td>
            <td height="0"> 
              <input type="text" name="admin_email" value="admin@admin.com">
            </td>
          </tr>
          <tr>
            <td height="1" align="right">是否清空测试数据：</td>
            <td height="1">
              <input type="radio" name="delete_all" value="0" checked>
              保留（推荐） 
              <input type="radio" name="delete_all" value="1">
              清空（不推荐） </td>
          </tr>
          <tr> 
            <td colspan="2" height="23"> 
              &nbsp;
            </td>
          </tr>
          <tr> 
            <td colspan="2"> 
              <div align="center"> 
                <input type="submit" name="Submit22" value="继续下一步">
              </div>
            </td>
          </tr>
      </table>
	  </form>
    </td>
  </tr>
</table>
</body>
</html>
