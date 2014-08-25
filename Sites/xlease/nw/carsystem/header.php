<?php
	session_start();
	include("../../config/config.php");
	if(!isset($_SESSION['languege']))
	{
		$lang="th";
	}
	else
	{
		$lang=$_SESSION['languege'];
	}
	$v_head = array();
	$i=0;
	if($lang=="th")
	{
		$query=pg_query("select \"word\" from carsystem.\"v_language_th\" where \"path_file\"='header.php'");
		while($rs=pg_fetch_assoc($query))
		{
			$v_head[$i]=$rs['word'];
			$i++;
		}
	}
	else if($lang=="en")
	{
		$query=pg_query("select \"word\" from carsystem.\"v_language_en\" where	\"path_file\"='header.php'");
		while($rs=pg_fetch_assoc($query))
		{
			$v_head[$i]=$rs['word'];
			$i++;
		}
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title>Untitled Document</title>
<link href="css/login.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="script/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="script/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="script/login.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('a.loginbox').click(function() {
		
                //Getting the variable's value from a link 
		var loginBox = $(this).attr('href');
		//$('#user-error').hide();

		//Fade in the Popup
		$(loginBox).fadeIn(300);
		
		//Set the center alignment padding + border see css style
		var popMargTop = ($(loginBox).height() + 24) / 2; 
		var popMargLeft = ($(loginBox).width() + 24) / 2; 
		
		$(loginBox).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		// Add the mask to body
		$('body').append('<div id="mask"></div>');
		$('#mask').fadeIn(300);
		
		return false;
	});
	
	// When clicking on the button close or the mask layer the popup closed
	$('a.close, #mask').live('click', function() { 
	  $('#mask , .login-popup').fadeOut(300 , function() {
		$('#mask').remove();  
	}); 
	return false;
	});	
	$('#spanheadregister a').click(function(){
		window.location.href = "register.php";
	});
	$('#findpostmenu a').click(function(){
		window.location.href = "showproduct.php";
	});
});
</script>
<script type="text/javascript">  
	   $(function(){  
		   $('#password').bind('keyup',function(e){ //on keydown for all textboxes  
			   if(e.keyCode==13)
			   {
				   $(".submitbutton").click();
			   }
				                    
		   });  
	   });  
</script>
<link href="css/header.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="divheadercontrainer">
	<div align="center">
		<div id="divheader">
        	<div id="divlogocontrainer">
            	<div id="divlogo"><img src="images/Head_web.png" height="62"></div>
            </div>
            <div id="divheadlogin">
            <?php
			if(!isset($_SESSION['username']))
			{
				echo "<span id=\"spanheadregister\"><a>$v_head[0]</a></span><span id=\"spanheadlogin\"><a href=\"#login-box\" class=\"loginbox\">$v_head[1]</a></span>";
			}
			else
			{
				echo "<span id=\"spanheadlogout\"><a href=\"logout.php\">$v_head[3]</a></span><span id=\"spanlogedin\"><a href=\"#\">".$_SESSION['showname']."</a></span>";
			}
			?>
        	</div>
        </div>
    </div>
</div>

<div id="divtopmenucontrainer">
    <div align="center">
        <div id="topmenu">
        <div id="divmenuhiligh"><a href="postproduct.php"><img src="images/01.png" /></a></div>
			<ul id="nav" class="dropdown">
            <li><a href="index.php"><?php echo $v_head[2]; ?></a></li>
            <li id="findpostmenu"><a><?php echo $v_head[4]; ?></a>
                <ul>
                    <li>
                        <div id="divsubtopmenutop"></div>
                    	<div id="divsubtopmenumiddle">
                            <table width="700" height="200" border="0" align="center" cellpadding="0" cellspacing="15">
                                <tr>
                                    <td width="151" height="125" align="center" valign="top">
                                        <div class="divbg-topmenu">
                                            <span class="spantopmenuhead"><?php echo $v_head[5]; ?></span><br>
                                            <?php
                                                $sql="select * from carsystem.\"productBrand\"";
                                                $dbquery=pg_query($sql);
                                                while($rs=pg_fetch_assoc($dbquery))
                                                {
                                                    echo "<a href=\"showproduct.php?brand=".$rs['productBrandName']."\"><span class=\"spantopmenulist\">".$rs['productBrandName']."</span></a>";
                                                }
                                            ?>
                                        </div>
                                    </td>
                                    <td width="151" height="125" align="center" valign="top">
                                        <div class="divbg-topmenu">
                                            <span class="spantopmenuhead"><?php echo $v_head[6]; ?></span><br>
                                            <a href="showproduct.php?cartype=ป้ายแดง"><span class="spantopmenulist"><?php echo $v_head[7]; ?></span></a>
                                            <a href="showproduct.php?cartype=มือสอง"><span class="spantopmenulist"><?php echo $v_head[8]; ?></span></a>
                                        </div>
                                    </td>
                                    <td width="151" height="125" align="center" valign="top">
                                        <div class="divbg-topmenu">
                                            <span class="spantopmenuhead"><?php echo $v_head[9]; ?></span>
                                            <a href="showproduct.php?carcolor=สีฟ้า"><span class="spantopmenulist"><?php echo $v_head[10]; ?></span></a>
                                            <a href="showproduct.php?carcolor=สีชมพู"><span class="spantopmenulist"><?php echo $v_head[11]; ?></span></a>
                                            <a href="showproduct.php?carcolor=สีขาว"><span class="spantopmenulist"><?php echo $v_head[12]; ?></span></a>
                                            <a href="showproduct.php?carcolor=สีเขียวเหลือง"><span class="spantopmenulist"><?php echo $v_head[13]; ?></span></a>
                                        </div>
                                    </td>
                                    <td width="151" height="125" align="center" valign="top">
                                        <div class="divbg-topmenu">
                                            <span class="spantopmenuhead"><?php echo $v_head[14]; ?></span>
                                            <a href="showproduct.php?carprice=0_10000"><span class="spantopmenulist"><?php echo $v_head[15]; ?></span></a>
                                            <a href="showproduct.php?carprice=10000_25000"><span class="spantopmenulist"><?php echo $v_head[16]; ?></span></a>
                                            <a href="showproduct.php?carprice=25000_40000"><span class="spantopmenulist"><?php echo $v_head[17]; ?></span></a>
                                            <a href="showproduct.php?carprice=40000_55000"><span class="spantopmenulist"><?php echo $v_head[18]; ?></span></a>
                                            <a href="showproduct.php?carprice=55000_1000000"><span class="spantopmenulist"><?php echo $v_head[19]; ?></span></a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
						</div>
                    <div id="divsubtopmenuboth"></div>
                    </li>
                </ul>
            </li>
				<li><a href="#"><?php echo $v_head[20]; ?></a></li>
                <li><a href="contact.php"><?php echo $v_head[21]; ?></a></li>
            </ul>
        </div>
    </div>
</div>
<div id="login-box" class="login-popup">
    <a href="#" class="close"><img src="images/Close.png" class="btn_close" title="Close Window" alt="Close" /></a>
    <form method="post" name="frm_signin" id="frm_signin" class="signin" action="">
        <fieldset class="textbox">
        	<div id="user-error"></div>
            <label class="username">
            <span><?php echo $v_head[22]; ?></span>
            <input id="username" name="username" value="<?php if(isset($_COOKIE['carSystemUsername'])&&$_COOKIE['carSystemUsername']!=""){ echo $_COOKIE['carSystemUsername']; } ?>" type="text" autocomplete="on" placeholder="Username">
            </label>
            <div id="pass-error"></div>
            <label class="password">
            <span><?php echo $v_head[23]; ?></span>
            <input id="password" name="password" value="" type="password" placeholder="Password">
          </label>
            <button class="submitbutton" type="button" onClick="login()"><?php echo $v_head[1]; ?></button>
            <p>
            <span><input type="checkbox" name="saveUserName" id="remember" value="1" <?php if(isset($_COOKIE['carSystemUsername'])&&$_COOKIE['carSystemUsername']!=""){ echo "checked=\"checked\""; } ?> /> จดจำชื่อผู้ใช้</span>
            <a class="forgot" href="#"><?php echo $v_head[24]; ?></a>
            </p>
            <div id="login_fail"></div>        
        </fieldset>
    </form>
</div>
</body>
</html>