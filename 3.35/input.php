<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
  include "conf.php";
?>
<html>   
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">       
    <meta name="大芒果" content="我的游戏世界">       
    <title> 
       <?php echo $servername;?>- 用户注册
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
            <div class="heading">输入密码</div>                
            <div class="inputwrap">
              <input type="password" name="pass" size="39"> 
            </div>             
            <div class="heading">重输密码</div>
            <div class="inputwrap">                 
              <input type="password" name="pass2" size="39">
            </div>                
            <div class="heading">游戏版本</div>
            <div class="inputwrap">                
              <select size="1" name="expansion">                  
                <!--option value="0" selected>经典版</option--!> 
                <!--option value="1">TBC</option--!>
                <option value="2">WLK</option>
                <!--option value="3">CTM</option--!> 
                <!--option value="4">MOP</option--!>               
              </select> 
            </div>                            
            <div class="heading">电子邮件</div> 
            <div class="inputwrap">               
              <input type="text" name="mail" size="39" value="@">
            </div>                             
            <div class="heading">
            <center>
              <br><br>
              <input type="submit" value="注册帐号" name="send" id="submit">
            </center> 
             <center>
              <li id="repass"><!--a href="dlq.rar" style="color:#222222">登陆器下载</a--!>
              <a href="repass.php" style="color:#222222">修改密码</a>
              <a href="fipass.php" style="color:#222222">找回密码</a>
              <!--a href="/bbs" style="color:#222222"> 进入论坛</a--!></li>
              </center>
            </div>                                    
          </form>                           
          <?php
          }
          else
          {
            echo "<h2>注册结果</h2>";
            if ($dbh)
            {
              $passed = true;          
              if (empty($_POST['login']) || empty($_POST['pass']) || empty($_POST['pass2']) || empty($_POST['mail']))
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
		$stmt = $dbh->query("SELECT Count(1) as cnt FROM account WHERE UPPER(`username`) = UPPER('$_POST[login]');");
		$account = $stmt->fetch(PDO::FETCH_BOTH);
                if ($account['cnt'] != 0)
                {
                  echo "<div class=\"error\">该名称的帐户已经存在！请尝试另一个。</div>"; 
                  echo "
                   <center>
                    <button class=\"homepage\" onclick=\"window.location.href='$page';\">重新填写</button>
                   </center> 
                  ";
                  $passed = false;
                }
                
                if (strlen($_POST['pass']) >= $minpasslenght)
                {    
                  if($_POST['pass'] !=$_POST['pass2'])
                  {
                    echo "<div class=\"error\">2次密码输入不匹配！请再输入一次！</div>"; 
                    echo "
                     <center>
                      <button class=\"homepage\" onclick=\"window.location.href='$page';\">重新填写</button>
                     </center> 
                    ";
                    $passed = false;
                  }
                }
                else
                {
                  echo "<div class=\"error\">密码太短，密码不能短于".$minpasslenght."个字符。</div>"; 
                  echo "
                   <center>
                    <button class=\"homepage\" onclick=\"window.location.href='$page';\">重新填写</button>
                   </center> 
                  ";
                  $passed = false;  
                }
                
		if (!filter_var($_REQUEST['mail'], FILTER_VALIDATE_EMAIL))
                {
                  echo "<div class=\"error\">你输入的电子邮件格式不对，请提供一个有效的电子邮件！</div>"; 
                  echo "
                   <center>
                    <button class=\"homepage\" onclick=\"window.location.href='$page';\">重新填写</button>
                   </center> 
                  ";
                  $passed = false;
                }
                else
                {
                  if ($oneaccpermail)
                  {
                    $stmt = $dbh->query("SELECT Count(1) as cnt FROM account WHERE email = '$_REQUEST[mail]';");
		    $mail = $stmt->fetch(PDO::FETCH_BOTH);
                    if ($mail['cnt'] != 0)
                    {
                      echo "<div class=\"error\">此电子邮件帐户已经存在！请用其他邮件地址注册。</div>"; 
                      echo "
                       <center>
                        <button class=\"homepage\" onclick=\"window.location.href='$page';\">重新填写</button>
                       </center> 
                      ";
                      $passed = false;
                    }  
                  }
                } 
      
                if($_POST['expansion'] > $expansion)
                {
                 $_POST['expansion'] = $expansion;
                 echo "<div class=\"warning\">此服务器没有你选择的".GetExpansionName($_POST['expansion'])."游戏版本</div>"; 
                 echo "
                  <center>
                   <button class=\"homepage\" onclick=\"window.location.href='$page';\">重新填写</button>
                  </center> 
                 ";
                }
                
                if($_POST['expansion'] < 0)
                {
                  $_POST['expansion'] = 0;
                  echo "<div class=\"warning\">你所选择的游戏版本不存在，请选择经典版。</div>"; 
                  echo "
                   <center>
                    <button class=\"homepage\" onclick=\"window.location.href='$page';\">重新填写</button>
                   </center> 
                  ";
                } 
          
                if ($passed)
		{
		  // Constants
                  $salt = random_bytes(32);
		  $g = gmp_init(7);
		  $N = gmp_init('894B645E89E1535BBDAD5B8B290650530801B18EBFBF5E8FAB3C82872A3E9BB7', 16);
		  // Calculate first hash
		  $h1 = sha1(strtoupper($_POST[login].':'.$_POST[pass]), TRUE);
		  // Calculate second hash
		  $h2 = sha1($salt.$h1, TRUE);
		  // Convert to integer (little-endian)
		  $h2 = gmp_import($h2, 1, GMP_LSW_FIRST);
		  // g^h2 mod N
		  $verifier = gmp_powm($g, $h2, $N);
		  // Convert back to a byte array (little-endian)
		  $verifier = gmp_export($verifier, 1, GMP_LSW_FIRST);
		  // Pad to 32 bytes, remember that zeros go on the end in little-endian!
		  $verifier = str_pad($verifier, 32, chr(0), STR_PAD_RIGHT);
		  //$new1 = bin2hex($salt);
		  //$new2 = bin2hex($verifier);
		  //echo "<div class=\"error\">salt:$new1</div>";
		  //echo "<div class=\"error\">verifier:$new2</div>";
                  $sql = "INSERT INTO `account` (`username`,`salt`, `verifier`, `email`, `expansion`) VALUES (UPPER('$_POST[login]'), '$salt', '$verifier', '$_POST[mail]', '$_POST[expansion]');";
		  $dbh->exec($sql);
		  $stmt=$dbh->query("SELECT Count(1) as cnt FROM account WHERE UPPER(`username`) = UPPER('$_POST[login]');");
		  $account=$stmt->fetch(PDO::FETCH_BOTH);
		  $dbh = null;
		  if($account['cnt'] != 0)//判断是否存在
		  {
                  	echo "<div class=\"done\">你已在".$servername."注册成功。<br><br><br><br></div>"; 
                  	echo "
                  	<div class=\"finished\"><b>注册成功.</b></div>
                  	<center>
                    	<button class=\"homepage\" onclick=\"window.location.href='$homepage';\">返回主页</button>
                  	</center>      
                  	";
		  }
                
                  else
                  {
                  	echo "
                  	<div class=\"failed\"><b>注册失败.</b></div>
                  	<center>
                    		<button class=\"homepage\" onclick=\"window.location.href='$page';\">重新注册</button> 
                  	</center>     
                  	";
                  }         
              	}
	      }
	    }
            else
	    {
		    $dbh = null;
               echo "<div class=\"error\">不能连接数据库，服务器离线。</div>"; 
               echo "
                <div class=\"failed\"><b>注册失败.</b></div>
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
