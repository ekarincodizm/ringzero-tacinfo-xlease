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

/* this compare captcha's number from POST and SESSION */
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['captcha']) && $_POST['captcha'] == $_SESSION['captcha'])
	{
		$username=$_POST['tbxuser_regis'];
		$password=md5($_POST['tbxpassword_regis']);
		$showname=$_POST['tbxshowname_regis'];
		$email=$_POST['tbxemail'];
		$mobile=$_POST['tbxmobile'];
		$titlename=$_POST['slctbefor'];
		$firstname=$_POST['tbxfullname'];
		$lastname=$_POST['tbxlastname'];
		$birthday=$_POST['tbxbirthday'];
		$address=$_POST['tbxaddress'];
		$province=$_POST['slctprovince'];
		$district=$_POST['slctdistrict'];
		$zipcode=$_POST['tbxzipcode'];
		$telephone=$_POST['tbxphone'];
		$faxnumber=$_POST['tbxfax'];
		$regisdate=date("Y-m-d H:i:s");
		$sql="insert into carsystem.\"members\"(\"username\",\"password\",\"showname\",\"titlename\",\"firstname\",\"lastname\",\"birthday\",\"address\",\"district\",\"province\",\"zipcode\",\"email_address\",\"mobilephone\",\"telephone\",\"faxnumber\",\"regisDate\",\"status\",\"usertype\") values('$username','$password','$showname','$titlename','$firstname','$lastname','$birthday','$address','$district','$province','$zipcode','$email','$mobile','$telephone','$faxnumber','$regisdate','0','0')";
		if(pg_query($sql))
		{
			echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
			echo "<head>";
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
			echo "<title>Untitled Document</title>";
			echo "<link href=\"css/register-result.css\" rel=\"stylesheet\" type=\"text/css\" />";
			echo "<script type=\"text/javascript\" src=\"script/jquery-1.7.2.min.js\"></script>";
			echo "<script type=\"text/javascript\">";
			echo "var settimmer = 0;";
			echo "$(document).ready(function(){";
			echo "window.setInterval(function() {";
			echo "var timeCounter = $(\"#countdown\").html();";
			echo "var updateTime = eval(timeCounter)- eval(1);";
			echo "$(\"#countdown\").html(updateTime);";
	
			echo "if(updateTime == 0){";
			echo "window.location = (\"index.php\");";
			echo "}";
			echo "}, 1000);";
			echo "});";
			echo "</script>";
			echo "</head>";
	
			echo "<body>";
			echo "<div id=\"divresult-box\">";
			echo "<span id=\"spanresult\">สมัครสมาชิกเรียบร้อย</span>";
			echo "<span id=\"countdown\">5</span>";
			echo "<span id=\"message\">กรุณารอสักครู่ ระบบกำลังพาท่านกลับไปยังหน้าหลัก</span>";
			echo "</div>";
			echo "</body>";
			echo "</html>";
		}
		else
		{
			echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
			echo "<head>";
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
			echo "<title>Untitled Document</title>";
			echo "<link href=\"css/register-result.css\" rel=\"stylesheet\" type=\"text/css\" />";
			echo "<script type=\"text/javascript\" src=\"script/jquery-1.7.2.min.js\"></script>";
			echo "<script type=\"text/javascript\">";
			echo "var settimmer = 0;";
			echo "$(document).ready(function(){";
			echo "window.setInterval(function() {";
			echo "var timeCounter = $(\"#countdown\").html();";
			echo "var updateTime = eval(timeCounter)- eval(1);";
			echo "$(\"#countdown\").html(updateTime);";
	
			echo "if(updateTime == 0){";
			echo "window.location = (\"index.php\");";
			echo "}";
			echo "}, 1000);";
			echo "});";
			echo "</script>";
			echo "</head>";
	
			echo "<body>";
			echo "<div id=\"divresult-box\">";
			echo "<span id=\"spanresult\">สมัครสมาชิกล้มเหลว  กรุณาติดต่อผู้ดูแลระบบ</span>";
			echo "<span id=\"countdown\">5</span>";
			echo "<span id=\"message\">กรุณารอสักครู่ ระบบกำลังพาท่านกลับไปยังหน้าหลัก</span>";
			echo "</div>";
			echo "</body>";
			echo "</html>";
		}
		unset($_SESSION['captcha']); /* this line makes session free, we recommend you to keep it */
	} 
elseif($_SERVER['REQUEST_METHOD'] == "POST" && !isset($_POST['captcha']))
	{
		echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
		echo "<head>";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
		echo "<title>Untitled Document</title>";
		echo "<link href=\"css/register-result.css\" rel=\"stylesheet\" type=\"text/css\" />";
		echo "<script type=\"text/javascript\" src=\"script/jquery-1.7.2.min.js\"></script>";
		echo "<script type=\"text/javascript\">";
		echo "var settimmer = 0;";
		echo "$(document).ready(function(){";
		echo "window.setInterval(function() {";
		echo "var timeCounter = $(\"#countdown1\").html();";
		echo "var updateTime = eval(timeCounter)- eval(1);";
		echo "$(\"#countdown1\").html(updateTime);";

		echo "if(updateTime == 0){";
		echo "window.location = (\"register.php\");";
		echo "}";
		echo "}, 1000);";
		echo "});";
		echo "</script>";
		echo "</head>";

		echo "<body>";
		echo "<div id=\"divresult-box\">";
		echo "<span id=\"spanresult1\">คุณพิสูจน์ตัวตนไม่ผ่าน</span>";
    	echo "<span id=\"countdown1\">5</span>";
    	echo "<span id=\"message1\">กรุณารอสักครู่ ระบบกำลังพาท่านกลับไปยังหน้าสมัครสมาชิก</span>";
    	echo "</div>";
		echo "</body>";
		echo "</html>";
	}
/* in case that form isn't submitted this file will create a random number and save it in session */
else
	{
		$rand = rand(0,4);
		$_SESSION['captcha'] = $rand;
		echo $rand;
	}
?>