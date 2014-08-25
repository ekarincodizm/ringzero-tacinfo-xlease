<?php
session_start();
include("../../config/config.php");
$idmenu = $_GET['men'];
$now_date = date("Y-m-d H:i:s");

$sql = pg_query("SELECT * FROM f_menu_warning where \"fmenuwarID\" = (SELECT MAX(\"fmenuwarID\") FROM f_menu_warning where id_menu = '$idmenu' and e_time > '$now_date' and appstatus = '1') ");
  
$sqlre = pg_fetch_array($sql);
$title1 = $sqlre['detail_warning'];


$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$end_time = $sqlre['e_time'];


$timecal = (strtotime($end_time) - strtotime($datenow));


if($title1 != ""){
	$title = $title1;
}else{
	$title = "เมนูนี้กำลังอยู่ในระหว่างการปรับปรุง";
}

if($datenow < $end_time){

	echo "<center><img src=\"images/pic.gif\" width=\"350\" height=\"250\">
	<br><br><h2><p>$title<p></h2></center>";
}

?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> แจ้งเมนูอยู่ระหว่างปรับปรุง </TITLE>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
</HEAD>

<BODY>
<body onLoad="begintimer()">
<script language="">
var limit="<?php echo $timecal ?>";
if (document.images){
var parselimit=limit.split(":")
parselimit=<?php echo timecal; ?>;
}
function begintimer(){
	if (!document.images)
	return
	if(parselimit==1){
		alert("การปิดปรับปรุงสิ้นสุดลง กรุณาปิดหน้าต่างนี้และเปิดเมนูขึ้นใหม่เพื่อทำงานตามปกติ");
		window.close();
	}else{
		parselimit-=1
		curday=Math.floor(parselimit/86400)
		curhour=Math.floor(parselimit/3600)%24
		curmin=Math.floor(parselimit/60)%60
		cursec=parselimit%60
		if(curday!=0){
			curtime="<center><h1>กรุณากลับเข้ามาอีกครั้งใน <font color=red> "+curday+" </font>วัน <font color=red> "+curhour+" </font>ชั่วโมง กับ<font color=red> "+curmin+" </font>นาที กับ <font color=red>"+cursec+" </font>วินาที </h1></center>"
		}else if(curhour!=0){
			curtime="<center><h1>กรุณากลับเข้ามาอีกครั้งใน <font color=red> "+curhour+" </font>ชั่วโมง กับ<font color=red> "+curmin+" </font>นาที กับ <font color=red>"+cursec+" </font>วินาที </h1></center>"
		}else if(curmin!=0 && curhour==0){
			curtime="<center><h1>กรุณากลับเข้ามาอีกครั้งใน <font color=red> "+curmin+" </font>นาที กับ <font color=red>"+cursec+" </font>วินาที </h1></center>"
		}else{
			if(cursec==0){
				alert('การปิดปรับปรุงสิ้นสุดลง');
			}else{
				curtime="<center><h1>กรุณากลับเข้ามาอีกครั้งใน <font color=red>"+cursec+" </font>วินาที </h1></center>"
			}
		}	
			document.getElementById('dplay').innerHTML = curtime;
			setTimeout("begintimer()",1000)
			
	}
}
//-->
</script>
<div id=dplay ></div>
<center><font color="red"><h3>*หากต้องการใช้หรือมีเหตุจำเป็นเร่งด่วน กรุณาติดต่อ IT ที่ โทร 4500#</h3></font></center>
</BODY>
</HTML>