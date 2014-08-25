<?php
session_start();
if(isset($_SESSION['username']))
{
	echo "<link rel=\"stylesheet\" href=\"css/fix_menu.css\" type=\"text/css\">";
	echo "<script type=\"text/javascript\" src=\"script/jquery-1.7.2.js\"></script>";
	echo "<script type=\"text/javascript\">";
	echo "var state = 0;";
	echo "$(document).ready(function(){
			  $('#show_fix_menu').hide();
		 	  $('#hide_fix_menu').click(function(){
			  		$('#fix-nav').hide();
		  	  });
		  });
		  ";
	echo "</script>";
	echo "<div id=\"fix-nav\">";
	echo "<ul>";
	echo "<li><a class=\"top\" href=\"#divheadercontrainer\"><span>บนสุด</span></a></li>";
	echo "<li><a class=\"bottom\" href=\"#divfooter\"><span>ล่างสุด</span></a></li>";
	echo "<li><a href=\"postproduct.php\">เขียนประกาศ</a></li>";
	echo "<li><a>เปลี่ยนรหัสผ่าน</a></li>";
	echo "<li><a href=\"logout.php\">ออกจากระบบ</a></li>";
	echo "</ul>";
	echo "</div>";
	echo "<div id=\"show_fix_menu\">";
	echo "<img src=\"images/Menu.PNG\" />";
	echo "</div>";
}
?>
