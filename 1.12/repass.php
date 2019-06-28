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
       <?php echo $servername;?>- 修改密码
    </title>  
    <link rel="stylesheet" type="text/css" href="style.css" />   

  </head>       
  <body text="black" vlink="white" link="black" alink="white">                         
    <center>
      <div id="wrap">
<div id="welc"> 
       <li id="welc"><style="color:#222222">欢迎来到艾泽拉斯的世界！</a></li> 
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
            <div class="heading">输入旧密码</div>                
            <div class="inputwrap">
              <input type="password" name="pass" size="39"> 
            </div>             
            <div class="heading">输入新密码</div>
            <div class="inputwrap">                 
              <input type="password" name="pass1" size="39">
            </div>                
            <div class="heading">重输新密码</div>
            <div class="inputwrap">                 
              <input type="password" name="pass2" size="39">
            </div> 
                           
                
             <br> <br> <br> <br> <br>
             <div class="heading">
                    
             <center>
             <input type="submit" value="确定修改" name="send" id="submit" >
              </center>
                
              <center>
               <li id="repass"><a href="index.php" style="color:#222222">返回首页  </a></l>                  
               </center>
              </div>   
              
             
        
                                                      
          </form>                           
          <?php
          }
          else
  {
            echo "<h2>修改结果</h2>";
            if ($dbh)
            {
              $username = strtoupper($_POST['login']); 
              $pass = strtoupper($_POST['pass']);          
              $newpass = strtoupper($_POST['pass1']);
	      $sha1pass=sha1($username.':'.$pass);
              $sha1newpass = sha1($username.':'.$newpass);
              $passed = true; 

                     
              if (empty( $_POST['login']) || empty($_POST['pass']) || empty($_POST['pass1']) || empty($_POST['pass2']))
              {
                echo "<div class=\"error\">你必须填写所有字段</div>";
                echo "
		 <center>
                  <button class=\"homepage\" onclick=\"window.location.href='$page';\">重新填写</button>
                </center> 
		";
                $passed = false;
              }
              else
              {
		$stmt = $dbh->query("select * from account where username = '$username' and sha_pass_hash='".$sha1pass."'");
                $opass = $stmt->fetch(PDO::FETCH_BOTH);
                if(empty($opass))
                {  
                  echo "<div class=\"error\">用户名或旧密码,填写不正确,请重新输入</div>"; 
                  $passed = false;
                }
                
                if (strlen($_POST['pass1']) >= $minpasslenght)
                {    
                  if($_POST['pass1'] !=$_POST['pass2'])
                  {
                    echo "<div class=\"error\">2次密码输入不匹配！请再输入一次！</div>"; 
                    $passed = false;
                  }
                }
                else
                {
                  echo "<div class=\"error\">密码太短，密码不能短于".$minpasslenght."个字符。</div>"; 
                  $passed = false;  
                }  
            
                if ($passed)
                {
                  $sql = "UPDATE account SET sessionkey=0 , v=0 , s=0 , sha_pass_hash = '".$sha1newpass."' WHERE username = '$username';";
		  $dbh->exec($sql);
		  $dbh = null;
                  echo "<div class=\"done\">你已经成功修改密码.<br><br><br><br></div>"; 
                  echo "
                  <div class=\"finished\"><b>成功修改密码.</b></div>
                  <center>
                    <button class=\"homepage\" onclick=\"window.location.href='$homepage';\">返回主页</button>
                  </center>      
                  ";
                }
                else
                {
                  echo "
                  <div class=\"failed\"><b>修改失败.</b></div>
                  <center>
                    <button class=\"homepage\" onclick=\"window.location.href='$page';\">重新修改</button> 
                  </center>     
                  ";
                }         
              }
            }
            else
            {
               echo "<div class=\"error\">不能连接数据库，服务器离线。</div>"; 
               echo "
                <div class=\"failed\"><b>修改失败.</b></div>
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
