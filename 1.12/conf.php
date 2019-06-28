<?php
################################################################################
# 版本：v2.0                                                                   #
# 功能：注册页配置文件                                                         #
# 作者：Cici                                                                   #
# 修改者：小得得                                                               #
# 添加功能：登陆器下载，论坛，修改密码，找回密码，gmlevel设置                                                                  #
#                             请编辑以下配置参数                               #
################################################################################
$expansion = 0;                        // 默认游戏版本 (0 = 经典版, 1 = TBC, 2 = WLK, 3 =CTM, 4 = MOP)
$minpasslenght = 6;                   // 最短密码长度限制
$dbhost = '127.0.0.1';                // 数据库地址   = localhost (127.0.0.1)
$dbport = '3306';                   // 数据库端口   = 3306
$dbuser = 'mangos';                     // 数据库用户名 = root
$dbpass = 'mangos';                     // 数据库密码
$dbname = 'realmd';                   // 帐号数据库
$servername = 'MaNgos'; 		 // 服务器名称
$ip = "127.0.0.1";                    // 服务器地址
$oneaccpermail = true;                // 是否只允许电子邮件注册? 'false' 取消检测
$dlq = "/dlq.rar";                     // 网站首页
$homepage = "index.php";              // 网站首页
$bbs = "/bbs";                        // 网站论坛

################################################################################
#                           请不要修改以下数据                                 #
################################################################################

error_reporting(0);
$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", "$dbuser", "$dbpass");
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function GetExpansionName($expansion)
{
  switch ($expansion)
  {
  	case 0:
		return "经典版";
        case 1:
		return "TBC";
	case 2:
		return "WLK";
	case 3:
		return "CTM";
	case 4:
		return "MOP";
	default:
		return "未知版本";
  }
}
?>
