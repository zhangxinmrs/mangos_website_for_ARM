<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
  include "conf.php";
?>
<html>   
  <head>
    <link rel="shortcut icon" href="images/mangosd.ico"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">       
    <meta name="大芒果" content="我的游戏世界">       
    <title> 
       <?php echo $servername;?>- 修改gmlevel
    </title>  
    <link rel="stylesheet" type="text/css" href="style.css" />   

  </head>       
  <body text="black" vlink="white" link="black" alink="white">                         
    <center>
      <div id="wrap">
<div id="welc"> 
       <li id="welc"><style="color:#222222">欢迎来到艾泽拉斯的世界！</a></li> 
<li id="note"><style="color:#222222">请注意:权限为直接修改，请自行将gm.php改名进行防护</a></li> 
     </div>
        <div id="innerwrap"> 
          <?php 
          if (!isset($_POST['sent']))
          {
          ?>                                
          <form method="post" action="">
            <input type="hidden" name="sent" value="true">                           
            <div class="heading">用户帐号</div>
            <div class="inputwrap">               
              <input type="text" name="login" size="39">
            </div>                                         
            <div class="heading">输入要设置的GM等级</div>
            <div class="inputwrap">                
              <select size="1" name="gmlevel">                  
                <option value="0" selected>普通玩家(0)</option> 
                <option value="1">活动管理员(1)</option>
                <option value="2">游戏管理员(2)</option>
                <option value="3">超级管理员(3)</option>
                <option value="4">游戏拥有者(4)</option>                  
              </select> 
            </div>        
                            
            
                           
                
             <br> <br> <br> <br> <br>
             <div class="heading">
                    
             <center>
             <input type="submit" value="确定设置" name="send" id="submit" >
              </center>
                
              <center>
               <li id="repass"><a href="index.php" style="color:#222222">返回首页  </a></l>                   </center>
              </div>   
              
             
        
                                                      
          </form>                           
          <?php
          }
          else
  {
            echo "<h2>设置结果</h2>";
            if ($dbh)
            {
              $username = strtoupper($_POST['login']);
              $gmlevel = $_POST['gmlevel'];
              $passed = true;       
              if (empty( $_POST['login']))
              {
                echo "<div class=\"error\">你必须填写要设置的账户名称</div>";
	        echo "
		 <center>
                  <button class=\"homepage\" onclick=\"window.location.href='$page';\">重新填写</button>
                </center> 
		";
                $passed = false;
              }
			 
              else
	
	
              {
		$stmt = $dbh->query("select * from account where username = '$username'");
                $olevel = $stmt->fetch(PDO::FETCH_BOTH);
                if(empty($olevel))
                {  
                  echo "<div class=\"error\">你输入的账户不存在</div>"; 
                  $passed = false;
                }
                
               
                if ($passed)
                {
                  $sql = "UPDATE account SET gmlevel='$gmlevel' WHERE username = '$username';";
                  $dbh->exec($sql);
                  $dbh = null;
                  echo "<div class=\"done\">你已经成功设置GM等级.<br><br><br><br></div>"; 
                  echo "
                  <div class=\"finished\"><b>成功设置GM等级.</b></div>
                  <center>
                    <button class=\"homepage\" onclick=\"window.location.href='$homepage';\">返回主页</button>
                  </center> 
                      <center>
                    <button class=\"homepage\" onclick=\"window.location.href='gm.php';\">继续设置</button>
                  </center>     
                  ";
                }
                else
                {
                  echo "
                  <div class=\"failed\"><b>设置失败.</b></div>
                  <center>
                    <button class=\"homepage\" onclick=\"window.location.href='$page';\">重新设置</button> 
                  </center>     
                  ";
                }         
              }
            }
            else
            {
               echo "<div class=\"error\">不能连接数据库，服务器离线。</div>"; 
               echo "
                <div class=\"failed\"><b>设置失败.</b></div>
                <center>
                  <button class=\"homepage\" onclick=\"window.location.href='$page';\">返回主页</button>
                </center>      
                ";
            } 
          } 
          ?>
        </div>
      </div>                                                    
    </center>                       
  </body>
</html>
